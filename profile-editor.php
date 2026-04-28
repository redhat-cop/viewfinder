<!doctype html>
<?php
/**
 * Profile Editor
 *
 * Web-based tool for editing existing assessment profiles
 * Reuses wizard UI from profile-creator with modifications for editing
 */

session_start();

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/error-pages/error-handler.php';
require_once __DIR__ . '/includes/Config.php';
require_once __DIR__ . '/includes/Security.php';
require_once __DIR__ . '/includes/Logger.php';

ErrorHandler::register();

// Get profile name from URL parameter
$profileName = $_GET['profile'] ?? '';
$mode = $_GET['mode'] ?? 'edit'; // edit or readonly

// Validate profile exists
if (empty($profileName)) {
    die('Error: No profile specified. Please provide a profile name via ?profile= parameter.');
}

if (!Config::isValidProfile($profileName)) {
    die("Error: Profile '{$profileName}' not found.");
}

// Store in session for use by JavaScript
$_SESSION['editing_profile'] = $profileName;
$_SESSION['editing_mode'] = $mode;

// Initialize wizard session
if (!isset($_SESSION['wizard_step'])) {
    $_SESSION['wizard_step'] = 0;
}
if (!isset($_SESSION['wizard_data'])) {
    $_SESSION['wizard_data'] = [
        'metadata' => [],
        'domains' => []
    ];
}
?>

<html lang="en-us" class="pf-theme-dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Profile Editor - Viewfinder Maturity Assessment</title>

  <!-- Existing application styles -->
  <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/brands.css" />
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="css/patternfly.css" />
  <link rel="stylesheet" href="css/patternfly-addons.css" />

  <!-- Wizard-specific styles -->
  <link rel="stylesheet" href="css/profile-wizard.css" />

  <!-- JavaScript libraries -->
  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
  <script src="https://kit.fontawesome.com/8a8c57f9cf.js" crossorigin="anonymous"></script>
  <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet" />

  <style>
    /* Header Button Styling */
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

    /* Read-only banner */
    .read-only-banner {
      background: #f0ab00;
      color: #000;
      padding: 1rem;
      text-align: center;
      font-weight: bold;
      border-radius: 4px;
      margin-bottom: 1.5rem;
    }
    .read-only-banner i {
      margin-right: 0.5rem;
    }

    /* Locked field styling */
    .locked {
      background-color: #1a1a1a !important;
      color: #ccc !important;
      cursor: not-allowed;
      opacity: 0.8;
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
        <a href="profile-admin.php"><button>← Back to Admin</button></a>
      </div>
    </div>
  </header>

  <div class="wizard-container">
    <?php if ($mode === 'readonly'): ?>
    <!-- Read-only banner for protected profiles -->
    <div class="read-only-banner">
      <i class="fa fa-lock"></i>
      This is a protected profile. You are viewing it in read-only mode.
    </div>
    <?php endif; ?>

    <h1><?php echo $mode === 'readonly' ? 'Profile Viewer' : 'Profile Editor'; ?></h1>
    <p>
      <?php
      if ($mode === 'readonly') {
          echo "Viewing profile: <strong>" . htmlspecialchars(Config::getProfileDisplayName($profileName)) . "</strong>";
      } else {
          echo "Editing profile: <strong>" . htmlspecialchars(Config::getProfileDisplayName($profileName)) . "</strong>";
      }
      ?>
    </p>

    <!-- Progress Bar -->
    <div class="wizard-progress">
      <div class="progress-bar" id="wizardProgress"></div>
      <div class="progress-text">Step <span id="currentStep">1</span> of 9</div>
    </div>

    <!-- Step Content (dynamically loaded via JavaScript) -->
    <div id="wizardContent">
      <div class="wizard-loading">
        <div class="spinner"></div>
        <p>Loading profile...</p>
      </div>
    </div>

    <!-- Navigation Buttons -->
    <div class="wizard-nav">
      <button id="prevBtn" class="ui-button ui-widget ui-corner-all" style="display: none;">
        <i class="fa fa-arrow-left"></i> Previous
      </button>
      <button id="nextBtn" class="ui-button ui-widget ui-corner-all">
        Next <i class="fa fa-arrow-right"></i>
      </button>
      <button id="updateBtn" class="ui-button ui-widget ui-corner-all" style="display: none;">
        <i class="fa fa-check"></i> Update Profile
      </button>
    </div>
  </div>

  <!-- Hidden inputs for profile context -->
  <input type="hidden" id="profileName" value="<?php echo htmlspecialchars($profileName); ?>">
  <input type="hidden" id="editMode" value="<?php echo htmlspecialchars($mode); ?>">

  <!-- Load wizard JavaScript (editor version) -->
  <script src="js/profile-editor.js"></script>

  <footer style="text-align: center; color: #999; padding: 2rem; margin-top: 2rem;">
    <p>&copy; <?php echo date('Y'); ?> Viewfinder Maturity Assessment v<?php echo Config::APP_VERSION; ?></p>
    <p><small>Profile Editor - For editing existing assessment profiles</small></p>
  </footer>
</body>
</html>
