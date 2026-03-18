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

// Generate random responses for all 56 capabilities (7 domains × 8 capabilities each)
// Using new maturity slider values: 0=No Capability, 1=In Planning, 2=Work in Progress, 3=Fully Complete
$totalWithMaturity = 0;
$domainScores = [];

for ($domain = 1; $domain <= 7; $domain++) {
    $domainScore = 0;
    $domainWithMaturity = 0;

    for ($item = 1; $item <= 8; $item++) {
        $controlId = "control{$domain}-{$item}";

        // Generate random maturity level (0-3)
        // Weight towards realistic maturity distribution:
        // - Most capabilities have some progress (1-2)
        // - Fewer fully complete (3)
        // - Some have no capability yet (0)
        $rand = rand(1, 100);
        if ($rand <= 15) {
            $maturityLevel = 0; // 15% No Capability
        } elseif ($rand <= 40) {
            $maturityLevel = 1; // 25% In Planning
        } elseif ($rand <= 75) {
            $maturityLevel = 2; // 35% Work in Progress
        } else {
            $maturityLevel = 3; // 25% Fully Complete
        }

        $_REQUEST[$controlId] = $maturityLevel;

        if ($maturityLevel > 0) {
            $domainWithMaturity++;
            $totalWithMaturity++;
        }

        // Track approximate score for display (not exact but representative)
        $domainScore += $maturityLevel * $item;
    }

    $domainScores[$domain] = [
        'avgMaturity' => $domainWithMaturity > 0 ? round(array_sum(array_slice($_REQUEST, -8)) / 8, 1) : 0,
        'withProgress' => $domainWithMaturity
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
echo "\nCapabilities with Maturity: $totalWithMaturity / 56\n";
echo "\nDomain Maturity Overview:\n";

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
    echo "  $domainName: {$stats['withProgress']}/8 capabilities with progress\n";
}
echo "-->\n\n";

// Build query string from $_REQUEST data so results.php can parse it
$_SERVER['QUERY_STRING'] = http_build_query($_REQUEST);

// Include the results page which will process the query string
require 'results.php';
