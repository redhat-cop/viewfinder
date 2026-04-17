<!doctype html>
<html lang="en-us" class="pf-theme-dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Open Source - Digital Sovereignty Assessment</title>
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

    <div class="page-title">
      <h1>
        <i class="fa-solid fa-shield-halved"></i> Open Source
      </h1>
      <div class="domain-overview">
        This domain focuses on strategic adoption of Open Source Software (OSS) to eliminate vendor dependency and strengthen technical control.<ul style='list-style: disc; margin-left: 1.5rem; margin-top: 0.5rem;'><li><b>Strategic OSS Use:</b> Goes beyond consumption to active assessment of stability and security.</li><li><b>Code Transparency:</b> Develops internal expertise to audit, modify, and understand source code.</li><li><b>Community Engagement:</b> Contributes to critical projects ensuring influence over direction.</li><li><b>Fork Capability:</b> Maintains viable contingency plans to fork and self-maintain if needed.</li></ul>
      </div>
    </div>

    <div class="capabilities-grid">
      <div class="capability-card">
        <div class="capability-header">
          <div class="capability-number">1</div>
          <h2 class="capability-title">OSS Policy and Usage Guidelines</h2>
          <span class="capability-badge badge-foundation">Foundation</span>
        </div>
        <div class="capability-content">
          <h3><i class="fa-solid fa-lightbulb"></i> Points to Consider</h3>
          <strong>Points to consider:</strong><ul style='margin: 0.5rem 0; padding-left: 1.5rem; text-align: left;'><li>What percentage of your stack uses open source software?</li><li>Do you have a policy favoring open source adoption?</li><li>Can you justify proprietary software choices?</li><li>Have you evaluated open source alternatives for proprietary tools?</li></ul>
        </div>
      </div>
      <div class="capability-card">
        <div class="capability-header">
          <div class="capability-number">2</div>
          <h2 class="capability-title">Internal OSS Skills and Expertise</h2>
          <span class="capability-badge badge-foundation">Foundation</span>
        </div>
        <div class="capability-content">
          <h3><i class="fa-solid fa-lightbulb"></i> Points to Consider</h3>
          <strong>Points to consider:</strong><ul style='margin: 0.5rem 0; padding-left: 1.5rem; text-align: left;'><li>Do you have an OSS governance framework?</li><li>How do you track open source components and their licenses?</li><li>Do you have processes for OSS security vulnerability management?</li><li>Can you identify all OSS dependencies in your applications?</li></ul>
        </div>
      </div>
      <div class="capability-card">
        <div class="capability-header">
          <div class="capability-number">3</div>
          <h2 class="capability-title">Source Code Escrow Arrangements</h2>
          <span class="capability-badge badge-foundation">Foundation</span>
        </div>
        <div class="capability-content">
          <h3><i class="fa-solid fa-lightbulb"></i> Points to Consider</h3>
          <strong>Points to consider:</strong><ul style='margin: 0.5rem 0; padding-left: 1.5rem; text-align: left;'><li>Do you contribute code, documentation, or resources to OSS projects?</li><li>Are developers encouraged to participate in OSS communities?</li><li>Do you sponsor or support critical OSS projects you depend on?</li><li>Have you open-sourced any internal tools or projects?</li></ul>
        </div>
      </div>
      <div class="capability-card">
        <div class="capability-header">
          <div class="capability-number">4</div>
          <h2 class="capability-title">Dependency Risk Assessment</h2>
          <span class="capability-badge badge-strategic">Strategic</span>
        </div>
        <div class="capability-content">
          <h3><i class="fa-solid fa-lightbulb"></i> Points to Consider</h3>
          <strong>Points to consider:</strong><ul style='margin: 0.5rem 0; padding-left: 1.5rem; text-align: left;'><li>Do you have internal expertise to support critical OSS components?</li><li>Can you fork and maintain OSS projects if needed?</li><li>Do you have developers skilled in the OSS technologies you use?</li><li>Can you provide emergency support for OSS issues?</li></ul>
        </div>
      </div>
      <div class="capability-card">
        <div class="capability-header">
          <div class="capability-number">5</div>
          <h2 class="capability-title">Forking Strategy for Critical OSS</h2>
          <span class="capability-badge badge-strategic">Strategic</span>
        </div>
        <div class="capability-content">
          <h3><i class="fa-solid fa-lightbulb"></i> Points to Consider</h3>
          <strong>Points to consider:</strong><ul style='margin: 0.5rem 0; padding-left: 1.5rem; text-align: left;'><li>How do you monitor OSS security advisories?</li><li>Do you have a process for patching OSS vulnerabilities?</li><li>Can you assess OSS security independently?</li><li>Do you perform security scanning of OSS components?</li></ul>
        </div>
      </div>
      <div class="capability-card">
        <div class="capability-header">
          <div class="capability-number">6</div>
          <h2 class="capability-title">Contribution to Strategic OSS Projects</h2>
          <span class="capability-badge badge-advanced">Advanced</span>
        </div>
        <div class="capability-content">
          <h3><i class="fa-solid fa-lightbulb"></i> Points to Consider</h3>
          <strong>Points to consider:</strong><ul style='margin: 0.5rem 0; padding-left: 1.5rem; text-align: left;'><li>Have you evaluated the maturity and sustainability of OSS projects you depend on?</li><li>Do you assess OSS project governance and community health?</li><li>What happens if a critical OSS project becomes unmaintained?</li><li>Do you have contingency plans for OSS project failures?</li></ul>
        </div>
      </div>
      <div class="capability-card">
        <div class="capability-header">
          <div class="capability-number">7</div>
          <h2 class="capability-title">Active OSS Community Engagement</h2>
          <span class="capability-badge badge-advanced">Advanced</span>
        </div>
        <div class="capability-content">
          <h3><i class="fa-solid fa-lightbulb"></i> Points to Consider</h3>
          <strong>Points to consider:</strong><ul style='margin: 0.5rem 0; padding-left: 1.5rem; text-align: left;'><li>Do you verify the integrity and provenance of OSS components?</li><li>Do you use signed releases and verify signatures?</li><li>Can you trace OSS components back to official sources?</li><li>Do you protect against OSS supply chain attacks?</li></ul>
        </div>
      </div>
      <div class="capability-card">
        <div class="capability-header">
          <div class="capability-number">8</div>
          <h2 class="capability-title">Ability to Influence OSS Roadmaps</h2>
          <span class="capability-badge badge-advanced">Advanced</span>
        </div>
        <div class="capability-content">
          <h3><i class="fa-solid fa-lightbulb"></i> Points to Consider</h3>
          <strong>Points to consider:</strong><ul style='margin: 0.5rem 0; padding-left: 1.5rem; text-align: left;'><li>Do you leverage OSS for strategic sovereignty goals?</li><li>Have you replaced proprietary tools with OSS alternatives?</li><li>Is OSS adoption part of your sovereignty roadmap?</li><li>How do you measure the sovereignty benefits of OSS?</li></ul>
        </div>
      </div>

    </div>
  </div>
</body>
</html>