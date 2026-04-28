<?php
/**
 * Add domain grouping metadata to controls-DigitalSovereignty.json
 * Implements Option B: Tagged Grouping approach
 */

$jsonFile = __DIR__ . '/controls-DigitalSovereignty.json';
$json = json_decode(file_get_contents($jsonFile), true);

if (!$json) {
    die("Error loading JSON file\n");
}

// Add metadata to each domain
foreach ($json as $domainKey => &$domainData) {
    if (strpos($domainKey, 'Domain-') !== 0) {
        continue;
    }

    switch ($domainKey) {
        case 'Domain-1':
            // Data Sovereignty - Main domain
            $domainData['group'] = 'main';
            $domainData['display_order'] = 1;
            $domainData['includes_subdomains'] = false;
            break;

        case 'Domain-2':
            // Technical Sovereignty - Main domain with Open Source sub-pillar
            $domainData['group'] = 'main';
            $domainData['display_order'] = 2;
            $domainData['includes_subdomains'] = true;
            $domainData['subdomain_titles'] = ['Domain-5'];
            $domainData['subtitle'] = 'Including Open Source & Vendor Independence';
            $domainData['section_1_label'] = 'Core Technical Sovereignty';
            $domainData['section_1_capabilities'] = 'Capabilities 1-8';
            $domainData['section_2_label'] = 'Open Source (Sub-Pillar)';
            $domainData['section_2_capabilities'] = 'Capabilities from Open Source domain';
            $domainData['section_2_source'] = 'Domain-5';
            break;

        case 'Domain-3':
            // Operational Sovereignty - Main domain with Managed Services sub-pillar
            $domainData['group'] = 'main';
            $domainData['display_order'] = 3;
            $domainData['includes_subdomains'] = true;
            $domainData['subdomain_titles'] = ['Domain-7'];
            $domainData['subtitle'] = 'Including Managed Services & Third Parties';
            $domainData['section_1_label'] = 'Core Operational Sovereignty';
            $domainData['section_1_capabilities'] = 'Capabilities 1-8';
            $domainData['section_2_label'] = 'Managed Services (Sub-Pillar)';
            $domainData['section_2_capabilities'] = 'Capabilities from Managed Services domain';
            $domainData['section_2_source'] = 'Domain-7';
            break;

        case 'Domain-4':
            // Assurance Sovereignty - Main domain
            $domainData['group'] = 'main';
            $domainData['display_order'] = 4;
            $domainData['includes_subdomains'] = false;
            break;

        case 'Domain-5':
            // Open Source - Sub-domain under Technical Sovereignty
            $domainData['group'] = 'subdomain';
            $domainData['parent_domain'] = 'Domain-2';
            $domainData['display_in_main_nav'] = false;
            $domainData['integrated_note'] = 'This domain is now integrated as a sub-pillar of Technical Sovereignty';
            break;

        case 'Domain-6':
            // Executive Oversight - Cross-cutting
            $domainData['group'] = 'cross_cutting';
            $domainData['display_order'] = 5;
            $domainData['badge'] = 'Cross-Cutting';
            $domainData['icon'] = 'fa-chess-king';
            $domainData['description_short'] = 'Foundational governance layer that applies across all sovereignty domains';
            break;

        case 'Domain-7':
            // Managed Services - Sub-domain under Operational Sovereignty
            $domainData['group'] = 'subdomain';
            $domainData['parent_domain'] = 'Domain-3';
            $domainData['display_in_main_nav'] = false;
            $domainData['integrated_note'] = 'This domain is now integrated as a sub-pillar of Operational Sovereignty';
            break;
    }
}

// Write back to file
$jsonOutput = json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
file_put_contents($jsonFile, $jsonOutput);

echo "✅ Successfully added domain grouping metadata to controls-DigitalSovereignty.json\n\n";
echo "Summary:\n";
echo "- Domain-1 (Data Sovereignty): Main domain\n";
echo "- Domain-2 (Technical Sovereignty): Main domain + includes Open Source\n";
echo "- Domain-3 (Operational Sovereignty): Main domain + includes Managed Services\n";
echo "- Domain-4 (Assurance Sovereignty): Main domain\n";
echo "- Domain-5 (Open Source): Sub-domain of Technical Sovereignty\n";
echo "- Domain-6 (Executive Oversight): Cross-cutting\n";
echo "- Domain-7 (Managed Services): Sub-domain of Operational Sovereignty\n";
echo "\nDomain structure updated successfully!\n";
?>
