<?php
/**
 * ProfileDeleter - Profile deletion logic
 *
 * Handles safe deletion of profiles with rollback support
 */

require_once __DIR__ . '/Config.php';
require_once __DIR__ . '/Logger.php';
require_once __DIR__ . '/FileUpdater.php';
require_once __DIR__ . '/Exceptions/ProfileException.php';

class ProfileDeleter {

    /**
     * Protected profiles that cannot be deleted
     */
    const PROTECTED_PROFILES = [
        'Security',
        'DigitalSovereignty',
        'Template'
    ];

    /**
     * Get list of deletable profiles
     *
     * @return array List of profiles that can be deleted
     */
    public static function getDeletableProfiles(): array {
        $allProfiles = Config::PROFILES;
        $deletable = [];

        foreach ($allProfiles as $key => $profile) {
            if (!in_array($key, self::PROTECTED_PROFILES, true)) {
                $deletable[] = [
                    'name' => $key,
                    'display_name' => $profile['display_name'],
                    'enabled' => $profile['enabled'],
                    'json_exists' => file_exists(Config::getBasePath() . "/controls-{$key}.json")
                ];
            }
        }

        return $deletable;
    }

    /**
     * Validate profile can be deleted
     *
     * @param string $profileName Profile to validate
     * @return bool True if can be deleted
     * @throws ProfileException If cannot be deleted
     */
    public static function validateDeletion(string $profileName): bool {
        // Check if protected
        if (in_array($profileName, self::PROTECTED_PROFILES, true)) {
            throw new ProfileException(
                "Cannot delete protected profile: {$profileName}",
                "This is a core profile and cannot be deleted.",
                ['profile' => $profileName]
            );
        }

        // Check if profile exists in Config
        if (!Config::isValidProfile($profileName)) {
            throw new ProfileException(
                "Profile '{$profileName}' does not exist in configuration",
                "Profile not found in system configuration.",
                ['profile' => $profileName]
            );
        }

        return true;
    }

    /**
     * Delete profile JSON file
     *
     * @param string $profileName Profile name
     * @return string Path to deleted file (for rollback)
     * @throws ProfileException If deletion fails
     */
    public static function deleteProfileJSON(string $profileName): string {
        $basePath = Config::getBasePath();
        $jsonPath = "{$basePath}/controls-{$profileName}.json";

        if (!file_exists($jsonPath)) {
            Logger::warning('JSON file does not exist, skipping deletion', ['file' => $jsonPath]);
            return '';
        }

        // Create backup before deletion
        $backupPath = $jsonPath . '.deleted.' . date('YmdHis');
        if (!copy($jsonPath, $backupPath)) {
            throw ProfileException::backupFailed($jsonPath);
        }

        // Delete the file
        if (!unlink($jsonPath)) {
            // Restore backup
            @copy($backupPath, $jsonPath);
            @unlink($backupPath);
            throw ProfileException::fileWriteFailed($jsonPath, 'Could not delete file');
        }

        Logger::info('Profile JSON deleted', [
            'profile' => $profileName,
            'file' => $jsonPath,
            'backup' => $backupPath
        ]);

        return $backupPath;
    }

    /**
     * Remove profile from Config.php
     *
     * @param string $profileName Profile to remove
     * @return string Backup file path
     * @throws ProfileException If update fails
     */
    public static function removeFromConfig(string $profileName): string {
        $configPath = Config::getBasePath() . '/includes/Config.php';

        // Create backup
        $backupPath = FileUpdater::createBackup($configPath);

        try {
            $content = file_get_contents($configPath);

            if ($content === false) {
                throw ProfileException::fileWriteFailed($configPath, 'Could not read file');
            }

            // Remove the profile entry (including surrounding whitespace)
            // Pattern matches the entire profile array entry
            $pattern = "/\\s*'{$profileName}'\\s*=>\\s*\\[\\s*'name'\\s*=>\\s*'{$profileName}',\\s*'display_name'\\s*=>\\s*'[^']*',\\s*'enabled'\\s*=>\\s*(?:true|false)\\s*\\],?\\s*/s";
            $newContent = preg_replace($pattern, '', $content);

            if ($newContent === null || $newContent === $content) {
                throw new ProfileException(
                    "Failed to remove profile from Config.php - pattern not found",
                    "Could not update configuration file.",
                    ['profile' => $profileName]
                );
            }

            // Write atomically
            $tempPath = $configPath . '.tmp';
            if (file_put_contents($tempPath, $newContent) === false) {
                throw ProfileException::fileWriteFailed($tempPath, 'Could not write temp file');
            }

            if (!rename($tempPath, $configPath)) {
                @unlink($tempPath);
                throw ProfileException::fileWriteFailed($configPath, 'Could not rename temp file');
            }

            Logger::info('Profile removed from Config.php', ['profile' => $profileName]);

            return $backupPath;

        } catch (Exception $e) {
            // Restore on failure
            FileUpdater::restoreBackup($backupPath);
            throw $e;
        }
    }

