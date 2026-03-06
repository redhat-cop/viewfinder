<!doctype html>
<html lang="en-us" class="pf-theme-dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Facilitator Guide - Full Maturity Assessment - Viewfinder</title>
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

    /* Table of Contents */
    .toc-container {
      background: #2a2a2a;
      border: 1px solid #444;
      border-radius: 8px;
      padding: 2rem;
      margin-bottom: 3rem;
    }

    .toc-container h2 {
      color: #9ec7fc;
      font-size: 1.5rem;
      margin: 0 0 1.5rem 0;
    }

    .toc-list {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .toc-list li {
      margin-bottom: 0.75rem;
    }

    .toc-list a {
      color: #fff;
      text-decoration: none;
      display: flex;
      align-items: center;
      padding: 0.5rem 1rem;
      border-radius: 4px;
      transition: all 0.2s ease;
    }

    .toc-list a:hover {
      background: #1a1a1a;
      color: #0d60f8;
    }

    .toc-list a i {
      margin-right: 0.75rem;
      color: #0d60f8;
      min-width: 20px;
    }

    /* Section Styling */
    .guide-section {
      background: #2a2a2a;
      border: 1px solid #444;
      border-radius: 8px;
      padding: 2.5rem;
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

    .guide-section h2 i {
      color: #0d60f8;
    }

    .guide-section h3 {
      color: #fff;
      font-size: 1.3rem;
      margin: 2rem 0 1rem 0;
      padding-top: 1.5rem;
      border-top: 1px solid #444;
    }

    .guide-section h3:first-of-type {
      border-top: none;
      padding-top: 0;
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

    .guide-section ul, .guide-section ol {
      color: #ccc;
      line-height: 1.7;
      margin-bottom: 1rem;
    }

    .guide-section li {
      margin-bottom: 0.5rem;
    }

    /* Info Boxes */
    .info-box {
      background: #1a1a1a;
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

    /* Domain Cards */
    .domain-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 1.5rem;
      margin: 1.5rem 0;
    }

    .domain-card {
      background: #1a1a1a;
      border: 1px solid #444;
      border-radius: 6px;
      padding: 1.5rem;
      transition: all 0.3s ease;
    }

    .domain-card:hover {
      border-color: #0d60f8;
      transform: translateY(-2px);
    }

    .domain-card h4 {
      color: #fff;
      margin: 0 0 0.75rem 0;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .domain-card h4 i {
      color: #0d60f8;
    }

    .domain-card p {
      color: #999;
      font-size: 0.9rem;
      margin: 0;
    }

    /* Maturity Levels Table */
    .maturity-table {
      width: 100%;
      border-collapse: collapse;
      margin: 1.5rem 0;
    }

    .maturity-table th {
      background: #1a1a1a;
      color: #9ec7fc;
      padding: 1rem;
      text-align: left;
      border: 1px solid #444;
    }

    .maturity-table td {
      padding: 1rem;
      border: 1px solid #444;
      color: #ccc;
    }

    .maturity-table tr:nth-child(even) {
      background: #1f1f1f;
    }

    .level-badge {
      display: inline-block;
      padding: 0.25rem 0.75rem;
      border-radius: 4px;
      font-size: 0.85rem;
      font-weight: 600;
      margin-right: 0.5rem;
    }

    .level-1 { background: rgba(201, 25, 11, 0.3); color: #c9190b; }
    .level-2 { background: rgba(236, 122, 8, 0.3); color: #ec7a08; }
    .level-3 { background: rgba(255, 193, 7, 0.3); color: #ffc107; }
    .level-4 { background: rgba(139, 195, 74, 0.3); color: #8bc34a; }
    .level-5 { background: rgba(42, 170, 4, 0.3); color: #2aaa04; }

    /* Collapsible Sections */
    .collapsible-header {
      background: #1a1a1a;
      padding: 1rem 1.5rem;
      border-radius: 4px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin: 1rem 0;
      transition: all 0.2s ease;
    }

    .collapsible-header:hover {
      background: #222;
    }

    .collapsible-header h4 {
      margin: 0;
      color: #fff;
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    .collapsible-header i.fa-chevron-down {
      transition: transform 0.3s ease;
      color: #0d60f8;
    }

    .collapsible-header.active i.fa-chevron-down {
      transform: rotate(180deg);
    }

    .collapsible-content {
      display: none;
      padding: 1.5rem;
      background: #1f1f1f;
      border: 1px solid #444;
      border-top: none;
      border-radius: 0 0 4px 4px;
      margin-top: -1rem;
    }

    .collapsible-content.active {
      display: block;
    }

    /* Timeline */
    .timeline {
      position: relative;
      padding-left: 2rem;
      margin: 1.5rem 0;
    }

    .timeline::before {
      content: '';
      position: absolute;
      left: 0;
      top: 0;
      bottom: 0;
      width: 2px;
      background: #444;
    }

    .timeline-item {
      position: relative;
      margin-bottom: 1.5rem;
    }

    .timeline-item::before {
      content: '';
      position: absolute;
      left: -2.5rem;
      top: 0.5rem;
      width: 12px;
      height: 12px;
      border-radius: 50%;
      background: #0d60f8;
      border: 2px solid #2a2a2a;
    }

    .timeline-item h5 {
      color: #fff;
      margin: 0 0 0.5rem 0;
      font-size: 1.1rem;
    }

    .timeline-item p {
      color: #999;
      margin: 0;
    }

    /* Print Styles */
    @media print {
      body {
        background: white !important;
        color: black !important;
      }

      .pf-c-page__header,
      .pf-c-page__header-tools {
        display: none;
      }

      .guide-section {
        page-break-inside: avoid;
        border: 1px solid #ccc;
      }

      .collapsible-content {
        display: block !important;
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
        <a href="maturity-assessment-landing.php"><button><i class="fa fa-chart-line"></i> Start Assessment</button></a>
        <button onclick="window.print()"><i class="fa fa-print"></i> Print Guide</button>
      </div>
    </div>
  </header>

  <div class="container">
    <!-- Header -->
    <div class="guide-header">
      <h1><i class="fa-solid fa-book-open"></i> Full Maturity Assessment Facilitator Guide</h1>
      <p class="subtitle">A comprehensive guide for conducting Digital Sovereignty and Security maturity assessments</p>
      <span class="version"><i class="fa-solid fa-tag"></i> Version 1.0 - March 2026</span>
    </div>

    <!-- Table of Contents -->
    <div class="toc-container">
      <h2><i class="fa-solid fa-list"></i> Table of Contents</h2>
      <ul class="toc-list">
        <li><a href="#introduction"><i class="fa-solid fa-circle-info"></i> Introduction</a></li>
        <li><a href="#pre-assessment"><i class="fa-solid fa-clipboard-check"></i> Pre-Assessment Preparation</a></li>
        <li><a href="#facilitation"><i class="fa-solid fa-users"></i> Facilitation Methodology</a></li>
        <li><a href="#domains"><i class="fa-solid fa-layer-group"></i> Domain Deep-Dives</a></li>
        <li><a href="#post-assessment"><i class="fa-solid fa-flag-checkered"></i> Post-Assessment Activities</a></li>
        <li><a href="#tips"><i class="fa-solid fa-lightbulb"></i> Facilitator Tips & Best Practices</a></li>
        <li><a href="#appendix"><i class="fa-solid fa-book"></i> Appendix</a></li>
        <li><a href="templates/index.html" style="color: #f0ab00;"><i class="fa-solid fa-download"></i> Downloadable Templates</a></li>
      </ul>
    </div>

    <!-- Introduction Section -->
    <div id="introduction" class="guide-section">
      <h2><i class="fa-solid fa-circle-info"></i> Introduction</h2>

      <h3>Purpose of This Guide</h3>
      <p>This Facilitator Guide provides comprehensive instructions for conducting Full Maturity Assessments with customers and partners. It is designed to help you deliver consistent, high-quality assessments that provide valuable insights into an organization's Digital Sovereignty and Security maturity.</p>

      <h3>What is a Full Maturity Assessment?</h3>
      <p>The Full Maturity Assessment is a structured evaluation tool that measures an organization's capabilities across multiple domains using a proven 5-level maturity model based on the CMMI (Capability Maturity Model Integration) framework:</p>

      <table class="maturity-table">
        <thead>
          <tr>
            <th>Level</th>
            <th>Name</th>
            <th>Range</th>
            <th>Description</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><span class="level-badge level-1">Level 1</span></td>
            <td>Initial</td>
            <td>0-20%</td>
            <td>Unpredictable, reactive processes; ad-hoc approach</td>
          </tr>
          <tr>
            <td><span class="level-badge level-2">Level 2</span></td>
            <td>Managed</td>
            <td>21-40%</td>
            <td>Planned and executed processes; basic controls in place</td>
          </tr>
          <tr>
            <td><span class="level-badge level-3">Level 3</span></td>
            <td>Defined</td>
            <td>41-60%</td>
            <td>Standardized and documented processes across organization</td>
          </tr>
          <tr>
            <td><span class="level-badge level-4">Level 4</span></td>
            <td>Quantitatively Managed</td>
            <td>61-80%</td>
            <td>Measured and controlled processes with metrics</td>
          </tr>
          <tr>
            <td><span class="level-badge level-5">Level 5</span></td>
            <td>Optimizing</td>
            <td>81-100%</td>
            <td>Continuous improvement and innovation</td>
          </tr>
        </tbody>
      </table>

      <h3>Assessment Profiles</h3>
      <p>We offer two primary assessment profiles, each focused on different organizational priorities:</p>

      <div class="domain-grid">
        <div class="domain-card">
          <h4><i class="fa-solid fa-shield-halved"></i> Digital Sovereignty</h4>
          <p><strong>7 Domains:</strong> Data Sovereignty, Technical Sovereignty, Operational Sovereignty, Assurance Sovereignty, Open Source, Executive Oversight, Managed Services</p>
          <p><strong>Focus:</strong> Organizational control and independence from external dependencies, particularly important for government, healthcare, finance, and organizations with strict data residency requirements.</p>
        </div>

        <div class="domain-card">
          <h4><i class="fa-solid fa-lock"></i> Security</h4>
          <p><strong>7 Domains:</strong> Secure Infrastructure, Secure Data, Secure Identity, Secure Application, Secure Network, Secure Recovery, Secure Operations</p>
          <p><strong>Focus:</strong> Comprehensive security posture across all layers of the technology stack, ideal for compliance-driven organizations and those with high security requirements.</p>
        </div>
      </div>

      <div class="info-box tip">
        <h4><i class="fa-solid fa-lightbulb"></i> Tip</h4>
        <p>Most organizations benefit from starting with <strong>Digital Sovereignty</strong> as it addresses strategic independence concerns. Security assessments can follow to provide deeper technical security insights.</p>
      </div>
    </div>

    <!-- Pre-Assessment Preparation -->
    <div id="pre-assessment" class="guide-section">
      <h2><i class="fa-solid fa-clipboard-check"></i> Pre-Assessment Preparation</h2>

      <h3>Scheduling the Assessment</h3>
      <p>Proper preparation is critical to a successful assessment. Consider the following when scheduling:</p>

      <div class="info-box">
        <h4><i class="fa-solid fa-clock"></i> Time Requirements</h4>
        <ul>
          <li><strong>Full Assessment:</strong> 2-4 hours depending on organization size and complexity</li>
          <li><strong>Quick Assessment:</strong> 1-2 hours (covering only Foundation tier questions)</li>
          <li><strong>Follow-up Session:</strong> 1 hour (for results review and roadmap planning)</li>
        </ul>
      </div>

      <h3>Participant Selection</h3>
      <p>The assessment requires input from multiple stakeholders to ensure accurate ratings. Recommended participants:</p>

      <table class="maturity-table">
        <thead>
          <tr>
            <th>Role</th>
            <th>Why They're Needed</th>
            <th>Essential?</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><strong>CIO / CTO</strong></td>
            <td>Strategic oversight, budget authority, executive-level questions</td>
            <td>Yes</td>
          </tr>
          <tr>
            <td><strong>CISO / Security Lead</strong></td>
            <td>Security controls, risk management, compliance frameworks</td>
            <td>Yes</td>
          </tr>
          <tr>
            <td><strong>Cloud/Infrastructure Lead</strong></td>
            <td>Technical sovereignty, infrastructure control, vendor relationships</td>
            <td>Yes</td>
          </tr>
          <tr>
            <td><strong>Compliance/Legal Officer</strong></td>
            <td>Data residency, jurisdictional control, regulatory requirements</td>
            <td>Recommended</td>
          </tr>
          <tr>
            <td><strong>Operations Manager</strong></td>
            <td>Operational processes, disaster recovery, managed services</td>
            <td>Recommended</td>
          </tr>
          <tr>
            <td><strong>Procurement Lead</strong></td>
            <td>Vendor management, supply chain, contract terms</td>
            <td>Optional</td>
          </tr>
        </tbody>
      </table>

      <h3>Industry (LOB) Selection</h3>
      <p>Selecting the appropriate Line of Business (LOB) is crucial as it applies industry-specific weightings to domains. Guide your customer through this decision:</p>

      <div class="collapsible-header" onclick="toggleCollapsible(this)">
        <h4><i class="fa-solid fa-building-columns"></i> Finance</h4>
        <i class="fa-solid fa-chevron-down"></i>
      </div>
      <div class="collapsible-content">
        <p><strong>Best for:</strong> Banks, insurance companies, financial services, payment processors</p>
        <p><strong>Emphasized domains:</strong> Data Sovereignty (2.0×), Assurance Sovereignty (2.0×), Operational Sovereignty (1.5×)</p>
        <p><strong>Rationale:</strong> Financial institutions face stringent regulatory requirements (PCI DSS, SOX, DORA) demanding strong data protection, audit controls, and business continuity.</p>
      </div>

      <div class="collapsible-header" onclick="toggleCollapsible(this)">
        <h4><i class="fa-solid fa-heart-pulse"></i> Healthcare</h4>
        <i class="fa-solid fa-chevron-down"></i>
      </div>
      <div class="collapsible-content">
        <p><strong>Best for:</strong> Hospitals, health systems, medical research, healthcare technology</p>
        <p><strong>Emphasized domains:</strong> Data Sovereignty (2.0×), Operational Sovereignty (2.0×)</p>
        <p><strong>Rationale:</strong> Healthcare organizations must protect sensitive patient data (HIPAA, GDPR) while maintaining 24/7 operational resilience for patient safety.</p>
      </div>

      <div class="collapsible-header" onclick="toggleCollapsible(this)">
        <h4><i class="fa-solid fa-landmark"></i> Government</h4>
        <i class="fa-solid fa-chevron-down"></i>
      </div>
      <div class="collapsible-content">
        <p><strong>Best for:</strong> Federal/state/local government, public sector, defense contractors</p>
        <p><strong>Emphasized domains:</strong> Data Sovereignty (2.0×), Assurance Sovereignty (2.0×), Executive Oversight (2.0×)</p>
        <p><strong>Rationale:</strong> Government entities handle sensitive citizen data and critical infrastructure with strict sovereignty requirements, transparency needs, and national security concerns.</p>
      </div>

      <div class="collapsible-header" onclick="toggleCollapsible(this)">
        <h4><i class="fa-solid fa-industry"></i> Manufacturing</h4>
        <i class="fa-solid fa-chevron-down"></i>
      </div>
      <div class="collapsible-content">
        <p><strong>Best for:</strong> Industrial manufacturing, automotive, aerospace, discrete manufacturing</p>
        <p><strong>Emphasized domains:</strong> Operational Sovereignty (2.0×), Managed Services (2.0×)</p>
        <p><strong>Rationale:</strong> Manufacturers prioritize production uptime, OT/IT integration, and IP protection for proprietary designs and processes.</p>
      </div>

      <div class="collapsible-header" onclick="toggleCollapsible(this)">
        <h4><i class="fa-solid fa-tower-cell"></i> Telecommunications</h4>
        <i class="fa-solid fa-chevron-down"></i>
      </div>
      <div class="collapsible-content">
        <p><strong>Best for:</strong> Telecom providers, ISPs, mobile carriers, network infrastructure</p>
        <p><strong>Emphasized domains:</strong> Data Sovereignty (2.0×), Operational Sovereignty (2.0×), Assurance Sovereignty (2.0×)</p>
        <p><strong>Rationale:</strong> Telecom operators manage critical communications infrastructure with subscriber data protection requirements and strict regulatory compliance (NIS2).</p>
      </div>

      <div class="collapsible-header" onclick="toggleCollapsible(this)">
        <h4><i class="fa-solid fa-balance-scale"></i> Balanced / Other</h4>
        <i class="fa-solid fa-chevron-down"></i>
      </div>
      <div class="collapsible-content">
        <p><strong>Best for:</strong> Organizations without specific industry focus or those spanning multiple sectors</p>
        <p><strong>Emphasized domains:</strong> All domains equally weighted (1.0×)</p>
        <p><strong>Rationale:</strong> Provides an unbiased assessment across all domains without industry-specific emphasis.</p>
      </div>

      <h3>Pre-Assessment Checklist</h3>
      <p>Send this checklist to participants at least 1 week before the assessment:</p>

      <div class="info-box">
        <h4><i class="fa-solid fa-tasks"></i> Pre-Assessment Information Needed</h4>
        <ul>
          <li>Current cloud infrastructure provider(s) and services used</li>
          <li>List of critical business applications and their hosting locations</li>
          <li>Existing compliance frameworks and certifications (ISO 27001, SOC 2, etc.)</li>
          <li>Data classification policies and data residency requirements</li>
          <li>Key vendor relationships and managed service providers</li>
          <li>Recent security audits or risk assessments</li>
          <li>Disaster recovery and business continuity documentation</li>
          <li>Open source usage policies (if applicable)</li>
        </ul>
      </div>

      <h3>Technical Setup</h3>
      <p>Before the session, ensure:</p>
      <ul>
        <li>Access to the Viewfinder assessment tool at the appropriate URL</li>
        <li>Screen sharing capability if conducting remotely</li>
        <li>Backup recording/note-taking method in case of technical issues</li>
        <li>Sample results ready to show (if first-time participants)</li>
      </ul>
    </div>

    <!-- Facilitation Methodology -->
    <div id="facilitation" class="guide-section">
      <h2><i class="fa-solid fa-users"></i> Facilitation Methodology</h2>

      <h3>Workshop Structure</h3>
      <p>A well-structured session keeps participants engaged and ensures comprehensive coverage of all domains.</p>

      <div class="timeline">
        <div class="timeline-item">
          <h5>0:00-0:15 - Introduction & Context Setting (15 min)</h5>
          <p>Explain assessment purpose, maturity model, review agenda, confirm participants and roles</p>
        </div>
        <div class="timeline-item">
          <h5>0:15-0:25 - Profile & Industry Selection (10 min)</h5>
          <p>Discuss and select appropriate assessment profile and industry weighting</p>
        </div>
        <div class="timeline-item">
          <h5>0:25-2:25 - Domain Assessment (120 min)</h5>
          <p>Work through each domain systematically (~17 min per domain for 7 domains)</p>
        </div>
        <div class="timeline-item">
          <h5>2:25-2:45 - Results Review (20 min)</h5>
          <p>Review spider chart, discuss scores, identify obvious gaps</p>
        </div>
        <div class="timeline-item">
          <h5>2:45-3:00 - Next Steps & Wrap-up (15 min)</h5>
          <p>Discuss next steps, schedule follow-up, export results</p>
        </div>
      </div>

      <div class="info-box warning">
        <h4><i class="fa-solid fa-triangle-exclamation"></i> Time Management</h4>
        <p>Sessions often run long as participants want to discuss their challenges. Build in buffer time or be prepared to schedule a continuation session. Consider breaking complex assessments into multiple shorter sessions.</p>
      </div>

      <h3>Opening Script</h3>
      <p>Use this script to open your assessment session professionally:</p>

      <div class="info-box tip">
        <h4><i class="fa-solid fa-comments"></i> Sample Opening</h4>
        <p><em>"Thank you all for joining today's Full Maturity Assessment. Over the next 2-3 hours, we'll be evaluating your organization's capabilities across [Digital Sovereignty / Security] domains using a proven 5-level maturity framework."</em></p>
        <p><em>"This assessment is designed to be honest and constructive—not punitive. Most organizations score between levels 2-3 initially, and that's perfectly normal. The goal is to establish a baseline and identify priority areas for improvement."</em></p>
        <p><em>"I'll be asking questions about your current capabilities and asking for evidence of implementation. Please be candid—overestimating maturity only hurts your own planning. If you're unsure about an answer, we can flag it for follow-up."</em></p>
        <p><em>"Let's start by selecting your industry profile, which will adjust the weighting of domains based on your sector's specific needs..."</em></p>
      </div>

      <h3>Question-by-Question Guidance</h3>

      <h4>How to Score Each Question</h4>
      <p>Each question has multiple-choice answers corresponding to maturity levels. Guide participants through this process:</p>

      <ol>
        <li><strong>Read the question aloud</strong> - Ensure everyone understands what's being asked</li>
        <li><strong>Review all options</strong> - Read through all maturity levels before deciding</li>
        <li><strong>Ask for evidence</strong> - "Can you show me documentation/tools/policies that demonstrate this?"</li>
        <li><strong>Probe deeper</strong> - "Walk me through how this actually works in practice"</li>
        <li><strong>Watch for inflation</strong> - Organizations often overestimate; look for concrete proof</li>
        <li><strong>Seek consensus</strong> - If participants disagree, facilitate discussion to reach agreement</li>
        <li><strong>Document notes</strong> - Use the notes field to capture important context</li>
      </ol>

      <h4>Evidence-Based Assessment</h4>
      <p>Always ask for evidence to support maturity claims. Here are examples of acceptable evidence:</p>

      <table class="maturity-table">
        <thead>
          <tr>
            <th>Level</th>
            <th>Acceptable Evidence Examples</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><span class="level-badge level-1">Level 1</span></td>
            <td>Verbal confirmation, acknowledgment of gaps, plans to implement</td>
          </tr>
          <tr>
            <td><span class="level-badge level-2">Level 2</span></td>
            <td>Draft policies, project plans, pilot implementations, partial rollouts</td>
          </tr>
          <tr>
            <td><span class="level-badge level-3">Level 3</span></td>
            <td>Approved policies, documented standards, widespread implementation, training records</td>
          </tr>
          <tr>
            <td><span class="level-badge level-4">Level 4</span></td>
            <td>Metrics dashboards, KPI reports, audit logs, automated compliance reporting</td>
          </tr>
          <tr>
            <td><span class="level-badge level-5">Level 5</span></td>
            <td>Continuous improvement programs, innovation initiatives, industry leadership, published case studies</td>
          </tr>
        </tbody>
      </table>

      <h4>Handling Difficult Conversations</h4>

      <div class="collapsible-header" onclick="toggleCollapsible(this)">
        <h4><i class="fa-solid fa-handshake-slash"></i> Scenario: Stakeholders Disagree on Rating</h4>
        <i class="fa-solid fa-chevron-down"></i>
      </div>
      <div class="collapsible-content">
        <p><strong>Example:</strong> The CIO believes they have Level 4 disaster recovery, but the Operations Manager says they've never successfully tested it.</p>
        <p><strong>Response:</strong> "I'm hearing different perspectives here. Let's focus on what we can verify. [Operations Manager], can you describe your most recent DR test? [CIO], what metrics are you using to assess DR maturity? Based on industry best practices, regular testing is required for Level 4. Without test evidence, we should consider Level 2 or 3."</p>
        <p><strong>Approach:</strong> Stay neutral, ask for evidence, refer to maturity definitions, help them reach consensus based on facts.</p>
      </div>

      <div class="collapsible-header" onclick="toggleCollapsible(this)">
        <h4><i class="fa-solid fa-face-frown"></i> Scenario: Customer is Defensive About Low Scores</h4>
        <i class="fa-solid fa-chevron-down"></i>
      </div>
      <div class="collapsible-content">
        <p><strong>Example:</strong> After several Level 1-2 scores, the CISO becomes defensive: "We have excellent security! This assessment is unfair!"</p>
        <p><strong>Response:</strong> "I appreciate your commitment to security. These scores reflect maturity along a journey—they're not a judgment of your team's effort or capability. Many excellent organizations score at Level 2-3 initially. The assessment helps us identify where focused investment will have the most impact. Would it help to review the scoring criteria together?"</p>
        <p><strong>Approach:</strong> Validate their feelings, emphasize growth mindset, reframe scores as opportunities, avoid blame.</p>
      </div>

      <div class="collapsible-header" onclick="toggleCollapsible(this)">
        <h4><i class="fa-solid fa-circle-question"></i> Scenario: "We Don't Know" Responses</h4>
        <i class="fa-solid fa-chevron-down"></i>
      </div>
      <div class="collapsible-content">
        <p><strong>Example:</strong> Multiple participants don't know the answer to questions about vendor contracts or key management.</p>
        <p><strong>Response:</strong> "That's valuable information in itself—if key stakeholders don't know, that typically indicates Level 1 or 2 maturity. Let's mark this for follow-up investigation and make a provisional rating of Level 1. You can update it later once you've verified."</p>
        <p><strong>Approach:</strong> Frame "don't know" as data, assign conservative rating, offer to revisit, ensure follow-up action item is captured.</p>
      </div>

      <h3>Maintaining Momentum</h3>
      <p>Keep the assessment moving while ensuring thoroughness:</p>
      <ul>
        <li><strong>Set time limits</strong> - Allocate ~2-3 minutes per question; use a visible timer</li>
        <li><strong>Park discussions</strong> - "That's an important topic; let's capture it for the roadmap discussion and continue"</li>
        <li><strong>Batch related questions</strong> - "These next 3 questions are all about encryption; let's discuss them together"</li>
        <li><strong>Take strategic breaks</strong> - Break between domains (5 min every 45 min)</li>
        <li><strong>Show progress</strong> - "We're through 3 of 7 domains—great progress!"</li>
      </ul>
    </div>

    <!-- Domain Deep-Dives -->
    <div id="domains" class="guide-section">
      <h2><i class="fa-solid fa-layer-group"></i> Domain Deep-Dives</h2>

      <p>This section provides detailed guidance for each Digital Sovereignty domain. Each domain contains 8 questions organized into three tiers:</p>
      <ul>
        <li><strong>Foundation Tier (Questions 1-3):</strong> Basic capabilities and policies</li>
        <li><strong>Strategic Tier (Questions 4-6):</strong> Advanced implementation and control</li>
        <li><strong>Advanced Tier (Questions 7-8):</strong> Optimization and continuous improvement</li>
      </ul>

      <div class="info-box">
        <h4><i class="fa-solid fa-info-circle"></i> Understanding Points</h4>
        <p>Each question is assigned points (1-8) reflecting its importance within the domain. Higher point values indicate more critical capabilities for achieving sovereignty. The assessment automatically calculates domain scores based on selected maturity levels and point values.</p>
      </div>

      <!-- Domain 1: Data Sovereignty -->
      <h3 style="border-top: 2px solid #0d60f8; padding-top: 2rem; margin-top: 2rem;">
        <i class="fa-solid fa-database"></i> Domain 1: Data Sovereignty
      </h3>

      <div class="info-box tip">
        <h4><i class="fa-solid fa-quote-left"></i> Domain Overview</h4>
        <p>This domain assesses an organization's ultimate control over its data, independent of external jurisdictions or political influences. It goes beyond basic data residency by focusing on legal control, access, and encryption management. Maturity here confirms that data location is actively governed by the organization's legal and business requirements, rather than dictated solely by a cloud provider or foreign law.</p>
      </div>

      <h4>Key Concepts to Explain</h4>
      <ul>
        <li><strong>Data Residency vs. Data Sovereignty:</strong> Residency = where data physically resides; Sovereignty = legal control and ability to resist foreign access demands</li>
        <li><strong>Jurisdictional Control:</strong> Ensuring data and access are governed by domestic law, not foreign legal frameworks like the CLOUD Act</li>
        <li><strong>Encryption Key Management:</strong> The entity controlling encryption keys ultimately controls access to data, regardless of where it's stored</li>
        <li><strong>Data-in-Use Protection:</strong> Protecting data during processing, not just at rest and in transit</li>
      </ul>

      <h4>Common Customer Misconceptions</h4>
      <div class="info-box warning">
        <h4><i class="fa-solid fa-exclamation-triangle"></i> Watch Out For</h4>
        <ul>
          <li><strong>"We use a local cloud region, so we have data sovereignty"</strong> - Physical location alone doesn't guarantee sovereignty if the provider is subject to foreign law</li>
          <li><strong>"Encryption protects our sovereignty"</strong> - Not if the cloud provider controls the keys or can be compelled to decrypt</li>
          <li><strong>"GDPR compliance means we have data sovereignty"</strong> - Compliance is necessary but not sufficient for true sovereignty</li>
          <li><strong>"We don't have sensitive data"</strong> - Most organizations underestimate the sensitivity and value of their data assets</li>
        </ul>
      </div>

      <h4>Domain 1 Question Guide</h4>

      <div class="collapsible-header" onclick="toggleCollapsible(this)">
        <h4><i class="fa-solid fa-1"></i> Q1: Data Residency & Location (1 point - Foundation)</h4>
        <i class="fa-solid fa-chevron-down"></i>
      </div>
      <div class="collapsible-content">
        <p><strong>What this measures:</strong> Whether the organization explicitly controls where data is stored based on legal requirements</p>
        <p><strong>Key questions to ask:</strong></p>
        <ul>
          <li>"Do you have a written data residency policy?"</li>
          <li>"Can you show me which cloud regions your data is stored in?"</li>
          <li>"How do you prevent data from being accidentally stored outside approved regions?"</li>
          <li>"What happens if a cloud provider wants to move your data for operational reasons?"</li>
        </ul>
        <p><strong>Evidence to request:</strong> Data residency policy document, cloud provider contracts specifying regions, configuration screenshots showing geo-restrictions</p>
        <p><strong>Red flags:</strong> "We think it's in [region]", "The cloud provider handles that", "We haven't checked recently"</p>
      </div>

      <div class="collapsible-header" onclick="toggleCollapsible(this)">
        <h4><i class="fa-solid fa-2"></i> Q2: Data Protection & Privacy (2 points - Foundation)</h4>
        <i class="fa-solid fa-chevron-down"></i>
      </div>
      <div class="collapsible-content">
        <p><strong>What this measures:</strong> Compliance with data protection regulations and implementation of privacy controls</p>
        <p><strong>Key questions to ask:</strong></p>
        <ul>
          <li>"Which data protection regulations apply to you? (GDPR, CCPA, PIPL, etc.)"</li>
          <li>"How do you handle data subject rights requests (access, deletion, portability)?"</li>
          <li>"Do you have a Data Protection Officer or equivalent role?"</li>
          <li>"How are cross-border data transfers authorized and tracked?"</li>
        </ul>
        <p><strong>Evidence to request:</strong> Privacy policies, consent management systems, GDPR compliance documentation, Privacy Impact Assessments</p>
        <p><strong>Red flags:</strong> Confusion about applicable regulations, no defined process for data subject requests, relying solely on vendor certifications</p>
      </div>

      <div class="collapsible-header" onclick="toggleCollapsible(this)">
        <h4><i class="fa-solid fa-3"></i> Q3: Data Classification and Inventory (3 points - Foundation)</h4>
        <i class="fa-solid fa-chevron-down"></i>
      </div>
      <div class="collapsible-content">
        <p><strong>What this measures:</strong> Whether the organization knows what data it has, where it is, and how sensitive it is</p>
        <p><strong>Key questions to ask:</strong></p>
        <ul>
          <li>"Do you have a complete inventory of your data assets?"</li>
          <li>"What classification levels do you use? (Public, Internal, Confidential, Restricted)"</li>
          <li>"How do you discover and classify new data automatically?"</li>
          <li>"Who owns each data asset and is accountable for its protection?"</li>
        </ul>
        <p><strong>Evidence to request:</strong> Data inventory/catalog, classification framework document, data discovery tool demonstrations, data ownership registers</p>
        <p><strong>Red flags:</strong> "We're working on that", manual spreadsheet-based tracking, no data ownership assigned</p>
      </div>

      <div class="collapsible-header" onclick="toggleCollapsible(this)">
        <h4><i class="fa-solid fa-4"></i> Q4: Legal & Jurisdictional Control (4 points - Strategic)</h4>
        <i class="fa-solid fa-chevron-down"></i>
      </div>
      <div class="collapsible-content">
        <p><strong>What this measures:</strong> Ability to resist extra-territorial legal demands and maintain domestic legal control</p>
        <p><strong>Key questions to ask:</strong></p>
        <ul>
          <li>"What jurisdiction's law governs your cloud contracts?"</li>
          <li>"How would you respond to a foreign government data access request?"</li>
          <li>"Do you have contractual provisions requiring vendors to notify you of legal demands?"</li>
          <li>"Have you assessed conflicts between foreign laws (CLOUD Act) and domestic requirements?"</li>
        </ul>
        <p><strong>Evidence to request:</strong> Vendor contracts showing governing law clauses, legal risk register, documented escalation procedures</p>
        <p><strong>Red flags:</strong> Contracts governed by foreign law, no notification provisions, unaware of jurisdictional conflicts</p>
      </div>

      <div class="collapsible-header" onclick="toggleCollapsible(this)">
        <h4><i class="fa-solid fa-5"></i> Q5: Cryptographic Key Management Control (6 points - Strategic)</h4>
        <i class="fa-solid fa-chevron-down"></i>
      </div>
      <div class="collapsible-content">
        <p><strong>What this measures:</strong> Whether the organization exclusively controls encryption keys, independent of cloud providers</p>
        <p><strong>Key questions to ask:</strong></p>
        <ul>
          <li>"Who generates and stores your encryption keys?"</li>
          <li>"Can your cloud provider access your encryption keys?"</li>
          <li>"Do you use HSMs (Hardware Security Modules)? Where are they located?"</li>
          <li>"How frequently do you rotate encryption keys?"</li>
          <li>"What would happen if your provider received a legal demand to decrypt your data?"</li>
        </ul>
        <p><strong>Evidence to request:</strong> Key management architecture diagrams, HSM procurement/contracts, key rotation policies, external key management (EKM) solution documentation</p>
        <p><strong>Red flags:</strong> Provider-managed keys, lack of HSMs, no key rotation schedule, unclear about who can access keys</p>
        <p><strong>Note:</strong> This is a 6-point question because key control is fundamental to data sovereignty. Organizations often struggle here.</p>
      </div>

      <div class="collapsible-header" onclick="toggleCollapsible(this)">
        <h4><i class="fa-solid fa-6"></i> Q6: Workload Data Protection & Privacy (5 points - Strategic)</h4>
        <i class="fa-solid fa-chevron-down"></i>
      </div>
      <div class="collapsible-content">
        <p><strong>What this measures:</strong> Protection of data during processing (data-in-use), not just storage and transit</p>
        <p><strong>Key questions to ask:</strong></p>
        <ul>
          <li>"How do you protect data while it's being processed in memory?"</li>
          <li>"Are you using confidential computing or Trusted Execution Environments (TEEs)?"</li>
          <li>"Can cloud administrators access data in memory during processing?"</li>
          <li>"How do you ensure sensitive data isn't logged in plaintext?"</li>
        </ul>
        <p><strong>Evidence to request:</strong> Confidential computing implementations (Intel SGX, AMD SEV, AWS Nitro Enclaves), memory encryption configurations, log sanitization policies</p>
        <p><strong>Red flags:</strong> Unaware of data-in-use protection, relying only on at-rest and in-transit encryption, plaintext logging of sensitive data</p>
        <p><strong>Note:</strong> This is often Level 1-2 for most organizations; confidential computing is still emerging.</p>
      </div>

      <div class="collapsible-header" onclick="toggleCollapsible(this)">
        <h4><i class="fa-solid fa-7"></i> Q7: Data Flow and Transfer Auditing (7 points - Advanced)</h4>
        <i class="fa-solid fa-chevron-down"></i>
      </div>
      <div class="collapsible-content">
        <p><strong>What this measures:</strong> Real-time monitoring and immutable logging of all data movements</p>
        <p><strong>Key questions to ask:</strong></p>
        <ul>
          <li>"Can you show me where your data flows across systems?"</li>
          <li>"Do you have Data Loss Prevention (DLP) tools deployed?"</li>
          <li>"How do you monitor and prevent unauthorized data transfers?"</li>
          <li>"Are your audit logs immutable and stored sovereignly?"</li>
          <li>"How quickly can you detect an unauthorized cross-border data transfer?"</li>
        </ul>
        <p><strong>Evidence to request:</strong> Data flow maps, DLP dashboards, audit log retention policies, SIEM integration, transfer blocking evidence</p>
        <p><strong>Red flags:</strong> No data flow visibility, reactive rather than preventive controls, logs stored with cloud provider</p>
      </div>

      <div class="collapsible-header" onclick="toggleCollapsible(this)">
        <h4><i class="fa-solid fa-8"></i> Q8: Data Access by Third Parties Policies (8 points - Advanced)</h4>
        <i class="fa-solid fa-chevron-down"></i>
      </div>
      <div class="collapsible-content">
        <p><strong>What this measures:</strong> Strict, audited, and revocable control over vendor and partner access to data</p>
        <p><strong>Key questions to ask:</strong></p>
        <ul>
          <li>"Which third parties have access to your data? Why?"</li>
          <li>"Do you use Just-in-Time (JIT) access for vendor support?"</li>
          <li>"How do you monitor and record third-party access sessions?"</li>
          <li>"Can you immediately revoke vendor access in an emergency?"</li>
          <li>"Where are vendor support personnel located geographically?"</li>
        </ul>
        <p><strong>Evidence to request:</strong> Third-party access policies, Privileged Access Management (PAM) systems, session recordings, vendor risk assessments</p>
        <p><strong>Red flags:</strong> Persistent vendor access, no session monitoring, vendors located in concerning jurisdictions, inability to quickly revoke access</p>
        <p><strong>Note:</strong> This is the highest point value question as third-party access is a primary sovereignty risk.</p>
      </div>

      <!-- Remaining Domains (abbreviated for length) -->
      <h3 style="border-top: 2px solid #0d60f8; padding-top: 2rem; margin-top: 2rem;">
        <i class="fa-solid fa-microchip"></i> Domain 2: Technical Sovereignty
      </h3>
      <div class="info-box tip">
        <h4><i class="fa-solid fa-quote-left"></i> Domain Overview</h4>
        <p>Technical Sovereignty evaluates the degree of control an organization maintains over the foundational components of its technology stack—from hardware and firmware to application source code and runtime environments. High maturity signifies deliberate reduction in reliance on proprietary interfaces and single-vendor ecosystems, ensuring the ability to rebuild or migrate critical functions if necessary.</p>
      </div>
      <p><strong>Key Focus Areas:</strong> Technology stack ownership, vendor lock-in mitigation, standardized frameworks, interoperability, hardware provenance, self-hosted runtimes, IP control, future-proofing</p>
      <p><strong>Common Discussion Topics:</strong> Open source adoption, Kubernetes and containerization, multi-cloud strategies, escrow agreements, supply chain security</p>

      <h3 style="border-top: 2px solid #0d60f8; padding-top: 2rem; margin-top: 2rem;">
        <i class="fa-solid fa-gears"></i> Domain 3: Operational Sovereignty
      </h3>
      <div class="info-box tip">
        <h4><i class="fa-solid fa-quote-left"></i> Domain Overview</h4>
        <p>This domain examines the organization's autonomy and independence in executing critical business and IT operations. It ensures that essential functions can be performed without reliance on external human expertise or infrastructure outside the organization's direct control or trusted sovereign borders.</p>
      </div>
      <p><strong>Key Focus Areas:</strong> Process documentation, managed service dependencies, IAM, internal skills, disaster recovery, supply chain vetting, incident response, operational autonomy</p>
      <p><strong>Common Discussion Topics:</strong> Break-glass procedures, in-house vs. outsourced operations, business continuity planning, geopolitical isolation scenarios</p>

      <h3 style="border-top: 2px solid #0d60f8; padding-top: 2rem; margin-top: 2rem;">
        <i class="fa-solid fa-shield-halved"></i> Domain 4: Assurance Sovereignty
      </h3>
      <div class="info-box tip">
        <h4><i class="fa-solid fa-quote-left"></i> Domain Overview</h4>
        <p>Assurance Sovereignty addresses the right, capability, and transparency required to verify the security and compliance claims of both internal systems and external vendors. It's the mechanism by which trust is verified, not assumed, through independent audits and continuous technical validation.</p>
      </div>
      <p><strong>Key Focus Areas:</strong> Audit rights, sovereign SIEM, compliance verification, transparency requirements, sovereign certifications, continuous monitoring, security testing, vulnerability management</p>
      <p><strong>Common Discussion Topics:</strong> Right to audit clauses, SOC 2 Type II, penetration testing, third-party attestations, domestic vs. foreign auditors</p>

      <h3 style="border-top: 2px solid #0d60f8; padding-top: 2rem; margin-top: 2rem;">
        <i class="fa-solid fa-code-branch"></i> Domain 5: Open Source
      </h3>
      <div class="info-box tip">
        <h4><i class="fa-solid fa-quote-left"></i> Domain Overview</h4>
        <p>This domain assesses the organization's strategic use of open-source software to reduce proprietary dependencies, increase transparency, and build internal capabilities. Mature organizations actively contribute to and influence open-source projects critical to their sovereignty goals.</p>
      </div>
      <p><strong>Key Focus Areas:</strong> Open source strategy, community participation, license compliance, vulnerability management, sovereign distributions, contribution policies, internal expertise, project governance</p>
      <p><strong>Common Discussion Topics:</strong> Red Hat Enterprise Linux, Kubernetes, Apache projects, InnerSource, security scanning, open source vs. commercial support</p>

      <h3 style="border-top: 2px solid #0d60f8; padding-top: 2rem; margin-top: 2rem;">
        <i class="fa-solid fa-users-gear"></i> Domain 6: Executive Oversight
      </h3>
      <div class="info-box tip">
        <h4><i class="fa-solid fa-quote-left"></i> Domain Overview</h4>
        <p>Executive Oversight ensures that sovereignty concerns are understood, prioritized, and actively managed at the highest levels of the organization. This domain measures board and C-suite engagement, dedicated budgets, governance structures, and accountability for sovereignty outcomes.</p>
      </div>
      <p><strong>Key Focus Areas:</strong> Board awareness, dedicated governance, budget allocation, sovereignty policies, risk management, accountability, strategic planning, regulatory engagement</p>
      <p><strong>Common Discussion Topics:</strong> Board reporting, sovereignty champions, dedicated budgets vs. embedded costs, KPIs and metrics, regulatory relationships</p>

      <h3 style="border-top: 2px solid #0d60f8; padding-top: 2rem; margin-top: 2rem;">
        <i class="fa-solid fa-handshake"></i> Domain 7: Managed Services
      </h3>
      <div class="info-box tip">
        <h4><i class="fa-solid fa-quote-left"></i> Domain Overview</h4>
        <p>This domain evaluates how the organization manages relationships with external managed service providers while maintaining sovereignty. It addresses vendor selection criteria, contractual controls, geographic restrictions, transition planning, and the balance between operational efficiency and sovereign control.</p>
      </div>
      <p><strong>Key Focus Areas:</strong> Vendor selection criteria, contractual controls, geographic restrictions, data access limitations, performance monitoring, transition planning, alternatives evaluation, insourcing capabilities</p>
      <p><strong>Common Discussion Topics:</strong> Domestic vs. foreign MSPs, data center locations, support personnel jurisdictions, exit strategies, dual-source strategies</p>
    </div>

    <!-- Post-Assessment -->
    <div id="post-assessment" class="guide-section">
      <h2><i class="fa-solid fa-flag-checkered"></i> Post-Assessment Activities</h2>

      <h3>Results Interpretation</h3>
      <p>After completing the assessment, guide the customer through understanding their results.</p>

      <h4>Understanding the Spider Chart</h4>
      <p>The spider/radar chart provides a visual representation of maturity across all domains:</p>
      <ul>
        <li><strong>Balanced shape:</strong> Consistent maturity across domains</li>
        <li><strong>Spikes and valleys:</strong> Strength in some areas, gaps in others</li>
        <li><strong>Small overall size:</strong> Early-stage maturity (common and expected)</li>
        <li><strong>Industry comparison:</strong> Compare against weighted targets for their LOB</li>
      </ul>

      <div class="info-box tip">
        <h4><i class="fa-solid fa-chart-line"></i> Typical Results</h4>
        <p>Most organizations on their first assessment score:</p>
        <ul>
          <li><strong>Overall average:</strong> 30-45% (Managed to Defined levels)</li>
          <li><strong>Strong domains:</strong> Often Executive Oversight, basic Data Protection</li>
          <li><strong>Weak domains:</strong> Often Cryptographic Key Management, Workload Protection, Operational Autonomy</li>
        </ul>
        <p>Reassure customers that these results are normal starting points, not failures.</p>
      </div>

      <h4>Score Discussion Points</h4>
      <ul>
        <li><strong>Celebrate strengths:</strong> "You're scoring well in [domain]—let's talk about how you achieved that"</li>
        <li><strong>Identify quick wins:</strong> "Foundation questions at Level 1 are often easy to advance with policy documentation"</li>
        <li><strong>Highlight strategic gaps:</strong> "The low score in Cryptographic Key Management is concerning given your industry requirements"</li>
        <li><strong>Consider domain weighting:</strong> "Your LOB weights Data Sovereignty at 2×, so gaps here have extra impact"</li>
      </ul>

      <h3>Gap Analysis and Prioritization</h3>
      <p>Work with the customer to translate scores into actionable priorities:</p>

      <h4>Prioritization Framework</h4>
      <table class="maturity-table">
        <thead>
          <tr>
            <th>Priority</th>
            <th>Criteria</th>
            <th>Example</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><strong>Critical (0-3 months)</strong></td>
            <td>Regulatory requirement, high industry weighting, Level 1 on high-point questions</td>
            <td>Implementing HSM-based key management for healthcare patient data</td>
          </tr>
          <tr>
            <td><strong>High (3-6 months)</strong></td>
            <td>Significant sovereignty risk, medium weighting, Level 2 on strategic questions</td>
            <td>Establishing sovereign audit rights with cloud providers</td>
          </tr>
          <tr>
            <td><strong>Medium (6-12 months)</strong></td>
            <td>Important capability, standard weighting, Level 2-3 on foundation questions</td>
            <td>Implementing data classification and discovery tools</td>
          </tr>
          <tr>
            <td><strong>Low (12+ months)</strong></td>
            <td>Optimization, already at Level 3+, advanced questions</td>
            <td>Establishing open source contribution programs</td>
          </tr>
        </tbody>
      </table>

      <h4>Recommended Roadmap Structure</h4>
      <div class="timeline">
        <div class="timeline-item">
          <h5>Phase 1: Foundation (0-6 months)</h5>
          <p>Policy development, basic controls, compliance alignment, data inventory, vendor assessment</p>
        </div>
        <div class="timeline-item">
          <h5>Phase 2: Strategic (6-18 months)</h5>
          <p>Technical implementations, key management, vendor migrations, skills development, tooling deployment</p>
        </div>
        <div class="timeline-item">
          <h5>Phase 3: Advanced (18-36 months)</h5>
          <p>Continuous monitoring, optimization, innovation, industry leadership, operational autonomy</p>
        </div>
      </div>

      <h3>Exporting and Sharing Results</h3>
      <p>Help customers export and distribute results appropriately:</p>
      <ul>
        <li><strong>Export Results:</strong> Use the Export functionality to save as JSON for future import/comparison</li>
        <li><strong>Screenshot the Spider Chart:</strong> Visual summaries are powerful for executive presentations</li>
        <li><strong>Document Key Findings:</strong> Capture top 3-5 gaps and recommendations in a summary document</li>
        <li><strong>Share Appropriately:</strong> Consider audience when sharing (Board vs. technical team vs. vendors)</li>
      </ul>

      <h3>Next Steps and Follow-up</h3>
      <p>Schedule follow-up activities to maintain momentum:</p>

      <div class="info-box">
        <h4><i class="fa-solid fa-calendar"></i> Recommended Follow-up Schedule</h4>
        <ul>
          <li><strong>Week 1:</strong> Send detailed results summary and initial recommendations</li>
          <li><strong>Week 2-3:</strong> Schedule roadmap planning workshop (2 hours)</li>
          <li><strong>Month 2:</strong> Check-in on quick wins and foundation initiatives</li>
          <li><strong>Quarter 2:</strong> Progress review and assessment update for changed answers</li>
          <li><strong>Annual:</strong> Full reassessment to measure improvement</li>
        </ul>
      </div>

      <h3>Engagement Opportunities</h3>
      <p>The assessment often reveals opportunities for further engagement:</p>
      <ul>
        <li><strong>Architecture Reviews:</strong> Deep-dive assessments of specific systems or domains</li>
        <li><strong>Policy Development:</strong> Creating missing governance and compliance documentation</li>
        <li><strong>Technology Selection:</strong> Evaluating sovereign-compliant alternatives to current tools</li>
        <li><strong>Skills Training:</strong> Targeted training on identified capability gaps</li>
        <li><strong>Implementation Services:</strong> Deploying specific solutions (HSMs, DLP, PAM, etc.)</li>
        <li><strong>Managed Services:</strong> Sovereign-compliant managed services to fill operational gaps</li>
      </ul>
    </div>

    <!-- Tips and Best Practices -->
    <div id="tips" class="guide-section">
      <h2><i class="fa-solid fa-lightbulb"></i> Facilitator Tips & Best Practices</h2>

      <h3>Do's</h3>
      <ul>
        <li><strong>Do prepare thoroughly:</strong> Review the customer's industry, known technologies, and recent news</li>
        <li><strong>Do set expectations:</strong> Explain that honest assessment is better than inflated scores</li>
        <li><strong>Do ask for evidence:</strong> "Can you show me?" is your most important question</li>
        <li><strong>Do take detailed notes:</strong> Capture context, concerns, and follow-up items</li>
        <li><strong>Do remain neutral:</strong> You're a facilitator, not a judge; help them self-assess accurately</li>
        <li><strong>Do celebrate progress:</strong> Acknowledge what they're doing well</li>
        <li><strong>Do connect dots:</strong> "Your answer here relates to what you said about [other domain]"</li>
        <li><strong>Do respect time:</strong> Keep things moving; park lengthy discussions for later</li>
        <li><strong>Do follow up:</strong> Send materials promptly and schedule next steps before leaving</li>
      </ul>

      <h3>Don'ts</h3>
      <ul>
        <li><strong>Don't sell during assessment:</strong> Stay consultative; sales conversations come after results review</li>
        <li><strong>Don't accept vague answers:</strong> "We're planning to" or "We're working on it" usually means Level 1</li>
        <li><strong>Don't inflate scores:</strong> It only hurts their planning and your credibility</li>
        <li><strong>Don't argue:</strong> If they insist on a rating despite lack of evidence, document the discrepancy</li>
        <li><strong>Don't rush foundation questions:</strong> They're as important as advanced questions for overall maturity</li>
        <li><strong>Don't skip breaks:</strong> Assessment fatigue leads to poor decisions</li>
        <li><strong>Don't assume technical knowledge:</strong> Explain concepts clearly for non-technical executives</li>
        <li><strong>Don't dismiss concerns:</strong> If they're worried about something, explore it</li>
      </ul>

      <h3>Remote Facilitation Tips</h3>
      <p>When conducting assessments remotely:</p>
      <ul>
        <li>Use video to read body language and maintain engagement</li>
        <li>Share your screen showing the assessment tool for transparency</li>
        <li>Use collaboration tools (digital whiteboard) for visual discussions</li>
        <li>Record the session (with permission) for reference</li>
        <li>Check in verbally more frequently: "Is everyone still with me?"</li>
        <li>Use chat for parking lot items and questions</li>
        <li>Send materials in advance as remote participants may not have printed copies</li>
      </ul>

      <h3>Dealing with Challenging Personalities</h3>

      <div class="collapsible-header" onclick="toggleCollapsible(this)">
        <h4><i class="fa-solid fa-user-tie"></i> The Over-Confident Executive</h4>
        <i class="fa-solid fa-chevron-down"></i>
      </div>
      <div class="collapsible-content">
        <p><strong>Behavior:</strong> Claims high maturity without evidence, dismisses concerns, believes "we have the best security"</p>
        <p><strong>Approach:</strong> Acknowledge their confidence, then request specific evidence. Use data and industry benchmarks. Ask their technical team to verify claims. Frame lower scores as "industry-standard journey" rather than failures.</p>
      </div>

      <div class="collapsible-header" onclick="toggleCollapsible(this)">
        <h4><i class="fa-solid fa-user-clock"></i> The "Too Busy" Participant</h4>
        <i class="fa-solid fa-chevron-down"></i>
      </div>
      <div class="collapsible-content">
        <p><strong>Behavior:</strong> Late to session, distracted, checking phone, wants to rush through</p>
        <p><strong>Approach:</strong> Respectfully emphasize the value of their time investment. Show early results to demonstrate value. Offer to reschedule if they can't focus. Break into shorter sessions if needed.</p>
      </div>

      <div class="collapsible-header" onclick="toggleCollapsible(this)">
        <h4><i class="fa-solid fa-user-secret"></i> The Defensive CISO</h4>
        <i class="fa-solid fa-chevron-down"></i>
      </div>
      <div class="collapsible-content">
        <p><strong>Behavior:</strong> Takes low scores personally, explains why gaps aren't their fault, blames budget/management</p>
        <p><strong>Approach:</strong> Emphasize this is organizational assessment, not personal evaluation. Validate resource constraints. Position results as ammunition for budget requests. Frame gaps as opportunities to demonstrate need for investment.</p>
      </div>

      <div class="collapsible-header" onclick="toggleCollapsible(this)">
        <h4><i class="fa-solid fa-user-graduate"></i> The Technical Perfectionist</h4>
        <i class="fa-solid fa-chevron-down"></i>
      </div>
      <div class="collapsible-content">
        <p><strong>Behavior:</strong> Debates every nuance, wants to discuss technical details extensively, struggles to choose between maturity levels</p>
        <p><strong>Approach:</strong> Appreciate their thoroughness. Set time limits for each question. Offer to deep-dive on specific topics afterward. Remind that perfect accuracy is less important than directional understanding. Use "parking lot" for detailed technical discussions.</p>
      </div>
    </div>

    <!-- Appendix -->
    <div id="appendix" class="guide-section">
      <h2><i class="fa-solid fa-book"></i> Appendix</h2>

      <h3>Downloadable Templates</h3>
      <p>Ready-to-use templates are available to support your assessment delivery:</p>
      <div class="info-box success">
        <h4><i class="fa-solid fa-file-alt"></i> Access All Templates</h4>
        <p>Visit the <a href="templates/index.html" style="color: #0d60f8; font-weight: 600; text-decoration: none;">Templates Library</a> for:</p>
        <ul style="margin-bottom: 0;">
          <li><strong>Full-Day Workshop Agenda:</strong> Comprehensive one-day format with detailed schedule</li>
          <li><strong>Short Assessment Agenda:</strong> 2-hour focused assessment format</li>
          <li><strong>Email Templates:</strong> Pre-written emails for invitation, preparation, follow-up, and check-ins</li>
          <li><strong>Executive Summary Template:</strong> One-page results summary for C-suite presentation</li>
        </ul>
      </div>

      <h3>Glossary of Key Terms</h3>
      <table class="maturity-table">
        <thead>
          <tr>
            <th>Term</th>
            <th>Definition</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><strong>BYOK</strong></td>
            <td>Bring Your Own Key - Customer-generated encryption keys imported to cloud provider</td>
          </tr>
          <tr>
            <td><strong>CLOUD Act</strong></td>
            <td>US law allowing government access to data held by US companies regardless of location</td>
          </tr>
          <tr>
            <td><strong>Confidential Computing</strong></td>
            <td>Protection of data during processing using hardware-based secure enclaves</td>
          </tr>
          <tr>
            <td><strong>Data Residency</strong></td>
            <td>Physical location where data is stored</td>
          </tr>
          <tr>
            <td><strong>Data Sovereignty</strong></td>
            <td>Legal and technical control over data, including ability to resist foreign access demands</td>
          </tr>
          <tr>
            <td><strong>DLP</strong></td>
            <td>Data Loss Prevention - Tools to monitor and prevent unauthorized data transfers</td>
          </tr>
          <tr>
            <td><strong>EKM</strong></td>
            <td>External Key Management - Encryption keys managed outside cloud provider infrastructure</td>
          </tr>
          <tr>
            <td><strong>HSM</strong></td>
            <td>Hardware Security Module - Dedicated cryptographic processor for key management</td>
          </tr>
          <tr>
            <td><strong>PAM</strong></td>
            <td>Privileged Access Management - System for controlling and monitoring administrative access</td>
          </tr>
          <tr>
            <td><strong>SCA</strong></td>
            <td>Software Composition Analysis - Scanning third-party code for vulnerabilities</td>
          </tr>
          <tr>
            <td><strong>TEE</strong></td>
            <td>Trusted Execution Environment - Secure area of processor for sensitive operations</td>
          </tr>
          <tr>
            <td><strong>Zero Trust</strong></td>
            <td>Security model assuming no implicit trust, requiring verification for all access</td>
          </tr>
        </tbody>
      </table>

      <h3>Reference Materials</h3>
      <ul>
        <li><strong>CMMI Framework:</strong> https://cmmiinstitute.com/</li>
        <li><strong>GDPR:</strong> General Data Protection Regulation (EU)</li>
        <li><strong>NIS2 Directive:</strong> Network and Information Security Directive (EU)</li>
        <li><strong>DORA:</strong> Digital Operational Resilience Act (EU)</li>
        <li><strong>FedRAMP:</strong> Federal Risk and Authorization Management Program (US)</li>
        <li><strong>Cloud Security Alliance:</strong> https://cloudsecurityalliance.org/</li>
      </ul>

      <h3>Sample Email Templates</h3>

      <h4>Pre-Assessment Email</h4>
      <div class="info-box">
        <p><strong>Subject:</strong> Preparation for Digital Sovereignty Maturity Assessment - [Date]</p>
        <p>Dear [Stakeholders],</p>
        <p>Thank you for scheduling a Full Maturity Assessment. This session will evaluate your organization's Digital Sovereignty capabilities across 7 key domains using a proven 5-level maturity framework.</p>
        <p><strong>Session Details:</strong><br>
        Date/Time: [Date/Time]<br>
        Duration: 2-4 hours<br>
        Location/Link: [Details]</p>
        <p><strong>Required Participants:</strong> CIO/CTO, CISO, Cloud/Infrastructure Lead, Compliance Officer</p>
        <p><strong>Please prepare:</strong></p>
        <ul>
          <li>List of cloud providers and services used</li>
          <li>Current compliance frameworks and certifications</li>
          <li>Data classification and residency policies</li>
          <li>Key vendor relationships and contracts</li>
        </ul>
        <p>Looking forward to our session.</p>
        <p>Best regards,<br>[Your Name]</p>
      </div>

      <h4>Post-Assessment Email</h4>
      <div class="info-box">
        <p><strong>Subject:</strong> Digital Sovereignty Assessment Results and Next Steps</p>
        <p>Dear [Stakeholders],</p>
        <p>Thank you for participating in yesterday's maturity assessment. Your engagement and candor were excellent.</p>
        <p><strong>Key Findings:</strong></p>
        <ul>
          <li>Overall maturity: [X]% ([Maturity Level])</li>
          <li>Strongest domain: [Domain] at [Y]%</li>
          <li>Priority gap: [Domain] at [Z]%</li>
        </ul>
        <p>Attached you'll find:</p>
        <ul>
          <li>Detailed results export</li>
          <li>Spider chart visualization</li>
          <li>Initial recommendations summary</li>
        </ul>
        <p><strong>Recommended Next Steps:</strong></p>
        <ol>
          <li>Review results with your teams (Week 1)</li>
          <li>Roadmap planning workshop (Week 2-3)</li>
          <li>Prioritize quick wins for immediate action</li>
        </ol>
        <p>I'll follow up next week to schedule our roadmap session.</p>
        <p>Best regards,<br>[Your Name]</p>
      </div>

      <h3>Quick Reference: Maturity Level Indicators</h3>
      <table class="maturity-table">
        <thead>
          <tr>
            <th>Level</th>
            <th>Key Indicators</th>
            <th>Common Language</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><span class="level-badge level-1">1</span></td>
            <td>No policy, ad-hoc, reactive, "we're planning to"</td>
            <td>"We know we need to do this"</td>
          </tr>
          <tr>
            <td><span class="level-badge level-2">2</span></td>
            <td>Draft policies, pilots, project plans, some implementation</td>
            <td>"We're working on it"</td>
          </tr>
          <tr>
            <td><span class="level-badge level-3">3</span></td>
            <td>Approved policies, widespread deployment, documented standards</td>
            <td>"We have this in place"</td>
          </tr>
          <tr>
            <td><span class="level-badge level-4">4</span></td>
            <td>Metrics, dashboards, KPIs, regular reporting, measured outcomes</td>
            <td>"We measure and optimize this"</td>
          </tr>
          <tr>
            <td><span class="level-badge level-5">5</span></td>
            <td>Continuous improvement, innovation, industry leadership</td>
            <td>"We're leading the industry"</td>
          </tr>
        </tbody>
      </table>
    </div>

  </div>

  <script>
    function toggleCollapsible(header) {
      header.classList.toggle('active');
      const content = header.nextElementSibling;
      content.classList.toggle('active');
    }

    // Smooth scrolling for TOC links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
        }
      });
    });
  </script>
</body>
</html>
