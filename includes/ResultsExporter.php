<?php
/**
 * ResultsExporter - Handles assessment results export functionality
 *
 * Generates downloadable JSON files from completed Security and DS-Qualifier assessments
 */

require_once __DIR__ . '/Config.php';
require_once __DIR__ . '/Security.php';
require_once __DIR__ . '/Logger.php';
require_once __DIR__ . '/Exceptions/ResultsException.php';

class ResultsExporter {

    /**
     * Export Security assessment results to JSON
     *
     * @param array $data Assessment data including profile, lob, frameworks, controls, and calculated results
     * @return string JSON string ready for download
     * @throws ResultsException If export fails
     */
    public static function exportSecurityResults(array $data): string {
        // Build export structure
        $exportData = [
            'viewfinder_export' => [
                'version' => '1.0',
                'type' => 'security_assessment',
                'export_date' => gmdate('Y-m-d\TH:i:s\Z'),
                'app_version' => Config::APP_VERSION
            ],
            'assessment' => [
                'profile' => $data['profile'] ?? '',
                'lob' => $data['lob'] ?? '',
                'frameworks' => $data['frameworks'] ?? [],
                'controls' => $data['controls'] ?? []
            ],
            'calculated_results' => [
                'total_score' => $data['total_score'] ?? 0,
                'overall_rating' => $data['overall_rating'] ?? '',
                'control_scores' => $data['control_scores'] ?? []
            ]
        ];

        // Encode JSON with pretty print
        $jsonString = json_encode(
            $exportData,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        );

        if ($jsonString === false) {
            throw ResultsException::invalidFormat(
                'JSON encoding failed: ' . json_last_error_msg(),
                ['error' => json_last_error_msg()]
            );
        }

        Logger::info('Security assessment results exported', [
            'profile' => $data['profile'] ?? 'unknown',
            'size' => strlen($jsonString)
        ]);

        return $jsonString;
    }

    /**
     * Export DS-Qualifier assessment results to JSON
     *
     * @param array $data Assessment data including responses and calculated results
     * @return string JSON string ready for download
     * @throws ResultsException If export fails
     */
    public static function exportDSQualifierResults(array $data): string {
        // Build export structure
        $exportData = [
            'viewfinder_export' => [
                'version' => '1.0',
                'type' => 'ds_qualifier',
                'export_date' => gmdate('Y-m-d\TH:i:s\Z'),
                'app_version' => Config::APP_VERSION
            ],
            'assessment' => [
                'responses' => $data['responses'] ?? []
            ],
            'calculated_results' => [
                'total_score' => $data['total_score'] ?? 0,
                'maturity_level' => $data['maturity_level'] ?? '',
                'domain_scores' => $data['domain_scores'] ?? []
            ]
        ];

        // Encode JSON with pretty print
        $jsonString = json_encode(
            $exportData,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        );

        if ($jsonString === false) {
            throw ResultsException::invalidFormat(
                'JSON encoding failed: ' . json_last_error_msg(),
                ['error' => json_last_error_msg()]
            );
        }

        Logger::info('DS-Qualifier assessment results exported', [
            'size' => strlen($jsonString)
        ]);

        return $jsonString;
    }

    /**
     * Generate unique filename for results export
     *
     * @param string $type Assessment type ('security' or 'dsqualifier')
     * @param string $profile Profile name (for Security assessments, empty for DS-Qualifier)
     * @return string Filename in format: viewfinder-readiness-assessment-{yyyymmdd}-{hhmm}.json or viewfinder-{profile}-{yyyymmdd}-{hhmm}.json
     */
    public static function generateFilename(string $type, string $profile = ''): string {
        $timestamp = gmdate('Ymd-Hi');

        if ($type === 'security' && !empty($profile)) {
            return "viewfinder-{$profile}-{$timestamp}.json";
        } elseif ($type === 'dsqualifier') {
            return "viewfinder-readiness-assessment-{$timestamp}.json";
        }

        // Fallback
        return "viewfinder-{$type}-{$timestamp}.json";
    }

    /**
     * Stream results file as download
     *
     * @param string $jsonContent JSON content to download
     * @param string $filename Filename for download
     * @return void Sets headers and outputs JSON
     */
    public static function downloadResults(string $jsonContent, string $filename): void {
        // Sanitize filename
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

        Logger::info('Results download initiated', [
            'filename' => $safeFilename,
            'size' => strlen($jsonContent)
        ]);
    }
}
