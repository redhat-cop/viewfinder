<?php
/**
 * Profile Deleter AJAX Handler
 *
 * Handles profile listing and deletion requests
 */

session_start();
header('Content-Type: application/json');

// Clear opcode cache BEFORE loading Config.php to ensure we see latest changes
// This is important when profiles are created/deleted via CLI
if (function_exists('opcache_reset')) {
    opcache_reset();
}

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/includes/Config.php';
require_once __DIR__ . '/includes/Security.php';
require_once __DIR__ . '/includes/Logger.php';
require_once __DIR__ . '/includes/ProfileDeleter.php';
require_once __DIR__ . '/includes/FileUpdater.php';
require_once __DIR__ . '/includes/Exceptions/ProfileException.php';

try {
    $action = $_POST['action'] ?? '';

    switch ($action) {

        case 'list_profiles':
            // Get list of deletable profiles
            $profiles = ProfileDeleter::getDeletableProfiles();

            echo json_encode([
                'success' => true,
                'profiles' => $profiles,
                'count' => count($profiles)
            ]);
            break;

        case 'validate_deletion':
            // Validate if profile can be deleted
            $profileName = trim($_POST['profile_name'] ?? '');

            if (empty($profileName)) {
                throw new ProfileException(
                    'No profile name provided',
                    'Profile name is required.'
                );
            }

            try {
                ProfileDeleter::validateDeletion($profileName);
                echo json_encode([
                    'success' => true,
                    'valid' => true,
                    'message' => 'Profile can be deleted'
                ]);
            } catch (ProfileException $e) {
                echo json_encode([
                    'success' => true,
                    'valid' => false,
                    'message' => $e->getUserMessage()
                ]);
            }
            break;

        case 'delete':
            // Execute profile deletion
            $profileName = trim($_POST['profile_name'] ?? '');

            if (empty($profileName)) {
                throw new ProfileException(
                    'No profile name provided',
                    'Profile name is required.'
                );
            }

            // Perform deletion
            $result = ProfileDeleter::deleteProfile($profileName);

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
        'debug' => Config::APP_VERSION
    ]);
}
