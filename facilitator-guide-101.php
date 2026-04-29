<!doctype html>
<html lang="en-us" class="pf-theme-dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Enablement Guide 101 - Introduction to Digital Sovereignty - Viewfinder</title>
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

    /* Domain cards - simplified for executives */
    .domain-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 1.25rem;
      margin: 1.5rem 0;
    }

    .domain-card {
      background: #1a1a1a;
      border: 2px solid #444;
      border-radius: 6px;
      padding: 1.25rem;
      transition: all 0.3s ease;
    }

    .domain-card:hover {
      border-color: #0d60f8;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(13, 96, 248, 0.2);
    }

    .domain-card h4 {
      color: #fff;
      margin: 0 0 0.75rem 0;
      display: flex;
      align-items: center;
      gap: 0.5rem;
      font-size: 1.1rem;
    }

    .domain-card h4 i {
      color: #0d60f8;
    }

    .domain-card p {
      color: #999;
      font-size: 0.95rem;
      margin: 0;
      line-height: 1.6;
    }

    /* Stats highlights */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1.5rem;
      margin: 2rem 0;
    }

    .stat-box {
      background: #1a1a1a;
      border: 1px solid #444;
      border-radius: 6px;
      padding: 1.5rem;
      text-align: center;
    }

    .stat-box .stat-number {
      color: #0d60f8;
      font-size: 2.5rem;
      font-weight: 700;
      display: block;
      margin-bottom: 0.5rem;
    }

    .stat-box .stat-label {
      color: #999;
      font-size: 0.9rem;
    }

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

      .page-header h1,
      .guide-section h2,
      .guide-section h3,
      .guide-section h4,
      .info-box h4 {
        color: #000000 !important;
      }

      .page-header .subtitle,
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
      .info-box strong,
      .info-box b,
      .info-box a {
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

      /* Ensure all strong/bold text is dark */
      strong, b {
        color: #000000 !important;
      }

      /* Ensure all links are visible */
      a {
        color: #0d60f8 !important;
      }

      .domain-card {
        background: #ffffff !important;
        border: 2px solid #cccccc !important;
      }

      .domain-card h4,
      .domain-card p {
        color: #000000 !important;
      }

      .stat-box {
        background: #f5f5f5 !important;
        border: 1px solid #cccccc !important;
      }

      .stat-box .stat-number {
        color: #0d60f8 !important;
      }

      .stat-box .stat-label {
        color: #666666 !important;
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
        display: block !important; /* Show all sections in print */
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

      /* Hide navigation buttons in print */
      .pf-c-page__header-tools {
        display: none !important;
      }

      /* Ensure level badges are visible */
      .guide-header div[style*="background"] {
        background: #0d60f8 !important;
        color: #ffffff !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }

      .level-badge {
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
      <div style="display: inline-block; background: #2aaa04; color: #fff; padding: 0.5rem 1rem; border-radius: 4px; font-size: 0.9rem; font-weight: 600; margin-bottom: 1rem;">
        <i class="fa-solid fa-graduation-cap"></i> LEVEL 101 - INTRODUCTION TO DIGITAL SOVEREIGNTY
      </div>
      <h1><i class="fa-solid fa-lightbulb"></i> Executive Enablement Guide</h1>
      <p class="subtitle">Understanding Digital Sovereignty: Concepts, Risks, and Business Value</p>
      <span class="version"><i class="fa-solid fa-tag"></i> Version 1.2 - 29th April 2026</span>
    </div>

    <!-- Level Navigation -->
    <div class="info-box">
      <h4><i class="fa-solid fa-layer-group"></i> Enablement Guide Levels</h4>
      <p>This is the <strong>101 - Introduction</strong> guide for executives and newcomers. Other levels available:</p>
      <ul style="margin: 0.5rem 0 0 1.5rem; color: #ccc;">
        <li><strong style="color: #2aaa04;">101 - Introduction to Digital Sovereignty</strong> - Executive overview (1-2 hours) [You are here]</li>
        <li><a href="facilitator-guide-201.php" style="color: #12bbd4;">201 - Domain Overview & Assessment</a> - Full assessment (2-4 hours)</li>
        <li>301 - Deep Dive Implementation - Coming soon</li>
      </ul>
    </div>

    <!-- Session Overview -->
    <div class="guide-section">
      <h2><i class="fa-solid fa-bullseye"></i> Session Overview</h2>

      <h3>Learning Objectives</h3>
      <p>By the end of this session, participants will be able to:</p>
      <ul>
        <li>Understand what Digital Sovereignty means and why it matters to modern organizations</li>
        <li>Recognize the key business risks and regulatory drivers</li>
        <li>Identify the five main domains of Digital Sovereignty and their integrated sub-pillars</li>
        <li>Articulate the business value and competitive advantages of Digital Sovereignty</li>
        <li>Understand next steps for assessing and improving their organization's maturity</li>
      </ul>

      <h3>Recommended Agenda</h3>
      <ol>
        <li><strong>Introduction & Context</strong> (10 minutes) - Set the stage, explain why now</li>
        <li><strong>What is Digital Sovereignty?</strong> (15 minutes) - Core concepts and definitions</li>
        <li><strong>Business Drivers & Risks</strong> (15 minutes) - Why it matters to your organization</li>
        <li><strong>The Five Domains</strong> (30 minutes) - High-level overview of each domain and sub-pillars</li>
        <li><strong>Next Steps & Q&A</strong> (20 minutes) - Path forward and discussion</li>
      </ol>
    </div>

    <!-- What is Digital Sovereignty? -->
    <div class="guide-section">
      <h2><i class="fa-solid fa-shield-halved"></i> What is Digital Sovereignty?</h2>

      <h3>The Simple Definition</h3>
      <div class="info-box success">
        <h4><i class="fa-solid fa-quote-left"></i> Executive Summary</h4>
        <p><strong>Digital Sovereignty</strong> is the ability of an organization to maintain control over its digital assets, operations, and decision-making without undue dependence on external vendors or foreign jurisdictions.</p>
        <p>It's about <strong>strategic autonomy</strong> in the digital age.</p>
      </div>

      <h3>Key Concepts to Communicate</h3>
      <ul>
        <li><strong>Control Over Data</strong> - Where your data lives, who can access it, and under what legal jurisdiction</li>
        <li><strong>Vendor Independence</strong> - Ability to switch providers without business disruption or prohibitive cost</li>
        <li><strong>Regulatory Compliance</strong> - Meeting increasingly strict data protection and sovereignty requirements</li>
        <li><strong>Operational Resilience</strong> - Ability to operate independently of single vendors or geographic regions</li>
        <li><strong>Strategic Freedom</strong> - Making technology decisions aligned with business goals, not vendor roadmaps</li>
      </ul>

      <div class="info-box tip">
        <h4><i class="fa-solid fa-comments"></i> Discussion Point</h4>
        <p><strong>Ask the group:</strong> "Can you think of a time when vendor lock-in or regulatory requirements created challenges for your organization?"</p>
        <p>This helps make the concept concrete and relevant to their experience.</p>
      </div>
    </div>

    <!-- Business Drivers & Risks -->
    <div class="guide-section">
      <h2><i class="fa-solid fa-chart-line"></i> Business Drivers & Risks</h2>

      <h3>Why Digital Sovereignty Matters Now</h3>

      <h4>1. Regulatory Landscape</h4>
      <p>Governments worldwide are enacting strict data sovereignty laws:</p>
      <ul>
        <li><strong>GDPR (EU)</strong> - €20M fines or 4% of global revenue for violations</li>
        <li><strong>NIS2 Directive (EU)</strong> - Critical infrastructure cybersecurity requirements</li>
        <li><strong>DORA (EU Financial)</strong> - Digital operational resilience requirements</li>
        <li><strong>Cloud Act (US)</strong> - Gives US government access to data held by US companies globally</li>
        <li><strong>Data Localization Laws</strong> - Growing number of countries requiring data to stay within borders</li>
      </ul>

      <div class="info-box warning">
        <h4><i class="fa-solid fa-exclamation-triangle"></i> Real Cost Example</h4>
        <p>In 2020, the EU-US Privacy Shield was invalidated, leaving 5,300+ US companies unable to legally transfer European customer data. Companies had to scramble to implement new data transfer mechanisms, costing millions in legal fees, infrastructure changes, and potential fines.</p>
      </div>

      <h4>2. Geopolitical Risks</h4>
      <ul>
        <li><strong>Foreign Government Access</strong> - Risk of foreign intelligence agencies accessing your data</li>
        <li><strong>Economic Sanctions</strong> - Sudden service disruptions due to international conflicts</li>
        <li><strong>Supply Chain Vulnerabilities</strong> - Dependence on single-region vendors or components</li>
      </ul>

      <h4>3. Business Continuity Risks</h4>
      <ul>
        <li><strong>Vendor Lock-in</strong> - Inability to change providers leads to escalating costs</li>
        <li><strong>Service Dependencies</strong> - Single point of failure if critical vendor has outages</li>
        <li><strong>Pricing Power</strong> - Vendors raise prices knowing migration is difficult/impossible</li>
        <li><strong>Technology Obsolescence</strong> - Locked into vendor's technology evolution path</li>
      </ul>

      <h4>4. Competitive Advantages</h4>
      <div class="info-box success">
        <h4><i class="fa-solid fa-trophy"></i> Business Value</h4>
        <ul style="margin: 0.5rem 0 0 1.5rem;">
          <li><strong>Customer Trust</strong> - Demonstrable data protection and privacy controls</li>
          <li><strong>Market Access</strong> - Meet regional requirements to operate in regulated markets</li>
          <li><strong>Negotiating Power</strong> - Ability to switch vendors gives leverage in contract negotiations</li>
          <li><strong>Innovation Speed</strong> - Not constrained by single vendor's roadmap</li>
          <li><strong>Risk Mitigation</strong> - Reduced exposure to geopolitical and regulatory changes</li>
        </ul>
      </div>
    </div>

    <!-- The Five Domains -->
    <div class="guide-section">
      <h2><i class="fa-solid fa-layer-group"></i> The Five Domains of Digital Sovereignty</h2>

      <p>Digital Sovereignty is measured across five main domains with integrated sub-pillars. For this executive overview, focus on the <strong>business value and risks</strong> of each domain, not technical details:</p>

      <div class="domain-grid">
        <div class="domain-card">
          <h4><i class="fa-solid fa-database"></i> 1. Data Sovereignty</h4>
          <p><strong>What it means:</strong> Control over where your data is stored, processed, and who can access it under what legal jurisdiction.</p>
          <p><strong>Business risk if lacking:</strong> Foreign government access to sensitive data, GDPR/regulatory fines, customer trust erosion.</p>
        </div>

        <div class="domain-card">
          <h4><i class="fa-solid fa-microchip"></i> 2. Technical Sovereignty</h4>
          <p><strong>What it means:</strong> Freedom from vendor lock-in through open standards, APIs, and data portability.</p>
          <p><strong>Includes:</strong> Open Source practices - leveraging transparent, community-driven software you can inspect, modify, and control.</p>
          <p><strong>Business risk if lacking:</strong> Trapped with single vendor, escalating costs, inability to innovate independently, proprietary software "black boxes."</p>
        </div>

        <div class="domain-card">
          <h4><i class="fa-solid fa-gears"></i> 3. Operational Sovereignty</h4>
          <p><strong>What it means:</strong> Ability to operate systems and services independently without relying on single vendors or regions.</p>
          <p><strong>Includes:</strong> Managed Services controls - maintaining contractual protections and exit rights when outsourcing operations.</p>
          <p><strong>Business risk if lacking:</strong> Business continuity threats, service outages beyond your control, losing control to service providers, difficult provider changes.</p>
        </div>

        <div class="domain-card">
          <h4><i class="fa-solid fa-shield-halved"></i> 4. Assurance Sovereignty</h4>
          <p><strong>What it means:</strong> Independent verification and auditing of security controls, not just trusting vendor claims.</p>
          <p><strong>Business risk if lacking:</strong> Hidden vulnerabilities, compliance gaps, inability to prove security to regulators or customers.</p>
        </div>

        <div class="domain-card">
          <h4><i class="fa-solid fa-users-gear"></i> 5. Executive Oversight <span style="background: #f0ab00; color: #000; padding: 0.25rem 0.5rem; border-radius: 3px; font-size: 0.75rem; margin-left: 0.5rem;">CROSS-CUTTING</span></h4>
          <p><strong>What it means:</strong> Leadership actively manages sovereignty risks across all domains and makes informed strategic decisions.</p>
          <p><strong>Business risk if lacking:</strong> Sovereignty risks not understood at board level, reactive rather than strategic approach.</p>
        </div>
      </div>

      <div class="info-box tip">
        <h4><i class="fa-solid fa-comments"></i> Facilitation Tip</h4>
        <p>For each domain, ask: <strong>"Which of these domains do you think represents the biggest risk or opportunity for your organization?"</strong></p>
        <p>This engages participants and helps them connect the concepts to their business context. Note that Technical Sovereignty includes Open Source practices, and Operational Sovereignty includes Managed Services controls.</p>
      </div>
    </div>

    <!-- Maturity Model Overview -->
    <div class="guide-section">
      <h2><i class="fa-solid fa-chart-line"></i> Measuring Digital Sovereignty Maturity</h2>

      <p>Organizations progress through five maturity levels as they strengthen their Digital Sovereignty posture:</p>

      <div style="background: #1a1a1a; border: 1px solid #444; border-radius: 6px; padding: 1.5rem; margin: 1.5rem 0;">
        <div style="display: grid; grid-template-columns: auto 1fr; gap: 1rem; align-items: center; padding: 0.75rem; border-bottom: 1px solid #444;">
          <div style="background: #e57373; color: #fff; padding: 0.5rem 1rem; border-radius: 4px; font-weight: 600; text-align: center;">Level 1</div>
          <div>
            <strong style="color: #fff;">Initial</strong> - Ad-hoc, reactive approach. High vendor dependency and sovereignty risks.
          </div>
        </div>
        <div style="display: grid; grid-template-columns: auto 1fr; gap: 1rem; align-items: center; padding: 0.75rem; border-bottom: 1px solid #444;">
          <div style="background: #ec7a08; color: #fff; padding: 0.5rem 1rem; border-radius: 4px; font-weight: 600; text-align: center;">Level 2</div>
          <div>
            <strong style="color: #fff;">Managed</strong> - Basic awareness and controls in place. Beginning to address risks.
          </div>
        </div>
        <div style="display: grid; grid-template-columns: auto 1fr; gap: 1rem; align-items: center; padding: 0.75rem; border-bottom: 1px solid #444;">
          <div style="background: #ffc107; color: #000; padding: 0.5rem 1rem; border-radius: 4px; font-weight: 600; text-align: center;">Level 3</div>
          <div>
            <strong style="color: #fff;">Defined</strong> - Documented processes and standards. Consistent approach across organization.
          </div>
        </div>
        <div style="display: grid; grid-template-columns: auto 1fr; gap: 1rem; align-items: center; padding: 0.75rem; border-bottom: 1px solid #444;">
          <div style="background: #8bc34a; color: #000; padding: 0.5rem 1rem; border-radius: 4px; font-weight: 600; text-align: center;">Level 4</div>
          <div>
            <strong style="color: #fff;">Quantitatively Managed</strong> - Metrics-driven, measured controls. Data-based decision making.
          </div>
        </div>
        <div style="display: grid; grid-template-columns: auto 1fr; gap: 1rem; align-items: center; padding: 0.75rem;">
          <div style="background: #2aaa04; color: #fff; padding: 0.5rem 1rem; border-radius: 4px; font-weight: 600; text-align: center;">Level 5</div>
          <div>
            <strong style="color: #fff;">Optimizing</strong> - Continuous improvement culture. Strategic sovereignty leadership.
          </div>
        </div>
      </div>

      <div class="info-box">
        <h4><i class="fa-solid fa-lightbulb"></i> Assessment Recommendation</h4>
        <p>After this introduction, organizations typically benefit from a <strong>Full Maturity Assessment</strong> to understand their current state and prioritize improvements.</p>
        <p>The assessment evaluates all five main domains (plus integrated sub-pillars) and provides:</p>
        <ul style="margin: 0.5rem 0 0 1.5rem;">
          <li>Current maturity level for each domain</li>
          <li>Specific gaps and risks</li>
          <li>Prioritized roadmap for improvement</li>
          <li>Business case and ROI analysis</li>
        </ul>
      </div>
    </div>

    <!-- Next Steps -->
    <div class="guide-section">
      <h2><i class="fa-solid fa-route"></i> Next Steps & Path Forward</h2>

      <h3>Immediate Actions</h3>
      <ol>
        <li><strong>Executive Alignment</strong> - Ensure leadership understands sovereignty risks and opportunities</li>
        <li><strong>Current State Assessment</strong> - Conduct full maturity assessment across all five domains</li>
        <li><strong>Risk Prioritization</strong> - Identify highest-risk areas based on your industry and regulatory requirements</li>
        <li><strong>Roadmap Development</strong> - Create phased improvement plan with quick wins and strategic initiatives</li>
      </ol>

      <h3>Assessment Options</h3>
      <div class="domain-grid">
        <div class="domain-card">
          <h4><i class="fa-solid fa-gauge"></i> Quick Assessment</h4>
          <p>15-minute online self-assessment provides initial maturity baseline. Good starting point for understanding current state.</p>
          <p><a href="ds-readiness/index.php" style="color: #12bbd4;">Start Quick Assessment →</a></p>
        </div>

        <div class="domain-card">
          <h4><i class="fa-solid fa-clipboard-check"></i> Full Assessment</h4>
          <p>2-4 hour facilitated workshop with deep-dive into all five domains and integrated sub-pillars. Provides detailed roadmap and recommendations.</p>
          <p><a href="facilitator-guide-201.php" style="color: #12bbd4;">View 201 Guide →</a></p>
        </div>
      </div>

      <h3>Additional Resources</h3>
      <ul>
        <li><a href="templates/index.html" style="color: #12bbd4;">Workshop Templates & Materials</a> - Email templates, agendas, executive summaries</li>
        <li><a href="facilitator-guide-201.php" style="color: #12bbd4;">Facilitator Guide 201</a> - For conducting full maturity assessments</li>
        <li><strong>Red Hat Consulting</strong> - Expert guidance on Digital Sovereignty implementation</li>
      </ul>
    </div>

    <!-- Q&A Preparation -->
    <div class="guide-section">
      <h2><i class="fa-solid fa-comments"></i> Common Questions & Answers</h2>

      <div class="collapsible-header" onclick="toggleSection(this)">
        <h4>Q: Doesn't Digital Sovereignty mean we can't use cloud services?</h4>
        <span class="toggle-icon"><i class="fa-solid fa-chevron-down"></i></span>
      </div>
      <div class="collapsible-content">
        <p><strong>A:</strong> No! Digital Sovereignty is about <em>control and choice</em>, not avoiding cloud. You can absolutely use cloud services while maintaining sovereignty through:</p>
        <ul>
          <li>Sovereign cloud providers (EU-based with EU-only data storage)</li>
          <li>Hybrid cloud architectures (sensitive data on-premises, other workloads in cloud)</li>
          <li>Multi-cloud strategies (avoiding single-vendor lock-in)</li>
          <li>Bring-your-own-key encryption (you control the keys, not the cloud provider)</li>
        </ul>
      </div>

      <div class="collapsible-header" onclick="toggleSection(this)">
        <h4>Q: Isn't Digital Sovereignty just a European concern?</h4>
        <span class="toggle-icon"><i class="fa-solid fa-chevron-down"></i></span>
      </div>
      <div class="collapsible-content">
        <p><strong>A:</strong> While EU regulations like GDPR and NIS2 have driven awareness, Digital Sovereignty matters globally:</p>
        <ul>
          <li>US organizations face foreign government access risks (e.g., Chinese intelligence laws)</li>
          <li>Financial institutions worldwide need operational resilience (DORA-like requirements)</li>
          <li>Healthcare providers everywhere must protect patient data sovereignty</li>
          <li>Critical infrastructure requires resilience regardless of location</li>
          <li>Vendor lock-in costs affect all organizations equally</li>
        </ul>
      </div>

      <div class="collapsible-header" onclick="toggleSection(this)">
        <h4>Q: How much does improving Digital Sovereignty cost?</h4>
        <span class="toggle-icon"><i class="fa-solid fa-chevron-down"></i></span>
      </div>
      <div class="collapsible-content">
        <p><strong>A:</strong> The investment varies based on current state and goals, but key points:</p>
        <ul>
          <li><strong>Cost avoidance:</strong> Preventing regulatory fines (GDPR fines average €2.5M) and vendor lock-in price increases</li>
          <li><strong>Quick wins:</strong> Many improvements (contractual controls, data classification) require minimal investment</li>
          <li><strong>Strategic investments:</strong> Migration to sovereign cloud or open-source platforms pays back through flexibility and reduced licensing costs</li>
          <li><strong>ROI timeframe:</strong> Most organizations see positive ROI within 18-24 months</li>
        </ul>
      </div>

      <div class="collapsible-header" onclick="toggleSection(this)">
        <h4>Q: We're already ISO 27001 certified. Isn't that enough?</h4>
        <span class="toggle-icon"><i class="fa-solid fa-chevron-down"></i></span>
      </div>
      <div class="collapsible-content">
        <p><strong>A:</strong> ISO 27001 is excellent for information security, but Digital Sovereignty addresses different concerns:</p>
        <ul>
          <li><strong>Jurisdictional control:</strong> ISO doesn't address foreign government access or data localization</li>
          <li><strong>Vendor independence:</strong> ISO doesn't measure lock-in or portability</li>
          <li><strong>Operational sovereignty:</strong> ISO doesn't require multi-vendor resilience</li>
          <li><strong>Complementary frameworks:</strong> Digital Sovereignty builds on ISO 27001, addressing modern geopolitical and vendor risks</li>
        </ul>
      </div>
    </div>

  </div>

  <footer>
    <p>&copy; 2026 Red Hat, Inc. | Viewfinder - Digital Sovereignty Assessment Platform</p>
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
