<?php
/**
 * Cache Clearer
 *
 * Clears PHP opcode cache for web server
 */

header('Content-Type: application/json');

$result = [
    'opcache_enabled' => false,
    'opcache_cleared' => false,
    'timestamp' => date('Y-m-d H:i:s')
];

if (function_exists('opcache_reset')) {
    $result['opcache_enabled'] = true;
    $result['opcache_cleared'] = opcache_reset();
}

echo json_encode($result, JSON_PRETTY_PRINT);
