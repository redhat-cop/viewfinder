<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../includes/Security.php';
require_once __DIR__ . '/../../includes/Config.php';
require_once __DIR__ . '/../../includes/Logger.php';
require_once __DIR__ . '/../../includes/Exceptions/FileSystemException.php';
require_once __DIR__ . '/../../includes/Exceptions/ViewfinderJsonException.php';

/**
 * SecurityTest - Unit tests for Security class
 *
 * Tests input validation, file operations, and security functions
 */
class SecurityTest extends TestCase {

    protected function setUp(): void {
        // Configure logger to suppress output during tests
        Logger::configure('/tmp/test-viewfinder/', 'ERROR');
    }

    // ========================================
    // validateProfile() Tests (6 tests)
    // ========================================

    public function testValidProfileReturnsProfile(): void {
        $result = Security::validateProfile('Security');
        $this->assertEquals('Security', $result);
    }

    public function testValidProfileDigitalSovereigntyReturnsProfile(): void {
        $result = Security::validateProfile('DigitalSovereignty');
        $this->assertEquals('DigitalSovereignty', $result);
    }

    public function testEmptyProfileReturnsDefault(): void {
        $result = Security::validateProfile('');
        $this->assertEquals('Security', $result);
    }

    public function testInvalidProfileReturnsDefault(): void {
        $result = Security::validateProfile('InvalidProfile');
        $this->assertEquals('Security', $result);
    }

    public function testWhitespaceProfileReturnsDefault(): void {
        $result = Security::validateProfile('   ');
        $this->assertEquals('Security', $result);
    }

    public function testNullInputProfileReturnsDefault(): void {
        $result = Security::validateProfile(null);
        $this->assertEquals('Security', $result);
    }

    // ========================================
    // validateLOB() Tests (6 tests)
    // ========================================

    public function testValidLOBReturnsLOB(): void {
        $result = Security::validateLOB('Finance');
        $this->assertEquals('Finance', $result);
    }

    public function testAllValidLOBs(): void {
        $validLOBs = ['Finance', 'Government', 'Manufacturing', 'Telecommunications', 'Healthcare', 'Other'];

        foreach ($validLOBs as $lob) {
            $result = Security::validateLOB($lob);
            $this->assertEquals($lob, $result, "Failed for LOB: $lob");
        }
    }

    public function testEmptyLOBReturnsNull(): void {
        $result = Security::validateLOB('');
        $this->assertNull($result);
    }

    public function testInvalidLOBReturnsNull(): void {
        $result = Security::validateLOB('InvalidLOB');
        $this->assertNull($result);
    }

    public function testCaseSensitiveLOB(): void {
        $result = Security::validateLOB('finance'); // lowercase
        $this->assertNull($result, 'LOB validation should be case-sensitive');
    }

    public function testNullInputLOBReturnsNull(): void {
        $result = Security::validateLOB(null);
        $this->assertNull($result);
    }

    // ========================================
    // validateFrameworks() Tests (5 tests)
    // ========================================

    public function testValidFrameworksReturnsFiltered(): void {
        $frameworks = ['NIST', 'PCI-DSS'];
        $valid = ['NIST', 'PCI-DSS', 'GDPR'];

        $result = Security::validateFrameworks($frameworks, $valid);
        $this->assertEquals($frameworks, $result);
    }

    public function testInvalidFrameworksFiltered(): void {
        $frameworks = ['Invalid1', 'Invalid2'];
        $valid = ['NIST', 'PCI-DSS'];

        $result = Security::validateFrameworks($frameworks, $valid);
        $this->assertEmpty($result);
    }

    public function testMixedFrameworksOnlyValidReturned(): void {
        $frameworks = ['NIST', 'Invalid', 'PCI-DSS'];
        $valid = ['NIST', 'PCI-DSS', 'GDPR'];

        $result = Security::validateFrameworks($frameworks, $valid);
        $this->assertCount(2, $result);
        $this->assertContains('NIST', $result);
        $this->assertContains('PCI-DSS', $result);
    }

    public function testEmptyArrayReturnsEmpty(): void {
        $result = Security::validateFrameworks([], ['NIST']);
        $this->assertEmpty($result);
    }

    public function testNonArrayReturnsEmpty(): void {
        $result = Security::validateFrameworks('not-an-array', ['NIST']);
        $this->assertEmpty($result);
    }

    // ========================================
    // escape() Tests (6 tests)
    // ========================================

    public function testEscapesBasicHTML(): void {
        $result = Security::escape('<script>alert("XSS")</script>');
        $this->assertEquals('&lt;script&gt;alert(&quot;XSS&quot;)&lt;/script&gt;', $result);
    }

    public function testEscapesQuotes(): void {
        $single = Security::escape("it's");
        $double = Security::escape('say "hello"');

        // PHP 8.4 uses &apos; instead of &#039; for single quotes
        $this->assertTrue(
            str_contains($single, '&#039;') || str_contains($single, '&apos;'),
            'Single quote should be escaped'
        );
        $this->assertStringContainsString('&quot;', $double);
    }

    public function testEscapesAmpersand(): void {
        $result = Security::escape('AT&T');
        $this->assertEquals('AT&amp;T', $result);
    }

    public function testEmptyStringReturnsEmpty(): void {
        $result = Security::escape('');
        $this->assertEquals('', $result);
    }

    public function testUtf8Preserved(): void {
        $result = Security::escape('Café');
        $this->assertEquals('Café', $result);
    }

