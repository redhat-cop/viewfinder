<?php
/**
 * Profile Admin AJAX Handler
 *
 * Handles admin dashboard operations: listing, toggling, and deleting profiles
 */

session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/includes/Config.php';
require_once __DIR__ . '/includes/Security.php';
require_once __DIR__ . '/includes/Logger.php';
require_once __DIR__ . '/includes/ProfileAdmin.php';
require_once __DIR__ . '/includes/ProfileExporter.php';
require_once __DIR__ . '/includes/ProfileImporter.php';
require_once __DIR__ . '/includes/Exceptions/ProfileException.php';

try {
    $action = $_POST['action'] ?? '';

    switch ($action) {

        case 'list_all_profiles':
            // Get all profiles with metadata and statistics
            $data = ProfileAdmin::getAllProfiles();

            echo json_encode([
                'success' => true,
                'profiles' => $data['profiles'],
                'stats' => $data['stats']
            ]);
            break;

        case 'toggle_enabled':
            // Toggle profile enabled status
            $profileName = trim($_POST['profile_name'] ?? '');

            if (empty($profileName)) {
                throw new ProfileException(
                    'No profile name provided',
                    'Profile name is required.'
                );
            }

            $result = ProfileAdmin::toggleProfileEnabled($profileName);

            echo json_encode($result);
            break;

        case 'delete_profile':
            // Delete a profile
            $profileName = trim($_POST['profile_name'] ?? '');

            if (empty($profileName)) {
                throw new ProfileException(
                    'No profile name provided',
                    'Profile name is required.'
                );
            }

            $result = ProfileAdmin::deleteProfile($profileName);

            echo json_encode($result);
            break;

        case 'export_profile':
            // Export a profile as downloadable JSON
            $profileName = trim($_POST['profile_name'] ?? '');

            if (empty($profileName)) {
                throw new ProfileException(
                    'No profile name provided',
                    'Profile name is required.'
                );
            }

            // This will set headers and output JSON directly
            // No need to echo json_encode - the download function handles it
            ProfileExporter::downloadProfile($profileName);
            exit; // Prevent additional output
            break;

        case 'validate_import':
            // Validate uploaded profile before importing
            if (!isset($_FILES['profile_file']) || $_FILES['profile_file']['error'] === UPLOAD_ERR_NO_FILE) {
                throw new ProfileException(
                    'No file uploaded',
                    'Please select a file to upload.'
                );
            }

            $customName = trim($_POST['custom_name'] ?? '');
            $customName = empty($customName) ? null : $customName;

            $result = ProfileImporter::validateImport($_FILES['profile_file'], $customName);

            echo json_encode($result);
            break;

        case 'import_profile':
            // Import a profile from uploaded JSON file
            if (!isset($_FILES['profile_file']) || $_FILES['profile_file']['error'] === UPLOAD_ERR_NO_FILE) {
                throw new ProfileException(
                    'No file uploaded',
                    'Please select a file to upload.'
                );
            }

            $customName = trim($_POST['custom_name'] ?? '');
            $displayName = trim($_POST['display_name'] ?? '');
            $overwrite = isset($_POST['overwrite']) && $_POST['overwrite'] === 'true';
            $enabled = isset($_POST['enabled']) && $_POST['enabled'] === 'true';

            $customName = empty($customName) ? null : $customName;
            $displayName = empty($displayName) ? '' : $displayName;

            $result = ProfileImporter::importProfile(
                $_FILES['profile_file'],
                $customName,
                $displayName,
                $overwrite,
                $enabled
            );

            echo json_encode($result);
            break;

        default:
            throw new ProfileException(
                "Unknown action: {$action}",
                'Invalid request.'
            );
    }

} catch (ProfileException $e) {
    Logger::logException($e);

    echo json_encode([
        'success' => false,
        'error' => $e->getUserMessage(),
        'error_code' => $e->getErrorCode()
    ]);

} catch (Exception $e) {
    Logger::logException($e);

    echo json_encode([
        'success' => false,
        'error' => 'An unexpected error occurred. Please try again.',
        'error_code' => 'UNKNOWN_ERROR',
        'details' => $e->getMessage(),
        'debug' => Config::APP_VERSION
    ]);
}
