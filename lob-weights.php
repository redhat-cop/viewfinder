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
                'Executive Oversight' => 1.0
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
                'Executive Oversight' => 1.0
            ]
        ],
        'Finance' => [
            'name' => 'Finance',
            'description' => 'Emphasizes data protection, audit controls, and compliance for banking and finance (DORA, PCI DSS)',
            'icon' => 'fa-building-columns',
            'weights' => [
                'Data Sovereignty' => 2.0,           // Critical: Data residency
                'Technical Sovereignty' => 1.0,      // Standard (includes Open Source sub-pillar)
                'Operational Sovereignty' => 1.5,    // Important: Business continuity (includes Managed Services sub-pillar)
                'Assurance Sovereignty' => 2.0,      // Critical: Audit requirements
                'Executive Oversight' => 1.5         // Important: Governance
            ]
        ],
        'Healthcare' => [
            'name' => 'Healthcare',
            'description' => 'Focuses on patient data sovereignty and operational resilience for healthcare systems (HIPAA, GDPR)',
            'icon' => 'fa-heart-pulse',
            'weights' => [
                'Data Sovereignty' => 2.0,           // Critical: Patient data
                'Technical Sovereignty' => 1.0,      // Standard (includes Open Source sub-pillar)
                'Operational Sovereignty' => 2.0,    // Critical: 24/7 operations (includes Managed Services sub-pillar)
                'Assurance Sovereignty' => 1.5,      // Important: Compliance
                'Executive Oversight' => 1.5         // Important
            ]
        ],
        'Government' => [
            'name' => 'Government',
            'description' => 'Comprehensive sovereignty for public sector handling sensitive citizen data and national infrastructure (NIS2, FedRAMP)',
            'icon' => 'fa-landmark',
            'weights' => [
                'Data Sovereignty' => 2.0,           // Critical: Citizen data
                'Technical Sovereignty' => 1.5,      // Important: Independence (includes Open Source sub-pillar for transparency)
                'Operational Sovereignty' => 1.5,    // Important: Continuity (includes Managed Services sub-pillar for control)
                'Assurance Sovereignty' => 2.0,      // Critical: National security
                'Executive Oversight' => 2.0         // Critical: Accountability
            ]
        ],
        'Manufacturing' => [
            'name' => 'Manufacturing',
            'description' => 'Emphasizes operational resilience and managed services for industrial operations and IP protection',
            'icon' => 'fa-industry',
            'weights' => [
                'Data Sovereignty' => 1.5,           // Important: IP protection
                'Technical Sovereignty' => 1.0,      // Standard (includes Open Source sub-pillar)
                'Operational Sovereignty' => 2.0,    // Critical: Production uptime, OT/IT integration (includes Managed Services sub-pillar)
                'Assurance Sovereignty' => 1.5,      // Important: Quality
                'Executive Oversight' => 1.5         // Important
            ]
        ],
        'Telecommunications' => [
            'name' => 'Telecommunications',
            'description' => 'Focuses on data sovereignty, operational resilience, and regulatory compliance for critical communications infrastructure (NIS2)',
            'icon' => 'fa-tower-cell',
            'weights' => [
                'Data Sovereignty' => 2.0,           // Critical: Subscriber data
                'Technical Sovereignty' => 1.5,      // Important: Network independence (includes Open Source sub-pillar)
                'Operational Sovereignty' => 2.0,    // Critical: Service availability (includes Managed Services sub-pillar)
                'Assurance Sovereignty' => 2.0,      // Critical: NIS2 compliance
                'Executive Oversight' => 1.5         // Important
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
                'Executive Oversight' => 1.0
            ]
        ]
    ],

    // ============================================
    // AI SOVEREIGNTY PROFILE WEIGHTS
    // ============================================
    'AISovereignty' => [
        'Balanced' => [
            'name' => 'Balanced',
            'description' => 'Equal weighting across all AI sovereignty domains',
            'icon' => 'fa-balance-scale',
            'weights' => [
                'AI Data Sovereignty' => 1.0,
                'AI Model Sovereignty' => 1.0,
                'AI Infrastructure Sovereignty' => 1.0,
                'AI Supply Chain Sovereignty' => 1.0,
                'AI Governance & Compliance' => 1.0,
                'AI Operations Sovereignty' => 1.0,
                'AI Innovation Sovereignty' => 1.0
            ]
        ],
        'General' => [
            'name' => 'General',
            'description' => 'Balanced approach suitable for general AI initiatives without specific industry focus',
            'icon' => 'fa-building',
            'weights' => [
                'AI Data Sovereignty' => 1.0,
                'AI Model Sovereignty' => 1.0,
                'AI Infrastructure Sovereignty' => 1.0,
                'AI Supply Chain Sovereignty' => 1.0,
                'AI Governance & Compliance' => 1.0,
                'AI Operations Sovereignty' => 1.0,
                'AI Innovation Sovereignty' => 1.0
            ]
        ],
        'Finance' => [
            'name' => 'Finance',
            'description' => 'Emphasizes AI governance, model transparency, and data protection for financial regulatory compliance',
            'icon' => 'fa-building-columns',
            'weights' => [
                'AI Data Sovereignty' => 2.0,            // Critical: Financial data privacy
                'AI Model Sovereignty' => 2.0,           // Critical: Explainability for regulators
                'AI Infrastructure Sovereignty' => 1.5,  // Important: Secure processing
                'AI Supply Chain Sovereignty' => 1.5,    // Important: Vendor risk
                'AI Governance & Compliance' => 2.0,     // Critical: Financial regulations
                'AI Operations Sovereignty' => 1.5,      // Important: Model monitoring
                'AI Innovation Sovereignty' => 1.0       // Standard
            ]
        ],
        'Healthcare' => [
            'name' => 'Healthcare',
            'description' => 'Focuses on patient data sovereignty, AI safety, and clinical governance for healthcare AI systems',
            'icon' => 'fa-heart-pulse',
            'weights' => [
                'AI Data Sovereignty' => 2.0,            // Critical: HIPAA, patient privacy
                'AI Model Sovereignty' => 2.0,           // Critical: Clinical explainability
                'AI Infrastructure Sovereignty' => 1.5,  // Important: Healthcare systems
                'AI Supply Chain Sovereignty' => 1.5,    // Important: Medical AI safety
                'AI Governance & Compliance' => 2.0,     // Critical: FDA, clinical governance
                'AI Operations Sovereignty' => 2.0,      // Critical: Patient safety monitoring
                'AI Innovation Sovereignty' => 1.5       // Important: Medical research
            ]
        ],
        'Government' => [
            'name' => 'Government',
            'description' => 'Comprehensive AI sovereignty for public sector including data residency, transparency, and accountability',
            'icon' => 'fa-landmark',
            'weights' => [
                'AI Data Sovereignty' => 2.0,            // Critical: Citizen data
                'AI Model Sovereignty' => 2.0,           // Critical: Public accountability
                'AI Infrastructure Sovereignty' => 2.0,  // Critical: National infrastructure
                'AI Supply Chain Sovereignty' => 2.0,    // Critical: National security
                'AI Governance & Compliance' => 2.0,     // Critical: EU AI Act, transparency
                'AI Operations Sovereignty' => 1.5,      // Important: Service delivery
                'AI Innovation Sovereignty' => 1.5       // Important: Public sector innovation
            ]
        ],
        'Manufacturing' => [
            'name' => 'Manufacturing',
            'description' => 'Emphasizes AI operations, edge deployment, and IP protection for industrial AI applications',
            'icon' => 'fa-industry',
            'weights' => [
                'AI Data Sovereignty' => 1.5,            // Important: Industrial IP
                'AI Model Sovereignty' => 1.5,           // Important: Proprietary models
                'AI Infrastructure Sovereignty' => 2.0,  // Critical: Edge AI, OT integration
                'AI Supply Chain Sovereignty' => 1.5,    // Important: Supply chain
                'AI Governance & Compliance' => 1.0,     // Standard
                'AI Operations Sovereignty' => 2.0,      // Critical: Production uptime
                'AI Innovation Sovereignty' => 1.5       // Important: Competitive advantage
            ]
        ],
        'Telecommunications' => [
            'name' => 'Telecommunications',
            'description' => 'Focuses on AI infrastructure, data sovereignty, and operational resilience for telecom AI systems',
            'icon' => 'fa-tower-cell',
            'weights' => [
                'AI Data Sovereignty' => 2.0,            // Critical: Subscriber data
                'AI Model Sovereignty' => 1.5,           // Important: Network optimization
                'AI Infrastructure Sovereignty' => 2.0,  // Critical: Network AI infrastructure
                'AI Supply Chain Sovereignty' => 1.5,    // Important: Vendor management
                'AI Governance & Compliance' => 2.0,     // Critical: NIS2, telecom regulations
                'AI Operations Sovereignty' => 2.0,      // Critical: Network reliability
                'AI Innovation Sovereignty' => 1.0       // Standard
            ]
        ],
        'Other' => [
            'name' => 'Other',
            'description' => 'Balanced approach suitable for general AI initiatives without specific regulatory constraints',
            'icon' => 'fa-building',
            'weights' => [
                'AI Data Sovereignty' => 1.0,
                'AI Model Sovereignty' => 1.0,
                'AI Infrastructure Sovereignty' => 1.0,
                'AI Supply Chain Sovereignty' => 1.0,
                'AI Governance & Compliance' => 1.0,
                'AI Operations Sovereignty' => 1.0,
                'AI Innovation Sovereignty' => 1.0
            ]
        ]
    ]
];
