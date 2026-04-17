<!doctype html>
<html lang="en-us" class="pf-theme-dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Assurance Sovereignty - Digital Sovereignty Assessment</title>
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
        <i class="fa-solid fa-shield-halved"></i> Assurance Sovereignty
      </h1>
      <div class="domain-overview">
        This domain addresses your ability to verify security and compliance claims—ensuring trust is verified, not assumed.<ul style='list-style: disc; margin-left: 1.5rem; margin-top: 0.5rem;'><li><b>Independent Verification:</b> Controls all audit and security monitoring data with local retention.</li><li><b>Audit Rights:</b> Maintains contractual authority to mandate independent inspections.</li><li><b>Continuous Validation:</b> Implements technical verification of security controls and claims.</li><li><b>Evidence Control:</b> Retains logs and monitoring data for independent forensic analysis.</li></ul>
      </div>
    </div>

    <div class="capabilities-grid">
      <div class="capability-card">
        <div class="capability-header">
          <div class="capability-number">1</div>
          <h2 class="capability-title">Regular Security Audits Conducted</h2>
          <span class="capability-badge badge-foundation">Foundation</span>
        </div>
        <div class="capability-content">
          <h3><i class="fa-solid fa-lightbulb"></i> Points to Consider</h3>
          <strong>Points to consider:</strong><ul style='margin: 0.5rem 0; padding-left: 1.5rem; text-align: left;'><li>Do you have the right to audit your service providers?</li><li>When was the last time you conducted a provider audit?</li><li>Can you perform unannounced audits?</li><li>Do you verify vendor compliance claims independently?</li></ul>
        </div>
      </div>
      <div class="capability-card">
        <div class="capability-header">
          <div class="capability-number">2</div>
          <h2 class="capability-title">Control over Security Monitoring Data</h2>
          <span class="capability-badge badge-foundation">Foundation</span>
        </div>
        <div class="capability-content">
          <h3><i class="fa-solid fa-lightbulb"></i> Points to Consider</h3>
          <strong>Points to consider:</strong><ul style='margin: 0.5rem 0; padding-left: 1.5rem; text-align: left;'><li>Which compliance frameworks apply to your operations?</li><li>Do you have current compliance certifications (ISO 27001, SOC 2, etc.)?</li><li>How do you verify vendor compliance certifications?</li><li>Can you demonstrate continuous compliance?</li></ul>
        </div>
      </div>
      <div class="capability-card">
        <div class="capability-header">
          <div class="capability-number">3</div>
          <h2 class="capability-title">Risk Management Framework</h2>
          <span class="capability-badge badge-foundation">Foundation</span>
        </div>
        <div class="capability-content">
          <h3><i class="fa-solid fa-lightbulb"></i> Points to Consider</h3>
          <strong>Points to consider:</strong><ul style='margin: 0.5rem 0; padding-left: 1.5rem; text-align: left;'><li>Do you control security monitoring tools?</li><li>Where are security logs aggregated and analyzed?</li><li>Can you detect threats without vendor-provided visibility?</li><li>Do you use sovereign-based security operations tools?</li></ul>
        </div>
      </div>
      <div class="capability-card">
        <div class="capability-header">
          <div class="capability-number">4</div>
          <h2 class="capability-title">Compliance with Local Security Standards</h2>
          <span class="capability-badge badge-strategic">Strategic</span>
        </div>
        <div class="capability-content">
          <h3><i class="fa-solid fa-lightbulb"></i> Points to Consider</h3>
          <strong>Points to consider:</strong><ul style='margin: 0.5rem 0; padding-left: 1.5rem; text-align: left;'><li>How do you verify the integrity of your infrastructure?</li><li>Do you have tamper-detection mechanisms in place?</li><li>Can you detect unauthorized changes to your environment?</li><li>Do you use cryptographic verification for system integrity?</li></ul>
        </div>
      </div>
      <div class="capability-card">
        <div class="capability-header">
          <div class="capability-number">5</div>
          <h2 class="capability-title">Transparency in Vendor Security Practices</h2>
          <span class="capability-badge badge-strategic">Strategic</span>
        </div>
        <div class="capability-content">
          <h3><i class="fa-solid fa-lightbulb"></i> Points to Consider</h3>
          <strong>Points to consider:</strong><ul style='margin: 0.5rem 0; padding-left: 1.5rem; text-align: left;'><li>Do you conduct regular penetration testing?</li><li>Are security assessments performed by independent third parties?</li><li>How do you validate security controls effectiveness?</li><li>Can you demonstrate security posture to regulators?</li></ul>
        </div>
      </div>
      <div class="capability-card">
        <div class="capability-header">
          <div class="capability-number">6</div>
          <h2 class="capability-title">Independent Certification and Vetting</h2>
          <span class="capability-badge badge-advanced">Advanced</span>
        </div>
        <div class="capability-content">
          <h3><i class="fa-solid fa-lightbulb"></i> Points to Consider</h3>
          <strong>Points to consider:</strong><ul style='margin: 0.5rem 0; padding-left: 1.5rem; text-align: left;'><li>Where are compliance audit trails stored?</li><li>Are audit logs immutable and tamper-proof?</li><li>Can you produce compliance evidence on demand?</li><li>How long do you retain audit records?</li></ul>
        </div>
      </div>
      <div class="capability-card">
        <div class="capability-header">
          <div class="capability-number">7</div>
          <h2 class="capability-title">Ability to Invoke Sovereign Inspections</h2>
          <span class="capability-badge badge-advanced">Advanced</span>
        </div>
        <div class="capability-content">
          <h3><i class="fa-solid fa-lightbulb"></i> Points to Consider</h3>
          <strong>Points to consider:</strong><ul style='margin: 0.5rem 0; padding-left: 1.5rem; text-align: left;'><li>Do you control access to the cloud management plane?</li><li>Can you detect unauthorized control plane access?</li><li>Are control plane activities continuously monitored?</li><li>Do you have alerts for suspicious control plane operations?</li></ul>
        </div>
      </div>
      <div class="capability-card">
        <div class="capability-header">
          <div class="capability-number">8</div>
          <h2 class="capability-title">Continuous Security Control Validation</h2>
          <span class="capability-badge badge-advanced">Advanced</span>
        </div>
        <div class="capability-content">
          <h3><i class="fa-solid fa-lightbulb"></i> Points to Consider</h3>
          <strong>Points to consider:</strong><ul style='margin: 0.5rem 0; padding-left: 1.5rem; text-align: left;'><li>Have you tested migration to alternative providers?</li><li>Do you have exit criteria that would trigger migration?</li><li>Can you migrate critical workloads within your defined timeframe?</li><li>Do you maintain automated migration playbooks?</li></ul>
        </div>
      </div>

    </div>
  </div>
</body>
</html>