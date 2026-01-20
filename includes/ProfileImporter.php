<?php
/**
 * ProfileImporter - Handles profile import functionality
 *
 * Validates and imports profile JSON files with comprehensive security checks
 */

require_once __DIR__ . '/Config.php';
require_once __DIR__ . '/Security.php';
require_once __DIR__ . '/Logger.php';
require_once __DIR__ . '/FileUpdater.php';
require_once __DIR__ . '/ProfileGenerator.php';
require_once __DIR__ . '/Exceptions/ProfileException.php';

class ProfileImporter {

    // Maximum file size: 5MB
    const MAX_FILE_SIZE = 5 * 1024 * 1024;

    // Required tiers
    const VALID_TIERS = ['Foundation', 'Strategic', 'Advanced'];

    /**
     * Validate uploaded file
     *
     * @param array $file $_FILES array entry
     * @return bool True if valid
     * @throws ProfileException If invalid
     */
    public static function validateUploadedFile(array $file): bool {
        // Check for upload errors
        if (!isset($file['error']) || is_array($file['error'])) {
            throw new ProfileException(
                'Invalid file upload parameters',
                'Invalid file upload.',
                ['file' => $file]
            );
        }

        // Check upload error code
        switch ($file['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new ProfileException(
                    'File size exceeds maximum allowed size',
                    'File is too large. Maximum size is 5MB.',
                    ['size' => $file['size'] ?? 'unknown']
                );
            case UPLOAD_ERR_NO_FILE:
                throw new ProfileException(
                    'No file was uploaded',
                    'Please select a file to upload.',
                    []
                );
            default:
                throw new ProfileException(
                    'File upload failed with error code: ' . $file['error'],
                    'File upload failed. Please try again.',
                    ['error_code' => $file['error']]
                );
        }

        // Verify file size
        if ($file['size'] > self::MAX_FILE_SIZE) {
            throw new ProfileException(
                'File size exceeds maximum: ' . $file['size'],
                'File is too large. Maximum size is 5MB.',
                ['size' => $file['size'], 'max' => self::MAX_FILE_SIZE]
            );
        }

        // Verify file was actually uploaded
        if (!is_uploaded_file($file['tmp_name'])) {
            throw new ProfileException(
                'File was not uploaded via HTTP POST',
                'Invalid file upload.',
                ['tmp_name' => $file['tmp_name']]
            );
        }

        // Check MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        $allowedMimes = ['application/json', 'text/plain'];
        if (!in_array($mimeType, $allowedMimes, true)) {
            throw new ProfileException(
                'Invalid MIME type: ' . $mimeType,
                'File must be a JSON file.',
                ['mime' => $mimeType, 'allowed' => $allowedMimes]
            );
        }

        return true;
    }

    /**
     * Parse and validate JSON content
     *
     * @param string $jsonContent JSON string to parse
     * @return array Parsed JSON data
     * @throws ProfileException If invalid
     */
    public static function parseJSON(string $jsonContent): array {
        // Decode JSON
        $data = json_decode($jsonContent, true);

        if ($data === null) {
            throw new ProfileException(
                'JSON decode failed: ' . json_last_error_msg(),
                'Invalid JSON file. Please check the file format.',
                ['error' => json_last_error_msg()]
            );
        }

        if (!is_array($data)) {
            throw new ProfileException(
                'JSON root is not an object/array',
                'Invalid profile format.',
                ['type' => gettype($data)]
            );
        }

        return $data;
    }

    /**
     * Validate profile JSON structure
     *
     * @param array $profileData Profile data to validate
     * @return bool True if valid
     * @throws ProfileException If invalid
     */
    public static function validateProfileStructure(array $profileData): bool {
        $errors = [];

        // Check for 7 domains (format: Domain-1, Domain-2, etc.)
        for ($i = 1; $i <= 7; $i++) {
            $domainKey = "Domain-{$i}";

            if (!isset($profileData[$domainKey])) {
                $errors[] = "Missing domain: {$domainKey}";
                continue;
            }

            $domain = $profileData[$domainKey];

            // Validate domain fields
            $requiredDomainFields = ['title', 'overview', 'qnum'];
            foreach ($requiredDomainFields as $field) {
                if (!isset($domain[$field])) {
                    $errors[] = "{$domainKey}: Missing field '{$field}'";
                }
            }

            // Validate qnum matches domain number
            if (isset($domain['qnum']) && $domain['qnum'] != $i) {
                $errors[] = "{$domainKey}: 'qnum' should be {$i}, got {$domain['qnum']}";
            }

            // Check for 8 capabilities
            for ($cap = 1; $cap <= 8; $cap++) {
                $capKey = (string)$cap;
                $requiredCapFields = [
                    $capKey,
                    "{$cap}-summary",
                    "{$cap}-tier",
                    "{$cap}-points",
                    "{$cap}-recommendation"
                ];

                foreach ($requiredCapFields as $field) {
                    if (!isset($domain[$field])) {
                        $errors[] = "{$domainKey}: Missing capability field '{$field}'";
                    }
                }

                // Validate tier value
                if (isset($domain["{$cap}-tier"])) {
                    $tier = $domain["{$cap}-tier"];
                    if (!in_array($tier, self::VALID_TIERS, true)) {
                        $errors[] = "{$domainKey}: Invalid tier '{$tier}' for capability {$cap}. Must be: " . implode(', ', self::VALID_TIERS);
                    }
                }

                // Validate points value
                if (isset($domain["{$cap}-points"])) {
                    $points = $domain["{$cap}-points"];
                    if ($points != $cap) {
                        $errors[] = "{$domainKey}: Points for capability {$cap} should be {$cap}, got {$points}";
                    }
                }
            }
        }

        if (!empty($errors)) {
            throw new ProfileException(
                'Profile structure validation failed: ' . implode('; ', $errors),
                'Profile structure is invalid. Please check the file format.',
                ['errors' => $errors]
            );
        }

        return true;
    }

