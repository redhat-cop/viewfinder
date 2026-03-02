<?php
/**
 * LOB (Line of Business) Weighting Profiles
 *
 * Defines domain weight multipliers for different industries/contexts
 * Weights: 1.0 = standard, 1.5 = higher priority, 2.0 = critical
 *
 * Each assessment profile (Security, DigitalSovereignty) has different domains,
 * so weights are defined separately for each.
 */

return [
    // ============================================
    // SECURITY PROFILE WEIGHTS
    // ============================================
    'Security' => [
        'Balanced' => [
            'name' => 'Balanced',
            'description' => 'Equal weighting across all security domains',
            'icon' => 'fa-balance-scale',
            'weights' => [
                'Secure Infrastructure' => 1.0,
                'Secure Data' => 1.0,
                'Secure Identity' => 1.0,
                'Secure Application' => 1.0,
                'Secure Network' => 1.0,
                'Secure Recovery' => 1.0,
                'Secure Operations' => 1.0
            ]
        ],
        'General' => [
            'name' => 'General',
            'description' => 'Balanced approach suitable for general organizations without specific industry focus',
            'icon' => 'fa-building',
            'weights' => [
                'Secure Infrastructure' => 1.0,
                'Secure Data' => 1.0,
                'Secure Identity' => 1.0,
                'Secure Application' => 1.0,
                'Secure Network' => 1.0,
                'Secure Recovery' => 1.0,
                'Secure Operations' => 1.0
            ]
        ],
        'Finance' => [
            'name' => 'Finance',
            'description' => 'Emphasizes data protection, identity management, and recovery for regulatory compliance (PCI DSS, SOX)',
            'icon' => 'fa-building-columns',
            'weights' => [
                'Secure Infrastructure' => 1.5,  // Important: Secure systems
                'Secure Data' => 2.0,            // Critical: PCI DSS, data residency
                'Secure Identity' => 2.0,        // Critical: Access controls, audit trails
                'Secure Application' => 1.5,     // Important: Secure transactions
                'Secure Network' => 1.5,         // Important: Network segmentation
                'Secure Recovery' => 2.0,        // Critical: Business continuity
                'Secure Operations' => 1.5       // Important: Security monitoring
            ]
        ],
        'Healthcare' => [
            'name' => 'Healthcare',
            'description' => 'Focuses on patient data protection (HIPAA) and operational resilience for 24/7 healthcare delivery',
            'icon' => 'fa-heart-pulse',
            'weights' => [
                'Secure Infrastructure' => 1.5,  // Important: Medical systems
                'Secure Data' => 2.0,            // Critical: HIPAA, patient records
                'Secure Identity' => 2.0,        // Critical: Access to patient data
                'Secure Application' => 1.5,     // Important: Healthcare apps
                'Secure Network' => 1.5,         // Important: Medical devices
                'Secure Recovery' => 2.0,        // Critical: Patient safety
                'Secure Operations' => 1.5       // Important: 24/7 monitoring
            ]
        ],
        'Government' => [
            'name' => 'Government',
            'description' => 'Comprehensive security for public sector organizations handling sensitive citizen data and critical infrastructure',
            'icon' => 'fa-landmark',
            'weights' => [
                'Secure Infrastructure' => 2.0,  // Critical: National infrastructure
                'Secure Data' => 2.0,            // Critical: Citizen data
                'Secure Identity' => 2.0,        // Critical: Government access
                'Secure Application' => 1.5,     // Important: Public services
                'Secure Network' => 2.0,         // Critical: Network security
                'Secure Recovery' => 1.5,        // Important: Continuity
                'Secure Operations' => 2.0       // Critical: Security operations
            ]
        ],
        'Manufacturing' => [
            'name' => 'Manufacturing',
            'description' => 'Emphasizes operational resilience, network security for OT/IT convergence, and IP protection',
            'icon' => 'fa-industry',
            'weights' => [
                'Secure Infrastructure' => 1.5,  // Important: Industrial systems
                'Secure Data' => 1.5,            // Important: IP protection
                'Secure Identity' => 1.5,        // Important: OT access
                'Secure Application' => 1.0,     // Standard
                'Secure Network' => 2.0,         // Critical: OT/IT segmentation
                'Secure Recovery' => 2.0,        // Critical: Production uptime
                'Secure Operations' => 2.0       // Critical: Continuous operations
            ]
        ],
        'Telecommunications' => [
            'name' => 'Telecommunications',
            'description' => 'Focuses on network security, infrastructure protection, and service availability for critical communications',
            'icon' => 'fa-tower-cell',
            'weights' => [
                'Secure Infrastructure' => 2.0,  // Critical: Telecom infrastructure
                'Secure Data' => 2.0,            // Critical: Subscriber data
                'Secure Identity' => 1.5,        // Important: Access management
                'Secure Application' => 1.5,     // Important: Services
                'Secure Network' => 2.0,         // Critical: Network security
                'Secure Recovery' => 2.0,        // Critical: Service availability
                'Secure Operations' => 2.0       // Critical: 24/7 operations
            ]
        ],
        'Other' => [
            'name' => 'Other',
            'description' => 'Balanced approach suitable for general organizations without specific regulatory constraints',
            'icon' => 'fa-building',
            'weights' => [
                'Secure Infrastructure' => 1.0,
                'Secure Data' => 1.0,
                'Secure Identity' => 1.0,
                'Secure Application' => 1.0,
                'Secure Network' => 1.0,
                'Secure Recovery' => 1.0,
                'Secure Operations' => 1.0
            ]
        ]
    ],

    // ============================================
    // DIGITAL SOVEREIGNTY PROFILE WEIGHTS
    // ============================================
    'DigitalSovereignty' => [
        'Balanced' => [
            'name' => 'Balanced',
            'description' => 'Equal weighting across all sovereignty domains',
            'icon' => 'fa-balance-scale',
            'weights' => [
                'Data Sovereignty' => 1.0,
                'Technical Sovereignty' => 1.0,
                'Operational Sovereignty' => 1.0,
                'Assurance Sovereignty' => 1.0,
                'Open Source' => 1.0,
                'Executive Oversight' => 1.0,
                'Managed Services' => 1.0
            ]
        ],
        'General' => [
            'name' => 'General',
            'description' => 'Balanced approach suitable for general organizations without specific industry focus',
            'icon' => 'fa-building',
            'weights' => [
                'Data Sovereignty' => 1.0,
                'Technical Sovereignty' => 1.0,
                'Operational Sovereignty' => 1.0,
                'Assurance Sovereignty' => 1.0,
                'Open Source' => 1.0,
                'Executive Oversight' => 1.0,
                'Managed Services' => 1.0
            ]
        ],
        'Finance' => [
            'name' => 'Finance',
            'description' => 'Emphasizes data protection, audit controls, and compliance for banking and finance (DORA, PCI DSS)',
            'icon' => 'fa-building-columns',
            'weights' => [
                'Data Sovereignty' => 2.0,           // Critical: Data residency
                'Technical Sovereignty' => 1.0,      // Standard
                'Operational Sovereignty' => 1.5,    // Important: Business continuity
                'Assurance Sovereignty' => 2.0,      // Critical: Audit requirements
                'Open Source' => 1.0,                // Standard
                'Executive Oversight' => 1.5,        // Important: Governance
                'Managed Services' => 1.5            // Important: Third-party risk
            ]
        ],
        'Healthcare' => [
            'name' => 'Healthcare',
            'description' => 'Focuses on patient data sovereignty and operational resilience for healthcare systems (HIPAA, GDPR)',
            'icon' => 'fa-heart-pulse',
            'weights' => [
                'Data Sovereignty' => 2.0,           // Critical: Patient data
                'Technical Sovereignty' => 1.0,      // Standard
                'Operational Sovereignty' => 2.0,    // Critical: 24/7 operations
                'Assurance Sovereignty' => 1.5,      // Important: Compliance
                'Open Source' => 1.0,                // Standard
                'Executive Oversight' => 1.5,        // Important
                'Managed Services' => 1.5            // Important
            ]
        ],
        'Government' => [
            'name' => 'Government',
            'description' => 'Comprehensive sovereignty for public sector handling sensitive citizen data and national infrastructure (NIS2, FedRAMP)',
            'icon' => 'fa-landmark',
            'weights' => [
                'Data Sovereignty' => 2.0,           // Critical: Citizen data
                'Technical Sovereignty' => 1.5,      // Important: Independence
                'Operational Sovereignty' => 1.5,    // Important: Continuity
                'Assurance Sovereignty' => 2.0,      // Critical: National security
                'Open Source' => 1.5,                // Important: Transparency
                'Executive Oversight' => 2.0,        // Critical: Accountability
                'Managed Services' => 1.5            // Important: Control
            ]
        ],
        'Manufacturing' => [
            'name' => 'Manufacturing',
            'description' => 'Emphasizes operational resilience and managed services for industrial operations and IP protection',
            'icon' => 'fa-industry',
            'weights' => [
                'Data Sovereignty' => 1.5,           // Important: IP protection
                'Technical Sovereignty' => 1.0,      // Standard
                'Operational Sovereignty' => 2.0,    // Critical: Production uptime
                'Assurance Sovereignty' => 1.5,      // Important: Quality
                'Open Source' => 1.0,                // Standard
                'Executive Oversight' => 1.5,        // Important
                'Managed Services' => 2.0            // Critical: OT/IT integration
            ]
        ],
        'Telecommunications' => [
            'name' => 'Telecommunications',
            'description' => 'Focuses on data sovereignty, operational resilience, and regulatory compliance for critical communications infrastructure (NIS2)',
            'icon' => 'fa-tower-cell',
            'weights' => [
                'Data Sovereignty' => 2.0,           // Critical: Subscriber data
                'Technical Sovereignty' => 1.5,      // Important: Network independence
                'Operational Sovereignty' => 2.0,    // Critical: Service availability
                'Assurance Sovereignty' => 2.0,      // Critical: NIS2 compliance
                'Open Source' => 1.0,                // Standard
                'Executive Oversight' => 1.5,        // Important
                'Managed Services' => 1.5            // Important
            ]
        ],
        'Other' => [
            'name' => 'Other',
            'description' => 'Balanced approach suitable for general organizations without specific regulatory constraints',
            'icon' => 'fa-building',
            'weights' => [
                'Data Sovereignty' => 1.0,
                'Technical Sovereignty' => 1.0,
                'Operational Sovereignty' => 1.0,
                'Assurance Sovereignty' => 1.0,
                'Open Source' => 1.0,
                'Executive Oversight' => 1.0,
                'Managed Services' => 1.0
            ]
        ]
    ]
];
