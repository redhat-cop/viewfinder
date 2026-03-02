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
    $rawTotalScore = 0;

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
        $rawTotalScore += $controlScore;
    }

    // ==========================================
    // WEIGHTED SCORING IMPLEMENTATION
    // ==========================================

    // Load LOB weights
    $lobWeights = require_once __DIR__ . '/lob-weights.php';

    // Get selected LOB (default to 'General' if not set)
    // Note: 'Balanced' is mapped to 'General' for consistency with Config::LOB_OPTIONS
    $selectedLob = Security::validateLOB($data['lob'] ?? '');
    if ($selectedLob === null || $selectedLob === 'Balanced') {
        $selectedLob = 'General';
    }

    // Get weights for this profile and LOB
    $domainWeights = [];
    if (isset($lobWeights[$profile]) && isset($lobWeights[$profile][$selectedLob])) {
        $domainWeights = $lobWeights[$profile][$selectedLob]['weights'];
    } else {
        // Fallback to balanced weights (all 1.0)
        foreach ($json as $control) {
            if (isset($control['title'])) {
                $domainWeights[$control['title']] = 1.0;
            }
        }
    }

    // Calculate weighted score
    $weightedSum = 0;
    $totalWeight = 0;
    $maxPossiblePerDomain = 36; // Each domain has max 36 points (9 questions × 4 levels)

    for ($i = 1; $i <= 7; $i++) {
        $controlKey = "Domain-$i";
        if (isset($json[$controlKey]['title'])) {
            $title = $json[$controlKey]['title'];
            $domainScore = $controlScores[$i];

            // Get weight for this domain (default 1.0 if not found)
            $weight = isset($domainWeights[$title]) ? $domainWeights[$title] : 1.0;

            // Calculate weighted contribution
            $domainPercentage = $domainScore / $maxPossiblePerDomain;
            $weightedDomainScore = $domainPercentage * $weight;

            $weightedSum += $weightedDomainScore;
            $totalWeight += $weight;
        }
    }

    // Normalize weighted score to 0-252 scale (7 domains × 36 max points)
    $totalScore = $totalWeight > 0 ? ($weightedSum / $totalWeight) * (7 * $maxPossiblePerDomain) : 0;
    $totalScore = round($totalScore);

    // Calculate overall rating using weighted score
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

    // Capture domain notes
    $domainNotes = [];
    foreach ($data as $key => $value) {
        if (strpos($key, 'domain_notes_') === 0 && !empty(trim($value))) {
            $domainNotes[$key] = $value;
        }
    }

    // Build export data
    $exportData = [
        'profile' => $profile,
        'lob' => $selectedLob,
        'frameworks' => $frameworks,
        'controls' => $controls,
        'raw_total_score' => $rawTotalScore,      // Unweighted score
        'weighted_total_score' => $totalScore,    // Weighted score (used for rating)
        'total_score' => $totalScore,             // For backward compatibility
        'overall_rating' => $overallRating,
        'control_scores' => $controlScores,
        'domain_weights' => $domainWeights,       // Include weight information
        'domain_notes' => $domainNotes            // Include facilitator notes
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
