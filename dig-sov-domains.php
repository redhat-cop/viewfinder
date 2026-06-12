<!doctype html>
<html lang="en-us" class="pf-theme-dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Digital Sovereignty Domains</title>
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
    <div class="page-title">
      <h1>
        <i class="fa-solid fa-shield-halved"></i> Digital Sovereignty Domains
      </h1>
      <p>Explore the five key domains that define comprehensive digital sovereignty—including specialized sub-pillars for Open Source and Managed Services</p>
    </div>

    <div class="intro-section">
      <h2><i class="fa-solid fa-info-circle"></i> About Digital Sovereignty</h2>
      <p>
        Digital sovereignty encompasses an organization's ability to maintain ultimate control over its data,
        technology, operations, and strategic decisions—independent of external jurisdictions, vendors, or
        political influences. The framework comprises five main domains, with Technical Sovereignty and
        Operational Sovereignty each including dedicated sub-pillars (Open Source and Managed Services respectively)
        that address specialized aspects of sovereignty. Each domain contains specific capabilities designed to
        assess and strengthen your organization's independence and resilience.
      </p>
    </div>

    <?php
    require_once __DIR__ . '/includes/Security.php';

    // Load Digital Sovereignty controls to get actual data
    $controlsFile = __DIR__ . '/controls-DigitalSovereignty.json';
    $controlsData = json_decode(file_get_contents($controlsFile), true);

    // Define main domain information with icons
    // Note: Domain-5 (Open Source) is now integrated into Technical Sovereignty as a sub-pillar
    // Note: Domain-7 (Managed Services) is now integrated into Operational Sovereignty as a sub-pillar
    $domains = [
      [
        'title' => 'Data Sovereignty',
        'slug' => 'data-sovereignty',
        'icon' => 'fa-database',
        'description' => 'Ultimate control over your data, independent of external jurisdictions or political influences. Ensures jurisdictional independence, active governance, and unilateral access.',
        'domain_key' => 'Domain-1',
        'is_main' => true
      ],
      [
        'title' => 'Technical Sovereignty',
        'slug' => 'technical-sovereignty',
        'icon' => 'fa-microchip',
        'description' => 'Control over foundational technology components—from hardware and firmware to application source code. Structured in two parts: Core Technical Sovereignty (platform control, vendor independence) and Open Source Sub-Pillar (transparency, community-driven innovation).',
        'domain_key' => 'Domain-2',
        'is_main' => true,
        'includes_subdomain' => true,
        'subdomain_name' => 'Open Source',
        'section_1_label' => 'Core Technical Sovereignty',
        'section_2_label' => 'Open Source (Sub-Pillar)'
      ],
      [
        'title' => 'Operational Sovereignty',
        'slug' => 'operational-sovereignty',
        'icon' => 'fa-gears',
        'description' => 'Independence in day-to-day operations, incident response, and business continuity. Structured in two parts: Core Operational Sovereignty (internal capabilities, operational resilience) and Managed Services Sub-Pillar (third-party oversight, contractual protections).',
        'domain_key' => 'Domain-3',
        'is_main' => true,
        'includes_subdomain' => true,
        'subdomain_name' => 'Managed Services',
        'section_1_label' => 'Core Operational Sovereignty',
        'section_2_label' => 'Managed Services (Sub-Pillar)'
      ],
      [
        'title' => 'Assurance Sovereignty',
        'slug' => 'assurance-sovereignty',
        'icon' => 'fa-shield-halved',
        'description' => 'Independent verification and validation of security controls, compliance, and risk management. Ensures trust through transparent auditing and certification.',
        'domain_key' => 'Domain-4',
        'is_main' => true
      ],
      [
        'title' => 'Executive Oversight',
        'slug' => 'executive-oversight',
        'icon' => 'fa-chess-king',
        'description' => 'Leadership accountability and governance structures ensuring sovereignty principles are embedded in strategic decisions, risk management, and organizational culture. This is a cross-cutting capability that applies across all sovereignty domains.',
        'domain_key' => 'Domain-6',
        'is_cross_cutting' => true
      ]
    ];

    // Count total capabilities
    $totalCapabilities = 0;
    foreach ($controlsData as $domainKey => $domainData) {
      if (strpos($domainKey, 'Domain-') === 0) {
        foreach ($domainData as $key => $value) {
          if (is_numeric($key)) {
            $totalCapabilities++;
          }
        }
      }
    }
    ?>

    <div class="stats-section">
      <div class="stat-item">
        <div class="stat-number"><?php echo count($domains); ?></div>
        <div class="stat-label">Sovereignty Domains</div>
      </div>
      <div class="stat-item">
        <div class="stat-number"><?php echo $totalCapabilities; ?></div>
        <div class="stat-label">Total Capabilities</div>
      </div>
      <div class="stat-item">
        <div class="stat-number">3</div>
        <div class="stat-label">Maturity Tiers</div>
      </div>
    </div>

    <div class="domains-grid">
      <?php foreach ($domains as $domain):
        // Count capabilities for this domain
        $capabilityCount = 0;
        if (isset($controlsData[$domain['domain_key']])) {
          $domainData = $controlsData[$domain['domain_key']];
          foreach ($domainData as $key => $value) {
            if (is_numeric($key)) {
              $capabilityCount++;
            }
          }
        }

        // Add sub-domain capabilities if applicable
        if (isset($domain['includes_subdomain']) && $domain['includes_subdomain']) {
          $subdomainKey = $controlsData[$domain['domain_key']]['section_2_source'];
          if (isset($controlsData[$subdomainKey])) {
            foreach ($controlsData[$subdomainKey] as $key => $value) {
              if (is_numeric($key)) {
                $capabilityCount++;
              }
            }
          }
        }

        // Prepare badges
        $badges = '';
        if (isset($domain['includes_subdomain']) && $domain['includes_subdomain']) {
          $badges .= '<span style="font-size: 0.7rem; background: #12bbd4; color: #fff; padding: 0.2rem 0.5rem; border-radius: 3px; margin-top: 0.5rem; display: inline-block;">+ ' . $domain['subdomain_name'] . ' Sub-Pillar</span>';
        }
        if (isset($domain['is_cross_cutting']) && $domain['is_cross_cutting']) {
          $badges .= '<span style="font-size: 0.7rem; background: #f0ab00; color: #000; padding: 0.2rem 0.5rem; border-radius: 3px; margin-top: 0.5rem; display: inline-block; font-weight: 600;">CROSS-CUTTING</span>';
        }
      ?>
      <a href="dig-sov-<?php echo $domain['slug']; ?>.php" class="domain-card">
        <div class="domain-header">
          <div class="domain-icon">
            <i class="fa-solid <?php echo $domain['icon']; ?>"></i>
          </div>
          <div style="flex: 1;">
            <h3 class="domain-title"><?php echo htmlspecialchars($domain['title']); ?></h3>
            <?php if ($badges): ?>
              <div style="margin-top: 0.5rem;"><?php echo $badges; ?></div>
            <?php endif; ?>
          </div>
        </div>
        <p class="domain-description"><?php echo htmlspecialchars($domain['description']); ?></p>
        <div class="domain-meta">
          <span class="capability-count">
            <i class="fa-solid fa-list-check"></i>
            <?php echo $capabilityCount; ?> Capabilities
          </span>
          <span class="view-link">
            Explore <i class="fa-solid fa-arrow-right"></i>
          </span>
        </div>
      </a>
      <?php endforeach; ?>
    </div>

    <div class="intro-section" style="margin-top: 3rem;">
      <h2><i class="fa-solid fa-rocket"></i> Ready to Assess Your Organization?</h2>
      <p style="margin-bottom: 1.5rem;">
        Take the comprehensive Full Assessment to evaluate your organization's maturity across all seven domains
        and receive personalized recommendations for strengthening your digital sovereignty posture.
      </p>
      <a href="maturity-assessment-landing.php" style="text-decoration: none;">
        <button style="background: #0d60f8; color: #fff; border: none; padding: 1rem 2rem; border-radius: 4px; cursor: pointer; font-size: 1.1rem; font-weight: 600; transition: all 0.3s ease;">
          <i class="fa-solid fa-chart-line"></i> Start Full Assessment
        </button>
      </a>
    </div>
  </div>
</body>
</html>
