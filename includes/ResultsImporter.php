<?php
/**
 * ResultsImporter - Handles assessment results import functionality
 *
 * Validates and imports previously exported Security and DS-Qualifier assessment results
 */

require_once __DIR__ . '/Config.php';
require_once __DIR__ . '/Security.php';
require_once __DIR__ . '/Logger.php';
require_once __DIR__ . '/Exceptions/ResultsException.php';

class ResultsImporter {

    // Maximum file size: 5MB
    const MAX_FILE_SIZE = 5 * 1024 * 1024;

    // Supported export versions
    const SUPPORTED_VERSIONS = ['1.0'];

    /**
     * Validate uploaded file
     *
     * @param array $file $_FILES array entry
     * @return bool True if valid
     * @throws ResultsException If invalid
     */
    public static function validateUploadedFile(array $file): bool {
        // Check for upload errors
        if (!isset($file['error']) || is_array($file['error'])) {
            throw ResultsException::uploadFailed(
                'Invalid file upload parameters',
                ['file' => $file]
            );
        }

        // Check upload error code
        switch ($file['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw ResultsException::uploadFailed(
                    'File size exceeds maximum allowed size',
                    ['size' => $file['size'] ?? 'unknown']
                );
            case UPLOAD_ERR_NO_FILE:
                throw ResultsException::uploadFailed(
                    'No file was uploaded',
                    []
                );
            default:
                throw ResultsException::uploadFailed(
                    'File upload failed with error code: ' . $file['error'],
                    ['error_code' => $file['error']]
                );
        }

        // Verify file size
        if ($file['size'] > self::MAX_FILE_SIZE) {
            throw ResultsException::uploadFailed(
                'File is too large. Maximum size is 5MB.',
                ['size' => $file['size'], 'max' => self::MAX_FILE_SIZE]
            );
        }

        // Verify file was actually uploaded
        if (!is_uploaded_file($file['tmp_name'])) {
            throw ResultsException::uploadFailed(
                'File was not uploaded via HTTP POST',
                ['tmp_name' => $file['tmp_name']]
            );
        }

        // Check MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        $allowedMimes = ['application/json', 'text/plain'];
        if (!in_array($mimeType, $allowedMimes, true)) {
            throw ResultsException::uploadFailed(
                'File must be a JSON file.',
                ['mime' => $mimeType, 'allowed' => $allowedMimes]
            );
        }

        return true;
    }

    /**
     * Parse and validate JSON content
     *
     * @param string $jsonContent JSON string to parse
     * @return array Parsed JSON data
     * @throws ResultsException If invalid
     */
    public static function parseJSON(string $jsonContent): array {
        // Decode JSON
        $data = json_decode($jsonContent, true);

        if ($data === null) {
            throw ResultsException::invalidFormat(
                'JSON decode failed: ' . json_last_error_msg(),
                ['error' => json_last_error_msg()]
            );
        }

        if (!is_array($data)) {
            throw ResultsException::invalidFormat(
                'JSON root is not an object/array',
                ['type' => gettype($data)]
            );
        }

        return $data;
    }

    /**
     * Validate results file structure
     *
     * @param array $data Results data to validate
     * @return bool True if valid
     * @throws ResultsException If invalid
     */
    public static function validateResultsStructure(array $data): bool {
        // Check for required top-level fields
        if (!isset($data['viewfinder_export'])) {
            throw ResultsException::invalidFormat(
                'Missing required field: viewfinder_export',
                []
            );
        }

        if (!isset($data['assessment'])) {
            throw ResultsException::invalidFormat(
                'Missing required field: assessment',
                []
            );
        }

        $export = $data['viewfinder_export'];

        // Validate export metadata
        if (!isset($export['version'])) {
            throw ResultsException::invalidFormat(
                'Missing export version',
                []
            );
        }

        if (!in_array($export['version'], self::SUPPORTED_VERSIONS, true)) {
            throw ResultsException::incompatibleVersion(
                $export['version'],
                self::SUPPORTED_VERSIONS
            );
        }

        if (!isset($export['type'])) {
            throw ResultsException::invalidFormat(
                'Missing assessment type',
                []
            );
        }

        $validTypes = ['security_assessment', 'ds_qualifier'];
        if (!in_array($export['type'], $validTypes, true)) {
            throw ResultsException::invalidFormat(
                'Invalid assessment type: ' . $export['type'],
                ['type' => $export['type'], 'valid_types' => $validTypes]
            );
        }

        return true;
    }

