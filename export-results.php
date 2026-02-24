<?php
/**
 * Export Security Assessment Results
 *
 * Generates a downloadable JSON file from Security assessment results
 */

require_once __DIR__ . '/error-pages/error-handler.php';
require_once __DIR__ . '/includes/Security.php';
require_once __DIR__ . '/includes/MaturityRating.php';
require_once __DIR__ . '/includes/Logger.php';
require_once __DIR__ . '/includes/Config.php';
require_once __DIR__ . '/includes/ResultsExporter.php';
require_once __DIR__ . '/includes/Exceptions/ResultsException.php';

// Register error handlers
ErrorHandler::register();

try {
    Logger::info('Security results export initiated');

    // Parse query string to get current results data
    parse_str($_SERVER["QUERY_STRING"] ?? '', $data);

    // Validate profile parameter
    $profile = Security::validateProfile($data['profile'] ?? '');
    $data['profile'] = $profile;

    // Load controls JSON
    $controlsFile = Security::getControlsFilePath($profile);
    $json = Security::loadJSON($controlsFile);

    // Extract assessment data
    $controls = [];
    $controlScores = [];
    $totalScore = 0;

    // Parse control responses from query string
    // Only include controls with non-zero values (matches normal query string behavior)
    for ($i = 1; $i <= 7; $i++) {
        $controlScore = 0;
        for ($j = 1; $j <= 8; $j++) {
            $controlId = "control{$i}-{$j}";
            $value = isset($data[$controlId]) ? (int)$data[$controlId] : 0;
            // Only include non-zero controls to match expected behavior
            if ($value > 0) {
                $controls[$controlId] = $value;
            }
            $controlScore += $value;
        }
        $controlScores[$i] = $controlScore;
        $totalScore += $controlScore;
    }

    // Calculate overall rating
    $overallRating = MaturityRating::getTotalRating($totalScore);

    // Extract frameworks (if present) - handle both 'framework' and 'frameworks'
    $frameworks = [];
    if (isset($data['framework'])) {
        if (is_array($data['framework'])) {
            $frameworks = $data['framework'];
        } else {
            $frameworks = [$data['framework']];
        }
    } elseif (isset($data['frameworks'])) {
        if (is_array($data['frameworks'])) {
            $frameworks = $data['frameworks'];
        } else {
            $frameworks = [$data['frameworks']];
        }
    }

    // Build export data
    $exportData = [
        'profile' => $profile,
        'lob' => $data['lob'] ?? '',
        'frameworks' => $frameworks,
        'controls' => $controls,
        'total_score' => $totalScore,
        'overall_rating' => $overallRating,
        'control_scores' => $controlScores
    ];

    // Generate JSON content
    $jsonContent = ResultsExporter::exportSecurityResults($exportData);

    // Generate filename
    $filename = ResultsExporter::generateFilename('security', $profile);

    // Download the file
    ResultsExporter::downloadResults($jsonContent, $filename);

} catch (ViewfinderException $e) {
    Logger::logException($e);
    http_response_code(500);
    echo "Export failed: " . Security::escape($e->getUserMessage());
} catch (\Throwable $e) {
    Logger::error('Unexpected error in export-results.php', [
        'exception' => get_class($e),
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
    http_response_code(500);
    echo "Export failed: An unexpected error occurred.";
}
