<?php
/**
 * ProfileGenerator - Core profile creation logic
 *
 * Handles profile validation, JSON generation, and file orchestration
 */

require_once __DIR__ . '/Config.php';
require_once __DIR__ . '/Security.php';
require_once __DIR__ . '/Logger.php';
require_once __DIR__ . '/FileUpdater.php';
require_once __DIR__ . '/Exceptions/ProfileException.php';

class ProfileGenerator {

    /**
     * Validate profile name for uniqueness and format
     *
     * @param string $name Profile name to validate
     * @return bool True if valid
     * @throws ProfileException If invalid
     */
    public static function validateProfileName(string $name): bool {
        // Whitelist: alphanumeric + underscores only
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $name)) {
            throw ProfileException::invalidName(
                $name,
                'Profile name must contain only letters, numbers, and underscores'
            );
        }

        // Check minimum length
        if (strlen($name) < 2) {
            throw ProfileException::invalidName($name, 'Profile name must be at least 2 characters');
        }

        // Check maximum length
        if (strlen($name) > 50) {
            throw ProfileException::invalidName($name, 'Profile name must be 50 characters or less');
        }

        // Check against existing profiles in Config::PROFILES
        if (Config::isValidProfile($name)) {
            throw ProfileException::profileExists($name);
        }

        // Check if JSON file already exists
        $jsonPath = Config::getBasePath() . "/controls-{$name}.json";
        if (file_exists($jsonPath)) {
            throw ProfileException::profileExists($name);
        }

        // Check for reserved names
        $reserved = ['Template', 'Security', 'DigitalSovereignty', 'AI', 'OpenShift', 'RHEL'];
        if (in_array($name, $reserved, true)) {
            throw ProfileException::invalidName($name, 'This is a reserved profile name');
        }

        return true;
    }

    /**
     * Build JSON structure from wizard data
     *
     * @param array $wizardData Data collected from wizard steps
     * @return array JSON-ready structure
     */
    public static function buildProfileJSON(array $wizardData): array {
        $json = [];

        for ($i = 1; $i <= 7; $i++) {
            $domainKey = "Domain{$i}";
            $domainData = $wizardData['domains'][$i] ?? [];

            $json[$domainKey] = [
                'overview' => $domainData['overview'] ?? '',
                'qnum' => (string)$i,
                'title' => $domainData['title'] ?? "Domain {$i}"
            ];

            // Add 8 capabilities per domain
            for ($cap = 1; $cap <= 8; $cap++) {
                $capData = $domainData['capabilities'][$cap] ?? [];

                $json[$domainKey][(string)$cap] = $capData['name'] ?? "Capability {$cap}";
                $json[$domainKey]["{$cap}-summary"] = $capData['summary'] ?? '';
                $json[$domainKey]["{$cap}-tier"] = $capData['tier'] ?? 'Foundation';
                $json[$domainKey]["{$cap}-points"] = (string)$cap;
                $json[$domainKey]["{$cap}-recommendation"] = $capData['recommendation'] ?? '';
            }
        }

        return $json;
    }

    /**
     * Write profile JSON file safely
     *
     * @param string $profileName Profile name
     * @param array $jsonData JSON structure to write
     * @return string Path to created file
     * @throws ProfileException If write fails
     */
    public static function writeProfileJSON(string $profileName, array $jsonData): string {
        $basePath = Config::getBasePath();
        $targetPath = "{$basePath}/controls-{$profileName}.json";
        $tempPath = "{$basePath}/.controls-{$profileName}.json.tmp";

        // Ensure base path is writable
        if (!is_writable($basePath)) {
            throw ProfileException::fileWriteFailed(
                $basePath,
                'Base directory is not writable'
            );
        }

        // Encode JSON with pretty print
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

        // Write to temp file first
        if (file_put_contents($tempPath, $jsonString) === false) {
            throw ProfileException::fileWriteFailed($tempPath, 'Could not write temporary file');
        }

        // Atomic rename
        if (!rename($tempPath, $targetPath)) {
            @unlink($tempPath); // Cleanup temp file
            throw ProfileException::fileWriteFailed($targetPath, 'Could not finalize file');
        }

        Logger::info('Profile JSON created', [
            'profile' => $profileName,
            'path' => $targetPath,
            'size' => filesize($targetPath)
        ]);

        return $targetPath;
    }

    /**
     * Update Config.php with new profile
     *
     * @param string $profileName Profile name
     * @param string $displayName Display name
     * @param bool $enabled Whether to enable profile
     * @return string Backup file path
     */
    public static function updateConfigFile(string $profileName, string $displayName, bool $enabled): string {
        return FileUpdater::updateConfigPHP($profileName, $displayName, $enabled);
    }

    /**
     * Update index.php with navigation button
     *
     * @param string $profileName Profile name
     * @param string $displayName Display name
     * @return string Backup file path
     */
    public static function updateIndexFile(string $profileName, string $displayName): string {
        return FileUpdater::updateIndexPHP($profileName, $displayName);
    }

    /**
     * Complete profile generation (orchestrates all steps)
     *
     * @param array $wizardData Complete wizard data
     * @return array Result information
     * @throws ProfileException If generation fails
     */
    public static function generateProfile(array $wizardData): array {
        $profileName = $wizardData['metadata']['profile_name'] ?? '';
        $displayName = $wizardData['metadata']['display_name'] ?? $profileName;
        $enabled = $wizardData['metadata']['enabled'] ?? false;

        $createdFiles = [];
        $rollbackActions = [];

        try {
            // Step 1: Validate profile name
            Logger::info('Starting profile generation', ['profile' => $profileName]);
            self::validateProfileName($profileName);

            // Step 2: Build JSON structure
            $jsonData = self::buildProfileJSON($wizardData);

            // Step 3: Write JSON file
            $jsonPath = self::writeProfileJSON($profileName, $jsonData);
            $createdFiles[] = $jsonPath;
            $rollbackActions[] = function() use ($jsonPath) {
                if (file_exists($jsonPath)) {
                    @unlink($jsonPath);
                    Logger::info('Rolled back: Deleted JSON file', ['file' => $jsonPath]);
                }
            };

            // Step 4: Update Config.php
            $configBackup = self::updateConfigFile($profileName, $displayName, $enabled);
            $rollbackActions[] = function() use ($configBackup) {
                FileUpdater::restoreBackup($configBackup);
                Logger::info('Rolled back: Restored Config.php', ['backup' => $configBackup]);
            };

            // Step 5: Update index.php
            $indexBackup = self::updateIndexFile($profileName, $displayName);
            $rollbackActions[] = function() use ($indexBackup) {
                FileUpdater::restoreBackup($indexBackup);
                Logger::info('Rolled back: Restored index.php', ['backup' => $indexBackup]);
            };

            // Success - clean up backups
            @unlink($configBackup);
            @unlink($indexBackup);

            // Clear opcode cache so changes are immediately visible
            if (function_exists('opcache_reset')) {
                opcache_reset();
                Logger::info('Cleared opcode cache after profile creation');
            }

            Logger::info('Profile generated successfully', [
                'profile' => $profileName,
                'display_name' => $displayName,
                'enabled' => $enabled,
                'files_created' => $createdFiles
            ]);

            return [
                'success' => true,
                'profile_name' => $profileName,
                'display_name' => $displayName,
                'files_created' => $createdFiles,
                'message' => "Profile '{$displayName}' created successfully!"
            ];

        } catch (Exception $e) {
            // Rollback all changes in reverse order
            Logger::error('Profile generation failed, initiating rollback', [
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
