<?php
require_once __DIR__ . '/Config.php';
require_once __DIR__ . '/Security.php';

/**
 * Maturity Rating Helper Class
 * Provides functions for calculating and displaying maturity ratings
 */
class MaturityRating {

    /**
     * Get maturity rating based on individual control score
     *
     * @param int $score The control score
     * @return string The rating level (Foundation, Strategic, Advanced, or Not Rated)
     */
    public static function getRating($score) {
        if ($score == 0) {
            return "Not Rated";
        }

        $levels = Config::MATURITY_LEVELS;

        if ($score >= $levels['strategic']['min'] && $score <= $levels['strategic']['max']) {
            return $levels['strategic']['display_name'];
        }

        if ($score >= $levels['advanced']['min']) {
            return $levels['advanced']['display_name'];
        }

        return $levels['foundation']['display_name'];
    }

    /**
     * Get overall maturity rating based on total score
     *
     * @param int $score The total score across all controls
     * @return string The overall rating level (Foundation, Strategic, or Advanced)
     */
    public static function getTotalRating($score) {
        $levels = Config::TOTAL_MATURITY_LEVELS;

        if ($score >= $levels['strategic']['min'] && $score <= $levels['strategic']['max']) {
            return $levels['strategic']['display_name'];
        }

        if ($score >= $levels['advanced']['min']) {
            return $levels['advanced']['display_name'];
        }

        return $levels['foundation']['display_name'];
    }

    /**
     * Get CSS class for rating display
     *
     * @param string $rating The rating level
     * @return string CSS class name
     */
    public static function getRatingClass($rating) {
        $rating = strtolower($rating);
        switch ($rating) {
            case 'advanced':
                return 'cellAdvanced';
            case 'strategic':
                return 'cellStrategic';
            case 'foundation':
                return 'cellFoundation';
            case 'not rated':
                return 'cellNotRated';
            default:
                return '';
        }
    }

    /**
     * Get completion status for a capability
     *
     * @param int $capabilityId The capability ID (1 = completed, anything else = not completed)
     * @return string Status string ('completed' or 'notcompleted')
     */
    public static function getStatus($capabilityId) {
        return ($capabilityId == "1") ? "completed" : "notcompleted";
    }

    /**
     * Output domain status cells for maturity table
     *
     * @param string|int $domainLevel The domain level (1-8)
     * @param array $controlDetails Array of control details
     * @param array $json JSON data containing domain information
     * @return void Outputs HTML directly
     */
    public static function putDomainStatus($domainLevel, $controlDetails, $json) {
        for ($ii = 1; $ii < 8; $ii++) {
            $dName = "Domain-" . $ii;
            $class = self::getStatus($controlDetails[$ii][$domainLevel] ?? 0);
            $content = $json[$dName][$domainLevel] ?? '';
            print '<td class="' . $class . '">' . Security::escape($content) . '</td>';
        }
    }
}
