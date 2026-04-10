<?php
/**
 * Quick Red Hat Solutions Test - Minimal Test Case
 * Shows just 3 key Red Hat solutions for fast testing
 *
 * Usage: Visit in browser: http://localhost/viewfinder-redhat/test-redhat-quick.php
 */

// Set profile to Digital Sovereignty
$_REQUEST['profile'] = 'DigitalSovereignty';
$_REQUEST['lob'] = 'Finance';

echo "<!-- \n";
echo "========================================\n";
echo "QUICK RED HAT SOLUTIONS TEST\n";
echo "========================================\n";
echo "This test shows 3 key Red Hat solutions:\n\n";
echo "1. Data Sovereignty - OpenShift Observability\n";
echo "2. Technical Sovereignty - Trusted Software Supply Chain\n";
echo "3. Managed Services - OpenShift Service Mesh\n";
echo "========================================\n";
echo "-->\n\n";

// Initialize capabilities with varied maturity (some at 0 for recommendations)
for ($domain = 1; $domain <= 7; $domain++) {
    for ($item = 1; $item <= 8; $item++) {
        $controlId = "control{$domain}-{$item}";
        // Create a realistic distribution: some complete, some in progress, some not started
        $rand = rand(1, 100);
        if ($rand <= 20) {
            $_REQUEST[$controlId] = 0; // 20% not started
        } elseif ($rand <= 50) {
            $_REQUEST[$controlId] = 1; // 30% planning
        } elseif ($rand <= 80) {
            $_REQUEST[$controlId] = 2; // 30% in progress
        } else {
            $_REQUEST[$controlId] = 3; // 20% complete
        }
    }
}

// === TEST CASE 1: Data Sovereignty - OpenShift Observability ===
// Domain 1, Capability 7
// Set capabilities 1-6 to completed, leave 7 incomplete
for ($i = 1; $i <= 6; $i++) {
    $_REQUEST["control1-{$i}"] = 3; // Fully complete
}
$_REQUEST["control1-7"] = 0; // Not started - will be recommended
$_REQUEST["control1-8"] = 0; // Leave 8 also incomplete

// === TEST CASE 2: Technical Sovereignty - Trusted Software Supply Chain ===
// Domain 2, Capability 7
// Set capabilities 1-6 to completed, leave 7 incomplete
for ($i = 1; $i <= 6; $i++) {
    $_REQUEST["control2-{$i}"] = 3; // Fully complete
}
$_REQUEST["control2-7"] = 0; // Not started - will be recommended
$_REQUEST["control2-8"] = 0;

// === TEST CASE 3: Managed Services - OpenShift Service Mesh ===
// Domain 7, Capability 5
// Set capabilities 1-4 to completed, leave 5 incomplete
for ($i = 1; $i <= 4; $i++) {
    $_REQUEST["control7-{$i}"] = 3; // Fully complete
}
$_REQUEST["control7-5"] = 0; // Not started - will be recommended
$_REQUEST["control7-6"] = 0;
$_REQUEST["control7-7"] = 0;
$_REQUEST["control7-8"] = 0;

// Add a workshop note to make it realistic
$_REQUEST['domain_notes_2'] = "Team interested in Red Hat solutions for software supply chain security.";

// Build query string and load results
$_SERVER['QUERY_STRING'] = http_build_query($_REQUEST);
require 'results.php';
