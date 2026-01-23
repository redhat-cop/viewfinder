<!doctype html>
<html lang="en-us" class="pf-theme-dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Operation Sovereign Shield - Digital Sovereignty Escape Room</title>

  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/brands.css" />
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="css/tab-dark.css" />
  <link rel="stylesheet" href="css/patternfly.css" />
  <link rel="stylesheet" href="css/patternfly-addons.css" />

  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://kit.fontawesome.com/8a8c57f9cf.js" crossorigin="anonymous"></script>

  <style>
    body {
      background-color: #151515 !important;
      color: #ccc !important;
    }

    .escape-room-wrapper {
      min-height: calc(100vh - 200px);
    }

    .escape-room-header {
      text-align: center;
      padding: 3rem 0 2rem 0;
      border-bottom: 2px solid #0d60f8;
      margin-bottom: 3rem;
    }

    .escape-room-header h1 {
      color: #9ec7fc;
      font-size: 2.8rem;
      margin-bottom: 0.5rem;
    }

    .escape-room-header .codename {
      color: #12bbd4;
      font-size: 1.5rem;
      font-weight: 600;
      margin-bottom: 1rem;
    }

    .escape-room-header .tagline {
      color: #ccc;
      font-size: 1.1rem;
      max-width: 800px;
      margin: 0 auto;
      line-height: 1.6;
    }

    .section-card {
      background: #2a2a2a;
      border: 1px solid #444;
      border-radius: 8px;
      padding: 2rem;
      margin-bottom: 2rem;
      transition: all 0.3s ease;
    }

    .section-card:hover {
      border-color: #0d60f8;
      box-shadow: 0 4px 16px rgba(13, 96, 248, 0.2);
    }

    .section-header {
      display: flex;
      align-items: center;
      gap: 1rem;
      margin-bottom: 1.5rem;
      padding-bottom: 1rem;
      border-bottom: 2px solid #444;
    }

    .section-header i {
      font-size: 2.5rem;
      color: #12bbd4;
    }

    .section-header h2 {
      color: #9ec7fc;
      font-size: 1.8rem;
      margin: 0;
    }

    .section-content {
      color: #ccc;
      line-height: 1.8;
      font-size: 1.05rem;
    }

    .section-content p {
      margin-bottom: 1rem;
    }

    .benefits-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 1.5rem;
      margin-top: 1.5rem;
    }

    .benefit-item {
      background: #1a1a1a;
      padding: 1.5rem;
      border-radius: 6px;
      border-left: 4px solid #0d60f8;
    }

    .benefit-item h3 {
      color: #12bbd4;
      font-size: 1.2rem;
      margin-bottom: 0.75rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .benefit-item h3 i {
      color: #0d60f8;
    }

    .benefit-item p {
      color: #ccc;
      margin: 0;
      line-height: 1.6;
    }

    .info-box {
      background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%);
      border: 2px solid #0d60f8;
      border-radius: 8px;
      padding: 1.5rem;
      margin: 1.5rem 0;
    }

    .info-box-header {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      margin-bottom: 1rem;
    }

    .info-box-header i {
      font-size: 1.5rem;
      color: #12bbd4;
    }

    .info-box-header h3 {
      color: #9ec7fc;
      font-size: 1.3rem;
      margin: 0;
    }

    .info-box p {
      color: #ccc;
      margin: 0;
      line-height: 1.6;
    }

    .topics-list {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 1rem;
      margin-top: 1.5rem;
    }

    .topic-badge {
      background: #1a1a1a;
      border: 1px solid #555;
      border-radius: 6px;
      padding: 1rem;
      display: flex;
      align-items: center;
      gap: 0.75rem;
      transition: all 0.2s ease;
    }

    .topic-badge:hover {
      border-color: #0d60f8;
      background: #252525;
    }

    .topic-badge i {
      font-size: 1.5rem;
      color: #12bbd4;
    }

    .topic-badge span {
      color: #ccc;
      font-weight: 500;
    }

    .cta-section {
      text-align: center;
      padding: 3rem 0;
      margin-top: 2rem;
      border-top: 2px solid #444;
    }

    .cta-button {
      display: inline-block;
      padding: 1.25rem 2.5rem;
      background: linear-gradient(135deg, #0d60f8 0%, #004cbf 100%);
      color: #fff;
      text-decoration: none;
      border-radius: 6px;
      font-size: 1.2rem;
      font-weight: 600;
      transition: all 0.3s ease;
      border: none;
      cursor: pointer;
      margin: 0.5rem;
    }

    .cta-button:hover {
      background: linear-gradient(135deg, #4d90fe 0%, #0d60f8 100%);
      box-shadow: 0 6px 20px rgba(13, 96, 248, 0.4);
      transform: translateY(-2px);
      color: #fff;
    }

    .cta-button i {
      margin-right: 0.5rem;
    }

    .cta-button.secondary {
      background: #444;
    }

    .cta-button.secondary:hover {
      background: #555;
      box-shadow: 0 6px 20px rgba(255, 255, 255, 0.1);
    }

    @media (max-width: 768px) {
      .escape-room-header h1 {
        font-size: 2rem;
      }

      .escape-room-header .codename {
        font-size: 1.2rem;
      }

      .benefits-grid,
      .topics-list {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>

<body>
  <header class="pf-c-page__header">
    <div class="pf-c-page__header-brand">
      <div class="pf-c-page__header-brand-toggle"></div>
      <a class="pf-c-page__header-brand-link" href="index.php">
        <img class="pf-c-brand" src="images/viewfinder-logo.png" alt="Viewfinder logo" />
      </a>
    </div>
    <div class="pf-c-page__header-tools">
      <div class="widget">
        <a href="index.php"><button><i class="fa-solid fa-home"></i> Home</button></a>
      </div>
    </div>
  </header>

  <div class="escape-room-wrapper">
    <div class="container" style="max-width: 1200px; margin: 0 auto;">

      <!-- Header -->
      <div class="escape-room-header">
        <div class="codename">
          <i class="fa-solid fa-shield-halved"></i> CODENAME: OPERATION SOVEREIGN SHIELD
        </div>
        <h1>Digital Sovereignty Escape Room</h1>
        <p class="tagline">
          An immersive executive challenge designed to accelerate strategic decision-making in the digital sovereignty landscape
        </p>
      </div>

      <!-- Description Section -->
      <div class="section-card">
        <div class="section-header">
          <i class="fa-solid fa-puzzle-piece"></i>
          <h2>The Challenge</h2>
        </div>
        <div class="section-content">
          <p>
            The Digital Sovereignty Escape Room (Codename Operation Sovereign Shield) is a fast-paced activity designed to test and enhance your executive team's ability to make rapid, informed decisions about digital sovereignty challenges.
          </p>
          <p>
            This time-bound experience allows you to make critical decisions based on evidence provided by your team. The puzzles vary in complexity - some will take longer than others, but don't get stuck on one! Our facilitators can provide tips if required, but this could cost you additional time!
          </p>
        </div>
      </div>

      <!-- Duration & Format -->
      <div class="section-card">
        <div class="section-header">
          <i class="fa-solid fa-clock"></i>
          <h2>Duration & Format</h2>
        </div>
        <div class="section-content">
          <div class="info-box">
            <div class="info-box-header">
              <i class="fa-solid fa-calendar-days"></i>
              <h3>Experience Timeline (2 Hours Total)</h3>
            </div>
            <p>
              <strong>Phase 1 - Pre-Briefing:</strong> Introduction to the scenario, team formation, and mission objectives<br>
              <strong>Phase 2 - Executive Challenge:</strong> Approximately 45 minutes of intense, hands-on problem-solving<br>
              <strong>Phase 3 - Debrief & Action Planning:</strong> Reflection, key takeaways, and strategic planning session
            </p>
          </div>

          <div class="info-box">
            <div class="info-box-header">
              <i class="fa-solid fa-users"></i>
              <h3>Team Configuration</h3>
            </div>
            <p>
              Teams of <strong>4-8 executives</strong> work collaboratively through puzzles and challenges with the guidance of <strong>Red Hat Subject Matter Experts</strong>. This size ensures everyone can actively participate while maintaining group dynamics.
            </p>
          </div>
        </div>
      </div>

      <!-- Topics Covered -->
      <div class="section-card">
        <div class="section-header">
          <i class="fa-solid fa-lightbulb"></i>
          <h2>Challenge Topics</h2>
        </div>
        <div class="section-content">
          <p>The puzzles cover critical digital sovereignty topics with a focus on how open source principles can help achieve digital independence:</p>

          <div class="topics-list">
            <div class="topic-badge">
              <i class="fa-solid fa-globe"></i>
              <span>Data Residency</span>
            </div>
            <div class="topic-badge">
              <i class="fa-solid fa-lock"></i>
              <span>Vendor Lock-In</span>
            </div>
            <div class="topic-badge">
              <i class="fa-solid fa-link"></i>
              <span>Supply Chain Dependencies</span>
            </div>
            <div class="topic-badge">
              <i class="fa-solid fa-scale-balanced"></i>
              <span>Regulatory Navigation</span>
            </div>
            <div class="topic-badge">
              <i class="fa-solid fa-code"></i>
              <span>Open Source Principles</span>
            </div>
            <div class="topic-badge">
              <i class="fa-solid fa-shield"></i>
              <span>Digital Independence</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Benefits -->
      <div class="section-card">
        <div class="section-header">
          <i class="fa-solid fa-trophy"></i>
          <h2>Key Benefits</h2>
        </div>
        <div class="section-content">
          <p>The Executive Challenge offers several transformative outcomes for leadership teams:</p>

          <div class="benefits-grid">
            <div class="benefit-item">
              <h3><i class="fa-solid fa-bullseye"></i> Strategic Alignment</h3>
              <p>Accelerate strategic alignment among leadership by experiencing digital sovereignty challenges together in a collaborative environment.</p>
            </div>

            <div class="benefit-item">
              <h3><i class="fa-solid fa-shield-halved"></i> Risk Mitigation</h3>
              <p>Experiential learning in a safe environment allows teams to understand and mitigate digital sovereignty risks without real-world consequences.</p>
            </div>

            <div class="benefit-item">
              <h3><i class="fa-solid fa-gauge-high"></i> Enhanced Decision-Making</h3>
              <p>Build decision-making skills under pressure, preparing executives to make critical sovereignty choices when they matter most.</p>
            </div>

            <div class="benefit-item">
              <h3><i class="fa-solid fa-rocket"></i> Future-Proofing</h3>
              <p>Build resilient digital foundations by understanding the principles and practices needed for long-term digital independence.</p>
            </div>

            <div class="benefit-item">
              <h3><i class="fa-solid fa-eye"></i> Demystify Complexity</h3>
              <p>Make complex concepts like open source principles and data governance immediately relevant and understandable for executives.</p>
            </div>

            <div class="benefit-item">
              <h3><i class="fa-solid fa-users-gear"></i> Team Building</h3>
              <p>Strengthen executive team cohesion through shared problem-solving and collaborative decision-making under realistic constraints.</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Call to Action -->
      <div class="cta-section">
        <h2 style="color: #9ec7fc; margin-bottom: 1.5rem; font-size: 2rem;">Ready to Accept the Challenge?</h2>
        <p style="color: #ccc; max-width: 700px; margin: 0 auto 2rem auto; font-size: 1.1rem;">
          Contact Red Hat to schedule Operation Sovereign Shield for your executive team and experience the future of digital sovereignty decision-making.
        </p>
        <a href="index.php" class="cta-button secondary">
          <i class="fa-solid fa-home"></i> Return to Home
        </a>
      </div>

    </div>
  </div>

  <footer class="disclaimer-footer">
    <p><strong>Red Hat Disclaimer:</strong> This application is provided for informational purposes only. The information is provided "as is" with no guarantee or warranty of accuracy, completeness, or fitness for a particular purpose.</p>
  </footer>

</body>
</html>
