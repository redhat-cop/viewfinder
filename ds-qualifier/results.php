<!doctype html>
<html lang="en-us" class="pf-theme-dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sales Qualification Results - Digital Sovereignty Sales Opportunity Qualifier</title>

  <!-- Reuse existing CSS from parent directory -->
  <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/brands.css" />
  <link rel="stylesheet" href="../css/style.css" />
  <link rel="stylesheet" href="../css/tab-dark.css" />
  <link rel="stylesheet" href="../css/patternfly.css" />
  <link rel="stylesheet" href="../css/patternfly-addons.css" />

  <!-- DS Qualifier specific styles -->
  <link rel="stylesheet" href="css/ds-qualifier.css" />

  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://kit.fontawesome.com/8a8c57f9cf.js" crossorigin="anonymous"></script>

  <style>
    body {
      background-color: #151515 !important;
      color: #ccc !important;
    }
    .pf-c-page__header-tools button {
      margin-right: 1rem;
    }
    @media print {
      .no-print { display: none; }
      .score-card { page-break-after: avoid; }
    }
  </style>
</head>

<body>
  <header class="pf-c-page__header no-print">
    <div class="pf-c-page__header-brand">
      <div class="pf-c-page__header-brand-toggle"></div>
      <a class="pf-c-page__header-brand-link" href="../index.php">
        <img class="pf-c-brand" src="../images/viewfinder-logo.png" alt="Viewfinder logo" />
      </a>
    </div>

    <div class="widget">
      <a href="../index.php"><button><i class="fa-solid fa-home"></i> Home</button></a>
      <a href="index.php"><button style="margin-left: 1rem;">New Sales Qualification</button></a>
      <a href="../index.php?profile=DigitalSovereignty"><button style="margin-left: 1rem;">Full Assessment</button></a>
    </div>
  </header>

  <div class="container">
    <?php
    // Load questions configuration for domain mapping
    $questions = require_once 'config.php';

    // Initialize scoring arrays
    $totalScore = 0;
    $maxScore = 21;
    $domainScores = [];
    $domainResponses = [];

    // Map domain keys to display names
    $domainKeyMap = [];
    foreach ($questions as $domainName => $domainData) {
        $domainKeyMap[$domainData['domain_key']] = $domainName;
        $domainScores[$domainName] = 0;
        $domainResponses[$domainName] = [];
    }

    // Calculate scores
    foreach ($_POST as $key => $value) {
        // Match question IDs (ds1, ts1, os1, etc.)
        if (preg_match('/^(ds|ts|os|as|oss|eo|ms)\d+$/', $key)) {
            $intValue = intval($value);
            $totalScore += $intValue;

            // Find which domain this question belongs to
            foreach ($questions as $domainName => $domainData) {
                foreach ($domainData['questions'] as $question) {
                    if ($question['id'] === $key) {
                        $domainScores[$domainName] += $intValue;
                        // Only add to responses if answer was "Yes" (value > 0)
                        if ($intValue > 0) {
                            $domainResponses[$domainName][] = $question['text'];
                        }
                        break 2;
                    }
                }
            }
        }
    }

    // Determine priority level (INVERTED: Low score = High opportunity because customer lacks DS capabilities)
    if ($totalScore <= 7) {
        $priority = 'High';
        $priorityClass = 'priority-high';
        $priorityIcon = 'fa-circle-check';
        $recommendation = 'Strong Digital Sovereignty Opportunity';
        $recommendationDetail = 'This customer has significant gaps in Digital Sovereignty capabilities. Excellent opportunity to position Red Hat sovereign solutions across multiple domains.';
    } elseif ($totalScore <= 14) {
        $priority = 'Medium';
        $priorityClass = 'priority-medium';
        $priorityIcon = 'fa-circle-exclamation';
        $recommendation = 'Moderate Digital Sovereignty Opportunity';
        $recommendationDetail = 'This customer has some DS capabilities but notable gaps remain. Good opportunity to strengthen their sovereignty posture with Red Hat solutions.';
    } else {
        $priority = 'Low';
        $priorityClass = 'priority-low';
        $priorityIcon = 'fa-circle-xmark';
        $recommendation = 'Limited Digital Sovereignty Opportunity';
        $recommendationDetail = 'This customer already has strong DS capabilities in place. Limited opportunity for new DS solutions, but consider maintenance, upgrades, or other Red Hat value propositions.';
    }

    $assessmentDate = date('F j, Y \a\t g:i A');
    ?>

    <!-- Results Header -->
    <div class="results-header">
      <h1><i class="fa-solid fa-chart-bar"></i> Digital Sovereignty Sales Qualification Results</h1>
      <p class="assessment-date"><strong>Assessment Date:</strong> <?php echo $assessmentDate; ?></p>
    </div>

    <!-- Score Card -->
    <div class="score-card <?php echo $priorityClass; ?>">
      <div class="score-icon">
        <i class="fa-solid <?php echo $priorityIcon; ?>"></i>
      </div>
      <h2><?php echo $priority; ?> Priority Opportunity</h2>

      <?php
      // Calculate percentage for visual display
      $scorePercentage = round(($totalScore / $maxScore) * 100);
      ?>

      <div class="score-visual-container">
        <div class="circular-progress" data-percentage="<?php echo $scorePercentage; ?>">
          <svg class="progress-ring" width="200" height="200">
            <circle class="progress-ring-circle-bg" cx="100" cy="100" r="90" />
            <circle class="progress-ring-circle"
                    cx="100"
                    cy="100"
                    r="90"
                    style="stroke-dasharray: <?php echo 2 * 3.14159 * 90; ?>; stroke-dashoffset: <?php echo 2 * 3.14159 * 90 * (1 - $scorePercentage / 100); ?>;" />
          </svg>
          <div class="progress-text">
            <div class="percentage-display"><?php echo $scorePercentage; ?>%</div>
            <div class="score-detail"><?php echo $totalScore; ?> of <?php echo $maxScore; ?> points</div>
          </div>
        </div>
      </div>

      <h3 class="recommendation-title"><?php echo $recommendation; ?></h3>
      <p class="recommendation-detail"><?php echo $recommendationDetail; ?></p>
    </div>

    <!-- Domain Breakdown -->
    <div class="domain-breakdown">
      <h2><i class="fa-solid fa-table"></i> Domain Analysis</h2>
      <p class="section-intro">Breakdown of qualification scores across the 7 Digital Sovereignty domains:</p>

      <div class="domain-table-wrapper">
        <table class="domain-table">
          <thead>
            <tr>
              <th>Domain</th>
              <th style="text-align: center;">Current Capabilities</th>
              <th style="text-align: center;">Gap Analysis</th>
              <th>Opportunity Level</th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($questions as $domainName => $domainData):
                $score = $domainScores[$domainName] ?? 0;
                $maxDomainScore = count($domainData['questions']);
                $percentage = ($score / $maxDomainScore) * 100;

                // INVERTED: Low score = High opportunity (customer lacks capabilities)
                if ($percentage <= 33) {
                    $strengthClass = 'strength-high';
                    $strengthIcon = 'fa-circle-check';
                    $strengthText = 'High Opportunity';
                } elseif ($percentage <= 66) {
                    $strengthClass = 'strength-medium';
                    $strengthIcon = 'fa-circle-exclamation';
                    $strengthText = 'Medium Opportunity';
                } else {
                    $strengthClass = 'strength-low';
                    $strengthIcon = 'fa-circle-xmark';
                    $strengthText = 'Low Opportunity';
                }
            ?>
              <tr>
                <td><strong><?php echo htmlspecialchars($domainName); ?></strong></td>
                <td style="text-align: center;">
                  <span class="domain-score-cell"><?php echo $score; ?>/<?php echo $maxDomainScore; ?></span>
                </td>
                <td style="text-align: center;">
                  <span class="progress-bar-wrapper">
                    <div class="progress-bar">
                      <div class="progress-fill <?php echo $strengthClass; ?>" style="width: <?php echo $percentage; ?>%;"></div>
                    </div>
                  </span>
                </td>
                <td>
                  <span class="strength-badge <?php echo $strengthClass; ?>">
                    <i class="fa-solid <?php echo $strengthIcon; ?>"></i> <?php echo $strengthText; ?>
                  </span>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Sales Actions -->
    <div class="sales-actions">
      <h2><i class="fa-solid fa-bullseye"></i> Recommended Next Steps</h2>

      <?php if ($priority === 'High'): ?>
        <div class="action-priority priority-high">
          <h3><i class="fa-solid fa-rocket"></i> Immediate Actions for High-Priority Opportunity</h3>
          <ul>
            <li><strong>Schedule Technical Deep-Dive:</strong> Arrange detailed discovery session on DS requirements, compliance needs, and technical architecture</li>
            <li><strong>Engage Specialists:</strong> Involve Red Hat Digital Sovereignty specialists and solution architects</li>
            <li><strong>Position Solutions:</strong> Present OpenShift, RHEL sovereign cloud, and compliance-focused offerings</li>
            <li><strong>Compliance Discussion:</strong> Discuss relevant frameworks (GDPR, NIS2, SecNumCloud, DSGVO, etc.)</li>
            <li><strong>Executive Alignment:</strong> Seek executive sponsor engagement on sovereignty strategy and budget</li>
            <li><strong>Reference Stories:</strong> Share case studies from similar sovereign deployments in public sector/regulated industries</li>
          </ul>

          <div class="recommended-products">
            <h4>Recommended Red Hat Solutions:</h4>
            <ul>
              <li>Red Hat OpenShift or Red Hat OpenShift AI</li>
              <li>Red Hat Enterprise Linux (sovereign OS)</li>
              <li>Red Hat Advanced Cluster Security</li>
              <li>Red Hat Ansible Automation Platform</li>
            </ul>
          </div>
        </div>

      <?php elseif ($priority === 'Medium'): ?>
        <div class="action-priority priority-medium">
          <h3><i class="fa-solid fa-magnifying-glass"></i> Discovery Actions for Medium-Priority Opportunity</h3>
          <ul>
            <li><strong>Full Assessment:</strong> Conduct complete <a href="../index.php?profile=DigitalSovereignty">Viewfinder Digital Sovereignty assessment</a> to identify specific gaps and requirements</li>
            <li><strong>Discovery Call:</strong> Schedule focused call on data residency, compliance, and sovereignty drivers</li>
            <li><strong>Education:</strong> Share Digital Sovereignty resources, whitepapers, and regulatory guidance</li>
            <li><strong>Stakeholder Mapping:</strong> Identify who owns compliance, security, and infrastructure decisions</li>
            <li><strong>Budget Validation:</strong> Confirm budget allocation and timeline for sovereignty initiatives</li>
            <li><strong>Competition Analysis:</strong> Understand competing vendors and their DS positioning</li>
          </ul>

          <div class="recommended-resources">
            <h4>Recommended Resources:</h4>
            <ul>
              <li>Digital Sovereignty whitepaper</li>
              <li>NIS2 compliance guide</li>
              <li>OpenShift sovereign deployment reference architectures</li>
              <li>Customer success stories in regulated industries</li>
            </ul>
          </div>
        </div>

      <?php else: ?>
        <div class="action-priority priority-low">
          <h3><i class="fa-solid fa-circle-info"></i> Positioning for Low-Priority Opportunity</h3>
          <ul>
            <li><strong>Alternative Value Props:</strong> Focus on other Red Hat strengths (automation, modernization, hybrid cloud, security)</li>
            <li><strong>Awareness Building:</strong> Keep DS messaging in background for future consideration</li>
            <li><strong>Monitor Changes:</strong> Track regulatory changes or M&A activity that could increase DS priority</li>
            <li><strong>Stakeholder Education:</strong> Provide educational content on emerging DS requirements in their industry</li>
            <li><strong>Revisit Quarterly:</strong> Reassess DS relevance as customer strategy evolves</li>
          </ul>

          <p class="note"><strong>Note:</strong> Even if DS is not primary today, regulations and market dynamics change. Position Red Hat as the sovereign-ready platform for future needs.</p>
        </div>
      <?php endif; ?>
    </div>

    <!-- Detailed Domain Insights -->
    <div class="domain-insights">
      <h2><i class="fa-solid fa-list-check"></i> Detailed Domain Insights</h2>
      <p class="section-intro">Review the specific areas where the customer showed DS requirements:</p>

      <?php foreach ($questions as $domainName => $domainData):
          $score = $domainScores[$domainName] ?? 0;
          $responses = $domainResponses[$domainName] ?? [];

          if ($score > 0):
      ?>
        <div class="domain-insight-card">
          <div class="domain-insight-header">
            <h3><?php echo htmlspecialchars($domainName); ?></h3>
            <span class="insight-score"><?php echo $score; ?>/<?php echo count($domainData['questions']); ?></span>
          </div>
          <p class="domain-insight-description"><?php echo htmlspecialchars($domainData['description']); ?></p>

          <div class="requirements-found">
            <h4>Requirements Identified:</h4>
            <ul>
              <?php foreach ($responses as $response): ?>
                <li><i class="fa-solid fa-check"></i> <?php echo htmlspecialchars($response); ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
      <?php
          endif;
        endforeach;
      ?>

      <?php if ($totalScore === 0): ?>
        <div class="no-requirements">
          <p><i class="fa-solid fa-info-circle"></i> No Digital Sovereignty requirements were identified in this assessment. Consider focusing on other Red Hat value propositions.</p>
        </div>
      <?php endif; ?>
    </div>

    <!-- Action Buttons -->
    <div class="form-actions no-print">
      <button onclick="window.print()" class="btn-primary">
        <i class="fa-solid fa-print"></i> Print Results
      </button>
      <a href="index.php" class="btn-secondary">
        <i class="fa-solid fa-rotate-left"></i> New Assessment
      </a>
      <a href="../index.php?profile=DigitalSovereignty" class="btn-success">
        <i class="fa-solid fa-arrow-right"></i> Run Full Viewfinder Assessment
      </a>
    </div>

    <!-- Footer -->
    <div class="results-footer">
      <p><small>Generated by Viewfinder Digital Sovereignty Sales Qualifier on <?php echo $assessmentDate; ?></small></p>
      <p><small>For technical assessments and detailed capability mapping, use the full <a href="../index.php?profile=DigitalSovereignty">Viewfinder Assessment Tool</a></small></p>
    </div>
  </div>
</body>
</html>
