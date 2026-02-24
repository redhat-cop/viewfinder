<?php
/**
 * Export DS-Qualifier Assessment Results
 *
 * Generates a downloadable JSON file from DS-Qualifier assessment results
 */

require_once __DIR__ . '/../error-pages/error-handler.php';
require_once __DIR__ . '/../includes/Security.php';
require_once __DIR__ . '/../includes/Logger.php';
require_once __DIR__ . '/../includes/Config.php';
require_once __DIR__ . '/../includes/ResultsExporter.php';
require_once __DIR__ . '/../includes/Exceptions/ResultsException.php';

// Register error handlers
ErrorHandler::register();

try {
    Logger::info('DS-Qualifier results export initiated');

    // Start session to access assessment data
    session_start();

    // Check if assessment data exists in session
    if (!isset($_SESSION['assessment_data']) || empty($_SESSION['assessment_data'])) {
        throw new ResultsException(
            'No assessment data found in session',
            'No assessment results found. Please complete the assessment first.',
            []
        );
    }

    // Load questions configuration
    $questions = require_once 'config.php';

    // Initialize scoring arrays
    $totalScore = 0;
    $maxScore = 21;
    $domainScores = [];
    $responses = $_SESSION['assessment_data'];

    // Map domain keys to display names
    $domainKeyMap = [];
    foreach ($questions as $domainName => $domainData) {
        $domainKeyMap[$domainData['domain_key']] = $domainName;
        $domainScores[$domainName] = 0;
    }

    // Calculate scores
    foreach ($responses as $key => $value) {
        // Match question IDs (ds1, ts1, os1, etc.)
        if (preg_match('/^(ds|ts|os|as|oss|eo|ms)\d+$/', $key)) {
            // Find which domain this question belongs to
            foreach ($questions as $domainName => $domainData) {
                foreach ($domainData['questions'] as $question) {
                    if ($question['id'] === $key) {
                        // Handle "Don't Know" responses
                        if ($value !== 'unknown') {
                            $intValue = intval($value);
                            $totalScore += $intValue;
                            $domainScores[$domainName] += $intValue;
                        }
                        break 2;
                    }
                }
            }
        }
    }

    // Determine maturity level based on score
    if ($totalScore <= 5) {
        $maturityLevel = 'Foundation';
    } elseif ($totalScore <= 10) {
        $maturityLevel = 'Developing';
    } elseif ($totalScore <= 15) {
        $maturityLevel = 'Strategic';
    } else {
        $maturityLevel = 'Advanced';
    }

    // Build export data
    $exportData = [
        'responses' => $responses,
        'total_score' => $totalScore,
        'maturity_level' => $maturityLevel,
        'domain_scores' => $domainScores
    ];

    // Generate JSON content
    $jsonContent = ResultsExporter::exportDSQualifierResults($exportData);

    // Generate filename
    $filename = ResultsExporter::generateFilename('dsqualifier');

    // Download the file
    ResultsExporter::downloadResults($jsonContent, $filename);

} catch (ViewfinderException $e) {
    Logger::logException($e);
    http_response_code(500);
    echo "Export failed: " . Security::escape($e->getUserMessage());
} catch (\Throwable $e) {
    Logger::error('Unexpected error in ds-qualifier/export-results.php', [
        'exception' => get_class($e),
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
    http_response_code(500);
    echo "Export failed: An unexpected error occurred.";
}
