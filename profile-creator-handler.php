<?php
/**
 * Profile Creator AJAX Handler
 *
 * Handles wizard step submissions, validation, and profile generation
 */

session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/includes/Config.php';
require_once __DIR__ . '/includes/Security.php';
require_once __DIR__ . '/includes/Logger.php';
require_once __DIR__ . '/includes/ProfileGenerator.php';
require_once __DIR__ . '/includes/FileUpdater.php';
require_once __DIR__ . '/includes/Exceptions/ProfileException.php';

try {
    $action = $_POST['action'] ?? '';

    switch ($action) {

        case 'save_step':
            // Save current step data to session
            $step = (int)($_POST['step'] ?? 0);
            $data = $_POST['data'] ?? [];

            // Initialize wizard session if needed
            if (!isset($_SESSION['wizard_data'])) {
                $_SESSION['wizard_data'] = [
                    'metadata' => [],
                    'domains' => []
                ];
            }

            // Store the data based on step type
            if ($step === 0) {
                // Metadata step
                $_SESSION['wizard_data']['metadata'] = $data;
            } elseif ($step >= 1 && $step <= 7) {
                // Domain step
                $_SESSION['wizard_data']['domains'][$step] = $data;
            }

            $_SESSION['wizard_step'] = $step;

            Logger::info('Wizard step saved', ['step' => $step]);

            echo json_encode([
                'success' => true,
                'step' => $step,
                'message' => 'Step data saved'
            ]);
            break;

        case 'get_step_data':
            // Retrieve saved step data from session
            $step = (int)($_POST['step'] ?? 0);

            if (!isset($_SESSION['wizard_data'])) {
                echo json_encode([
                    'success' => true,
                    'data' => null
                ]);
                break;
            }

            $data = null;
            if ($step === 0) {
                $data = $_SESSION['wizard_data']['metadata'] ?? null;
            } elseif ($step >= 1 && $step <= 7) {
                $data = $_SESSION['wizard_data']['domains'][$step] ?? null;
            }

            echo json_encode([
                'success' => true,
                'data' => $data
            ]);
            break;

        case 'validate_name':
            // Validate profile name availability and format
            $name = trim($_POST['profile_name'] ?? '');

            if (empty($name)) {
                echo json_encode([
                    'success' => true,
                    'valid' => false,
                    'message' => 'Profile name is required'
                ]);
                break;
            }

            try {
                ProfileGenerator::validateProfileName($name);
                echo json_encode([
                    'success' => true,
                    'valid' => true,
                    'message' => 'Name is available'
                ]);
            } catch (ProfileException $e) {
                echo json_encode([
                    'success' => true,
                    'valid' => false,
                    'message' => $e->getUserMessage()
                ]);
            }
            break;

        case 'get_preview':
            // Build and return JSON preview for review step
            if (empty($_SESSION['wizard_data'])) {
                throw new ProfileException(
                    'No wizard data in session',
                    'Session expired. Please restart the wizard.'
                );
            }

            $jsonData = ProfileGenerator::buildProfileJSON($_SESSION['wizard_data']);
            $jsonString = json_encode($jsonData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

            echo json_encode([
                'success' => true,
                'json' => $jsonString,
                'metadata' => $_SESSION['wizard_data']['metadata'] ?? []
            ]);
            break;

        case 'generate':
            // Execute profile generation
            if (empty($_SESSION['wizard_data'])) {
                throw new ProfileException(
                    'No wizard data in session',
                    'Session expired. Please restart the wizard.'
                );
            }

            // Validate we have all required data
            $metadata = $_SESSION['wizard_data']['metadata'] ?? [];
            if (empty($metadata['profile_name']) || empty($metadata['display_name'])) {
                throw new ProfileException(
                    'Missing required metadata',
                    'Profile name and display name are required.'
                );
            }

            // Validate we have all 7 domains
            $domains = $_SESSION['wizard_data']['domains'] ?? [];
            if (count($domains) < 7) {
                throw new ProfileException(
                    'Incomplete domain data',
                    'All 7 domains must be defined.'
                );
            }

            // Generate the profile
            $result = ProfileGenerator::generateProfile($_SESSION['wizard_data']);

            // Clear session on success
            unset($_SESSION['wizard_data']);
            unset($_SESSION['wizard_step']);

            echo json_encode($result);
            break;

        case 'reset':
            // Reset wizard session
            unset($_SESSION['wizard_data']);
            unset($_SESSION['wizard_step']);

            echo json_encode([
                'success' => true,
                'message' => 'Wizard reset successfully'
            ]);
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
        'debug' => Config::APP_VERSION // Include app version for debugging
    ]);
}
