<!doctype html>
<html lang="en-us" class="pf-theme-dark">
  <head>
  <title>Viewfinder - Results</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<link rel="stylesheet" href="css/table.css">
<link rel="stylesheet" href="css/style.css">

<link rel="stylesheet" href="css/patternfly.css" />
<link rel="stylesheet" href="css/patternfly-addons.css" />
<link rel="stylesheet" href="css/tab.css">
<link rel="stylesheet" href="css/table2.css">
<link rel="stylesheet" href="css/results-dark.css">


<script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.6/d3.min.js" charset="utf-8"></script>
<script src="https://kit.fontawesome.com/8a8c57f9cf.js" crossorigin="anonymous"></script>

<style>
  /* Dark Theme Body */
  body {
    background-color: #151515 !important;
    color: #ccc !important;
  }

  /* Header Top Padding */
  .pf-c-page__header {
    padding-top: 1.5rem;
  }

  /* Header Button Spacing */
  .pf-c-page__header-tools button {
    margin-right: 1rem;
  }

  /* Override jQuery UI default styles */
  .ui-widget {
    font-family: inherit !important;
  }

  .ui-widget-content {
    background: transparent !important;
    border: none !important;
    color: #ccc !important;
  }

  .ui-state-default {
    background: transparent !important;
    border: none !important;
  }
</style>

<script>
  $( function() {
    $( "#accordion" ).accordion({
      heightStyle: "content",
      collapsible: true,
      active : 'none'
    });
  } );
  </script>

</head>
<body>
  <header class="pf-c-page__header">
                <div class="pf-c-page__header-brand">
                  <div class="pf-c-page__header-brand-toggle">
                  </div>
                </div>
                <div class="pf-c-page__header-tools">
                  <div class="widget">
                    <a href="index.php"><button><i class="fa fa-home"></i> Home</button></a>
                    <a href="export-results.php?<?php echo htmlspecialchars($_SERVER['QUERY_STRING'], ENT_QUOTES, 'UTF-8'); ?>"><button><i class="fa fa-download"></i> Export Results</button></a>
                  </div>
                </div>
</header>
<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/error-pages/error-handler.php';
require_once __DIR__ . '/includes/Security.php';
require_once __DIR__ . '/includes/MaturityRating.php';
require_once __DIR__ . '/includes/Logger.php';
require_once __DIR__ . '/includes/Config.php';
require_once __DIR__ . '/includes/Exceptions/ResultsException.php';

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;

// Register error handlers
ErrorHandler::register();

