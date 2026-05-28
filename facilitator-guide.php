<!doctype html>
<html lang="en-us" class="pf-theme-dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Enablement Guides - Choose Your Level</title>
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

    .page-header {
      text-align: center;
      margin-bottom: 3rem;
      padding-bottom: 2rem;
      border-bottom: 2px solid #444;
    }

    .page-header h1 {
      color: #9ec7fc;
      font-size: 2.5rem;
      font-weight: 600;
      margin: 0 0 1rem 0;
    }

    .page-header .subtitle {
      color: #999;
      font-size: 1.2rem;
      max-width: 800px;
      margin: 0 auto;
    }

    .guide-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
      gap: 2rem;
      margin: 2rem 0;
    }

    .guide-card {
      background: #2a2a2a;
      border: 2px solid #444;
      border-radius: 8px;
      padding: 2rem;
      transition: all 0.3s ease;
      display: flex;
      flex-direction: column;
    }

    .guide-card:hover {
      border-color: #0d60f8;
      transform: translateY(-4px);
      box-shadow: 0 8px 24px rgba(13, 96, 248, 0.3);
    }

    .guide-card .level-badge {
      display: inline-block;
      padding: 0.5rem 1rem;
      border-radius: 4px;
      font-size: 0.85rem;
      font-weight: 700;
      margin-bottom: 1rem;
      text-align: center;
    }

    .guide-card .level-badge.level-101 {
      background: #2aaa04;
      color: #fff;
    }

    .guide-card .level-badge.level-201 {
      background: #0d60f8;
      color: #fff;
    }

    .guide-card .level-badge.level-301 {
      background: #a855f7;
      color: #fff;
    }

    .guide-card h2 {
      color: #fff;
      font-size: 1.6rem;
      margin: 0 0 1rem 0;
    }

    .guide-card .subtitle-text {
      color: #9ec7fc;
      font-size: 1rem;
      font-weight: 600;
      margin-bottom: 1rem;
    }

    .guide-card p {
      color: #ccc;
      line-height: 1.6;
      margin-bottom: 1rem;
      flex-grow: 1;
    }

    .guide-card .meta {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 1rem;
      margin: 1.5rem 0;
      padding: 1rem;
      background: #1f1f1f;
      border-radius: 4px;
    }

    .guide-card .meta-item {
      text-align: center;
    }

    .guide-card .meta-label {
      color: #999;
      font-size: 0.8rem;
      display: block;
      margin-bottom: 0.25rem;
    }

    .guide-card .meta-value {
      color: #fff;
      font-size: 1.1rem;
      font-weight: 600;
    }

    .guide-card ul {
      color: #ccc;
      margin: 1rem 0;
      padding-left: 1.5rem;
    }

    .guide-card li {
      margin-bottom: 0.5rem;
    }

    .guide-card .btn {
      display: block;
      width: 100%;
      padding: 0.875rem 1.5rem;
      background: #0d60f8;
      color: #fff;
      text-align: center;
      text-decoration: none;
      border-radius: 4px;
      font-weight: 600;
      font-size: 1rem;
      transition: all 0.3s ease;
      margin-top: auto;
    }

    .guide-card .btn:hover {
      background: #0a4fc5;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(13, 96, 248, 0.4);
      color: #fff;
    }

    .guide-card .btn.coming-soon {
      background: #666;
      cursor: not-allowed;
    }

    .guide-card .btn.coming-soon:hover {
      background: #666;
      transform: none;
      box-shadow: none;
    }

    .info-box {
      background: #2a2a2a;
      border-left: 4px solid #12bbd4;
      padding: 1.5rem;
      margin: 2rem 0;
      border-radius: 4px;
    }

    .info-box h3 {
      color: #fff;
      margin: 0 0 1rem 0;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .info-box h3 i {
      color: #12bbd4;
    }

    .info-box p {
      color: #ccc;
      margin: 0;
      line-height: 1.6;
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

    @media (max-width: 768px) {
      .guide-grid {
        grid-template-columns: 1fr;
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
        <a href="templates/index.html"><button><i class="fa-solid fa-download"></i> Templates</button></a>
      </div>
    </div>
  </header>

  <div class="container">
    <!-- Page Header -->
    <div class="page-header">
      <h1><i class="fa-solid fa-book-open"></i> Workshop Enablement Guides</h1>
      <p class="subtitle">Choose the right guide for your audience and session objectives</p>
    </div>

    <!-- Selection Guidance -->
    <div class="info-box">
      <h3><i class="fa-solid fa-compass"></i> Which Guide Should I Use?</h3>
      <p>Select your enablement guide based on your <strong>audience</strong> and <strong>session depth</strong>:</p>
      <ul style="margin: 0.75rem 0 0 1.5rem;">
        <li><strong>New to Digital Sovereignty?</strong> Start with the 101 guide</li>
        <li><strong>Conducting full assessment?</strong> Use the 201 guide</li>
        <li><strong>Ready to take action on results?</strong> Use the 301 guide for quick wins and remediation</li>
      </ul>
    </div>

    <!-- Guide Cards -->
    <div class="guide-grid">
      <!-- 101 Guide -->
      <div class="guide-card">
        <div class="level-badge level-101">
          <i class="fa-solid fa-graduation-cap"></i> LEVEL 101
        </div>
        <h2>Introduction to Digital Sovereignty</h2>
        <p class="subtitle-text">Executive Overview & Business Value</p>
        <p>A business-focused introduction designed for executives, decision-makers, and teams new to Digital Sovereignty concepts. Covers the "why" and "what" without deep technical detail.</p>

        <p><strong>Topics Covered:</strong></p>
        <ul>
          <li>What is Digital Sovereignty?</li>
          <li>Business drivers and regulatory landscape</li>
          <li>High-level overview of 7 domains (5 main + 2 sub-pillars)</li>
          <li>Maturity model introduction</li>
          <li>Assessment options and next steps</li>
        </ul>

        <p><strong>Best For:</strong> C-suite, Board members, business stakeholders, newcomers to Digital Sovereignty</p>

        <a href="facilitator-guide-101.php" target="_blank" class="btn">
          <i class="fa-solid fa-book-open"></i> Open 101 Guide
        </a>
        <a href="Enablement-Guide-101.pdf" target="_blank" class="btn" style="background: #2a2a2a; margin-top: 0.5rem; border: 1px solid #444;">
          <i class="fa-solid fa-file-pdf"></i> Download PDF (167 KB)
        </a>
      </div>

      <!-- 201 Guide -->
      <div class="guide-card">
        <div class="level-badge level-201">
          <i class="fa-solid fa-graduation-cap"></i> LEVEL 201
        </div>
        <h2>Domain Overview & Assessment</h2>
        <p class="subtitle-text">Comprehensive Maturity Assessment</p>
        <p>Complete guide for conducting full maturity assessments across all seven Digital Sovereignty domains. Includes detailed facilitation guidance, scoring methodology, and domain deep-dives.</p>

        <p><strong>Topics Covered:</strong></p>
        <ul>
          <li>Pre-assessment preparation</li>
          <li>Facilitation methodology</li>
          <li>Deep-dive into all 7 domains (5 main + 2 sub-pillars)</li>
          <li>Scoring and maturity calculations</li>
          <li>Results interpretation and roadmapping</li>
        </ul>

        <p><strong>Best For:</strong> Technical managers, solution architects, experienced facilitators conducting full assessments</p>

        <a href="facilitator-guide-201.php" target="_blank" class="btn">
          <i class="fa-solid fa-book-open"></i> Open 201 Guide
        </a>
        <a href="Enablement-Guide-201.pdf" target="_blank" class="btn" style="background: #2a2a2a; margin-top: 0.5rem; border: 1px solid #444;">
          <i class="fa-solid fa-file-pdf"></i> Download PDF (739 KB)
        </a>
      </div>

      <!-- 301 Guide -->
      <div class="guide-card">
        <div class="level-badge level-301">
          <i class="fa-solid fa-rocket"></i> LEVEL 301
        </div>
        <h2>Quick Wins & Remediation</h2>
        <p class="subtitle-text">Post-Assessment Action Planning</p>
        <p>Practical guide for turning assessment results into action. Includes quick wins (30/60/90 days), level-by-level remediation roadmaps, and scenario-based guidance to help teams prioritize and execute sovereignty improvements.</p>

        <p><strong>Topics Covered:</strong></p>
        <ul>
          <li>Quick wins by domain (30/60/90 day actions)</li>
          <li>Remediation roadmaps for each maturity level</li>
          <li>Common scenarios and action plans</li>
          <li>Templates and resource library</li>
          <li>Prioritization and roadmap planning</li>
        </ul>

        <p><strong>Best For:</strong> Teams with completed assessments, project managers, technical leads executing improvements</p>

        <a href="facilitator-guide-301.php" target="_blank" class="btn">
          <i class="fa-solid fa-book-open"></i> Open 301 Guide
        </a>
        <a href="Enablement-Guide-301.pdf" target="_blank" class="btn" style="background: #2a2a2a; margin-top: 0.5rem; border: 1px solid #444;">
          <i class="fa-solid fa-file-pdf"></i> Download PDF (302 KB)
        </a>
      </div>
    </div>

    <!-- Additional Resources -->
    <div class="info-box" style="border-left-color: #f0ab00;">
      <h3><i class="fa-solid fa-download"></i> Additional Workshop Materials</h3>
      <p>All guides are complemented by our <strong>Workshop Templates Library</strong> which includes:</p>
      <ul style="margin: 0.75rem 0 0 1.5rem;">
        <li>Full-day and short workshop agendas</li>
        <li>Email templates for workshop communication</li>
        <li>Executive summary templates for C-suite presentations</li>
        <li>Downloadable PDF versions of all materials</li>
      </ul>
      <p style="margin-top: 1rem;">
        <a href="templates/index.html" style="color: #f0ab00; font-weight: 600;">
          <i class="fa-solid fa-arrow-right"></i> Access Templates Library
        </a>
      </p>
    </div>

  </div>

  <footer>
    <p>&copy; 2026 Red Hat, Inc. | Digital Sovereignty Assessment Platform</p>
  </footer>
</body>
</html>