    /**
     * Extract or generate profile name from import data
     *
     * @param array $file $_FILES array entry
     * @param string|null $customName Optional custom profile name
     * @return string Profile name
     * @throws ProfileException If invalid
     */
    public static function extractProfileName(array $file, ?string $customName = null): string {
        // Use custom name if provided
        if (!empty($customName)) {
            $profileName = trim($customName);
        } else {
            // Extract from filename: controls-{ProfileName}.json
            $filename = basename($file['name']);

            if (preg_match('/^controls-([a-zA-Z0-9_]+)\.json$/', $filename, $matches)) {
                $profileName = $matches[1];
            } else {
                throw new ProfileException(
                    'Cannot extract profile name from filename: ' . $filename,
                    'Invalid filename format. Expected: controls-ProfileName.json',
                    ['filename' => $filename]
                );
            }
        }

        // Validate profile name format
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $profileName)) {
            throw new ProfileException(
                'Invalid profile name format: ' . $profileName,
                'Profile name must contain only letters, numbers, and underscores.',
                ['name' => $profileName]
            );
        }

        if (strlen($profileName) < 2) {
            throw new ProfileException(
                'Profile name too short: ' . $profileName,
                'Profile name must be at least 2 characters.',
                ['name' => $profileName, 'length' => strlen($profileName)]
            );
        }

        if (strlen($profileName) > 50) {
            throw new ProfileException(
                'Profile name too long: ' . $profileName,
                'Profile name must be 50 characters or less.',
                ['name' => $profileName, 'length' => strlen($profileName)]
            );
        }

        return $profileName;
    }

    /**
     * Check if profile already exists
     *
     * @param string $profileName Profile name to check
     * @return bool True if exists
     */
    public static function profileExists(string $profileName): bool {
        // Check in Config
        if (Config::isValidProfile($profileName)) {
            return true;
        }

        // Check if JSON file exists
        $jsonPath = Config::getBasePath() . "/controls-{$profileName}.json";
        if (file_exists($jsonPath)) {
            return true;
        }

        return false;
    }

    /**
     * Check if profile is protected (cannot be overwritten)
     *
     * @param string $profileName Profile name to check
     * @return bool True if protected
     */
    public static function isProtectedProfile(string $profileName): bool {
        $protected = ['Template', 'Security', 'DigitalSovereignty', 'AI', 'OpenShift', 'RHEL'];
        return in_array($profileName, $protected, true);
    }

    /**
     * Import profile from uploaded file
     *
     * @param array $file $_FILES array entry
     * @param string|null $customName Optional custom profile name
     * @param string $displayName Display name for profile
     * @param bool $overwrite Allow overwriting existing profile
     * @param bool $enabled Enable profile after import
     * @return array Import result
     * @throws ProfileException If import fails
     */
    public static function importProfile(
        array $file,
        ?string $customName = null,
        string $displayName = '',
        bool $overwrite = false,
        bool $enabled = true
    ): array {
        $rollbackActions = [];

        try {
            // Step 1: Validate uploaded file
            Logger::info('Starting profile import', ['filename' => $file['name']]);
            self::validateUploadedFile($file);

            // Step 2: Read file content
            $jsonContent = file_get_contents($file['tmp_name']);
            if ($jsonContent === false) {
                throw new ProfileException(
                    'Failed to read uploaded file',
                    'Unable to read uploaded file.',
                    ['tmp_name' => $file['tmp_name']]
                );
            }

            // Step 3: Parse JSON
            $profileData = self::parseJSON($jsonContent);

            // Step 4: Validate structure
            self::validateProfileStructure($profileData);

            // Step 5: Extract/validate profile name
            $profileName = self::extractProfileName($file, $customName);

            // Step 6: Check if profile is protected
            if (self::isProtectedProfile($profileName)) {
                throw new ProfileException(
                    'Cannot overwrite protected profile: ' . $profileName,
                    "'{$profileName}' is a protected profile and cannot be overwritten.",
                    ['profile' => $profileName]
                );
            }

            // Step 7: Check if profile exists
            $exists = self::profileExists($profileName);
            if ($exists && !$overwrite) {
                throw new ProfileException(
                    'Profile already exists: ' . $profileName,
                    "Profile '{$profileName}' already exists. Use overwrite option to replace it.",
                    ['profile' => $profileName, 'overwrite' => false]
                );
            }

            // Set display name
            if (empty($displayName)) {
                $displayName = $profileName;
            }

            // Step 8: Backup existing profile if overwriting
            $backupPath = null;
            if ($exists) {
                $jsonPath = Config::getBasePath() . "/controls-{$profileName}.json";
                $backupPath = $jsonPath . '.backup.' . time();
                if (!copy($jsonPath, $backupPath)) {
                    throw new ProfileException(
                        'Failed to backup existing profile',
                        'Unable to backup existing profile before overwrite.',
                        ['profile' => $profileName, 'backup' => $backupPath]
                    );
                }
                $rollbackActions[] = function() use ($backupPath, $jsonPath) {
                    if (file_exists($backupPath)) {
                        copy($backupPath, $jsonPath);
                        @unlink($backupPath);
                        Logger::info('Rolled back: Restored profile from backup', ['backup' => $backupPath]);
                    }
                };
            }

            // Step 9: Write profile JSON file
            $jsonPath = ProfileGenerator::writeProfileJSON($profileName, $profileData);
            if (!$exists) {
                $rollbackActions[] = function() use ($jsonPath) {
                    if (file_exists($jsonPath)) {
                        @unlink($jsonPath);
                        Logger::info('Rolled back: Deleted imported JSON file', ['file' => $jsonPath]);
                    }
                };
            }

            // Step 10: Update Config.php (only if new profile)
            if (!$exists) {
                $configBackup = FileUpdater::updateConfigPHP($profileName, $displayName, $enabled);
                $rollbackActions[] = function() use ($configBackup) {
                    if (file_exists($configBackup)) {
                        FileUpdater::restoreBackup($configBackup);
                        Logger::info('Rolled back: Restored Config.php', ['backup' => $configBackup]);
                    }
                };
            }

            // Step 11: index.php update not needed - it dynamically reads from Config::PROFILES
            // The profile will appear automatically once it's registered in Config.php

            // Step 12: Clean up backup
            if ($backupPath && file_exists($backupPath)) {
                @unlink($backupPath);
            }

            // Clear opcode cache
            if (function_exists('opcache_reset')) {
                opcache_reset();
                Logger::info('Cleared opcode cache after profile import');
            }

            Logger::info('Profile imported successfully', [
                'profile' => $profileName,
                'display_name' => $displayName,
                'overwrite' => $exists,
                'enabled' => $enabled,
                'size' => strlen($jsonContent)
            ]);

            return [
                'success' => true,
                'profile_name' => $profileName,
                'display_name' => $displayName,
                'overwrite' => $exists,
                'message' => $exists
                    ? "Profile '{$displayName}' updated successfully!"
                    : "Profile '{$displayName}' imported successfully!"
            ];

        } catch (Exception $e) {
            // Rollback all changes in reverse order
            Logger::error('Profile import failed, initiating rollback', [
                'filename' => $file['name'],
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

    /**
     * Validate import before processing (preview mode)
     *
     * @param array $file $_FILES array entry
     * @param string|null $customName Optional custom profile name
     * @return array Validation result
     */
    public static function validateImport(array $file, ?string $customName = null): array {
        try {
            // Validate file
            self::validateUploadedFile($file);

            // Read and parse
            $jsonContent = file_get_contents($file['tmp_name']);
            $profileData = self::parseJSON($jsonContent);

            // Validate structure
            self::validateProfileStructure($profileData);

            // Extract name
            $profileName = self::extractProfileName($file, $customName);

            // Check conflicts
            $exists = self::profileExists($profileName);
            $protected = self::isProtectedProfile($profileName);

            return [
                'valid' => true,
                'profile_name' => $profileName,
                'exists' => $exists,
                'protected' => $protected,
                'can_import' => !$protected,
                'domains' => 7,
                'capabilities' => 56,
                'size' => strlen($jsonContent),
                'message' => 'Profile is valid and ready to import.'
            ];

        } catch (ProfileException $e) {
            return [
                'valid' => false,
                'error' => $e->getUserMessage(),
                'details' => $e->getContext(),
                'message' => $e->getUserMessage()
            ];
        } catch (Exception $e) {
            return [
                'valid' => false,
                'error' => 'Validation failed: ' . $e->getMessage(),
                'message' => 'Unable to validate profile.'
            ];
        }
    }
}
