

<!DOCTYPE html>
<html lang="en">
   <head>
      <!-- basic -->
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <!-- mobile metas -->
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="viewport" content="initial-scale=1, maximum-scale=1">
      <!-- site metas -->
      <title>Viewfinder Detailed Report</title>
      <meta name="keywords" content="">
      <meta name="description" content="">
      <meta name="author" content="">
      <!-- bootstrap css -->
      <link rel="stylesheet" href="css/bootstrap.min.css">
      <!-- style css -->
      <link rel="stylesheet" href="css/style.css">
      <!-- Responsive-->
      <link rel="stylesheet" href="css/responsive.css">
      <!-- Scrollbar Custom CSS -->
      <link rel="stylesheet" href="css/jquery.mCustomScrollbar.min.css">

      <link rel="stylesheet" href="css/table.css">
      <link rel="stylesheet" href="css/table2.css">

      <script src="https://kit.fontawesome.com/8a8c57f9cf.js" crossorigin="anonymous"></script>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen">
      <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.6/d3.min.js" charset="utf-8"></script>

   </head>
   <!-- body -->
   <body class="main-layout">

<!--
Images:
<a href="https://www.freepik.com/search">Icon by Freepik</a>



-->

<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../error-pages/error-handler.php';
require_once __DIR__ . '/../includes/Security.php';
require_once __DIR__ . '/../includes/MaturityRating.php';
require_once __DIR__ . '/../includes/Logger.php';
require_once __DIR__ . '/../includes/Config.php';

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;

// Register error handlers
ErrorHandler::register();

