<!doctype html>
<html lang="en-us" class="pf-theme-dark">
  <head>
  <title>Assessment Results</title>
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

  /* Custom Capability Tooltips with HTML support */
  .custom-capability-tooltip {
    position: absolute;
    display: none;
    max-width: 500px;
    min-width: 400px;
    background: #2a2a2a;
    border: 2px solid #0d60f8;
    border-radius: 6px;
    color: #e0e0e0;
    font-size: 0.9rem;
    padding: 1rem;
    box-shadow: 0 4px 20px rgba(13, 96, 248, 0.4);
    z-index: 10000;
    pointer-events: none;
  }

  .custom-capability-tooltip strong {
    color: #9ec7fc;
    display: block;
    margin-bottom: 0.5rem;
    font-size: 1rem;
  }

  .custom-capability-tooltip ul {
    margin: 0.5rem 0 0 0;
    padding-left: 1.5rem;
    list-style-type: disc;
  }

  .custom-capability-tooltip ul li {
    color: #e0e0e0;
    margin-bottom: 0.4rem;
    line-height: 1.5;
  }

  .tooltip-icon {
    color: #0d60f8;
    transition: color 0.2s ease;
  }

  .tooltip-icon:hover {
    color: #4d90fe;
  }

  /* Responsive layout for theme cards */
  @media (max-width: 1200px) {
    .theme-cards-grid {
      grid-template-columns: repeat(2, 1fr) !important;
    }
  }

  @media (max-width: 768px) {
    .theme-cards-grid {
      grid-template-columns: 1fr !important;
    }
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
// Build controls array - filter out sub-domains from main display
// Sub-domains (Domain-5, Domain-7) are integrated into their parent domains
foreach($json as $key => $value) {
	// Only include domains that should display in main navigation
	if (!isset($value['display_in_main_nav']) || $value['display_in_main_nav'] !== false) {
		array_push($controls,$key);
	}
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
$totalMaxPoints = 0; // Track actual max points across all domains (including sub-domains)

foreach ($controls as $control) {
    $title = $json[$control]['title'];
    $qnum = $json[$control]['qnum'];
    $domainScore = $controlTotal[$qnum];
    $domainMaxPoints = $maxPossiblePerDomain;

    // Check if this domain includes sub-domains and add their scores
    if (isset($json[$control]['includes_subdomains']) && $json[$control]['includes_subdomains'] === true) {
        if (isset($json[$control]['section_2_source'])) {
            $subdomainKey = $json[$control]['section_2_source'];
            if (isset($json[$subdomainKey])) {
                $subQnum = $json[$subdomainKey]['qnum'];
                $domainScore += $controlTotal[$subQnum];
                $domainMaxPoints += $maxPossiblePerDomain; // Sub-domain also has 8 capabilities
            }
        }
    }

    // Get weight for this domain (default 1.0 if not found)
    $weight = isset($domainWeights[$title]) ? $domainWeights[$title] : 1.0;

    // Calculate weighted contribution
    // Normalize domain score to 0-1 range, apply weight, then scale back
    $domainPercentage = $domainScore / $domainMaxPoints;
    $weightedDomainScore = $domainPercentage * $weight;

    $weightedSum += $weightedDomainScore;
    $totalWeight += $weight;
    $totalMaxPoints += $domainMaxPoints;
}

// Normalize weighted score to 0-252 scale (total max points across all domains including sub-domains)
$totalScore = $totalWeight > 0 ? ($weightedSum / $totalWeight) * $totalMaxPoints : 0;

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

<!-- Profile Header Banner -->
<div style="background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%); border-left: 4px solid #0d60f8; border-radius: 8px; padding: 1.25rem 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.3);">
  <h1 style="margin: 0; color: #9ec7fc; font-size: 1.75rem; font-weight: 600; display: flex; align-items: center; gap: 0.75rem;">
    <i class="fa-solid fa-clipboard-check"></i>
    <?php print Security::escape(Config::getProfileDisplayName($profile)); ?> Profile - Results
  </h1>
</div>

<div class="tab">
  <button class="tablinks" onclick="openTab(event, 'Radar')" id="defaultOpen"><i class="fa-solid fa-gauge"></i> Overview</button>
  <button class="tablinks" onclick="openTab(event, 'Strengths')"><i class="fa-solid fa-chart-line"></i> Strengths</button>
  <button class="tablinks" onclick="openTab(event, 'Gaps')"><i class="fa-solid fa-exclamation-triangle"></i> Gaps</button>
  <button class="tablinks" onclick="openTab(event, 'Status')"><i class="fa-solid fa-chart-pie"></i> Status</button>
  <button class="tablinks" onclick="openTab(event, 'ThematicView')"><i class="fa-solid fa-grip"></i> Themes</button>
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
	$maxScore = 36; // Default for single domain (8 capabilities)

	// Check if this domain includes sub-domains and add their scores
	if (isset($json[$control]['includes_subdomains']) && $json[$control]['includes_subdomains'] === true) {
		if (isset($json[$control]['section_2_source'])) {
			$subdomainKey = $json[$control]['section_2_source'];
			if (isset($json[$subdomainKey])) {
				$subQnum = $json[$subdomainKey]['qnum'];
				$score += $controlTotal[$subQnum];
				$maxScore += 36; // Add max points for sub-domain (8 capabilities)
			}
		}
	}

	// Get weight for this domain
	$weight = isset($domainWeights[$title]) ? $domainWeights[$title] : 1.0;
	$isWeighted = $weight >= 1.5;

	// Split multi-word titles to reduce column width
	// For AI Sovereignty domains, keep "AI" with the next word (e.g., "AI Data Sovereignty" → "AI Data<br>Sovereignty")
	if (strpos($title, 'AI ') === 0) {
	    // AI Sovereignty domain - replace first space with non-breaking space, then break on remaining spaces
	    $displayTitle = preg_replace('/^AI /', 'AI&nbsp;', $title);
	    $displayTitle = str_replace(' ', '<br>', $displayTitle);
	} else {
	    // Other profiles - break on all spaces (e.g., "Data Sovereignty" → "Data<br>Sovereignty")
	    $displayTitle = str_replace(' ', '<br>', $title);
	}

	// Add subtitle for domains with sub-pillars
	$subtitle = '';
	if (isset($json[$control]['includes_subdomains']) && $json[$control]['includes_subdomains'] === true) {
		$subtitle = '<br><span style="font-size: 0.75rem; color: #9ec7fc; font-style: italic;">incl. sub-pillar</span>';
	}

	print "<td>" . $displayTitle . $subtitle . "</td>";

	// Weight column with badge
	print "<td style='text-align: center;'>";
	$weightBadgeClass = $isWeighted ? 'weight-badge weight-high' : 'weight-badge';
	print "<span class='" . $weightBadgeClass . "'>" . number_format($weight, 1) . "×</span>";
	print "</td>";

	$rating = MaturityRating::getRating($score);
	$ratingClass = MaturityRating::getRatingClass($rating);
	$displayScore = ceil($score); // Round up for display
	print "<td class='" . $ratingClass . "'>" . $rating . " ($displayScore out of $maxScore)</td>";
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

    // Check if this domain includes sub-domains and add their scores
    if (isset($json[$control]['includes_subdomains']) && $json[$control]['includes_subdomains'] === true) {
        if (isset($json[$control]['section_2_source'])) {
            $subdomainKey = $json[$control]['section_2_source'];
            if (isset($json[$subdomainKey])) {
                $subQnum = $json[$subdomainKey]['qnum'];
                $score += $controlTotal[$subQnum];
                $maxScore += 36; // Add max points for sub-domain
            }
        }
    }

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

// Domain-specific quick wins (profile-aware)
if ($profile === 'Security') {
    $quickWins = [
        'Secure Infrastructure' => 'Implement Infrastructure-as-Code (IaC) scanning and automated policy enforcement to advance configuration management maturity.',
        'Secure Data' => 'Deploy automated data classification tools and implement encryption-at-rest for all sensitive data repositories.',
        'Secure Identity' => 'Roll out risk-based MFA across all user accounts and implement privileged access management for administrative functions.',
        'Secure Application' => 'Integrate SAST/DAST into CI/CD pipelines and establish security champions program for development teams.',
        'Secure Network' => 'Implement microsegmentation for critical workloads and deploy network traffic analysis for anomaly detection.',
        'Secure Recovery' => 'Conduct quarterly disaster recovery drills and implement automated backup validation and restoration testing.',
        'Secure Operations' => 'Deploy SOAR platform for automated incident response playbooks and establish threat hunting program.'
    ];

    $businessImpacts = [
        'Secure Infrastructure' => 'Configuration drift and misconfigurations lead to vulnerabilities; lack of hardening exposes systems to exploitation and compliance violations.',
        'Secure Data' => 'Unencrypted or poorly classified data exposes organization to data breaches, regulatory fines (GDPR, CCPA), and reputational damage.',
        'Secure Identity' => 'Weak identity controls enable unauthorized access, privilege escalation, and insider threats; credential theft leads to account compromise.',
        'Secure Application' => 'Vulnerable applications expose organization to OWASP Top 10 attacks including SQL injection, XSS, and remote code execution.',
        'Secure Network' => 'Insufficient network segmentation allows lateral movement; unencrypted protocols expose data in transit to interception.',
        'Secure Recovery' => 'Inadequate backup and recovery capabilities result in extended downtime during incidents; inability to meet RTO/RPO requirements.',
        'Secure Operations' => 'Delayed threat detection and slow incident response increase breach impact; lack of security monitoring creates blind spots for attackers.'
    ];

    $firstSteps = [
        'Secure Infrastructure' => [
            'Implement configuration management tool (Ansible, Puppet) and establish baseline hardening standards (CIS Benchmarks)',
            'Deploy security scanning for container images and establish automated policy enforcement for infrastructure-as-code'
        ],
        'Secure Data' => [
            'Conduct data discovery and classification exercise to identify sensitive data locations',
            'Implement encryption-at-rest for databases and file storage; enable TLS 1.3 for all data in transit'
        ],
        'Secure Identity' => [
            'Roll out MFA for all remote access and administrative accounts within 90 days',
            'Implement centralized identity management (SSO) and establish RBAC policies aligned with least privilege'
        ],
        'Secure Application' => [
            'Integrate SAST tools into development workflow and establish secure coding standards',
            'Deploy web application firewall (WAF) for internet-facing applications and implement dependency scanning'
        ],
        'Secure Network' => [
            'Document network segmentation strategy and implement firewall rules following zero-trust principles',
            'Deploy network intrusion detection system (IDS) and enable logging for all firewall and network devices'
        ],
        'Secure Recovery' => [
            'Establish backup schedule with 3-2-1 rule (3 copies, 2 media types, 1 offsite) and test restoration quarterly',
            'Document disaster recovery plan with defined RTO/RPO targets and conduct tabletop exercise'
        ],
        'Secure Operations' => [
            'Deploy centralized SIEM solution and establish security event monitoring with 24/7 alerting',
            'Create incident response plan with defined roles, escalation procedures, and communication templates'
        ]
    ];
} else {
    // Digital Sovereignty profile
    $quickWins = [
        'Data Sovereignty' => 'Implement automated data flow monitoring and quarterly audits of vendor data access to advance toward Level 4 quantitative management.',
        'Technical Sovereignty' => 'Document exit strategies for all critical systems and conduct annual portability drills to strengthen vendor independence.',
        'Operational Sovereignty' => 'Establish a Center of Excellence for sovereign technologies and implement quarterly DR testing scenarios including geopolitical isolation.',
        'Assurance Sovereignty' => 'Expand continuous security validation with automated compliance reporting and establish formal vendor transparency requirements in all contracts.',
        'Open Source' => 'Formalize contribution policies and establish metrics tracking for community engagement and project influence.',
        'Executive Oversight' => 'Implement sovereignty KPI dashboards for Board reporting and establish quarterly reviews with regulatory authorities.',
        'Managed Services' => 'Develop comprehensive transition playbooks for all critical managed services and conduct annual vendor alternative assessments.'
    ];

    $businessImpacts = [
        'Data Sovereignty' => 'Exposes organization to foreign government data access demands, violates data residency regulations (GDPR, NIS2), and creates legal liability for cross-border data transfers.',
        'Technical Sovereignty' => 'Creates vendor lock-in preventing migration, increases costs through proprietary dependencies, and exposes organization to supply chain disruption risks.',
        'Operational Sovereignty' => 'Inability to maintain critical operations during vendor outages or geopolitical conflicts; excessive reliance on external expertise threatens business continuity.',
        'Assurance Sovereignty' => 'Limits ability to verify security claims, prevents independent compliance validation, and creates blind spots in third-party risk management.',
        'Open Source' => 'Increases dependency on proprietary software vendors, limits ability to audit code for security vulnerabilities, and reduces long-term technology flexibility.',
        'Executive Oversight' => 'Lack of strategic direction and budget allocation for sovereignty initiatives; inability to demonstrate compliance to regulators and stakeholders.',
        'Managed Services' => 'Third-party access to sensitive systems without adequate controls; inability to quickly transition services if vendor relationship deteriorates or sovereignty requirements change.'
    ];

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
}

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
<h3 style="color: #9ec7fc; text-align: center; margin-bottom: 1.5rem; font-size: 1.2rem;">Capability Status Distribution</h3>
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

<!-- Thematic View Tab -->
<div id="ThematicView" class="tabcontent">
<div style="max-width: 1600px; margin: 0 auto; padding: 2rem;">
<h1 style="color: #9ec7fc; font-size: 2rem; margin: 0 0 1.5rem 0;"><i class="fa-solid fa-grip"></i> Thematic Capability View</h1>

<?php
// Define thematic groupings - mapping capabilities to themes (profile-specific)
if ($profile === 'Security') {
    $thematicGroups = [
        'Configuration & Compliance' => [
            'icon' => 'cog',
            'color' => '#0d60f8',
            'overview' => 'This theme focuses on establishing and maintaining consistent security configurations across your infrastructure through automated policy enforcement and continuous compliance monitoring. It encompasses configuration management, policy automation, and security posture validation—ensuring systems remain in a known-good state that aligns with organizational security standards and regulatory requirements.',
            'capabilities' => [
                ['domain' => 'Secure Infrastructure', 'capability' => 1, 'name' => 'Config Management'],
                ['domain' => 'Secure Infrastructure', 'capability' => 4, 'name' => 'Automated Policy / Enforcement'],
                ['domain' => 'Secure Data', 'capability' => 6, 'name' => 'Automated Posture Management'],
                ['domain' => 'Secure Application', 'capability' => 3, 'name' => 'Secure Code Practices'],
                ['domain' => 'Secure Recovery', 'capability' => 5, 'name' => 'Lifecycle Management'],
            ]
        ],
        'Data Protection' => [
            'icon' => 'shield-halved',
            'color' => '#2aaa04',
            'overview' => 'This theme addresses comprehensive data protection throughout its lifecycle—from classification and encryption to loss prevention and immutable storage. It ensures sensitive data is properly identified, encrypted at rest and in transit, protected from unauthorized access or exfiltration, and stored in tamper-proof formats for compliance and forensic purposes.',
            'capabilities' => [
                ['domain' => 'Secure Data', 'capability' => 1, 'name' => 'Classification'],
                ['domain' => 'Secure Data', 'capability' => 2, 'name' => 'Encryption'],
                ['domain' => 'Secure Data', 'capability' => 3, 'name' => 'Access Control'],
                ['domain' => 'Secure Data', 'capability' => 4, 'name' => 'Tokenization'],
                ['domain' => 'Secure Data', 'capability' => 5, 'name' => 'Loss Prevention'],
                ['domain' => 'Secure Data', 'capability' => 7, 'name' => 'Immutable Storage'],
                ['domain' => 'Secure Infrastructure', 'capability' => 5, 'name' => 'Secrets Management'],
                ['domain' => 'Secure Recovery', 'capability' => 7, 'name' => 'Advanced Key Management'],
            ]
        ],
        'Identity & Access Management' => [
            'icon' => 'user-shield',
            'color' => '#ec7a08',
            'overview' => 'This theme evaluates your organization\'s ability to authenticate users, authorize access, and manage privileged accounts across the enterprise. It covers the full spectrum from basic password policies to advanced contextual access controls, encompassing MFA, SSO, PAM, identity federation, and risk-based authentication mechanisms that adapt to threat context.',
            'capabilities' => [
                ['domain' => 'Secure Identity', 'capability' => 1, 'name' => 'Passwords'],
                ['domain' => 'Secure Identity', 'capability' => 2, 'name' => 'Role-Based Access Control'],
                ['domain' => 'Secure Identity', 'capability' => 3, 'name' => 'Multi-Factor Authentication'],
                ['domain' => 'Secure Identity', 'capability' => 4, 'name' => 'Single Sign On'],
                ['domain' => 'Secure Identity', 'capability' => 5, 'name' => 'Privileged Access Management'],
                ['domain' => 'Secure Identity', 'capability' => 6, 'name' => 'Identity Federation'],
                ['domain' => 'Secure Identity', 'capability' => 8, 'name' => 'Contextual / Risk Based Access'],
                ['domain' => 'Secure Operations', 'capability' => 3, 'name' => 'Access Control and Identity Management'],
            ]
        ],
        'Application Security' => [
            'icon' => 'code',
            'color' => '#12bbd4',
            'overview' => 'This theme addresses security throughout the software development lifecycle—from managing dependencies and scanning code for vulnerabilities to protecting applications at runtime. It encompasses secure coding practices, static and dynamic testing, container scanning, web application firewalls, and runtime application self-protection (RASP) for comprehensive application defense.',
            'capabilities' => [
                ['domain' => 'Secure Application', 'capability' => 1, 'name' => 'Dependency Management'],
                ['domain' => 'Secure Application', 'capability' => 2, 'name' => 'Static Application Security Testing'],
                ['domain' => 'Secure Application', 'capability' => 4, 'name' => 'Dynamic Application Security Testing'],
                ['domain' => 'Secure Application', 'capability' => 5, 'name' => 'Web Application Firewall'],
                ['domain' => 'Secure Application', 'capability' => 6, 'name' => 'Container Scanning'],
                ['domain' => 'Secure Application', 'capability' => 7, 'name' => 'Runtime Application Self Protection'],
                ['domain' => 'Secure Application', 'capability' => 8, 'name' => 'Interactive Application Security Testing'],
                ['domain' => 'Secure Infrastructure', 'capability' => 6, 'name' => 'Container Runtime Security'],
            ]
        ],
        'Network Security' => [
            'icon' => 'network-wired',
            'color' => '#f0ab00',
            'overview' => 'This theme focuses on securing network communications and implementing defense-in-depth through segmentation, encryption, and access controls. It covers traditional perimeter defenses like firewalls and IDS/IPS, as well as modern zero-trust architectures including microsegmentation, service mesh security, and identity-based perimeters that minimize implicit trust.',
            'capabilities' => [
                ['domain' => 'Secure Network', 'capability' => 1, 'name' => 'Firewalls & Segmentation'],
                ['domain' => 'Secure Network', 'capability' => 2, 'name' => 'Secure Protocols'],
                ['domain' => 'Secure Network', 'capability' => 3, 'name' => 'Access Control Lists'],
                ['domain' => 'Secure Network', 'capability' => 4, 'name' => 'Intrusion Detection / Prevention'],
                ['domain' => 'Secure Network', 'capability' => 5, 'name' => 'Traffic Analysis'],
                ['domain' => 'Secure Network', 'capability' => 6, 'name' => 'Secure Connections'],
                ['domain' => 'Secure Network', 'capability' => 7, 'name' => 'Microsegmentation'],
                ['domain' => 'Secure Network', 'capability' => 8, 'name' => 'Zero Trust Network Access'],
                ['domain' => 'Secure Infrastructure', 'capability' => 2, 'name' => 'Segmentation / Isolation'],
                ['domain' => 'Secure Infrastructure', 'capability' => 7, 'name' => 'Service Mesh Security'],
                ['domain' => 'Secure Infrastructure', 'capability' => 8, 'name' => 'Identity-Based Perimeter'],
            ]
        ],
        'Detection & Monitoring' => [
            'icon' => 'binoculars',
            'color' => '#c9190b',
            'overview' => 'This theme examines your organization\'s ability to detect security threats through continuous monitoring, log analysis, and anomaly detection. It encompasses logging and monitoring infrastructure, SIEM platforms for correlation and analysis, endpoint detection and response (EDR), threat intelligence integration, and advanced AI/ML-based anomaly detection across identity, data, and operations.',
            'capabilities' => [
                ['domain' => 'Secure Infrastructure', 'capability' => 3, 'name' => 'Logging & Monitoring'],
                ['domain' => 'Secure Operations', 'capability' => 3, 'name' => 'Security Information & Event Management'],
                ['domain' => 'Secure Operations', 'capability' => 4, 'name' => 'Endpoint Detection & Response'],
                ['domain' => 'Secure Operations', 'capability' => 6, 'name' => 'Threat Intelligence Integration'],
                ['domain' => 'Secure Data', 'capability' => 8, 'name' => 'Anomaly Detection'],
                ['domain' => 'Secure Identity', 'capability' => 7, 'name' => 'AI/ML Anomaly Detection'],
                ['domain' => 'Secure Recovery', 'capability' => 6, 'name' => 'Storage Scanning & Monitoring'],
                ['domain' => 'Secure Operations', 'capability' => 2, 'name' => 'Anti-Virus scan'],
            ]
        ],
        'Incident Response & Recovery' => [
            'icon' => 'life-ring',
            'color' => '#a18fff',
            'overview' => 'This theme addresses organizational resilience through comprehensive incident response planning, disaster recovery capabilities, and business continuity measures. It covers incident response plans, backup and redundancy strategies, disaster recovery procedures, automated failovers, consistent versioning, predictive recovery capabilities, and security orchestration, automation, and response (SOAR) for rapid incident handling.',
            'capabilities' => [
                ['domain' => 'Secure Operations', 'capability' => 1, 'name' => 'Incident Response Plan'],
                ['domain' => 'Secure Operations', 'capability' => 5, 'name' => 'Orchestration, Automation, Response'],
                ['domain' => 'Secure Recovery', 'capability' => 1, 'name' => 'Backup & Redundancy'],
                ['domain' => 'Secure Recovery', 'capability' => 2, 'name' => 'Disaster Recovery Plan'],
                ['domain' => 'Secure Recovery', 'capability' => 3, 'name' => 'Consistent Versioning'],
                ['domain' => 'Secure Recovery', 'capability' => 4, 'name' => 'Automated Failovers'],
                ['domain' => 'Secure Recovery', 'capability' => 8, 'name' => 'Predictive Recovery'],
            ]
        ],
        'Advanced Threat Defense' => [
            'icon' => 'shield-virus',
            'color' => '#7d1007',
            'overview' => 'This theme focuses on defending against sophisticated adversaries through advanced threat detection, hunting, and validation. It encompasses Advanced Persistent Threat (APT) detection and response capabilities, purple team exercises combining offensive and defensive security, and proactive threat hunting. These capabilities go beyond basic detection to identify and respond to nation-state actors, organized cybercrime, and advanced attack techniques.',
            'capabilities' => [
                ['domain' => 'Secure Operations', 'capability' => 7, 'name' => 'APT Detection & Response'],
                ['domain' => 'Secure Operations', 'capability' => 8, 'name' => 'Purple Teaming'],
            ]
        ],
    ];
} elseif ($profile === 'AISovereignty') {
    // AI Sovereignty profile thematic groups
    $thematicGroups = [
        'AI Data & Model Governance' => [
            'icon' => 'database',
            'color' => '#0d60f8',
            'overview' => 'This theme ensures sovereign control over AI training data, model artifacts, and governance frameworks. It addresses data residency for AI workloads, model ownership and transparency, regulatory compliance (EU AI Act), and ethical AI practices including bias detection and fairness testing.',
            'capabilities' => [
                ['domain' => 'AI Data Sovereignty', 'capability' => 1, 'name' => 'AI Training Data Residency & Location'],
                ['domain' => 'AI Data Sovereignty', 'capability' => 2, 'name' => 'AI Inference Data Protection & Privacy'],
                ['domain' => 'AI Data Sovereignty', 'capability' => 6, 'name' => 'Training Data Provenance & Quality Control'],
                ['domain' => 'AI Model Sovereignty', 'capability' => 1, 'name' => 'Model Architecture Ownership & Control'],
                ['domain' => 'AI Model Sovereignty', 'capability' => 3, 'name' => 'Model Interpretability & Explainability'],
                ['domain' => 'AI Model Sovereignty', 'capability' => 7, 'name' => 'Algorithmic Transparency & Auditability'],
                ['domain' => 'AI Model Sovereignty', 'capability' => 8, 'name' => 'Model Governance Framework'],
                ['domain' => 'AI Governance & Compliance', 'capability' => 1, 'name' => 'AI Governance Policy Framework'],
                ['domain' => 'AI Governance & Compliance', 'capability' => 2, 'name' => 'Regulatory Compliance Tracking'],
                ['domain' => 'AI Governance & Compliance', 'capability' => 3, 'name' => 'AI Ethics & Responsible AI Principles'],
                ['domain' => 'AI Governance & Compliance', 'capability' => 4, 'name' => 'Bias Detection & Fairness Testing'],
            ]
        ],
        'AI Infrastructure & Operations' => [
            'icon' => 'server',
            'color' => '#2aaa04',
            'overview' => 'This theme focuses on independent control of AI compute resources, deployment platforms, and operational capabilities. It encompasses sovereign GPU infrastructure, model portability, inference independence, edge AI deployment, and production monitoring for AI systems.',
            'capabilities' => [
                ['domain' => 'AI Infrastructure Sovereignty', 'capability' => 1, 'name' => 'Sovereign GPU & Accelerator Resources'],
                ['domain' => 'AI Infrastructure Sovereignty', 'capability' => 2, 'name' => 'AI Training Environment Isolation'],
                ['domain' => 'AI Infrastructure Sovereignty', 'capability' => 3, 'name' => 'Model Registry & Artifact Control'],
                ['domain' => 'AI Infrastructure Sovereignty', 'capability' => 6, 'name' => 'Inference Infrastructure Independence'],
                ['domain' => 'AI Infrastructure Sovereignty', 'capability' => 7, 'name' => 'Edge AI Deployment Capabilities'],
                ['domain' => 'AI Model Sovereignty', 'capability' => 6, 'name' => 'Model Portability & Export Capabilities'],
                ['domain' => 'AI Operations Sovereignty', 'capability' => 1, 'name' => 'AI Model Performance Monitoring'],
                ['domain' => 'AI Operations Sovereignty', 'capability' => 2, 'name' => 'AI System Observability & Logging'],
                ['domain' => 'AI Operations Sovereignty', 'capability' => 5, 'name' => 'Model Retraining & Update Operations'],
                ['domain' => 'AI Operations Sovereignty', 'capability' => 7, 'name' => 'AI Disaster Recovery & Business Continuity'],
            ]
        ],
        'AI Supply Chain & Security' => [
            'icon' => 'link',
            'color' => '#ec7a08',
            'overview' => 'This theme addresses the security and transparency of the AI supply chain from data sources through model deployment. It covers model provenance verification, third-party model risk assessment, dependency management, container scanning, and continuous supply chain monitoring.',
            'capabilities' => [
                ['domain' => 'AI Supply Chain Sovereignty', 'capability' => 1, 'name' => 'Model Provenance & Authenticity Verification'],
                ['domain' => 'AI Supply Chain Sovereignty', 'capability' => 2, 'name' => 'Third-Party Model Risk Assessment'],
                ['domain' => 'AI Supply Chain Sovereignty', 'capability' => 3, 'name' => 'AI Dependency & Library Management'],
                ['domain' => 'AI Supply Chain Sovereignty', 'capability' => 4, 'name' => 'Training Data Supply Chain Security'],
                ['domain' => 'AI Supply Chain Sovereignty', 'capability' => 5, 'name' => 'Model & Container Image Scanning'],
                ['domain' => 'AI Supply Chain Sovereignty', 'capability' => 6, 'name' => 'AI Software Bill of Materials (SBOM)'],
                ['domain' => 'AI Supply Chain Sovereignty', 'capability' => 8, 'name' => 'Continuous Supply Chain Monitoring'],
                ['domain' => 'AI Data Sovereignty', 'capability' => 7, 'name' => 'Real-time Data Flow Monitoring for AI'],
                ['domain' => 'AI Model Sovereignty', 'capability' => 4, 'name' => 'Model Versioning & Lineage Tracking'],
            ]
        ],
        'AI Innovation & Competitive Advantage' => [
            'icon' => 'lightbulb',
            'color' => '#a18fff',
            'overview' => 'This theme focuses on building independent AI innovation capabilities to maintain competitive advantage and reduce vendor dependency. It includes internal R&D, experimentation platforms, custom model development, AI talent development, and open-source AI engagement.',
            'capabilities' => [
                ['domain' => 'AI Innovation Sovereignty', 'capability' => 1, 'name' => 'Internal AI Research & Development'],
                ['domain' => 'AI Innovation Sovereignty', 'capability' => 2, 'name' => 'AI Experimentation Platform & Tools'],
                ['domain' => 'AI Innovation Sovereignty', 'capability' => 3, 'name' => 'Open Source AI Contribution & Participation'],
                ['domain' => 'AI Innovation Sovereignty', 'capability' => 4, 'name' => 'Custom AI Model Development Capabilities'],
                ['domain' => 'AI Innovation Sovereignty', 'capability' => 5, 'name' => 'AI Talent Development & Retention'],
                ['domain' => 'AI Innovation Sovereignty', 'capability' => 6, 'name' => 'Proprietary AI Intellectual Property'],
                ['domain' => 'AI Innovation Sovereignty', 'capability' => 7, 'name' => 'AI Academic & Industry Partnerships'],
                ['domain' => 'AI Innovation Sovereignty', 'capability' => 8, 'name' => 'AI Innovation Culture & Practices'],
                ['domain' => 'AI Infrastructure Sovereignty', 'capability' => 4, 'name' => 'Distributed Training Orchestration'],
            ]
        ],
    ];
} else {
    // Digital Sovereignty profile thematic groups (default)
    $thematicGroups = [
        'Governance & Policy' => [
            'icon' => 'gavel',
            'color' => '#0d60f8',
            'overview' => 'This theme addresses formal governance structures, policy frameworks, and strategic integration of Digital Sovereignty principles across your organization. It evaluates executive accountability, board-level oversight, and the legal/jurisdictional controls ensuring data sovereignty. Key aspects include designated executive sponsorship, formal sovereignty policies with dedicated budgets, and legal frameworks controlling data access and jurisdictional authority.',
            'capabilities' => [
                ['domain' => 'Data Sovereignty', 'capability' => 4, 'name' => 'Legal & Jurisdictional Control'],
                ['domain' => 'Data Sovereignty', 'capability' => 8, 'name' => 'Data Access by Third Parties Policies'],
                ['domain' => 'Open Source', 'capability' => 1, 'name' => 'OSS Policy and Usage Guidelines'],
                ['domain' => 'Executive Oversight', 'capability' => 1, 'name' => 'Designated Executive Sponsor'],
                ['domain' => 'Executive Oversight', 'capability' => 2, 'name' => 'Defined Digital Sovereignty Policy'],
                ['domain' => 'Executive Oversight', 'capability' => 3, 'name' => 'Budget Allocation for Sovereignty Initiatives'],
                ['domain' => 'Executive Oversight', 'capability' => 4, 'name' => 'Integration into Organisational Strategy'],
                ['domain' => 'Executive Oversight', 'capability' => 7, 'name' => 'Dedicated Sovereignty Governance Board'],
            ]
        ],
        'Data & Privacy' => [
            'icon' => 'shield-halved',
            'color' => '#2aaa04',
            'overview' => 'This theme focuses on comprehensive data protection across its entire lifecycle—from classification and residency to encryption and privacy compliance. It ensures your organization maintains ultimate control over data independent of external jurisdictions, implements robust cryptographic key management, and verifies that security monitoring data remains under sovereign control. Core elements include data residency policies, privacy-by-design principles, and workload data protection during processing.',
            'capabilities' => [
                ['domain' => 'Data Sovereignty', 'capability' => 1, 'name' => 'Data Residency & Location'],
                ['domain' => 'Data Sovereignty', 'capability' => 2, 'name' => 'Data Protection & Privacy'],
                ['domain' => 'Data Sovereignty', 'capability' => 3, 'name' => 'Data Classification and Inventory'],
                ['domain' => 'Data Sovereignty', 'capability' => 5, 'name' => 'Cryptographic Key Management Control'],
                ['domain' => 'Data Sovereignty', 'capability' => 6, 'name' => 'Workload Data Protection & Privacy'],
                ['domain' => 'Assurance Sovereignty', 'capability' => 2, 'name' => 'Control over Security Monitoring Data'],
            ]
        ],
        'Risk & Compliance' => [
            'icon' => 'clipboard-check',
            'color' => '#ec7a08',
            'overview' => 'This theme addresses your ability to verify security and compliance claims through independent audits, certifications, and continuous validation—ensuring trust is verified, not assumed. It encompasses formal risk management frameworks, compliance with local security standards, and dependency risk assessment for open source components. Key capabilities include regular security audits, independent certification and vetting processes, and defined KPIs for measuring sovereignty performance.',
            'capabilities' => [
                ['domain' => 'Assurance Sovereignty', 'capability' => 1, 'name' => 'Regular Security Audits Conducted'],
                ['domain' => 'Assurance Sovereignty', 'capability' => 3, 'name' => 'Risk Management Framework'],
                ['domain' => 'Assurance Sovereignty', 'capability' => 4, 'name' => 'Compliance with Local Security Standards'],
                ['domain' => 'Assurance Sovereignty', 'capability' => 6, 'name' => 'Independent Certification and Vetting'],
                ['domain' => 'Assurance Sovereignty', 'capability' => 8, 'name' => 'Continuous Security Control Validation'],
                ['domain' => 'Open Source', 'capability' => 4, 'name' => 'Dependency Risk Assessment'],
                ['domain' => 'Executive Oversight', 'capability' => 8, 'name' => 'Key Performance Indicators (KPIs) Defined'],
            ]
        ],
        'Technical Control' => [
            'icon' => 'microchip',
            'color' => '#12bbd4',
            'overview' => 'This theme evaluates your control over foundational technology components—from hardware and firmware to application runtime environments. It prioritizes open standards, platform portability, and vendor independence while minimizing technical dependencies that restrict flexibility. Core aspects include technology stack ownership, vendor lock-in mitigation, standardized framework adoption, and control over cloud infrastructure placement including sovereign image registries and regional zoning.',
            'capabilities' => [
                ['domain' => 'Technical Sovereignty', 'capability' => 1, 'name' => 'Technology Stack Ownership & Control'],
                ['domain' => 'Technical Sovereignty', 'capability' => 2, 'name' => 'Vendor Lock-in Risk Mitigation'],
                ['domain' => 'Technical Sovereignty', 'capability' => 3, 'name' => 'Standardised Technical Framework Adoption'],
                ['domain' => 'Technical Sovereignty', 'capability' => 4, 'name' => 'Interoperability and Portability Strategy'],
                ['domain' => 'Technical Sovereignty', 'capability' => 5, 'name' => 'Hardware and Infrastructure Source Verification'],
                ['domain' => 'Technical Sovereignty', 'capability' => 6, 'name' => 'Self-Hosted Application Runtime Control'],
                ['domain' => 'Managed Services', 'capability' => 1, 'name' => 'Region and Zoning Control'],
                ['domain' => 'Managed Services', 'capability' => 2, 'name' => 'Sovereign Image and Container Registry'],
            ]
        ],
        'Operational Resilience' => [
            'icon' => 'server',
            'color' => '#f0ab00',
            'overview' => 'This theme examines your autonomy and independence in executing critical business and IT operations without mandatory reliance on external expertise or infrastructure. It ensures business continuity through locally managed processes, internal capability development, and independent incident response. Key elements include comprehensive operational documentation, disaster recovery planning, internal skills development, future-proofing technology roadmaps, and fostering a sovereignty-aware organizational culture.',
            'capabilities' => [
                ['domain' => 'Operational Sovereignty', 'capability' => 1, 'name' => 'Operational Process Documentation'],
                ['domain' => 'Operational Sovereignty', 'capability' => 4, 'name' => 'Internal Skills and Competency Development'],
                ['domain' => 'Operational Sovereignty', 'capability' => 5, 'name' => 'Disaster Recovery and Business Continuity'],
                ['domain' => 'Operational Sovereignty', 'capability' => 8, 'name' => 'Operational Autonomy in Critical Functions'],
                ['domain' => 'Technical Sovereignty', 'capability' => 8, 'name' => 'Future-Proofing Technology Roadmaps'],
                ['domain' => 'Managed Services', 'capability' => 8, 'name' => 'Multi-Cloud Exit Strategy Testing'],
                ['domain' => 'Executive Oversight', 'capability' => 6, 'name' => 'Sovereignty Culture and Awareness Program'],
            ]
        ],
        'Vendor & Dependencies' => [
            'icon' => 'handshake',
            'color' => '#c9190b',
            'overview' => 'This theme addresses management of external dependencies including cloud providers, managed service vendors, and open source software components. It evaluates transparency requirements for vendor security practices, supply chain vetting processes, and contingency strategies such as code escrow and forking capabilities. Key aspects include dependency mapping, hyperscaler data access controls, and maintaining the ability to operate independently if vendor relationships change or terminate.',
            'capabilities' => [
                ['domain' => 'Operational Sovereignty', 'capability' => 2, 'name' => 'Dependency on External Managed Services'],
                ['domain' => 'Operational Sovereignty', 'capability' => 6, 'name' => 'Supply Chain Transparency and Vetting'],
                ['domain' => 'Assurance Sovereignty', 'capability' => 5, 'name' => 'Transparency in Vendor Security Practices'],
                ['domain' => 'Open Source', 'capability' => 3, 'name' => 'Source Code Escrow Arrangements'],
                ['domain' => 'Open Source', 'capability' => 5, 'name' => 'Forking Strategy for Critical OSS'],
                ['domain' => 'Managed Services', 'capability' => 3, 'name' => 'Resource Dependency Mapping'],
                ['domain' => 'Managed Services', 'capability' => 4, 'name' => 'Hyperscaler Data Access Vetting'],
            ]
        ],
        'Monitoring & Security' => [
            'icon' => 'binoculars',
            'color' => '#a18fff',
            'overview' => 'This theme focuses on continuous security monitoring, access control, and audit capabilities ensuring you maintain visibility and control over your infrastructure. It encompasses data flow auditing, identity and access management, incident response planning, and the ability to invoke sovereign inspections. Critical components include network egress/ingress controls, configuration-as-code ownership, control plane audit capabilities, and comprehensive logging with independent forensic analysis.',
            'capabilities' => [
                ['domain' => 'Data Sovereignty', 'capability' => 7, 'name' => 'Data Flow and Transfer Auditing'],
                ['domain' => 'Operational Sovereignty', 'capability' => 3, 'name' => 'Access Control and Identity Management'],
                ['domain' => 'Operational Sovereignty', 'capability' => 7, 'name' => 'Sovereign Incident Response Plan'],
                ['domain' => 'Assurance Sovereignty', 'capability' => 7, 'name' => 'Ability to Invoke Sovereign Inspections'],
                ['domain' => 'Managed Services', 'capability' => 5, 'name' => 'Network Egress/Ingress Path Control'],
                ['domain' => 'Managed Services', 'capability' => 6, 'name' => 'Configuration-as-Code Ownership'],
                ['domain' => 'Managed Services', 'capability' => 7, 'name' => 'Control Plane Audit and Integrity'],
            ]
        ],
        'Open Source' => [
            'icon' => 'code-branch',
            'color' => '#7d1007',
            'overview' => 'This theme focuses on strategic adoption of Open Source Software (OSS) to eliminate vendor dependency and strengthen technical control through code transparency and community engagement. It goes beyond consumption to active contribution, ensuring influence over project direction and maintaining fork capabilities as contingency plans. Key elements include internal OSS expertise development, intellectual property control, community engagement, and the ability to audit, modify, and self-maintain critical open source components.',
            'capabilities' => [
                ['domain' => 'Open Source', 'capability' => 2, 'name' => 'Internal OSS Skills and Expertise'],
                ['domain' => 'Open Source', 'capability' => 6, 'name' => 'Contribution to Strategic OSS Projects'],
                ['domain' => 'Open Source', 'capability' => 7, 'name' => 'Active OSS Community Engagement'],
                ['domain' => 'Open Source', 'capability' => 8, 'name' => 'Ability to Influence OSS Roadmaps'],
                ['domain' => 'Technical Sovereignty', 'capability' => 7, 'name' => 'Code and Intellectual Property Control'],
                ['domain' => 'Executive Oversight', 'capability' => 5, 'name' => 'Regular Reporting to the Board'],
            ]
        ],
    ];
}

// Map domain names to qnum
$domainNameToQnum = [];
foreach ($controls as $control) {
    $domainNameToQnum[$json[$control]['title']] = $json[$control]['qnum'];
}

$statusLabels = ['No Capability', 'In Planning', 'Work in Progress', 'Fully Complete'];
$statusColors = ['#6a6e73', '#f0ab00', '#ec7a08', '#2aaa04'];

// Calculate statistics for each theme first
$themeStatistics = [];
foreach ($thematicGroups as $themeName => $themeData) {
    $themeStats = ['total' => 0, 'complete' => 0, 'inprogress' => 0, 'planning' => 0, 'none' => 0, 'maturityScore' => 0];

    foreach ($themeData['capabilities'] as $capInfo) {
        $qnum = $domainNameToQnum[$capInfo['domain']];
        $capNum = $capInfo['capability'];
        $controlId = "control{$qnum}-{$capNum}";
        $sliderValue = isset($data[$controlId]) ? intval($data[$controlId]) : 0;

        $themeStats['total']++;
        $themeStats['maturityScore'] += $sliderValue;
        if ($sliderValue == 3) $themeStats['complete']++;
        elseif ($sliderValue == 2) $themeStats['inprogress']++;
        elseif ($sliderValue == 1) $themeStats['planning']++;
        else $themeStats['none']++;
    }

    $themeStatistics[$themeName] = $themeStats;
}
?>

<!-- Summary Dashboard -->
<div style="background: #1a1a1a; border: 1px solid #444; border-radius: 8px; padding: 2rem; margin-bottom: 2rem;">
<h2 style="color: #9ec7fc; margin: 0 0 1.5rem 0; font-size: 1.4rem;"><i class="fa-solid fa-chart-bar"></i> Thematic Summary</h2>

<!-- Theme Cards Grid - 4 per row -->
<div class="theme-cards-grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 1.5rem;">
<?php
foreach ($thematicGroups as $themeName => $themeData) {
    $stats = $themeStatistics[$themeName];
    $maturityPercent = $stats['total'] > 0 ? round(($stats['maturityScore'] / ($stats['total'] * 3)) * 100) : 0;

    // Determine color based on maturity
    $cardBorderColor = $themeData['color'];
    if ($maturityPercent >= 75) $bgGradient = 'rgba(42, 170, 4, 0.1)';
    elseif ($maturityPercent >= 50) $bgGradient = 'rgba(236, 122, 8, 0.1)';
    elseif ($maturityPercent >= 25) $bgGradient = 'rgba(240, 171, 0, 0.1)';
    else $bgGradient = 'rgba(106, 110, 115, 0.1)';

    print '<div style="background: linear-gradient(135deg, ' . $bgGradient . ' 0%, #2a2a2a 100%); border: 2px solid ' . $cardBorderColor . '; border-radius: 8px; padding: 1.25rem; text-align: center; transition: transform 0.2s ease, box-shadow 0.2s ease; cursor: pointer;" onclick="scrollToTheme(\'' . str_replace(' ', '', $themeName) . '\')" onmouseover="this.style.transform=\'translateY(-4px)\'; this.style.boxShadow=\'0 6px 20px rgba(13, 96, 248, 0.3)\';" onmouseout="this.style.transform=\'translateY(0)\'; this.style.boxShadow=\'none\';">';

    // Icon
    print '<div style="font-size: 2.5rem; color: ' . $cardBorderColor . '; margin-bottom: 0.75rem;"><i class="fa-solid fa-' . $themeData['icon'] . '"></i></div>';

    // Theme name with info icon
    print '<div style="color: #fff; font-weight: 600; font-size: 0.95rem; margin-bottom: 0.75rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">';
    print '<span>' . $themeName . '</span>';
    print '<i class="fa-solid fa-info-circle tooltip-icon theme-tooltip" style="font-size: 0.75rem; cursor: help;" data-tooltip="' . htmlspecialchars($themeData['overview'], ENT_QUOTES, 'UTF-8') . '" data-theme-name="' . htmlspecialchars($themeName, ENT_QUOTES, 'UTF-8') . '" data-theme-icon="' . $themeData['icon'] . '" data-theme-color="' . $cardBorderColor . '"></i>';
    print '</div>';

    // Maturity percentage (large)
    print '<div style="font-size: 2.5rem; font-weight: 700; color: ' . $cardBorderColor . '; margin-bottom: 0.5rem;">' . $maturityPercent . '%</div>';

    // Capability count
    print '<div style="color: #999; font-size: 0.85rem; margin-bottom: 0.75rem;">' . $stats['total'] . ' capabilities</div>';

    // Mini progress bar
    $completePercent = $stats['total'] > 0 ? ($stats['complete'] / $stats['total']) * 100 : 0;
    $inprogressPercent = $stats['total'] > 0 ? ($stats['inprogress'] / $stats['total']) * 100 : 0;
    $planningPercent = $stats['total'] > 0 ? ($stats['planning'] / $stats['total']) * 100 : 0;

    print '<div style="height: 6px; background: #444; border-radius: 3px; overflow: hidden; display: flex;">';
    if ($completePercent > 0) print '<div style="width: ' . $completePercent . '%; background: #2aaa04;"></div>';
    if ($inprogressPercent > 0) print '<div style="width: ' . $inprogressPercent . '%; background: #ec7a08;"></div>';
    if ($planningPercent > 0) print '<div style="width: ' . $planningPercent . '%; background: #f0ab00;"></div>';
    print '</div>';

    print '</div>';
}
?>
</div>
<!-- End of Theme Cards Grid -->

<!-- Theme Overview Display Area - Full Width Below Cards -->
<div id="themeOverviewDisplay" style="background: #2a2a2a; border: 2px solid #444; border-radius: 8px; padding: 1.25rem; min-height: 120px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
<div id="themeOverviewContent" style="display: none; width: 100%;">
<div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem;">
<div id="themeOverviewIcon" style="font-size: 2rem;"></div>
<h3 id="themeOverviewTitle" style="color: #9ec7fc; margin: 0; font-size: 1.2rem;"></h3>
</div>
<div id="themeOverviewText" style="color: #ccc; line-height: 1.6; font-size: 0.95rem;"></div>
</div>
<div id="themeOverviewPlaceholder" style="text-align: center; color: #666;">
<i class="fa-solid fa-info-circle" style="font-size: 2.5rem; margin-bottom: 0.75rem; opacity: 0.3;"></i>
<p style="margin: 0; font-size: 0.9rem;">Hover over any theme's <i class="fa-solid fa-info-circle" style="font-size: 0.85rem;"></i> icon to view its overview</p>
</div>
</div>

<div style="margin-top: 1.5rem; padding: 1rem; background: rgba(13, 96, 248, 0.1); border-left: 3px solid #0d60f8; border-radius: 4px;">
<p style="color: #9ec7fc; margin: 0; font-size: 0.9rem;"><i class="fa-solid fa-info-circle"></i> <strong>Tip:</strong> Click any theme card to jump to its details below. Themes show cross-cutting patterns across all domains.</p>
</div>
</div>

<!-- Detailed Thematic Groups -->
<?php
foreach ($thematicGroups as $themeName => $themeData) {
    $stats = $themeStatistics[$themeName];
    $maturityPercent = $stats['total'] > 0 ? round(($stats['maturityScore'] / ($stats['total'] * 3)) * 100) : 0;

    print '<div id="' . str_replace(' ', '', $themeName) . '" style="margin-bottom: 1.5rem; padding: 1.5rem; background: #2a2a2a; border-left: 4px solid ' . $themeData['color'] . '; border-radius: 6px;">';

    // Header with icon and percentage
    print '<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">';
    print '<h3 style="color: #fff; margin: 0; font-size: 1.3rem;">';
    print '<i class="fa-solid fa-' . $themeData['icon'] . '" style="color: ' . $themeData['color'] . '; margin-right: 0.5rem;"></i>';
    print $themeName;
    print '</h3>';
    print '<div style="font-size: 1.8rem; font-weight: 700; color: ' . $themeData['color'] . ';">' . $maturityPercent . '%</div>';
    print '</div>';

    // Thematic overview
    print '<div style="padding: 1rem; background: rgba(13, 96, 248, 0.05); border-left: 3px solid ' . $themeData['color'] . '; border-radius: 4px; margin-bottom: 1rem;">';
    print '<p style="color: #ccc; margin: 0; font-size: 0.9rem; line-height: 1.6;">' . $themeData['overview'] . '</p>';
    print '</div>';

    // Compact capability grid with color-coded dots
    print '<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 0.5rem; margin-bottom: 1rem;">';

    foreach ($themeData['capabilities'] as $capInfo) {
        $qnum = $domainNameToQnum[$capInfo['domain']];
        $capNum = $capInfo['capability'];
        $controlId = "control{$qnum}-{$capNum}";
        $sliderValue = isset($data[$controlId]) ? intval($data[$controlId]) : 0;

        print '<div style="display: flex; align-items: center; padding: 0.5rem; background: #1a1a1a; border-radius: 4px; border: 1px solid #444;">';

        // Color dot
        print '<div style="width: 12px; height: 12px; border-radius: 50%; background: ' . $statusColors[$sliderValue] . '; margin-right: 0.75rem; flex-shrink: 0;"></div>';

        // Capability name and domain
        print '<div style="flex: 1; min-width: 0;">';
        print '<div style="color: #e0e0e0; font-size: 0.85rem; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">' . Security::escape($capInfo['name']) . '</div>';
        print '<div style="color: #888; font-size: 0.75rem;">' . Security::escape($capInfo['domain']) . '</div>';
        print '</div>';

        print '</div>';
    }

    print '</div>';

    // Compact summary bar
    $completePercent = $stats['total'] > 0 ? round(($stats['complete'] / $stats['total']) * 100) : 0;
    $inprogressPercent = $stats['total'] > 0 ? round(($stats['inprogress'] / $stats['total']) * 100) : 0;
    $planningPercent = $stats['total'] > 0 ? round(($stats['planning'] / $stats['total']) * 100) : 0;

    print '<div style="display: flex; align-items: center; gap: 1rem;">';
    print '<div style="flex: 1; height: 24px; background: #444; border-radius: 4px; overflow: hidden; display: flex;">';
    if ($completePercent > 0) print '<div style="width: ' . $completePercent . '%; background: #2aaa04;"></div>';
    if ($inprogressPercent > 0) print '<div style="width: ' . $inprogressPercent . '%; background: #ec7a08;"></div>';
    if ($planningPercent > 0) print '<div style="width: ' . $planningPercent . '%; background: #f0ab00;"></div>';
    print '</div>';
    print '<div style="color: #ccc; font-size: 0.85rem; white-space: nowrap;">';
    print '<span style="color: #2aaa04;">●</span> ' . $stats['complete'] . ' ';
    print '<span style="color: #ec7a08;">●</span> ' . $stats['inprogress'] . ' ';
    print '<span style="color: #f0ab00;">●</span> ' . $stats['planning'] . ' ';
    print '<span style="color: #6a6e73;">●</span> ' . $stats['none'];
    print '</div>';
    print '</div>';

    print '</div>';
}
?>

</div>
</div>

<script>
function scrollToTheme(themeId) {
    document.getElementById(themeId).scrollIntoView({ behavior: 'smooth', block: 'start' });
}

// Theme overview display in dedicated panel
$(document).ready(function() {
    $(".theme-tooltip").on('mouseenter', function(e) {
        var overviewText = $(this).attr('data-tooltip');
        var themeName = $(this).attr('data-theme-name');
        var themeIcon = $(this).attr('data-theme-icon');
        var themeColor = $(this).attr('data-theme-color');

        if (!overviewText) return;

        // Decode HTML entities
        var txt = document.createElement("textarea");
        txt.innerHTML = overviewText;
        var decoded = txt.value;

        // Update the overview display
        $('#themeOverviewIcon').html('<i class="fa-solid fa-' + themeIcon + '" style="color: ' + themeColor + ';"></i>');
        $('#themeOverviewTitle').text(themeName);
        $('#themeOverviewText').text(decoded);

        // Show content, hide placeholder
        $('#themeOverviewPlaceholder').fadeOut(200, function() {
            $('#themeOverviewContent').fadeIn(200);
        });
    });

    $(".theme-tooltip").on('mouseleave', function() {
        // Show placeholder, hide content
        $('#themeOverviewContent').fadeOut(200, function() {
            $('#themeOverviewPlaceholder').fadeIn(200);
        });
    });
});
</script>

<!-- Detailed Output -->
<div id="Recommendations" class="tabcontent">
<div id="accordion">
<?php
foreach ($controls as $control) {
    $highest=0;
    $qnum = $json[$control]['qnum'];
	$score = $controlTotal[$qnum];

	// Check if this domain includes sub-domains and add their scores
	if (isset($json[$control]['includes_subdomains']) && $json[$control]['includes_subdomains'] === true) {
		if (isset($json[$control]['section_2_source'])) {
			$subdomainKey = $json[$control]['section_2_source'];
			if (isset($json[$subdomainKey])) {
				$subQnum = $json[$subdomainKey]['qnum'];
				$score += $controlTotal[$subQnum];
			}
		}
	}

	$title = $json[$control]['title'];
	array_push($nextDomain, $title);
	$rating = MaturityRating::getRating($score);
	$ratingClass = MaturityRating::getRatingClass($rating);
	// Convert cell class to header class (e.g., cellInitial -> cellHeaderInitial)
	$headerClass = str_replace('cell', 'cellHeader', $ratingClass);
    print "<h3>$title <span class='" . $headerClass . "'>". $rating . "</span></h3><div>";


    $qnum = $json[$control]['qnum'];
    $levelArray = array();

    // Find the first capability that is not fully complete (value < 3) in core domain
    $nextLevel = null;
    $nextLevelSource = 'core'; // Track whether recommendation is from core or sub-domain
    $nextLevelQnum = $qnum;

    for ($cap = 1; $cap <= 8; $cap++) {
        $controlId = "control{$qnum}-{$cap}";
        $capValue = isset($data[$controlId]) ? intval($data[$controlId]) : 0;

        if ($capValue > 0) {
            array_push($levelArray, $cap);
        }

        // Find first incomplete capability (value < 3)
        if ($nextLevel === null && $capValue < 3) {
            $nextLevel = $cap;
        }
    }

    // If core domain is complete and this domain has a sub-domain, check sub-domain capabilities
    if ($nextLevel === null && isset($json[$control]['includes_subdomains']) && $json[$control]['includes_subdomains'] === true) {
        if (isset($json[$control]['section_2_source'])) {
            $subdomainKey = $json[$control]['section_2_source'];
            if (isset($json[$subdomainKey])) {
                $subQnum = $json[$subdomainKey]['qnum'];
                $subdomainTitle = $json[$subdomainKey]['title'];

                for ($cap = 1; $cap <= 8; $cap++) {
                    $controlId = "control{$subQnum}-{$cap}";
                    $capValue = isset($data[$controlId]) ? intval($data[$controlId]) : 0;

                    if ($capValue > 0) {
                        array_push($levelArray, $cap + 8); // Offset by 8 to avoid conflicts
                    }

                    // Find first incomplete capability in sub-domain
                    if ($nextLevel === null && $capValue < 3) {
                        $nextLevel = $cap;
                        $nextLevelSource = 'subdomain';
                        $nextLevelQnum = $subQnum;
                    }
                }
            }
        }
    }

    if ($nextLevel !== null) {
        // Determine which JSON object to use based on source
        $sourceJson = $nextLevelSource === 'core' ? $json[$control] : $json[$json[$control]['section_2_source']];
        $sourceDomainName = $nextLevelSource === 'core' ? '' : ' (' . $json[$json[$control]['section_2_source']]['title'] . ')';

        ## Check if there is a recommendation for the next level
        $nextRecommendation = $nextLevel . '-recommendation';
        $nextSummary = $nextLevel . '-summary';
        print "<h4 class=title-text>Recommendation</h4>";
        print "<p>Start to work on preparing for actions concerning " . $sourceJson[$nextLevel] . "$sourceDomainName (Level $nextLevel)<p>";
        print "<br><p class=why-what>What is " . $sourceJson[$nextLevel] . " ?</p><p>" . $sourceJson[$nextSummary] . "</p>";

        if ($sourceJson[$nextRecommendation] != "") {
            print "<br>";
            print "<p>" . $sourceJson[$nextRecommendation] . "<p>";
			array_push($nextSteps,$sourceJson[$nextLevel]);
			array_push($nextStepsHow,$sourceJson[$nextSummary]);

			// Display Vendor Solution if available
			$vendorSolutionField = $nextLevel . '-vendor-solution';
			$vendorDescField = $nextLevel . '-vendor-description';
			if (!empty($sourceJson[$vendorSolutionField])) {
			    print '<div style="margin-top: 1.5rem; padding: 1.25rem; background: linear-gradient(135deg, rgba(13, 96, 248, 0.1) 0%, #2a2a2a 100%); border-left: 4px solid #0d60f8; border-radius: 6px;">';
			    print '<h4 style="color: #9ec7fc; margin-top: 0; display: flex; align-items: center; gap: 0.5rem;">';
			    print '<i class="fa-solid fa-cube"></i> Vendor Solution';
			    print '</h4>';
			    print '<div style="font-weight: 600; color: #fff; font-size: 1.1rem; margin-bottom: 0.5rem;">' .
			          Security::escape($sourceJson[$vendorSolutionField]) . '</div>';
			    print '<p style="color: #e0e0e0; margin: 0; line-height: 1.6;">' .
			          Security::escape($sourceJson[$vendorDescField]) . '</p>';
			    print '</div>';
			}
        } else {
        print "<p>You're doing great as you are!</p>";
    }
} else {
    // All capabilities in this domain are fully complete (including sub-domain if applicable)
    print "<h4 class=title-text>Excellent Work!</h4>";
    print "<p>All capabilities in this domain have reached full maturity (Fully Complete). Continue maintaining these capabilities and consider exploring advanced optimizations.</p>";
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
<td class="optimizing"><strong>Optimizing</strong></td>
<?php
MaturityRating::putDomainStatus("8",$controlDetails,$json,$controls);
?>
</tr>

<tr>
<td class="quantitative"></td>

<?php
MaturityRating::putDomainStatus("7",$controlDetails,$json,$controls);
?>
</tr>

<tr>
<td class="quantitative"><strong>Quantitatively Managed</strong></td>
<?php
MaturityRating::putDomainStatus("6",$controlDetails,$json,$controls);
?>
</tr>

<tr>
<td class="defined"></td>
<?php
MaturityRating::putDomainStatus("5",$controlDetails,$json,$controls);
?>
</tr>

<tr>
<td class="defined"><strong>Defined</strong></td>
<?php
MaturityRating::putDomainStatus("4",$controlDetails,$json,$controls);
?>
</tr>

<tr>
<td class="managed"></td>
<?php
MaturityRating::putDomainStatus("3",$controlDetails,$json,$controls);
?>
</tr>

<tr>
<td class="managed"><strong>Managed</strong></td>
<?php
MaturityRating::putDomainStatus("2",$controlDetails,$json,$controls);
?>
</tr>

<tr>
<td class="initial"><strong>Initial</strong></td>
<?php
MaturityRating::putDomainStatus("1",$controlDetails,$json,$controls);
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
						foreach ($controls as $control) {
							$title = $json[$control]['title'];
							$qnum = $json[$control]['qnum'];
							$radarScore = $controlTotal[$qnum];
							$radarMaxScore = 36;

							// Check if this domain includes sub-domains and add their scores
							if (isset($json[$control]['includes_subdomains']) && $json[$control]['includes_subdomains'] === true) {
								if (isset($json[$control]['section_2_source'])) {
									$subdomainKey = $json[$control]['section_2_source'];
									if (isset($json[$subdomainKey])) {
										$subQnum = $json[$subdomainKey]['qnum'];
										$radarScore += $controlTotal[$subQnum];
										$radarMaxScore += 36; // Add max for sub-domain
									}
								}
							}

							// Normalize all domains to 36-point scale for consistent radar chart display
							// This ensures domains with sub-pillars (72 max) are comparable to single domains (36 max)
							$normalizedScore = $radarMaxScore > 0 ? ($radarScore / $radarMaxScore) * 36 : 0;

							print '{axis:"' . $title . '",value: ' . round($normalizedScore, 1) . '},';
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
			  maxValue: 36,  // All domains normalized to 36-point scale for fair comparison
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