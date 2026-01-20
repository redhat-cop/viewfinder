<?php
/**
 * ProfileExporter - Handles profile export functionality
 *
 * Generates downloadable JSON files from existing profiles
 */

require_once __DIR__ . '/Config.php';
require_once __DIR__ . '/Security.php';
require_once __DIR__ . '/Logger.php';
require_once __DIR__ . '/Exceptions/ProfileException.php';

class ProfileExporter {

    /**
     * Validate profile exists and is exportable
     *
     * @param string $profileName Profile name to validate
     * @return bool True if valid
     * @throws ProfileException If invalid or not found
     */
    public static function validateProfileForExport(string $profileName): bool {
        // Trim and validate input
        $profileName = trim($profileName);

        // Check if profile exists in Config
        if (!Config::isValidProfile($profileName)) {
            throw new ProfileException(
                "Profile not found: {$profileName}",
                "Profile '{$profileName}' does not exist.",
                ['profile' => $profileName]
            );
        }

        // Check if JSON file exists
        $jsonPath = Config::getBasePath() . "/controls-{$profileName}.json";
        if (!file_exists($jsonPath)) {
            throw new ProfileException(
                "Profile file not found: {$jsonPath}",
                "Profile '{$profileName}' file does not exist.",
                ['profile' => $profileName, 'path' => $jsonPath]
            );
        }

        // Check if file is readable
        if (!is_readable($jsonPath)) {
            throw new ProfileException(
                "Profile file is not readable: {$jsonPath}",
                "Unable to read profile '{$profileName}'.",
                ['profile' => $profileName, 'path' => $jsonPath]
            );
        }

        return true;
    }

    /**
     * Load profile JSON data
     *
     * @param string $profileName Profile name
     * @return array Profile JSON data
     * @throws ProfileException If load fails
     */
    public static function loadProfileData(string $profileName): array {
        $jsonPath = Config::getBasePath() . "/controls-{$profileName}.json";

        // Load and decode JSON
        $jsonData = Security::loadJSON($jsonPath);

        if (empty($jsonData)) {
            throw new ProfileException(
                "Profile data is empty or invalid: {$jsonPath}",
                "Unable to load profile '{$profileName}'.",
                ['profile' => $profileName, 'path' => $jsonPath]
            );
        }

        return $jsonData;
    }

    /**
     * Generate downloadable JSON content
     *
     * @param string $profileName Profile name to export
     * @return string JSON string ready for download
     * @throws ProfileException If export fails
     */
    public static function generateExportJSON(string $profileName): string {
        // Validate profile
        self::validateProfileForExport($profileName);

        // Load profile data
        $profileData = self::loadProfileData($profileName);

        // Encode JSON with pretty print
        $jsonString = json_encode(
            $profileData,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        );

        if ($jsonString === false) {
            throw new ProfileException(
                'JSON encoding failed: ' . json_last_error_msg(),
                "Unable to export profile '{$profileName}'.",
                ['profile' => $profileName, 'error' => json_last_error_msg()]
            );
        }

        Logger::info('Profile exported', [
            'profile' => $profileName,
            'size' => strlen($jsonString)
        ]);

        return $jsonString;
    }

    /**
     * Send profile JSON as download
     *
     * @param string $profileName Profile name to export
     * @return void Sets headers and outputs JSON
     * @throws ProfileException If export fails
     */
    public static function downloadProfile(string $profileName): void {
        // Generate JSON content
        $jsonContent = self::generateExportJSON($profileName);

        // Sanitize filename
        $filename = "controls-{$profileName}.json";
        $safeFilename = basename($filename);

        // Set headers for download
        header('Content-Type: application/json; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $safeFilename . '"');
        header('Content-Length: ' . strlen($jsonContent));
        header('Cache-Control: no-cache, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');

        // Output JSON
        echo $jsonContent;

        Logger::info('Profile download initiated', [
            'profile' => $profileName,
            'filename' => $safeFilename,
            'size' => strlen($jsonContent)
        ]);
    }

    /**
     * Export multiple profiles as a single archive (future enhancement)
     *
     * @param array $profileNames Array of profile names to export
     * @return array Export result information
     * @throws ProfileException If export fails
     */
    public static function exportMultipleProfiles(array $profileNames): array {
        $exportedProfiles = [];
        $errors = [];

        foreach ($profileNames as $profileName) {
            try {
                $jsonContent = self::generateExportJSON($profileName);
                $exportedProfiles[$profileName] = [
                    'success' => true,
                    'size' => strlen($jsonContent),
                    'data' => $jsonContent
                ];
            } catch (Exception $e) {
                $errors[$profileName] = $e->getMessage();
                Logger::error('Failed to export profile', [
                    'profile' => $profileName,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return [
            'exported' => $exportedProfiles,
            'errors' => $errors,
            'total' => count($profileNames),
            'success_count' => count($exportedProfiles),
            'error_count' => count($errors)
        ];
    }

    /**
     * Get exportable profile list
     *
     * @return array List of profiles that can be exported
     */
    public static function getExportableProfiles(): array {
        $exportable = [];

        foreach (Config::PROFILES as $profileName => $profileConfig) {
            $jsonPath = Config::getBasePath() . "/controls-{$profileName}.json";

            if (file_exists($jsonPath) && is_readable($jsonPath)) {
                $exportable[] = [
                    'name' => $profileName,
                    'display_name' => $profileConfig['display_name'] ?? $profileName,
                    'enabled' => $profileConfig['enabled'] ?? false,
                    'size' => filesize($jsonPath),
                    'modified' => filemtime($jsonPath)
                ];
            }
        }

        return $exportable;
    }
}
