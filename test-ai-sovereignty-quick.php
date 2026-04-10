<?php
/**
 * Quick Test for AI Sovereignty Profile
 *
 * Generates a realistic AI Sovereignty assessment showing:
 * - Mix of maturity levels across domains
 * - Red Hat solutions displayed where applicable
 * - Recommendations for incomplete capabilities
 *
 * Showcases Red Hat AI portfolio:
 * - OpenShift (AI infrastructure)
 * - OpenShift AI (MLOps platform)
 * - Red Hat AI Inference Server (model serving)
 * - Red Hat Quay (model registry)
 * - OpenShift Data Foundation (AI data storage)
 */

// Simulate form submission for AI Sovereignty profile
$_REQUEST = [];
$_REQUEST['profile'] = 'AISovereignty';
$_REQUEST['lob'] = 'General';

// Domain 1: AI Data Sovereignty - Good progress (mostly level 2-3)
for ($cap = 1; $cap <= 8; $cap++) {
    $controlId = "control1-{$cap}";
    if ($cap <= 2) {
        $_REQUEST[$controlId] = 3; // Complete
    } elseif ($cap <= 5) {
        $_REQUEST[$controlId] = 2; // In Progress
    } else {
        $_REQUEST[$controlId] = 1; // Planning
    }
}

// Domain 2: AI Model Sovereignty - Mixed progress
for ($cap = 1; $cap <= 8; $cap++) {
    $controlId = "control2-{$cap}";
    if ($cap <= 3) {
        $_REQUEST[$controlId] = 2; // In Progress
    } elseif ($cap <= 6) {
        $_REQUEST[$controlId] = 1; // Planning
    } else {
        $_REQUEST[$controlId] = 0; // Not started - will show recommendations
    }
}

// Domain 3: AI Infrastructure Sovereignty - Strong (showcase Red Hat solutions)
for ($cap = 1; $cap <= 8; $cap++) {
    $controlId = "control3-{$cap}";
    if ($cap <= 4) {
        $_REQUEST[$controlId] = 3; // Complete
    } elseif ($cap <= 6) {
        $_REQUEST[$controlId] = 2; // In Progress
    } else {
        $_REQUEST[$controlId] = 1; // Planning
    }
}

// Domain 4: AI Supply Chain Sovereignty - Early stage
for ($cap = 1; $cap <= 8; $cap++) {
    $controlId = "control4-{$cap}";
    if ($cap <= 2) {
        $_REQUEST[$controlId] = 2; // In Progress
    } elseif ($cap <= 4) {
        $_REQUEST[$controlId] = 1; // Planning
    } else {
        $_REQUEST[$controlId] = 0; // Not started - will show recommendations
    }
}

// Domain 5: AI Governance & Compliance - Policy foundation established
for ($cap = 1; $cap <= 8; $cap++) {
    $controlId = "control5-{$cap}";
    if ($cap <= 3) {
        $_REQUEST[$controlId] = 2; // In Progress
    } elseif ($cap <= 5) {
        $_REQUEST[$controlId] = 1; // Planning
    } else {
        $_REQUEST[$controlId] = 0; // Not started
    }
}

// Domain 6: AI Operations Sovereignty - Operational monitoring in place
for ($cap = 1; $cap <= 8; $cap++) {
    $controlId = "control6-{$cap}";
    if ($cap <= 2) {
        $_REQUEST[$controlId] = 3; // Complete (monitoring tools deployed)
    } elseif ($cap <= 5) {
        $_REQUEST[$controlId] = 2; // In Progress
    } else {
        $_REQUEST[$controlId] = 1; // Planning
    }
}

// Domain 7: AI Innovation Sovereignty - Beginning to invest
for ($cap = 1; $cap <= 8; $cap++) {
    $controlId = "control7-{$cap}";
    if ($cap <= 2) {
        $_REQUEST[$controlId] = 2; // In Progress (platform deployed)
    } elseif ($cap <= 4) {
        $_REQUEST[$controlId] = 1; // Planning
    } else {
        $_REQUEST[$controlId] = 0; // Not started
    }
}

// Build query string from $_REQUEST data so results.php can parse it
$_SERVER['QUERY_STRING'] = http_build_query($_REQUEST);

// Load the results page
include 'results.php';
?>
