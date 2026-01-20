<?php
/**
 * ProfileEditor - Profile editing logic
 *
 * Handles loading, validating, and updating existing profiles
 */

require_once __DIR__ . '/Config.php';
require_once __DIR__ . '/Security.php';
require_once __DIR__ . '/Logger.php';
require_once __DIR__ . '/FileUpdater.php';
require_once __DIR__ . '/ProfileGenerator.php';
require_once __DIR__ . '/Exceptions/ProfileException.php';

class ProfileEditor {

    /**
     * Validate that a profile exists and can be edited
     *
     * @param string $profileName Profile to validate
     * @return bool True if valid
     * @throws ProfileException If invalid
     */
    public static function validateProfileForEdit(string $profileName): bool {
        // Check if profile exists in Config::PROFILES
        if (!Config::isValidProfile($profileName)) {
            throw new ProfileException(
                "Profile '{$profileName}' does not exist in configuration",
                "Profile not found in system configuration.",
                ['profile' => $profileName]
            );
        }

        // Verify JSON file exists
        $jsonPath = Config::getControlsPath($profileName);
        if (!file_exists($jsonPath)) {
            throw new ProfileException(
                "JSON file not found for profile '{$profileName}': {$jsonPath}",
                "Profile data file is missing.",
                ['profile' => $profileName, 'path' => $jsonPath]
            );
        }

        return true;
    }

    /**
     * Load existing profile data into wizard-compatible structure
     *
     * @param string $profileName Profile to load
     * @return array Structure with 'metadata' and 'domains'
     * @throws ProfileException If load fails
     */
    public static function loadProfile(string $profileName): array {
        // Validate first
        self::validateProfileForEdit($profileName);

        $jsonPath = Config::getControlsPath($profileName);

        // Read JSON file
        $jsonContent = file_get_contents($jsonPath);
        if ($jsonContent === false) {
            throw ProfileException::fileWriteFailed($jsonPath, 'Could not read file');
        }

        // Parse JSON
        $jsonData = json_decode($jsonContent, true);
        if ($jsonData === null) {
            throw new ProfileException(
                "Failed to parse JSON for profile '{$profileName}': " . json_last_error_msg(),
                "Profile data file is corrupted.",
                ['profile' => $profileName, 'error' => json_last_error_msg()]
            );
        }

        // Get metadata from Config
        $configProfile = Config::PROFILES[$profileName];

        // Convert JSON structure to wizard format
        $wizardData = [
            'metadata' => [
                'profile_name' => $profileName,
                'display_name' => $configProfile['display_name'],
                'enabled' => $configProfile['enabled']
            ],
            'domains' => []
        ];

        // Parse each domain (Domain-1 through Domain-7)
        for ($i = 1; $i <= 7; $i++) {
            $domainKey = "Domain-{$i}";
            $domainData = $jsonData[$domainKey] ?? [];

            $wizardData['domains'][$i] = [
                'title' => $domainData['title'] ?? "Domain {$i}",
                'overview' => $domainData['overview'] ?? '',
                'capabilities' => []
            ];

            // Parse 8 capabilities per domain
            for ($cap = 1; $cap <= 8; $cap++) {
                $wizardData['domains'][$i]['capabilities'][$cap] = [
                    'name' => $domainData[(string)$cap] ?? "Capability {$cap}",
                    'summary' => $domainData["{$cap}-summary"] ?? '',
                    'tier' => $domainData["{$cap}-tier"] ?? 'Foundation',
                    'recommendation' => $domainData["{$cap}-recommendation"] ?? ''
                ];
            }
        }

        Logger::info('Profile loaded for editing', [
            'profile' => $profileName,
            'path' => $jsonPath
        ]);

        return $wizardData;
    }

