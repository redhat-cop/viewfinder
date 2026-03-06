# Digital Sovereignty Maturity Assessment - Facilitator Resources

## Overview

This document provides an overview of the comprehensive Facilitator Guide and supporting materials created for conducting Digital Sovereignty maturity assessments with customers and partners.

## What's Been Created

### 1. Main Facilitator Guide (`facilitator-guide.php`)

A comprehensive, interactive web-based guide covering all aspects of conducting maturity assessments.

**Key Sections:**
- **Introduction:** Overview of the assessment, maturity model, and assessment profiles
- **Pre-Assessment Preparation:** Scheduling, participant selection, industry (LOB) selection, technical setup
- **Facilitation Methodology:** Workshop structure, opening scripts, question-by-question guidance, handling difficult conversations
- **Domain Deep-Dives:** Detailed guidance for all 7 Digital Sovereignty domains with question guides, evidence examples, and red flags
- **Post-Assessment Activities:** Results interpretation, gap analysis, roadmap development, next steps
- **Facilitator Tips & Best Practices:** Do's and don'ts, remote facilitation, challenging personalities
- **Appendix:** Glossary, reference materials, email templates, maturity indicators

**Features:**
- Collapsible sections for easy navigation
- Interactive table of contents with smooth scrolling
- Print-friendly format
- Comprehensive coverage of Data Sovereignty domain with all 8 questions detailed
- Sample scripts and conversation frameworks
- Industry-specific LOB guidance (Finance, Healthcare, Government, Manufacturing, Telecommunications)

