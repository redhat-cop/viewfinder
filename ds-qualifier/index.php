<!doctype html>
<html lang="en-us" class="pf-theme-dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Digital Sovereignty Sales Opportunity Qualifier - Viewfinder</title>

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
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
  <script src="https://kit.fontawesome.com/8a8c57f9cf.js" crossorigin="anonymous"></script>

  <style>
    body {
      background-color: #151515 !important;
      color: #ccc !important;
    }
    .pf-c-page__header-tools button {
      margin-right: 1rem;
    }
  </style>
</head>

<body>
  <header class="pf-c-page__header">
    <div class="pf-c-page__header-brand">
      <div class="pf-c-page__header-brand-toggle"></div>
      <a class="pf-c-page__header-brand-link" href="../index.php">
        <img class="pf-c-brand" src="../images/viewfinder-logo.png" alt="Viewfinder logo" />
      </a>
    </div>

    <div class="widget">
      <a href="../index.php?profile=DigitalSovereignty"><button>Full Assessment</button></a>
    </div>
  </header>

  <div class="container">
    <?php
    // Load questions configuration
    $questions = require_once 'config.php';
    ?>

    <div class="qualifier-header">
      <h1><i class="fa-solid fa-clipboard-check"></i> Digital Sovereignty Sales Opportunity Qualifier</h1>
      <p class="subtitle">Quick 10-15 minute assessment for sales teams</p>
    </div>

    <div class="qualifier-intro" id="intro-section">
      <h3><i class="fa-solid fa-info-circle"></i> About This Tool</h3>
      <p>This lightweight qualification tool helps identify whether an opportunity has Digital Sovereignty requirements.
         Answer the questions below based on customer conversations, discovery notes, or RFP requirements.</p>
      <ul>
        <li><strong>Time Required:</strong> 10-15 minutes</li>
        <li><strong>Questions:</strong> 21 yes/no questions across 7 domains</li>
        <li><strong>Output:</strong> Opportunity score with recommended next steps</li>
        <li><strong>For Full Assessment:</strong> Use the <a href="../index.php?profile=DigitalSovereignty">complete Viewfinder tool</a> for technical deep-dive</li>
      </ul>
    </div>

    <form action="results.php" method="POST" id="qualifier-form">
      <!-- Score Preview -->
      <div class="score-preview">
        <div class="score-preview-content">
          <span class="score-label">Current Score:</span>
          <span id="score-counter" class="score-value">0/21</span>
        </div>
      </div>

      <!-- Progress Indicator -->
      <div class="section-progress">
        <span class="progress-text">Section <span id="current-section">1</span> of 7</span>
        <div class="progress-bar-container">
          <div class="progress-bar-fill" id="section-progress-bar"></div>
        </div>
      </div>

      <!-- Domain Questions -->
      <?php
      $sectionIndex = 0;
      foreach ($questions as $domainName => $domainData):
        $sectionIndex++;
      ?>
        <div class="domain-section section-pane"
             id="domain-<?php echo strtolower(str_replace(' ', '-', $domainName)); ?>"
             data-section="<?php echo $sectionIndex; ?>"
             style="display: <?php echo $sectionIndex === 1 ? 'block' : 'none'; ?>;">
          <div class="domain-header">
            <h2><i class="fa-solid fa-shield-halved"></i> <?php echo htmlspecialchars($domainName); ?></h2>
            <p class="domain-description"><?php echo htmlspecialchars($domainData['description']); ?></p>
            <div class="domain-score">
              <span class="domain-score-label">Domain Score:</span>
              <span class="domain-score-value" id="score-<?php echo $domainData['domain_key']; ?>">0/<?php echo count($domainData['questions']); ?></span>
            </div>
          </div>

          <div class="questions-list">
            <?php foreach ($domainData['questions'] as $question): ?>
              <div class="question-item">
                <div class="checkbox-wrapper">
                  <input type="checkbox"
                         id="<?php echo $question['id']; ?>"
                         name="<?php echo $question['id']; ?>"
                         value="<?php echo $question['weight']; ?>"
                         data-domain="<?php echo $domainData['domain_key']; ?>"
                         class="question-checkbox">
                  <label for="<?php echo $question['id']; ?>">
                    <span class="question-text"><?php echo htmlspecialchars($question['text']); ?></span>
                  </label>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endforeach; ?>

      <!-- Navigation Buttons -->
      <div class="form-navigation">
        <button type="button" id="prev-section" class="btn-secondary nav-button" style="display: none;">
          <i class="fa-solid fa-arrow-left"></i> Previous
        </button>
        <button type="button" id="next-section" class="btn-primary nav-button">
          Next <i class="fa-solid fa-arrow-right"></i>
        </button>
        <button type="submit" id="submit-form" class="btn-success nav-button" style="display: none;">
          <i class="fa-solid fa-chart-line"></i> Generate Qualification Report
        </button>
      </div>

      <!-- Reset Button -->
      <div class="form-reset">
        <button type="reset" class="btn-secondary btn-reset">
          <i class="fa-solid fa-rotate-left"></i> Reset All Answers
        </button>
      </div>
    </form>
  </div>

  <!-- Load DS Qualifier JavaScript -->
  <script src="js/ds-qualifier.js"></script>
</body>
</html>
