<!doctype html>
<html lang="en-us" class="pf-theme-dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Executive Oversight - Digital Sovereignty Assessment</title>
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
        <i class="fa-solid fa-shield-halved"></i> Executive Oversight
      </h1>
      <div class="domain-overview">
        This domain assesses formal governance and strategic integration of Digital Sovereignty principles across your organisation.<ul style='list-style: disc; margin-left: 1.5rem; margin-top: 0.5rem;'><li><b>Board-Level Strategy:</b> Elevates sovereignty from IT concern to core business strategy.</li><li><b>Policy Framework:</b> Establishes organization-wide policies with dedicated budgets.</li><li><b>KPI Integration:</b> Tracks sovereignty metrics alongside other strategic performance indicators.</li><li><b>Executive Accountability:</b> Regular board reporting fostering organization-wide awareness.</li></ul>
      </div>
    </div>

    <div class="capabilities-grid">
      <div class="capability-card">
        <div class="capability-header">
          <div class="capability-number">1</div>
          <h2 class="capability-title">Designated Executive Sponsor </h2>
          <span class="capability-badge badge-foundation">Foundation</span>
        </div>
        <div class="capability-content">
          <h3><i class="fa-solid fa-lightbulb"></i> Points to Consider</h3>
          <strong>Points to consider:</strong><ul style='margin: 0.5rem 0; padding-left: 1.5rem; text-align: left;'><li>Is digital sovereignty on the board's risk agenda?</li><li>Do executives understand sovereignty risks and opportunities?</li><li>Is there a designated executive owner for sovereignty?</li><li>How often is sovereignty discussed at the executive level?</li></ul>
        </div>
      </div>
      <div class="capability-card">
        <div class="capability-header">
          <div class="capability-number">2</div>
          <h2 class="capability-title">Defined Digital Sovereignty Policy</h2>
          <span class="capability-badge badge-foundation">Foundation</span>
        </div>
        <div class="capability-content">
          <h3><i class="fa-solid fa-lightbulb"></i> Points to Consider</h3>
          <strong>Points to consider:</strong><ul style='margin: 0.5rem 0; padding-left: 1.5rem; text-align: left;'><li>Do you have a formal digital sovereignty strategy?</li><li>Is the strategy aligned with business objectives?</li><li>Does the strategy include measurable goals and timelines?</li><li>Is the strategy reviewed and updated regularly?</li></ul>
        </div>
      </div>
      <div class="capability-card">
        <div class="capability-header">
          <div class="capability-number">3</div>
          <h2 class="capability-title">Budget Allocation for Sovereignty Initiatives</h2>
          <span class="capability-badge badge-foundation">Foundation</span>
        </div>
        <div class="capability-content">
          <h3><i class="fa-solid fa-lightbulb"></i> Points to Consider</h3>
          <strong>Points to consider:</strong><ul style='margin: 0.5rem 0; padding-left: 1.5rem; text-align: left;'><li>Is there dedicated budget for sovereignty initiatives?</li><li>How much are you investing in sovereignty improvements?</li><li>Are sovereignty costs tracked separately?</li><li>Do you measure ROI on sovereignty investments?</li></ul>
        </div>
      </div>
      <div class="capability-card">
        <div class="capability-header">
          <div class="capability-number">4</div>
          <h2 class="capability-title">Integration into Organisational Strategy</h2>
          <span class="capability-badge badge-strategic">Strategic</span>
        </div>
        <div class="capability-content">
          <h3><i class="fa-solid fa-lightbulb"></i> Points to Consider</h3>
          <strong>Points to consider:</strong><ul style='margin: 0.5rem 0; padding-left: 1.5rem; text-align: left;'><li>How do you track sovereignty maturity over time?</li><li>Do you have KPIs for sovereignty progress?</li><li>Are sovereignty metrics reported to executives?</li><li>How do you benchmark against industry peers?</li></ul>
        </div>
      </div>
      <div class="capability-card">
        <div class="capability-header">
          <div class="capability-number">5</div>
          <h2 class="capability-title">Regular Reporting to the Board</h2>
          <span class="capability-badge badge-strategic">Strategic</span>
        </div>
        <div class="capability-content">
          <h3><i class="fa-solid fa-lightbulb"></i> Points to Consider</h3>
          <strong>Points to consider:</strong><ul style='margin: 0.5rem 0; padding-left: 1.5rem; text-align: left;'><li>Is sovereignty integrated into procurement processes?</li><li>Do vendor selection criteria include sovereignty requirements?</li><li>Are contracts reviewed for sovereignty compliance?</li><li>Do you negotiate sovereignty terms with vendors?</li></ul>
        </div>
      </div>
      <div class="capability-card">
        <div class="capability-header">
          <div class="capability-number">6</div>
          <h2 class="capability-title">Sovereignty Culture and Awareness Program</h2>
          <span class="capability-badge badge-strategic">Strategic</span>
        </div>
        <div class="capability-content">
          <h3><i class="fa-solid fa-lightbulb"></i> Points to Consider</h3>
          <strong>Points to consider:</strong><ul style='margin: 0.5rem 0; padding-left: 1.5rem; text-align: left;'><li>Do employees understand sovereignty requirements?</li><li>Is sovereignty training provided to relevant staff?</li><li>Is sovereignty awareness part of onboarding?</li><li>How do you promote a culture of sovereignty?</li></ul>
        </div>
      </div>
      <div class="capability-card">
        <div class="capability-header">
          <div class="capability-number">7</div>
          <h2 class="capability-title">Dedicated Sovereignty Governance Board</h2>
          <span class="capability-badge badge-advanced">Advanced</span>
        </div>
        <div class="capability-content">
          <h3><i class="fa-solid fa-lightbulb"></i> Points to Consider</h3>
          <strong>Points to consider:</strong><ul style='margin: 0.5rem 0; padding-left: 1.5rem; text-align: left;'><li>Do you engage with regulators on sovereignty topics?</li><li>Are you monitoring regulatory developments?</li><li>Do you participate in industry sovereignty initiatives?</li><li>How do you stay ahead of sovereignty regulations?</li></ul>
        </div>
      </div>
      <div class="capability-card">
        <div class="capability-header">
          <div class="capability-number">8</div>
          <h2 class="capability-title">Key Performance Indicators (KPIs) Defined</h2>
          <span class="capability-badge badge-advanced">Advanced</span>
        </div>
        <div class="capability-content">
          <h3><i class="fa-solid fa-lightbulb"></i> Points to Consider</h3>
          <strong>Points to consider:</strong><ul style='margin: 0.5rem 0; padding-left: 1.5rem; text-align: left;'><li>Do you communicate sovereignty posture to stakeholders?</li><li>Is sovereignty part of customer value propositions?</li><li>How do you demonstrate sovereignty to clients?</li><li>Do you publish sovereignty commitments publicly?</li></ul>
        </div>
      </div>

    </div>
  </div>
</body>
</html>