try {
    // Start session for import handling
    session_start();

    Logger::info('Results page loaded', ['page' => 'results.php']);

    // Check if this is an imported result
    if (isset($_GET['imported']) && isset($_SESSION['imported_results'])) {
        $importedData = $_SESSION['imported_results'];

        // Validate import type
        if ($importedData['viewfinder_export']['type'] !== 'security_assessment') {
            throw ResultsException::wrongType(
                'security_assessment',
                $importedData['viewfinder_export']['type']
            );
        }

        // Extract assessment data and flatten structure
        $assessment = $importedData['assessment'];

        // Start with base data (profile, lob)
        $data = [
            'profile' => $assessment['profile'],
            'lob' => $assessment['lob'] ?? ''
        ];

        // Handle frameworks - set 'framework' param if frameworks exist
        if (isset($assessment['frameworks']) && !empty($assessment['frameworks'])) {
            // Set framework parameter for tab display (expects singular)
            $data['framework'] = $assessment['frameworks'];
        }

        // Flatten controls to root level (expected by results.php)
        if (isset($assessment['controls'])) {
            foreach ($assessment['controls'] as $controlId => $controlValue) {
                // Cast to integer to ensure proper type
                $data[$controlId] = (int)$controlValue;
            }
        }

        // Include domain notes if present
        if (isset($assessment['domain_notes'])) {
            foreach ($assessment['domain_notes'] as $noteKey => $noteValue) {
                $data[$noteKey] = $noteValue;
            }
        }

        // Clear session
        unset($_SESSION['imported_results']);

        Logger::info('Imported Security results displayed', ['profile' => $data['profile']]);
    } else {
        // Normal flow - parse query string
        parse_str($_SERVER["QUERY_STRING"] ?? '', $data);
    }

    // Validate profile parameter
    $profile = Security::validateProfile($data['profile'] ?? '');
    $data['profile'] = $profile; // Update with validated value
    Logger::info('Profile selected', ['profile' => $profile]);

    // Safely load controls JSON
    $controlsFile = Security::getControlsFilePath($profile);
    $json = Security::loadJSON($controlsFile);

    // Build safe URL for detailed output
    $urlData = "./report/index.php?" . http_build_query($data);

    // Generate QR code for current page URL
    // Get the full current page URL with query string
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $currentPageUrl = $protocol . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    // Build the QR code using fluent builder pattern (v5.x API)
    $qrCodeResult = Builder::create()
        ->writer(new PngWriter())
        ->data($currentPageUrl)
        ->encoding(new Encoding('UTF-8'))
        ->errorCorrectionLevel(ErrorCorrectionLevel::High)
        ->size(300)
        ->margin(10)
        ->roundBlockSizeMode(RoundBlockSizeMode::Margin)
        ->validateResult(false)
        ->build();

    // Convert to base64 for inline display
    $qrCodeDataUri = $qrCodeResult->getDataUri();

} catch (ViewfinderException $e) {
    Logger::logException($e);
    throw $e; // Re-throw for error handler to display error page
} catch (\Throwable $e) {
    Logger::error('Unexpected error in results.php', [
        'exception' => get_class($e),
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
    throw new ViewfinderException(
        'Unexpected error: ' . $e->getMessage(),
        'An unexpected error occurred. Please contact support.',
        ['original_exception' => get_class($e)],
        0,
        $e
    );
}
$nextSteps = array();
$nextStepsHow = array();
$nextDomain = array();
$controls = array();
foreach($json as $key => $value) {
	array_push($controls,$key);
	}
$controlTotal = array_fill(0,8,0);
$controlDetails = array(array_fill(0,8,0));

foreach($data as $field=>$value){
	if (strpos($field,"control") !== false){
    // Extract domain number and capability number from field name (e.g., "control1-3")
    $parts = explode('-', substr($field, 7)); // Remove "control" prefix and split
    $controlNumber = $parts[0]; // Domain number (1-7)
    $capabilityNumber = $parts[1]; // Capability number (1-8)

    // Get the control area key (e.g., "Domain-1")
    $domainKey = "Domain-" . $controlNumber;

    // Get max points for this capability from JSON
    $pointsKey = $capabilityNumber . "-points";
    $maxPoints = isset($json[$domainKey][$pointsKey]) ? $json[$domainKey][$pointsKey] : 0;

    // Calculate partial credit: slider value (0-3) / 3 * max points
    // 0 = No capability (0%), 1 = In Planning (33%), 2 = Work in Progress (67%), 3 = Fully Complete (100%)
    $sliderValue = intval($value);
    $partialScore = ($sliderValue / 3) * $maxPoints;

    $controlTotal[$controlNumber] += $partialScore;
}
}

// Functions moved to MaturityRating class

// ==========================================
// WEIGHTED SCORING IMPLEMENTATION
// ==========================================

// Load LOB weights
$lobWeights = require_once __DIR__ . '/lob-weights.php';

// Get selected LOB (default to 'General' if not set)
$selectedLob = Security::validateLOB($_REQUEST['lob'] ?? '');
if ($selectedLob === null) {
    $selectedLob = 'General';
}

// Get weights for this profile and LOB
$domainWeights = [];
if (isset($lobWeights[$profile]) && isset($lobWeights[$profile][$selectedLob])) {
    $domainWeights = $lobWeights[$profile][$selectedLob]['weights'];
} else {
    // Fallback to balanced weights (all 1.0)
    foreach ($controls as $control) {
        $title = $json[$control]['title'];
        $domainWeights[$title] = 1.0;
    }
}

// Calculate raw total score (unweighted, for reference)
$totalScore = array_sum($controlTotal);

// Calculate weighted score
$weightedSum = 0;
$totalWeight = 0;
$maxPossiblePerDomain = 36; // Each domain has max 36 points (8 capabilities with varying point values)

foreach ($controls as $control) {
    $title = $json[$control]['title'];
    $qnum = $json[$control]['qnum'];
    $domainScore = $controlTotal[$qnum];

    // Get weight for this domain (default 1.0 if not found)
    $weight = isset($domainWeights[$title]) ? $domainWeights[$title] : 1.0;

    // Calculate weighted contribution
    // Normalize domain score to 0-1 range, apply weight, then scale back
    $domainPercentage = $domainScore / $maxPossiblePerDomain;
    $weightedDomainScore = $domainPercentage * $weight;

    $weightedSum += $weightedDomainScore;
    $totalWeight += $weight;
}

// Normalize weighted score to 0-252 scale (7 domains × 36 max points)
$totalScore = $totalWeight > 0 ? ($weightedSum / $totalWeight) * (count($controls) * $maxPossiblePerDomain) : 0;

// Check if any workshop notes exist
$hasNotes = false;
$workshopNotes = [];
foreach ($controls as $control) {
    $qnum = $json[$control]['qnum'];
    $notesFieldName = 'domain_notes_' . $qnum;
    if (isset($_REQUEST[$notesFieldName]) && !empty(trim($_REQUEST[$notesFieldName]))) {
        $hasNotes = true;
        $workshopNotes[$qnum] = [
            'title' => $json[$control]['title'],
            'notes' => $_REQUEST[$notesFieldName]
        ];
    }
}

?>


<div class="container">

<div class="tab">
  <button class="tablinks" onclick="openTab(event, 'Radar')" id="defaultOpen"><i class="fa-solid fa-gauge"></i> Overview</button>
  <button class="tablinks" onclick="openTab(event, 'Strengths')"><i class="fa-solid fa-chart-line"></i> Strengths</button>
  <button class="tablinks" onclick="openTab(event, 'Gaps')"><i class="fa-solid fa-exclamation-triangle"></i> Gaps</button>
  <button class="tablinks" onclick="openTab(event, 'Status')"><i class="fa-solid fa-chart-pie"></i> Status</button>
  <button class="tablinks" onclick="openTab(event, 'Recommendations')"><i class="fa-solid fa-list-check"></i> Details</button>
  <button class="tablinks" onclick="openTab(event, 'TableOutput')"><i class="fa-solid fa-table"></i> Table</button> 
  <?php
  if (isset($_REQUEST['framework'])) {
	print '<button class="tablinks" onclick="openTab(event, \'Frameworks\')"><i class="fa-solid fa-shield-halved"></i> Frameworks</button>';
}
  ?>
  <?php
  // Validate and display LOB tab
  $lob = Security::validateLOB($_REQUEST['lob'] ?? '');
  if ($lob !== null && $lob !== 'Other') {
      print '<button class="tablinks" onclick="openTab(event, \'LineOfBusiness\')"><i class="fa-solid fa-building"></i> ' . Security::escape($lob) . '</button>';
  }
  ?>
  <?php
  // Display Workshop Notes tab if notes exist
  if ($hasNotes) {
      print '<button class="tablinks" onclick="openTab(event, \'WorkshopNotes\')"><i class="fa-solid fa-note-sticky"></i> Notes</button>';
  }
  ?>
  <button class="tablinks""><a href="<?php print $urlData; ?>" target= _blank><i class='fas fa-file-alt'></i> Report</a>&nbsp; <i class='fas fa-external-link-alt'></i></button>

</div>

<div id="Radar" class="tabcontent">

<div class="htmlChart">
<div class="radarChart"></div>
</div>

<div class="bigtableLeft">
<h1 class="profileHeader">Profile: <?php print Security::escape(Config::getProfileDisplayName($data['profile']));?> </h1>

<table class="spacedTable">
	<thead>
		<tr>
			<th>Control</th>
			<th style="text-align: center;">Weight</th>
			<th>Rating</th>
			</tr>
		</tr>
</thead>


<?php
// Use the weighted totalScore calculated earlier, don't recalculate
$displayTotalScore = round($totalScore); // Round for display

## Work out all the stuff for the table
foreach ($controls as $control) {
	print "<tr>";
	$title = $json[$control]['title'];
	$qnum = $json[$control]['qnum'];
	$score = $controlTotal[$qnum];

	// Get weight for this domain
	$weight = isset($domainWeights[$title]) ? $domainWeights[$title] : 1.0;
	$isWeighted = $weight >= 1.5;

	// Split multi-word titles to reduce column width (e.g., "Data Sovereignty" → "Data<br>Sovereignty")
	$displayTitle = str_replace(' ', '<br>', $title);

	print "<td>" . $displayTitle . "</td>";

	// Weight column with badge
	print "<td style='text-align: center;'>";
	$weightBadgeClass = $isWeighted ? 'weight-badge weight-high' : 'weight-badge';
	print "<span class='" . $weightBadgeClass . "'>" . number_format($weight, 1) . "×</span>";
	print "</td>";

	$rating = MaturityRating::getRating($score);
	$ratingClass = MaturityRating::getRatingClass($rating);
	$displayScore = ceil($score); // Round up for display
	print "<td class='" . $ratingClass . "'>" . $rating . " ($displayScore out of 36)</td>";
	print "</tr>";
}
print '</table>';
$overallRating = MaturityRating::getTotalRating($displayTotalScore);
$overallRatingClass = MaturityRating::getRatingClass($overallRating);
print "<br><table class='spacedTable' style='margin-top: 0.5rem;'><tr><td class='" . $overallRatingClass . "' style='padding: 0.5rem;'>Overall rating: " . $overallRating . " (" . $displayTotalScore . " weighted out of 252)</td></tr></table>";

// ==========================================
// KEY STRENGTHS AND CRITICAL GAPS ANALYSIS
// (Data preparation for separate tabs)
// ==========================================

// Analyze domain performance
$domainAnalysis = [];
foreach ($controls as $control) {
    $qnum = $json[$control]["qnum"];
    $title = $json[$control]["title"];
    $score = $controlTotal[$qnum];
    $maxScore = 36;
    $percentage = round(($score / $maxScore) * 100);
    $rating = MaturityRating::getRating($score);
    $weight = isset($domainWeights[$title]) ? $domainWeights[$title] : 1.0;

    // Calculate average maturity level for this domain
    $totalSliderValue = 0;
    $capabilityCount = 0;
    for ($j = 1; $j <= 8; $j++) {
        $controlId = "control{$qnum}-{$j}";
        if (isset($data[$controlId])) {
            $totalSliderValue += intval($data[$controlId]);
            $capabilityCount++;
        }
    }
    $avgMaturity = $capabilityCount > 0 ? $totalSliderValue / $capabilityCount : 0;

    // Map average to maturity label
    $maturityLabels = ['No Capability', 'In Planning', 'Work in Progress', 'Fully Complete'];
    if ($avgMaturity < 0.5) {
        $maturityLevel = $maturityLabels[0];
    } elseif ($avgMaturity < 1.5) {
        $maturityLevel = $maturityLabels[1];
    } elseif ($avgMaturity < 2.5) {
        $maturityLevel = $maturityLabels[2];
    } else {
        $maturityLevel = $maturityLabels[3];
    }

    $domainAnalysis[] = [
        "title" => $title,
        "score" => $score,
        "percentage" => $percentage,
        "rating" => $rating,
        "weight" => $weight,
        "maturityLevel" => $maturityLevel
    ];
}

// Sort by score to find strengths and gaps
usort($domainAnalysis, function($a, $b) {
    return $b["score"] <=> $a["score"];
});

$strengths = array_slice($domainAnalysis, 0, 2); // Top 2 domains
$gaps = array_slice($domainAnalysis, -3); // Bottom 3 domains

// Filter gaps to prioritize high-weighted domains
usort($gaps, function($a, $b) {
    // Sort by weight first, then by low score
    if ($b["weight"] != $a["weight"]) {
        return $b["weight"] <=> $a["weight"];
    }
    return $a["score"] <=> $b["score"];
});

// Domain-specific quick wins
$quickWins = [
    'Data Sovereignty' => 'Implement automated data flow monitoring and quarterly audits of vendor data access to advance toward Level 4 quantitative management.',
    'Technical Sovereignty' => 'Document exit strategies for all critical systems and conduct annual portability drills to strengthen vendor independence.',
    'Operational Sovereignty' => 'Establish a Center of Excellence for sovereign technologies and implement quarterly DR testing scenarios including geopolitical isolation.',
    'Assurance Sovereignty' => 'Expand continuous security validation with automated compliance reporting and establish formal vendor transparency requirements in all contracts.',
    'Open Source' => 'Formalize contribution policies and establish metrics tracking for community engagement and project influence.',
    'Executive Oversight' => 'Implement sovereignty KPI dashboards for Board reporting and establish quarterly reviews with regulatory authorities.',
    'Managed Services' => 'Develop comprehensive transition playbooks for all critical managed services and conduct annual vendor alternative assessments.'
];

// Domain-specific business impacts
$businessImpacts = [
    'Data Sovereignty' => 'Exposes organization to foreign government data access demands, violates data residency regulations (GDPR, NIS2), and creates legal liability for cross-border data transfers.',
    'Technical Sovereignty' => 'Creates vendor lock-in preventing migration, increases costs through proprietary dependencies, and exposes organization to supply chain disruption risks.',
    'Operational Sovereignty' => 'Inability to maintain critical operations during vendor outages or geopolitical conflicts; excessive reliance on external expertise threatens business continuity.',
    'Assurance Sovereignty' => 'Limits ability to verify security claims, prevents independent compliance validation, and creates blind spots in third-party risk management.',
    'Open Source' => 'Increases dependency on proprietary software vendors, limits ability to audit code for security vulnerabilities, and reduces long-term technology flexibility.',
    'Executive Oversight' => 'Lack of strategic direction and budget allocation for sovereignty initiatives; inability to demonstrate compliance to regulators and stakeholders.',
    'Managed Services' => 'Third-party access to sensitive systems without adequate controls; inability to quickly transition services if vendor relationship deteriorates or sovereignty requirements change.'
];

// Domain-specific first steps
$firstSteps = [
    'Data Sovereignty' => [
        'Implement external key management (HSM) to ensure cryptographic sovereignty within 90 days',
        'Audit and renegotiate cloud contracts to include data residency guarantees and foreign access notification clauses'
    ],
    'Technical Sovereignty' => [
        'Conduct vendor lock-in assessment identifying proprietary dependencies and migration risks',
        'Develop 12-month roadmap for containerizing applications using Kubernetes for portability'
    ],
    'Operational Sovereignty' => [
        'Create documented "break-glass" procedures for operating critical systems without vendor support',
        'Establish skills development plan and begin cross-training staff on sovereign technology alternatives'
    ],
    'Assurance Sovereignty' => [
        'Negotiate "right to audit" clauses in all vendor contracts with sovereignty-critical providers',
        'Implement sovereign-controlled SIEM for independent security monitoring within 6 months'
    ],
    'Open Source' => [
        'Develop open source strategy policy defining when to prefer OSS over proprietary alternatives',
        'Implement software composition analysis tools to track and manage open source dependencies'
    ],
    'Executive Oversight' => [
        'Establish dedicated sovereignty governance committee with Board reporting and quarterly reviews',
        'Develop sovereignty KPIs and allocate dedicated budget line for sovereignty initiatives'
    ],
    'Managed Services' => [
        'Implement Just-in-Time (JIT) access controls and session recording for all third-party vendor access',
        'Develop transition playbooks with defined exit criteria and alternative provider options for critical services'
    ]
];

?>
</div>
</div>

<!-- Key Strengths Tab -->
<div id="Strengths" class="tabcontent">
<div style="max-width: 1400px; margin: 0 auto; padding: 2rem;">
<h1 style="color: #9ec7fc; font-size: 2rem; margin: 0 0 1.5rem 0;"><i class="fa-solid fa-chart-line"></i> Key Strengths</h1>

<div style="padding: 1.5rem; background: #1a1a1a; border: 1px solid #444; border-radius: 8px;">
<p style="color: #ccc; margin-bottom: 1.5rem; font-size: 1.1rem;">The assessment identified the following areas of strong maturity. These strengths can be leveraged to build momentum and accelerate improvements in other domains.</p>

<?php
foreach ($strengths as $strength) {
    $quickWin = isset($quickWins[$strength["title"]]) ? $quickWins[$strength["title"]] : 'Continue to refine and optimize processes, and consider sharing best practices with other domains.';

    print '<div style="margin-bottom: 2rem; padding: 1.5rem; background: #2a2a2a; border-left: 4px solid #2aaa04; border-radius: 6px;">';
    print '<h3 style="color: #fff; margin-top: 0; font-size: 1.4rem;">' . Security::escape($strength["title"]) . '</h3>';
    print '<div style="margin-bottom: 1rem;">';
    print '<span style="color: #2aaa04; font-weight: 600; font-size: 1.1rem;">' . Security::escape($strength["rating"]) . ' Level</span>';
    print ' <span style="color: #999;">(' . $strength["percentage"] . '%)</span>';
    if (isset($strength["maturityLevel"])) {
        print ' <span style="color: #12bbd4; margin-left: 0.5rem;">• ' . Security::escape($strength["maturityLevel"]) . '</span>';
    }
    print '</div>';
    print '<p style="color: #ccc; margin-bottom: 1rem;">Demonstrating well-established capabilities in this domain.</p>';

    print '<div style="margin-top: 1rem; padding: 1rem; background: #1a1a1a; border: 1px solid #444; border-radius: 4px;">';
    print '<div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">';
    print '<i class="fa-solid fa-lightbulb" style="color: #12bbd4; font-size: 1.2rem;"></i>';
    print '<strong style="color: #12bbd4; font-size: 1.1rem;">Quick Win to Advance Further</strong>';
    print '</div>';
    print '<p style="color: #ccc; margin: 0;">' . Security::escape($quickWin) . '</p>';
    print '</div>';
    print '</div>';
}
?>

<div style="margin-top: 2rem; padding: 1.5rem; background: #0d60f8; border-radius: 6px;">
<p style="color: #fff; margin: 0; font-size: 1rem;"><i class="fa-solid fa-info-circle"></i> <strong>Tip:</strong> Use these strong domains as templates for improving weaker areas. Share processes, tools, and lessons learned across teams.</p>
</div>

</div>
</div>
</div>

<!-- Critical Gaps Tab -->
<div id="Gaps" class="tabcontent">
<div style="max-width: 1400px; margin: 0 auto; padding: 2rem;">
<h1 style="color: #9ec7fc; font-size: 2rem; margin: 0 0 1.5rem 0;"><i class="fa-solid fa-exclamation-triangle"></i> Critical Gaps</h1>

<div style="padding: 1.5rem; background: #1a1a1a; border: 1px solid #444; border-radius: 8px;">
<p style="color: #ccc; margin-bottom: 0.5rem; font-size: 1.1rem;">Priority areas requiring immediate attention<?php if ($selectedLob !== "General") { print ' (based on ' . Security::escape($selectedLob) . ' industry priorities)'; } ?>:</p>
<p style="color: #999; font-size: 0.95rem; margin-bottom: 1.5rem;">Each gap includes business impact analysis and concrete first steps to begin addressing the deficiency.</p>

<?php
foreach ($gaps as $gap) {
    $priorityBadge = "";
    if ($gap["weight"] >= 1.5) {
        $priorityBadge = '<span style="background: #f0ab00; color: #000; padding: 0.25rem 0.75rem; border-radius: 3px; font-size: 0.85rem; font-weight: 600; margin-left: 0.75rem;">HIGH PRIORITY</span>';
    }

    $impact = isset($businessImpacts[$gap["title"]]) ? $businessImpacts[$gap["title"]] : 'Reduces overall sovereignty maturity and organizational resilience.';
    $steps = isset($firstSteps[$gap["title"]]) ? $firstSteps[$gap["title"]] : ['Review domain-specific recommendations in detailed assessment.'];

    print '<div style="margin-bottom: 2.5rem; padding: 1.5rem; background: #2a2a2a; border-left: 4px solid #c9190b; border-radius: 6px;">';

    print '<h3 style="color: #fff; margin-top: 0; font-size: 1.4rem; display: flex; align-items: center; flex-wrap: wrap;">';
    print Security::escape($gap["title"]);
    print $priorityBadge;
    print '</h3>';

    print '<div style="margin-bottom: 1.5rem;">';
    print '<span style="color: #c9190b; font-weight: 600; font-size: 1.1rem;">' . Security::escape($gap["rating"]) . ' Level</span>';
    print ' <span style="color: #999;">(' . $gap["percentage"] . '%)</span>';
    if (isset($gap["maturityLevel"])) {
        print ' <span style="color: #f0ab00; margin-left: 0.5rem;">• ' . Security::escape($gap["maturityLevel"]) . '</span>';
    }
    print '</div>';

    // Business Impact
    print '<div style="margin-bottom: 1.5rem; padding: 1.25rem; background: #1a1a1a; border: 1px solid #f0ab00; border-radius: 4px;">';
    print '<div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">';
    print '<i class="fa-solid fa-exclamation-circle" style="color: #f0ab00; font-size: 1.2rem;"></i>';
    print '<strong style="color: #f0ab00; font-size: 1.1rem;">Business Impact</strong>';
    print '</div>';
    print '<p style="color: #ccc; margin: 0; line-height: 1.6;">' . Security::escape($impact) . '</p>';
    print '</div>';

    // First Steps
    print '<div style="padding: 1.25rem; background: #1a1a1a; border: 1px solid #12bbd4; border-radius: 4px;">';
    print '<div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">';
    print '<i class="fa-solid fa-list-check" style="color: #12bbd4; font-size: 1.2rem;"></i>';
    print '<strong style="color: #12bbd4; font-size: 1.1rem;">First Steps</strong>';
    print '</div>';
    print '<ol style="margin: 0; padding-left: 1.5rem; color: #ccc; line-height: 1.8;">';
    foreach ($steps as $step) {
        print '<li style="margin-bottom: 0.5rem;">' . Security::escape($step) . '</li>';
    }
    print '</ol>';
    print '</div>';

    print '</div>';
}
?>

<div style="margin-top: 2rem; padding: 1.5rem; background: #c9190b; border-radius: 6px;">
<p style="color: #fff; margin: 0; font-size: 1rem;"><i class="fa-solid fa-triangle-exclamation"></i> <strong>Action Required:</strong> Review the detailed recommendations in the "Recommendations" tab for comprehensive guidance on addressing each gap.</p>
</div>

</div>
</div>
</div>

<!-- Status Tab -->
<div id="Status" class="tabcontent">
<div style="max-width: 1400px; margin: 0 auto; padding: 2rem;">
<h1 style="color: #9ec7fc; font-size: 2rem; margin: 0 0 1.5rem 0;"><i class="fa-solid fa-chart-pie"></i> Capability Status</h1>

<div style="padding: 1.5rem; background: #1a1a1a; border: 1px solid #444; border-radius: 8px;">
<p style="color: #ccc; margin-bottom: 1.5rem; font-size: 1.1rem;">Overview of all capabilities grouped by their current status.</p>

<!-- Pie Chart -->
<div style="background: #2a2a2a; border-radius: 8px; padding: 2rem; margin-bottom: 2rem;">
<h3 style="color: #9ec7fc; text-align: center; margin-bottom: 1.5rem; font-size: 1.2rem;">Capability Maturity Distribution</h3>
<div id="statusPieChart" style="text-align: center;"></div>
</div>

<?php
// Group capabilities by maturity level, then by domain
$statusGroups = [
    '3' => ['label' => 'Fully Complete', 'color' => '#2aaa04', 'icon' => 'check-circle', 'domains' => []],
    '2' => ['label' => 'Work in Progress', 'color' => '#ec7a08', 'icon' => 'spinner', 'domains' => []],
    '1' => ['label' => 'In Planning', 'color' => '#f0ab00', 'icon' => 'clipboard-list', 'domains' => []],
    '0' => ['label' => 'No Capability', 'color' => '#6a6e73', 'icon' => 'circle', 'domains' => []]
];

// Collect all capabilities and their statuses, grouped by domain
foreach ($controls as $control) {
    $qnum = $json[$control]['qnum'];
    $domainTitle = $json[$control]['title'];

    for ($i = 1; $i <= 8; $i++) {
        $controlId = "control{$qnum}-{$i}";
        $capabilityName = $json[$control][$i];
        $maturityValue = isset($data[$controlId]) ? intval($data[$controlId]) : 0;

        // Initialize domain array if it doesn't exist
        if (!isset($statusGroups[strval($maturityValue)]['domains'][$domainTitle])) {
            $statusGroups[strval($maturityValue)]['domains'][$domainTitle] = [];
        }

        // Add capability to domain
        $statusGroups[strval($maturityValue)]['domains'][$domainTitle][] = $capabilityName;
    }
}

// Calculate counts for each status level (for pie chart)
$statusCounts = [];
foreach ($statusGroups as $level => $group) {
    $totalCount = 0;
    foreach ($group['domains'] as $capabilities) {
        $totalCount += count($capabilities);
    }
    $statusCounts[$level] = $totalCount;
}

// Output pie chart data as JavaScript
print '<script type="text/javascript">';
print 'var statusChartData = [';
print '{"label": "Fully Complete", "value": ' . $statusCounts['3'] . ', "color": "#2aaa04"},';
print '{"label": "Work in Progress", "value": ' . $statusCounts['2'] . ', "color": "#ec7a08"},';
print '{"label": "In Planning", "value": ' . $statusCounts['1'] . ', "color": "#f0ab00"},';
print '{"label": "No Capability", "value": ' . $statusCounts['0'] . ', "color": "#6a6e73"}';
print '];';
print '</script>';

// Display each status group
foreach ($statusGroups as $level => $group) {
    $totalCount = $statusCounts[$level];
    $percentage = round(($totalCount / 56) * 100);

    print '<div id="status-section-' . $level . '" style="margin-bottom: 2rem; padding: 1.5rem; background: #2a2a2a; border-left: 4px solid ' . $group['color'] . '; border-radius: 6px; transition: border-left-width 0.3s ease;">';

    // Header with count
    print '<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">';
    print '<h3 style="color: #fff; margin: 0; font-size: 1.3rem;">';
    print '<i class="fa-solid fa-' . $group['icon'] . '" style="color: ' . $group['color'] . '; margin-right: 0.5rem;"></i>';
    print $group['label'];
    print '</h3>';
    print '<div style="text-align: right;">';
    print '<span style="color: ' . $group['color'] . '; font-weight: 600; font-size: 1.2rem;">' . $totalCount . '</span>';
    print '<span style="color: #999; font-size: 0.9rem;"> / 56 (' . $percentage . '%)</span>';
    print '</div>';
    print '</div>';

    // List domains and their capabilities
    if ($totalCount > 0) {
        print '<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(400px, 1fr)); gap: 1rem; margin-top: 1rem;">';
        foreach ($group['domains'] as $domainName => $capabilities) {
            print '<div style="padding: 1rem; background: #1a1a1a; border-radius: 4px; border: 1px solid #444;">';
            print '<div style="color: #12bbd4; font-weight: 600; font-size: 1rem; margin-bottom: 0.75rem;">' . Security::escape($domainName) . '</div>';
            print '<ul style="list-style: disc; margin: 0; padding-left: 1.5rem; color: #e0e0e0;">';
            foreach ($capabilities as $capName) {
                print '<li style="margin-bottom: 0.4rem; font-size: 0.9rem;">' . Security::escape($capName) . '</li>';
            }
            print '</ul>';
            print '</div>';
        }
        print '</div>';
    } else {
        print '<p style="color: #999; margin: 0; font-style: italic;">No capabilities at this level</p>';
    }

    print '</div>';
}
?>

<div style="margin-top: 2rem; padding: 1.5rem; background: #0d60f8; border-radius: 6px;">
<p style="color: #fff; margin: 0; font-size: 1rem;"><i class="fa-solid fa-info-circle"></i> <strong>Tip:</strong> Focus on moving "In Planning" capabilities to "Work in Progress" and completing "Work in Progress" items to improve overall maturity.</p>
</div>

</div>
</div>
</div>

<script type="text/javascript">
// Render Status Pie Chart using D3.js
(function() {
    if (typeof statusChartData === 'undefined') return;

    var width = 400;
    var height = 400;
    var radius = Math.min(width, height) / 2;

    var svg = d3.select("#statusPieChart")
        .append("svg")
        .attr("width", width)
        .attr("height", height)
        .append("g")
        .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

    var pie = d3.layout.pie()
        .value(function(d) { return d.value; })
        .sort(null);

    var arc = d3.svg.arc()
        .innerRadius(0)
        .outerRadius(radius - 20);

    var labelArc = d3.svg.arc()
        .innerRadius(radius - 60)
        .outerRadius(radius - 60);

    var arcs = svg.selectAll(".arc")
        .data(pie(statusChartData))
        .enter()
        .append("g")
        .attr("class", "arc");

    var paths = arcs.append("path")
        .attr("d", arc)
        .style("fill", function(d) { return d.data.color; })
        .style("stroke", "#1a1a1a")
        .style("stroke-width", "2px")
        .style("cursor", "pointer")
        .style("transition", "all 0.3s ease")
        .on("mouseover", function(d) {
            // Calculate the angle to move the segment outward
            var angle = (d.startAngle + d.endAngle) / 2;
            var x = Math.sin(angle) * 10;
            var y = -Math.cos(angle) * 10;

            d3.select(this.parentNode)
                .transition()
                .duration(200)
                .attr("transform", "translate(" + x + "," + y + ")");

            d3.select(this)
                .style("opacity", 0.8);
        })
        .on("mouseout", function(d) {
            d3.select(this.parentNode)
                .transition()
                .duration(200)
                .attr("transform", "translate(0,0)");

            d3.select(this)
                .style("opacity", 1);
        })
        .on("click", function(d) {
            // Map data label to section ID
            var sectionMap = {
                "Fully Complete": "status-section-3",
                "Work in Progress": "status-section-2",
                "In Planning": "status-section-1",
                "No Capability": "status-section-0"
            };

            var sectionId = sectionMap[d.data.label];
            if (sectionId) {
                var element = document.getElementById(sectionId);
                if (element) {
                    element.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    // Flash the section border briefly
                    element.style.borderLeftWidth = '6px';
                    setTimeout(function() {
                        element.style.borderLeftWidth = '4px';
                    }, 500);
                }
            }
        });

    arcs.append("text")
        .attr("transform", function(d) { return "translate(" + labelArc.centroid(d) + ")"; })
        .attr("text-anchor", "middle")
        .style("fill", "#fff")
        .style("font-size", "14px")
        .style("font-weight", "600")
        .style("pointer-events", "none")
        .text(function(d) {
            if (d.data.value > 0) {
                return d.data.value;
            }
            return "";
        });

    // Legend
    var legend = d3.select("#statusPieChart")
        .append("div")
        .style("margin-top", "1.5rem")
        .style("display", "flex")
        .style("justify-content", "center")
        .style("gap", "1.5rem")
        .style("flex-wrap", "wrap");

    statusChartData.forEach(function(d) {
        var item = legend.append("div")
            .style("display", "flex")
            .style("align-items", "center")
            .style("gap", "0.5rem");

        item.append("div")
            .style("width", "16px")
            .style("height", "16px")
            .style("background-color", d.color)
            .style("border-radius", "3px");

        item.append("span")
            .style("color", "#ccc")
            .style("font-size", "0.9rem")
            .text(d.label + ": " + d.value + " (" + Math.round((d.value / 56) * 100) + "%)");
    });
})();
</script>

<!-- Detailed Output -->
<div id="Recommendations" class="tabcontent">
<div id="accordion">
<?php
foreach ($controls as $control) {
    $highest=0;
    $qnum = $json[$control]['qnum'];
	$score = $controlTotal[$qnum];
	$title = $json[$control]['title'];
	array_push($nextDomain, $title);
	$rating = MaturityRating::getRating($score);
	$ratingClass = MaturityRating::getRatingClass($rating);
	// Convert cell class to header class (e.g., cellInitial -> cellHeaderInitial)
	$headerClass = str_replace('cell', 'cellHeader', $ratingClass);
    print "<h3>$title <span class='" . $headerClass . "'>". $rating . "</span></h3><div>";

    
    $qnum = $json[$control]['qnum'];
    $levelArray = array();
    ## Get capabilities with values > 0 (any maturity level)
    foreach ($data as $key => $value) {
    if (preg_match("/^control$qnum-[0-9]*/", $key)) {
        // Extract capability number from field name (e.g., "control1-3" -> "3")
        $parts = explode('-', $key);
        $capabilityNum = $parts[1];
        // Only add to array if slider value > 0
        if ($value > 0) {
            array_push($levelArray, $capabilityNum);
            $highest++;
        }
          }
    }
    $nextLevel = $highest + 1;
    if ($nextLevel < 9) {
        ## Check if there is a recommendation for the next level
        $nextRecommendation = $nextLevel . '-recommendation';
        $nextSummary = $nextLevel . '-summary';
        print "<h4 class=title-text>Recommendation</h4>";
        print "<p>Start to work on preparing for actions concerning " . $json[$control][$nextLevel] . " (Level $nextLevel)<p>";
        print "<br><p class=why-what>What is " . $json[$control][$nextLevel] . " ?</p><p>" . $json[$control][$nextSummary] . "</p>";

        if ($json[$control][$nextRecommendation] != "") {
            print "<br>";
            print "<p>" . $json[$control][$nextRecommendation] . "<p>";
			array_push($nextSteps,$json[$control][$nextLevel]);
			array_push($nextStepsHow,$json[$control][$nextSummary]);
        } else {
        print "<p>You're doing great as you are!</p>";
    }
}


## Check for any gaps
if ($levelArray) {
	#print "Max: " . max($levelArray) . "<br>";
	$allLevels = range(1,max($levelArray));
	$missing = array_diff($allLevels,$levelArray);
	if ($missing) {
		print "<br><br><h4 class=why-what>Skipped Level(s)</h4>";
		foreach ($missing as $notthere) {
			$skippedRecommendation = $notthere . '-recommendation';
			print "Level $notthere - ";
			if ($json[$control][$skippedRecommendation] != "") {
			print $json[$control][$skippedRecommendation] . ". ";
			} else {
                $notthereComment = $notthere . "-summary";
#				print_r($json[$control][$notthere]);
                print $json[$control][$notthereComment];
			}
			print "<br>";
		}
	}
	}
    
    print "</div>";

}
?>

</div>
<!-- End of Detailed Output -->

</div>

<!-- Start of table output  -->

<div id="TableOutput" class="tabcontent">

<?php
  // Functions moved to MaturityRating class

  $controlDetail = array_fill(1,8,0);
  $controlDetails = array_fill(1,8,$controlDetail);

  foreach($data as $field=>$value){
	  if (strpos($field,"control") !== false){
	  // Extract domain number and capability number from field name (e.g., "control1-3")
	  $parts = explode('-', substr($field, 7)); // Remove "control" prefix and split
	  $controlNumber = $parts[0]; // Domain number (1-7)
	  $capabilityNumber = $parts[1]; // Capability number (1-8)

	  // Mark capability as selected if slider value > 0
	  if ($value > 0) {
	      $controlDetails[$controlNumber][$capabilityNumber] = 1;
	  }
  }
  }   
?>

<div class="bigtable">

<table class="tableMaturity"><thead><tr>
<th class="table-header">Rating</th>

<?php
foreach ($controls as $control) {
	$title = $json[$control]['title'];
print '<th class="table-header">' . $title .'</th>';
}

?>

</tr></thead>
<tr>
<td class="optimizing">Optimizing</td>
<?php
MaturityRating::putDomainStatus("8",$controlDetails,$json);
?>
</tr>

<tr>
<td class="quantitative"></td>

<?php
MaturityRating::putDomainStatus("7",$controlDetails,$json);
?>
</tr>

<tr>
<td class="quantitative">Quantitatively Managed</td>
<?php
MaturityRating::putDomainStatus("6",$controlDetails,$json);
?>
</tr>

<tr>
<td class="defined"></td>
<?php
MaturityRating::putDomainStatus("5",$controlDetails,$json);
?>
</tr>

<tr>
<td class="defined">Defined</td>
<?php
MaturityRating::putDomainStatus("4",$controlDetails,$json);
?>
</tr>

<tr>
<td class="managed"></td>
<?php
MaturityRating::putDomainStatus("3",$controlDetails,$json);
?>
</tr>

<tr>
<td class="managed">Managed</td>
<?php
MaturityRating::putDomainStatus("2",$controlDetails,$json);
?>
</tr>

<tr>
<td class="initial">Initial</td>
<?php
MaturityRating::putDomainStatus("1",$controlDetails,$json);
?>

</tr>

</table>

</div>


</div>
<!-- End of table output  -->

<!-- Start of Security Frameworks -->
<div id="Frameworks" class="tabcontent">


<?php
if (isset($_REQUEST['framework'])) {
    // Safely load compliance frameworks
    $jsonFrameworks = Security::loadJSON(__DIR__ . '/compliance.json');

    if ($jsonFrameworks !== null) {
        // Build list of valid framework names
        $validFrameworks = array_column($jsonFrameworks, 'name');

        // Validate user-provided frameworks
        $userFrameworks = Security::validateFrameworks($_REQUEST['framework'], $validFrameworks);

        foreach ($userFrameworks as $selectedFramework) {
            foreach ($jsonFrameworks as $framework) {
                if ($framework['name'] === $selectedFramework) {
                    $linkFile = $framework['link'];
                    print "<br><div class='niceList'>";
                    print "<ul>";

                    // Safely get framework file path
                    $safeFilePath = Security::getFrameworkFilePath($linkFile);

                    if ($safeFilePath !== null) {
                        include $safeFilePath;
                    } else {
                        print "<h3 class='frameworkHeader'>No current information for " . Security::escape($framework['name']) . "</h3>";
                    }
                    print "</ul></div>";
                }
            }
        }
    }
}
?>
</div>


<!-- Start of LOB -->
<?php
if (isset($_REQUEST['lob'])) {
    // Validate LOB parameter
    $lob = Security::validateLOB($_REQUEST['lob']);

    if ($lob !== null) {
        print '<div id="LineOfBusiness" class="tabcontent"><p class="category-large">Advice for ' . Security::escape($lob) . ' industries</p>';
        // Safely get LOB file path
#        if ($profile === "DigitalSovereignty") {
#        $safeFilePath = Security::getLOBFilePath("DigitalSovereignty", $profile);
#        } else {
        $safeFilePath = Security::getLOBFilePath($lob, $profile);
        #        }  
        if ($safeFilePath !== null) {
            include $safeFilePath;
        } else {
            print '<p>No current information available for this industry.</p>';
        }
    }
}
?>

</div>

<!-- Start of Workshop Notes -->
<?php if ($hasNotes): ?>
<div id="WorkshopNotes" class="tabcontent">
    <h2 style="color: #9ec7fc; margin-bottom: 1.5rem;">
        Workshop Facilitator Notes
    </h2>
    <p style="color: #999; margin-bottom: 2rem;">
        Notes captured during the assessment workshop for each domain.
    </p>

    <?php foreach ($workshopNotes as $qnum => $noteData): ?>
        <div style="margin-bottom: 2rem; padding: 1rem 1.5rem 1rem 0; background: #1f1f1f; border-left: 4px solid #0d60f8; border-radius: 4px; border: 1px solid #444;">
            <h3 style="color: #9ec7fc; ">
                <?php echo Security::escape($noteData['title']); ?>
            </h3>
            <div style="color: #ccc; line-height: 1.6; margin: 0; padding: 0.5rem;">
                <?php echo Security::escape($noteData['notes']); ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

</div>


<script src="js/radarChart.js"></script>	
		<script>
      
      /* Radar chart design created by Nadieh Bremer - VisualCinnamon.com */
      
			////////////////////////////////////////////////////////////// 
			//////////////////////// Set-Up ////////////////////////////// 
			////////////////////////////////////////////////////////////// 

			var margin = {top: 120, right: 120, bottom: 120, left: 120},
				width = Math.min(500, window.innerWidth - 10) - margin.left - margin.right,
				height = Math.min(width, window.innerHeight - margin.top - margin.bottom - 20);
					
			////////////////////////////////////////////////////////////// 
			////////////////////////// Data ////////////////////////////// 
			////////////////////////////////////////////////////////////// 

			var data = [
					  [
						<?php
						$numControls = 1;
						foreach ($controls as $control) {
							$title = $json[$control]['title'];
							print '{axis:"' . $title . '",value: ' . $controlTotal[$numControls]. '},';		
							$numControls++;
						}
						?>

					  ]
					];
			////////////////////////////////////////////////////////////// 
			//////////////////// Draw the Chart ////////////////////////// 
			////////////////////////////////////////////////////////////// 

			var color = d3.scale.ordinal()
				.range(["#0d60f8","#0d60f8","#12bbd4"]);
				
			var radarChartOptions = {
			  w: width,
			  h: height,
			  margin: margin,
			  maxValue: 0.5,
			  roundStrokes: true,
			  color: color,
			};
			//Call function to draw the Radar chart
			RadarChart(".radarChart", data, radarChartOptions);
</script>


<script type="text/javascript" >
function openTab(evt, cityName) {
  // Declare all variables
  var i, tabcontent, tablinks;

  // Get all elements with class="tabcontent" and hide them
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }

  // Get all elements with class="tablinks" and remove the class "active"
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }

  // Show the current tab, and add an "active" class to the button that opened the tab
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
}
</script>
<script type="text/javascript" >
document.getElementById("defaultOpen").click();
</script>
</body>
  </html>