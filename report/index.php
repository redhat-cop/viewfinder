

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
      <title>Detailed Assessment Report</title>
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
// Build controls array - filter out sub-domains from main display
// Sub-domains (Domain-5, Domain-7) are integrated into their parent domains
foreach($json as $key => $value) {
	// Only include domains that should display in main navigation/reports
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
    $domainPercentage = $domainScore / $domainMaxPoints;
    $weightedDomainScore = $domainPercentage * $weight;

    $weightedSum += $weightedDomainScore;
    $totalWeight += $weight;
    $totalMaxPoints += $domainMaxPoints;
}

// Normalize weighted score to 0-252 scale (total max points across all domains including sub-domains)
$totalScore = $totalWeight > 0 ? ($weightedSum / $totalWeight) * $totalMaxPoints : 0;


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
                                 <!-- Vendor logo can be inserted here -->
                                 <!-- Example: <img src="../images/your-logo.svg" alt="Your Company" style="height: 80px;"> -->
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
                                       <?php
                              // Generate dynamic executive summary based on assessment results (all profiles)
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
                                  } else {
                                      $quickWins = [
                                          'Data Sovereignty' => 'Implement automated data flow monitoring and quarterly audits of vendor data access to advance toward Level 4 quantitative management.',
                                          'Technical Sovereignty' => 'Document exit strategies for all critical systems and conduct annual portability drills to strengthen vendor independence.',
                                          'Operational Sovereignty' => 'Establish a Center of Excellence for sovereign technologies and implement quarterly DR testing scenarios including geopolitical isolation.',
                                          'Assurance Sovereignty' => 'Expand continuous security validation with automated compliance reporting and establish formal vendor transparency requirements in all contracts.',
                                          'Open Source' => 'Formalize contribution policies and establish metrics tracking for community engagement and project influence.',
                                          'Executive Oversight' => 'Implement sovereignty KPI dashboards for Board reporting and establish quarterly reviews with regulatory authorities.',
                                          'Managed Services' => 'Develop comprehensive transition playbooks for all critical managed services and conduct annual vendor alternative assessments.'
                                      ];
                                  }

                                  if (empty($strengths)) {
                                      echo '<p style="color: #999; font-style: italic;">No strength data available. This may indicate all domains are at similar maturity levels, or no assessment data was provided.</p>';
                                  } else {
                                      foreach ($strengths as $strength):
                                          $quickWin = isset($quickWins[$strength["title"]]) ? $quickWins[$strength["title"]] : 'Continue to refine and optimize processes, and consider sharing best practices with other domains.';
                                      ?>
                                          <li style="margin-bottom: 1.5rem;">
                                              <strong><?php echo Security::escape($strength["title"]); ?></strong>: <?php echo Security::escape($strength["rating"]); ?> level (<?php echo $strength["percentage"]; ?>%)<?php if (isset($strength["maturityLevel"])): ?> • <span style="color: #2aaa04;"><?php echo Security::escape($strength["maturityLevel"]); ?></span><?php endif; ?> - demonstrating well-established capabilities in this domain.
                                              <div style="margin-top: 0.5rem; padding-left: 1.5rem; color: #0d60f8;">
                                                  <strong>Quick Win:</strong> <?php echo Security::escape($quickWin); ?>
                                              </div>
                                          </li>
                                      <?php endforeach;
                                  }
                                  ?>
                              </div>

                              <div class="section-header">
                                  <h3><i class="fa-solid fa-exclamation-triangle"></i> Critical Gaps</h3>
                                  <p>Priority areas requiring immediate attention
                                  <?php if ($assessmentLob !== "General"): ?>
                                  (based on <?php echo Security::escape($assessmentLob); ?> industry priorities)
                                  <?php endif; ?>:</p>
                                  <?php
                                  // Domain-specific business impacts and first steps (profile-aware)
                                  if ($profile === 'Security') {
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

                                  if (empty($gaps)) {
                                      echo '<p style="color: #999; font-style: italic;">No gap data available. This may indicate all domains are at similar maturity levels, or no assessment data was provided.</p>';
                                  } else {
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
                                      <?php endforeach;
                                  }
                                  ?>
                              </div>

                              <div class="section-header" style="page-break-before: always;">
                                  <h3><i class="fa-solid fa-chart-pie"></i> Capability Status</h3>
                                  <p>Distribution of all capabilities across maturity levels:</p>

                                  <?php
                                  if (empty($controls)) {
                                      echo '<p style="color: #999; font-style: italic;">No status data available. No assessment data was provided.</p>';
                                  } else {
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
                                  }
                                  ?>
                              </div>

                              <div class="section-header" style="page-break-before: always;">
                                  <h3><i class="fa-solid fa-grip"></i> Thematic Analysis</h3>
                                  <p>Cross-domain analysis organizing capabilities by strategic theme to identify organizational patterns:</p>

                                  <?php
                                  // Define thematic groupings (profile-aware)
                                  if ($profile === 'Security') {
                                      $thematicGroups = [
                                          'Configuration & Compliance' => [
                                              'icon' => 'cog',
                                              'color' => '#0d60f8',
                                              'overview' => 'Establishing and maintaining consistent security configurations through automated policy enforcement and continuous compliance monitoring.',
                                              'capabilities' => [
                                                  ['domain' => 'Secure Infrastructure', 'capability' => 1],
                                                  ['domain' => 'Secure Infrastructure', 'capability' => 4],
                                                  ['domain' => 'Secure Data', 'capability' => 6],
                                                  ['domain' => 'Secure Application', 'capability' => 3],
                                                  ['domain' => 'Secure Recovery', 'capability' => 5],
                                              ]
                                          ],
                                          'Data Protection' => [
                                              'icon' => 'shield-halved',
                                              'color' => '#2aaa04',
                                              'overview' => 'Comprehensive data protection throughout its lifecycle including classification, encryption, loss prevention, and immutable storage.',
                                              'capabilities' => [
                                                  ['domain' => 'Secure Data', 'capability' => 1],
                                                  ['domain' => 'Secure Data', 'capability' => 2],
                                                  ['domain' => 'Secure Data', 'capability' => 3],
                                                  ['domain' => 'Secure Data', 'capability' => 4],
                                                  ['domain' => 'Secure Data', 'capability' => 5],
                                                  ['domain' => 'Secure Data', 'capability' => 7],
                                                  ['domain' => 'Secure Infrastructure', 'capability' => 5],
                                                  ['domain' => 'Secure Recovery', 'capability' => 7],
                                              ]
                                          ],
                                          'Identity & Access Management' => [
                                              'icon' => 'user-shield',
                                              'color' => '#ec7a08',
                                              'overview' => 'Authentication, authorization, and privileged account management from basic passwords to advanced risk-based controls.',
                                              'capabilities' => [
                                                  ['domain' => 'Secure Identity', 'capability' => 1],
                                                  ['domain' => 'Secure Identity', 'capability' => 2],
                                                  ['domain' => 'Secure Identity', 'capability' => 3],
                                                  ['domain' => 'Secure Identity', 'capability' => 4],
                                                  ['domain' => 'Secure Identity', 'capability' => 5],
                                                  ['domain' => 'Secure Identity', 'capability' => 6],
                                                  ['domain' => 'Secure Identity', 'capability' => 8],
                                              ]
                                          ],
                                          'Application Security' => [
                                              'icon' => 'code',
                                              'color' => '#12bbd4',
                                              'overview' => 'Security throughout the software development lifecycle from dependency management to runtime protection.',
                                              'capabilities' => [
                                                  ['domain' => 'Secure Application', 'capability' => 1],
                                                  ['domain' => 'Secure Application', 'capability' => 2],
                                                  ['domain' => 'Secure Application', 'capability' => 4],
                                                  ['domain' => 'Secure Application', 'capability' => 5],
                                                  ['domain' => 'Secure Application', 'capability' => 6],
                                                  ['domain' => 'Secure Application', 'capability' => 7],
                                                  ['domain' => 'Secure Application', 'capability' => 8],
                                                  ['domain' => 'Secure Infrastructure', 'capability' => 6],
                                              ]
                                          ],
                                          'Network Security' => [
                                              'icon' => 'network-wired',
                                              'color' => '#f0ab00',
                                              'overview' => 'Securing network communications through segmentation, encryption, and zero-trust architectures.',
                                              'capabilities' => [
                                                  ['domain' => 'Secure Network', 'capability' => 1],
                                                  ['domain' => 'Secure Network', 'capability' => 2],
                                                  ['domain' => 'Secure Network', 'capability' => 3],
                                                  ['domain' => 'Secure Network', 'capability' => 4],
                                                  ['domain' => 'Secure Network', 'capability' => 5],
                                                  ['domain' => 'Secure Network', 'capability' => 6],
                                                  ['domain' => 'Secure Network', 'capability' => 7],
                                                  ['domain' => 'Secure Network', 'capability' => 8],
                                                  ['domain' => 'Secure Infrastructure', 'capability' => 2],
                                                  ['domain' => 'Secure Infrastructure', 'capability' => 7],
                                                  ['domain' => 'Secure Infrastructure', 'capability' => 8],
                                              ]
                                          ],
                                          'Detection & Monitoring' => [
                                              'icon' => 'binoculars',
                                              'color' => '#c9190b',
                                              'overview' => 'Continuous threat detection through monitoring, log analysis, and AI/ML-based anomaly detection.',
                                              'capabilities' => [
                                                  ['domain' => 'Secure Infrastructure', 'capability' => 3],
                                                  ['domain' => 'Secure Operations', 'capability' => 3],
                                                  ['domain' => 'Secure Operations', 'capability' => 4],
                                                  ['domain' => 'Secure Operations', 'capability' => 6],
                                                  ['domain' => 'Secure Data', 'capability' => 8],
                                                  ['domain' => 'Secure Identity', 'capability' => 7],
                                                  ['domain' => 'Secure Recovery', 'capability' => 6],
                                                  ['domain' => 'Secure Operations', 'capability' => 2],
                                              ]
                                          ],
                                          'Incident Response & Recovery' => [
                                              'icon' => 'life-ring',
                                              'color' => '#a18fff',
                                              'overview' => 'Organizational resilience through incident response planning, disaster recovery, and SOAR capabilities.',
                                              'capabilities' => [
                                                  ['domain' => 'Secure Operations', 'capability' => 1],
                                                  ['domain' => 'Secure Operations', 'capability' => 5],
                                                  ['domain' => 'Secure Recovery', 'capability' => 1],
                                                  ['domain' => 'Secure Recovery', 'capability' => 2],
                                                  ['domain' => 'Secure Recovery', 'capability' => 3],
                                                  ['domain' => 'Secure Recovery', 'capability' => 4],
                                                  ['domain' => 'Secure Recovery', 'capability' => 8],
                                              ]
                                          ],
                                          'Advanced Threat Defense' => [
                                              'icon' => 'shield-virus',
                                              'color' => '#7d1007',
                                              'overview' => 'Defending against sophisticated adversaries through APT detection and purple team exercises.',
                                              'capabilities' => [
                                                  ['domain' => 'Secure Operations', 'capability' => 7],
                                                  ['domain' => 'Secure Operations', 'capability' => 8],
                                              ]
                                          ],
                                      ];
                                  } else {
                                      $thematicGroups = [
                                          'Governance & Policy' => [
                                              'icon' => 'gavel',
                                              'color' => '#0d60f8',
                                              'overview' => 'Formal governance structures, policy frameworks, and strategic integration of Digital Sovereignty principles including executive accountability and legal controls.',
                                              'capabilities' => [
                                                  ['domain' => 'Data Sovereignty', 'capability' => 4],
                                                  ['domain' => 'Data Sovereignty', 'capability' => 8],
                                                  ['domain' => 'Open Source', 'capability' => 1],
                                                  ['domain' => 'Executive Oversight', 'capability' => 1],
                                                  ['domain' => 'Executive Oversight', 'capability' => 2],
                                                  ['domain' => 'Executive Oversight', 'capability' => 3],
                                                  ['domain' => 'Executive Oversight', 'capability' => 4],
                                                  ['domain' => 'Executive Oversight', 'capability' => 7],
                                              ]
                                          ],
                                          'Data & Privacy' => [
                                              'icon' => 'shield-halved',
                                              'color' => '#2aaa04',
                                              'overview' => 'Comprehensive data protection across its lifecycle including classification, residency, encryption, and privacy compliance.',
                                              'capabilities' => [
                                                  ['domain' => 'Data Sovereignty', 'capability' => 1],
                                                  ['domain' => 'Data Sovereignty', 'capability' => 2],
                                                  ['domain' => 'Data Sovereignty', 'capability' => 3],
                                                  ['domain' => 'Data Sovereignty', 'capability' => 5],
                                                  ['domain' => 'Data Sovereignty', 'capability' => 6],
                                                  ['domain' => 'Assurance Sovereignty', 'capability' => 2],
                                              ]
                                          ],
                                          'Risk & Compliance' => [
                                              'icon' => 'clipboard-check',
                                              'color' => '#ec7a08',
                                              'overview' => 'Independent verification through audits, certifications, and continuous validation with formal risk management frameworks.',
                                              'capabilities' => [
                                                  ['domain' => 'Assurance Sovereignty', 'capability' => 1],
                                                  ['domain' => 'Assurance Sovereignty', 'capability' => 3],
                                                  ['domain' => 'Assurance Sovereignty', 'capability' => 4],
                                                  ['domain' => 'Assurance Sovereignty', 'capability' => 6],
                                                  ['domain' => 'Assurance Sovereignty', 'capability' => 8],
                                                  ['domain' => 'Open Source', 'capability' => 4],
                                                  ['domain' => 'Executive Oversight', 'capability' => 8],
                                              ]
                                          ],
                                          'Technical Control' => [
                                              'icon' => 'microchip',
                                              'color' => '#12bbd4',
                                              'overview' => 'Control over foundational technology components prioritizing open standards, platform portability, and vendor independence.',
                                              'capabilities' => [
                                                  ['domain' => 'Technical Sovereignty', 'capability' => 1],
                                                  ['domain' => 'Technical Sovereignty', 'capability' => 2],
                                                  ['domain' => 'Technical Sovereignty', 'capability' => 3],
                                                  ['domain' => 'Technical Sovereignty', 'capability' => 4],
                                                  ['domain' => 'Technical Sovereignty', 'capability' => 5],
                                                  ['domain' => 'Technical Sovereignty', 'capability' => 6],
                                                  ['domain' => 'Managed Services', 'capability' => 1],
                                                  ['domain' => 'Managed Services', 'capability' => 2],
                                              ]
                                          ],
                                          'Operational Resilience' => [
                                              'icon' => 'server',
                                              'color' => '#f0ab00',
                                              'overview' => 'Autonomy in executing critical operations without external reliance including business continuity and internal capability development.',
                                              'capabilities' => [
                                                  ['domain' => 'Operational Sovereignty', 'capability' => 1],
                                                  ['domain' => 'Operational Sovereignty', 'capability' => 4],
                                                  ['domain' => 'Operational Sovereignty', 'capability' => 5],
                                                  ['domain' => 'Operational Sovereignty', 'capability' => 8],
                                                  ['domain' => 'Technical Sovereignty', 'capability' => 8],
                                                  ['domain' => 'Managed Services', 'capability' => 8],
                                                  ['domain' => 'Executive Oversight', 'capability' => 6],
                                              ]
                                          ],
                                          'Vendor & Dependencies' => [
                                              'icon' => 'handshake',
                                              'color' => '#c9190b',
                                              'overview' => 'Management of external dependencies including transparency requirements, supply chain vetting, and contingency strategies.',
                                              'capabilities' => [
                                                  ['domain' => 'Operational Sovereignty', 'capability' => 2],
                                                  ['domain' => 'Operational Sovereignty', 'capability' => 6],
                                                  ['domain' => 'Assurance Sovereignty', 'capability' => 5],
                                                  ['domain' => 'Open Source', 'capability' => 3],
                                                  ['domain' => 'Open Source', 'capability' => 5],
                                                  ['domain' => 'Managed Services', 'capability' => 3],
                                                  ['domain' => 'Managed Services', 'capability' => 4],
                                              ]
                                          ],
                                          'Monitoring & Security' => [
                                              'icon' => 'binoculars',
                                              'color' => '#a18fff',
                                              'overview' => 'Continuous security monitoring, access control, and audit capabilities ensuring visibility and control over infrastructure.',
                                              'capabilities' => [
                                                  ['domain' => 'Data Sovereignty', 'capability' => 7],
                                                  ['domain' => 'Operational Sovereignty', 'capability' => 3],
                                                  ['domain' => 'Operational Sovereignty', 'capability' => 7],
                                                  ['domain' => 'Assurance Sovereignty', 'capability' => 7],
                                                  ['domain' => 'Managed Services', 'capability' => 5],
                                                  ['domain' => 'Managed Services', 'capability' => 6],
                                                  ['domain' => 'Managed Services', 'capability' => 7],
                                              ]
                                          ],
                                          'Open Source' => [
                                              'icon' => 'code-branch',
                                              'color' => '#7d1007',
                                              'overview' => 'Strategic OSS adoption for vendor independence through code transparency, community engagement, and fork capabilities.',
                                              'capabilities' => [
                                                  ['domain' => 'Open Source', 'capability' => 2],
                                                  ['domain' => 'Open Source', 'capability' => 6],
                                                  ['domain' => 'Open Source', 'capability' => 7],
                                                  ['domain' => 'Open Source', 'capability' => 8],
                                                  ['domain' => 'Technical Sovereignty', 'capability' => 7],
                                                  ['domain' => 'Executive Oversight', 'capability' => 5],
                                              ]
                                          ],
                                      ];
                                  }

                                  // Map domain names to qnum (include all domains, not just those in main nav)
                                  $domainNameToQnum = [];
                                  foreach ($json as $key => $value) {
                                      if (isset($value['title']) && isset($value['qnum'])) {
                                          $domainNameToQnum[$value['title']] = $value['qnum'];
                                      }
                                  }

                                  // Calculate statistics for each theme
                                  $themeStatistics = [];
                                  foreach ($thematicGroups as $themeName => $themeData) {
                                      $themeStats = ['total' => 0, 'maturityScore' => 0, 'complete' => 0, 'inprogress' => 0, 'planning' => 0, 'none' => 0];

                                      foreach ($themeData['capabilities'] as $capInfo) {
                                          // Skip if domain doesn't exist in current profile
                                          if (!isset($domainNameToQnum[$capInfo['domain']])) {
                                              continue;
                                          }

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

                                  <div style="background: #f9f9f9; padding: 1.5rem; border-radius: 8px; margin-bottom: 1.5rem;">
                                      <table style="width: 100%; border-collapse: collapse;">
                                          <thead>
                                              <tr style="border-bottom: 2px solid #0d60f8;">
                                                  <th style="text-align: left; padding: 0.75rem; color: #0d60f8; font-weight: 600;">Theme</th>
                                                  <th style="text-align: center; padding: 0.75rem; color: #0d60f8; font-weight: 600;">Maturity</th>
                                                  <th style="text-align: center; padding: 0.75rem; color: #0d60f8; font-weight: 600;">Complete</th>
                                                  <th style="text-align: center; padding: 0.75rem; color: #0d60f8; font-weight: 600;">In Progress</th>
                                                  <th style="text-align: center; padding: 0.75rem; color: #0d60f8; font-weight: 600;">Planning</th>
                                                  <th style="text-align: center; padding: 0.75rem; color: #0d60f8; font-weight: 600;">Not Started</th>
                                              </tr>
                                          </thead>
                                          <tbody>
                                              <?php
                                              foreach ($thematicGroups as $themeName => $themeData) {
                                                  $stats = $themeStatistics[$themeName];
                                                  $maturityPercent = $stats['total'] > 0 ? round(($stats['maturityScore'] / ($stats['total'] * 3)) * 100) : 0;

                                                  echo '<tr style="border-bottom: 1px solid #ddd;">';
                                                  echo '<td style="padding: 0.75rem;"><strong><i class="fa-solid fa-' . $themeData['icon'] . '" style="color: ' . $themeData['color'] . ';"></i> ' . $themeName . '</strong></td>';
                                                  echo '<td style="text-align: center; padding: 0.75rem; font-weight: 600; color: ' . $themeData['color'] . ';">' . $maturityPercent . '%</td>';
                                                  echo '<td style="text-align: center; padding: 0.75rem;">' . $stats['complete'] . '</td>';
                                                  echo '<td style="text-align: center; padding: 0.75rem;">' . $stats['inprogress'] . '</td>';
                                                  echo '<td style="text-align: center; padding: 0.75rem;">' . $stats['planning'] . '</td>';
                                                  echo '<td style="text-align: center; padding: 0.75rem;">' . $stats['none'] . '</td>';
                                                  echo '</tr>';
                                              }
                                              ?>
                                          </tbody>
                                      </table>
                                  </div>

                                  <?php
                                  if (empty($thematicGroups) || empty($themeStatistics)) {
                                      echo '<p style="color: #999; font-style: italic;">No thematic analysis data available. No assessment data was provided.</p>';
                                  } else {
                                  // Identify highest and lowest scoring themes for insights
                                  $themeScores = [];
                                  foreach ($thematicGroups as $themeName => $themeData) {
                                      $stats = $themeStatistics[$themeName];
                                      $maturityPercent = $stats['total'] > 0 ? round(($stats['maturityScore'] / ($stats['total'] * 3)) * 100) : 0;
                                      $themeScores[$themeName] = $maturityPercent;
                                  }
                                  arsort($themeScores);
                                  $topThemes = array_slice($themeScores, 0, 2, true);
                                  $bottomThemes = array_slice($themeScores, -2, 2, true);
                                  ?>

                                  <div style="background: #e8f4fd; border-left: 4px solid #0d60f8; padding: 1.25rem; border-radius: 4px; margin-bottom: 1.5rem;">
                                      <h4 style="color: #0d60f8; margin-top: 0;"><i class="fa-solid fa-lightbulb"></i> Key Thematic Insights</h4>

                                      <p style="margin-bottom: 0.75rem;"><strong>Strongest Themes:</strong></p>
                                      <ul style="margin-top: 0; margin-bottom: 1rem;">
                                          <?php
                                          foreach ($topThemes as $theme => $score) {
                                              echo '<li><strong>' . $theme . '</strong> (' . $score . '%) - Shows strong capability across ' . $thematicGroups[$theme]['overview'] . '</li>';
                                          }
                                          ?>
                                      </ul>

                                      <p style="margin-bottom: 0.75rem;"><strong>Areas Requiring Focus:</strong></p>
                                      <ul style="margin-top: 0; margin-bottom: 1rem;">
                                          <?php
                                          foreach ($bottomThemes as $theme => $score) {
                                              echo '<li><strong>' . $theme . '</strong> (' . $score . '%) - Indicates systemic gaps in ' . $thematicGroups[$theme]['overview'] . '</li>';
                                          }
                                          ?>
                                      </ul>

                                      <p style="margin: 0;"><strong>Pattern Analysis:</strong>
                                      <?php
                                      // Analyze if it's organizational vs domain-specific
                                      $scoreVariance = max($themeScores) - min($themeScores);
                                      if ($scoreVariance > 40) {
                                          echo 'Wide variation (' . $scoreVariance . ' points) between themes suggests <strong>domain-specific strengths and weaknesses</strong> rather than organizational-wide maturity levels. Focus improvement efforts on specific thematic areas.';
                                      } elseif ($scoreVariance > 20) {
                                          echo 'Moderate variation (' . $scoreVariance . ' points) indicates a mix of <strong>organizational capabilities with some domain-specific gaps</strong>. Consider both broad organizational initiatives and targeted domain improvements.';
                                      } else {
                                          echo 'Consistent maturity (' . $scoreVariance . ' points variance) across themes suggests <strong>organization-wide maturity level</strong>. Systematic enterprise-wide improvement programs will be most effective.';
                                      }
                                      ?>
                                      </p>
                                  </div>
                                  <?php
                                  }
                                  ?>
                              </div>

                              <div class="section-header">
                                  <h3><i class="fa-solid fa-route"></i> Strategic Recommendations</h3>
                                  <p class="imperative">To advance maturity, executive leadership should:</p>
                                  <ul class="action-list">
                                      <li><strong>Prioritize foundational capabilities</strong> in the identified gap areas before pursuing advanced maturity levels</li>
                                      <li><strong>Align investment decisions</strong> with <?php echo Security::escape($assessmentLob); ?> industry requirements and regulatory obligations</li>
                                      <li><strong>Establish executive sponsorship</strong> for improvement initiatives with dedicated budget allocation</li>
                                      <?php if (!empty($strengths)): ?>
                                      <li><strong>Leverage existing strengths</strong> in <?php echo Security::escape($strengths[0]["title"]); ?> to build momentum and demonstrate value</li>
                                      <?php endif; ?>
                                      <li><strong>Review detailed domain analysis</strong> (following pages) for specific technical and operational recommendations</li>
                                  </ul>

                                  <div class="result">The detailed assessment report that follows provides actionable recommendations for each domain, prioritized by maturity gap and industry relevance.</div>
                              </div>
                              </div>
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

	#print "<td><i class='fa-regular fa-" . $qnum . "'>&nbsp; &nbsp; </i>" . $title . "</td>";
	print "<td>" . $title . "</td>";
	$rating = MaturityRating::getRating($score);
	$ratingClass = MaturityRating::getRatingClass($rating);
	$displayScore = ceil($score);
	print "<td class='" . $ratingClass . "'>" . $rating . " ($displayScore out of $maxScore)</td>";
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
MaturityRating::putDomainStatus("8",$controlDetails,$json,$controls);
?>
</tr>

<tr>
<td class="optimizing"><strong>Optimizing</strong></td>

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
<td class="quantitative"></td>
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
<td class="defined"></td>

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
   #print '<div class="pagebreak"> </div>';
	$rating = MaturityRating::getRating($score);
	$ratingClass = MaturityRating::getRatingClass($rating);
	// Convert cell class to header class (e.g., cellInitial -> cellHeaderInitial)
	$headerClass = str_replace('cell', 'cellHeader', $ratingClass);
    print "<br><h2>$title - <span class='" . $headerClass . "'>". $rating . " Level</span></h2><div>";


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
        #print "<h4 class=title-text>Recommendation</h4>";
        print "<p>Start to work on preparing for actions concerning " . $sourceJson[$nextLevel] . "$sourceDomainName (Level $nextLevel)<p>";
        print "<br><p class=why-what>Definition of " . $sourceJson[$nextLevel] . " </p><p>" . $sourceJson[$nextSummary] . "</p>";

        if ($sourceJson[$nextRecommendation] != "") {
            print "<br>";
            print "<p>" . $sourceJson[$nextRecommendation] . "<p>";
			array_push($nextSteps,$sourceJson[$nextLevel]);
			array_push($nextStepsHow,$sourceJson[$nextSummary]);

			// Display Vendor Solution if available
			$vendorSolutionField = $nextLevel . '-vendor-solution';
			$vendorDescField = $nextLevel . '-vendor-description';
			if (!empty($sourceJson[$vendorSolutionField])) {
			    print '<div style="margin-top: 1.5rem; padding: 1.5rem; background: #f0f7ff; border-left: 4px solid #0d60f8; border-radius: 4px; border: 1px solid #b3d9ff;">';
			    print '<h3 style="color: #0d60f8; margin-top: 0; display: flex; align-items: center; gap: 0.5rem;">';
			    print '<i class="fa-solid fa-cube"></i> Vendor Solution';
			    print '</h3>';
			    print '<h4 style="color: #333; margin: 0.5rem 0;">' .
			          Security::escape($sourceJson[$vendorSolutionField]) . '</h4>';
			    print '<p style="color: #555; line-height: 1.6;">' .
			          Security::escape($sourceJson[$vendorDescField]) . '</p>';
			    print '</div>';
			}
        } else {
        print "<p>You're doing great as you are!</p>";
    }
   } else {
       // All capabilities in this domain are fully complete (including sub-domain if applicable)
       print "<h3 style='color: #2aaa04; margin-top: 1rem;'>Excellent Work!</h3>";
       print "<p>All capabilities in this domain have reached full maturity (Fully Complete). Continue maintaining these capabilities and consider exploring advanced optimizations.</p>";
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
                        <p>Don't wait until it's too late. Take proactive steps and empower your organization to enable proactive digital sovereignty. Use this maturity assessment to identify gaps, prioritize improvements, and take the first step towards a more autonomous and resilient future.
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

