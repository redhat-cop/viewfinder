<!doctype html>
<html lang="en-us" class="pf-theme-dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Digital Sovereignty Readiness Assessment - Viewfinder</title>

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
    .pf-c-page__header {
      padding-top: 1.5rem;
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
    </div>

    <div class="widget">
      <a href="../index.php"><button><i class="fa-solid fa-home"></i> Home</button></a>
      <a href="../import-results.php"><button style="margin-left: 1rem;"><i class="fa-solid fa-upload"></i> Import Results</button></a>
      <a href="../quiz/"><button style="margin-left: 1rem;">Take Quiz</button></a>
    </div>
  </header>

  <div class="container">
    <?php
    // Load questions configuration
    $questions = require_once 'config.php';
    $domainNames = array_keys($questions);

    // Load profiles
    $profiles = require_once __DIR__ . '/profiles.php';

    // Get selected profile from URL parameter (default: balanced)
    $selectedProfile = isset($_GET['profile']) ? $_GET['profile'] : 'balanced';

    // Validate profile exists
    if (!isset($profiles[$selectedProfile])) {
        $selectedProfile = 'balanced';
    }

    $profileData = $profiles[$selectedProfile];

    // For custom profile, handle weight parameters
    $customWeights = [];
    if ($selectedProfile === 'custom') {
        foreach ($questions as $domainName => $domainData) {
            $paramName = 'weight_' . str_replace(' ', '_', $domainName);
            if (isset($_GET[$paramName])) {
                $weight = floatval($_GET[$paramName]);
                $customWeights[$domainName] = max(1.0, min(2.0, $weight));
            } else {
                $customWeights[$domainName] = 1.0;
            }
        }
    }

    // Check if we should show the form directly (profile selected)
    $showForm = isset($_GET['start']) && $_GET['start'] === '1';
    ?>

    <div class="qualifier-header">
      <h1><i class="fa-solid fa-clipboard-check"></i> Digital Sovereignty Readiness Assessment</h1>
      <p class="subtitle">Quick 10-15 minute assessment to evaluate digital sovereignty readiness</p>
    </div>

    <!-- Landing Page Section -->
    <div id="landing-section" style="<?php echo $showForm ? 'display: none;' : ''; ?>">
      <div class="qualifier-intro">
        <h3><i class="fa-solid fa-info-circle"></i> About This Tool</h3>
        <p>This lightweight assessment tool helps evaluate your organization's digital sovereignty readiness.
           Answer the questions below based on your current practices and requirements.</p>
        <ul>
          <li><strong>Time Required:</strong> 10-15 minutes</li>
          <li><strong>Questions:</strong> 21 questions across 7 domains (Yes / No / Don't Know)</li>
          <li><strong>Output:</strong> Readiness score with recommended next steps</li>
          <li><strong>Don't Know?</strong> Questions marked "Don't Know" will appear as "Questions to Research"</li>
        </ul>
      </div>

      <div class="landing-card-content" style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.5rem; margin: 2rem 0;">
        <!-- Left Column: Profile Selector -->
        <div class="landing-card-left">
          <div class="landing-profile-selector">
            <h3><i class="fa-solid fa-layer-group"></i> Select Your Industry/Context</h3>
            <select id="profile-select" class="profile-dropdown">
              <?php foreach ($profiles as $profileKey => $profile): ?>
                <option value="<?php echo htmlspecialchars($profileKey); ?>"
                        data-description="<?php echo htmlspecialchars($profile['description']); ?>"
                        data-icon="<?php echo htmlspecialchars($profile['icon']); ?>"
                        <?php echo $profileKey === 'balanced' ? 'selected' : ''; ?>>
                  <?php echo htmlspecialchars($profile['name']); ?>
                </option>
              <?php endforeach; ?>
            </select>
            <div class="profile-description" id="profile-description">
              <i class="fa-solid fa-balance-scale"></i>
              <span id="profile-description-text">
                <?php echo htmlspecialchars($profiles['balanced']['description']); ?>
              </span>
            </div>
          </div>

          <!-- Custom Weights Controls -->
          <div class="custom-weights-section" id="custom-weights-section">
            <h4><i class="fa-solid fa-sliders"></i> Customize Domain Weights</h4>
            <p style="font-size: 0.8rem; color: #999; margin-bottom: 1rem; text-align: center;">
              Adjust weights from 1.0× (standard) to 2.0× (critical priority)
            </p>
            <?php foreach ($domainNames as $domain): ?>
              <div class="custom-weight-control">
                <label for="slider-<?php echo htmlspecialchars(str_replace(' ', '-', $domain)); ?>">
                  <?php echo htmlspecialchars($domain); ?>
                </label>
                <input
                  type="range"
                  id="slider-<?php echo htmlspecialchars(str_replace(' ', '-', $domain)); ?>"
                  name="weight-<?php echo htmlspecialchars(str_replace(' ', '-', $domain)); ?>"
                  min="1.0"
                  max="2.0"
                  step="0.5"
                  value="1.0"
                  data-domain="<?php echo htmlspecialchars($domain); ?>"
                >
                <span class="weight-slider-value" id="slider-value-<?php echo htmlspecialchars(str_replace(' ', '-', $domain)); ?>">1.0×</span>
              </div>
            <?php endforeach; ?>
          </div>

          <div style="margin-top: 1.5rem;">
            <button id="start-assessment-btn" class="btn-primary" style="width: 100%; padding: 1rem; font-size: 1.1rem;">
              <i class="fa-solid fa-rocket"></i> Start Assessment
            </button>
          </div>
        </div>

        <!-- Middle Column: Domain Weights Display -->
        <div class="landing-card-right">
          <div class="weights-display">
            <h3>
              <i class="fa-solid fa-chart-bar"></i> Domain Weighting - <span id="profile-name-display">Balanced</span>
            </h3>
            <div id="weights-container">
              <?php foreach ($domainNames as $domain): ?>
                <div class="weight-item">
                  <span class="weight-domain"><?php echo htmlspecialchars($domain); ?></span>
                  <div class="weight-bar-container">
                    <div class="weight-bar" id="weight-bar-<?php echo htmlspecialchars(str_replace(' ', '-', $domain)); ?>" style="width: 50%;"></div>
                  </div>
                  <span class="weight-value" id="weight-value-<?php echo htmlspecialchars(str_replace(' ', '-', $domain)); ?>">1.0×</span>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>

        <!-- Right Column: 5-Level Maturity Model -->
        <div class="landing-card-right">
          <div class="maturity-levels-display">
            <h3>
              <i class="fa-solid fa-layer-group"></i> 5-Level Maturity Model
            </h3>

            <div class="maturity-level-item level-initial">
              <div class="maturity-level-name">
                <i class="fa-solid fa-circle-exclamation"></i>
                Initial
                <span class="maturity-level-range">(0-20%)</span>
              </div>
              <div class="maturity-level-desc">Unpredictable, poorly controlled, reactive processes</div>
            </div>

            <div class="maturity-level-item level-managed">
              <div class="maturity-level-name">
                <i class="fa-solid fa-clipboard-list"></i>
                Managed
                <span class="maturity-level-range">(21-40%)</span>
              </div>
              <div class="maturity-level-desc">Projects planned and executed per policy, basic controls in place</div>
            </div>

            <div class="maturity-level-item level-defined">
              <div class="maturity-level-name">
                <i class="fa-solid fa-sitemap"></i>
                Defined
                <span class="maturity-level-range">(41-60%)</span>
              </div>
              <div class="maturity-level-desc">Standardized, documented, and proactive processes organization-wide</div>
            </div>

            <div class="maturity-level-item level-quantitative">
              <div class="maturity-level-name">
                <i class="fa-solid fa-chart-line"></i>
                Quantitatively Managed
                <span class="maturity-level-range">(61-80%)</span>
              </div>
              <div class="maturity-level-desc">Measured and controlled using statistical techniques and data</div>
            </div>

            <div class="maturity-level-item level-optimizing">
              <div class="maturity-level-name">
                <i class="fa-solid fa-rocket"></i>
                Optimizing
                <span class="maturity-level-range">(81-100%)</span>
              </div>
              <div class="maturity-level-desc">Continuous improvement and innovation-focused processes</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Assessment Form Section -->
    <div id="form-section" style="<?php echo $showForm ? '' : 'display: none;'; ?>">
      <!-- Show selected profile -->
      <div style="margin: 1.5rem 0; padding: 1rem; background: #1a1a1a; border-radius: 4px; border-left: 3px solid #0d60f8; display: flex; justify-content: space-between; align-items: center;">
        <div>
          <i class="fa-solid <?php echo htmlspecialchars($profileData['icon']); ?>" style="color: #0d60f8; margin-right: 0.5rem;"></i>
          <strong style="color: #9ec7fc;">Profile:</strong>
          <span style="color: #fff;"><?php echo htmlspecialchars($profileData['name']); ?></span>
        </div>
        <a href="index.php" class="btn-secondary" style="padding: 0.5rem 1rem; text-decoration: none; display: inline-block;">
          <i class="fa-solid fa-arrow-left"></i> Change Profile
        </a>
      </div>

    <form action="results.php" method="POST" id="qualifier-form">
      <!-- Hidden field for profile -->
      <input type="hidden" name="profile" value="<?php echo htmlspecialchars($selectedProfile); ?>">

      <!-- For custom profiles, add weight fields -->
      <?php if ($selectedProfile === 'custom'): ?>
        <?php foreach ($customWeights as $domain => $weight): ?>
          <input type="hidden"
                 name="custom_weight_<?php echo str_replace(' ', '_', $domain); ?>"
                 value="<?php echo $weight; ?>">
        <?php endforeach; ?>
      <?php endif; ?>
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
          </div>

          <div class="questions-list">
            <?php foreach ($domainData['questions'] as $question): ?>
              <div class="question-item">
                <div class="question-header">
                  <span class="question-text">
                    <?php echo htmlspecialchars($question['text']); ?>
                    <?php if (!empty($question['tooltip'])): ?>
                      <span class="tooltip-icon" data-tooltip="<?php echo htmlspecialchars($question['tooltip']); ?>">
                        <i class="fa-solid fa-circle-info"></i>
                      </span>
                    <?php endif; ?>
                  </span>
                </div>
                <div class="button-group" data-domain="<?php echo $domainData['domain_key']; ?>">
                  <input type="radio"
                         id="<?php echo $question['id']; ?>-yes"
                         name="<?php echo $question['id']; ?>"
                         value="<?php echo $question['weight']; ?>"
                         class="question-radio">
                  <label for="<?php echo $question['id']; ?>-yes" class="btn-option btn-yes">
                    <i class="fa-solid fa-check"></i> Yes
                  </label>

                  <input type="radio"
                         id="<?php echo $question['id']; ?>-no"
                         name="<?php echo $question['id']; ?>"
                         value="0"
                         class="question-radio">
                  <label for="<?php echo $question['id']; ?>-no" class="btn-option btn-no">
                    <i class="fa-solid fa-xmark"></i> No
                  </label>

                  <input type="radio"
                         id="<?php echo $question['id']; ?>-unknown"
                         name="<?php echo $question['id']; ?>"
                         value="unknown"
                         class="question-radio">
                  <label for="<?php echo $question['id']; ?>-unknown" class="btn-option btn-unknown">
                    <i class="fa-solid fa-question"></i> Don't Know
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
    </div> <!-- Close form-section -->
  </div> <!-- Close container -->

  <!-- Landing Page JavaScript -->
  <script>
    // Profile data embedded from PHP
    const profilesData = <?php echo json_encode($profiles); ?>;
    const domainNames = <?php echo json_encode($domainNames); ?>;

    // Custom weights storage
    let customWeights = {};

    // Initialize custom weights with defaults
    domainNames.forEach(domain => {
      customWeights[domain] = 1.0;
    });

    // Update weights display when profile changes
    function updateWeightsDisplay(profileKey, useCustomWeights = false) {
      const profile = profilesData[profileKey];
      const weights = useCustomWeights ? customWeights : profile.weights;

      // Update profile description
      const descIcon = document.querySelector('.profile-description i');
      descIcon.className = 'fa-solid ' + profile.icon;
      document.getElementById('profile-description-text').textContent = profile.description;

      // Update profile name in Domain Weighting header
      document.getElementById('profile-name-display').textContent = profile.name;

      // Show/hide custom weights section
      const customSection = document.getElementById('custom-weights-section');
      if (profileKey === 'custom') {
        customSection.classList.add('active');
      } else {
        customSection.classList.remove('active');
      }

      // Update each domain weight
      Object.keys(weights).forEach(domain => {
        const weight = weights[domain];
        const domainId = domain.replace(/ /g, '-');
        const barElement = document.getElementById('weight-bar-' + domainId);
        const valueElement = document.getElementById('weight-value-' + domainId);

        if (barElement && valueElement) {
          // Calculate percentage (max weight is 2.0 = 100%)
          const percentage = (weight / 2.0) * 100;
          barElement.style.width = percentage + '%';

          // Add critical class for weights >= 1.5
          if (weight >= 1.5) {
            barElement.classList.add('critical');
          } else {
            barElement.classList.remove('critical');
          }

          valueElement.textContent = weight.toFixed(1) + '×';
        }

        // Update slider if in custom mode
        if (profileKey === 'custom') {
          const slider = document.getElementById('slider-' + domainId);
          if (slider) {
            slider.value = weight;
            const sliderValue = document.getElementById('slider-value-' + domainId);
            if (sliderValue) {
              sliderValue.textContent = weight.toFixed(1) + '×';
            }
          }
        }
      });
    }

    // Handle slider changes
    domainNames.forEach(domain => {
      const domainId = domain.replace(/ /g, '-');
      const slider = document.getElementById('slider-' + domainId);

      if (slider) {
        slider.addEventListener('input', function() {
          const weight = parseFloat(this.value);
          customWeights[domain] = weight;

          // Update slider value display
          const sliderValue = document.getElementById('slider-value-' + domainId);
          if (sliderValue) {
            sliderValue.textContent = weight.toFixed(1) + '×';
          }

          // Update weight visualization
          updateWeightsDisplay('custom', true);
        });
      }
    });

    // Initialize with default profile
    updateWeightsDisplay('balanced');

    // Listen for profile selection changes
    document.getElementById('profile-select').addEventListener('change', function() {
      updateWeightsDisplay(this.value);
    });

    // Handle start assessment button
    document.getElementById('start-assessment-btn').addEventListener('click', function() {
      const selectedProfile = document.getElementById('profile-select').value;

      // Build URL with profile
      let url = window.location.pathname + '?profile=' + encodeURIComponent(selectedProfile) + '&start=1';

      // If custom profile, add custom weights as URL parameters
      if (selectedProfile === 'custom') {
        domainNames.forEach(domain => {
          const weight = customWeights[domain];
          url += '&weight_' + encodeURIComponent(domain.replace(/ /g, '_')) + '=' + weight;
        });
      }

      window.location.href = url;
    });
  </script>

  <!-- Load DS Qualifier JavaScript -->
  <script src="js/ds-qualifier.js"></script>
</body>
</html>
