<!doctype html>
<html lang="en-us" class="pf-theme-dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Enablement Guide 301 - Quick Wins & Remediation</title>
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
      max-width: 1200px;
      margin: 2rem auto;
      padding: 0 2rem;
      flex: 1;
    }

    .guide-header {
      text-align: center;
      margin-bottom: 3rem;
      padding-bottom: 2rem;
      border-bottom: 2px solid #444;
    }

    .guide-header h1 {
      color: #9ec7fc;
      font-size: 2.5rem;
      font-weight: 600;
      margin: 0 0 1rem 0;
    }

    .guide-header .subtitle {
      color: #999;
      font-size: 1.2rem;
      margin-bottom: 1.5rem;
    }

    .guide-header .version {
      display: inline-block;
      background: #2a2a2a;
      color: #f0ab00;
      padding: 0.5rem 1rem;
      border-radius: 4px;
      font-size: 0.9rem;
      border: 1px solid #444;
    }

    .guide-section {
      background: #2a2a2a;
      border: 1px solid #444;
      border-radius: 8px;
      padding: 2rem;
      margin-bottom: 2rem;
    }

    .guide-section h2 {
      color: #9ec7fc;
      font-size: 1.8rem;
      margin: 0 0 1.5rem 0;
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    .guide-section h3 {
      color: #fff;
      font-size: 1.3rem;
      margin: 2rem 0 1rem 0;
    }

    .guide-section h4 {
      color: #9ec7fc;
      font-size: 1.1rem;
      margin: 1.5rem 0 0.75rem 0;
    }

    .guide-section p {
      color: #ccc;
      line-height: 1.7;
      margin-bottom: 1rem;
    }

    .guide-section ul,
    .guide-section ol {
      color: #ccc;
      line-height: 1.8;
      margin: 1rem 0;
      padding-left: 2rem;
    }

    .guide-section li {
      margin-bottom: 0.75rem;
    }

    .guide-section strong {
      color: #fff;
    }

    /* Info boxes */
    .info-box {
      background: #1f1f1f;
      border-left: 4px solid #0d60f8;
      padding: 1.5rem;
      margin: 1.5rem 0;
      border-radius: 4px;
    }

    .info-box.warning {
      border-left-color: #f0ab00;
    }

    .info-box.success {
      border-left-color: #2aaa04;
    }

    .info-box.tip {
      border-left-color: #12bbd4;
    }

    .info-box h4 {
      color: #fff;
      margin: 0 0 0.75rem 0;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .info-box h4 i {
      color: #0d60f8;
    }

    .info-box.warning h4 i {
      color: #f0ab00;
    }

    .info-box.success h4 i {
      color: #2aaa04;
    }

    .info-box.tip h4 i {
      color: #12bbd4;
    }

    .info-box p:last-child {
      margin-bottom: 0;
    }

    /* Quick wins cards */
    .quick-wins-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 1.25rem;
      margin: 1.5rem 0;
    }

    .quick-win-card {
      background: #1a1a1a;
      border: 2px solid #444;
      border-radius: 6px;
      padding: 1.25rem;
      transition: all 0.3s ease;
    }

    .quick-win-card:hover {
      border-color: #0d60f8;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(13, 96, 248, 0.2);
    }

    .quick-win-card h4 {
      color: #fff;
      margin: 0 0 0.75rem 0;
      display: flex;
      align-items: center;
      gap: 0.5rem;
      font-size: 1.1rem;
    }

    .quick-win-card h4 i {
      color: #0d60f8;
    }

    .quick-win-card p {
      color: #999;
      font-size: 0.95rem;
      margin: 0;
      line-height: 1.6;
    }

    .quick-win-card .timeframe {
      display: inline-block;
      background: #2aaa04;
      color: #fff;
      padding: 0.25rem 0.75rem;
      border-radius: 3px;
      font-size: 0.85rem;
      font-weight: 600;
      margin-top: 0.75rem;
    }

    .quick-win-card .timeframe.medium {
      background: #f0ab00;
      color: #000;
    }

    .quick-win-card .timeframe.long {
      background: #0d60f8;
    }

    /* Maturity progression */
    .maturity-progression {
      background: #1a1a1a;
      border: 1px solid #444;
      border-radius: 6px;
      padding: 1.5rem;
      margin: 1.5rem 0;
    }

    .maturity-level {
      display: grid;
      grid-template-columns: auto 1fr;
      gap: 1.5rem;
      padding: 1rem;
      border-bottom: 1px solid #444;
    }

    .maturity-level:last-child {
      border-bottom: none;
    }

    .level-badge {
      padding: 0.5rem 1rem;
      border-radius: 4px;
      font-weight: 600;
      text-align: center;
      min-width: 100px;
    }

    .level-1 { background: #e57373; color: #fff; }
    .level-2 { background: #ec7a08; color: #fff; }
    .level-3 { background: #ffc107; color: #000; }
    .level-4 { background: #8bc34a; color: #000; }
    .level-5 { background: #2aaa04; color: #fff; }

    /* Collapsible sections */
    .collapsible-header {
      background: #1a1a1a;
      border: 1px solid #444;
      border-radius: 6px;
      padding: 1rem 1.5rem;
      margin: 1rem 0 0 0;
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .collapsible-header:hover {
      background: #252525;
      border-color: #0d60f8;
    }

    .collapsible-header h4 {
      margin: 0;
      color: #fff;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .collapsible-header .toggle-icon {
      color: #0d60f8;
      transition: transform 0.3s ease;
    }

    .collapsible-header.active .toggle-icon {
      transform: rotate(180deg);
    }

    .collapsible-content {
      display: none;
      background: #1f1f1f;
      border: 1px solid #444;
      border-top: none;
      border-radius: 0 0 6px 6px;
      padding: 1.5rem;
      margin: 0 0 1rem 0;
    }

    .collapsible-content.active {
      display: block;
    }

    footer {
      background: #1a1a1a;
      border-top: 1px solid #444;
      padding: 2rem;
      text-align: center;
      margin-top: 3rem;
    }

    footer p {
      color: #999;
      margin: 0;
    }

    /* Print-friendly styles for PDF generation */
    @media print {
      body {
        background-color: #ffffff !important;
        color: #000000 !important;
      }

      .pf-c-page__header {
        background: #ffffff !important;
        border-bottom: 2px solid #000 !important;
      }

      .guide-header h1,
      .guide-section h2,
      .guide-section h3,
      .guide-section h4,
      .info-box h4 {
        color: #000000 !important;
      }

      .guide-header .subtitle,
      .guide-section p,
      .guide-section ul,
      .guide-section ol,
      .guide-section li {
        color: #333333 !important;
      }

      .guide-section {
        background: #ffffff !important;
        border: 1px solid #cccccc !important;
      }

      .info-box {
        background: #f5f5f5 !important;
        border-left: 4px solid #0d60f8 !important;
      }

      .info-box p,
      .info-box ul,
      .info-box li,
      .info-box strong {
        color: #333333 !important;
      }

      .info-box.warning {
        border-left-color: #f0ab00 !important;
      }

      .info-box.success {
        border-left-color: #2aaa04 !important;
      }

      .info-box.tip {
        border-left-color: #12bbd4 !important;
      }

      strong, b {
        color: #000000 !important;
      }

      a {
        color: #0d60f8 !important;
      }

      .quick-win-card {
        background: #ffffff !important;
        border: 2px solid #cccccc !important;
      }

      .quick-win-card h4,
      .quick-win-card p {
        color: #000000 !important;
      }

      .maturity-progression {
        background: #ffffff !important;
        border: 1px solid #cccccc !important;
      }

      .level-badge {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }

      .collapsible-header {
        background: #f5f5f5 !important;
        border: 1px solid #cccccc !important;
      }

      .collapsible-header h4 {
        color: #000000 !important;
      }

      .collapsible-content {
        background: #ffffff !important;
        border: 1px solid #cccccc !important;
        display: block !important;
      }

      .collapsible-content p,
      .collapsible-content ul,
      .collapsible-content li {
        color: #333333 !important;
      }

      footer {
        background: #ffffff !important;
        border-top: 2px solid #000 !important;
      }

      footer p {
        color: #666666 !important;
      }

      .pf-c-page__header-tools {
        display: none !important;
      }

      .guide-header div[style*="background"] {
        background: #a855f7 !important;
        color: #ffffff !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }
    }
  </style>
</head>
<body>
  <header class="pf-c-page__header">
    <div class="pf-c-page__header-brand">
      <div class="pf-c-page__header-brand-toggle">
      </div>
    </div>
    <div class="pf-c-page__header-tools">
      <div class="widget">
        <a href="maturity-assessment-landing.php"><button><i class="fa fa-home"></i> Home</button></a>
        <a href="facilitator-guide-101.php"><button><i class="fa-solid fa-graduation-cap"></i> 101 Guide</button></a>
        <a href="facilitator-guide-201.php"><button><i class="fa-solid fa-graduation-cap"></i> 201 Guide</button></a>
      </div>
    </div>
  </header>

  <div class="container">
    <!-- Header -->
    <div class="guide-header">
      <div style="text-align: center; margin-bottom: 1.5rem;">
        <img src="images/Logo-Red_Hat-C-Standard-RGB.svg" alt="Red Hat Logo" style="height: 60px;">
      </div>
      <div style="display: inline-block; background: #a855f7; color: #fff; padding: 0.5rem 1rem; border-radius: 4px; font-size: 0.9rem; font-weight: 600; margin-bottom: 1rem;">
        <i class="fa-solid fa-rocket"></i> LEVEL 301 - QUICK WINS & REMEDIATION
      </div>
      <h1><i class="fa-solid fa-wrench"></i> Post-Assessment Action Guide</h1>
      <p class="subtitle">Practical steps to improve your Digital Sovereignty maturity</p>
      <span class="version"><i class="fa-solid fa-tag"></i> Version 1.0 - 29th April 2026</span>
    </div>

    <!-- Level Navigation -->
    <div class="info-box">
      <h4><i class="fa-solid fa-layer-group"></i> Enablement Guide Levels</h4>
      <p>This is the <strong>301 - Post-Assessment Action</strong> guide for teams ready to improve. Other levels available:</p>
      <ul style="margin: 0.5rem 0 0 1.5rem; color: #ccc;">
        <li><a href="facilitator-guide-101.php" style="color: #12bbd4;">101 - Introduction to Digital Sovereignty</a> - Executive overview (1-2 hours)</li>
        <li><a href="facilitator-guide-201.php" style="color: #12bbd4;">201 - Domain Overview & Assessment</a> - Full assessment (2-4 hours)</li>
        <li><strong style="color: #a855f7;">301 - Quick Wins & Remediation</strong> - Action planning and improvement [You are here]</li>
      </ul>
    </div>

    <!-- Introduction -->
    <div class="guide-section">
      <h2><i class="fa-solid fa-compass"></i> How to Use This Guide</h2>

      <p>Congratulations on completing your Digital Sovereignty maturity assessment! This guide helps you turn your results into action.</p>

      <h3>Understanding Your Results</h3>
      <p>Your assessment provided a maturity score (0-36 points) for each of the five main domains:</p>
      <ul>
        <li><strong>Data Sovereignty</strong> - Control over data storage, processing, and jurisdiction</li>
        <li><strong>Technical Sovereignty</strong> - Freedom from vendor lock-in (includes Open Source sub-pillar)</li>
        <li><strong>Operational Sovereignty</strong> - Independent operations capability (includes Managed Services sub-pillar)</li>
        <li><strong>Assurance Sovereignty</strong> - Independent verification and audit capabilities</li>
        <li><strong>Executive Oversight</strong> - Leadership governance and strategic decision-making</li>
      </ul>

      <div class="maturity-progression">
        <div class="maturity-level">
          <div class="level-badge level-1">Level 1<br>Initial</div>
          <div>
            <strong style="color: #fff;">0-7.2 points</strong>
            <p style="margin: 0.5rem 0 0 0;">Ad-hoc, reactive processes. High vendor dependency and sovereignty risks. <strong>Focus: Quick wins and foundational policies.</strong></p>
          </div>
        </div>
        <div class="maturity-level">
          <div class="level-badge level-2">Level 2<br>Managed</div>
          <div>
            <strong style="color: #fff;">7.2-14.4 points</strong>
            <p style="margin: 0.5rem 0 0 0;">Basic awareness and controls in place. Beginning to address risks. <strong>Focus: Document processes and implement standards.</strong></p>
          </div>
        </div>
        <div class="maturity-level">
          <div class="level-badge level-3">Level 3<br>Defined</div>
          <div>
            <strong style="color: #fff;">14.4-21.6 points</strong>
            <p style="margin: 0.5rem 0 0 0;">Documented processes and standards. Consistent approach. <strong>Focus: Automation and measurement.</strong></p>
          </div>
        </div>
        <div class="maturity-level">
          <div class="level-badge level-4">Level 4<br>Quantitatively Managed</div>
          <div>
            <strong style="color: #fff;">21.6-28.8 points</strong>
            <p style="margin: 0.5rem 0 0 0;">Metrics-driven, measured controls. Data-based decisions. <strong>Focus: Optimization and continuous improvement.</strong></p>
          </div>
        </div>
        <div class="maturity-level">
          <div class="level-badge level-5">Level 5<br>Optimizing</div>
          <div>
            <strong style="color: #fff;">28.8-36 points</strong>
            <p style="margin: 0.5rem 0 0 0;">Continuous improvement culture. Strategic sovereignty leadership. <strong>Focus: Innovation and thought leadership.</strong></p>
          </div>
        </div>
      </div>

      <h3>Where to Start</h3>
      <div class="info-box tip">
        <h4><i class="fa-solid fa-lightbulb"></i> Prioritization Strategy</h4>
        <ol style="margin: 0.5rem 0 0 1.5rem;">
          <li><strong>Address your lowest-scoring domain first</strong> - It represents your biggest sovereignty risk</li>
          <li><strong>Focus on Quick Wins (30-day actions)</strong> - Build momentum with visible progress</li>
          <li><strong>Align with business priorities</strong> - Use industry weighting to guide investment</li>
          <li><strong>Balance effort vs. impact</strong> - Prioritize high-impact, low-effort improvements</li>
        </ol>
      </div>

      <h3>Guide Structure</h3>
      <p>This guide is organized into three main sections:</p>
      <ul>
        <li><strong>Quick Wins by Domain</strong> - Immediate actions (30/60/90 days) organized by domain</li>
        <li><strong>Remediation Roadmaps</strong> - Step-by-step progression from your current level to the next</li>
        <li><strong>Common Scenarios</strong> - Specific guidance based on typical assessment results</li>
      </ul>
    </div>

    <!-- Quick Wins Section -->
    <div class="guide-section">
      <h2><i class="fa-solid fa-bolt"></i> Quick Wins by Domain</h2>

      <p>These are high-impact, low-effort improvements you can implement quickly. Organized by timeframe:</p>
      <ul>
        <li><strong>30 Days</strong> - Policy/process changes, no infrastructure needed</li>
        <li><strong>60 Days</strong> - Limited technical implementation or vendor engagement</li>
        <li><strong>90 Days</strong> - Moderate technical work or organizational changes</li>
      </ul>

      <!-- Data Sovereignty Quick Wins -->
      <h3><i class="fa-solid fa-database"></i> Data Sovereignty</h3>

      <div class="quick-wins-grid">
        <div class="quick-win-card">
          <h4><i class="fa-solid fa-clipboard-list"></i> Data Classification</h4>
          <p>Create a simple data classification scheme (Public, Internal, Confidential, Restricted). Document what data falls into each category.</p>
          <span class="timeframe">30 days</span>
        </div>

        <div class="quick-win-card">
          <h4><i class="fa-solid fa-map-location-dot"></i> Data Location Inventory</h4>
          <p>Document where all critical data is stored (region, country, cloud provider). Identify any data in non-compliant jurisdictions.</p>
          <span class="timeframe">30 days</span>
        </div>

        <div class="quick-win-card">
          <h4><i class="fa-solid fa-file-contract"></i> Data Processing Agreement Review</h4>
          <p>Review existing contracts with cloud/SaaS providers. Add data residency clauses and access restrictions.</p>
          <span class="timeframe medium">60 days</span>
        </div>

        <div class="quick-win-card">
          <h4><i class="fa-solid fa-key"></i> Encryption Key Control</h4>
          <p>Implement bring-your-own-key (BYOK) encryption for sensitive data in cloud storage. Retain control of encryption keys.</p>
          <span class="timeframe medium">60 days</span>
        </div>

        <div class="quick-win-card">
          <h4><i class="fa-solid fa-shield-halved"></i> Data Sovereignty Policy</h4>
          <p>Create formal policy defining acceptable data storage locations, cross-border transfer requirements, and access controls.</p>
          <span class="timeframe long">90 days</span>
        </div>

        <div class="quick-win-card">
          <h4><i class="fa-solid fa-database"></i> Regional Data Migration</h4>
          <p>Migrate non-compliant data to sovereign-compliant regions. Prioritize highest-sensitivity data first.</p>
          <span class="timeframe long">90 days</span>
        </div>
      </div>

      <!-- Technical Sovereignty Quick Wins -->
      <h3><i class="fa-solid fa-microchip"></i> Technical Sovereignty (includes Open Source)</h3>

      <div class="quick-wins-grid">
        <div class="quick-win-card">
          <h4><i class="fa-solid fa-file-export"></i> Data Export Testing</h4>
          <p>Test ability to export all data from current vendors in usable formats. Document any gaps or proprietary formats.</p>
          <span class="timeframe">30 days</span>
        </div>

        <div class="quick-win-card">
          <h4><i class="fa-solid fa-scale-balanced"></i> Open Source Policy</h4>
          <p>Create policy allowing/encouraging open source adoption where appropriate. Define approval process and license compliance.</p>
          <span class="timeframe">30 days</span>
        </div>

        <div class="quick-win-card">
          <h4><i class="fa-solid fa-code-branch"></i> API Standardization</h4>
          <p>Audit current integrations. Replace proprietary APIs with open standards (REST, GraphQL, OpenAPI) where possible.</p>
          <span class="timeframe medium">60 days</span>
        </div>

        <div class="quick-win-card">
          <h4><i class="fa-solid fa-boxes-stacked"></i> Container Adoption</h4>
          <p>Containerize key applications to improve portability. Start with stateless web applications as proof of concept.</p>
          <span class="timeframe medium">60 days</span>
        </div>

        <div class="quick-win-card">
          <h4><i class="fa-solid fa-code"></i> Open Source Alternatives</h4>
          <p>Identify proprietary tools with open source equivalents. Pilot replacements in non-critical environments.</p>
          <span class="timeframe long">90 days</span>
        </div>

        <div class="quick-win-card">
          <h4><i class="fa-solid fa-people-group"></i> Open Source Contributions</h4>
          <p>Establish contribution policy and process. Begin contributing bug fixes/documentation to projects you depend on.</p>
          <span class="timeframe long">90 days</span>
        </div>
      </div>

      <!-- Operational Sovereignty Quick Wins -->
      <h3><i class="fa-solid fa-gears"></i> Operational Sovereignty (includes Managed Services)</h3>

      <div class="quick-wins-grid">
        <div class="quick-win-card">
          <h4><i class="fa-solid fa-book"></i> Operations Documentation</h4>
          <p>Document all critical operational procedures (runbooks, deployment guides). Ensure operations knowledge isn't vendor-dependent.</p>
          <span class="timeframe">30 days</span>
        </div>

        <div class="quick-win-card">
          <h4><i class="fa-solid fa-chart-line"></i> Observability Implementation</h4>
          <p>Deploy open source monitoring (Prometheus, Grafana). Ensure you can observe systems without vendor-specific tools.</p>
          <span class="timeframe medium">60 days</span>
        </div>

        <div class="quick-win-card">
          <h4><i class="fa-solid fa-handshake"></i> MSP Contract Review</h4>
          <p>Review managed service agreements. Add transition assistance clauses, knowledge transfer requirements, and exit rights.</p>
          <span class="timeframe medium">60 days</span>
        </div>

        <div class="quick-win-card">
          <h4><i class="fa-solid fa-rotate"></i> Disaster Recovery Testing</h4>
          <p>Test failover to alternate provider/region. Document dependencies and gaps that prevent independent operations.</p>
          <span class="timeframe long">90 days</span>
        </div>

        <div class="quick-win-card">
          <h4><i class="fa-solid fa-graduation-cap"></i> Skills Development</h4>
          <p>Train internal staff on critical operational capabilities currently outsourced. Build ability to insource if needed.</p>
          <span class="timeframe long">90 days</span>
        </div>

        <div class="quick-win-card">
          <h4><i class="fa-solid fa-robot"></i> Automation Implementation</h4>
          <p>Automate deployment and operations using open tools (Ansible, Terraform). Reduce manual operational dependencies.</p>
          <span class="timeframe long">90 days</span>
        </div>
      </div>

      <!-- Assurance Sovereignty Quick Wins -->
      <h3><i class="fa-solid fa-shield-halved"></i> Assurance Sovereignty</h3>

      <div class="quick-wins-grid">
        <div class="quick-win-card">
          <h4><i class="fa-solid fa-clipboard-check"></i> Security Audit Rights</h4>
          <p>Review vendor contracts. Add right-to-audit clauses and third-party security assessment requirements.</p>
          <span class="timeframe">30 days</span>
        </div>

        <div class="quick-win-card">
          <h4><i class="fa-solid fa-certificate"></i> Compliance Verification</h4>
          <p>Request current compliance certifications from vendors (ISO 27001, SOC 2). Verify they're independently audited.</p>
          <span class="timeframe">30 days</span>
        </div>

        <div class="quick-win-card">
          <h4><i class="fa-solid fa-bug"></i> Vulnerability Scanning</h4>
          <p>Deploy independent security scanning tools. Don't rely solely on vendor-provided security reports.</p>
          <span class="timeframe medium">60 days</span>
        </div>

        <div class="quick-win-card">
          <h4><i class="fa-solid fa-file-shield"></i> Transparency Requirements</h4>
          <p>Establish requirements for vendor security transparency: incident disclosure timelines, security architecture documentation.</p>
          <span class="timeframe medium">60 days</span>
        </div>

        <div class="quick-win-card">
          <h4><i class="fa-solid fa-magnifying-glass"></i> Code Review Process</h4>
          <p>For critical systems, require source code escrow or independent security code review before deployment.</p>
          <span class="timeframe long">90 days</span>
        </div>

        <div class="quick-win-card">
          <h4><i class="fa-solid fa-user-shield"></i> Third-Party Penetration Testing</h4>
          <p>Engage independent security firm to assess critical systems. Don't rely only on vendor security claims.</p>
          <span class="timeframe long">90 days</span>
        </div>
      </div>

      <!-- Executive Oversight Quick Wins -->
      <h3><i class="fa-solid fa-users-gear"></i> Executive Oversight</h3>

      <div class="quick-wins-grid">
        <div class="quick-win-card">
          <h4><i class="fa-solid fa-presentation"></i> Board Briefing</h4>
          <p>Present assessment results to board/executive leadership. Establish sovereignty as strategic priority.</p>
          <span class="timeframe">30 days</span>
        </div>

        <div class="quick-win-card">
          <h4><i class="fa-solid fa-circle-user"></i> Sovereignty Champion</h4>
          <p>Designate executive owner for Digital Sovereignty. Ensure accountability at leadership level.</p>
          <span class="timeframe">30 days</span>
        </div>

        <div class="quick-win-card">
          <h4><i class="fa-solid fa-gauge"></i> KPI Dashboard</h4>
          <p>Create executive dashboard tracking sovereignty metrics: vendor concentration, data jurisdiction compliance, exit readiness.</p>
          <span class="timeframe medium">60 days</span>
        </div>

        <div class="quick-win-card">
          <h4><i class="fa-solid fa-scale-balanced"></i> Governance Framework</h4>
          <p>Establish sovereignty governance: regular reviews, risk escalation process, vendor approval requirements.</p>
          <span class="timeframe medium">60 days</span>
        </div>

        <div class="quick-win-card">
          <h4><i class="fa-solid fa-handshake-simple"></i> Vendor Strategy Review</h4>
          <p>Review all major vendor relationships through sovereignty lens. Identify concentration risks and alternatives.</p>
          <span class="timeframe long">90 days</span>
        </div>

        <div class="quick-win-card">
          <h4><i class="fa-solid fa-calendar-check"></i> Quarterly Reviews</h4>
          <p>Establish quarterly sovereignty review meetings. Track progress, update risk register, adjust strategy.</p>
          <span class="timeframe long">90 days</span>
        </div>
      </div>
    </div>

    <!-- Remediation Roadmaps Section -->
    <div class="guide-section">
      <h2><i class="fa-solid fa-route"></i> Remediation Roadmaps</h2>

      <p>Use these roadmaps to progress from your current maturity level to the next. Each section shows the key actions needed to advance.</p>

      <!-- Data Sovereignty Roadmap -->
      <div class="collapsible-header" onclick="toggleSection(this)">
        <h4><i class="fa-solid fa-database"></i> Data Sovereignty - Level Progression</h4>
        <span class="toggle-icon"><i class="fa-solid fa-chevron-down"></i></span>
      </div>
      <div class="collapsible-content">
        <h4>Level 1 → Level 2 (Initial to Managed)</h4>
        <p><strong>Goal:</strong> Establish basic data awareness and controls</p>
        <ol>
          <li>Create data classification scheme and inventory</li>
          <li>Document current data storage locations</li>
          <li>Identify data stored in non-compliant jurisdictions</li>
          <li>Add data residency clauses to new vendor contracts</li>
          <li>Implement basic access controls on sensitive data</li>
        </ol>

        <h4>Level 2 → Level 3 (Managed to Defined)</h4>
        <p><strong>Goal:</strong> Formalize processes and ensure consistency</p>
        <ol>
          <li>Create formal data sovereignty policy</li>
          <li>Migrate non-compliant data to approved regions</li>
          <li>Implement encryption with customer-controlled keys (BYOK)</li>
          <li>Establish data transfer approval process</li>
          <li>Document all cross-border data flows</li>
        </ol>

        <h4>Level 3 → Level 4 (Defined to Quantitatively Managed)</h4>
        <p><strong>Goal:</strong> Add measurement and automation</p>
        <ol>
          <li>Deploy automated data discovery and classification tools</li>
          <li>Implement real-time monitoring of data jurisdiction compliance</li>
          <li>Create metrics dashboard for leadership</li>
          <li>Automate policy enforcement (prevent non-compliant storage)</li>
          <li>Conduct regular compliance audits with metrics</li>
        </ol>

        <h4>Level 4 → Level 5 (Quantitatively Managed to Optimizing)</h4>
        <p><strong>Goal:</strong> Continuous improvement and innovation</p>
        <ol>
          <li>Implement predictive analytics for data sovereignty risks</li>
          <li>Establish data sovereignty center of excellence</li>
          <li>Contribute to industry standards and best practices</li>
          <li>Share learnings publicly (whitepapers, conferences)</li>
          <li>Proactively adapt to emerging regulations</li>
        </ol>
      </div>

      <!-- Technical Sovereignty Roadmap -->
      <div class="collapsible-header" onclick="toggleSection(this)">
        <h4><i class="fa-solid fa-microchip"></i> Technical Sovereignty - Level Progression</h4>
        <span class="toggle-icon"><i class="fa-solid fa-chevron-down"></i></span>
      </div>
      <div class="collapsible-content">
        <h4>Level 1 → Level 2 (Initial to Managed)</h4>
        <p><strong>Goal:</strong> Understand current lock-in and start reducing dependencies</p>
        <ol>
          <li>Inventory all vendor dependencies and proprietary technologies</li>
          <li>Test data export capabilities from all major platforms</li>
          <li>Identify critical systems with single-vendor lock-in</li>
          <li>Create open source adoption policy</li>
          <li>Begin using open standards for new integrations</li>
        </ol>

        <h4>Level 2 → Level 3 (Managed to Defined)</h4>
        <p><strong>Goal:</strong> Implement portability and reduce proprietary dependencies</p>
        <ol>
          <li>Containerize applications for portability</li>
          <li>Replace proprietary APIs with open standards</li>
          <li>Adopt open source alternatives for non-critical systems</li>
          <li>Implement infrastructure-as-code for reproducibility</li>
          <li>Establish open source contribution process</li>
        </ol>

        <h4>Level 3 → Level 4 (Defined to Quantitatively Managed)</h4>
        <p><strong>Goal:</strong> Measure portability and automate migration capability</p>
        <ol>
          <li>Create vendor lock-in metrics and dashboard</li>
          <li>Implement automated testing of multi-cloud portability</li>
          <li>Regular "chaos engineering" tests of provider migration</li>
          <li>Track open source adoption rate and health metrics</li>
          <li>Measure time-to-migrate for critical workloads</li>
        </ol>

        <h4>Level 4 → Level 5 (Quantitatively Managed to Optimizing)</h4>
        <p><strong>Goal:</strong> Thought leadership and continuous innovation</p>
        <ol>
          <li>Active maintainer status in critical open source projects</li>
          <li>Contribute to open standards development</li>
          <li>Publish open source tooling for sovereignty challenges</li>
          <li>Automated dynamic workload migration based on cost/risk</li>
          <li>Lead industry initiatives for vendor independence</li>
        </ol>
      </div>

      <!-- Operational Sovereignty Roadmap -->
      <div class="collapsible-header" onclick="toggleSection(this)">
        <h4><i class="fa-solid fa-gears"></i> Operational Sovereignty - Level Progression</h4>
        <span class="toggle-icon"><i class="fa-solid fa-chevron-down"></i></span>
      </div>
      <div class="collapsible-content">
        <h4>Level 1 → Level 2 (Initial to Managed)</h4>
        <p><strong>Goal:</strong> Document operations and reduce blind dependencies</p>
        <ol>
          <li>Create runbooks for all critical operational procedures</li>
          <li>Document all managed service dependencies</li>
          <li>Implement basic monitoring with open source tools</li>
          <li>Test disaster recovery to alternate provider/region</li>
          <li>Add exit assistance clauses to MSP contracts</li>
        </ol>

        <h4>Level 2 → Level 3 (Managed to Defined)</h4>
        <p><strong>Goal:</strong> Establish independent operational capability</p>
        <ol>
          <li>Train internal staff on critical outsourced operations</li>
          <li>Implement comprehensive observability stack</li>
          <li>Automate deployments with vendor-neutral tools</li>
          <li>Establish alternate providers for critical services</li>
          <li>Create detailed transition plans for each MSP</li>
        </ol>

        <h4>Level 3 → Level 4 (Defined to Quantitatively Managed)</h4>
        <p><strong>Goal:</strong> Measure and optimize operational independence</p>
        <ol>
          <li>Track operational metrics independent of vendor dashboards</li>
          <li>Regular failover drills to alternate infrastructure</li>
          <li>Measure time-to-insource for outsourced operations</li>
          <li>Automated incident response without vendor dependency</li>
          <li>Quantify operational risk by vendor concentration</li>
        </ol>

        <h4>Level 4 → Level 5 (Quantitatively Managed to Optimizing)</h4>
        <p><strong>Goal:</strong> Self-healing and autonomous operations</p>
        <ol>
          <li>Fully automated operations (AIOps) without vendor tools</li>
          <li>Predictive capacity planning and auto-scaling</li>
          <li>Dynamic workload distribution across multiple providers</li>
          <li>Share operational best practices publicly</li>
          <li>Continuous optimization based on sovereignty metrics</li>
        </ol>
      </div>

      <!-- Assurance Sovereignty Roadmap -->
      <div class="collapsible-header" onclick="toggleSection(this)">
        <h4><i class="fa-solid fa-shield-halved"></i> Assurance Sovereignty - Level Progression</h4>
        <span class="toggle-icon"><i class="fa-solid fa-chevron-down"></i></span>
      </div>
      <div class="collapsible-content">
        <h4>Level 1 → Level 2 (Initial to Managed)</h4>
        <p><strong>Goal:</strong> Basic independent verification capabilities</p>
        <ol>
          <li>Add right-to-audit clauses to vendor contracts</li>
          <li>Request and verify vendor compliance certifications</li>
          <li>Deploy independent vulnerability scanning</li>
          <li>Establish incident disclosure requirements</li>
          <li>Document security architecture for critical systems</li>
        </ol>

        <h4>Level 2 → Level 3 (Managed to Defined)</h4>
        <p><strong>Goal:</strong> Formalized independent assessment processes</p>
        <ol>
          <li>Regular third-party security assessments</li>
          <li>Source code review for critical components</li>
          <li>Implement continuous security monitoring</li>
          <li>Establish security transparency requirements</li>
          <li>Create vendor security scorecard process</li>
        </ol>

        <h4>Level 3 → Level 4 (Defined to Quantitatively Managed)</h4>
        <p><strong>Goal:</strong> Metrics-driven security verification</p>
        <ol>
          <li>Automated security testing in CI/CD pipeline</li>
          <li>Track vendor security posture with quantified metrics</li>
          <li>Regular red team exercises by independent firms</li>
          <li>Security metrics dashboard for leadership</li>
          <li>Trend analysis of security findings and remediation</li>
        </ol>

        <h4>Level 4 → Level 5 (Quantitatively Managed to Optimizing)</h4>
        <p><strong>Goal:</strong> Proactive security assurance and innovation</p>
        <ol>
          <li>Predictive security risk modeling</li>
          <li>AI-driven threat detection and response</li>
          <li>Contribute to security standards and frameworks</li>
          <li>Publish security research and findings</li>
          <li>Continuous improvement based on threat intelligence</li>
        </ol>
      </div>

      <!-- Executive Oversight Roadmap -->
      <div class="collapsible-header" onclick="toggleSection(this)">
        <h4><i class="fa-solid fa-users-gear"></i> Executive Oversight - Level Progression</h4>
        <span class="toggle-icon"><i class="fa-solid fa-chevron-down"></i></span>
      </div>
      <div class="collapsible-content">
        <h4>Level 1 → Level 2 (Initial to Managed)</h4>
        <p><strong>Goal:</strong> Establish executive awareness and ownership</p>
        <ol>
          <li>Present sovereignty assessment to board/executives</li>
          <li>Designate executive champion for sovereignty</li>
          <li>Add sovereignty to enterprise risk register</li>
          <li>Include sovereignty in vendor approval process</li>
          <li>Establish basic reporting to leadership</li>
        </ol>

        <h4>Level 2 → Level 3 (Managed to Defined)</h4>
        <p><strong>Goal:</strong> Formalized governance and accountability</p>
        <ol>
          <li>Create sovereignty governance framework</li>
          <li>Establish quarterly executive reviews</li>
          <li>Define KPIs and success metrics</li>
          <li>Integrate sovereignty into strategic planning</li>
          <li>Vendor strategy review through sovereignty lens</li>
        </ol>

        <h4>Level 3 → Level 4 (Defined to Quantitatively Managed)</h4>
        <p><strong>Goal:</strong> Data-driven strategic decision making</p>
        <ol>
          <li>Executive dashboard with real-time metrics</li>
          <li>ROI tracking for sovereignty investments</li>
          <li>Risk-based budget allocation for improvements</li>
          <li>Benchmark against industry peers</li>
          <li>Board-level sovereignty reporting</li>
        </ol>

        <h4>Level 4 → Level 5 (Quantitatively Managed to Optimizing)</h4>
        <p><strong>Goal:</strong> Strategic leadership and thought leadership</p>
        <ol>
          <li>Sovereignty as competitive differentiator</li>
          <li>Industry leadership and advocacy</li>
          <li>Proactive regulatory engagement</li>
          <li>Public commitment to sovereignty principles</li>
          <li>Continuous strategic innovation</li>
        </ol>
      </div>
    </div>

    <!-- Common Scenarios Section -->
    <div class="guide-section">
      <h2><i class="fa-solid fa-map"></i> Common Scenarios</h2>

      <p>Based on typical assessment results, here are recommended action plans for common situations:</p>

      <!-- Scenario 1 -->
      <div class="collapsible-header" onclick="toggleSection(this)">
        <h4><i class="fa-solid fa-1"></i> "Our Data Sovereignty score is very low (Level 1-2)"</h4>
        <span class="toggle-icon"><i class="fa-solid fa-chevron-down"></i></span>
      </div>
      <div class="collapsible-content">
        <div class="info-box warning">
          <h4><i class="fa-solid fa-triangle-exclamation"></i> Priority: CRITICAL</h4>
          <p>Low Data Sovereignty creates immediate regulatory and business risk.</p>
        </div>

        <p><strong>Immediate Actions (Next 30 Days):</strong></p>
        <ol>
          <li><strong>Data Inventory</strong> - Document what data you have and where it's stored</li>
          <li><strong>Risk Assessment</strong> - Identify data in non-compliant jurisdictions</li>
          <li><strong>Classification</strong> - Categorize data by sensitivity and regulatory requirements</li>
          <li><strong>Quick Wins</strong> - Move highest-risk data to compliant regions immediately</li>
        </ol>

        <p><strong>Short-term (60-90 Days):</strong></p>
        <ol>
          <li>Create formal data sovereignty policy</li>
          <li>Add data residency requirements to vendor contracts</li>
          <li>Implement BYOK encryption for sensitive data</li>
          <li>Establish data transfer approval process</li>
        </ol>

        <p><strong>Long-term (6-12 Months):</strong></p>
        <ol>
          <li>Migrate all non-compliant data to approved regions</li>
          <li>Implement automated compliance monitoring</li>
          <li>Regular audits and compliance reporting</li>
        </ol>
      </div>

      <!-- Scenario 2 -->
      <div class="collapsible-header" onclick="toggleSection(this)">
        <h4><i class="fa-solid fa-2"></i> "We're locked into a single vendor (Low Technical Sovereignty)"</h4>
        <span class="toggle-icon"><i class="fa-solid fa-chevron-down"></i></span>
      </div>
      <div class="collapsible-content">
        <div class="info-box warning">
          <h4><i class="fa-solid fa-triangle-exclamation"></i> Priority: HIGH</h4>
          <p>Vendor lock-in limits negotiating power and creates business continuity risk.</p>
        </div>

        <p><strong>Immediate Actions (Next 30 Days):</strong></p>
        <ol>
          <li><strong>Test Data Export</strong> - Verify you can extract all data in usable formats</li>
          <li><strong>Document Dependencies</strong> - List all proprietary features/APIs you depend on</li>
          <li><strong>Identify Alternatives</strong> - Research open source or multi-vendor alternatives</li>
          <li><strong>Contract Review</strong> - Check for data portability guarantees and exit assistance</li>
        </ol>

        <p><strong>Short-term (60-90 Days):</strong></p>
        <ol>
          <li>Containerize applications to improve portability</li>
          <li>Replace proprietary APIs with open standards where possible</li>
          <li>Pilot open source alternatives in non-production</li>
          <li>Create vendor exit plan and timeline</li>
        </ol>

        <p><strong>Long-term (6-12 Months):</strong></p>
        <ol>
          <li>Migrate to multi-cloud or hybrid architecture</li>
          <li>Adopt open source for critical infrastructure</li>
          <li>Establish portability as requirement for new projects</li>
        </ol>
      </div>

      <!-- Scenario 3 -->
      <div class="collapsible-header" onclick="toggleSection(this)">
        <h4><i class="fa-solid fa-3"></i> "All scores are medium (Level 2-3 across domains)"</h4>
        <span class="toggle-icon"><i class="fa-solid fa-chevron-down"></i></span>
      </div>
      <div class="collapsible-content">
        <div class="info-box tip">
          <h4><i class="fa-solid fa-lightbulb"></i> Priority: BALANCED</h4>
          <p>Good foundation. Focus on moving to Level 3 systematically across all domains.</p>
        </div>

        <p><strong>Recommended Approach:</strong></p>
        <ol>
          <li><strong>Pick Your Weakest Domain</strong> - Focus on bringing lowest score to Level 3 first</li>
          <li><strong>Use Industry Weighting</strong> - If your industry emphasizes certain domains, prioritize those</li>
          <li><strong>Balance Quick Wins with Strategic Work</strong> - Mix 30-day wins with longer-term improvements</li>
          <li><strong>Establish Regular Reviews</strong> - Quarterly check-ins to track progress</li>
        </ol>

        <p><strong>Action Priority by Domain (choose 1-2):</strong></p>
        <ul>
          <li><strong>Data Sovereignty:</strong> Implement automated compliance monitoring</li>
          <li><strong>Technical Sovereignty:</strong> Adopt container platforms for portability</li>
          <li><strong>Operational Sovereignty:</strong> Build comprehensive observability</li>
          <li><strong>Assurance Sovereignty:</strong> Regular third-party security assessments</li>
          <li><strong>Executive Oversight:</strong> Establish governance framework and KPIs</li>
        </ul>
      </div>

      <!-- Scenario 4 -->
      <div class="collapsible-header" onclick="toggleSection(this)">
        <h4><i class="fa-solid fa-4"></i> "Executive Oversight is our lowest score"</h4>
        <span class="toggle-icon"><i class="fa-solid fa-chevron-down"></i></span>
      </div>
      <div class="collapsible-content">
        <div class="info-box warning">
          <h4><i class="fa-solid fa-triangle-exclamation"></i> Priority: HIGH</h4>
          <p>Without executive support, other sovereignty improvements will struggle.</p>
        </div>

        <p><strong>Immediate Actions (Next 30 Days):</strong></p>
        <ol>
          <li><strong>Build Business Case</strong> - Frame sovereignty in business terms (risk, compliance, cost)</li>
          <li><strong>Executive Briefing</strong> - Present assessment results with clear recommendations</li>
          <li><strong>Find Your Champion</strong> - Identify executive who understands the risk</li>
          <li><strong>Start Small</strong> - Request approval for quick wins to demonstrate value</li>
        </ol>

        <p><strong>Key Messages for Executives:</strong></p>
        <ul>
          <li><strong>Risk:</strong> "We have €X in potential GDPR fines due to data in non-compliant regions"</li>
          <li><strong>Business Continuity:</strong> "Single vendor failure would cause X hours of downtime"</li>
          <li><strong>Cost:</strong> "Vendor lock-in prevents us from negotiating better pricing"</li>
          <li><strong>Competitive Advantage:</strong> "Sovereignty enables us to win regulated-market customers"</li>
        </ul>

        <p><strong>Quick Wins to Build Momentum:</strong></p>
        <ol>
          <li>Add data residency clauses to contracts (no cost)</li>
          <li>Conduct vendor concentration risk analysis</li>
          <li>Test data export from critical vendors</li>
          <li>Create sovereignty KPI dashboard for leadership</li>
        </ol>
      </div>

      <!-- Scenario 5 -->
      <div class="collapsible-header" onclick="toggleSection(this)">
        <h4><i class="fa-solid fa-5"></i> "We scored high in most areas but have gaps"</h4>
        <span class="toggle-icon"><i class="fa-solid fa-chevron-down"></i></span>
      </div>
      <div class="collapsible-content">
        <div class="info-box success">
          <h4><i class="fa-solid fa-trophy"></i> Priority: OPTIMIZATION</h4>
          <p>Strong foundation. Focus on specific gaps and continuous improvement.</p>
        </div>

        <p><strong>Recommended Approach:</strong></p>
        <ol>
          <li><strong>Address Specific Gaps</strong> - Look at individual capability scores, not just domain totals</li>
          <li><strong>Benchmark Against Industry</strong> - Compare your scores to peers</li>
          <li><strong>Move from Level 4 to Level 5</strong> - Focus on innovation and thought leadership</li>
          <li><strong>Share Your Success</strong> - Document and publish your sovereignty journey</li>
        </ol>

        <p><strong>Level 4 → Level 5 Focus Areas:</strong></p>
        <ul>
          <li><strong>Automation:</strong> Fully automated compliance monitoring and enforcement</li>
          <li><strong>Predictive:</strong> Use metrics to predict and prevent sovereignty risks</li>
          <li><strong>Thought Leadership:</strong> Contribute to standards, publish best practices</li>
          <li><strong>Continuous Improvement:</strong> Regular optimization based on metrics</li>
        </ul>

        <p><strong>Consider:</strong></p>
        <ul>
          <li>Conducting second assessment on different profile (AI Sovereignty, Security)</li>
          <li>Helping peer organizations improve their sovereignty posture</li>
          <li>Contributing to open source sovereignty tools/frameworks</li>
        </ul>
      </div>
    </div>

    <!-- Templates & Resources Section -->
    <div class="guide-section">
      <h2><i class="fa-solid fa-box-archive"></i> Templates & Resources</h2>

      <p>Ready-to-use templates to support your sovereignty improvements:</p>

      <h3>Available Templates</h3>
      <div class="quick-wins-grid">
        <div class="quick-win-card">
          <h4><i class="fa-solid fa-file-lines"></i> Data Classification Policy</h4>
          <p>Template policy defining data categories, handling requirements, and storage restrictions.</p>
          <span class="timeframe"><a href="templates/index.html" style="color: #fff; text-decoration: none;">Download</a></span>
        </div>

        <div class="quick-win-card">
          <h4><i class="fa-solid fa-file-contract"></i> Vendor Contract Clauses</h4>
          <p>Sample contractual language for data residency, audit rights, exit assistance, and transparency.</p>
          <span class="timeframe"><a href="templates/index.html" style="color: #fff; text-decoration: none;">Download</a></span>
        </div>

        <div class="quick-win-card">
          <h4><i class="fa-solid fa-clipboard-list"></i> Vendor Assessment Scorecard</h4>
          <p>Evaluation criteria for assessing vendor sovereignty risks and compliance.</p>
          <span class="timeframe"><a href="templates/index.html" style="color: #fff; text-decoration: none;">Download</a></span>
        </div>

        <div class="quick-win-card">
          <h4><i class="fa-solid fa-presentation"></i> Executive Briefing Deck</h4>
          <p>PowerPoint template for presenting assessment results and recommendations to leadership.</p>
          <span class="timeframe"><a href="templates/index.html" style="color: #fff; text-decoration: none;">Download</a></span>
        </div>

        <div class="quick-win-card">
          <h4><i class="fa-solid fa-chart-pie"></i> KPI Dashboard Template</h4>
          <p>Sample metrics and dashboard layout for tracking sovereignty progress.</p>
          <span class="timeframe"><a href="templates/index.html" style="color: #fff; text-decoration: none;">Download</a></span>
        </div>

        <div class="quick-win-card">
          <h4><i class="fa-solid fa-map"></i> Roadmap Planning Tool</h4>
          <p>Spreadsheet for prioritizing improvements and building phased implementation plan.</p>
          <span class="timeframe"><a href="templates/index.html" style="color: #fff; text-decoration: none;">Download</a></span>
        </div>
      </div>

      <h3>Additional Resources</h3>
      <ul>
        <li><a href="facilitator-guide-101.php" style="color: #12bbd4;">101 Guide</a> - For educating executives on sovereignty concepts</li>
        <li><a href="facilitator-guide-201.php" style="color: #12bbd4;">201 Guide</a> - For conducting detailed assessments</li>
        <li><a href="maturity-assessment-landing.php" style="color: #12bbd4;">Assessment Tool</a> - Conduct new assessment or re-assess after improvements</li>
        <li><a href="templates/index.html" style="color: #12bbd4;">Templates Library</a> - Full collection of downloadable resources</li>
      </ul>
    </div>

    <!-- Next Steps Section -->
    <div class="guide-section">
      <h2><i class="fa-solid fa-compass"></i> Next Steps</h2>

      <p>You've completed your assessment and reviewed this action guide. Here's how to move forward:</p>

      <div class="info-box success">
        <h4><i class="fa-solid fa-list-check"></i> Your Action Plan</h4>
        <ol style="margin: 0.5rem 0 0 1.5rem;">
          <li><strong>Review your assessment results</strong> - Identify your lowest-scoring domain(s)</li>
          <li><strong>Select 3-5 Quick Wins</strong> - Choose actions you can complete in next 30-60 days</li>
          <li><strong>Build your roadmap</strong> - Map out 6-12 month improvement plan</li>
          <li><strong>Secure executive support</strong> - Present findings and get approval for resources</li>
          <li><strong>Execute and track progress</strong> - Implement improvements and measure results</li>
          <li><strong>Re-assess in 6 months</strong> - Measure improvement and adjust strategy</li>
        </ol>
      </div>

      <h3>Support Options</h3>
      <div class="quick-wins-grid">
        <div class="quick-win-card">
          <h4><i class="fa-solid fa-users"></i> Facilitated Workshop</h4>
          <p>Work with experts to prioritize improvements and build detailed roadmap customized to your organization.</p>
        </div>

        <div class="quick-win-card">
          <h4><i class="fa-solid fa-graduation-cap"></i> Training & Enablement</h4>
          <p>Upskill your team on sovereignty best practices, tools, and implementation approaches.</p>
        </div>

        <div class="quick-win-card">
          <h4><i class="fa-solid fa-hands-helping"></i> Implementation Assistance</h4>
          <p>Expert consulting to help execute complex migrations, architecture changes, and organizational transformations.</p>
        </div>
      </div>

      <div class="info-box tip">
        <h4><i class="fa-solid fa-rotate"></i> Remember</h4>
        <p>Digital Sovereignty is a journey, not a destination. Start small, build momentum with quick wins, and continuously improve. Re-assess every 6 months to track progress and identify new opportunities.</p>
      </div>
    </div>

  </div>

  <footer>
    <p>&copy; 2026 Red Hat, Inc. | Digital Sovereignty Assessment Platform</p>
  </footer>

  <script>
    // Collapsible sections
    function toggleSection(header) {
      header.classList.toggle('active');
      const content = header.nextElementSibling;
      content.classList.toggle('active');
    }

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
      });
    });
  </script>
</body>
</html>
