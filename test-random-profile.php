<?php
/**
 * Generic Random Results Generator - Profile-Aware
 * Generates random assessment responses for any profile
 *
 * Usage:
 * - http://localhost/viewfinder-redhat/test-random-profile.php?profile=DigitalSovereignty
 * - http://localhost/viewfinder-redhat/test-random-profile.php?profile=AISovereignty
 * - http://localhost/viewfinder-redhat/test-random-profile.php?profile=Security
 */

require_once __DIR__ . '/includes/Config.php';
require_once __DIR__ . '/includes/Security.php';

// Get profile from URL parameter, default to DigitalSovereignty
$profile = isset($_GET['profile']) ? $_GET['profile'] : 'DigitalSovereignty';

// Validate profile
if (!Config::isValidProfile($profile) || !Config::isProfileEnabled($profile)) {
    die("Error: Invalid or disabled profile '$profile'. Please use a valid profile (DigitalSovereignty, AISovereignty, or Security).");
}

// Set profile
$_REQUEST['profile'] = $profile;

// Randomly select a Line of Business
$lobOptions = ['Finance', 'Healthcare', 'Government', 'Manufacturing', 'Telecommunications', 'General'];
$_REQUEST['lob'] = $lobOptions[array_rand($lobOptions)];

// Randomly select compliance frameworks (0-3 frameworks) - only for profiles that use them
if ($profile === 'DigitalSovereignty' || $profile === 'Security') {
    $frameworkOptions = [
        'GDPR',
        'NIS2',
        'DORA',
        'ISO 27001',
        'NIST CSF',
        'SecNumCloud',
        'C5'
    ];
    $numFrameworks = rand(0, 3);
    if ($numFrameworks > 0) {
        $selectedFrameworks = array_rand(array_flip($frameworkOptions), $numFrameworks);
        if (!is_array($selectedFrameworks)) {
            $selectedFrameworks = [$selectedFrameworks];
        }
        $_REQUEST['framework'] = $selectedFrameworks;
    }
}

// Generate random responses for all 56 capabilities (7 domains × 8 capabilities each)
// Using maturity slider values: 0=No Capability, 1=In Planning, 2=Work in Progress, 3=Fully Complete

// First, assign each domain a performance tier for more realistic variance
// This creates some strong domains, some developing, and some that need attention
$domainTiers = [];
$tierDistribution = ['strong', 'strong', 'developing', 'developing', 'developing', 'needs-attention', 'needs-attention'];
shuffle($tierDistribution); // Randomize which domains get which tier

for ($i = 1; $i <= 7; $i++) {
    $domainTiers[$i] = $tierDistribution[$i - 1];
}

$totalWithMaturity = 0;
$domainScores = [];

for ($domain = 1; $domain <= 7; $domain++) {
    $domainScore = 0;
    $domainWithMaturity = 0;
    $tier = $domainTiers[$domain];

    for ($item = 1; $item <= 8; $item++) {
        $controlId = "control{$domain}-{$item}";

        // Generate maturity level based on domain tier
        // Each tier has a different probability distribution
        $rand = rand(1, 100);

        if ($tier === 'strong') {
            // Strong domains: mostly 2s and 3s, few 1s, no 0s
            if ($rand <= 10) {
                $maturityLevel = 1; // 10% In Planning
            } elseif ($rand <= 50) {
                $maturityLevel = 2; // 40% Work in Progress
            } else {
                $maturityLevel = 3; // 50% Fully Complete
            }
        } elseif ($tier === 'developing') {
            // Developing domains: mix of 1s and 2s, some 3s, few 0s
            if ($rand <= 10) {
                $maturityLevel = 0; // 10% No Capability
            } elseif ($rand <= 35) {
                $maturityLevel = 1; // 25% In Planning
            } elseif ($rand <= 75) {
                $maturityLevel = 2; // 40% Work in Progress
            } else {
                $maturityLevel = 3; // 25% Fully Complete
            }
        } else { // needs-attention
            // Needs attention domains: mostly 0s and 1s, few 2s, no 3s
            if ($rand <= 40) {
                $maturityLevel = 0; // 40% No Capability
            } elseif ($rand <= 75) {
                $maturityLevel = 1; // 35% In Planning
            } elseif ($rand <= 95) {
                $maturityLevel = 2; // 20% Work in Progress
            } else {
                $maturityLevel = 3; // 5% Fully Complete (rare)
            }
        }

        $_REQUEST[$controlId] = $maturityLevel;

        if ($maturityLevel > 0) {
            $domainWithMaturity++;
            $totalWithMaturity++;
        }
        $domainScore += $maturityLevel;
    }

    $domainScores[$domain] = [
        'total' => $domainScore,
        'withProgress' => $domainWithMaturity,
        'tier' => $tier
    ];
}

// Generate HTML comment with statistics
$profileDisplayName = Config::getProfileDisplayName($profile);
echo "<!--\n";
echo "Random Test Data Generated for: {$profileDisplayName}\n";
echo "LOB: {$_REQUEST['lob']}\n";
echo "Capabilities with progress: {$totalWithMaturity}/56 (" . round($totalWithMaturity / 56 * 100) . "%)\n";
echo "\nDomain breakdown:\n";

// Load JSON to get domain names
$json = Security::loadJSON(Security::getControlsFilePath($profile));
$domainNames = [];
foreach ($json as $key => $value) {
    if (strpos($key, 'Domain-') === 0 && isset($value['qnum'])) {
        $domainNames[$value['qnum']] = $value['title'];
    }
}

for ($domainNum = 1; $domainNum <= 7; $domainNum++) {
    $stats = $domainScores[$domainNum];
    $domainName = $domainNames[$domainNum] ?? "Domain $domainNum";
    $tier = ucfirst(str_replace('-', ' ', $stats['tier'])); // Format tier name
    $avgScore = round(($stats['total'] / 24) * 100); // Calculate percentage (max 24 = 8 capabilities × 3 points)
    echo "  $domainName: {$stats['withProgress']}/8 capabilities, ~$avgScore% ($tier)\n";
}
echo "-->\n\n";

// Build query string from $_REQUEST data so results.php can parse it
$_SERVER['QUERY_STRING'] = http_build_query($_REQUEST);

// Include the results page which will process the query string
require 'results.php';
?>
