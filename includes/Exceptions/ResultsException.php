<?php
/**
 * Results Exception - Handles export/import errors for assessment results
 *
 * Extends ViewfinderException to provide consistent error handling
 * for assessment result export and import operations.
 */

require_once __DIR__ . '/ViewfinderException.php';

class ResultsException extends ViewfinderException {

    protected string $errorCode = 'RESULTS_ERROR';

    /**
     * Create exception for invalid JSON format
     *
     * @param string $details Technical details about the format error
     * @param array $context Additional context data
     * @return self
     */
    public static function invalidFormat(string $details, array $context = []): self {
        return new self(
            'Invalid results file format: ' . $details,
            'Invalid file format. Please upload a valid Viewfinder results file.',
            $context
        );
    }

    /**
     * Create exception for incompatible version
     *
     * @param string $version Version found in file
     * @param array $supportedVersions Supported version list
     * @return self
     */
    public static function incompatibleVersion(string $version, array $supportedVersions): self {
        return new self(
            "Incompatible results file version: {$version}",
            'This results file version is not supported. Supported versions: ' . implode(', ', $supportedVersions),
            ['version' => $version, 'supported' => $supportedVersions]
        );
    }

    /**
     * Create exception for data validation failure
     *
     * @param string $details Validation failure details
     * @param array $context Additional context data
     * @return self
     */
    public static function dataValidationFailed(string $details, array $context = []): self {
        return new self(
            'Results data validation failed: ' . $details,
            'Invalid assessment data in file. ' . $details,
            $context
        );
    }

    /**
     * Create exception for file upload errors
     *
     * @param string $details Upload error details
     * @param array $context Additional context data
     * @return self
     */
    public static function uploadFailed(string $details, array $context = []): self {
        return new self(
            'File upload failed: ' . $details,
            'File upload failed. Please try again.',
            $context
        );
    }

    /**
     * Create exception for wrong assessment type
     *
     * @param string $expected Expected assessment type
     * @param string $actual Actual assessment type found
     * @return self
     */
    public static function wrongType(string $expected, string $actual): self {
        return new self(
            "Wrong assessment type: expected {$expected}, got {$actual}",
            "This file contains a {$actual} assessment, but {$expected} was expected.",
            ['expected' => $expected, 'actual' => $actual]
        );
    }
}
