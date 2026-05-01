<!doctype html>
<html lang="en-us" class="pf-theme-dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Technical Sovereignty - Digital Sovereignty Assessment</title>
  <link rel="icon" type="image/svg+xml" href="favicon.svg">
  <link rel="alternate icon" href="favicon.ico">
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
      padding-bottom: 1.5rem;
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
      margin-bottom: 2rem;
    }

    .page-title h1 {
      color: #9ec7fc;
      font-size: 2.5rem;
      font-weight: 600;
      margin: 0 0 1rem 0;
    }

    .page-title .domain-overview {
      color: #ccc;
      font-size: 1rem;
      line-height: 1.6;
      max-width: 1000px;
      margin: 0 auto;
      text-align: left;
      padding: 1.5rem;
      background: #1f1f1f;
      border: 1px solid #444;
      border-radius: 8px;
      border-left: 4px solid #0d60f8;
    }

    .capabilities-grid {
      display: grid;
      gap: 2rem;
      margin-top: 2rem;
    }

    .capability-card {
      background: #1f1f1f;
      border: 1px solid #444;
      border-radius: 8px;
      padding: 2rem;
      transition: all 0.3s ease;
    }

    .capability-card:hover {
      border-color: #0d60f8;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(13, 96, 248, 0.2);
    }

    .capability-header {
      display: flex;
      align-items: center;
      gap: 1rem;
      margin-bottom: 1.5rem;
      padding-bottom: 1rem;
      border-bottom: 2px solid #333;
    }

    .capability-number {
      background: #0d60f8;
      color: #fff;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: 1.2rem;
      flex-shrink: 0;
    }

    .capability-title {
      color: #9ec7fc;
      font-size: 1.5rem;
      font-weight: 600;
      margin: 0;
      flex: 1;
    }

    .capability-badge {
      padding: 0.4rem 1rem;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .badge-foundation {
      background: rgba(42, 170, 4, 0.2);
      color: #2aaa04;
      border: 1px solid #2aaa04;
    }

    .badge-strategic {
      background: rgba(236, 122, 8, 0.2);
      color: #ec7a08;
      border: 1px solid #ec7a08;
    }

    .badge-advanced {
      background: rgba(201, 25, 11, 0.2);
      color: #c9190b;
      border: 1px solid #c9190b;
    }

    .capability-content h3 {
      color: #9ec7fc;
      font-size: 1.1rem;
      font-weight: 600;
      margin: 0 0 1rem 0;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .capability-content h3 i {
      color: #0d60f8;
    }

    .capability-content ul {
      list-style: disc;
      margin: 0.5rem 0 0 1.5rem;
      padding: 0;
      color: #ccc;
      line-height: 1.8;
    }

    .capability-content ul li {
      margin-bottom: 0.5rem;
    }

    .capability-content strong {
      color: #fff;
      font-weight: 600;
    }

    .navigation-buttons {
      display: flex;
      gap: 1rem;
      margin-bottom: 2rem;
    }

    .back-button {
      display: inline-block;
      padding: 0.75rem 1.5rem;
      background: #2a2a2a;
      color: #9ec7fc;
      border: 1px solid #444;
      border-radius: 4px;
      text-decoration: none;
      transition: all 0.3s ease;
      font-weight: 500;
    }

    .back-button:hover {
      background: #333;
      border-color: #0d60f8;
      color: #fff;
      transform: translateX(-4px);
    }

    .back-button.primary {
      background: #0d60f8;
      color: #fff;
      border-color: #0d60f8;
    }

    .back-button.primary:hover {
      background: #0a4fc5;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(13, 96, 248, 0.4);
    }

    .back-button i {
      margin-right: 0.5rem;
    }

    /* Mobile Responsive Styles */
    @media (max-width: 768px) {
      .container {
        padding: 0 1rem;
        margin: 1rem auto;
      }

      .pf-c-page__header {
        padding-top: 1rem;
        padding-bottom: 1rem;
      }

      .pf-c-page__header-tools {
        padding: 0 0.5rem;
      }

      .pf-c-page__header-tools .widget {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        justify-content: center;
      }

      .pf-c-page__header-tools button {
        margin-right: 0;
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        white-space: nowrap;
      }

      .pf-c-page__header-tools button i {
        display: none;
      }

      .navigation-buttons {
        flex-direction: column;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
      }

      .back-button {
        width: 100%;
        text-align: center;
        padding: 0.65rem 1rem;
        font-size: 0.9rem;
      }

      .page-title h1 {
        font-size: 1.75rem;
        line-height: 1.3;
      }

      .page-title .domain-overview {
        padding: 1rem;
        font-size: 0.9rem;
      }

      .capabilities-grid {
        gap: 1.25rem;
        margin-top: 1.5rem;
      }

      .capability-card {
        padding: 1.25rem;
      }

      .capability-card:hover {
        transform: none;
      }

      .capability-header {
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
      }

      .capability-number {
        width: 35px;
        height: 35px;
        font-size: 1rem;
      }

      .capability-title {
        font-size: 1.2rem;
        flex: 1 1 100%;
        order: 2;
      }

      .capability-badge {
        order: 1;
        margin-left: auto;
        padding: 0.3rem 0.75rem;
        font-size: 0.75rem;
      }

      .capability-content h3 {
        font-size: 1rem;
      }

      .capability-content ul {
        margin-left: 1rem;
        font-size: 0.9rem;
        line-height: 1.6;
      }

      .capability-content ul li {
        margin-bottom: 0.4rem;
      }
    }

    @media (max-width: 480px) {
      .page-title h1 {
        font-size: 1.5rem;
      }

      .capability-title {
        font-size: 1.1rem;
      }

      .capability-card {
        padding: 1rem;
      }

      .capability-content {
        font-size: 0.85rem;
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
        <a href="maturity-assessment-landing.php"><button><i class="fa fa-chart-line"></i> Full Assessment</button></a>
        <a href="facilitator-guide.php" target="_blank"><button><i class="fa fa-book-open"></i> Enablement Guides</button></a>
      </div>
    </div>
  </header>

  <div class="container">
    <div class="navigation-buttons">
      <a href="dig-sov-domains.php" class="back-button primary">
        <i class="fa fa-th-large"></i> All Domains
      </a>
      <a href="maturity-assessment-landing.php" class="back-button">
        <i class="fa fa-chart-line"></i> Full Assessment
      </a>
    </div>

    <?php
    require_once __DIR__ . '/includes/Security.php';

    // Load Digital Sovereignty controls
    $controlsFile = __DIR__ . '/controls-DigitalSovereignty.json';
    $controlsData = json_decode(file_get_contents($controlsFile), true);

    $domainKey = 'Domain-2';
    $domain = $controlsData[$domainKey];

    // Get sub-pillar information if exists
    $hasSubPillar = isset($domain['section_2_source']);
    $subPillarKey = $hasSubPillar ? $domain['section_2_source'] : null;
    $subPillar = $hasSubPillar ? $controlsData[$subPillarKey] : null;
    ?>

    <div class="page-title">
      <h1>
        <i class="fa-solid fa-microchip"></i> Technical Sovereignty
      </h1>
      <div class="domain-overview">
        <?php echo $domain['overview']; ?>
      </div>
    </div>

    <?php if ($hasSubPillar): ?>
      <div style="background: #1f1f1f; border: 1px solid #444; border-radius: 8px; padding: 1.5rem; margin-bottom: 2rem; border-left: 4px solid #12bbd4;">
        <h2 style="color: #9ec7fc; font-size: 1.3rem; margin: 0 0 0.5rem 0;">
          <i class="fa-solid fa-layer-group"></i> Two-Part Structure
        </h2>
        <p style="color: #ccc; margin: 0; line-height: 1.6;">
          This domain is organized into <strong><?php echo $domain['section_1_label']; ?></strong> (capabilities 1-<?php
          $coreCount = 0;
          foreach ($domain as $key => $value) {
            if (is_numeric($key)) $coreCount++;
          }
          echo $coreCount;
          ?>) covering platform control and vendor independence, and the <strong><?php echo $domain['section_2_label']; ?></strong> addressing transparency and community-driven innovation.
        </p>
      </div>
    <?php endif; ?>

    <!-- Core Technical Sovereignty Section -->
    <div style="margin-bottom: 3rem;">
      <h2 style="color: #9ec7fc; font-size: 1.8rem; margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 2px solid #0d60f8;">
        <i class="fa-solid fa-microchip"></i> <?php echo isset($domain['section_1_label']) ? $domain['section_1_label'] : 'Core Capabilities'; ?>
      </h2>
      <div class="capabilities-grid">
        <?php
        // Display core domain capabilities
        $capabilityNum = 1;
        foreach ($domain as $key => $value) {
          if (!is_numeric($key)) continue;

          $capabilityTitle = $domain[$key];
          $capabilitySummary = $domain[$key . '-summary'];
          $capabilityTier = strtolower($domain[$key . '-tier']);

          $badgeClass = 'badge-foundation';
          if ($capabilityTier === 'strategic') $badgeClass = 'badge-strategic';
          elseif ($capabilityTier === 'advanced') $badgeClass = 'badge-advanced';
        ?>
        <div class="capability-card">
          <div class="capability-header">
            <div class="capability-number"><?php echo $capabilityNum; ?></div>
            <h2 class="capability-title"><?php echo htmlspecialchars($capabilityTitle); ?></h2>
            <span class="capability-badge <?php echo $badgeClass; ?>"><?php echo ucfirst($capabilityTier); ?></span>
          </div>
          <div class="capability-content">
            <h3><i class="fa-solid fa-lightbulb"></i> Points to Consider</h3>
            <?php echo $capabilitySummary; ?>
          </div>
        </div>
        <?php
          $capabilityNum++;
        }
        ?>
      </div>
    </div>

    <?php if ($hasSubPillar && $subPillar): ?>
    <!-- Sub-Pillar Section -->
    <div style="margin-bottom: 3rem;">
      <h2 style="color: #9ec7fc; font-size: 1.8rem; margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 2px solid #12bbd4;">
        <i class="fa-solid fa-code-branch"></i> <?php echo $domain['section_2_label']; ?>
      </h2>
      <div style="background: #1f1f1f; border: 1px solid #444; border-radius: 8px; padding: 1.5rem; margin-bottom: 1.5rem; border-left: 4px solid #12bbd4;">
        <div style="color: #ccc; line-height: 1.6;">
          <?php echo $subPillar['overview']; ?>
        </div>
      </div>
      <div class="capabilities-grid">
        <?php
        // Display sub-pillar capabilities
        $subCapNum = 1;
        foreach ($subPillar as $key => $value) {
          if (!is_numeric($key)) continue;

          $capabilityTitle = $subPillar[$key];
          $capabilitySummary = $subPillar[$key . '-summary'];
          $capabilityTier = strtolower($subPillar[$key . '-tier']);

          $badgeClass = 'badge-foundation';
          if ($capabilityTier === 'strategic') $badgeClass = 'badge-strategic';
          elseif ($capabilityTier === 'advanced') $badgeClass = 'badge-advanced';
        ?>
        <div class="capability-card">
          <div class="capability-header">
            <div class="capability-number"><?php echo $subCapNum; ?></div>
            <h2 class="capability-title"><?php echo htmlspecialchars($capabilityTitle); ?></h2>
            <span class="capability-badge <?php echo $badgeClass; ?>"><?php echo ucfirst($capabilityTier); ?></span>
          </div>
          <div class="capability-content">
            <h3><i class="fa-solid fa-lightbulb"></i> Points to Consider</h3>
            <?php echo $capabilitySummary; ?>
          </div>
        </div>
        <?php
          $subCapNum++;
        }
        ?>
      </div>
    </div>
    <?php endif; ?>

  </div>
  </div>
</body>
</html>