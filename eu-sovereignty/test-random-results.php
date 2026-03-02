<?php
/**
 * Random Results Generator - For Testing
 * Generates random assessment responses and displays the results page
 *
 * Usage: php test-random-results.php
 * Or visit in browser: http://localhost/viewfinder-redhat/eu-sovereignty/test-random-results.php
 */

// Generate random responses for all 24 questions (SOV-1 through SOV-8, 3 questions each)
$_POST = [];
$yesCount = 0;
$noCount = 0;
$unknownCount = 0;

// Generate for each domain
for ($domain = 1; $domain <= 8; $domain++) {
    for ($q = 1; $q <= 3; $q++) {
        $questionId = "sov{$domain}_{$q}";

        // Randomly choose: 1 (Yes), 0 (No), or 'unknown'
        $random = rand(0, 10);
        if ($random <= 5) {
            $_POST[$questionId] = '1';  // 60% chance of "Yes"
            $yesCount++;
        } elseif ($random <= 8) {
            $_POST[$questionId] = '0';  // 30% chance of "No"
            $noCount++;
        } else {
            $_POST[$questionId] = 'unknown';  // 10% chance of "Don't Know"
            $unknownCount++;
        }
    }
}

// Output the generated responses for reference
echo "<!-- Generated Random Responses:\n";
echo "Yes: $yesCount, No: $noCount, Don't Know: $unknownCount\n";
echo "-->\n\n";

// Include the results page which will process the $_POST data
require 'results.php';