    public function testXSSVectorsPrevented(): void {
        $vectors = [
            '<img src=x onerror=alert(1)>',
            'javascript:alert(1)',
            '<svg onload=alert(1)>',
            '"><script>alert(1)</script>'
        ];

        foreach ($vectors as $vector) {
            $result = Security::escape($vector);
            // Check that dangerous scripts are not present in unescaped form
            $this->assertStringNotContainsString('<script', $result, "Failed for: $vector");

            // Verify specific dangerous patterns are neutralized
            if (str_contains($vector, '<img')) {
                $this->assertStringContainsString('&lt;img', $result);
                $this->assertStringNotContainsString('<img', $result);
            }
            if (str_contains($vector, '<svg')) {
                $this->assertStringContainsString('&lt;svg', $result);
                $this->assertStringNotContainsString('<svg', $result);
            }
            if (str_contains($vector, '<')) {
                // Only check for escaped brackets if the input contains brackets
                $this->assertStringContainsString('&lt;', $result, "Angle brackets should be escaped for: $vector");
            }
        }
    }

    // ========================================
    // loadJSON() Tests (7 tests)
    // ========================================

    public function testLoadValidJSON(): void {
        $testFile = __DIR__ . '/../Fixtures/test-controls.json';
        $result = Security::loadJSON($testFile);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('Domain-1', $result);
    }

    public function testFileNotFoundThrowsException(): void {
        $this->expectException(FileSystemException::class);
        $this->expectExceptionMessage('File not found');

        Security::loadJSON('/nonexistent/file.json');
    }

    public function testInvalidJSONThrowsException(): void {
        $this->expectException(ViewfinderJsonException::class);
        $this->expectExceptionMessage('JSON decode failed');

        $testFile = __DIR__ . '/../Fixtures/invalid.json';
        Security::loadJSON($testFile);
    }

    public function testEmptyFileThrowsException(): void {
        // Create temporary empty file
        $tempFile = sys_get_temp_dir() . '/empty-test-' . uniqid() . '.json';
        file_put_contents($tempFile, '');

        try {
            $this->expectException(ViewfinderJsonException::class);
            Security::loadJSON($tempFile);
        } finally {
            @unlink($tempFile);
        }
    }

    public function testValidJSONReturnsCorrectStructure(): void {
        $testFile = __DIR__ . '/../Fixtures/test-controls.json';
        $result = Security::loadJSON($testFile);

        $this->assertArrayHasKey('Domain-1', $result);
        $this->assertArrayHasKey('Domain-2', $result);
        $this->assertArrayHasKey('qnum', $result['Domain-1']);
        $this->assertEquals(1, $result['Domain-1']['qnum']);
    }

    public function testLoadJSONReturnsArray(): void {
        $testFile = __DIR__ . '/../Fixtures/test-controls.json';
        $result = Security::loadJSON($testFile);

        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
    }

    public function testLoadJSONPreservesDataTypes(): void {
        $tempFile = sys_get_temp_dir() . '/types-test-' . uniqid() . '.json';
        file_put_contents($tempFile, json_encode([
            'string' => 'test',
            'number' => 123,
            'boolean' => true,
            'null' => null,
            'array' => [1, 2, 3]
        ]));

        try {
            $result = Security::loadJSON($tempFile);

            $this->assertIsString($result['string']);
            $this->assertIsInt($result['number']);
            $this->assertIsBool($result['boolean']);
            $this->assertNull($result['null']);
            $this->assertIsArray($result['array']);
        } finally {
            @unlink($tempFile);
        }
    }

    // ========================================
    // getControlsFilePath() Tests (3 tests)
    // ========================================

    public function testReturnsCorrectPathForProfile(): void {
        $result = Security::getControlsFilePath('Security');
        $this->assertStringContainsString('controls-Security.json', $result);
    }

    public function testValidatesProfileBeforeReturn(): void {
        $result = Security::getControlsFilePath('InvalidProfile');
        // Should default to Security profile
        $this->assertStringContainsString('controls-Security.json', $result);
    }

    public function testUsesConfigGetControlsPath(): void {
        $result = Security::getControlsFilePath('Security');
        $expected = Config::getControlsPath('Security');
        $this->assertEquals($expected, $result);
    }

    // ========================================
    // Additional Tests for Full Coverage
    // ========================================

    public function testEscapeHandlesNullByte(): void {
        $input = "test\0null";
        $result = Security::escape($input);
        // htmlspecialchars doesn't remove null bytes, but it doesn't need to for HTML safety
        // This test verifies the function doesn't crash with null bytes
        $this->assertIsString($result);
        $this->assertEquals(strlen($input), strlen($result));
    }

    public function testValidateFrameworksPreservesOrder(): void {
        $frameworks = ['PCI-DSS', 'NIST', 'GDPR'];
        $valid = ['NIST', 'PCI-DSS', 'GDPR', 'ISO'];

        $result = Security::validateFrameworks($frameworks, $valid);
        $resultValues = array_values($result);

        // Check that order is preserved
        $this->assertEquals('PCI-DSS', $resultValues[0]);
        $this->assertEquals('NIST', $resultValues[1]);
        $this->assertEquals('GDPR', $resultValues[2]);
    }

    public function testValidateProfileWithNumericInput(): void {
        $result = Security::validateProfile(123);
        $this->assertEquals('Security', $result);
    }

    public function testValidateLOBWithSpecialCharacters(): void {
        $result = Security::validateLOB('Finance<script>');
        $this->assertNull($result);
    }

    public function testEscapeWithMixedContent(): void {
        $input = 'Normal text <b>bold</b> & "quoted" text';
        $result = Security::escape($input);

        $this->assertStringContainsString('Normal text', $result);
        $this->assertStringContainsString('&lt;b&gt;', $result);
        $this->assertStringContainsString('&amp;', $result);
        $this->assertStringContainsString('&quot;', $result);
    }
}
