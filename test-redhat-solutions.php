<?php
/**
 * Red Hat Solutions Test Generator - For Testing Red Hat Recommendations
 * Generates assessment responses specifically to show Red Hat solution recommendations
 *
 * This test file strategically sets capability maturity levels to ensure that
 * capabilities with Red Hat solutions are shown as "next recommended" items.
 *
 * Usage: Visit in browser: http://localhost/viewfinder-redhat/test-redhat-solutions.php
 */

// Set profile to Digital Sovereignty
$_REQUEST['profile'] = 'DigitalSovereignty';
$_REQUEST['lob'] = 'Finance'; // Use Finance for industry-specific recommendations

// Map of capabilities that have Red Hat solutions
// Format: domain_number => [capability_numbers_with_solutions]
$redhatCapabilities = [
    1 => [7],           // Data Sovereignty: Capability 7
    2 => [1, 3, 4, 7],  // Technical Sovereignty: Capabilities 1, 3, 4, 7
    3 => [1, 4],        // Operational Sovereignty: Capabilities 1, 4
    4 => [1],           // Assurance Sovereignty: Capability 1
    5 => [2, 6],        // Open Source: Capabilities 2, 6
    6 => [3],           // Executive Oversight: Capability 3
    7 => [2, 5, 6]      // Managed Services: Capabilities 2, 5, 6
];

echo "<!-- \n";
echo "========================================\n";
echo "RED HAT SOLUTIONS TEST GENERATOR\n";
echo "========================================\n";
echo "This test generates results showing Red Hat solution recommendations.\n\n";
echo "Red Hat Solutions will be displayed for:\n";

$domainNames = [
    1 => 'Data Sovereignty',
    2 => 'Technical Sovereignty',
    3 => 'Operational Sovereignty',
    4 => 'Assurance Sovereignty',
    5 => 'Open Source',
    6 => 'Executive Oversight',
    7 => 'Managed Services'
];

// Generate responses for all 56 capabilities
for ($domain = 1; $domain <= 7; $domain++) {
    $rhCapabilities = $redhatCapabilities[$domain] ?? [];

    if (!empty($rhCapabilities)) {
        echo "\n" . $domainNames[$domain] . ":\n";
    }

    for ($item = 1; $item <= 8; $item++) {
        $controlId = "control{$domain}-{$item}";

        // Check if this capability has a Red Hat solution
        $hasRedHatSolution = in_array($item, $rhCapabilities);

        if ($hasRedHatSolution) {
            // This capability has a Red Hat solution
            // Set all previous capabilities (1 to item-1) to have some progress
            // Leave this one incomplete so it becomes the "next recommendation"

            // Go back and ensure previous capabilities have maturity
            for ($prev = 1; $prev < $item; $prev++) {
                $prevControlId = "control{$domain}-{$prev}";
                if (!isset($_REQUEST[$prevControlId])) {
                    // Set to random maturity level (1-3, not 0)
                    $_REQUEST[$prevControlId] = rand(1, 3);
                }
            }

            // Set this capability to 0 (not started) so it will be recommended
            $_REQUEST[$controlId] = 0;

            // Get capability name from JSON (we'll display this in the comment)
            echo "  - Capability $item (will show Red Hat recommendation)\n";

        } else {
            // This capability doesn't have a Red Hat solution
            // Set to random maturity if not already set by previous logic
            // Weight toward having some incomplete capabilities for recommendations
            if (!isset($_REQUEST[$controlId])) {
                $rand = rand(1, 100);
                if ($rand <= 25) {
                    $_REQUEST[$controlId] = 0; // 25% not started
                } elseif ($rand <= 50) {
                    $_REQUEST[$controlId] = 1; // 25% planning
                } elseif ($rand <= 75) {
                    $_REQUEST[$controlId] = 2; // 25% in progress
                } else {
                    $_REQUEST[$controlId] = 3; // 25% complete
                }
            }
        }
    }
}

echo "\n========================================\n";
echo "Look for red-bordered boxes with:\n";
echo "  - Cube icon (🧊)\n";
echo "  - 'Red Hat Solution' heading\n";
echo "  - Product name in bold\n";
echo "  - Description text\n";
echo "\nCheck these tabs:\n";
echo "  1. Details (Recommendations) - Accordion sections\n";
echo "  2. Report - Detailed PDF view\n";
echo "========================================\n";
echo "-->\n\n";

// Add some workshop notes to make it more realistic
$_REQUEST['domain_notes_2'] = "Technical team expressed interest in Red Hat solutions for containerization and automation.";
$_REQUEST['domain_notes_5'] = "Discussion around building internal open source expertise and contribution strategy.";
$_REQUEST['domain_notes_7'] = "Need to evaluate sovereign image registry and service mesh solutions.";

// Build query string from $_REQUEST data so results.php can parse it
$_SERVER['QUERY_STRING'] = http_build_query($_REQUEST);

// Include the results page which will process the query string
require 'results.php';