    /**
     * Update an existing profile with new data
     *
     * @param string $profileName Profile to update (must match wizardData profile_name)
     * @param array $wizardData Complete wizard data structure
     * @return array Result information
     * @throws ProfileException If update fails
     */
    public static function updateProfile(string $profileName, array $wizardData): array {
        $wizardProfileName = $wizardData['metadata']['profile_name'] ?? '';

        // Validate that profile name hasn't changed
        if ($profileName !== $wizardProfileName) {
            throw new ProfileException(
                "Profile name mismatch: expected '{$profileName}', got '{$wizardProfileName}'",
                "Profile name cannot be changed during editing.",
                ['expected' => $profileName, 'provided' => $wizardProfileName]
            );
        }

        // Validate profile exists
        self::validateProfileForEdit($profileName);

        $displayName = $wizardData['metadata']['display_name'] ?? $profileName;
        $enabled = $wizardData['metadata']['enabled'] ?? false;

        // Check if Config.php needs updating (display_name or enabled changed)
        $currentConfig = Config::PROFILES[$profileName];
        $configNeedsUpdate = (
            $currentConfig['display_name'] !== $displayName ||
            $currentConfig['enabled'] !== $enabled
        );

        $rollbackActions = [];

        try {
            Logger::info('Starting profile update', [
                'profile' => $profileName,
                'config_update_needed' => $configNeedsUpdate
            ]);

            // Step 1: Build new JSON structure
            $jsonData = ProfileGenerator::buildProfileJSON($wizardData);

            // Step 2: Backup and write JSON file
            $jsonPath = Config::getControlsPath($profileName);
            $jsonBackup = $jsonPath . '.backup.' . date('YmdHis');

            if (!copy($jsonPath, $jsonBackup)) {
                throw ProfileException::backupFailed($jsonPath);
            }

            $rollbackActions[] = function() use ($jsonBackup, $jsonPath) {
                if (file_exists($jsonBackup)) {
                    @copy($jsonBackup, $jsonPath);
                    Logger::info('Rolled back: Restored JSON file', ['backup' => $jsonBackup]);
                }
            };

            // Write to temp file first for atomic update
            $tempPath = $jsonPath . '.tmp';
            $jsonString = json_encode(
                $jsonData,
                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
            );

            if ($jsonString === false) {
                throw new ProfileException(
                    'JSON encoding failed: ' . json_last_error_msg(),
                    'Failed to generate profile data.',
                    ['profile' => $profileName, 'error' => json_last_error_msg()]
                );
            }

            if (file_put_contents($tempPath, $jsonString) === false) {
                throw ProfileException::fileWriteFailed($tempPath, 'Could not write temporary file');
            }

            if (!rename($tempPath, $jsonPath)) {
                @unlink($tempPath);
                throw ProfileException::fileWriteFailed($jsonPath, 'Could not finalize file');
            }

            Logger::info('Profile JSON updated', [
                'profile' => $profileName,
                'path' => $jsonPath
            ]);

            // Step 3: Update Config.php if needed
            if ($configNeedsUpdate) {
                $configBackup = FileUpdater::updateConfigEntry($profileName, $displayName, $enabled);
                $rollbackActions[] = function() use ($configBackup) {
                    FileUpdater::restoreBackup($configBackup);
                    Logger::info('Rolled back: Restored Config.php', ['backup' => $configBackup]);
                };

                Logger::info('Config.php updated during profile edit', [
                    'profile' => $profileName,
                    'display_name' => $displayName,
                    'enabled' => $enabled
                ]);
            }

            // Success - clean up backups
            @unlink($jsonBackup);

            // Clear opcode cache so changes are immediately visible
            if (function_exists('opcache_reset')) {
                opcache_reset();
                Logger::info('Cleared opcode cache after profile update');
            }

            Logger::info('Profile updated successfully', [
                'profile' => $profileName,
                'display_name' => $displayName,
                'enabled' => $enabled
            ]);

            return [
                'success' => true,
                'profile_name' => $profileName,
                'display_name' => $displayName,
                'message' => "Profile '{$displayName}' updated successfully!"
            ];

        } catch (Exception $e) {
            // Rollback all changes in reverse order
            Logger::error('Profile update failed, initiating rollback', [
                'profile' => $profileName,
                'error' => $e->getMessage()
            ]);

            foreach (array_reverse($rollbackActions) as $rollback) {
                try {
                    $rollback();
                } catch (Exception $rollbackError) {
                    Logger::error('Rollback action failed', [
                        'error' => $rollbackError->getMessage()
                    ]);
                }
            }

            // Re-throw the original exception
            throw $e;
        }
    }
}
