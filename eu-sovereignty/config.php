<?php
/**
 * EU Cloud Sovereignty Framework Assessment Questions
 * Based on European Commission Cloud Sovereignty Framework (Version 1.2.1, Oct 2025)
 *
 * 8 Sovereignty Objectives (SOV):
 * - SOV-1: Strategic Sovereignty (15%)
 * - SOV-2: Legal & Jurisdictional Sovereignty (10%)
 * - SOV-3: Data & AI Sovereignty (10%)
 * - SOV-4: Operational Sovereignty (15%)
 * - SOV-5: Supply Chain Sovereignty (20%)
 * - SOV-6: Technology Sovereignty (15%)
 * - SOV-7: Security & Compliance Sovereignty (10%)
 * - SOV-8: Environmental Sustainability (5%)
 *
 * SEAL Levels: 0 (No Sovereignty) to 4 (Full Digital Sovereignty)
 */

return [
    'Strategic Sovereignty' => [
        'domain_key' => 'sov1',
        'icon' => 'fa-chess-king',
        'description' => 'Ensures that decision-making bodies and governance structures are anchored in the EU legal, financial and industrial ecosystem',
        'weight' => 0.15,
        'contributing_factors' => [
            'EU authority with protection against control changes',
            'Financing from EU funds',
            'Investment and job creation within the EU',
            'Participation in EU initiatives',
            'Resilience to service interruption requests'
        ],
        'next_steps' => [
            'Prioritize EU-based cloud providers with headquarters and decision-making centers within EU borders',
            'Negotiate contractual protections against change of control to non-EU entities without your approval'
        ],
        'questions' => [
            [
                'id' => 'sov1_1',
                'text' => 'Is your cloud service provider incorporated and headquartered within the European Union?',
                'tooltip' => 'Strategic sovereignty requires EU-based legal entities with decisive authority under EU jurisdiction'
            ],
            [
                'id' => 'sov1_2',
                'text' => 'Does your organization have contractual guarantees that prevent change of control to non-EU entities without approval?',
                'tooltip' => 'Protections against acquisition or control transfers to non-EU parties'
            ],
            [
                'id' => 'sov1_3',
                'text' => 'Is the majority of your provider\'s financing sourced from EU-based investors or institutions?',
                'tooltip' => 'Reduces dependency on non-EU capital and potential foreign influence'
            ]
        ]
    ],

    'Legal & Jurisdictional Sovereignty' => [
        'domain_key' => 'sov2',
        'icon' => 'fa-gavel',
        'description' => 'Minimizes exposure to foreign legislation (such as US Cloud Act) and ensures enforceability of European rights',
        'weight' => 0.10,
        'contributing_factors' => [
            'Applicable law aligned with EU requirements',
            'Protection from non-EU legal system application',
            'International regulatory compliance',
            'EU-based intellectual property location'
        ],
        'next_steps' => [
            'Ensure all cloud contracts explicitly specify EU member state law as governing jurisdiction',
            'Request provider certification that they are not subject to foreign disclosure laws (US Cloud Act, FISA)'
        ],
        'questions' => [
            [
                'id' => 'sov2_1',
                'text' => 'Do your cloud service contracts explicitly specify EU member state law as the governing jurisdiction?',
                'tooltip' => 'Ensures disputes are resolved under EU legal framework'
            ],
            [
                'id' => 'sov2_2',
                'text' => 'Has your provider certified that they are not subject to foreign laws (e.g., US Cloud Act, FISA) that could compel data disclosure?',
                'tooltip' => 'Protection from extra-territorial data access requests'
            ],
            [
                'id' => 'sov2_3',
                'text' => 'Are all contractually agreed dispute resolution venues located within the European Union?',
                'tooltip' => 'Ensures legal recourse is available under EU jurisdiction'
            ]
        ]
    ],

    'Data & AI Sovereignty' => [
        'domain_key' => 'sov3',
        'icon' => 'fa-database',
        'description' => 'Ensures customer control over data and AI models, including processing locations and encryption keys',
        'weight' => 0.10,
        'contributing_factors' => [
            'Customer control over data and encryption keys',
            'Transparency in data access and option of permanent deletion',
            'Data storage and processing exclusively within EU borders',
            'AI models under EU governance using European technology stacks'
        ],
        'next_steps' => [
            'Implement customer-managed encryption keys (CMK) or bring-your-own-key (BYOK) solutions',
            'Verify all data (including backups and logs) is stored and processed exclusively in EU data centers'
        ],
        'questions' => [
            [
                'id' => 'sov3_1',
                'text' => 'Is all of your organizational data (including backups and logs) processed and stored exclusively within EU data centers?',
                'tooltip' => 'Data residency within EU borders for all data types'
            ],
            [
                'id' => 'sov3_2',
                'text' => 'Does your organization retain exclusive control of encryption keys with no provider access?',
                'tooltip' => 'Customer-managed keys (CMK) or bring-your-own-key (BYOK) models'
            ],
            [
                'id' => 'sov3_3',
                'text' => 'Do you have contractual guarantees that your data and AI models will not be used for provider training or profiling without explicit consent?',
                'tooltip' => 'Protection against unauthorized use of customer data and intellectual property'
            ]
        ]
    ],

    'Operational Sovereignty' => [
        'domain_key' => 'sov4',
        'icon' => 'fa-users-gear',
        'description' => 'Ensures practical ability to manage, support and maintain technology independent of foreign control',
        'weight' => 0.15,
        'contributing_factors' => [
            'Migration support to other EU vendors',
            'Operational expertise from EU personnel',
            'Full availability of technical documentation, source code and operational know-how',
            'Critical supplier location within EU jurisdiction'
        ],
        'next_steps' => [
            'Require EU-based administrative and technical support teams in your cloud service contracts',
            'Develop and test exit strategies to enable migration to alternative EU providers within regulatory timeframes'
        ],
        'questions' => [
            [
                'id' => 'sov4_1',
                'text' => 'Are all administrative and technical support teams for your cloud services located within the European Union?',
                'tooltip' => 'EU-based operations teams ensure operational independence'
            ],
            [
                'id' => 'sov4_2',
                'text' => 'Can your organization independently operate and recover critical systems without relying on non-EU third parties?',
                'tooltip' => 'Self-sufficiency in incident response and business continuity'
            ],
            [
                'id' => 'sov4_3',
                'text' => 'Do you have documented and tested exit strategies that enable migration from your current provider within EU regulatory timeframes?',
                'tooltip' => 'Portability and reversibility without vendor lock-in'
            ]
        ]
    ],

    'Supply Chain Sovereignty' => [
        'domain_key' => 'sov5',
        'icon' => 'fa-industry',
        'description' => 'Ensures transparency and EU control over critical software components in the supply chain',
        'weight' => 0.20,
        'contributing_factors' => [
            'Firmware origin transparency',
            'Software development location and legal framework',
            'Architecture, packaging, distribution governance',
            'Transparency in the supply chain with inspection and audit rights'
        ],
        'next_steps' => [
            'Request Software Bill of Materials (SBOM) for all critical components including origin and manufacturing locations',
            'Implement vendor diversity strategies to avoid single points of failure from non-EU suppliers'
        ],
        'questions' => [
            [
                'id' => 'sov5_1',
                'text' => 'Do you have full transparency into the supply chain of critical components, including their origin and manufacturing location?',
                'tooltip' => 'Software Bill of Materials (SBOM) for dependencies'
            ],
            [
                'id' => 'sov5_2',
                'text' => 'Are critical software components in your infrastructure predominantly sourced from EU-based or open-source providers?',
                'tooltip' => 'Reduces dependency on proprietary non-EU software'
            ],
            [
                'id' => 'sov5_3',
                'text' => 'Has your organization implemented vendor diversity strategies to avoid single points of failure from non-EU suppliers?',
                'tooltip' => 'Multi-vendor approach reduces supply chain risk'
            ]
        ]
    ],

    'Technology Sovereignty' => [
        'domain_key' => 'sov6',
        'icon' => 'fa-microchip',
        'description' => 'Promotes independence through open standards and avoidance of proprietary vendor lock-in',
        'weight' => 0.15,
        'contributing_factors' => [
            'Well-documented, non-proprietary APIs or protocols',
            'Open-source software availability',
            'Architectural documentation',
            'Independence in high-performance computing capabilities'
        ],
        'next_steps' => [
            'Adopt open standards and interoperable technologies to enable workload portability between providers',
            'Leverage open-source technologies and contribute to EU-supported open-source projects'
        ],
        'questions' => [
            [
                'id' => 'sov6_1',
                'text' => 'Does your cloud infrastructure use open standards and interoperable technologies rather than proprietary platforms?',
                'tooltip' => 'Open standards enable portability and vendor independence'
            ],
            [
                'id' => 'sov6_2',
                'text' => 'Can your critical workloads be migrated to alternative providers without significant re-engineering?',
                'tooltip' => 'Workload portability indicates technology independence'
            ],
            [
                'id' => 'sov6_3',
                'text' => 'Do you leverage open-source technologies and contribute back to EU-supported open-source projects?',
                'tooltip' => 'Open-source adoption strengthens EU technology ecosystem'
            ]
        ]
    ],

    'Security & Compliance Sovereignty' => [
        'domain_key' => 'sov7',
        'icon' => 'fa-shield-halved',
        'description' => 'Ensures security operations and compliance controls are under exclusive EU jurisdiction',
        'weight' => 0.10,
        'contributing_factors' => [
            'ISO and ENISA certifications',
            'GDPR, NIS, DORA compliance',
            'EU-based security operations and incident response',
            'EU-compliant reporting of security incidents',
            'Patch management and audit support capabilities'
        ],
        'next_steps' => [
            'Verify Security Operations Centers (SOC) are located exclusively within the EU',
            'Ensure all security logs, audit trails, and compliance evidence remain under your control in EU jurisdictions'
        ],
        'questions' => [
            [
                'id' => 'sov7_1',
                'text' => 'Are your Security Operations Centers (SOC) located exclusively within the European Union?',
                'tooltip' => 'EU-based security monitoring and incident response'
            ],
            [
                'id' => 'sov7_2',
                'text' => 'Do you have the contractual right to conduct unannounced audits and security assessments of your cloud provider?',
                'tooltip' => 'Independent verification of security and compliance controls'
            ],
            [
                'id' => 'sov7_3',
                'text' => 'Are all security logs, audit trails, and compliance evidence stored exclusively in EU jurisdictions under your control?',
                'tooltip' => 'Evidence preservation under EU legal framework'
            ]
        ]
    ],

    'Environmental Sustainability' => [
        'domain_key' => 'sov8',
        'icon' => 'fa-leaf',
        'description' => 'Ensures long-term autonomy and resilience regarding energy consumption and resource dependencies',
        'weight' => 0.05,
        'contributing_factors' => [
            'Energy-efficient infrastructure (PUE optimization)',
            'Circular economy practices',
            'CO₂ emissions and water consumption tracking'
        ],
        'next_steps' => [
            'Select cloud providers that source energy from renewable EU-based sources',
            'Align with EU environmental regulations and sustainability frameworks (EU Taxonomy, Green Deal)'
        ],
        'questions' => [
            [
                'id' => 'sov8_1',
                'text' => 'Does your cloud provider source energy from renewable EU-based sources to reduce dependency on external energy supplies?',
                'tooltip' => 'EU renewable energy reduces geopolitical energy dependencies'
            ],
            [
                'id' => 'sov8_2',
                'text' => 'Has your organization evaluated the environmental impact of your cloud services and implemented carbon reduction strategies?',
                'tooltip' => 'Sustainable operations support long-term resilience'
            ],
            [
                'id' => 'sov8_3',
                'text' => 'Are you aligned with EU environmental regulations and sustainability frameworks (e.g., EU Taxonomy, Green Deal)?',
                'tooltip' => 'Compliance with EU environmental standards ensures future readiness'
            ]
        ]
    ]
];
