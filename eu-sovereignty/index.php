<!doctype html>
<html lang="en-us" class="pf-theme-dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>EU Cloud Sovereignty Assessment - Viewfinder</title>

  <!-- Reuse existing CSS from parent directory -->
  <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/brands.css" />
  <link rel="stylesheet" href="../css/style.css" />
  <link rel="stylesheet" href="../css/tab-dark.css" />
  <link rel="stylesheet" href="../css/patternfly.css" />
  <link rel="stylesheet" href="../css/patternfly-addons.css" />

  <!-- EU Sovereignty specific styles (reuse DS Qualifier styles) -->
  <link rel="stylesheet" href="../ds-qualifier/css/ds-qualifier.css" />

  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
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

    /* EU-specific header styles */
    .eu-header {
      background: linear-gradient(135deg, #003399 0%, #0051A5 100%);
      color: #fff;
      padding: 2rem;
      border-radius: 8px;
      margin-bottom: 2rem;
      text-align: center;
    }

    .eu-header h1 {
      margin: 0 0 0.5rem 0;
      color: #fff;
      font-size: 2rem;
    }

    .eu-header p {
      margin: 0;
      color: #ffdd00;
      font-size: 1.1rem;
    }

    .eu-flag-header {
      font-size: 4rem;
      margin-bottom: 1rem;
      line-height: 1;
    }
  </style>
</head>

<body>
  <header class="pf-c-page__header">
    <div class="pf-c-page__header-brand">
      <div class="pf-c-page__header-brand-toggle"></div>
    </div>

    <div class="widget">
      <a href="../index.php"><button><i class="fa-solid fa-home"></i> Home</button></a>
    </div>
  </header>

  <div class="container">
    <?php
    // Load questions configuration
    $questions = require_once 'config.php';
    $domainNames = array_keys($questions);
    ?>

    <div class="eu-header">
      <div class="eu-flag-header">🇪🇺</div>
      <h1>EU Cloud Sovereignty Framework Assessment</h1>
      <p>Based on European Commission Cloud Sovereignty Framework v1.2.1 (October 2025)</p>
    </div>

    <div class="assessment-intro">
      <h2><i class="fa-solid fa-info-circle"></i> About This Assessment</h2>
      <p>
        This assessment evaluates your organization's cloud sovereignty maturity based on the
        <strong>European Commission's Cloud Sovereignty Framework</strong>, which defines measurable
        criteria across 8 Sovereignty Objectives (SOV). 
        <p>This assessment is not endorsed by any regulatory authority, and its findings or recommendations do not constitute legal advice. Red Hat bears no legal responsibility or liability for the results or its use.</p>
      </p>

      <div class="framework-overview">
        <h3>The 8 Sovereignty Objectives:</h3>
        <div class="sov-grid">
          <?php foreach ($questions as $domain => $data): ?>
            <div class="sov-card">
              <i class="fa-solid <?php echo $data['icon']; ?>"></i>
              <strong><?php echo htmlspecialchars($domain); ?></strong>
              <span>(<?php echo round($data['weight'] * 100); ?>%)</span>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="seal-levels">
        <h3>SEAL Levels (Sovereignty Effectiveness Assurance Levels):</h3>
        <br>
        <ul>
          <li><strong>SEAL-0:</strong> No Sovereignty - Under exclusive non-EU control</li>
          <li><strong>SEAL-1:</strong> Jurisdictional Sovereignty - EU law applies but limited enforceability</li>
          <li><strong>SEAL-2:</strong> Data Sovereignty - EU law enforceable, some non-EU dependencies remain</li>
          <li><strong>SEAL-3:</strong> Digital Resilience - Meaningful EU influence, marginal non-EU control</li>
          <li><strong>SEAL-4:</strong> Full Digital Sovereignty - Complete EU control, no critical non-EU dependencies</li>
        </ul>
      </div>
    </div>

    <form action="results.php" method="POST" class="assessment-form">
      <h2><i class="fa-solid fa-clipboard-question"></i> Assessment Questions</h2>
      <p class="instructions">
        Answer each question with <strong>Yes</strong>, <strong>No</strong>, or <strong>Don't Know</strong>.
        Each "Yes" answer contributes to your overall sovereignty score.
      </p>

      <?php foreach ($questions as $domainName => $domainData): ?>
        <div class="domain-section">
          <div class="domain-header">
            <i class="fa-solid <?php echo $domainData['icon']; ?>"></i>
            <h3><?php echo htmlspecialchars($domainName); ?></h3>
            <span class="domain-weight"><?php echo round($domainData['weight'] * 100); ?>% of total score</span>
          </div>
          <p class="domain-description"><?php echo htmlspecialchars($domainData['description']); ?></p>

          <?php if (!empty($domainData['contributing_factors'])): ?>
            <div class="contributing-factors">
              <strong>Contributing Factors:</strong>
              <ul>
                <?php foreach ($domainData['contributing_factors'] as $factor): ?>
                  <li><?php echo htmlspecialchars($factor); ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>

          <div class="questions-grid">
            <?php foreach ($domainData['questions'] as $question): ?>
              <div class="question-card">
                <div class="question-text">
                  <?php echo htmlspecialchars($question['text']); ?>
                  <?php if (!empty($question['tooltip'])): ?>
                    <span class="tooltip-icon" data-tooltip="<?php echo htmlspecialchars($question['tooltip']); ?>">ⓘ</span>
                  <?php endif; ?>
                </div>
                <div class="question-options">
                  <input type="radio" name="<?php echo $question['id']; ?>" id="<?php echo $question['id']; ?>_yes" value="1" class="question-radio" required>
                  <label for="<?php echo $question['id']; ?>_yes" class="btn-option btn-yes">
                    <i class="fa-solid fa-check"></i> Yes
                  </label>

                  <input type="radio" name="<?php echo $question['id']; ?>" id="<?php echo $question['id']; ?>_no" value="0" class="question-radio">
                  <label for="<?php echo $question['id']; ?>_no" class="btn-option btn-no">
                    <i class="fa-solid fa-xmark"></i> No
                  </label>

                  <input type="radio" name="<?php echo $question['id']; ?>" id="<?php echo $question['id']; ?>_unknown" value="unknown" class="question-radio">
                  <label for="<?php echo $question['id']; ?>_unknown" class="btn-option btn-unknown">
                    <i class="fa-solid fa-question"></i> Don't Know
                  </label>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endforeach; ?>

      <div class="form-actions">
        <button type="submit" class="submit-button">
          <i class="fa-solid fa-chart-line"></i> View Results
        </button>
      </div>
    </form>
  </div>

  <style>
    .assessment-intro {
      background: #2a2a2a;
      border: 1px solid #444;
      border-radius: 8px;
      padding: 2rem;
      margin-bottom: 2rem;
    }

    .assessment-intro h2 {
      color: #9ec7fc;
      margin-top: 0;
    }

    .framework-overview {
      margin: 1.5rem 0;
    }

    .framework-overview h3 {
      color: #9ec7fc;
      margin-bottom: 1rem;
    }

    .sov-grid {
      display: grid;
      grid-template-columns: repeat(8, 1fr);
      gap: 0.75rem;
      margin-top: 1rem;
    }

    .sov-card {
      background: #1f1f1f;
      border: 1px solid #444;
      border-radius: 6px;
      padding: 0.75rem 0.5rem;
      text-align: center;
      transition: all 0.3s ease;
    }

    .sov-card:hover {
      border-color: #0d60f8;
      transform: translateY(-2px);
    }

    .sov-card i {
      font-size: 1.5rem;
      color: #0d60f8;
      margin-bottom: 0.5rem;
      display: block;
    }

    .sov-card strong {
      display: block;
      color: #fff;
      margin-bottom: 0.25rem;
      font-size: 0.85rem;
      line-height: 1.2;
    }

    .sov-card span {
      color: #999;
      font-size: 0.8rem;
    }

    @media (max-width: 1200px) {
      .sov-grid {
        grid-template-columns: repeat(4, 1fr);
      }
    }

    @media (max-width: 768px) {
      .sov-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    .seal-levels {
      margin-top: 1.5rem;
      background: #1f1f1f;
      border-left: 4px solid #ffdd00;
      padding: 1rem 1.5rem;
      border-radius: 4px;
    }

    .seal-levels h3 {
      color: #9ec7fc;
      margin-top: 0;
    }

    .seal-levels ul {
      margin: 0;
      padding-left: 1.5rem;
    }

    .seal-levels li {
      margin-bottom: 0.5rem;
      color: #ccc;
    }

    .domain-section {
      background: #2a2a2a;
      border: 1px solid #444;
      border-radius: 8px;
      padding: 1.5rem;
      margin-bottom: 2rem;
    }

    .domain-header {
      display: flex;
      align-items: center;
      gap: 1rem;
      margin-bottom: 0.5rem;
      padding-bottom: 1rem;
      border-bottom: 2px solid #444;
    }

    .domain-header i {
      font-size: 1.5rem;
      color: #0d60f8;
    }

    .domain-header h3 {
      margin: 0;
      color: #9ec7fc;
      flex: 1;
    }

    .domain-weight {
      background: #0d60f8;
      color: #fff;
      padding: 0.25rem 0.75rem;
      border-radius: 4px;
      font-size: 0.9rem;
      font-weight: 600;
    }

    .domain-description {
      color: #ccc;
      font-style: italic;
      margin-bottom: 1rem;
    }

    .contributing-factors {
      background: #1f1f1f;
      border-left: 3px solid #0d60f8;
      padding: 1rem 1.5rem;
      margin-bottom: 1.5rem;
      border-radius: 4px;
    }

    .contributing-factors strong {
      color: #9ec7fc;
      display: block;
      margin-bottom: 0.5rem;
      font-size: 0.95rem;
    }

    .contributing-factors ul {
      margin: 0;
      padding-left: 1.5rem;
      list-style-type: disc;
    }

    .contributing-factors li {
      color: #ccc;
      margin-bottom: 0.35rem;
      font-size: 0.9rem;
      line-height: 1.4;
    }

    .questions-grid {
      display: grid;
      gap: 1rem;
    }

    .question-card {
      background: #1f1f1f;
      border: 1px solid #444;
      border-radius: 6px;
      padding: 1rem;
    }

    .question-text {
      color: #fff;
      margin-bottom: 1rem;
      font-weight: 500;
      line-height: 1.5;
    }

    .tooltip-icon {
      color: #12bbd4;
      cursor: help;
      margin-left: 0.5rem;
      font-size: 1.1rem;
      display: inline-block;
      font-weight: bold;
      vertical-align: middle;
    }

    .question-options {
      display: flex;
      gap: 0.75rem;
      flex-wrap: wrap;
    }

    /* Hide actual radio inputs */
    .question-radio {
      position: absolute;
      opacity: 0;
      width: 0;
      height: 0;
    }

    /* Style labels as buttons */
    .btn-option {
      min-width: 120px;
      padding: 0.75rem 2rem;
      border-radius: 6px;
      cursor: pointer;
      transition: all 0.2s ease;
      text-align: center;
      font-weight: 600;
      font-size: 1rem;
      border: 2px solid;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      white-space: nowrap;
    }

    .btn-yes {
      background: #1a1a1a;
      border-color: #2aaa04;
      color: #2aaa04;
    }

    .btn-yes:hover {
      background: rgba(42, 170, 4, 0.1);
      border-color: #3fcc00;
      color: #3fcc00;
    }

    .btn-no {
      background: #1a1a1a;
      border-color: #e57373;
      color: #e57373;
    }

    .btn-no:hover {
      background: rgba(201, 25, 11, 0.1);
      border-color: #e01000;
      color: #e01000;
    }

    .btn-unknown {
      background: #1a1a1a;
      border-color: #f0ab00;
      color: #f0ab00;
    }

    .btn-unknown:hover {
      background: rgba(240, 171, 0, 0.1);
      border-color: #ffc425;
      color: #ffc425;
    }

    /* Selected state */
    .question-radio:checked + .btn-yes {
      background: linear-gradient(135deg, #2aaa04 0%, #1b7003 100%);
      border-color: #2aaa04;
      color: #fff;
      box-shadow: 0 2px 8px rgba(42, 170, 4, 0.3);
    }

    .question-radio:checked + .btn-no {
      background: linear-gradient(135deg, #e57373 0%, #a30000 100%);
      border-color: #e57373;
      color: #fff;
      box-shadow: 0 2px 8px rgba(201, 25, 11, 0.3);
    }

    .question-radio:checked + .btn-unknown {
      background: linear-gradient(135deg, #f0ab00 0%, #c58c00 100%);
      border-color: #f0ab00;
      color: #fff;
      box-shadow: 0 2px 8px rgba(240, 171, 0, 0.3);
    }

    .form-actions {
      text-align: center;
      margin-top: 2rem;
    }

    .submit-button {
      background: linear-gradient(135deg, #0d60f8 0%, #12bbd4 100%);
      color: #fff;
      border: none;
      padding: 1rem 3rem;
      border-radius: 6px;
      font-size: 1.1rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .submit-button:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(13, 96, 248, 0.4);
    }

    .submit-button i {
      margin-right: 0.5rem;
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

    /* jQuery UI Tooltip Dark Theme */
    .ui-tooltip {
      background: #2a2a2a !important;
      border: 1px solid #0d60f8 !important;
      color: #fff !important;
      padding: 0.75rem !important;
      font-size: 0.9rem !important;
      line-height: 1.4 !important;
      max-width: 300px !important;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5) !important;
      border-radius: 4px !important;
    }

    .ui-tooltip-content {
      color: #ccc !important;
    }
  </style>

  <script>
    // Add tooltips
    $(function() {
      $('.tooltip-icon').tooltip({
        content: function() {
          return $(this).attr('data-tooltip');
        },
        position: { my: "left+15 center", at: "right center" },
        classes: {
          "ui-tooltip": "ui-corner-all ui-widget-shadow"
        }
      });
    });
  </script>

  <footer class="disclaimer-footer">
    <p><strong>Red Hat Disclaimer:</strong> This Cloud Sovereignty Framework Self-Assessment Tool is provided by Red Hat to help organizations review their sovereign posture. It is not endorsed by any regulatory authority, and its findings or recommendations do not constitute legal advice. Red Hat bears no legal responsibility or liability for the results or its use.</p>
  </footer>
</body>
</html>
