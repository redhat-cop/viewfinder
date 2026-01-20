<?php
/**
 * FileUpdater - Safe file modification utilities
 *
 * Handles updating Config.php and index.php with backup/rollback support
 */

require_once __DIR__ . '/Config.php';
require_once __DIR__ . '/Logger.php';
require_once __DIR__ . '/Exceptions/ProfileException.php';

class FileUpdater {

    /**
     * Create a timestamped backup of a file
     *
     * @param string $filePath Path to file to backup
     * @return string Path to backup file
     * @throws ProfileException If backup fails
     */
    public static function createBackup(string $filePath): string {
        $backupPath = $filePath . '.backup.' . date('YmdHis');

        if (!file_exists($filePath)) {
            throw ProfileException::backupFailed($filePath);
        }

        if (!copy($filePath, $backupPath)) {
            throw ProfileException::backupFailed($filePath);
        }

        Logger::info('File backup created', ['file' => $filePath, 'backup' => $backupPath]);

        return $backupPath;
    }

    /**
     * Restore a file from backup
     *
     * @param string $backupPath Path to backup file
     * @throws ProfileException If restore fails
     */
    public static function restoreBackup(string $backupPath): void {
        $originalPath = preg_replace('/\.backup\.\d+$/', '', $backupPath);

        if (!file_exists($backupPath)) {
            throw new ProfileException(
                "Backup file not found: {$backupPath}",
                "System restore failed - backup file missing.",
                ['backup' => $backupPath]
            );
        }

        if (!copy($backupPath, $originalPath)) {
            throw new ProfileException(
                "Failed to restore from backup: {$backupPath}",
                "System restore failed.",
                ['backup' => $backupPath, 'original' => $originalPath]
            );
        }

        @unlink($backupPath);
        Logger::info('File restored from backup', ['file' => $originalPath]);
    }

    /**
     * Update Config.php with new profile entry
     *
     * @param string $profileName Internal profile name
     * @param string $displayName Display name for UI
     * @param bool $enabled Whether profile is enabled
     * @return string Path to backup file
     * @throws ProfileException If update fails
     */
    public static function updateConfigPHP(string $profileName, string $displayName, bool $enabled): string {
        $configPath = Config::getBasePath() . '/includes/Config.php';

        // Sanitize inputs
        $profileName = preg_replace('/[^a-zA-Z0-9_]/', '', $profileName);
        $displayName = str_replace("'", "\\'", $displayName);
        $enabledStr = $enabled ? 'true' : 'false';

        // Create backup
        $backupPath = self::createBackup($configPath);

        try {
            $content = file_get_contents($configPath);

            if ($content === false) {
                throw ProfileException::fileWriteFailed($configPath, 'Could not read file');
            }

            // Build new profile entry
            $newEntry = "        '{$profileName}' => [\n"
                      . "            'name' => '{$profileName}',\n"
                      . "            'display_name' => '{$displayName}',\n"
                      . "            'enabled' => {$enabledStr}\n"
                      . "        ],\n";

            // Find the PROFILES constant and insert before closing bracket
            // Strategy: Find "const PROFILES = [" then count brackets to find matching close
            $startPos = strpos($content, 'const PROFILES');
            if ($startPos === false) {
                throw new ProfileException(
                    "Could not find 'const PROFILES' in Config.php",
                    "Configuration file structure is invalid.",
                    ['profile' => $profileName]
                );
            }

            // Find the opening bracket of PROFILES
            $openPos = strpos($content, '[', $startPos);
            if ($openPos === false) {
                throw new ProfileException(
                    "Could not find opening bracket for PROFILES constant",
                    "Configuration file structure is invalid.",
                    ['profile' => $profileName]
                );
            }

            // Count brackets to find the matching closing bracket
            $depth = 0;
            $closePos = $openPos;
            $len = strlen($content);

            for ($i = $openPos; $i < $len; $i++) {
                if ($content[$i] === '[') {
                    $depth++;
                } elseif ($content[$i] === ']') {
                    $depth--;
                    if ($depth === 0) {
                        $closePos = $i;
                        break;
                    }
                }
            }

            if ($depth !== 0) {
                throw new ProfileException(
                    "Could not find matching closing bracket for PROFILES constant",
                    "Configuration file structure is invalid.",
                    ['profile' => $profileName]
                );
            }

            // Check if we need to add a comma after the previous entry
            // Look backwards from closePos to find the last non-whitespace character
            $checkPos = $closePos - 1;
            while ($checkPos >= 0 && ctype_space($content[$checkPos])) {
                $checkPos--;
            }

            // If the last character is not a comma, we need to add one
            $needsComma = ($checkPos >= 0 && $content[$checkPos] !== ',');

            // Find where to insert - we want to insert right after any whitespace before the ]
            // This preserves the indentation pattern
            $insertPos = $closePos;

            // Look backwards to skip any whitespace (including newlines) before the ]
            $wsStart = $closePos - 1;
            while ($wsStart >= 0 && ctype_space($content[$wsStart])) {
                $wsStart--;
            }
            // Position right after the last non-whitespace character (could be a ])
            $insertPos = $wsStart + 1;

            // Insert the new entry
            $insertion = $needsComma ? ",\n{$newEntry}" : "\n{$newEntry}";
            $newContent = substr_replace($content, $insertion, $insertPos, 0);
            $count = 1;

            if ($count === 0 || $newContent === null || $newContent === $content) {
                throw new ProfileException(
                    "Failed to update Config.php - pattern not found or no changes made",
                    "Could not register profile in configuration.",
                    ['profile' => $profileName]
                );
            }

            // Write atomically using temp file
            $tempPath = $configPath . '.tmp';
            if (file_put_contents($tempPath, $newContent) === false) {
                throw ProfileException::fileWriteFailed($tempPath, 'Could not write temp file');
            }

            if (!rename($tempPath, $configPath)) {
                @unlink($tempPath);
                throw ProfileException::fileWriteFailed($configPath, 'Could not rename temp file');
            }

            Logger::info('Config.php updated successfully', ['profile' => $profileName]);

            return $backupPath;

        } catch (Exception $e) {
            // Restore on failure
            self::restoreBackup($backupPath);
            throw $e;
        }
    }

