<?php
/**
 * ProfileAdmin - Profile administration logic
 *
 * Handles dashboard operations: listing, toggling, and managing profiles
 */

require_once __DIR__ . '/Config.php';
require_once __DIR__ . '/Logger.php';
require_once __DIR__ . '/FileUpdater.php';
require_once __DIR__ . '/ProfileDeleter.php';
require_once __DIR__ . '/Exceptions/ProfileException.php';

class ProfileAdmin {

    /**
     * Protected profiles that cannot be edited or deleted
     */
    const PROTECTED_PROFILES = [
        'Security',
        'DigitalSovereignty',
        'Template'
    ];

    /**
     * Check if a profile is protected
     *
     * @param string $profileName Profile to check
     * @return bool True if protected
     */
    public static function isProtected(string $profileName): bool {
        return in_array($profileName, self::PROTECTED_PROFILES, true);
    }

    /**
     * Get all profiles with metadata
     *
     * @return array Array of profile objects with metadata
     */
    public static function getAllProfiles(): array {
        $profiles = [];
        $stats = [
            'total' => 0,
            'enabled' => 0,
            'disabled' => 0,
            'protected' => 0,
            'custom' => 0
        ];

        foreach (Config::PROFILES as $key => $profile) {
            $jsonPath = Config::getControlsPath($key);
            $jsonExists = file_exists($jsonPath);
            $isProtected = self::isProtected($key);
            $isEnabled = $profile['enabled'] ?? false;

            $profileData = [
                'name' => $key,
                'display_name' => $profile['display_name'],
                'enabled' => $isEnabled,
                'protected' => $isProtected,
                'json_exists' => $jsonExists,
                'json_path' => $jsonPath
            ];

            // Add file stats if JSON exists
            if ($jsonExists) {
                $profileData['file_size'] = filesize($jsonPath);
                $profileData['modified_date'] = filemtime($jsonPath);
            }

            $profiles[] = $profileData;

            // Update statistics
            $stats['total']++;
            if ($isEnabled) {
                $stats['enabled']++;
            } else {
                $stats['disabled']++;
            }
            if ($isProtected) {
                $stats['protected']++;
            } else {
                $stats['custom']++;
            }
        }

        Logger::info('Retrieved all profiles for admin dashboard', [
            'count' => $stats['total']
        ]);

        return [
            'profiles' => $profiles,
            'stats' => $stats
        ];
    }

    /**
     * Toggle profile enabled status
     *
     * @param string $profileName Profile to toggle
     * @return array Result with new status
     * @throws ProfileException If toggle fails
     */
    public static function toggleProfileEnabled(string $profileName): array {
        // Validate profile exists
        if (!Config::isValidProfile($profileName)) {
            throw new ProfileException(
                "Profile '{$profileName}' does not exist",
                "Profile not found in system configuration.",
                ['profile' => $profileName]
            );
        }

        // Get current status
        $currentConfig = Config::PROFILES[$profileName];
        $currentEnabled = $currentConfig['enabled'] ?? false;
        $newEnabled = !$currentEnabled;

        try {
            Logger::info('Toggling profile enabled status', [
                'profile' => $profileName,
                'from' => $currentEnabled,
                'to' => $newEnabled
            ]);

            // Update Config.php
            $backupPath = FileUpdater::updateConfigEntry(
                $profileName,
                $currentConfig['display_name'],
                $newEnabled
            );

            // Clean up backup on success
            @unlink($backupPath);

            // Clear opcode cache
            if (function_exists('opcache_reset')) {
                opcache_reset();
                Logger::info('Cleared opcode cache after toggle');
            }

            Logger::info('Profile enabled status toggled successfully', [
                'profile' => $profileName,
                'new_status' => $newEnabled
            ]);

            return [
                'success' => true,
                'profile_name' => $profileName,
                'new_status' => $newEnabled,
                'message' => "Profile '{$currentConfig['display_name']}' is now " .
                            ($newEnabled ? 'enabled' : 'disabled')
            ];

        } catch (Exception $e) {
            Logger::error('Failed to toggle profile status', [
                'profile' => $profileName,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Delete a profile
     *
     * @param string $profileName Profile to delete
     * @return array Result information
     * @throws ProfileException If deletion fails
     */
    public static function deleteProfile(string $profileName): array {
        // Validate not protected
        if (self::isProtected($profileName)) {
            throw new ProfileException(
                "Cannot delete protected profile: {$profileName}",
                "This is a core profile and cannot be deleted.",
                ['profile' => $profileName]
            );
        }

        Logger::info('Admin dashboard initiating profile deletion', [
            'profile' => $profileName
        ]);

        // Delegate to ProfileDeleter
        return ProfileDeleter::deleteProfile($profileName);
    }
}