    /**
     * Validate Security assessment data
     *
     * @param array $assessment Assessment data to validate
     * @return bool True if valid
     * @throws ResultsException If invalid
     */
    private static function validateSecurityAssessment(array $assessment): bool {
        // Validate profile
        if (!isset($assessment['profile'])) {
            throw ResultsException::dataValidationFailed(
                'Missing profile field',
                []
            );
        }

        $profile = $assessment['profile'];
        if (!Config::isValidProfile($profile)) {
            throw ResultsException::dataValidationFailed(
                "Invalid profile: {$profile}",
                ['profile' => $profile]
            );
        }

        // Validate LOB (optional)
        if (isset($assessment['lob']) && !empty($assessment['lob'])) {
            if (!array_key_exists($assessment['lob'], Config::LOB_OPTIONS)) {
                throw ResultsException::dataValidationFailed(
                    "Invalid Line of Business: {$assessment['lob']}",
                    ['lob' => $assessment['lob']]
                );
            }
        }

        // Validate frameworks (optional array)
        if (isset($assessment['frameworks']) && !is_array($assessment['frameworks'])) {
            throw ResultsException::dataValidationFailed(
                'Frameworks must be an array',
                ['frameworks' => $assessment['frameworks']]
            );
        }

        // Validate controls
        if (!isset($assessment['controls']) || !is_array($assessment['controls'])) {
            throw ResultsException::dataValidationFailed(
                'Missing or invalid controls data',
                []
            );
        }

        // Validate control format and values
        foreach ($assessment['controls'] as $controlId => $value) {
            // Control format: control{1-7}-{1-8}
            if (!preg_match('/^control([1-7])-([1-8])$/', $controlId, $matches)) {
                throw ResultsException::dataValidationFailed(
                    "Invalid control ID format: {$controlId}",
                    ['control_id' => $controlId]
                );
            }

            // Value must be integer between 0 and 8
            if (!is_numeric($value) || $value < 0 || $value > 8) {
                throw ResultsException::dataValidationFailed(
                    "Invalid control value for {$controlId}: {$value}",
                    ['control_id' => $controlId, 'value' => $value]
                );
            }
        }

        return true;
    }

    /**
     * Validate DS-Qualifier assessment data
     *
     * @param array $assessment Assessment data to validate
     * @return bool True if valid
     * @throws ResultsException If invalid
     */
    private static function validateDSQualifierAssessment(array $assessment): bool {
        // Validate responses
        if (!isset($assessment['responses']) || !is_array($assessment['responses'])) {
            throw ResultsException::dataValidationFailed(
                'Missing or invalid responses data',
                []
            );
        }

        // Validate question ID format and values
        foreach ($assessment['responses'] as $questionId => $value) {
            // Question format: (ds|ts|os|as|oss|eo|ms)\d+
            if (!preg_match('/^(ds|ts|os|as|oss|eo|ms)\d+$/', $questionId)) {
                throw ResultsException::dataValidationFailed(
                    "Invalid question ID format: {$questionId}",
                    ['question_id' => $questionId]
                );
            }

            // Value must be "0", "1", or "unknown"
            $validValues = ['0', '1', 'unknown'];
            if (!in_array($value, $validValues, true)) {
                throw ResultsException::dataValidationFailed(
                    "Invalid response value for {$questionId}: {$value}",
                    ['question_id' => $questionId, 'value' => $value, 'valid' => $validValues]
                );
            }
        }

        return true;
    }

    /**
     * Import Security assessment results
     *
     * @param array $file $_FILES array entry
     * @return array Imported and validated assessment data
     * @throws ResultsException If import fails
     */
    public static function importSecurityResults(array $file): array {
        Logger::info('Starting Security results import', ['filename' => $file['name']]);

        // Validate uploaded file
        self::validateUploadedFile($file);

        // Read file content
        $jsonContent = file_get_contents($file['tmp_name']);
        if ($jsonContent === false) {
            throw ResultsException::uploadFailed(
                'Unable to read uploaded file',
                ['tmp_name' => $file['tmp_name']]
            );
        }

        // Parse JSON
        $data = self::parseJSON($jsonContent);

        // Validate structure
        self::validateResultsStructure($data);

        // Verify assessment type
        if ($data['viewfinder_export']['type'] !== 'security_assessment') {
            throw ResultsException::wrongType(
                'security_assessment',
                $data['viewfinder_export']['type']
            );
        }

        // Validate Security assessment data
        self::validateSecurityAssessment($data['assessment']);

        Logger::info('Security results imported successfully', [
            'profile' => $data['assessment']['profile'],
            'controls_count' => count($data['assessment']['controls'])
        ]);

        return $data;
    }

    /**
     * Import DS-Qualifier assessment results
     *
     * @param array $file $_FILES array entry
     * @return array Imported and validated assessment data
     * @throws ResultsException If import fails
     */
    public static function importDSQualifierResults(array $file): array {
        Logger::info('Starting DS-Qualifier results import', ['filename' => $file['name']]);

        // Validate uploaded file
        self::validateUploadedFile($file);

        // Read file content
        $jsonContent = file_get_contents($file['tmp_name']);
        if ($jsonContent === false) {
            throw ResultsException::uploadFailed(
                'Unable to read uploaded file',
                ['tmp_name' => $file['tmp_name']]
            );
        }

        // Parse JSON
        $data = self::parseJSON($jsonContent);

        // Validate structure
        self::validateResultsStructure($data);

        // Verify assessment type
        if ($data['viewfinder_export']['type'] !== 'ds_qualifier') {
            throw ResultsException::wrongType(
                'ds_qualifier',
                $data['viewfinder_export']['type']
            );
        }

        // Validate DS-Qualifier assessment data
        self::validateDSQualifierAssessment($data['assessment']);

        Logger::info('DS-Qualifier results imported successfully', [
            'responses_count' => count($data['assessment']['responses'])
        ]);

        return $data;
    }
}
