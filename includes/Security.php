<?php
require_once __DIR__ . '/Config.php';

/**
 * Security Helper Class
 * Provides input validation and output sanitization functions
 */
class Security {

    /**
     * Validate and sanitize profile parameter
     *
     * @param string $profile User-provided profile value
     * @return string Validated profile or default 'Security'
     */
    public static function validateProfile($profile) {
        if (empty($profile) || !Config::isValidProfile($profile)) {
            return 'Security'; // Safe default
        }
        return $profile;
    }

    /**
     * Validate line of business parameter
     *
     * @param string $lob User-provided LOB value
     * @return string|null Validated LOB or null if invalid
     */
    public static function validateLOB($lob) {
        if (empty($lob) || !Config::isValidLOB($lob)) {
            return null;
        }
        return $lob;
    }

    /**
     * Sanitize output for HTML display (prevents XSS)
     *
     * @param string $string Input string
     * @return string Escaped string safe for HTML output
     */
    public static function escape($string) {
        return htmlspecialchars($string, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /**
     * Validate framework names against whitelist from JSON
     *
     * @param array $frameworks User-provided framework array
     * @param array $validFrameworks Valid framework names from compliance.json
     * @return array Validated frameworks
     */
    public static function validateFrameworks($frameworks, $validFrameworks) {
        if (!is_array($frameworks)) {
            return [];
        }

        // Filter to only allowed frameworks
        return array_filter($frameworks, function($framework) use ($validFrameworks) {
            return in_array($framework, $validFrameworks, true);
        });
    }

    /**
     * Build safe file path for LOB includes
     *
     * @param string $lob Validated LOB value
     * @param string $profile Validated profile value
     * @return string|null Safe file path or null if file doesn't exist
     */
    public static function getLOBFilePath($lob, $profile) {
        // Validate inputs first
        //$lob = self::validateLOB($lob);
        $profile = self::validateProfile($profile);

        if ($lob === null) {
            return null;
        }

        // Build safe path using Config
        $baseDir = Config::getLOBContentPath();

        if ($profile == "Security") {
        $fileName = "lob-{$lob}.html";
        } else {
        $fileName = "lob-{$lob}-{$profile}.html";
        }
        $fullPath = $baseDir . $fileName;

        // Verify file exists and is within allowed directory
        if (file_exists($fullPath) && realpath($fullPath) !== false &&
            strpos(realpath($fullPath), realpath($baseDir)) === 0) {
            return $fullPath;
        }

        return null;
    }

    /**
     * Build safe file path for compliance framework includes
     *
     * @param string $linkFile Framework link from JSON
     * @return string|null Safe file path or null if file doesn't exist
     */
    public static function getFrameworkFilePath($linkFile) {
        $baseDir = Config::getComplianceContentPath();

        // Only allow files from compliance directory
        $fileName = basename($linkFile);
        $fullPath = $baseDir . $fileName;

        // Verify file exists and is within allowed directory
        if (file_exists($fullPath) && realpath($fullPath) !== false &&
            strpos(realpath($fullPath), realpath($baseDir)) === 0) {
            return $fullPath;
        }

        return null;
    }

    /**
     * Safely load and decode JSON file
     *
     * @param string $filePath Path to JSON file
     * @return array|null Decoded JSON data or null on error
     */
    public static function loadJSON($filePath) {
        if (!file_exists($filePath)) {
            error_log("JSON file not found: {$filePath}");
            return null;
        }

        $content = @file_get_contents($filePath);
        if ($content === false) {
            error_log("Failed to read JSON file: {$filePath}");
            return null;
        }

        $data = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("JSON decode error in {$filePath}: " . json_last_error_msg());
            return null;
        }

        return $data;
    }

    /**
     * Get safe controls file path
     *
     * @param string $profile Validated profile name
     * @return string Safe controls file path
     */
    public static function getControlsFilePath($profile) {
        $profile = self::validateProfile($profile);
        return Config::getControlsPath($profile);
    }
}
