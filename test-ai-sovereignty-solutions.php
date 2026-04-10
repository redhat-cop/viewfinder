<?php
/**
 * Comprehensive Test for AI Sovereignty - All Red Hat Solutions
 *
 * This test generates a realistic AI Sovereignty assessment designed to showcase
 * ALL Red Hat solutions mapped in controls-AISovereignty.json.
 *
 * Strategy: Sets capabilities to strategic maturity levels that trigger
 * recommendations for capabilities with Red Hat solutions.
 *
 * Red Hat AI Solutions Showcased:
 * 1. Red Hat OpenShift Data Foundation (Domain 1, Cap 1)
 * 2. Red Hat OpenShift AI (Domain 1, Cap 5; Domain 2, Cap 4; Domain 6, Cap 1, 5; Domain 7, Cap 2)
 * 3. Red Hat OpenShift Service Mesh (Domain 1, Cap 7)
 * 4. Red Hat Quay (Domain 2, Cap 2; Domain 3, Cap 3; Domain 4, Cap 5)
 * 5. Red Hat OpenShift (Domain 2, Cap 6; Domain 3, Cap 1, 2)
 * 6. Red Hat AI Inference Server (Domain 3, Cap 6)
 * 7. Red Hat Device Edge (Domain 3, Cap 7)
 * 8. Red Hat OpenShift Observability (Domain 6, Cap 2)
 */

// Simulate form submission for AI Sovereignty profile
$_REQUEST = [];
$_REQUEST['profile'] = 'AISovereignty';
$_REQUEST['lob'] = 'General';

// Generate realistic maturity distribution
// 15% not started (0), 25% planning (1), 30% in progress (2), 30% complete (3)
function getRealisticMaturity() {
    $rand = rand(1, 100);
    if ($rand <= 15) return 0; // 15% not started
    if ($rand <= 40) return 1; // 25% planning
    if ($rand <= 70) return 2; // 30% in progress
    return 3; // 30% complete
}

// Domain 1: AI Data Sovereignty
// Ensure Cap 1, 5, 7 are incomplete to show Red Hat solutions
for ($cap = 1; $cap <= 8; $cap++) {
    $controlId = "control1-{$cap}";
    if ($cap == 1) {
        $_REQUEST[$controlId] = 0; // Not started - will show OpenShift Data Foundation
    } elseif ($cap == 5) {
        $_REQUEST[$controlId] = 1; // Planning - will show OpenShift AI
    } elseif ($cap == 7) {
        $_REQUEST[$controlId] = 0; // Not started - will show OpenShift Service Mesh
    } else {
        $_REQUEST[$controlId] = getRealisticMaturity();
    }
}

// Domain 2: AI Model Sovereignty
// Ensure Cap 2, 4, 6 are incomplete to show Red Hat solutions
for ($cap = 1; $cap <= 8; $cap++) {
    $controlId = "control2-{$cap}";
    if ($cap == 2) {
        $_REQUEST[$controlId] = 1; // Planning - will show Quay
    } elseif ($cap == 4) {
        $_REQUEST[$controlId] = 0; // Not started - will show OpenShift AI
    } elseif ($cap == 6) {
        $_REQUEST[$controlId] = 1; // Planning - will show OpenShift
    } else {
        $_REQUEST[$controlId] = getRealisticMaturity();
    }
}

// Domain 3: AI Infrastructure Sovereignty
// Ensure Cap 1, 2, 3, 6, 7 are incomplete to show Red Hat solutions
for ($cap = 1; $cap <= 8; $cap++) {
    $controlId = "control3-{$cap}";
    if ($cap == 1) {
        $_REQUEST[$controlId] = 0; // Not started - will show OpenShift
    } elseif ($cap == 2) {
        $_REQUEST[$controlId] = 1; // Planning - will show OpenShift
    } elseif ($cap == 3) {
        $_REQUEST[$controlId] = 0; // Not started - will show Quay
    } elseif ($cap == 6) {
        $_REQUEST[$controlId] = 1; // Planning - will show Red Hat AI Inference Server
    } elseif ($cap == 7) {
        $_REQUEST[$controlId] = 0; // Not started - will show Red Hat Device Edge
    } else {
        $_REQUEST[$controlId] = getRealisticMaturity();
    }
}

// Domain 4: AI Supply Chain Sovereignty
// Ensure Cap 5 is incomplete to show Quay
for ($cap = 1; $cap <= 8; $cap++) {
    $controlId = "control4-{$cap}";
    if ($cap == 5) {
        $_REQUEST[$controlId] = 1; // Planning - will show Quay
    } else {
        $_REQUEST[$controlId] = getRealisticMaturity();
    }
}

// Domain 5: AI Governance & Compliance
// No Red Hat solutions mapped - use realistic distribution
for ($cap = 1; $cap <= 8; $cap++) {
    $controlId = "control5-{$cap}";
    $_REQUEST[$controlId] = getRealisticMaturity();
}

// Domain 6: AI Operations Sovereignty
// Ensure Cap 1, 2, 5 are incomplete to show Red Hat solutions
for ($cap = 1; $cap <= 8; $cap++) {
    $controlId = "control6-{$cap}";
    if ($cap == 1) {
        $_REQUEST[$controlId] = 0; // Not started - will show OpenShift AI
    } elseif ($cap == 2) {
        $_REQUEST[$controlId] = 1; // Planning - will show OpenShift Observability
    } elseif ($cap == 5) {
        $_REQUEST[$controlId] = 0; // Not started - will show OpenShift AI
    } else {
        $_REQUEST[$controlId] = getRealisticMaturity();
    }
}

// Domain 7: AI Innovation Sovereignty
// Ensure Cap 2 is incomplete to show OpenShift AI
for ($cap = 1; $cap <= 8; $cap++) {
    $controlId = "control7-{$cap}";
    if ($cap == 2) {
        $_REQUEST[$controlId] = 1; // Planning - will show OpenShift AI
    } else {
        $_REQUEST[$controlId] = getRealisticMaturity();
    }
}

// Build query string from $_REQUEST data so results.php can parse it
$_SERVER['QUERY_STRING'] = http_build_query($_REQUEST);

// Load the results page
include 'results.php';
?>