    /**
     * Remove navigation button from index.php
     *
     * @param string $profileName Profile name
     * @return string Backup file path
     * @throws ProfileException If update fails
     */
    public static function removeFromIndex(string $profileName): string {
        $indexPath = Config::getBasePath() . '/index.php';

        // Create backup
        $backupPath = FileUpdater::createBackup($indexPath);

        try {
            $content = file_get_contents($indexPath);

            if ($content === false) {
                throw ProfileException::fileWriteFailed($indexPath, 'Could not read file');
            }

            // Remove the button line (with optional surrounding whitespace and &nbsp)
            $pattern = "/\\s*<a href=\"index\\.php\\?profile={$profileName}\"><button>.*?<\\/button><\\/a>&nbsp\\s*/";
            $newContent = preg_replace($pattern, '', $content);

            if ($newContent === null || $newContent === $content) {
                Logger::warning('Profile button not found in index.php, skipping removal', [
                    'profile' => $profileName
                ]);
                // Don't fail if button isn't found - it might not have been added
                @unlink($backupPath);
                return '';
            }

            // Write atomically
            $tempPath = $indexPath . '.tmp';
            if (file_put_contents($tempPath, $newContent) === false) {
                throw ProfileException::fileWriteFailed($tempPath, 'Could not write temp file');
            }

            if (!rename($tempPath, $indexPath)) {
                @unlink($tempPath);
                throw ProfileException::fileWriteFailed($indexPath, 'Could not rename temp file');
            }

            Logger::info('Profile button removed from index.php', ['profile' => $profileName]);

            return $backupPath;

        } catch (Exception $e) {
            // Restore on failure
            if (!empty($backupPath)) {
                FileUpdater::restoreBackup($backupPath);
            }
            throw $e;
        }
    }

    /**
     * Delete profile completely (orchestrates all steps)
     *
     * @param string $profileName Profile to delete
     * @return array Result information
     * @throws ProfileException If deletion fails
     */
    public static function deleteProfile(string $profileName): array {
        $rollbackActions = [];
        $deletedFiles = [];

        try {
            // Step 1: Validate
            Logger::info('Starting profile deletion', ['profile' => $profileName]);
            self::validateDeletion($profileName);

            // Step 2: Remove from index.php
            $indexBackup = self::removeFromIndex($profileName);
            if (!empty($indexBackup)) {
                $rollbackActions[] = function() use ($indexBackup) {
                    FileUpdater::restoreBackup($indexBackup);
                    Logger::info('Rolled back: Restored index.php', ['backup' => $indexBackup]);
                };
            }

            // Step 3: Remove from Config.php
            $configBackup = self::removeFromConfig($profileName);
            $rollbackActions[] = function() use ($configBackup) {
                FileUpdater::restoreBackup($configBackup);
                Logger::info('Rolled back: Restored Config.php', ['backup' => $configBackup]);
            };

            // Step 4: Delete JSON file
            $jsonBackup = self::deleteProfileJSON($profileName);
            if (!empty($jsonBackup)) {
                $deletedFiles[] = $jsonBackup;
                $rollbackActions[] = function() use ($jsonBackup, $profileName) {
                    $originalPath = Config::getBasePath() . "/controls-{$profileName}.json";
                    if (!copy($jsonBackup, $originalPath)) {
                        Logger::error('Failed to restore JSON file', ['backup' => $jsonBackup]);
                    } else {
                        Logger::info('Rolled back: Restored JSON file', ['file' => $originalPath]);
                    }
                };
            }

            // Success - clean up backups
            @unlink($configBackup);
            if (!empty($indexBackup)) {
                @unlink($indexBackup);
            }
            if (!empty($jsonBackup)) {
                @unlink($jsonBackup);
            }

            // Clear opcode cache so changes are immediately visible
            if (function_exists('opcache_reset')) {
                opcache_reset();
                Logger::info('Cleared opcode cache after profile deletion');
            }

            Logger::info('Profile deleted successfully', [
                'profile' => $profileName,
                'files_deleted' => $deletedFiles
            ]);

            return [
                'success' => true,
                'profile_name' => $profileName,
                'message' => "Profile '{$profileName}' has been deleted successfully."
            ];

        } catch (Exception $e) {
            // Rollback all changes in reverse order
            Logger::error('Profile deletion failed, initiating rollback', [
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
