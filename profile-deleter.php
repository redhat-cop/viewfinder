<!doctype html>
<?php
/**
 * Profile Deleter
 *
 * Web-based tool for deleting custom assessment profiles
 * Hidden from main navigation - access via direct URL only
 */

session_start();

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/error-pages/error-handler.php';
require_once __DIR__ . '/includes/Config.php';
require_once __DIR__ . '/includes/Security.php';
require_once __DIR__ . '/includes/Logger.php';

ErrorHandler::register();
?>

<html lang="en-us" class="pf-theme-dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Profile Deleter - Viewfinder Maturity Assessment</title>

  <!-- Existing application styles -->
  <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/brands.css" />
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="css/patternfly.css" />
  <link rel="stylesheet" href="css/patternfly-addons.css" />

  <!-- Wizard styles (reused for consistency) -->
  <link rel="stylesheet" href="css/profile-wizard.css" />

  <!-- JavaScript libraries -->
  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
  <script src="https://kit.fontawesome.com/8a8c57f9cf.js" crossorigin="anonymous"></script>
  <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet" />

  <style>
    /* Profile card styles */
    .profile-cards {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 1.5rem;
      margin: 2rem 0;
    }

    .profile-card {
      background-color: #2a2a2a;
      border: 2px solid #444;
      border-radius: 8px;
      padding: 1.5rem;
      transition: all 0.3s;
      cursor: pointer;
    }

    .profile-card:hover {
      border-color: #0d60f8;
      box-shadow: 0 4px 12px rgba(13, 96, 248, 0.3);
    }

    .profile-card input[type="radio"] {
      margin-right: 0.75rem;
      width: auto;
    }

    .profile-card input[type="radio"]:checked ~ label {
      color: #12bbd4;
    }

    .profile-card.selected {
      border-color: #0d60f8;
      background-color: #1a3a5a;
    }

    .profile-card-header {
      display: flex;
      align-items: center;
      margin-bottom: 1rem;
      padding-bottom: 1rem;
      border-bottom: 1px solid #444;
    }

    .profile-card-header label {
      cursor: pointer;
      margin: 0;
      flex: 1;
    }

    .profile-card-header h3 {
      margin: 0;
      font-size: 1.25rem;
      color: #fff;
    }

    .profile-card-body p {
      margin: 0.5rem 0;
      color: #ccc;
    }

    .badge {
      display: inline-block;
      padding: 0.25rem 0.75rem;
      border-radius: 12px;
      font-size: 0.8rem;
      font-weight: 500;
    }

    .badge-success {
      background-color: #5ba352;
      color: #fff;
    }

    .badge-secondary {
      background-color: #666;
      color: #fff;
    }

    .no-profiles {
      text-align: center;
      padding: 4rem 2rem;
      color: #ccc;
    }

    .no-profiles i {
      color: #12bbd4;
      margin-bottom: 1.5rem;
    }

    .no-profiles p {
      font-size: 1.1rem;
      margin: 1rem 0;
    }

    .loading-container {
      text-align: center;
      padding: 4rem;
    }

    .result-container {
      text-align: center;
      padding: 4rem;
    }

    .result-container.success i {
      color: #5ba352;
      margin-bottom: 1rem;
    }

    .result-container h2 {
      color: #5ba352;
      margin: 1rem 0;
    }

    .delete-button-container {
      text-align: center;
      margin-top: 2rem;
      padding-top: 2rem;
      border-top: 1px solid #444;
    }

    .delete-button-container button {
      padding: 1rem 3rem;
      font-size: 1.1rem;
      background-color: #c9190b;
    }

    .delete-button-container button:hover:not(:disabled) {
      background-color: #a30000;
    }

    .delete-button-container button:disabled {
      background-color: #444;
      cursor: not-allowed;
    }

    /* Profile card selection visual feedback */
    input[type="radio"]:checked + label h3::before {
      content: "✓ ";
      color: #12bbd4;
    }
  </style>
</head>

<body>
  <header class="pf-c-page__header">
    <div class="pf-c-page__header-brand">
      <div class="pf-c-page__header-brand-toggle">
      </div>
      <a class="pf-c-page__header-brand-link" href="index.php">
        <!-- <img class="pf-c-brand" src="images/viewfinder-logo.png" alt="Viewfinder logo" /> -->
      </a>
    </div>
    <div class="pf-c-page__header-tools">
      <div class="widget">
        <a href="profile-creator.php"><button><i class="fa fa-plus"></i> Create Profile</button></a>&nbsp
        <a href="index.php"><button><i class="fa fa-home"></i> Home</button></a>
      </div>
    </div>
  </header>

  <div class="wizard-container">
    <h1><i class="fa fa-trash"></i> Profile Deleter</h1>
    <p>Delete custom assessment profiles</p>

    <div class="alert alert-danger" style="margin-bottom: 2rem;">
      <i class="fa fa-exclamation-triangle"></i>
      <strong>Warning:</strong> Deleting a profile will remove the JSON file, Config.php entry, and navigation button. This action cannot be easily undone.
    </div>

    <div id="profileList">
      <div class="loading-container">
        <div class="spinner"></div>
        <p>Loading profiles...</p>
      </div>
    </div>

    <div class="delete-button-container">
      <button id="deleteBtn" class="ui-button ui-widget ui-corner-all" disabled>
        <i class="fa fa-trash"></i> Delete Selected Profile
      </button>
    </div>
  </div>

  <!-- Load deleter JavaScript -->
  <script src="js/profile-deleter.js"></script>

  <footer style="text-align: center; color: #999; padding: 2rem; margin-top: 2rem;">
    <p>&copy; <?php echo date('Y'); ?> Viewfinder Maturity Assessment v<?php echo Config::APP_VERSION; ?></p>
    <p><small>Profile Deleter Tool - For removing custom assessment profiles</small></p>
  </footer>
</body>
</html>
