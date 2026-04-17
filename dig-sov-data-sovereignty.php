<!doctype html>
<html lang="en-us" class="pf-theme-dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Data Sovereignty - Digital Sovereignty Assessment</title>
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
        <i class="fa-solid fa-shield-halved"></i> Data Sovereignty
      </h1>
      <div class="domain-overview">
        This domain assesses your organisation's ultimate control over its data, independent of external jurisdictions or political influences.<ul style='list-style: disc; margin-left: 1.5rem; margin-top: 0.5rem;'><li><b>Jurisdictional Independence:</b> Ensures full control over data, free from external political or foreign legal influence.</li><li><b>Active Governance:</b> Data location is driven by internal business and legal requirements, not cloud provider defaults.</li><li><b>Unilateral Access:</b> Guarantees the independent ability to secure, access, and migrate data at will.</li><li><b>Regulatory Compliance:</b> Maintains strict alignment with domestic data protection and residency laws.</li></ul>
      </div>
    </div>

    <div class="capabilities-grid">
      <div class="capability-card">
        <div class="capability-header">
          <div class="capability-number">1</div>
          <h2 class="capability-title">Data Residency & Location</h2>
          <span class="capability-badge badge-foundation">Foundation</span>
        </div>
        <div class="capability-content">
          <h3><i class="fa-solid fa-lightbulb"></i> Points to Consider</h3>
          <strong>Points to consider:</strong><ul style='margin: 0.5rem 0; padding-left: 1.5rem; text-align: left;'><li>Do you have a written data residency policy?</li><li>Can you show me which cloud regions your data is stored in?</li><li>How do you prevent data from being accidentally stored outside approved regions?</li><li>What happens if a cloud provider wants to move your data for operational reasons?</li></ul>
        </div>
      </div>
      <div class="capability-card">
        <div class="capability-header">
          <div class="capability-number">2</div>
          <h2 class="capability-title">Data Protection & Privacy</h2>
          <span class="capability-badge badge-foundation">Foundation</span>
        </div>
        <div class="capability-content">
          <h3><i class="fa-solid fa-lightbulb"></i> Points to Consider</h3>
          <strong>Points to consider:</strong><ul style='margin: 0.5rem 0; padding-left: 1.5rem; text-align: left;'><li>Which data protection regulations apply to you? (GDPR, CCPA, PIPL, etc.)</li><li>How do you handle data subject rights requests (access, deletion, portability)?</li><li>Do you have a Data Protection Officer or equivalent role?</li><li>How are cross-border data transfers authorized and tracked?</li></ul>
        </div>
      </div>
      <div class="capability-card">
        <div class="capability-header">
          <div class="capability-number">3</div>
          <h2 class="capability-title">Data Classification and Inventory</h2>
          <span class="capability-badge badge-foundation">Foundation</span>
        </div>
        <div class="capability-content">
          <h3><i class="fa-solid fa-lightbulb"></i> Points to Consider</h3>
          <strong>Points to consider:</strong><ul style='margin: 0.5rem 0; padding-left: 1.5rem; text-align: left;'><li>Do you have a complete inventory of your data assets?</li><li>What classification levels do you use? (Public, Internal, Confidential, Restricted)</li><li>How do you discover and classify new data automatically?</li><li>Who owns each data asset and is accountable for its protection?</li></ul>
        </div>
      </div>
      <div class="capability-card">
        <div class="capability-header">
          <div class="capability-number">4</div>
          <h2 class="capability-title">Legal & Jurisdictional Control</h2>
          <span class="capability-badge badge-strategic">Strategic</span>
        </div>
        <div class="capability-content">
          <h3><i class="fa-solid fa-lightbulb"></i> Points to Consider</h3>
          <strong>Points to consider:</strong><ul style='margin: 0.5rem 0; padding-left: 1.5rem; text-align: left;'><li>What jurisdiction's law governs your cloud contracts?</li><li>How would you respond to a foreign government data access request?</li><li>Do you have contractual provisions requiring vendors to notify you of legal demands?</li><li>Have you assessed conflicts between foreign laws (CLOUD Act) and domestic requirements?</li></ul>
        </div>
      </div>
      <div class="capability-card">
        <div class="capability-header">
          <div class="capability-number">5</div>
          <h2 class="capability-title">Cryptographic Key Management Control</h2>
          <span class="capability-badge badge-strategic">Strategic</span>
        </div>
        <div class="capability-content">
          <h3><i class="fa-solid fa-lightbulb"></i> Points to Consider</h3>
          <strong>Points to consider:</strong><ul style='margin: 0.5rem 0; padding-left: 1.5rem; text-align: left;'><li>Who generates and stores your encryption keys?</li><li>Can your cloud provider access your encryption keys?</li><li>Do you use HSMs (Hardware Security Modules)? Where are they located?</li><li>How frequently do you rotate encryption keys?</li><li>What would happen if your provider received a legal demand to decrypt your data?</li></ul>
        </div>
      </div>
      <div class="capability-card">
        <div class="capability-header">
          <div class="capability-number">6</div>
          <h2 class="capability-title">Workload Data Protection & Privacy</h2>
          <span class="capability-badge badge-strategic">Strategic</span>
        </div>
        <div class="capability-content">
          <h3><i class="fa-solid fa-lightbulb"></i> Points to Consider</h3>
          <strong>Points to consider:</strong><ul style='margin: 0.5rem 0; padding-left: 1.5rem; text-align: left;'><li>How do you protect data while it's being processed in memory?</li><li>Are you using confidential computing or Trusted Execution Environments (TEEs)?</li><li>Can cloud administrators access data in memory during processing?</li><li>How do you ensure sensitive data isn't logged in plaintext?</li></ul>
        </div>
      </div>
      <div class="capability-card">
        <div class="capability-header">
          <div class="capability-number">7</div>
          <h2 class="capability-title">Data Flow and Transfer Auditing</h2>
          <span class="capability-badge badge-advanced">Advanced</span>
        </div>
        <div class="capability-content">
          <h3><i class="fa-solid fa-lightbulb"></i> Points to Consider</h3>
          <strong>Points to consider:</strong><ul style='margin: 0.5rem 0; padding-left: 1.5rem; text-align: left;'><li>Can you show me where your data flows across systems?</li><li>Do you have Data Loss Prevention (DLP) tools deployed?</li><li>How do you monitor and prevent unauthorized data transfers?</li><li>Are your audit logs immutable and stored sovereignly?</li><li>How quickly can you detect an unauthorized cross-border data transfer?</li></ul>
        </div>
      </div>
      <div class="capability-card">
        <div class="capability-header">
          <div class="capability-number">8</div>
          <h2 class="capability-title">Data Access by Third Parties Policies</h2>
          <span class="capability-badge badge-advanced">Advanced</span>
        </div>
        <div class="capability-content">
          <h3><i class="fa-solid fa-lightbulb"></i> Points to Consider</h3>
          <strong>Points to consider:</strong><ul style='margin: 0.5rem 0; padding-left: 1.5rem; text-align: left;'><li>Which third parties have access to your data? Why?</li><li>Do you use Just-in-Time (JIT) access for vendor support?</li><li>How do you monitor and record third-party access sessions?</li><li>Can you immediately revoke vendor access in an emergency?</li><li>Where are vendor support personnel located geographically?</li></ul>
        </div>
      </div>

    </div>
  </div>
</body>
</html>