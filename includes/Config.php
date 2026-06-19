<?php
/**
 * Application Configuration
 * Centralized configuration management for the Viewfinder Assessment Tool
 */
class Config {

    /**
     * Application settings
     */
    const APP_NAME = 'Viewfinder Maturity Assessment';
    const APP_VERSION = '2.0.0';

    /**
     * Available assessment profiles
     */
    const PROFILES = [
        'Security' => [
            'name' => 'Security',
            'display_name' => 'Security',
            'enabled' => true
        ],
        'DigitalSovereignty' => [
            'name' => 'DigitalSovereignty',
            'display_name' => 'Digital Sovereignty',
            'enabled' => true
        ],
        'SecureDefault' => [
            'name' => 'SecureDefault',
            'display_name' => 'Secure by Design & Secure by Default',
            'enabled' => true
        ],
        'AISovereignty' => [
            'name' => 'AISovereignty',
            'display_name' => 'AI Sovereignty',
            'enabled' => false
        ],
        'AI' => [
            'name' => 'AI',
            'display_name' => 'AI Readiness Assessment',
            'enabled' => false // Work in Progress
        ],];

    /**
     * Line of Business options
     */
    const LOB_OPTIONS = [
        'General' => 'General',
        'Finance' => 'Finance',
        'Government' => 'Government',
        'Manufacturing' => 'Manufacturing',
        'Telecommunications' => 'Telecommunications',
        'Healthcare' => 'Healthcare',
        'Other' => 'Other'
,];

    /**
     * Maturity level thresholds for individual controls (5-Level Maturity Model)
     * Based on max score of 36 per control with 20% intervals
     */
    const MATURITY_LEVELS = [
        'initial' => [
            'min' => 0,
            'max' => 7.2,
            'display_name' => 'Initial'
        ],
        'managed' => [
            'min' => 7.21,
            'max' => 14.4,
            'display_name' => 'Managed'
        ],
        'defined' => [
            'min' => 14.41,
            'max' => 21.6,
            'display_name' => 'Defined'
        ],
        'quantitative' => [
            'min' => 21.61,
            'max' => 28.8,
            'display_name' => 'Quantitatively Managed'
        ],
        'optimizing' => [
            'min' => 28.81,
            'max' => 36,
            'display_name' => 'Optimizing'
        ]
    ];

    /**
     * Overall maturity rating thresholds (total across all controls - 5-Level Maturity Model)
     * Based on max total score of 252 (7 controls × 36) with 20% intervals
     */
    const TOTAL_MATURITY_LEVELS = [
        'initial' => [
            'min' => 0,
            'max' => 50.4,
            'display_name' => 'Initial'
        ],
        'managed' => [
            'min' => 50.41,
            'max' => 100.8,
            'display_name' => 'Managed'
        ],
        'defined' => [
            'min' => 100.81,
            'max' => 151.2,
            'display_name' => 'Defined'
        ],
        'quantitative' => [
            'min' => 151.21,
            'max' => 201.6,
            'display_name' => 'Quantitatively Managed'
        ],
        'optimizing' => [
            'min' => 201.61,
            'max' => 252,
            'display_name' => 'Optimizing'
        ]
    ];

    /**
     * Control domain configuration
     */
    const CONTROL_LEVELS = 8; // Number of maturity levels per control (1-8)
    const MAX_CONTROL_SCORE = 36; // Maximum score per control
    const TOTAL_CONTROLS = 7; // Number of control domains
    const MAX_TOTAL_SCORE = 252; // Maximum total score (36 * 7)

    /**
     * File paths
     */
    private static $basePath = null;

    /**
     * Get base application path
     *
     * @return string
     */
    public static function getBasePath() {
        if (self::$basePath === null) {
            self::$basePath = dirname(__DIR__);
        }
        return self::$basePath;
    }

    /**
     * Get path to controls JSON file for a profile
     *
     * @param string $profile Profile name
     * @return string Full path to controls file
     */
    public static function getControlsPath($profile) {
        return self::getBasePath() . "/controls-{$profile}.json";
    }

    /**
     * Get path to compliance frameworks JSON
     *
     * @return string Full path to compliance.json
     */
    public static function getCompliancePath() {
        return self::getBasePath() . '/compliance.json';
    }

    /**
     * Get path to LOB data JSON
     *
     * @return string Full path to lob.json
     */
    public static function getLOBPath() {
        return self::getBasePath() . '/lob.json';
    }

    /**
     * Get path to LOB content directory
     *
     * @return string Full path to lob directory
     */
    public static function getLOBContentPath() {
        return self::getBasePath() . '/lob/';
    }

    /**
     * Get path to compliance content directory
     *
     * @return string Full path to compliance directory
     */
    public static function getComplianceContentPath() {
        return self::getBasePath() . '/compliance/';
    }

    /**
     * Get enabled profiles
     *
     * @return array Array of enabled profiles
     */
    public static function getEnabledProfiles() {
        return array_filter(self::PROFILES, function($profile) {
            return $profile['enabled'] === true;
        });
    }

    /**
     * Get profile display name
     *
     * @param string $profileName Profile name
     * @return string Display name
     */
    public static function getProfileDisplayName($profileName) {
        return self::PROFILES[$profileName]['display_name'] ?? $profileName;
    }

    /**
     * Check if a profile is valid
     *
     * @param string $profileName Profile name
     * @return bool True if valid
     */
    public static function isValidProfile($profileName) {
        return isset(self::PROFILES[$profileName]);
    }

    /**
     * Check if a profile is enabled
     *
     * @param string $profileName Profile name
     * @return bool True if enabled
     */
    public static function isProfileEnabled($profileName) {
        return self::PROFILES[$profileName]['enabled'] ?? false;
    }

    /**
     * Get all LOB options
     *
     * @return array LOB options
     */
    public static function getLOBOptions() {
        return self::LOB_OPTIONS;
    }

    /**
     * Check if LOB is valid
     *
     * @param string $lob LOB name
     * @return bool True if valid
     */
    public static function isValidLOB($lob) {
        return isset(self::LOB_OPTIONS[$lob]);
    }

    /**
     * Error handling configuration
     */
    const ERROR_REPORTING_ENABLED = true;
    const LOG_ERRORS = true;
    const DISPLAY_ERRORS = false; // Set to true only in development

    /**
     * Security configuration
     */
    const ENABLE_CSRF_PROTECTION = true;
    const SESSION_TIMEOUT = 3600; // 1 hour in seconds
}
