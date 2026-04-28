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
     * @return string The rating level (Initial, Managed, Defined, Quantitatively Managed, Optimizing, or Not Rated)
     */
    public static function getRating($score) {
        if ($score == 0) {
            return "Not Rated";
        }

        $levels = Config::MATURITY_LEVELS;

        // Check levels in descending order for accurate classification
        if ($score >= $levels['optimizing']['min']) {
            return $levels['optimizing']['display_name'];
        }

        if ($score >= $levels['quantitative']['min'] && $score <= $levels['quantitative']['max']) {
            return $levels['quantitative']['display_name'];
        }

        if ($score >= $levels['defined']['min'] && $score <= $levels['defined']['max']) {
            return $levels['defined']['display_name'];
        }

        if ($score >= $levels['managed']['min'] && $score <= $levels['managed']['max']) {
            return $levels['managed']['display_name'];
        }

        return $levels['initial']['display_name'];
    }

    /**
     * Get overall maturity rating based on total score
     *
     * @param int $score The total score across all controls
     * @return string The overall rating level (Initial, Managed, Defined, Quantitatively Managed, or Optimizing)
     */
    public static function getTotalRating($score) {
        $levels = Config::TOTAL_MATURITY_LEVELS;

        // Check levels in descending order for accurate classification
        if ($score >= $levels['optimizing']['min']) {
            return $levels['optimizing']['display_name'];
        }

        if ($score >= $levels['quantitative']['min'] && $score <= $levels['quantitative']['max']) {
            return $levels['quantitative']['display_name'];
        }

        if ($score >= $levels['defined']['min'] && $score <= $levels['defined']['max']) {
            return $levels['defined']['display_name'];
        }

        if ($score >= $levels['managed']['min'] && $score <= $levels['managed']['max']) {
            return $levels['managed']['display_name'];
        }

        return $levels['initial']['display_name'];
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
            case 'optimizing':
                return 'cellOptimizing';
            case 'quantitatively managed':
                return 'cellQuantitative';
            case 'defined':
                return 'cellDefined';
            case 'managed':
                return 'cellManaged';
            case 'initial':
                return 'cellInitial';
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
     * @param array $controls Array of domain keys to display
     * @return void Outputs HTML directly
     */
    public static function putDomainStatus($domainLevel, $controlDetails, $json, $controls = null) {
        // If no controls array provided, use legacy behavior (domains 1-7)
        if ($controls === null) {
            for ($ii = 1; $ii < 8; $ii++) {
                $dName = "Domain-" . $ii;
                $class = self::getStatus($controlDetails[$ii][$domainLevel] ?? 0);
                $content = $json[$dName][$domainLevel] ?? '';
                print '<td class="' . $class . '">' . Security::escape($content) . '</td>';
            }
            return;
        }

        // New behavior: iterate through provided controls array
        foreach ($controls as $control) {
            $qnum = $json[$control]['qnum'];
            $class = self::getStatus($controlDetails[$qnum][$domainLevel] ?? 0);
            $content = $json[$control][$domainLevel] ?? '';

            // Check if this domain includes sub-domains
            if (isset($json[$control]['includes_subdomains']) && $json[$control]['includes_subdomains'] === true) {
                if (isset($json[$control]['section_2_source'])) {
                    $subdomainKey = $json[$control]['section_2_source'];
                    if (isset($json[$subdomainKey])) {
                        $subQnum = $json[$subdomainKey]['qnum'];
                        $subContent = $json[$subdomainKey][$domainLevel] ?? '';
                        $subClass = self::getStatus($controlDetails[$subQnum][$domainLevel] ?? 0);

                        // Display both core and sub-domain capabilities
                        // Use the "selected" status if either is selected
                        if ($class === 'selected' || $subClass === 'selected') {
                            $class = 'selected';
                        }

                        // Combine content with line break
                        if (!empty($content) && !empty($subContent)) {
                            $content = Security::escape($content) . '<br><span style="font-size: 0.85em; font-style: italic; opacity: 0.9;">' . Security::escape($subContent) . '</span>';
                        } elseif (!empty($subContent)) {
                            $content = '<span style="font-size: 0.85em; font-style: italic; opacity: 0.9;">' . Security::escape($subContent) . '</span>';
                        } else {
                            $content = Security::escape($content);
                        }
                        print '<td class="' . $class . '">' . $content . '</td>';
                        continue;
                    }
                }
            }

            print '<td class="' . $class . '">' . Security::escape($content) . '</td>';
        }
    }
}
