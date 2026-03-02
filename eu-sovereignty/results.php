<!doctype html>
<html lang="en-us" class="pf-theme-dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>EU Cloud Sovereignty Assessment Results - Viewfinder</title>

  <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/brands.css" />
  <link rel="stylesheet" href="../css/style.css" />
  <link rel="stylesheet" href="../css/tab-dark.css" />
  <link rel="stylesheet" href="../css/patternfly.css" />
  <link rel="stylesheet" href="../css/patternfly-addons.css" />
  <link rel="stylesheet" href="../ds-qualifier/css/ds-qualifier.css" />

  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://kit.fontawesome.com/8a8c57f9cf.js" crossorigin="anonymous"></script>

  <style>
    body {
      background-color: #151515 !important;
      color: #ccc !important;
    }
    .pf-c-page__header {
      padding-top: 1.5rem;
    }
    .pf-c-page__header-tools button,
    .widget button {
      margin-right: 1rem;
    }
  </style>
</head>

<body>
  <header class="pf-c-page__header">
    <div class="pf-c-page__header-brand"></div>
    <div class="widget">
      <a href="../index.php"><button><i class="fa-solid fa-home"></i> Home</button></a>
      <a href="index.php"><button><i class="fa-solid fa-redo"></i> Retake Assessment</button></a>
    </div>
  </header>

  <div class="container">
    <?php
    // Load questions configuration
    $questions = require_once 'config.php';

    // Initialize scoring arrays
    $totalScore = 0;
    $maxScore = 0;
    $domainScores = [];
    $domainMaxScores = [];
    $domainResponses = [];
    $unknownQuestions = [];

    // Process responses
    foreach ($questions as $domainName => $domainData) {
        $domainScore = 0;
        $domainMax = count($domainData['questions']);
        $domainResponses[$domainName] = [];

        foreach ($domainData['questions'] as $question) {
            if (isset($_POST[$question['id']])) {
                $response = $_POST[$question['id']];

                if ($response === 'unknown') {
                    $unknownQuestions[] = [
                        'domain' => $domainName,
                        'question' => $question['text']
                    ];
                } else {
                    $value = (int)$response;
                    $domainScore += $value;
                    if ($value > 0) {
                        $domainResponses[$domainName][] = $question['text'];
                    }
                }
            }
        }

        $domainScores[$domainName] = $domainScore;
        $domainMaxScores[$domainName] = $domainMax;
        $maxScore += $domainMax;
        $totalScore += $domainScore;
    }

    // Calculate weighted score
    $weightedScore = 0;
    foreach ($questions as $domainName => $domainData) {
        $domainPercentage = $domainMaxScores[$domainName] > 0
            ? ($domainScores[$domainName] / $domainMaxScores[$domainName])
            : 0;
        $weightedScore += $domainPercentage * $domainData['weight'];
    }
    $weightedScore *= 100; // Convert to percentage

    // Determine SEAL level
    if ($weightedScore >= 90) {
        $sealLevel = 4;
        $sealName = 'Full Digital Sovereignty';
        $sealColor = '#2aaa04';
        $sealTextColor = '#ffffff';
        $sealDescription = 'Technology and operations are under complete EU control, subject only to EU law, with no critical non-EU dependencies.';
    } elseif ($weightedScore >= 70) {
        $sealLevel = 3;
        $sealName = 'Digital Resilience';
        $sealColor = '#8bc34a';
        $sealTextColor = '#000000';
        $sealDescription = 'EU actors exercise meaningful but not full influence. Technology or operations are under only marginal control of non-EU third parties.';
    } elseif ($weightedScore >= 50) {
        $sealLevel = 2;
        $sealName = 'Data Sovereignty';
        $sealColor = '#ffc107';
        $sealTextColor = '#000000';
        $sealDescription = 'EU law is applicable and enforceable, but material non-EU dependencies remain. Operations are under indirect control of non-EU third parties.';
    } elseif ($weightedScore >= 30) {
        $sealLevel = 1;
        $sealName = 'Jurisdictional Sovereignty';
        $sealColor = '#ec7a08';
        $sealTextColor = '#ffffff';
        $sealDescription = 'While EU law formally applies, it has limited practical enforceability. Technology or service remains under exclusive control of non-EU third parties.';
    } else {
        $sealLevel = 0;
        $sealName = 'No Sovereignty';
        $sealColor = '#c9190b';
        $sealTextColor = '#ffffff';
        $sealDescription = 'The service, technology, or operations are under exclusive control of non-EU third parties and are governed entirely by non-EU jurisdictions.';
    }
    ?>

    <div class="results-header">
      <div class="eu-flag-header">🇪🇺</div>
      <h1><i class="fa-solid fa-chart-bar"></i> EU Cloud Sovereignty Assessment Results</h1>
    </div>

    <!-- SEAL Level Display -->
    <div class="seal-result" style="background: linear-gradient(135deg, <?php echo $sealColor; ?>22 0%, <?php echo $sealColor; ?>44 100%); border-left: 4px solid <?php echo $sealColor; ?>;">
      <div class="seal-badge" style="background: <?php echo $sealColor; ?>; color: <?php echo $sealTextColor; ?>;">
        <div class="seal-level">SEAL-<?php echo $sealLevel; ?></div>
        <div class="seal-name"><?php echo $sealName; ?></div>
      </div>
      <div class="seal-details">
        <h2>Your Sovereignty Level</h2>
        <p><?php echo $sealDescription; ?></p>
        <div class="seal-score">
          <strong>Weighted Sovereignty Score:</strong> <?php echo round($weightedScore, 1); ?>%
          <span class="raw-score">(<?php echo $totalScore; ?>/<?php echo $maxScore; ?> questions answered "Yes")</span>
        </div>
      </div>
    </div>

    <!-- Domain Scores -->
    <div class="domain-results">
      <h2><i class="fa-solid fa-list-check"></i> Domain Analysis</h2>
      <p>Performance across the 8 Sovereignty Objectives:</p>

      <div class="domain-cards">
        <?php foreach ($questions as $domainName => $domainData): ?>
          <?php
          $score = $domainScores[$domainName];
          $max = $domainMaxScores[$domainName];
          $percentage = $max > 0 ? round(($score / $max) * 100) : 0;
          $weight = $domainData['weight'];
          ?>
          <div class="domain-card">
            <div class="domain-card-header">
              <i class="fa-solid <?php echo $domainData['icon']; ?>"></i>
              <div class="domain-info">
                <h3><?php echo htmlspecialchars($domainName); ?></h3>
                <span class="domain-weight-badge"><?php echo round($weight * 100); ?>% of total</span>
              </div>
            </div>
            <div class="domain-score-display">
              <div class="score-number"><?php echo $score; ?>/<?php echo $max; ?></div>
              <div class="progress-bar">
                <div class="progress-fill" style="width: <?php echo $percentage; ?>%;"></div>
              </div>
              <div class="score-percentage"><?php echo $percentage; ?>%</div>
            </div>
            <?php if (!empty($domainData['next_steps'])): ?>
              <div class="domain-next-steps">
                <strong>Recommended Next Steps:</strong>
                <ul>
                  <?php foreach ($domainData['next_steps'] as $nextStep): ?>
                    <li><?php echo htmlspecialchars($nextStep); ?></li>
                  <?php endforeach; ?>
                </ul>
              </div>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Unknown Questions -->
    <?php if (!empty($unknownQuestions)): ?>
      <div class="unknown-section">
        <h2><i class="fa-solid fa-circle-question"></i> Areas Requiring Investigation</h2>
        <p>You answered "Don't Know" to the following questions. Investigating these areas could improve your sovereignty posture:</p>
        <div class="unknown-list">
          <?php foreach ($unknownQuestions as $unknown): ?>
            <div class="unknown-item">
              <strong><?php echo htmlspecialchars($unknown['domain']); ?>:</strong>
              <?php echo htmlspecialchars($unknown['question']); ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>

    <!-- Recommendations -->
    <div class="recommendations">
      <h2><i class="fa-solid fa-lightbulb"></i> Next Steps</h2>
      <?php if ($sealLevel >= 3): ?>
        <p>Your organization demonstrates strong cloud sovereignty practices. To maintain and improve:</p>
        <ul>
          <li>Continue regular assessments to ensure compliance with evolving EU regulations</li>
          <li>Share best practices within your industry to strengthen the EU digital ecosystem</li>
          <li>Consider contributing to EU open-source projects relevant to your operations</li>
          <li>Monitor supply chain dependencies to prevent erosion of sovereignty</li>
        </ul>
      <?php elseif ($sealLevel >= 2): ?>
        <p>You have established data sovereignty foundations. To advance to higher SEAL levels:</p>
        <ul>
          <li>Reduce dependencies on non-EU technology providers and infrastructure</li>
          <li>Strengthen operational capabilities to enable independent system management</li>
          <li>Review and renegotiate contracts to ensure EU jurisdiction for all disputes</li>
          <li>Implement comprehensive exit strategies for all critical services</li>
        </ul>
      <?php elseif ($sealLevel >= 1): ?>
        <p>Your organization recognizes the importance of sovereignty but significant gaps remain:</p>
        <ul>
          <li>Prioritize data residency requirements - ensure all data stays within EU borders</li>
          <li>Establish customer-managed encryption key controls</li>
          <li>Conduct supply chain analysis to identify and mitigate non-EU dependencies</li>
          <li>Require EU-based support and operations teams from your providers</li>
        </ul>
      <?php else: ?>
        <p>Immediate action is needed to establish basic sovereignty protections:</p>
        <ul>
          <li><strong>Critically assess</strong> all cloud provider relationships for EU presence and jurisdiction</li>
          <li><strong>Demand transparency</strong> on data processing locations and foreign law exposure</li>
          <li><strong>Begin migration planning</strong> toward EU-based or sovereign cloud solutions</li>
          <li><strong>Engage legal counsel</strong> to review contracts for sovereignty risks</li>
        </ul>
      <?php endif; ?>
    </div>

    <!-- EU Framework Reference -->
    <div class="framework-reference">
      <h2><i class="fa-solid fa-book"></i> About the EU Cloud Sovereignty Framework</h2>
      <p>
        This assessment is based on the European Commission's Cloud Sovereignty Framework (Version 1.2.1, October 2025),
        which provides a standardized method to assess and procure cloud services that align with EU values and regulatory requirements.
      </p>
      <p>
        <a href="https://commission.europa.eu/document/download/09579818-64a6-4dd5-9577-446ab6219113_en" target="_blank" class="framework-link">
          <i class="fa-solid fa-external-link"></i> View Official EU Framework Document
        </a>
      </p>
    </div>
  </div>

  <style>
    .eu-flag-header {
      font-size: 4rem;
      text-align: center;
      margin-bottom: 1rem;
      line-height: 1;
    }

    .results-header {
      text-align: center;
      margin-bottom: 2rem;
    }

    .results-header h1 {
      color: #9ec7fc;
      margin: 0;
    }

    .seal-result {
      border-radius: 8px;
      padding: 2rem;
      margin-bottom: 2rem;
      display: flex;
      gap: 2rem;
      align-items: center;
    }

    .seal-badge {
      color: #fff;
      padding: 2rem;
      border-radius: 8px;
      text-align: center;
      min-width: 200px;
    }

    .seal-level {
      font-size: 3rem;
      font-weight: bold;
      margin-bottom: 0.5rem;
    }

    .seal-name {
      font-size: 1.2rem;
      font-weight: 600;
    }

    .seal-details {
      flex: 1;
    }

    .seal-details h2 {
      color: #fff;
      margin-top: 0;
    }

    .seal-details p {
      color: #ccc;
      line-height: 1.6;
    }

    .seal-score {
      margin-top: 1rem;
      padding: 1rem;
      background: rgba(0, 0, 0, 0.3);
      border-radius: 4px;
    }

    .seal-score strong {
      color: #fff;
    }

    .raw-score {
      display: block;
      color: #999;
      font-size: 0.9rem;
      margin-top: 0.5rem;
    }

    .domain-results {
      background: #2a2a2a;
      border: 1px solid #444;
      border-radius: 8px;
      padding: 2rem;
      margin-bottom: 2rem;
    }

    .domain-results h2 {
      color: #9ec7fc;
      margin-top: 0;
    }

    .domain-cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 1rem;
      margin-top: 1.5rem;
    }

    .domain-card {
      background: #1f1f1f;
      border: 1px solid #444;
      border-radius: 6px;
      padding: 1.5rem;
    }

    .domain-card-header {
      display: flex;
      align-items: center;
      gap: 1rem;
      margin-bottom: 1rem;
      padding-bottom: 1rem;
      border-bottom: 1px solid #444;
    }

    .domain-card-header i {
      font-size: 1.5rem;
      color: #0d60f8;
    }

    .domain-info h3 {
      margin: 0;
      color: #fff;
      font-size: 1rem;
    }

    .domain-weight-badge {
      display: inline-block;
      background: #0d60f8;
      color: #fff;
      padding: 0.25rem 0.5rem;
      border-radius: 4px;
      font-size: 0.75rem;
      margin-top: 0.25rem;
    }

    .domain-score-display {
      display: flex;
      align-items: center;
      gap: 1rem;
      margin-bottom: 1rem;
    }

    .score-number {
      font-size: 1.5rem;
      font-weight: bold;
      color: #0d60f8;
      min-width: 60px;
    }

    .progress-bar {
      flex: 1;
      height: 12px;
      background: #1a1a1a;
      border-radius: 6px;
      overflow: hidden;
    }

    .progress-fill {
      height: 100%;
      background: linear-gradient(90deg, #0d60f8 0%, #12bbd4 100%);
      transition: width 0.3s ease;
    }

    .score-percentage {
      font-weight: bold;
      color: #fff;
      min-width: 50px;
      text-align: right;
    }

    .domain-next-steps {
      color: #ccc;
      font-size: 0.9rem;
      margin-top: 1rem;
      padding-top: 0.75rem;
      border-top: 1px solid #555;
    }

    .domain-next-steps strong {
      color: #12bbd4;
      font-size: 0.85rem;
    }

    .domain-next-steps ul {
      margin: 0.5rem 0 0 0;
      padding-left: 2rem;
      list-style-type: disc;
    }

    .domain-next-steps li {
      margin-bottom: 0.5rem;
      line-height: 1.5;
      color: #fff;
    }

    .domain-next-steps li::marker {
      color: #12bbd4;
      font-size: 1.2em;
    }

    .unknown-section {
      background: rgba(236, 122, 8, 0.1);
      border: 1px solid #ec7a08;
      border-radius: 8px;
      padding: 2rem;
      margin-bottom: 2rem;
    }

    .unknown-section h2 {
      color: #ec7a08;
      margin-top: 0;
    }

    .unknown-list {
      margin-top: 1rem;
    }

    .unknown-item {
      background: rgba(0, 0, 0, 0.3);
      padding: 1rem;
      border-radius: 4px;
      margin-bottom: 0.5rem;
      color: #ccc;
    }

    .unknown-item strong {
      color: #ec7a08;
    }

    .recommendations {
      background: #2a2a2a;
      border: 1px solid #444;
      border-radius: 8px;
      padding: 2rem;
      margin-bottom: 2rem;
    }

    .recommendations h2 {
      color: #9ec7fc;
      margin-top: 0;
    }

    .recommendations ul {
      line-height: 1.8;
    }

    .framework-reference {
      background: #1f1f1f;
      border: 1px solid #444;
      border-radius: 8px;
      padding: 2rem;
      text-align: center;
    }

    .framework-reference h2 {
      color: #9ec7fc;
      margin-top: 0;
    }

    .framework-link {
      display: inline-block;
      background: linear-gradient(135deg, #003399 0%, #0051A5 100%);
      color: #fff;
      padding: 1rem 2rem;
      border-radius: 6px;
      text-decoration: none;
      font-weight: 600;
      margin-top: 1rem;
      transition: all 0.3s ease;
    }

    .framework-link:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0, 51, 153, 0.4);
    }

    @media (max-width: 768px) {
      .seal-result {
        flex-direction: column;
      }

      .domain-cards {
        grid-template-columns: 1fr;
      }
    }

    /* Disclaimer Footer */
    .disclaimer-footer {
      background-color: #1a1a1a;
      border-top: 1px solid #444;
      padding: 1.5rem 2rem;
      text-align: center;
      margin-top: 3rem;
    }

    .disclaimer-footer p {
      color: #999;
      margin: 0;
      font-size: 0.9rem;
    }

    .disclaimer-footer strong {
      color: #ccc;
    }
  </style>

  <footer class="disclaimer-footer">
    <p><strong>Red Hat Disclaimer:</strong> This Cloud Sovereignty Framework Self-Assessment Tool is provided by Red Hat to help organizations review their sovereign posture. It is not endorsed by any regulatory authority, and its findings or recommendations do not constitute legal advice. Red Hat bears no legal responsibility or liability for the results or its use.</p>
  </footer>
</body>
</html>