try {
    Logger::info('Report page loaded', ['page' => 'report/index.php']);

    // Parse and validate input data
    parse_str($_SERVER["QUERY_STRING"] ?? '', $data);

    // Validate profile parameter
    $profile = Security::validateProfile($data['profile'] ?? $_REQUEST['profile'] ?? '');
    $data['profile'] = $profile; // Update with validated value
    Logger::info('Profile selected', ['profile' => $profile]);

    // Safely load controls JSON
    $controlsFile = Security::getControlsFilePath($profile);
    $json = Security::loadJSON($controlsFile);

    // Generate QR code for current page URL
    // Get the full current page URL with query string
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $currentPageUrl = $protocol . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    // Try to build the QR code - if URL is too long, skip QR code generation
    $qrCodeDataUri = null;
    try {
        // Build the QR code using fluent builder pattern (v5.x API)
        // Use Low error correction to maximize data capacity
        $qrCodeResult = Builder::create()
            ->writer(new PngWriter())
            ->data($currentPageUrl)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(ErrorCorrectionLevel::Low)
            ->size(300)
            ->margin(10)
            ->roundBlockSizeMode(RoundBlockSizeMode::Margin)
            ->validateResult(false)
            ->build();

        // Convert to base64 for inline display
        $qrCodeDataUri = $qrCodeResult->getDataUri();
    } catch (\Exception $qrException) {
        // URL too long for QR code - log but continue without QR code
        Logger::warning('QR code generation skipped - data too large', [
            'url_length' => strlen($currentPageUrl),
            'error' => $qrException->getMessage()
        ]);
        $qrCodeDataUri = null;
    }

} catch (ViewfinderException $e) {
    Logger::logException($e);
    throw $e; // Re-throw for error handler to display error page
} catch (\Throwable $e) {
    Logger::error('Unexpected error in report/index.php', [
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

// Collect workshop notes if any exist
$hasNotes = false;
$workshopNotes = [];
foreach ($controls as $control) {
    $qnum = $json[$control]['qnum'];
    $notesFieldName = 'domain_notes_' . $qnum;
    if (isset($data[$notesFieldName]) && !empty(trim($data[$notesFieldName]))) {
        $hasNotes = true;
        $workshopNotes[$qnum] = [
            'title' => $json[$control]['title'],
            'notes' => $data[$notesFieldName]
        ];
    }
}

// Functions moved to MaturityRating class

// ==========================================
// WEIGHTED SCORING IMPLEMENTATION
// ==========================================

// Load LOB weights
$lobWeights = require_once __DIR__ . '/../lob-weights.php';

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
$rawTotalScore = array_sum($controlTotal);

// Calculate weighted score
$weightedSum = 0;
$totalWeight = 0;
$maxPossiblePerDomain = 36; // Each domain has max 36 points (9 questions × 4 levels)

foreach ($controls as $control) {
    $title = $json[$control]['title'];
    $qnum = $json[$control]['qnum'];
    $domainScore = $controlTotal[$qnum];

    // Get weight for this domain (default 1.0 if not found)
    $weight = isset($domainWeights[$title]) ? $domainWeights[$title] : 1.0;

    // Calculate weighted contribution
    $domainPercentage = $domainScore / $maxPossiblePerDomain;
    $weightedDomainScore = $domainPercentage * $weight;

    $weightedSum += $weightedDomainScore;
    $totalWeight += $weight;
}

// Normalize weighted score to 0-252 scale (7 domains × 36 max points)
$totalScore = $totalWeight > 0 ? ($weightedSum / $totalWeight) * (count($controls) * $maxPossiblePerDomain) : 0;


?>

      <!-- header -->
      <header>
         <!-- header inner -->
            <div class="header">
               <div class="container-fluid">
                  <div class="row">
                        <div class="full">
                           <div class="center-desk">
                              <div class="logo" style="text-align: center; margin-bottom: 30px;">
                                 <img src="../images/Logo-Red_Hat-C-Standard-RGB.svg" alt="Red Hat" style="height: 80px;">
                              </div>
                        <div class="text-bg">
                           <h1><?php
                           // Dynamically get the profile display name from Config
                           $profileDisplayName = Config::getProfileDisplayName($profile);
                           $assessment = Security::escape($profileDisplayName) . " Maturity Assessment";
                           print $assessment;
                              ?> </h1>

</div>
                              
                           </div>
                     </div>


                  </div>
               </div>
            </div>
            <!-- end header inner -->
            <!-- end header -->
            <!-- banner -->
            <section class="banner_main">
               <div class="container-fluid">
                     <div class="col-md-4">


                           <?php if ($profile == "Security") {
                              print '
                           <h3> How does this radar chart help me?</h3>
                           <ul>
                              <li><b>Identify Weaknesses</b> Pinpoint vulnerabilities and weaknesses in your security infrastructure.</li>
                              <li><b>Help with Risk Mitigation</b> Understand potential risks and how to mitigate them effectively.</li>
                              <li><b>Resource Optimisation</b> Allocate resources more effectively by focusing on areas that need improvement the most.</li>
                           </ul>';
                           }
                           ?>
                        </div>
                     </div>

                        <div class="text-img">
                           
                           <figure>
                           <div class="radarChart"></div>
                           </figure>
                        </div>
</div>
<div class="overviewBlock">
<?php if ($profile == "DigitalSovereignty") {
                              print '   
<h1>Digital Sovereignty Maturity Assessment: Introduction</h1>
    <p>Digital sovereignty is a complex strategic imperative that seeks to maximize an organization\'s <strong>control over its own digital destiny</strong>—its data, technology, and operations. This maturity assessment provides a clear, actionable framework for evaluating your current level of control and independence. Understanding the trade-offs between global efficiency and self-determination is critical for charting a successful digital future.</p>

    <h2>Benefits of Digital Sovereignty</h2>

    <p>The key advantages center on reducing systemic risk and fostering strategic independence. By achieving digital sovereignty, an organization gains <strong>greater control over data residency and access</strong>, ensuring sensitive information is stored, processed, and governed under its preferred legal jurisdiction. This is essential for meeting stringent national and sectoral compliance requirements (e.g., in healthcare or finance) and protecting data from foreign surveillance laws. Furthermore, a sovereign approach improves <strong>operational resilience and business continuity</strong>. By lessening dependence on a small number of foreign hyper-scalers or proprietary technology stacks, the organization mitigates the risk of vendor lock-in, supply chain disruptions, or service outages caused by geopolitical conflict or sanctions. This autonomy supports long-term <strong>cost control and flexibility</strong>, allowing the organization to tailor its IT architecture to its unique mission rather than being confined by a provider\'s global roadmap.</p>

    <h2>Challenges of Digital Sovereignty</h2>

    <p>The path to sovereignty is not without friction. One of the main challenges is the <strong>increase in complexity and potential cost</strong>. Achieving true independence often means moving away from the convenience and scale of global cloud platforms, which can lead to higher operational expenses, particularly for smaller organizations. Building or acquiring sovereign-compliant alternatives—such as local cloud infrastructure, bespoke software, or open-source solutions—requires significant upfront investment and specialized technical talent. Another major hurdle is the risk of <strong>digital fragmentation</strong>. As an organization prioritizes internal or local solutions, it may lose out on the latest global innovations or sacrifice the seamless interoperability required to participate in international digital supply chains, creating an "innovation gap." Finally, managing the <b>evolving and non-uniform regulatory landscape</b> across different jurisdictions adds a layer of complexity to compliance that requires constant monitoring and adaptation.</p>
';
                           }
                           ?>
                           </div>
                     </div>
            </section>
         </div>
      </header>
      <div class="pagebreak"> </div>
      <!-- end banner -->
      <!-- business -->
      <div class="business">
                                       <?php if ($profile == "DigitalSovereignty") {
                              // Generate dynamic executive summary based on assessment results
                              $totalMaturityScore = array_sum($controlTotal);
                              $maxPossible = 252; // 7 domains × 36 points each
                              $overallPercentage = round(($totalMaturityScore / $maxPossible) * 100);
                              $overallRating = MaturityRating::getTotalRating($totalMaturityScore);

                              // Get LOB for context
                              $assessmentLob = $selectedLob ?? "General";

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

                              ?>
                              <div class="overviewBlock">
                              <div class="titlepage">
                              <span>Executive Summary</span>
                              </div>

                              <div style="background: #f9f9f9; border-left: 4px solid #0d60f8; padding: 1.5rem; margin-bottom: 2rem; border-radius: 4px;">
                                  <h3 style="color: #0d60f8; margin-top: 0;">Overall Maturity Assessment</h3>
                                  <table style="width: 100%; border-collapse: collapse;">
                                      <tr>
                                          <td style="padding: 0.5rem; font-weight: 600; width: 200px;">Overall Maturity Level:</td>
                                          <td style="padding: 0.5rem;"><strong><?php echo Security::escape($overallRating); ?></strong> (<?php echo $overallPercentage; ?>%)</td>
                                      </tr>
                                      <tr style="background: #f5f5f5;">
                                          <td style="padding: 0.5rem; font-weight: 600;">Industry Context:</td>
                                          <td style="padding: 0.5rem;"><?php echo Security::escape($assessmentLob); ?></td>
                                      </tr>
                                      <tr>
                                          <td style="padding: 0.5rem; font-weight: 600;">Total Score:</td>
                                          <td style="padding: 0.5rem;"><?php echo ceil($totalMaturityScore); ?> / <?php echo $maxPossible; ?> points</td>
                                      </tr>
                                      <tr style="background: #f5f5f5;">
                                          <td style="padding: 0.5rem; font-weight: 600;">Assessment Date:</td>
                                          <td style="padding: 0.5rem;"><?php echo date("F j, Y"); ?></td>
                                      </tr>
                                  </table>
                              </div>

                              <div class="section-header">
                                  <h3><i class="fa-solid fa-chart-line"></i> Key Strengths</h3>
                                  <p>The assessment identified the following areas of strong maturity:</p>
                                  <?php
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

                                  foreach ($strengths as $strength):
                                      $quickWin = isset($quickWins[$strength["title"]]) ? $quickWins[$strength["title"]] : 'Continue to refine and optimize processes, and consider sharing best practices with other domains.';
                                  ?>
                                      <li style="margin-bottom: 1.5rem;">
                                          <strong><?php echo Security::escape($strength["title"]); ?></strong>: <?php echo Security::escape($strength["rating"]); ?> level (<?php echo $strength["percentage"]; ?>%)<?php if (isset($strength["maturityLevel"])): ?> • <span style="color: #2aaa04;"><?php echo Security::escape($strength["maturityLevel"]); ?></span><?php endif; ?> - demonstrating well-established capabilities in this domain.
                                          <div style="margin-top: 0.5rem; padding-left: 1.5rem; color: #0d60f8;">
                                              <strong>Quick Win:</strong> <?php echo Security::escape($quickWin); ?>
                                          </div>
                                      </li>
                                  <?php endforeach; ?>
                              </div>

                              <div class="section-header">
                                  <h3><i class="fa-solid fa-exclamation-triangle"></i> Critical Gaps</h3>
                                  <p>Priority areas requiring immediate attention
                                  <?php if ($assessmentLob !== "General"): ?>
                                  (based on <?php echo Security::escape($assessmentLob); ?> industry priorities)
                                  <?php endif; ?>:</p>
                                  <?php
                                  // Domain-specific business impacts and first steps
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

                                  foreach ($gaps as $gap):
                                      $priorityNote = "";
                                      if ($gap["weight"] >= 1.5) {
                                          $priorityNote = " <strong>(High Priority for " . Security::escape($assessmentLob) . ")</strong>";
                                      }

                                      $impact = isset($businessImpacts[$gap["title"]]) ? $businessImpacts[$gap["title"]] : 'Reduces overall sovereignty maturity and organizational resilience.';
                                      $steps = isset($firstSteps[$gap["title"]]) ? $firstSteps[$gap["title"]] : ['Review domain-specific recommendations in detailed assessment.'];
                                  ?>
                                      <li style="margin-bottom: 2rem;">
                                          <strong><?php echo Security::escape($gap["title"]); ?></strong>: <?php echo Security::escape($gap["rating"]); ?> level (<?php echo $gap["percentage"]; ?>%)<?php if (isset($gap["maturityLevel"])): ?> • <span style="color: #f0ab00;"><?php echo Security::escape($gap["maturityLevel"]); ?></span><?php endif; ?><?php echo $priorityNote; ?>

                                          <div style="margin-top: 0.75rem; padding: 1rem; background: #fff3cd; border-left: 3px solid #ffc107; border-radius: 4px;">
                                              <div style="margin-bottom: 0.75rem;">
                                                  <strong style="color: #856404;"><i class="fa-solid fa-exclamation-circle"></i> Business Impact:</strong>
                                                  <div style="margin-top: 0.25rem; color: #333;"><?php echo Security::escape($impact); ?></div>
                                              </div>

                                              <div>
                                                  <strong style="color: #0d60f8;"><i class="fa-solid fa-list-check"></i> First Steps:</strong>
                                                  <ol style="margin: 0.5rem 0 0 1.5rem; padding: 0; color: #333;">
                                                      <?php foreach ($steps as $step): ?>
                                                          <li style="margin-bottom: 0.25rem;"><?php echo Security::escape($step); ?></li>
                                                      <?php endforeach; ?>
                                                  </ol>
                                              </div>
                                          </div>
                                      </li>
                                  <?php endforeach; ?>
                              </div>

                              <div class="section-header" style="page-break-before: always;">
                                  <h3><i class="fa-solid fa-chart-pie"></i> Capability Status</h3>
                                  <p>Distribution of all capabilities across maturity levels:</p>

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
                                  ?>

                                  <!-- Pie Chart -->
                                  <div style="text-align: center; background: #f9f9f9; padding: 2rem; border-radius: 8px; margin: 1.5rem 0;">
                                      <h4 style="color: #0d60f8; margin-bottom: 1.5rem;">Capability Status Distribution</h4>
                                      <div id="statusPieChart"></div>
                                  </div>

                                  <script type="text/javascript">
                                  var statusChartData = [
                                      {"label": "Fully Complete", "value": <?php echo $statusCounts['3']; ?>, "color": "#2aaa04"},
                                      {"label": "Work in Progress", "value": <?php echo $statusCounts['2']; ?>, "color": "#ec7a08"},
                                      {"label": "In Planning", "value": <?php echo $statusCounts['1']; ?>, "color": "#f0ab00"},
                                      {"label": "No Capability", "value": <?php echo $statusCounts['0']; ?>, "color": "#6a6e73"}
                                  ];
                                  </script>

                                  <?php
                                  // Display each status group
                                  foreach ($statusGroups as $level => $group) {
                                      $totalCount = $statusCounts[$level];
                                      $percentage = round(($totalCount / 56) * 100);

                                      if ($totalCount > 0) {
                                          echo '<div style="margin-bottom: 1.5rem; padding: 1rem; background: #f9f9f9; border-left: 4px solid ' . $group['color'] . '; border-radius: 4px;">';
                                          echo '<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">';
                                          echo '<strong style="color: #333; font-size: 1.1rem;"><i class="fa-solid fa-' . $group['icon'] . '" style="color: ' . $group['color'] . ';"></i> ' . $group['label'] . '</strong>';
                                          echo '<span style="color: ' . $group['color'] . '; font-weight: 600;">' . $totalCount . ' / 56 (' . $percentage . '%)</span>';
                                          echo '</div>';

                                          foreach ($group['domains'] as $domainName => $capabilities) {
                                              echo '<div style="margin-bottom: 0.5rem;">';
                                              echo '<strong style="color: #0d60f8;">' . Security::escape($domainName) . ':</strong> ';
                                              echo implode(', ', array_map(function($cap) { return Security::escape($cap); }, $capabilities));
                                              echo '</div>';
                                          }
                                          echo '</div>';
                                      }
                                  }
                                  ?>
                              </div>

                              <div class="section-header">
                                  <h3><i class="fa-solid fa-route"></i> Strategic Recommendations</h3>
                                  <p class="imperative">To advance digital sovereignty maturity, executive leadership should:</p>
                                  <ul class="action-list">
                                      <li><strong>Prioritize foundational capabilities</strong> in the identified gap areas before pursuing advanced maturity levels</li>
                                      <li><strong>Align investment decisions</strong> with <?php echo Security::escape($assessmentLob); ?> industry requirements and regulatory obligations</li>
                                      <li><strong>Establish executive sponsorship</strong> for sovereignty initiatives with dedicated budget allocation</li>
                                      <li><strong>Leverage existing strengths</strong> in <?php echo Security::escape($strengths[0]["title"]); ?> to build momentum and demonstrate value</li>
                                      <li><strong>Review detailed domain analysis</strong> (following pages) for specific technical and operational recommendations</li>
                                  </ul>

                                  <div class="result">The detailed assessment report that follows provides actionable recommendations for each domain, prioritized by maturity gap and industry relevance.</div>
                              </div>
                              </div>
                              <?php
                           }
                           ?>
            <div class="row">
               <div class="col-md-12">
                  <div class="titlepage">
                     <span>Maturity Levels</span>
                     <h3>As of <?php print date('l jS \of F Y'); ?> </h3>
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-12">
                  <div class="row">
                     <div class="col-md-12">
                        <div class="titlepage">
                           <table class="spacedTable">
	<thead>
		<tr>
			<th>Control</th>
			<th>Rating</th>
			</tr>
		</tr>
</thead>


<?php
// Use the weighted totalScore calculated earlier
$displayTotalScore = ceil($totalScore);

## Work out all the stuff for the table
foreach ($controls as $control) {
	print "<tr>";
	$title = $json[$control]['title'];
	$qnum = $json[$control]['qnum'];
	$score = $controlTotal[$qnum];

	#print "<td><i class='fa-regular fa-" . $qnum . "'>&nbsp; &nbsp; </i>" . $title . "</td>";
	print "<td>" . $title . "</td>";
	$rating = MaturityRating::getRating($score);
	$ratingClass = MaturityRating::getRatingClass($rating);
	$displayScore = ceil($score);
	print "<td class='" . $ratingClass . "'>" . $rating . " ($displayScore out of 36)</td>";
	print "</tr>";
}
print '</table>';
$overallRating = MaturityRating::getTotalRating($displayTotalScore);
$overallRatingClass = MaturityRating::getRatingClass($overallRating);
print "<br><table><td class='" . $overallRatingClass . "'>Overall rating: " . $overallRating . " (" . $displayTotalScore . " weighted out of 252)</td></tr></table>";

// Display 5-Level Maturity Model visualization (HTML/CSS)
require_once __DIR__ . '/includes/maturity-model-visual.php';
?>
                        </div>
                     </div>
                  </div>
               </div>
               <?php
  // Functions moved to MaturityRating class

  $controlDetail = array_fill(1,8,0);
  $controlDetails = array_fill(1,8,$controlDetail);
  
  foreach($data as $field=>$value){
	  if (strpos($field,"control") !== false){
	  $controlNumber = substr($field,7,1);
	  $controlDetails[$controlNumber][$value] = 1;
  }
  }
?>

<?php
// putDomainStatus function moved to MaturityRating class
?>

<div class="bigtable">
<div class="titlepage">
<div class="pagebreak"> </div>


                           <div class="titlepage">
<span>Current Status</span>
                        </div>
<table><thead><tr>
<th class="table-header">Rating</th>


<?php
foreach ($controls as $control) {
	$title = $json[$control]['title'];
print '<th class="table-header">' . $title .'</th>';
}

?>

</tr></thead>
<tr>
<td class="optimizing"></td>
<?php
MaturityRating::putDomainStatus("8",$controlDetails,$json);
?>
</tr>

<tr>
<td class="optimizing">Optimizing</td>

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
<td class="quantitative"></td>
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
<td class="defined"></td>

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

<hr>

</div>
         </div>
      </div>
      
      <!-- end business -->
      <!-- Projects -->
   <div class="pagebreak"> </div>;

      <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <div class="titlepageLeft">

                  <!-- Start of recomendations -->

                  <?php
if (isset($_REQUEST['lob'])) {
    // Validate LOB parameter
    $lob = Security::validateLOB($_REQUEST['lob']);

    if ($lob !== null && $lob !== 'Other') {
        print "<br><h2>General Advice for " . Security::escape($lob) . " Industries</h2>";

        // Safely get LOB file path
        $safeFilePath = Security::getLOBFilePath($lob, $profile);

        if ($safeFilePath !== null) {
            include $safeFilePath;
            print "<img class=smallImage src=images/info.jpg>";
        } else {
            print '<p>No specific information available for this industry.</p>';
        }
    }
}
?>

<h1 style="margin-top: 2rem;">Domain Analysis & Recommendations</h1>

<?php
foreach ($controls as $control) {
    $highest=0;	
    $qnum = $json[$control]['qnum'];
	$score = $controlTotal[$qnum];
	$title = $json[$control]['title'];
	array_push($nextDomain, $title);
   #print '<div class="pagebreak"> </div>';
	$rating = MaturityRating::getRating($score);
	$ratingClass = MaturityRating::getRatingClass($rating);
	// Convert cell class to header class (e.g., cellInitial -> cellHeaderInitial)
	$headerClass = str_replace('cell', 'cellHeader', $ratingClass);
    print "<br><h2>$title - <span class='" . $headerClass . "'>". $rating . " Level</span></h2><div>";

    
    $qnum = $json[$control]['qnum'];
    $levelArray = array();
    ## Get the highest score per capability & keep the results
    foreach ($data as $key => $value) {
    if (preg_match("/^control$qnum-[0-9]*/", $key)) {
        array_push($levelArray, substr($key, -1));
        $highest++;
          }
    }
    $nextLevel = $highest + 1;
    if ($nextLevel < 9) {
        ## Check if there is a recommendation for the next level
        $nextRecommendation = $nextLevel . '-recommendation';
        $nextSummary = $nextLevel . '-summary';
        #print "<h4 class=title-text>Recommendation</h4>"; 
        print "<p>Start to work on preparing for actions concerning " . $json[$control][$nextLevel] . " (Level $nextLevel)<p>";
        print "<br><p class=why-what>Definition of " . $json[$control][$nextLevel] . " </p><p>" . $json[$control][$nextSummary] . "</p>";

        if ($json[$control][$nextRecommendation] != "") {
            print "<br>";
            print "<p>" . $json[$control][$nextRecommendation] . "<p>";
			array_push($nextSteps,$json[$control][$nextLevel]);
			array_push($nextStepsHow,$json[$control][$nextSummary]);
        } else {
        print "<p>You're doing great as you are!</p>";
    }
   }

   // Display workshop notes for this domain if they exist
   $notesFieldName = 'domain_notes_' . $qnum;
   if (isset($data[$notesFieldName]) && !empty(trim($data[$notesFieldName]))) {
       $notes = Security::escape($data[$notesFieldName]);
       print '<div style="margin-top: 2rem; padding: 1.5rem; background: #f5f5f5; border-left: 4px solid #0d60f8; border-radius: 4px; border: 1px solid #ddd;">';
       print '<h3 style="color: #0d60f8; margin-top: 0; margin-bottom: 1rem;"><i class="fa-solid fa-note-sticky"></i> Workshop Notes</h3>';
       print '<div style="white-space: pre-wrap; color: #333; line-height: 1.6; font-size: 1rem;">' . $notes . '</div>';
       print '</div>';
   }


## Check for any gaps
#if ($levelArray) {
#	#print "Max: " . max($levelArray) . "<br>";
#	$allLevels = range(1,max($levelArray));
#	$missing = array_diff($allLevels,$levelArray);
#	if ($missing) {
#		print "<br><br><h4 class=why-what>Skipped Level(s)</h4>";
#		foreach ($missing as $notthere) {
#			$skippedRecommendation = $notthere . '-recommendation';
#			print "<p class=why-what>Level $notthere </p>";
#			if ($json[$control][$skippedRecommendation] != "") {
#			print $json[$control][$skippedRecommendation] . ". ";
#			} else {
#                $notthereComment = $notthere . "-summary";
##				print_r($json[$control][$notthere]);
#                print $json[$control][$notthereComment];
#			}
#			print "<br>";
#		}
#	}
#	}
   $randomImage = rand(1, 9);
   print "<img class=smallImage src=images/tech-image-" . $randomImage . ".png>";
    print "</div>";

}
?>

                  <!-- End of recommendations -->





                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-10 offset-md-1">
                  <div class="row">
 
                  </div>
               </div>
            </div>
         </div>
      <!-- end projects -->
      <!-- Testimonial -->
      <div class="section">
         <div class="container">
            <div id="" class="Testimonial">
               <div class="row">
                  <div class="col-md-12">
                     <div class="titlepage">
                        <h2>Need more information ?</h2>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-3">
                     <div class="Testimonial_box">
                     </div>
                  </div>
                  <div class="col-md-9">
                     <div class="Testimonial_box">
                        <p>Don't wait until it's too late. Take proactive steps and empower yourselves with Project Viewfinder and enable proactive digital sovereignty within your customer's organisation with the Viewfinder Maturity Assessment. Contact your Red Hat account team for more information and take the first step towards a more autonomous future.
                        </p>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <!-- end Testimonial -->

      <!-- QR Code Section -->
      <div class="section">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <div class="titlepage">
                     <h2>Share This Report</h2>
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-12">
                  <div class="Testimonial_box" style="text-align: center;">
                     <?php if ($qrCodeDataUri !== null): ?>
                     <div style="display: flex; justify-content: center; margin: 20px 0;">
                        <img src="<?php echo $qrCodeDataUri; ?>" alt="QR Code for Report Page" style="border: 2px solid #ccc; padding: 10px; background: white; display: block;" />
                     </div>
                     <?php else: ?>
                     <div style="padding: 20px; background: #fff3cd; border: 1px solid #ffc107; border-radius: 4px; margin: 20px auto; max-width: 600px;">
                        <p style="margin: 0; color: #856404;"><i class="fa-solid fa-info-circle"></i> <strong>Note:</strong> QR code not available - assessment data is too large to encode. Please bookmark or share the URL directly.</p>
                     </div>
                     <?php endif; ?>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- end QR Code Section -->
      
      <!--  footer -->
      <footer>
         <div class="footer">
            <div class="container">
               <div class="row">

               </div>
            </div>
            <div class="copyright">
               <div class="container">
                  <div class="row">
                     <div class="col-md-12">
                        <p>Copyright 2026 All Right Reserved </p>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </footer>
      <!-- end footer -->
      <!-- Javascript files-->
      <script src="js/jquery.min.js"></script>
      <script src="js/popper.min.js"></script>
      <script src="js/bootstrap.bundle.min.js"></script>
      <script src="js/jquery-3.0.0.min.js"></script>
      <script src="js/plugin.js"></script>
      <!-- sidebar -->
      <script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
      <script src="js/custom.js"></script>
      <script src="https:cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js"></script>

      <script src="js/radarChart.js"></script>	
		<script>
      
      /* Radar chart design created by Nadieh Bremer - VisualCinnamon.com */
      
			////////////////////////////////////////////////////////////// 
			//////////////////////// Set-Up ////////////////////////////// 
			////////////////////////////////////////////////////////////// 

			var margin = {top: 100, right: 100, bottom: 100, left: 100},
				width = Math.min(700, window.innerWidth - 10) - margin.left - margin.right,
				height = Math.min(width, window.innerHeight - margin.top - margin.bottom - 20);
					
			////////////////////////////////////////////////////////////// 
			////////////////////////// Data ////////////////////////////// 
			////////////////////////////////////////////////////////////// 

/*			var data = [
					  [
						{axis:"Secure Infrastructure",value:<?php echo $controlTotal[1]; ?>},
						{axis:"Secure Data",value:<?php echo $controlTotal[2]; ?>},
						{axis:"Secure Identity",value:<?php echo $controlTotal[3]; ?>},
						{axis:"Secure Application",value:<?php echo $controlTotal[4]; ?>},
						{axis:"Secure Network",value:<?php echo $controlTotal[5]; ?>},
						{axis:"Secure Recovery",value:<?php echo $controlTotal[6]; ?>},
						{axis:"Secure Operations",value:<?php echo $controlTotal[7]; ?>}
					  ]
					]; */
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
				.range(["#CC333F","#CC333F","#00A0B0"]);
				
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

			    arcs.append("path")
			        .attr("d", arc)
			        .style("fill", function(d) { return d.data.color; })
			        .style("stroke", "#fff")
			        .style("stroke-width", "2px");

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
			            .style("color", "#333")
			            .style("font-size", "0.9rem")
			            .text(d.label + ": " + d.value + " (" + Math.round((d.value / 56) * 100) + "%)");
			    });
			})();
</script>
   </body>
</html>

