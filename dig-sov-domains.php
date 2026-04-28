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
      margin-bottom: 1rem;
    }

    .page-title h1 {
      color: #9ec7fc;
      font-size: 2.5rem;
      font-weight: 600;
      margin: 0 0 0.5rem 0;
    }

    .page-title p {
      color: #999;
      font-size: 1.1rem;
      margin: 0 0 2rem 0;
      max-width: 800px;
      margin-left: auto;
      margin-right: auto;
    }

    .intro-section {
      background: #1f1f1f;
      border: 1px solid #444;
      border-radius: 8px;
      padding: 2rem;
      margin-bottom: 3rem;
      border-left: 4px solid #0d60f8;
    }

    .intro-section h2 {
      color: #9ec7fc;
      font-size: 1.3rem;
      font-weight: 600;
      margin: 0 0 1rem 0;
    }

    .intro-section p {
      color: #ccc;
      line-height: 1.8;
      margin: 0;
    }

    .domains-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
      gap: 2rem;
      margin-top: 2rem;
    }

    .domain-card {
      background: #1f1f1f;
      border: 1px solid #444;
      border-radius: 8px;
      padding: 2rem;
      transition: all 0.3s ease;
      cursor: pointer;
      text-decoration: none;
      display: block;
      position: relative;
      overflow: hidden;
    }

    .domain-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 4px;
      height: 100%;
      background: #0d60f8;
      transform: scaleY(0);
      transition: transform 0.3s ease;
    }

    .domain-card:hover {
      border-color: #0d60f8;
      transform: translateY(-4px);
      box-shadow: 0 8px 24px rgba(13, 96, 248, 0.3);
    }

    .domain-card:hover::before {
      transform: scaleY(1);
    }

    .domain-header {
      display: flex;
      align-items: center;
      gap: 1rem;
      margin-bottom: 1rem;
    }

    .domain-icon {
      width: 50px;
      height: 50px;
      background: linear-gradient(135deg, #0d60f8 0%, #12bbd4 100%);
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      color: #fff;
      flex-shrink: 0;
    }

    .domain-title {
      color: #9ec7fc;
      font-size: 1.4rem;
      font-weight: 600;
      margin: 0;
      transition: color 0.3s ease;
    }

    .domain-card:hover .domain-title {
      color: #fff;
    }

    .domain-description {
      color: #ccc;
      font-size: 0.95rem;
      line-height: 1.6;
      margin: 1rem 0;
      min-height: 80px;
    }

    .domain-meta {
      display: flex;
      align-items: center;
      gap: 1.5rem;
      margin-top: 1.5rem;
      padding-top: 1rem;
      border-top: 1px solid #333;
    }

    .capability-count {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      color: #999;
      font-size: 0.9rem;
    }

    .capability-count i {
      color: #0d60f8;
    }

    .view-link {
      margin-left: auto;
      color: #0d60f8;
      font-weight: 600;
      font-size: 0.9rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
      transition: gap 0.3s ease;
    }

    .domain-card:hover .view-link {
      gap: 0.75rem;
    }

    .stats-section {
      background: #1f1f1f;
      border: 1px solid #444;
      border-radius: 8px;
      padding: 2rem;
      margin-bottom: 3rem;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 2rem;
    }

    .stat-item {
      text-align: center;
    }

    .stat-number {
      font-size: 3rem;
      font-weight: 700;
      background: linear-gradient(135deg, #0d60f8 0%, #12bbd4 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      margin-bottom: 0.5rem;
    }

    .stat-label {
      color: #999;
      font-size: 1rem;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

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
        margin-right: 0.3rem;
      }

      .page-title h1 {
        font-size: 1.75rem;
      }

      .page-title p {
        font-size: 1rem;
        padding: 0 0.5rem;
      }

      .intro-section {
        padding: 1.25rem;
        margin-bottom: 2rem;
      }

      .intro-section h2 {
        font-size: 1.1rem;
      }

      .intro-section p {
        font-size: 0.9rem;
      }

      .intro-section button {
        width: 100%;
        padding: 0.85rem 1.5rem !important;
        font-size: 1rem !important;
      }

      .stats-section {
        grid-template-columns: 1fr;
        gap: 1.5rem;
        padding: 1.5rem;
        margin-bottom: 2rem;
      }

      .stat-number {
        font-size: 2.5rem;
      }

      .stat-label {
        font-size: 0.9rem;
      }

      .domains-grid {
        grid-template-columns: 1fr;
        gap: 1.25rem;
      }

      .domain-card {
        padding: 1.25rem;
      }

      .domain-card:hover {
        transform: none;
      }

      .domain-header {
        gap: 0.75rem;
      }

      .domain-icon {
        width: 45px;
        height: 45px;
        font-size: 1.3rem;
      }

      .domain-title {
        font-size: 1.2rem;
      }

      .domain-description {
        font-size: 0.9rem;
        min-height: auto;
      }

      .capability-count {
        font-size: 0.85rem;
      }

      .view-link {
        font-size: 0.85rem;
      }
    }

    @media (max-width: 480px) {
      .page-title h1 {
        font-size: 1.5rem;
      }

      .domain-title {
        font-size: 1.1rem;
      }

      .domain-card {
        padding: 1rem;
      }

      .stat-number {
        font-size: 2rem;
      }

      .pf-c-page__header-tools button {
        font-size: 0.8rem;
        padding: 0.4rem 0.75rem;
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
    <div class="page-title">
      <h1>
        <i class="fa-solid fa-shield-halved"></i> Digital Sovereignty Domains
      </h1>
      <p>Explore the seven key domains that define comprehensive digital sovereignty</p>
    </div>

    <div class="intro-section">
      <h2><i class="fa-solid fa-info-circle"></i> About Digital Sovereignty</h2>
      <p>
        Digital sovereignty encompasses an organization's ability to maintain ultimate control over its data,
        technology, operations, and strategic decisions—independent of external jurisdictions, vendors, or
        political influences. Each domain below represents a critical pillar of sovereignty, with specific
        capabilities designed to assess and strengthen your organization's independence and resilience.
      </p>
    </div>

    <?php
    require_once __DIR__ . '/includes/Security.php';

    // Load Digital Sovereignty controls to get actual data
    $controlsFile = __DIR__ . '/controls-DigitalSovereignty.json';
    $controlsData = json_decode(file_get_contents($controlsFile), true);

    // Define main domain information with icons
    // Note: Domain-5 (Open Source) is now integrated into Technical Sovereignty
    // Note: Domain-7 (Managed Services) is now integrated into Operational Sovereignty
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
        'description' => 'Control over foundational technology components—from hardware and firmware to application source code. Includes Open Source as a sub-pillar covering vendor independence and community-driven innovation.',
        'domain_key' => 'Domain-2',
        'is_main' => true,
        'includes_subdomain' => true,
        'subdomain_name' => 'Open Source'
      ],
      [
        'title' => 'Operational Sovereignty',
        'slug' => 'operational-sovereignty',
        'icon' => 'fa-gears',
        'description' => 'Independence in day-to-day operations, incident response, and business continuity. Includes Managed Services as a sub-pillar covering third-party provider oversight and contractual protections.',
        'domain_key' => 'Domain-3',
        'is_main' => true,
        'includes_subdomain' => true,
        'subdomain_name' => 'Managed Services'
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
          $badges .= '<span style="font-size: 0.7rem; background: #12bbd4; color: #fff; padding: 0.2rem 0.5rem; border-radius: 3px; margin-left: 0.5rem;">Includes ' . $domain['subdomain_name'] . '</span>';
        }
        if (isset($domain['is_cross_cutting']) && $domain['is_cross_cutting']) {
          $badges .= '<span style="font-size: 0.7rem; background: #f0ab00; color: #000; padding: 0.2rem 0.5rem; border-radius: 3px; margin-left: 0.5rem; font-weight: 600;">CROSS-CUTTING</span>';
        }
      ?>
      <a href="dig-sov-<?php echo $domain['slug']; ?>.php" class="domain-card">
        <div class="domain-header">
          <div class="domain-icon">
            <i class="fa-solid <?php echo $domain['icon']; ?>"></i>
          </div>
          <h3 class="domain-title"><?php echo htmlspecialchars($domain['title']); ?><?php echo $badges; ?></h3>
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
