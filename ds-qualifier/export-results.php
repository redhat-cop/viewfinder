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

    // Load profiles
    $profiles = require_once __DIR__ . '/profiles.php';

    // Initialize scoring arrays
    $totalScore = 0;              // Raw unweighted score
    $weightedScore = 0;           // Weighted score
    $maxScore = 21;
    $domainScores = [];
    $domainMaxScores = [];
    $domainWeightedScores = [];
    $responses = $_SESSION['assessment_data'];

    // Get selected profile
    $selectedProfile = isset($responses['profile']) ? $responses['profile'] : 'balanced';
    if (!isset($profiles[$selectedProfile])) {
        $selectedProfile = 'balanced';
    }

    // Load domain weights
    if ($selectedProfile === 'custom') {
        $domainWeights = [];
        foreach ($questions as $domainName => $domainData) {
            $paramName = 'custom_weight_' . str_replace(' ', '_', $domainName);
            if (isset($responses[$paramName])) {
                $weight = floatval($responses[$paramName]);
                $domainWeights[$domainName] = max(1.0, min(2.0, $weight));
            } else {
                $domainWeights[$domainName] = 1.0;
            }
        }
    } else {
        $domainWeights = $profiles[$selectedProfile]['weights'];
    }

    // Initialize domain scores
    foreach ($questions as $domainName => $domainData) {
        $domainScores[$domainName] = 0;
        $domainMaxScores[$domainName] = count($domainData['questions']);
    }

    // Calculate raw scores
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

    // Calculate weighted scores per domain
    $totalWeight = 0;
    $weightedSum = 0;

    foreach ($domainScores as $domainName => $score) {
        $maxForDomain = $domainMaxScores[$domainName];
        $weight = $domainWeights[$domainName] ?? 1.0;

        // Calculate percentage for this domain (0-1)
        $domainPercentage = $maxForDomain > 0 ? ($score / $maxForDomain) : 0;

        // Apply weight
        $weightedDomainScore = $domainPercentage * $weight;
        $domainWeightedScores[$domainName] = $weightedDomainScore;

        $weightedSum += $weightedDomainScore;
        $totalWeight += $weight;
    }

    // Normalize weighted score to 0-21 scale
    $weightedScore = $totalWeight > 0 ? ($weightedSum / $totalWeight) * 21 : 0;
    $scorePercentage = round(($weightedScore / $maxScore) * 100);

    // Determine maturity level based on weighted score (5-Level Maturity Model)
    if ($weightedScore <= 4.2) {
        $maturityLevel = 'Initial';
    } elseif ($weightedScore <= 8.4) {
        $maturityLevel = 'Managed';
    } elseif ($weightedScore <= 12.6) {
        $maturityLevel = 'Defined';
    } elseif ($weightedScore <= 16.8) {
        $maturityLevel = 'Quantitatively Managed';
    } else {
        $maturityLevel = 'Optimizing';
    }

    // Build export data
    $exportData = [
        'responses' => $responses,
        'total_score' => $totalScore,           // Raw score
        'weighted_score' => $weightedScore,     // NEW: Weighted score
        'score_percentage' => $scorePercentage,
        'maturity_level' => $maturityLevel,
        'profile' => $selectedProfile,          // NEW: Profile used
        'domain_weights' => $domainWeights,     // NEW: Weights applied
        'assessment_date' => date('Y-m-d H:i:s'),
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