    /**
     * Update index.php with navigation button
     *
     * @param string $profileName Internal profile name
     * @param string $displayName Display name for button
     * @return string Path to backup file
     * @throws ProfileException If update fails
     */
    public static function updateIndexPHP(string $profileName, string $displayName): string {
        $indexPath = Config::getBasePath() . '/index.php';

        // Sanitize inputs
        $profileName = preg_replace('/[^a-zA-Z0-9_]/', '', $profileName);
        $displayName = htmlspecialchars($displayName, ENT_QUOTES, 'UTF-8');

        // Create backup
        $backupPath = self::createBackup($indexPath);

        try {
            $content = file_get_contents($indexPath);

            if ($content === false) {
                throw ProfileException::fileWriteFailed($indexPath, 'Could not read file');
            }

            // Build new button
            $newButton = "              <a href=\"index.php?profile={$profileName}\"><button>{$displayName}</button></a>&nbsp\n";

            // Find the profile buttons section and insert after the last enabled profile button
            // Look for the pattern after DigitalSovereignty button and before the comment or closing div
            $pattern = '/(              <a href="index\.php\?profile=DigitalSovereignty">.*?<\/a>&nbsp)(\s*(?:<!--.*?-->\s*)?<\/div>)/s';
            $replacement = "$1\n{$newButton}$2";
            $newContent = preg_replace($pattern, $replacement, $content, 1, $count);

            if ($count === 0 || $newContent === null || $newContent === $content) {
                throw new ProfileException(
                    "Failed to update index.php - pattern not found or no changes made",
                    "Could not add navigation button.",
                    ['profile' => $profileName]
                );
            }

            // Write atomically using temp file
            $tempPath = $indexPath . '.tmp';
            if (file_put_contents($tempPath, $newContent) === false) {
                throw ProfileException::fileWriteFailed($tempPath, 'Could not write temp file');
            }

            if (!rename($tempPath, $indexPath)) {
                @unlink($tempPath);
                throw ProfileException::fileWriteFailed($indexPath, 'Could not rename temp file');
            }

            Logger::info('index.php updated successfully', ['profile' => $profileName]);

            return $backupPath;

        } catch (Exception $e) {
            // Restore on failure
            self::restoreBackup($backupPath);
            throw $e;
        }
    }

    /**
     * Update existing Config.php profile entry (for editing display_name or enabled)
     *
     * @param string $profileName Profile name to update
     * @param string $newDisplayName New display name
     * @param bool $newEnabled New enabled status
     * @return string Path to backup file
     * @throws ProfileException If update fails
     */
    public static function updateConfigEntry(string $profileName, string $newDisplayName, bool $newEnabled): string {
        $configPath = Config::getBasePath() . '/includes/Config.php';

        // Sanitize inputs
        $profileName = preg_replace('/[^a-zA-Z0-9_]/', '', $profileName);
        $newDisplayName = str_replace("'", "\\'", $newDisplayName);
        $newEnabledStr = $newEnabled ? 'true' : 'false';

        // Create backup
        $backupPath = self::createBackup($configPath);

        try {
            $content = file_get_contents($configPath);

            if ($content === false) {
                throw ProfileException::fileWriteFailed($configPath, 'Could not read file');
            }

            // Find and replace the profile entry
            // Pattern: 'ProfileName' => [ 'name' => 'ProfileName', 'display_name' => '...', 'enabled' => true/false ]
            $pattern = "/('{$profileName}'\\s*=>\\s*\\[\\s*'name'\\s*=>\\s*'{$profileName}',\\s*)'display_name'\\s*=>\\s*'[^']*',(\\s*'enabled'\\s*=>\\s*)(?:true|false)/s";
            $replacement = "$1'display_name' => '{$newDisplayName}',$2{$newEnabledStr}";
            $newContent = preg_replace($pattern, $replacement, $content, 1, $count);

            if ($count === 0 || $newContent === null || $newContent === $content) {
                throw new ProfileException(
                    "Failed to update Config.php entry for '{$profileName}' - pattern not found or no changes made",
                    "Could not update profile configuration.",
                    ['profile' => $profileName]
                );
            }

            // Write atomically using temp file
            $tempPath = $configPath . '.tmp';
            if (file_put_contents($tempPath, $newContent) === false) {
                throw ProfileException::fileWriteFailed($tempPath, 'Could not write temp file');
            }

            if (!rename($tempPath, $configPath)) {
                @unlink($tempPath);
                throw ProfileException::fileWriteFailed($configPath, 'Could not rename temp file');
            }

            Logger::info('Config.php entry updated successfully', [
                'profile' => $profileName,
                'display_name' => $newDisplayName,
                'enabled' => $newEnabled
            ]);

            return $backupPath;

        } catch (Exception $e) {
            // Restore on failure
            self::restoreBackup($backupPath);
            throw $e;
        }
    }
}