**Access:** [https://your-domain/facilitator-guide.php](facilitator-guide.php)

### 2. Landing Page Integration (`maturity-assessment-landing.php`)

Added "Facilitator Guide" button to the assessment landing page header for easy access.

**Location:** Header navigation bar between "Home" and "Import Results"

### 3. Supporting Templates (in `/templates/` directory)

#### 3.1 Full-Day Workshop Agenda (`workshop-agenda-full-day.html`)
- Comprehensive one-day workshop format (6-7 hours)
- Based on the example agenda provided
- Includes:
  - Detailed session timings
  - Participant lists (customer and Red Hat team)
  - Complete agenda breakdown:
    - Welcome & objectives (30 min)
    - Digital Sovereignty context (60 min)
    - 5-Level maturity model framework (30 min)
    - Domain assessment Part 1 - 3 domains (75 min)
    - Lunch break (60 min)
    - Domain assessment Part 2 - 4 domains (90 min)
    - Results review & analysis (60 min)
    - Next steps (15 min)
  - Post-workshop Value-Based Action Plan session (90 min)
  - Pre-workshop preparation checklist
  - Expected outcomes

**Access:** [templates/workshop-agenda-full-day.html](templates/workshop-agenda-full-day.html)

#### 3.2 Short Assessment Agenda (`workshop-agenda-short.html`)
- Focused 2-hour assessment format
- Assessment-only with brief introduction
- Time management tips for rapid evaluation
- Includes:
  - Introduction & framework overview (15 min)
  - All 7 domains assessment (90 min)
  - Quick results review (15 min)
  - Follow-up recommendations

**Access:** [templates/workshop-agenda-short.html](templates/workshop-agenda-short.html)

#### 3.3 Email Templates (`email-templates.html`)
Complete set of 5 customizable email templates:
1. **Initial Workshop Invitation** - Inviting customers to participate
2. **Pre-Assessment Preparation Email** - Logistics and preparation materials
3. **Post-Assessment Summary & Next Steps** - Results summary with attachments
4. **Roadmap Planning Session Invitation** - Follow-up workshop invitation
5. **Quarterly Progress Check-in** - Ongoing engagement template

Each template includes:
- Subject lines
- Professional formatting
- Highlighted placeholders for customization
- Recommended attachments lists
- Best practice language

**Access:** [templates/email-templates.html](templates/email-templates.html)

#### 3.4 Executive Summary Template (`executive-summary-template.html`)
- One-page executive summary for C-suite/Board presentation
- Professional formatting with Red Hat branding
- Includes:
  - Assessment overview with key metrics
  - Score cards (overall, strongest domain, priority gap)
  - Domain-by-domain results table
  - Key strengths and critical gaps
  - Priority recommendations (immediate, tactical, strategic)
  - Industry context and regulatory drivers
  - Next steps
  - How Red Hat can help section
- Print-optimized layout

**Access:** [templates/executive-summary-template.html](templates/executive-summary-template.html)

#### 3.5 Templates Library Index (`templates/index.html`)
- Centralized hub for all facilitator templates
- Visual template cards with descriptions
- Direct view and download links
- Usage instructions
- Template metadata (duration, format, page count)

**Access:** [templates/index.html](templates/index.html)

## File Structure

```
/var/www/html/viewfinder-redhat/
├── facilitator-guide.php                      # Main facilitator guide
├── maturity-assessment-landing.php            # Updated with guide link
├── FACILITATOR-GUIDE-README.md                # This file
└── templates/
    ├── index.html                              # Templates library hub
    ├── workshop-agenda-full-day.html           # Full-day workshop format
    ├── workshop-agenda-short.html              # Short 2-hour format
    ├── email-templates.html                    # 5 email templates
    └── executive-summary-template.html         # Executive summary report
```

## How to Use

### For Facilitators:

1. **Read the Facilitator Guide First**
   - Access at [facilitator-guide.php](facilitator-guide.php)
   - Review all sections before your first assessment
   - Pay special attention to the Domain Deep-Dives section

2. **Select Appropriate Workshop Format**
   - Full-day for comprehensive education and assessment
   - Short format for focused assessment only
   - Download relevant agenda from [templates/index.html](templates/index.html)

3. **Customize Templates**
   - All templates have highlighted `[placeholders]`
   - Replace with customer-specific information
   - Save or print for distribution

4. **Use Email Templates**
   - Copy relevant email template
   - Customize placeholders with customer details
   - Send at appropriate stages (invitation, preparation, follow-up, etc.)

5. **Generate Executive Summary**
   - After assessment, populate executive summary template
   - Include actual scores and findings
   - Use for C-suite briefings

### Navigation:

- **Main Guide:** Click "Facilitator Guide" button on assessment landing page
- **Templates:** Click "Downloadable Templates" in guide's table of contents or appendix
- **Direct Access:** Navigate to `templates/index.html` for template library

## Key Features

### Interactive Elements:
- Collapsible sections for detailed information
- Smooth scrolling navigation
- Print-friendly formatting
- Responsive design for mobile/tablet viewing

### Comprehensive Coverage:
- Complete methodology for 2-hour and full-day formats
- Detailed question-by-question guidance for Data Sovereignty domain
- Industry-specific LOB recommendations
- Evidence-based assessment approach
- Handling challenging scenarios and personalities

### Professional Templates:
- Ready-to-use workshop agendas
- Complete email communication templates
- Executive-ready summary format
- All customizable with placeholders

## Workshop Formats Comparison

| Aspect | Full-Day Workshop | Short Assessment |
|--------|------------------|------------------|
| **Duration** | 6-7 hours | 2 hours |
| **Best For** | First-time assessments, comprehensive education | Follow-up assessments, quick baseline |
| **Education** | Extensive sovereignty context and framework | Brief overview only |
| **Assessment** | Thorough domain coverage with discussion | Rapid evaluation |
| **Results Review** | Detailed analysis with gap prioritization | Quick highlights only |
| **Follow-up** | Value-based action plan session included | Detailed review required separately |
| **Participant Engagement** | High - collaborative workshops | Moderate - efficient assessment |

## LOB (Industry) Profiles

The guide covers detailed selection criteria for:
- **Finance** - Emphasizes Data Sovereignty, Assurance, Operational resilience
- **Healthcare** - Focuses on Data Sovereignty, Operational continuity
- **Government** - Prioritizes Data, Assurance, and Executive Oversight
- **Manufacturing** - Emphasizes Operational Sovereignty and Managed Services
- **Telecommunications** - Focuses on Data, Operational, and Assurance Sovereignty
- **Balanced/Other** - Equal weighting across all domains

## Integration with Assessment Tool

The facilitator resources integrate seamlessly with the Viewfinder assessment tool:
- References to domain structure match assessment UI
- LOB selection guidance aligns with weighting system
- Question numbering corresponds to tool
- Export/import results workflow included

## Customization

All materials can be customized for:
- Partner co-branding
- Regional compliance variations (EU, US, APAC)
- Industry-specific examples
- Customer-specific contexts

## Future Enhancements (Potential)

- Video walkthrough tutorials
- Sample completed assessments (anonymized)
- Domain-specific deep-dive presentations
- Customer success stories and case studies
- Roadmap planning tools and calculators

## Support

For questions about using these materials:
1. Review the comprehensive Facilitator Guide
2. Check the specific template usage instructions
3. Contact the Digital Sovereignty practice lead
4. Reference the Viewfinder tool documentation

## Version History

- **v1.0** (March 2026) - Initial comprehensive release
  - Main facilitator guide with all 7 domains
  - Full-day and short workshop agendas
  - Complete email template set
  - Executive summary template
  - Templates library hub

---

## Quick Links

- **Facilitator Guide:** [facilitator-guide.php](facilitator-guide.php)
- **Templates Library:** [templates/index.html](templates/index.html)
- **Assessment Landing:** [maturity-assessment-landing.php](maturity-assessment-landing.php)
- **Start Assessment:** [index.php](index.php)

---

*These materials support the delivery of Digital Sovereignty Maturity Assessments using the Viewfinder assessment tool.*
