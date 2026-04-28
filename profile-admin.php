<!doctype html>
<?php
/**
 * Profile Administration Dashboard
 *
 * Unified hub for managing all assessment profiles
 * Features: Create, Edit, Delete, Enable/Disable profiles
 */

session_start();

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/error-pages/error-handler.php';
require_once __DIR__ . '/includes/Config.php';
require_once __DIR__ . '/includes/Security.php';
require_once __DIR__ . '/includes/Logger.php';

ErrorHandler::register();

Logger::info('Profile admin dashboard accessed');
?>

<html lang="en-us" class="pf-theme-dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Profile Administration - Viewfinder Maturity Assessment</title>

  <!-- Existing application styles -->
  <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/brands.css" />
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="css/patternfly.css" />
  <link rel="stylesheet" href="css/patternfly-addons.css" />

  <!-- Admin-specific styles -->
  <link rel="stylesheet" href="css/profile-admin.css" />

  <!-- JavaScript libraries -->
  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
  <script src="https://kit.fontawesome.com/8a8c57f9cf.js" crossorigin="anonymous"></script>
  <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet" />
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
        <a href="index.php"><button><i class="fa fa-home"></i> Home</button></a>
      </div>
    </div>
  </header>

  <div class="admin-container">
    <h1>Profile Administration</h1>
    <p>Manage all assessment profiles from this unified dashboard</p>

    <!-- Quick Actions -->
    <div class="quick-actions">
      <a href="profile-creator.php">
        <button class="btn-primary">
          <i class="fa fa-plus"></i> Create New Profile
        </button>
      </a>
      <button class="btn-secondary" id="import-btn">
        <i class="fa fa-upload"></i> Import Profile
      </button>
      <button class="btn-secondary" id="refresh-btn">
        <i class="fa fa-refresh"></i> Refresh
      </button>
    </div>

    <!-- Loading Indicator -->
    <div id="loading-indicator" class="loading-container">
      <div class="spinner"></div>
      <p>Loading profiles...</p>
    </div>

    <!-- Profile Cards Grid -->
    <div id="profile-grid" class="profile-cards-grid" style="display: none;">
      <!-- Cards loaded via JavaScript -->
    </div>

    <!-- Empty State -->
    <div id="empty-state" class="empty-state" style="display: none;">
      <i class="fa fa-folder-open"></i>
      <h3>No Custom Profiles</h3>
      <p>Get started by creating your first custom assessment profile.</p>
      <a href="profile-creator.php">
        <button class="btn-primary">
          <i class="fa fa-plus"></i> Create Profile
        </button>
      </a>
    </div>
  </div>

  <!-- Delete Confirmation Modal -->
  <div id="delete-modal" class="modal">
    <div class="modal-content">
      <h2>Confirm Deletion</h2>
      <p id="delete-message">Are you sure you want to delete this profile?</p>
      <div class="profile-details" id="delete-profile-details"></div>
      <div class="modal-actions">
        <button id="confirm-delete" class="btn-danger">
          <i class="fa fa-trash"></i> Delete
        </button>
        <button id="cancel-delete" class="btn-secondary">
          Cancel
        </button>
      </div>
    </div>
  </div>

  <!-- Import Profile Modal -->
  <div id="import-modal" class="modal">
    <div class="modal-content">
      <h2><i class="fa fa-upload"></i> Import Profile</h2>
      <p>Upload a profile JSON file to import it into Viewfinder</p>

      <form id="import-form" enctype="multipart/form-data">
        <div class="form-group">
          <label for="profile-file">Profile File (JSON)</label>
          <input type="file" id="profile-file" name="profile_file" accept=".json,application/json" required>
          <small class="form-text">Select a controls-*.json file to import</small>
        </div>

        <div class="form-group">
          <label for="custom-name">Custom Profile Name (optional)</label>
          <input type="text" id="custom-name" name="custom_name" placeholder="Leave empty to use filename">
          <small class="form-text">Override the profile name from the filename</small>
        </div>

        <div class="form-group">
          <label for="display-name">Display Name (optional)</label>
          <input type="text" id="display-name" name="display_name" placeholder="Human-readable name">
          <small class="form-text">Friendly name shown in the UI</small>
        </div>

        <div class="form-group checkbox-group">
          <label>
            <input type="checkbox" id="overwrite-existing" name="overwrite" value="true">
            Overwrite existing profile if it exists
          </label>
        </div>

        <div class="form-group checkbox-group">
          <label>
            <input type="checkbox" id="enable-profile" name="enabled" value="true" checked>
            Enable profile after import
          </label>
        </div>

        <div id="import-validation-result" class="validation-result" style="display: none;"></div>

        <div class="modal-actions">
          <button type="submit" id="confirm-import" class="btn-primary">
            <i class="fa fa-upload"></i> Import
          </button>
          <button type="button" id="validate-import" class="btn-secondary">
            <i class="fa fa-check"></i> Validate
          </button>
          <button type="button" id="cancel-import" class="btn-secondary">
            Cancel
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Notification Toast -->
  <div id="notification-toast" class="notification-toast">
    <div class="toast-content">
      <i class="fa fa-check-circle"></i>
      <span id="toast-message"></span>
    </div>
  </div>

  <!-- Load admin JavaScript -->
  <script src="js/profile-admin.js"></script>

  <footer style="text-align: center; color: #999; padding: 2rem; margin-top: 2rem;">
    <p>&copy; <?php echo date('Y'); ?> Viewfinder Maturity Assessment v<?php echo Config::APP_VERSION; ?></p>
    <p><small>Profile Administration Dashboard</small></p>
  </footer>
</body>
</html>
