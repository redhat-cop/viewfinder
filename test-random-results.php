<?php
/**
 * Random Results Generator - For Testing
 * Generates random assessment responses and displays the results page
 *
 * Usage: php test-random-results.php
 * Or visit in browser: http://localhost/viewfinder-redhat/test-random-results.php
 */

// Set profile to Digital Sovereignty
$_REQUEST['profile'] = 'DigitalSovereignty';

// Randomly select a Line of Business
$lobOptions = ['Finance', 'Healthcare', 'Government', 'Manufacturing', 'Telecommunications', 'General'];
$_REQUEST['lob'] = $lobOptions[array_rand($lobOptions)];

// Randomly select compliance frameworks (0-3 frameworks)
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

// Generate random responses for all 56 questions (7 domains × 8 questions each)
$totalChecked = 0;
$domainScores = [];

for ($domain = 1; $domain <= 7; $domain++) {
    $domainScore = 0;
    $domainChecked = 0;

    for ($item = 1; $item <= 8; $item++) {
        $controlId = "control{$domain}-{$item}";

        // Randomly decide if this checkbox should be checked
        // Higher items (higher points) have lower probability of being checked
        // This creates more realistic distribution across maturity levels
        $probability = 100 - ($item * 10); // Item 1: 90%, Item 2: 80%, ... Item 8: 20%

        if (rand(1, 100) <= $probability) {
            $_REQUEST[$controlId] = $item; // Value is the point value
            $domainScore += $item;
            $domainChecked++;
            $totalChecked++;
        }
    }

    $domainScores[$domain] = [
        'score' => $domainScore,
        'checked' => $domainChecked
    ];

    // Randomly add facilitator notes for some domains (50% chance)
    if (rand(0, 1) === 1) {
        $sampleNotes = [
            "Strong engagement from the team during this section.",
            "Concerns raised about vendor lock-in with current cloud providers.",
            "Identified quick wins: implementing BYOK encryption within 30 days.",
            "Team demonstrated good understanding of data residency requirements.",
            "Need follow-up discussion with legal team on GDPR/NIS2 compliance.",
            "Budget constraints mentioned for sovereign cloud migration.",
            "Open source tools available but training required for technical team.",
            "Strong sponsorship from CISO for Digital Sovereignty improvements.",
            "Current multi-cloud strategy provides good foundation for sovereignty.",
            "Key dependency identified: external SaaS providers need contract review."
        ];
        $_REQUEST["domain_notes_{$domain}"] = $sampleNotes[array_rand($sampleNotes)];
    }
}

// Output the generated responses for reference
echo "<!-- Generated Random Assessment:\n";
echo "Profile: Digital Sovereignty\n";
echo "LOB: " . $_REQUEST['lob'] . "\n";
if (isset($_REQUEST['framework'])) {
    echo "Frameworks: " . implode(', ', $_REQUEST['framework']) . "\n";
}
echo "\nTotal Controls Selected: $totalChecked / 56\n";
echo "\nDomain Scores:\n";

$domainNames = [
    1 => 'Data Sovereignty',
    2 => 'Technical Sovereignty',
    3 => 'Operational Sovereignty',
    4 => 'Assurance Sovereignty',
    5 => 'Open Source Sovereignty',
    6 => 'Executive Oversight',
    7 => 'Managed Services Sovereignty'
];

foreach ($domainScores as $domainNum => $stats) {
    $domainName = $domainNames[$domainNum] ?? "Domain $domainNum";
    echo "  $domainName: {$stats['score']} points ({$stats['checked']}/8 controls)\n";
}
echo "-->\n\n";

// Build query string from $_REQUEST data so results.php can parse it
$_SERVER['QUERY_STRING'] = http_build_query($_REQUEST);

// Include the results page which will process the query string
require 'results.php';
