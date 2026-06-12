<!doctype html>
<html lang="en-us" class="pf-theme-dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Operational Sovereignty - Digital Sovereignty Assessment</title>
  <link rel="icon" type="image/svg+xml" href="favicon.svg">
  <link rel="alternate icon" href="favicon.ico">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/tab-dark.css">
  <link rel="stylesheet" href="css/patternfly.css">
  <link rel="stylesheet" href="css/patternfly-addons.css">

  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://kit.fontawesome.com/8a8c57f9cf.js" crossorigin="anonymous"></script>

  <link rel="stylesheet" href="css/domain-pages.css">
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

    $domainKey = 'Domain-3';
    $domain = $controlsData[$domainKey];

    // Get sub-pillar information if exists
    $hasSubPillar = isset($domain['section_2_source']);
    $subPillarKey = $hasSubPillar ? $domain['section_2_source'] : null;
    $subPillar = $hasSubPillar ? $controlsData[$subPillarKey] : null;
    ?>

    <div class="page-title">
      <h1>
        <i class="fa-solid fa-gears"></i> Operational Sovereignty
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
          ?>) covering internal capabilities and operational resilience, and the <strong><?php echo $domain['section_2_label']; ?></strong> addressing third-party provider oversight and contractual protections.
        </p>
      </div>
    <?php endif; ?>

    <!-- Core Operational Sovereignty Section -->
    <div style="margin-bottom: 3rem;">
      <h2 style="color: #9ec7fc; font-size: 1.8rem; margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 2px solid #0d60f8;">
        <i class="fa-solid fa-gears"></i> <?php echo isset($domain['section_1_label']) ? $domain['section_1_label'] : 'Core Capabilities'; ?>
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
        <i class="fa-solid fa-handshake"></i> <?php echo $domain['section_2_label']; ?>
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