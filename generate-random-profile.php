<?php
/**
 * Random Profile Generator
 *
 * Quickly generate a test profile with random data
 * Usage: php generate-random-profile.php [ProfileName]
 */

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/includes/Config.php';
require_once __DIR__ . '/includes/ProfileGenerator.php';
require_once __DIR__ . '/includes/FileUpdater.php';
require_once __DIR__ . '/includes/Logger.php';
require_once __DIR__ . '/includes/Exceptions/ProfileException.php';

// Get profile name from command line or use default
$profileName = $argv[1] ?? 'Random';
$displayName = $argv[2] ?? 'Random Test Profile';

// Sample data for random generation
$domainTitles = [
    'Infrastructure Automation',
    'Continuous Integration & Deployment',
    'Configuration Management',
    'Monitoring & Observability',
    'Security & Compliance Automation',
    'Testing Automation',
    'DevOps Culture & Collaboration'
];

$capabilityPrefixes = [
    'Basic', 'Initial', 'Defined', 'Managed', 'Optimized',
    'Advanced', 'Strategic', 'Innovative'
];

$capabilitySuffixes = [
    'Implementation', 'Practices', 'Framework', 'Strategy',
    'Maturity', 'Excellence', 'Leadership', 'Transformation'
];

$tiers = ['Foundation', 'Foundation', 'Foundation', 'Strategic', 'Strategic', 'Strategic', 'Advanced', 'Advanced'];

/**
 * Generate random capability name
 */
function generateCapabilityName($level) {
    global $capabilityPrefixes, $capabilitySuffixes;
    return $capabilityPrefixes[$level - 1] . ' ' . $capabilitySuffixes[array_rand($capabilitySuffixes)];
}

/**
 * Generate random summary
 */
function generateSummary($capName) {
    $summaries = [
        "This level focuses on {$capName} with basic implementation.",
        "Achieving {$capName} through systematic approaches and best practices.",
        "Organizations at this level demonstrate {$capName} across teams.",
        "Advanced capabilities in {$capName} with measurable outcomes.",
        "Industry-leading {$capName} with continuous innovation."
    ];
    return $summaries[array_rand($summaries)];
}

/**
 * Generate random HTML recommendation
 */
function generateRecommendation($capName, $level) {
    $recommendations = [
        "<h2>Getting Started</h2><p>To achieve {$capName}, organizations should:</p><ul><li>Establish baseline processes</li><li>Document current state</li><li>Identify quick wins</li></ul>",
        "<h2>Building Capability</h2><p>Focus on:</p><ul><li>Tool selection and implementation</li><li>Team training and enablement</li><li>Process standardization</li></ul>",
        "<h2>Scaling Excellence</h2><p>Key focus areas:</p><ul><li>Automation at scale</li><li>Cross-team collaboration</li><li>Metrics and measurement</li></ul>",
        "<h2>Advanced Maturity</h2><p>Organizations should:</p><ul><li>Drive continuous improvement</li><li>Share best practices</li><li>Innovate new approaches</li></ul>"
    ];
    return $recommendations[min($level - 1, count($recommendations) - 1)];
}

try {
    echo "==========================================\n";
    echo "Random Profile Generator\n";
    echo "==========================================\n\n";

    echo "Generating profile: {$profileName}\n";
    echo "Display name: {$displayName}\n\n";

    // Build wizard data structure
    $wizardData = [
        'metadata' => [
            'profile_name' => $profileName,
            'display_name' => $displayName,
            'enabled' => true
        ],
        'domains' => []
    ];

    // Generate 7 domains
    for ($d = 1; $d <= 7; $d++) {
        echo "Generating Domain {$d}: {$domainTitles[$d - 1]}\n";

        $wizardData['domains'][$d] = [
            'title' => $domainTitles[$d - 1],
            'overview' => "This domain covers {$domainTitles[$d - 1]} practices and capabilities, "
                        . "ranging from foundational approaches to advanced strategic implementations.",
            'capabilities' => []
        ];

        // Generate 8 capabilities per domain
        for ($c = 1; $c <= 8; $c++) {
            $capName = generateCapabilityName($c);

            $wizardData['domains'][$d]['capabilities'][$c] = [
                'name' => $capName,
                'tier' => $tiers[$c - 1],
                'summary' => generateSummary($capName),
                'recommendation' => generateRecommendation($capName, $c)
            ];
        }
    }

    echo "\nGenerating profile files...\n";

    // Generate the profile
    $result = ProfileGenerator::generateProfile($wizardData);

    echo "\n==========================================\n";
    echo "SUCCESS!\n";
    echo "==========================================\n\n";
    echo "Profile Name: {$result['profile_name']}\n";
    echo "Display Name: {$result['display_name']}\n";
    echo "Files Created:\n";
    foreach ($result['files_created'] as $file) {
        echo "  - {$file}\n";
    }
    echo "\nYou can now access the profile at:\n";
    echo "  index.php?profile={$result['profile_name']}\n\n";

    exit(0);

} catch (ProfileException $e) {
    echo "\n==========================================\n";
    echo "ERROR!\n";
    echo "==========================================\n\n";
    echo "Error: {$e->getUserMessage()}\n";
    echo "Details: {$e->getMessage()}\n\n";
    exit(1);

} catch (Exception $e) {
    echo "\n==========================================\n";
    echo "UNEXPECTED ERROR!\n";
    echo "==========================================\n\n";
    echo "Error: {$e->getMessage()}\n";
    echo "File: {$e->getFile()}:{$e->getLine()}\n\n";
    exit(1);
}
