<!doctype html>
<html lang="en-us" class="pf-theme-dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Full Maturity Assessment - Viewfinder</title>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/tab-dark.css">
  <link rel="stylesheet" href="css/patternfly.css">
  <link rel="stylesheet" href="css/patternfly-addons.css">

  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://kit.fontawesome.com/8a8c57f9cf.js" crossorigin="anonymous"></script>

  <style>
    body {
      background-color: #151515 !important;
      color: #ccc !important;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .pf-c-page__header {
      padding-top: 1.5rem;
      background: #1a1a1a;
      border-bottom: 1px solid #444;
    }

    .pf-c-page__header-tools button {
      margin-right: 1rem;
      background: #0d60f8;
      color: #fff;
      border: none;
      padding: 0.75rem 1.5rem;
      border-radius: 4px;
      cursor: pointer;
      font-size: 1rem;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .pf-c-page__header-tools button:hover {
      background: #0a4fc5;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(13, 96, 248, 0.4);
    }

    .container {
      max-width: 1400px;
      margin: 2rem auto;
      padding: 0 2rem;
      flex: 1;
    }

    .page-title {
      text-align: center;
      margin-bottom: 1rem;
    }

    .page-title h1 {
      color: #9ec7fc;
      font-size: 2rem;
      font-weight: 600;
      margin: 0 0 0.5rem 0;
    }

    .page-title p {
      color: #999;
      font-size: 1.1rem;
      margin: 0;
    }

    .assessment-selector-container {
      background: #2a2a2a;
      border: 1px solid #444;
      border-radius: 8px;
      padding: 2rem;
    }

    .maturity-landing-content {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr;
      gap: 2rem;
      margin-top: 1.5rem;
    }

    .maturity-selection-col,
    .maturity-weights-col,
    .maturity-levels-col {
      background: #1f1f1f;
      border: 1px solid #444;
      border-radius: 8px;
      padding: 1.5rem;
    }

    .maturity-selector-group {
      margin-bottom: 1rem;
    }

    .maturity-selector-group label {
      display: block;
      color: #9ec7fc;
      font-weight: 600;
      margin-bottom: 0.5rem;
    }

    .maturity-selector-group label i {
      color: #0d60f8;
      margin-right: 0.5rem;
    }

    .profile-dropdown {
      width: 100%;
      padding: 0.75rem;
      background: #2a2a2a;
      border: 1px solid #444;
      border-radius: 4px;
      color: #fff;
      font-size: 1rem;
      cursor: pointer;
      transition: all 0.2s ease;
    }

    .profile-dropdown:hover {
      border-color: #0d60f8;
    }

    .profile-dropdown:focus {
      outline: none;
      border-color: #0d60f8;
      box-shadow: 0 0 0 2px rgba(13, 96, 248, 0.2);
    }

    .maturity-profile-description {
      margin-top: 1.5rem;
      padding: 1rem;
      background: #1a1a1a;
      border-left: 3px solid #0d60f8;
      border-radius: 4px;
      color: #ccc;
      font-size: 0.9rem;
      line-height: 1.5;
    }

    .maturity-profile-description i {
      color: #0d60f8;
      margin-right: 0.5rem;
    }

    .weights-display h3,
    .maturity-levels-display h3 {
      color: #9ec7fc;
      font-size: 1.1rem;
      font-weight: 600;
      margin: 0 0 1.5rem 0;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .weights-display h3 i,
    .maturity-levels-display h3 i {
      color: #0d60f8;
    }

    .weight-item {
      display: flex;
      align-items: center;
      gap: 1rem;
      margin-bottom: 1rem;
    }

    .weight-domain {
      color: #ccc;
      font-size: 0.875rem;
      width: 160px;
      min-width: 160px;
      max-width: 160px;
      flex-shrink: 0;
      text-align: left;
      line-height: 1.3;
    }

    .weight-bar-container {
      flex: 1;
      height: 20px;
      background: #1a1a1a;
      border-radius: 4px;
      overflow: hidden;
      border: 1px solid #444;
    }

    .weight-bar {
      height: 100%;
      background: linear-gradient(90deg, #0d60f8 0%, #12bbd4 100%);
      transition: width 0.3s ease;
      border-radius: 3px;
    }

    .weight-bar.weight-high {
      background: linear-gradient(90deg, #f0ab00 0%, #c58c00 100%);
    }

    .weight-value {
      color: #fff;
      font-size: 0.875rem;
      font-weight: 600;
      min-width: 40px;
      text-align: right;
    }

    .weight-value.weight-high {
      color: #f0ab00;
    }

    .maturity-level-item {
      padding: 0.75rem;
      margin-bottom: 0.75rem;
      border-radius: 6px;
      border-left: 4px solid;
    }

    .maturity-level-item:last-child {
      margin-bottom: 0;
    }

    .maturity-level-item.level-initial {
      background: rgba(201, 25, 11, 0.15);
      border-color: #c9190b;
    }

    .maturity-level-item.level-managed {
      background: rgba(236, 122, 8, 0.15);
      border-color: #ec7a08;
    }

    .maturity-level-item.level-defined {
      background: rgba(255, 193, 7, 0.15);
      border-color: #ffc107;
    }

    .maturity-level-item.level-quantitative {
      background: rgba(139, 195, 74, 0.15);
      border-color: #8bc34a;
    }

    .maturity-level-item.level-optimizing {
      background: rgba(42, 170, 4, 0.15);
      border-color: #2aaa04;
    }

    .maturity-level-name {
      color: #fff;
      font-weight: 600;
      font-size: 0.95rem;
      margin-bottom: 0.25rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .maturity-level-name i {
      font-size: 0.875rem;
    }

    .maturity-level-range {
      color: #999;
      font-weight: 400;
      font-size: 0.85rem;
      margin-left: auto;
    }

    .maturity-level-desc {
      color: #fff;
      font-size: 0.85rem;
      margin-left: 1.875rem;
    }

    .start-assessment-btn {
      width: 100%;
      padding: 1rem 2rem;
      background: #0d60f8;
      color: #fff;
      border: none;
      border-radius: 4px;
      font-size: 1.1rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      margin-top: 1.5rem;
    }

    .start-assessment-btn:hover {
      background: #0a4fc5;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(13, 96, 248, 0.4);
    }

    .start-assessment-btn i {
      margin-right: 0.5rem;
    }

    @media (max-width: 1200px) {
      .maturity-landing-content {
        grid-template-columns: 1fr;
        gap: 1.5rem;
      }
    }
  </style>
</head>

<body>
  <header class="pf-c-page__header">
    <div class="pf-c-page__header-brand">
      <div class="pf-c-page__header-brand-toggle"></div>
    </div>
    <div class="pf-c-page__header-tools">
      <div class="widget">
        <a href="index.php"><button><i class="fa fa-home"></i> Home</button></a>
        <a href="import-results.php"><button><i class="fa fa-upload"></i> Import Results</button></a>
      </div>
    </div>
  </header>

  <div class="container">
    <div class="page-title">
      <h1>
        <i class="fa-solid fa-chart-line"></i> Full Maturity Assessment
      </h1>
    </div>

    <?php
    require_once __DIR__ . '/includes/Security.php';
    require_once __DIR__ . '/includes/Config.php';

    // Load LOB weights
    $lobWeights = require_once __DIR__ . '/lob-weights.php';
    $enabledProfiles = Config::getEnabledProfiles();
    $lobOptions = Config::LOB_OPTIONS;

    // Get domain names for default profile (Digital Sovereignty is now first)
    $defaultProfile = 'DigitalSovereignty';
    // For Digital Sovereignty, use hardcoded domain names
    $domainNames = ['Data Sovereignty', 'Technical Sovereignty', 'Operational Sovereignty', 'Assurance Sovereignty', 'Open Source', 'Executive Oversight', 'Managed Services'];
    ?>

    <div class="assessment-selector-container">
      <div class="maturity-landing-content">
        <!-- Left: Profile & LOB Selection -->
        <div class="maturity-selection-col">
          <div class="maturity-selector-group">
            <label for="maturity-profile-select">
              <i class="fa-solid fa-clipboard-check"></i> Assessment Type:
            </label>
            <select id="maturity-profile-select" class="profile-dropdown">
              <?php
              // Display in specific order: Digital Sovereignty first, then Security
              $profileOrder = ['DigitalSovereignty', 'Security'];
              foreach ($profileOrder as $profileKey):
                if (isset($enabledProfiles[$profileKey])):
                  $profileData = $enabledProfiles[$profileKey];
              ?>
                <option value="<?php echo htmlspecialchars($profileKey); ?>">
                  <?php echo htmlspecialchars($profileData['display_name']); ?>
                </option>
              <?php
                endif;
              endforeach;
              ?>
            </select>
          </div>

          <div class="maturity-selector-group" style="margin-top: 1rem;">
            <label for="maturity-lob-select">
              <i class="fa-solid fa-layer-group"></i> Your Industry:
            </label>
            <select id="maturity-lob-select" class="profile-dropdown">
              <option value="Balanced">Balanced (Equal Priority)</option>
              <?php foreach ($lobOptions as $lobKey => $lobName): ?>
                <?php if ($lobKey !== 'Other'): ?>
                  <option value="<?php echo htmlspecialchars($lobKey); ?>">
                    <?php echo htmlspecialchars($lobName); ?>
                  </option>
                <?php endif; ?>
              <?php endforeach; ?>
              <option value="Other">Other</option>
            </select>
          </div>

          <div class="maturity-profile-description" id="maturity-profile-description">
            <i class="fa-solid fa-balance-scale"></i>
            <span id="maturity-profile-description-text">
              Equal weighting across all security domains
            </span>
          </div>

          <div class="maturity-selector-group" style="margin-top: 1.5rem;">
            <label>
              <i class="fa-solid fa-shield-halved"></i> Compliance Frameworks (optional):
            </label>
            <div style="margin-top: 0.75rem; padding: 1rem; background: #1a1a1a; border: 1px solid #444; border-radius: 4px; display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.75rem;">
              <?php
              // Load compliance frameworks
              $jsonFrameworks = Security::loadJSON(__DIR__ . '/compliance.json');
              foreach ($jsonFrameworks as $framework):
              ?>
                <label for="framework-<?php echo htmlspecialchars($framework['name']); ?>"
                       style="display: flex; align-items: center; color: #ccc; font-size: 0.9rem; font-weight: normal; cursor: pointer;">
                  <input type="checkbox"
                         id="framework-<?php echo htmlspecialchars($framework['name']); ?>"
                         name="framework[]"
                         value="<?php echo htmlspecialchars($framework['name']); ?>"
                         style="margin-right: 0.5rem; cursor: pointer;">
                  <span><?php echo htmlspecialchars($framework['name']); ?></span>
                </label>
              <?php endforeach; ?>
            </div>
          </div>

          <button id="maturity-start-btn" class="start-assessment-btn">
            <i class="fa-solid fa-rocket"></i> Start Assessment
          </button>
        </div>

        <!-- Middle: Domain Weights -->
        <div class="maturity-weights-col">
          <div class="weights-display">
            <h3>
              <i class="fa-solid fa-chart-bar"></i> Domain Weighting
            </h3>
            <div id="maturity-weights-container">
              <?php foreach ($domainNames as $domain): ?>
                <div class="weight-item">
                  <span class="weight-domain"><?php echo htmlspecialchars($domain); ?></span>
                  <div class="weight-bar-container">
                    <div class="weight-bar" id="maturity-weight-bar-<?php echo htmlspecialchars(str_replace(' ', '-', $domain)); ?>" style="width: 50%;"></div>
                  </div>
                  <span class="weight-value" id="maturity-weight-value-<?php echo htmlspecialchars(str_replace(' ', '-', $domain)); ?>">1.0×</span>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>

        <!-- Right: CMMI Levels -->
        <div class="maturity-levels-col">
          <div class="maturity-levels-display">
            <h3>
              <i class="fa-solid fa-layer-group"></i> CMMI Maturity Levels
            </h3>

            <div class="maturity-level-item level-initial">
              <div class="maturity-level-name">
                <i class="fa-solid fa-circle-exclamation"></i>
                Initial
                <span class="maturity-level-range">(0-20%)</span>
              </div>
              <div class="maturity-level-desc">Unpredictable, reactive</div>
            </div>

            <div class="maturity-level-item level-managed">
              <div class="maturity-level-name">
                <i class="fa-solid fa-clipboard-list"></i>
                Managed
                <span class="maturity-level-range">(21-40%)</span>
              </div>
              <div class="maturity-level-desc">Planned, basic controls</div>
            </div>

            <div class="maturity-level-item level-defined">
              <div class="maturity-level-name">
                <i class="fa-solid fa-sitemap"></i>
                Defined
                <span class="maturity-level-range">(41-60%)</span>
              </div>
              <div class="maturity-level-desc">Standardized, documented</div>
            </div>

            <div class="maturity-level-item level-quantitative">
              <div class="maturity-level-name">
                <i class="fa-solid fa-chart-line"></i>
                Quantitatively Managed
                <span class="maturity-level-range">(61-80%)</span>
              </div>
              <div class="maturity-level-desc">Measured, controlled</div>
            </div>

            <div class="maturity-level-item level-optimizing">
              <div class="maturity-level-name">
                <i class="fa-solid fa-rocket"></i>
                Optimizing
                <span class="maturity-level-range">(81-100%)</span>
              </div>
              <div class="maturity-level-desc">Continuous improvement</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script type="text/javascript">
  (function() {
    // Embed LOB weights data
    const lobWeightsData = <?php echo json_encode($lobWeights); ?>;

    // Profile to control file mapping
    const profileControlsMap = {
      'Security': <?php echo json_encode($domainNames); ?>,
      'DigitalSovereignty': ['Data Sovereignty', 'Technical Sovereignty', 'Operational Sovereignty', 'Assurance Sovereignty', 'Open Source', 'Executive Oversight', 'Managed Services']
    };

    // Icons for each LOB
    const lobIcons = {
      'Balanced': 'fa-balance-scale',
      'Finance': 'fa-building-columns',
      'Healthcare': 'fa-heart-pulse',
      'Government': 'fa-landmark',
      'Manufacturing': 'fa-industry',
      'Telecommunications': 'fa-tower-cell',
      'Other': 'fa-building'
    };

    // Update weights display based on selected profile and LOB
    function updateMaturityWeights() {
      const profileSelect = document.getElementById('maturity-profile-select');
      const lobSelect = document.getElementById('maturity-lob-select');
      const descriptionText = document.getElementById('maturity-profile-description-text');
      const descriptionContainer = document.getElementById('maturity-profile-description');

      if (!profileSelect || !lobSelect) return;

      const selectedProfile = profileSelect.value;
      const selectedLob = lobSelect.value;

      // Get weights for this profile and LOB
      const profileWeights = lobWeightsData[selectedProfile];
      if (!profileWeights || !profileWeights[selectedLob]) {
        console.error('No weights found for profile:', selectedProfile, 'LOB:', selectedLob);
        return;
      }

      const lobData = profileWeights[selectedLob];
      const weights = lobData.weights;

      // Update profile description
      const icon = lobIcons[selectedLob] || 'fa-building';
      descriptionContainer.innerHTML = '<i class="fa-solid ' + icon + '"></i><span id="maturity-profile-description-text">' +
                                       lobData.description + '</span>';

      // Get domain names for this profile
      const domainNames = profileControlsMap[selectedProfile] || [];

      // Update weight bars
      domainNames.forEach(function(domain) {
        const weight = weights[domain] || 1.0;
        const barId = 'maturity-weight-bar-' + domain.replace(/\s+/g, '-');
        const valueId = 'maturity-weight-value-' + domain.replace(/\s+/g, '-');

        const barElement = document.getElementById(barId);
        const valueElement = document.getElementById(valueId);

        if (barElement && valueElement) {
          // Calculate width percentage (1.0 = 50%, 2.0 = 100%)
          const widthPercent = ((weight - 1.0) / 1.0) * 50 + 50;
          barElement.style.width = widthPercent + '%';

          // Add/remove high weight class
          if (weight >= 1.5) {
            barElement.classList.add('weight-high');
            valueElement.classList.add('weight-high');
          } else {
            barElement.classList.remove('weight-high');
            valueElement.classList.remove('weight-high');
          }

          // Update weight value text
          valueElement.textContent = weight.toFixed(1) + '×';
        }
      });
    }

    // Rebuild weight display when profile changes (domains differ between profiles)
    function rebuildWeightDisplay() {
      const profileSelect = document.getElementById('maturity-profile-select');
      const lobSelect = document.getElementById('maturity-lob-select');
      const container = document.getElementById('maturity-weights-container');

      if (!profileSelect || !container) return;

      const selectedProfile = profileSelect.value;
      const selectedLob = lobSelect.value;
      const domainNames = profileControlsMap[selectedProfile] || [];

      // Get weights for initial display
      const profileWeights = lobWeightsData[selectedProfile];
      const weights = profileWeights && profileWeights[selectedLob] ? profileWeights[selectedLob].weights : {};

      // Clear and rebuild
      container.innerHTML = '';

      domainNames.forEach(function(domain) {
        const weight = weights[domain] || 1.0;
        const domainId = domain.replace(/\s+/g, '-');
        const widthPercent = ((weight - 1.0) / 1.0) * 50 + 50;
        const highWeightClass = weight >= 1.5 ? 'weight-high' : '';

        const weightItem = document.createElement('div');
        weightItem.className = 'weight-item';
        weightItem.innerHTML = `
          <span class="weight-domain">${domain}</span>
          <div class="weight-bar-container">
            <div class="weight-bar ${highWeightClass}" id="maturity-weight-bar-${domainId}" style="width: ${widthPercent}%;"></div>
          </div>
          <span class="weight-value ${highWeightClass}" id="maturity-weight-value-${domainId}">${weight.toFixed(1)}×</span>
        `;

        container.appendChild(weightItem);
      });
    }

    // Handle "Start Assessment" button
    function handleStartAssessment() {
      const profileSelect = document.getElementById('maturity-profile-select');
      const lobSelect = document.getElementById('maturity-lob-select');

      if (!profileSelect || !lobSelect) return;

      const selectedProfile = profileSelect.value;
      const selectedLob = lobSelect.value;

      // Collect selected frameworks
      const frameworkCheckboxes = document.querySelectorAll('input[name="framework[]"]:checked');
      const selectedFrameworks = Array.from(frameworkCheckboxes).map(cb => cb.value);

      // Build URL with parameters
      let url = 'index.php?profile=' + encodeURIComponent(selectedProfile) +
                '&lob=' + encodeURIComponent(selectedLob);

      // Add framework parameters
      selectedFrameworks.forEach(function(framework) {
        url += '&framework[]=' + encodeURIComponent(framework);
      });

      // Navigate to assessment
      window.location.href = url;
    }

    // Attach event listeners when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
      const profileSelect = document.getElementById('maturity-profile-select');
      const lobSelect = document.getElementById('maturity-lob-select');
      const startBtn = document.getElementById('maturity-start-btn');

      if (profileSelect) {
        profileSelect.addEventListener('change', function() {
          rebuildWeightDisplay();
        });
      }

      if (lobSelect) {
        lobSelect.addEventListener('change', updateMaturityWeights);
      }

      if (startBtn) {
        startBtn.addEventListener('click', handleStartAssessment);
      }

      // Initial display
      if (profileSelect && lobSelect) {
        rebuildWeightDisplay();
      }
    });
  })();
  </script>
</body>
</html>
