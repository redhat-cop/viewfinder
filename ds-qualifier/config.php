<?php
/**
 * Digital Sovereignty Sales Qualifier - Questions Configuration
 *
 * This file contains the qualifying questions extracted from controls-DigitalSovereignty.json
 * Designed for quick 10-15 minute sales qualification assessments
 */

return [
    'Data Sovereignty' => [
        'domain_key' => 'Domain-1',
        'description' => 'Data control, residency, and encryption sovereignty',
        'questions' => [
            [
                'id' => 'ds1',
                'text' => 'Do you have formal data residency requirements or regulations to comply with?',
                'weight' => 1
            ],
            [
                'id' => 'ds2',
                'text' => 'Do you control and manage your encryption keys exclusively (not shared with cloud providers)?',
                'weight' => 1
            ],
            [
                'id' => 'ds3',
                'text' => 'Can you prevent sensitive data from crossing specific geographic borders?',
                'weight' => 1
            ]
        ]
    ],

    'Technical Sovereignty' => [
        'domain_key' => 'Domain-2',
        'description' => 'Technology independence and platform portability',
        'questions' => [
            [
                'id' => 'ts1',
                'text' => 'Are you concerned about vendor lock-in risks with your current technology stack?',
                'weight' => 1
            ],
            [
                'id' => 'ts2',
                'text' => 'Do you prioritize open standards over proprietary APIs in your architecture?',
                'weight' => 1
            ],
            [
                'id' => 'ts3',
                'text' => 'Can you migrate critical applications to different cloud platforms if needed?',
                'weight' => 1
            ]
        ]
    ],

    'Operational Sovereignty' => [
        'domain_key' => 'Domain-3',
        'description' => 'Operational independence and resilience',
        'questions' => [
            [
                'id' => 'os1',
                'text' => 'Can you continue operating critical systems if external cloud services become unavailable?',
                'weight' => 1
            ],
            [
                'id' => 'os2',
                'text' => 'Do you have in-house technical expertise to manage sovereign infrastructure?',
                'weight' => 1
            ],
            [
                'id' => 'os3',
                'text' => 'Do you have disaster recovery plans that account for geopolitical scenarios?',
                'weight' => 1
            ]
        ]
    ],

    'Assurance Sovereignty' => [
        'domain_key' => 'Domain-4',
        'description' => 'Security, compliance, and audit control',
        'questions' => [
            [
                'id' => 'as1',
                'text' => 'Do you have contractual rights to audit your vendors\' security practices and controls?',
                'weight' => 1
            ],
            [
                'id' => 'as2',
                'text' => 'Do you control where your security logs and audit trails are stored?',
                'weight' => 1
            ],
            [
                'id' => 'as3',
                'text' => 'Are you certified against your country\'s national security standards (e.g., NIS2, SecNumCloud)?',
                'weight' => 1
            ]
        ]
    ],

    'Open Source' => [
        'domain_key' => 'Domain-5',
        'description' => 'Open source strategy and independence',
        'questions' => [
            [
                'id' => 'oss1',
                'text' => 'Do you have a formal policy favoring open-source software over proprietary alternatives?',
                'weight' => 1
            ],
            [
                'id' => 'oss2',
                'text' => 'Can you fork and independently maintain critical open-source dependencies if needed?',
                'weight' => 1
            ],
            [
                'id' => 'oss3',
                'text' => 'Do you actively contribute to strategic open-source projects important to your operations?',
                'weight' => 1
            ]
        ]
    ],

    'Executive Oversight' => [
        'domain_key' => 'Domain-6',
        'description' => 'Strategic governance and leadership commitment',
        'questions' => [
            [
                'id' => 'eo1',
                'text' => 'Do you have an executive sponsor or steering committee for digital sovereignty initiatives?',
                'weight' => 1
            ],
            [
                'id' => 'eo2',
                'text' => 'Is digital sovereignty explicitly part of your corporate or IT strategy?',
                'weight' => 1
            ],
            [
                'id' => 'eo3',
                'text' => 'Do you have a dedicated budget allocated for sovereignty initiatives and compliance?',
                'weight' => 1
            ]
        ]
    ],

    'Managed Services' => [
        'domain_key' => 'Domain-7',
        'description' => 'Cloud service control and provider independence',
        'questions' => [
            [
                'id' => 'ms1',
                'text' => 'Can you restrict cloud deployments to specific regions or certified data centers?',
                'weight' => 1
            ],
            [
                'id' => 'ms2',
                'text' => 'Do you control and monitor your cloud provider\'s administrative access to your systems?',
                'weight' => 1
            ],
            [
                'id' => 'ms3',
                'text' => 'Have you tested or validated the ability to migrate workloads to different cloud providers?',
                'weight' => 1
            ]
        ]
    ]
];
