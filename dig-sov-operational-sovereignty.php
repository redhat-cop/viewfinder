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
        <i class="fa-solid fa-shield-halved"></i> Operational Sovereignty
      </h1>
      <div class="domain-overview">
        This domain examines your autonomy and independence in executing critical business and IT operations without reliance on external expertise or infrastructure.<ul style='list-style: disc; margin-left: 1.5rem; margin-top: 0.5rem;'><li><b>Internal Capability:</b> Develops and maintains in-house skills for critical operations.</li><li><b>Operational Resilience:</b> Ensures business continuity through locally managed processes.</li><li><b>Independent Response:</b> Executes incident response and disaster recovery without external support.</li><li><b>Process Autonomy:</b> Operates systems free from external interference or vendor dependencies.</li></ul>
      </div>
    </div>

    <div class="capabilities-grid">
      <div class="capability-card">
        <div class="capability-header">
          <div class="capability-number">1</div>
          <h2 class="capability-title">Operational Process Documentation</h2>
          <span class="capability-badge badge-foundation">Foundation</span>
        </div>
        <div class="capability-content">
          <h3><i class="fa-solid fa-lightbulb"></i> Points to Consider</h3>
          <strong>Points to consider:</strong><ul style='margin: 0.5rem 0; padding-left: 1.5rem; text-align: left;'><li>Are all critical operational procedures documented?</li><li>Can your team execute operations without vendor documentation?</li><li>Where are operational runbooks stored?</li><li>How often are operational procedures reviewed and updated?</li></ul>
        </div>
      </div>
      <div class="capability-card">
        <div class="capability-header">
          <div class="capability-number">2</div>
          <h2 class="capability-title">Dependency on External Managed Services</h2>
          <span class="capability-badge badge-foundation">Foundation</span>
        </div>
        <div class="capability-content">
          <h3><i class="fa-solid fa-lightbulb"></i> Points to Consider</h3>
          <strong>Points to consider:</strong><ul style='margin: 0.5rem 0; padding-left: 1.5rem; text-align: left;'><li>Do you have internal staff capable of performing all critical operations?</li><li>What percentage of operations require vendor involvement?</li><li>Have you identified skills gaps in your operations team?</li><li>Do you have training programs for sovereign operations?</li></ul>
        </div>
      </div>
      <div class="capability-card">
        <div class="capability-header">
          <div class="capability-number">3</div>
          <h2 class="capability-title">Access Control and Identity Management</h2>
          <span class="capability-badge badge-foundation">Foundation</span>
        </div>
        <div class="capability-content">
          <h3><i class="fa-solid fa-lightbulb"></i> Points to Consider</h3>
          <strong>Points to consider:</strong><ul style='margin: 0.5rem 0; padding-left: 1.5rem; text-align: left;'><li>Can you recover operations without vendor assistance?</li><li>Are disaster recovery plans tested regularly?</li><li>Do backup systems reside in sovereign infrastructure?</li><li>Can you maintain operations during a vendor outage?</li></ul>
        </div>
      </div>
      <div class="capability-card">
        <div class="capability-header">
          <div class="capability-number">4</div>
          <h2 class="capability-title">Internal Skills and Competency Development</h2>
          <span class="capability-badge badge-strategic">Strategic</span>
        </div>
        <div class="capability-content">
          <h3><i class="fa-solid fa-lightbulb"></i> Points to Consider</h3>
          <strong>Points to consider:</strong><ul style='margin: 0.5rem 0; padding-left: 1.5rem; text-align: left;'><li>Do you control incident response processes end-to-end?</li><li>Can you investigate security incidents without vendor access to logs?</li><li>Where are security logs stored?</li><li>Do you have an internal Security Operations Center (SOC)?</li></ul>
        </div>
      </div>
      <div class="capability-card">
        <div class="capability-header">
          <div class="capability-number">5</div>
          <h2 class="capability-title">Disaster Recovery and Business Continuity</h2>
          <span class="capability-badge badge-strategic">Strategic</span>
        </div>
        <div class="capability-content">
          <h3><i class="fa-solid fa-lightbulb"></i> Points to Consider</h3>
          <strong>Points to consider:</strong><ul style='margin: 0.5rem 0; padding-left: 1.5rem; text-align: left;'><li>Who manages your production deployment processes?</li><li>Can you deploy updates without vendor involvement?</li><li>Do you control CI/CD pipelines independently?</li><li>Where are deployment automation tools hosted?</li></ul>
        </div>
      </div>
      <div class="capability-card">
        <div class="capability-header">
          <div class="capability-number">6</div>
          <h2 class="capability-title">Supply Chain Transparency and Vetting</h2>
          <span class="capability-badge badge-strategic">Strategic</span>
        </div>
        <div class="capability-content">
          <h3><i class="fa-solid fa-lightbulb"></i> Points to Consider</h3>
          <strong>Points to consider:</strong><ul style='margin: 0.5rem 0; padding-left: 1.5rem; text-align: left;'><li>How quickly can you onboard new operational staff?</li><li>Do you have succession planning for critical operational roles?</li><li>Are operational skills concentrated with specific individuals?</li><li>Do you cross-train team members on critical functions?</li></ul>
        </div>
      </div>
      <div class="capability-card">
        <div class="capability-header">
          <div class="capability-number">7</div>
          <h2 class="capability-title">Sovereign Incident Response Plan</h2>
          <span class="capability-badge badge-advanced">Advanced</span>
        </div>
        <div class="capability-content">
          <h3><i class="fa-solid fa-lightbulb"></i> Points to Consider</h3>
          <strong>Points to consider:</strong><ul style='margin: 0.5rem 0; padding-left: 1.5rem; text-align: left;'><li>Do you control infrastructure monitoring tools?</li><li>Where is operational telemetry data stored?</li><li>Can you detect anomalies without vendor-provided tools?</li><li>Do you have independent visibility into infrastructure health?</li></ul>
        </div>
      </div>
      <div class="capability-card">
        <div class="capability-header">
          <div class="capability-number">8</div>
          <h2 class="capability-title">Operational Autonomy in Critical Functions</h2>
          <span class="capability-badge badge-advanced">Advanced</span>
        </div>
        <div class="capability-content">
          <h3><i class="fa-solid fa-lightbulb"></i> Points to Consider</h3>
          <strong>Points to consider:</strong><ul style='margin: 0.5rem 0; padding-left: 1.5rem; text-align: left;'><li>Can you exit your current infrastructure within a defined timeframe?</li><li>Have you tested workload migration to alternative platforms?</li><li>Do you have automated tools for infrastructure migration?</li><li>What is your RTO (Recovery Time Objective) for a forced migration?</li></ul>
        </div>
      </div>

    </div>
  </div>
</body>
</html>