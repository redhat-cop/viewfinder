# Viewfinder - Maturity Assessment Tool

A comprehensive web-based assessment platform for evaluating organizational maturity across multiple domains including Security, Digital Sovereignty, AI Readiness, OpenShift, and RHEL capabilities.

## Overview

Viewfinder is a dynamic assessment tool designed to help organizations measure and visualize their maturity levels across various technology and operational domains. The platform provides interactive assessments, real-time scoring, visual analytics, and detailed recommendations for improvement based on industry best practices and regulatory requirements.

## Screenshots

### Main Assessment Interface
![Main Page](./images/main-page.png)

## Key Features

### Assessment Capabilities
- **Multi-Profile Assessments**: Security, Digital Sovereignty, AI Readiness, OpenShift, RHEL, and custom profiles
- **Multi-Domain Evaluation**: 7 control domains per profile
- **Tiered Maturity Levels**: Foundation (0-9), Strategic (10-27), Advanced (28-36)
- **8 Capability Levels**: Progressive maturity scoring from 1-8 points per domain
- **Real-time Scoring**: Dynamic calculation of maturity scores as assessments are completed
- **Framework Mapping**: Map assessments to NIST 800-53, PCI DSS, ISO 27001, FedRAMP, NIS2, DORA, DISA STIG
- **Industry-Specific Guidance**: Tailored recommendations for Finance, Government, Healthcare, Manufacturing, Telecommunications

### Visualization & Reporting
- **Interactive Radar Charts**: D3.js-powered visualizations of maturity across domains
- **Detailed Recommendations**: HTML-formatted guidance for improving maturity levels
- **Compliance Mapping**: Link capabilities to compliance framework controls
- **Maturity Tables**: Tabular view of scores and recommendations
- **Framework-Specific Views**: Detailed compliance guidance per selected framework
- **Industry-Specific Views**: Sector-specific recommendations

### Landing Page Dashboard ✨ NEW
- **Unified Entry Point**: Clean, professional landing page at root URL
- **Card-Based Navigation**: Four main sections with intuitive card design
  - **Full Maturity Assessments**: Dynamically generated buttons for all enabled profiles
  - **DS Sales Qualifier**: Quick access to sales qualification tool
  - **Operation Sovereign Shield**: Digital Sovereignty Escape Room for executives
  - **Profile Management**: Administrative access
- **Dynamic Profile Discovery**: Automatically displays new profiles when added via Profile Admin
- **Automatic Updates**: Add/remove profiles and landing page updates automatically
- **Responsive Design**: Mobile-friendly card grid layout
- **Sticky Footer**: Footer properly positioned at bottom of page
- **Consistent Theming**: PatternFly dark theme throughout

### Operation Sovereign Shield - Digital Sovereignty Escape Room ✨ NEW
- **Executive Challenge**: Immersive 2-hour experience designed for leadership teams (4-8 executives)
- **Fast-Paced Decision Making**: Time-bound activity testing rapid strategic decisions under pressure
- **Varied Complexity Puzzles**: Multiple challenges covering critical digital sovereignty topics
  - Data residency requirements and compliance
  - Vendor lock-in mitigation strategies
  - Supply chain dependency management
  - Regulatory navigation (GDPR, NIS2, etc.)
  - Open source principles for digital independence
- **Facilitated Experience**: Guided by Red Hat Subject Matter Experts with optional hints
- **Three-Phase Structure**:
  - Pre-briefing and mission objectives
  - 45-minute Executive Challenge (hands-on problem solving)
  - Debrief and action planning session
- **Key Benefits**:
  - Accelerated strategic alignment among leadership
  - Risk mitigation through experiential learning in safe environment
  - Enhanced decision-making skills under pressure
  - Future-proofing organization with resilient digital foundations
  - Demystifies complex sovereignty concepts for executives
  - Team building through collaborative problem-solving
- **Engaging Presentation**: Professional page design with challenge topics, benefits, and timeline
- **Home Navigation**: Easy return to main landing page

### Digital Sovereignty Sales Qualifier ✨ NEW
- **Quick Sales Qualification**: Lightweight 10-15 minute assessment for sales teams
- **Paginated Navigation**: Section-by-section wizard with Next/Previous buttons and keyboard shortcuts
- **21 Key Questions**: 2-3 critical questions per domain (Data, Technical, Operational, Assurance, Open Source, Executive, Managed Services)
- **Answer Validation**: Required field validation - must answer all questions before proceeding to next section
- **Progress Tracking**: Visual section indicator (Section X of 7) with progress bar
- **Contextual Help Tooltips**: Info icons with detailed explanations for each question
  - Regulatory frameworks (GDPR, NIS2, SecNumCloud, etc.)
  - Technical concepts (BYOK, vendor lock-in, etc.)
  - Real-world examples and use cases
- **Opportunity Scoring**: 0-21 point scale with High/Medium/Low priority classification (lower scores = higher opportunity)
- **Visual Score Display**: Circular progress chart showing percentage and points
  - Color-coded by priority (green=high opportunity, yellow=medium, red=low)
  - Animated ring visualization
- **Gap-Based Prioritization**: Identifies customers lacking DS capabilities as prime opportunities
- **Sales-Friendly Results**: Actionable recommendations and next steps
- **Domain Analysis**: Gap analysis showing opportunity level across all DS domains
- **Automatic Recommendations**: Tailored product positioning and sales actions based on score
- **Print & Share**: Print-optimized results for customer conversations
- **Auto-Save Progress**: Automatically saves form progress and current section in browser
- **Keyboard Navigation**: Arrow keys to navigate between sections, Ctrl+S to save
- **Full Assessment Link**: Easy transition to complete Viewfinder DS assessment

### Profile Management System
- **Create Custom Profiles**: Build tailored assessment profiles via intuitive 4-step wizard
- **Edit Existing Profiles**: Modify profile content, domains, and capabilities
- **Profile Administration Dashboard**: Unified hub for managing all profiles
- **Enable/Disable Profiles**: Control which profiles appear in navigation
- **Delete Profiles**: Remove custom profiles with automatic cleanup
- **Protected Profiles**: Core profiles safeguarded from modification/deletion

### Export/Import Functionality ✨ NEW
- **Export Profiles**: Download any profile as JSON for backup or sharing
  - Works with both protected and custom profiles
  - Standard JSON format (`controls-{ProfileName}.json`)
  - One-click download from profile cards
  - Preserves complete profile structure

- **Import Profiles**: Upload and import profile configurations
  - Comprehensive pre-import validation
  - Structure validation (7 domains, 8 capabilities each)
  - Real-time validation feedback
  - Duplicate detection with overwrite option
  - Custom profile naming support
  - Protected profile safeguards
  - Automatic registration in system

- **Profile Validation**: Pre-import validation with detailed feedback
  - JSON format verification
  - Domain/capability structure checks
  - Tier and points validation
  - File size and MIME type checking (5MB limit)
  - Detailed error messages for troubleshooting

### Security & Reliability
- **Input Validation**: Comprehensive sanitization of all user inputs
- **Path Traversal Prevention**: Secure file path handling
- **Protected Profiles**: Core profiles cannot be deleted or overwritten
- **Atomic Operations**: File operations with automatic rollback on failure
- **Backup & Restore**: Automatic backups before all modifications
- **Opcode Cache Management**: Cache invalidation for immediate updates
- **Error Handling**: Custom exception hierarchy with user-friendly messages
- **Comprehensive Logging**: Activity and error logging for troubleshooting

## Quick Start

### Installation

#### Option 1: Container Deployment (Recommended)

```bash
# Clone the repository
git clone https://github.com/redhat-cop/viewfinder.git
cd viewfinder

# Build with Podman (or Docker)
podman build -t viewfinder:latest .

# Run the container
podman run -p 8080:8080 localhost/viewfinder
```

#### Option 2: Use Pre-built Image

```bash
# Pull and run the pre-built image
podman pull quay.io/rhn_gps_cjenkins/viewfinder
podman run -p 8080:8080 quay.io/rhn_gps_cjenkins/viewfinder
```

#### Option 3: Direct Installation

**Prerequisites:**
```bash
# PHP 8.1 or higher
php --version

# Apache or Nginx web server
# Required PHP extensions
php -m | grep -E 'json|fileinfo|mbstring'
```

**Setup Steps:**

1. **Clone and install dependencies**
```bash
git clone https://github.com/redhat-cop/viewfinder.git
cd viewfinder
composer install
```

2. **Set file permissions**
```bash
# Set ownership to web server user (adjust for your system)
sudo chown -R apache:apache .

# Set appropriate permissions
chmod 755 .
chmod 644 *.php *.json
chmod 755 includes/ css/ js/
chmod 644 includes/*.php css/* js/*
```

3. **Configure web server** (see detailed examples in installation section below)

4. **Access the application**
```
http://your-server/
```

### Access the Application

Once running, open your browser and navigate to:
- **Container**: [http://localhost:8080](http://localhost:8080)
- **Direct Installation**: [http://your-server/](http://your-server/)

You'll see the **Landing Page Dashboard** with three main sections:
1. **Full Maturity Assessments** - Click any profile to start a comprehensive assessment
2. **DS Sales Qualifier** - Quick 10-15 minute sales qualification tool
3. **Profile Management** - Configure and manage assessment profiles

## Architecture

### Technology Stack

**Backend:**
- PHP 8.1+ with object-oriented architecture
- JSON-based data storage
- Comprehensive error handling and logging
- Atomic file operations with rollback support

**Frontend:**
- HTML5, CSS3, JavaScript (jQuery 3.6.0)
- PatternFly (Red Hat Design System)
- Bootstrap for responsive grid
- D3.js for data visualizations
- Font Awesome icons

**Container:**
- Red Hat UBI 9 base image
- PHP 8.1 with required extensions
- Apache web server

### Core Components

#### Backend Classes (`includes/`)
- **Config.php**: Centralized configuration and profile registry
- **Security.php**: Input validation and sanitization
- **Logger.php**: Comprehensive logging system
- **ProfileGenerator.php**: Profile creation logic
- **ProfileEditor.php**: Profile editing logic
- **ProfileAdmin.php**: Administrative operations
- **ProfileDeleter.php**: Profile deletion with cleanup
- **ProfileExporter.php**: ✨ Profile export functionality
- **ProfileImporter.php**: ✨ Profile import with validation
- **FileUpdater.php**: Safe file modification utilities
- **MaturityRating.php**: Maturity score calculations
- **Exceptions/**: Custom exception hierarchy

#### Frontend Components
- **profile-admin.php**: Administration dashboard UI
- **profile-creator.php**: Profile creation wizard
- **profile-editor.php**: Profile editing interface
- **profile-deleter.php**: Profile deletion interface
- **index.php**: Main assessment interface
- **results.php**: Results dashboard with visualizations

#### JavaScript Modules (`js/`)
- **profile-admin.js**: ✨ Admin dashboard, export/import logic
- **profile-wizard.js**: Profile creation wizard
- **profile-editor.js**: Profile editing logic
- **profile-deleter.js**: Profile deletion logic
- **radarChart.js**: D3.js radar chart implementation

## Usage Guide

### Navigating the Landing Page

1. **Start from the Landing Page**:
   - Visit the root URL to access the main dashboard
   - Choose from three main options

2. **Select Full Maturity Assessment**:
   - Click any enabled profile button (Security, Digital Sovereignty, AI, etc.)
   - Dynamically updated as new profiles are added

3. **Quick Sales Qualification**:
   - Click "Start Sales Qualifier" for the lightweight DS assessment tool

4. **Profile Management**:
   - Click "Manage Profiles" to configure assessments (admin)

### Conducting an Assessment

1. **Select Assessment Profile**:
   - From the landing page or navigation header
   - Security, Digital Sovereignty, AI Readiness, OpenShift, RHEL, or custom profiles
   - Each profile evaluates 7 distinct domains

2. **Choose Line of Business** (optional):
   - Select your industry for tailored recommendations
   - Available: Finance, Government, Healthcare, Manufacturing, Telecommunications, Other

3. **Complete Domain Assessment**:
   - Navigate through 7 domain tabs
   - For each domain, select your current maturity level (1-8 capabilities)
   - Use info icons for detailed control descriptions
   - Real-time scoring updates as you progress

4. **Select Compliance Frameworks** (optional):
   - Choose relevant regulatory frameworks
   - Options: NIST 800-53, PCI DSS, ISO 27001, FedRAMP, NIS2, DORA, DISA STIG
   - Multiple selections supported

5. **Submit Assessment**:
   - Review your selections
   - Submit for processing

### Interpreting Results

#### Maturity Levels
- **Foundation (0-9 points)**: Basic controls in place, manual processes
- **Strategic (10-27 points)**: Intermediate posture with some automation
- **Advanced (28-36 points)**: Mature program with comprehensive automation

#### Maximum Scores
- Per domain: 36 points (8 capabilities × 1-8 points each)
- Total assessment: 252 points (7 domains × 36 points)

#### Results Tabs
- **Radar Chart**: Visual representation of maturity across all 7 domains
- **Recommendations**: Specific improvement suggestions based on identified gaps
- **Maturity Table**: Detailed scoring breakdown by domain and capability
- **Compliance Frameworks**: Framework-specific guidance (if frameworks selected)
- **Industry Specifics**: Line-of-business recommendations (if LOB selected)

### Using the Digital Sovereignty Sales Qualifier

The DS Sales Qualifier is a lightweight tool designed for sales teams to quickly identify Digital Sovereignty opportunities. Access it via the **DS Sales Qualifier** button in the main navigation.

#### Quick Start
1. **Answer Domain Questions**:
   - Navigate through 7 DS domains using Next/Previous buttons
   - Review 2-3 yes/no questions per domain (21 questions total)
   - Check boxes for any capabilities the customer currently has or requires
   - Track your progress with the live score counter and section indicator
   - Form auto-saves to browser storage (including your current section)
   - Use keyboard arrow keys for quick navigation

2. **Submit & Review Results**:
   - Click "Generate Qualification Report" on the final section
   - Review opportunity priority (High/Medium/Low)
   - Examine domain-by-domain breakdown
   - Follow recommended next steps

#### Understanding DS Qualifier Results

**Priority Levels (Inverted Scoring):**

*Note: Lower scores indicate HIGHER sales opportunity because the customer lacks DS capabilities and needs Red Hat solutions.*

- **High Priority (0-7 points)**: Strong DS opportunity - Customer has significant gaps
  - Engage DS specialists immediately
  - Position OpenShift, RHEL sovereign solutions
  - Schedule technical deep-dive
  - Discuss compliance frameworks (GDPR, NIS2, etc.)
  - Excellent opportunity to provide value across multiple domains

- **Medium Priority (8-14 points)**: Moderate DS opportunity - Customer has some capabilities
  - Conduct full Viewfinder assessment
  - Schedule discovery call on data residency
  - Share DS resources and whitepapers
  - Validate budget and timeline
  - Good opportunity to strengthen their sovereignty posture

- **Low Priority (15-21 points)**: Limited DS opportunity - Customer already compliant
  - Customer already has strong DS capabilities
  - Limited opportunity for new DS solutions
  - Consider maintenance, upgrades, or other Red Hat value propositions
  - Monitor for future expansion opportunities

**Results Include:**
- **Score Card**: Visual priority indicator with total score
- **Domain Breakdown**: Gap analysis and opportunity level across 7 domains (Data, Technical, Operational, Assurance, Open Source, Executive, Managed Services)
- **Sales Actions**: Recommended next steps based on priority level
- **Domain Insights**: Detailed view of identified gaps and opportunities
- **Printable Report**: Print-optimized format for customer meetings

**Next Steps:**
- **Print Results**: Use print button for customer conversations
- **New Assessment**: Start another qualification
- **Full Assessment**: Run complete Viewfinder DS assessment for technical details

### Managing Profiles

#### Access Profile Administration
Navigate to **Manage Profiles** button in the header to access the unified administration dashboard.

#### Create New Profile
1. Click **Create New Profile** button
2. Complete the 4-step wizard:
   - **Step 1 - Metadata**: Profile name, display name, enabled status
   - **Step 2 - Domains**: Define 7 control domains with titles and descriptions
   - **Step 3 - Capabilities**: Configure 8 capabilities per domain (names, tiers, recommendations)
   - **Step 4 - Review**: Preview JSON structure and confirm
3. Click **Generate Profile** to create
4. Profile automatically registered in Config.php
5. Profile appears in navigation (if enabled)

#### Edit Existing Profile
1. Find the profile card in the administration dashboard
2. Click **Edit** button (blue pencil icon)
3. Modify domain titles, descriptions, or capability details
4. Save changes
5. Changes reflected immediately

#### Export Profile ✨
1. Find the profile card in the administration dashboard
2. Click **Export** button (green download icon)
3. Profile downloads as `controls-{ProfileName}.json`
4. Save to your desired location
5. Works for both protected and custom profiles

#### Import Profile ✨
1. Click **Import Profile** button in the administration dashboard
2. Select a JSON file from your computer
3. **Optional**: Click **Validate** to check file before importing
   - Reviews JSON structure
   - Checks domain/capability completeness
   - Identifies conflicts with existing profiles
   - Shows detailed validation results
4. Configure import options:
   - **Custom Profile Name**: Override the filename-derived name (optional)
   - **Display Name**: Set friendly name for UI (optional)
   - **Overwrite**: Allow replacing existing profile (required if profile exists)
   - **Enabled**: Enable profile after import (checked by default)
5. Review validation results:
   - ✓ Green = Valid, ready to import
   - ✗ Red = Invalid, shows error details
6. Click **Import** to complete
7. Profile registered in Config.php automatically
8. Page refreshes to show new profile

#### Enable/Disable Profile
1. Find the profile card in the administration dashboard
2. Toggle the **Enabled** switch
3. Profile immediately appears/disappears from navigation
4. Change saved to Config.php automatically

#### Delete Profile
1. Find the profile card in the administration dashboard
2. Click **Delete** button (red trash icon)
3. Review deletion details in confirmation modal
4. Click **Delete** to confirm
5. Profile removed from:
   - Config.php (profile registry)
   - File system (controls-{ProfileName}.json)
6. Changes take effect immediately

### Protected Profiles

The following profiles are **protected** from deletion and overwrite:
- Template
- Security
- DigitalSovereignty
- AI
- OpenShift
- RHEL

**Protected profiles can be:**
- ✓ Viewed (read-only mode in editor)
- ✓ Exported (downloaded as JSON)
- ✓ Enabled/Disabled (toggle navigation visibility)

**Protected profiles CANNOT be:**
- ✗ Edited (modifications blocked)
- ✗ Deleted (deletion blocked)
- ✗ Overwritten via import (import blocked)

## Profile JSON Format

Profiles follow a standardized JSON structure with 7 domains and 8 capabilities each:

```json
{
  "Domain-1": {
    "title": "Secure Infrastructure",
    "overview": "Establishment of robust and resilient systems...",
    "qnum": "1",
    "1": "Config Management",
    "1-summary": "Configuration management is the systematic process...",
    "1-tier": "Foundation",
    "1-points": "1",
    "1-recommendation": "<h2>Recommendations for Improving...</h2>...",
    "2": "Segmentation / Isolation",
    "2-summary": "Infrastructure segmentation is the practice...",
    "2-tier": "Foundation",
    "2-points": "2",
    "2-recommendation": "<h2>Recommendations for Improving...</h2>...",
    // ... capabilities 3-8
  },
  "Domain-2": { /* ... */ },
  "Domain-3": { /* ... */ },
  "Domain-4": { /* ... */ },
  "Domain-5": { /* ... */ },
  "Domain-6": { /* ... */ },
  "Domain-7": { /* ... */ }
}
```

### Required Fields per Domain:
- `title`: Domain name (string)
- `overview`: Domain description (string)
- `qnum`: Domain number as string (1-7)

### Required Fields per Capability (1-8):
- `{n}`: Capability name (string)
- `{n}-summary`: Capability description (string)
- `{n}-tier`: Maturity tier (must be: "Foundation", "Strategic", or "Advanced")
- `{n}-points`: Points value as string (must match capability number: "1" through "8")
- `{n}-recommendation`: HTML-formatted recommendations (string, can contain HTML tags)

### Validation Rules:
- Must have exactly 7 domains (Domain-1 through Domain-7)
- Each domain must have exactly 8 capabilities (1 through 8)
- Tier must be one of: Foundation, Strategic, Advanced
- Points must match capability number (capability 1 = "1" points, capability 2 = "2" points, etc.)
- qnum must match domain number

## File Structure

```
viewfinder/
├── index.php                          # Landing page dashboard & assessment interface (dual mode)
├── results.php                        # Results dashboard with visualizations
├── profile-creator.php                # Profile creation wizard UI
├── profile-creator-handler.php        # AJAX handler for profile creation
├── profile-editor.php                 # Profile editing interface
├── profile-editor-handler.php         # AJAX handler for profile editing
├── profile-admin.php                  # ✨ Profile administration dashboard
├── profile-admin-handler.php          # ✨ AJAX handler for admin operations
├── profile-deleter.php                # Profile deletion interface
├── profile-deleter-handler.php        # AJAX handler for deletion
├── clear-cache.php                    # Opcode cache clearing utility
│
├── ds-qualifier/                      # ✨ Digital Sovereignty Sales Qualifier
│   ├── index.php                      # Qualification questionnaire interface
│   ├── results.php                    # Sales-friendly results page
│   ├── config.php                     # Questions configuration
│   ├── css/
│   │   └── ds-qualifier.css           # Tool-specific styling
│   └── js/
│       └── ds-qualifier.js            # Interactive features & auto-save
│
├── includes/
│   ├── Config.php                     # Application configuration & constants
│   ├── Security.php                   # Input validation & sanitization
│   ├── Logger.php                     # Logging system
│   ├── ProfileGenerator.php           # Profile creation logic
│   ├── ProfileEditor.php              # Profile editing logic
│   ├── ProfileAdmin.php               # Admin operations
│   ├── ProfileDeleter.php             # Profile deletion logic
│   ├── ProfileExporter.php            # ✨ Profile export functionality
│   ├── ProfileImporter.php            # ✨ Profile import functionality
│   ├── FileUpdater.php                # Safe file modification utilities
│   ├── MaturityRating.php             # Rating calculations
│   └── Exceptions/                    # Custom exceptions
│       ├── ProfileException.php
│       ├── ViewfinderException.php
│       └── ...
│
├── css/
│   ├── style.css                      # Main application styles
│   ├── patternfly.css                 # PatternFly design system
│   ├── profile-wizard.css             # Profile wizard styling
│   ├── profile-admin.css              # ✨ Admin dashboard styling
│   ├── results-dark.css               # Results page dark theme
│   └── ...
│
├── js/
│   ├── radarChart.js                  # D3.js radar chart implementation
│   ├── profile-wizard.js              # Profile creation wizard logic
│   ├── profile-editor.js              # Profile editing logic
│   ├── profile-admin.js               # ✨ Admin dashboard & import/export
│   ├── profile-deleter.js             # Profile deletion logic
│   └── ...
│
├── controls-*.json                    # Profile data files
│   ├── controls-Security.json         # Security profile
│   ├── controls-DigitalSovereignty.json
│   ├── controls-AI.json
│   ├── controls-OpenShift.json
│   ├── controls-RHEL.json
│   └── controls-Template.json         # Template for new profiles
│
├── compliance.json                    # Compliance framework mappings
├── lob.json                          # Line of business data
├── compliance/                        # Framework-specific HTML files
├── lob/                              # Line of business HTML files
├── images/                           # Screenshots and assets
├── vendor/                           # Composer dependencies
├── Dockerfile                        # Container build configuration
└── README.md                         # This file
```

## API Endpoints (AJAX Handlers)

### Profile Administration (`profile-admin-handler.php`)
- `POST list_all_profiles` - Retrieve all profiles with metadata and statistics
- `POST toggle_enabled` - Enable/disable a profile in navigation
- `POST delete_profile` - Delete a custom profile (protected profiles blocked)
- `POST export_profile` - ✨ Download profile as JSON file
- `POST validate_import` - ✨ Validate uploaded profile file before import
- `POST import_profile` - ✨ Import and register profile from JSON file

### Profile Creation (`profile-creator-handler.php`)
- `POST save_step` - Save wizard step data to session
- `POST get_preview` - Generate JSON preview for review
- `POST create_profile` - Finalize profile creation and register

### Profile Editing (`profile-editor-handler.php`)
- `POST load_profile` - Load profile data for editing
- `POST save_domain` - Save domain modifications
- `POST get_preview` - Generate JSON preview
- `POST save_profile` - Finalize profile changes

### Profile Deletion (`profile-deleter-handler.php`)
- `POST delete_profile` - Delete custom profile with cleanup

## Configuration

### Application Settings (`includes/Config.php`)

```php
class Config {
    // Application version
    const APP_VERSION = '2.0.0';

    // Base path
    public static function getBasePath(): string

    // Profile registry
    const PROFILES = [
        'Security' => [
            'name' => 'Security',
            'display_name' => 'Security',
            'enabled' => true,
            'protected' => true
        ],
        // ... additional profiles dynamically registered via import
    ];

    // Get enabled profiles for navigation
    public static function getEnabledProfiles(): array

    // Check if profile exists and is valid
    public static function isValidProfile(string $name): bool
}
```

### Web Server Configuration

**Apache (VirtualHost):**
```apache
<VirtualHost *:80>
    ServerName viewfinder.example.com
    DocumentRoot /var/www/html/viewfinder

    <Directory /var/www/html/viewfinder>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/viewfinder-error.log
    CustomLog ${APACHE_LOG_DIR}/viewfinder-access.log combined
</VirtualHost>
```

**Nginx:**
```nginx
server {
    listen 80;
    server_name viewfinder.example.com;
    root /var/www/html/viewfinder;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php-fpm/php-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

## Troubleshooting

### Common Issues

**Issue**: Import fails with "System restore failed"
**Solution**: Check file permissions on critical files:
```bash
sudo chown apache:apache index.php includes/Config.php
sudo chmod 644 index.php includes/Config.php
```

**Issue**: Profile doesn't appear after import
**Solution**:
1. Verify profile is enabled in Config.php
2. Clear opcode cache: `curl http://your-server/clear-cache.php`
3. Check that profile JSON file exists: `ls -la controls-{ProfileName}.json`
4. Check web server error logs

**Issue**: Export downloads empty file
**Solution**:
1. Verify profile JSON file exists and is readable:
```bash
ls -la controls-{ProfileName}.json
cat controls-{ProfileName}.json
```
2. Check web server error logs for PHP errors

**Issue**: Validation fails on import
**Solution**: Ensure JSON file has:
- Exactly 7 domains (Domain-1 through Domain-7)
- Exactly 8 capabilities per domain (1 through 8)
- All required fields for each domain and capability
- Valid tier values (Foundation, Strategic, Advanced)
- Points matching capability numbers ("1" for capability 1, etc.)

**Issue**: "Failed to update Config.php" error
**Solution**:
```bash
# Check Config.php is writable
sudo chown apache:apache includes/Config.php
sudo chmod 644 includes/Config.php

# Verify Config.php structure is valid
php -l includes/Config.php
```

**Issue**: Permission denied errors
**Solution**:
```bash
# Set correct ownership recursively
sudo chown -R apache:apache /var/www/html/viewfinder

# Set correct permissions
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;
```

### Logging

Application logs provide detailed troubleshooting information:

```bash
# View recent activity logs
tail -f /var/log/viewfinder/app.log

# Search for errors
grep ERROR /var/log/viewfinder/app.log

# View PHP errors
tail -f /var/log/httpd/error_log  # Apache
tail -f /var/log/nginx/error.log  # Nginx
```

## Development

### Adding a New Profile Manually

1. **Create JSON file**: `controls-NewProfile.json` following the standard format

2. **Add to Config.php**:
```php
'NewProfile' => [
    'name' => 'NewProfile',
    'display_name' => 'New Profile Display Name',
    'enabled' => true,
    'protected' => false  // true to prevent deletion/editing
]
```

3. **Clear opcode cache**:
```bash
curl http://your-server/clear-cache.php
```

4. Profile appears in navigation automatically (if enabled)

### Extending the System

**Add new validation rules:**
Edit `includes/ProfileImporter.php` → `validateProfileStructure()` method

**Add new export formats (e.g., CSV, XML):**
Extend `includes/ProfileExporter.php` with new export methods

**Customize UI styling:**
- Modify `css/profile-admin.css` for admin dashboard styling
- Update `css/style.css` for main application styling
- Edit `css/results-dark.css` for results page styling

**Add new JavaScript functionality:**
- Update `js/profile-admin.js` for admin dashboard behavior
- Modify `js/radarChart.js` for visualization changes

**Add new compliance frameworks:**
- Add framework to `compliance.json`
- Create framework HTML file in `compliance/` directory
- Update UI to include new framework option

## Version History

### Version 2.5.0 (January 2026)
- ✨ **NEW**: Operation Sovereign Shield - Digital Sovereignty Escape Room
- ✨ **NEW**: Immersive executive challenge page with comprehensive information
- ✨ **NEW**: Escape room card added to landing page dashboard
- ✨ **NEW**: Warning button style (orange/yellow gradient) for escape room
- 🔧 **IMPROVED**: Landing page now features four main sections
- 📖 **DOCS**: Updated README with escape room details and benefits

### Version 2.4.1 (January 2026)
- ✨ **NEW**: Landing page dashboard with card-based navigation
- ✨ **NEW**: Dynamic profile discovery on landing page
- ✨ **NEW**: Contextual help tooltips for all DS Qualifier questions
- ✨ **NEW**: Answer validation (required fields) for DS Qualifier
- ✨ **NEW**: Circular progress chart visualization for DS Qualifier results
- ✨ **NEW**: Home button navigation from all tools
- 🔧 **IMPROVED**: Button spacing in DS Qualifier navigation
- 🔧 **IMPROVED**: Footer positioning on landing page (sticky footer)
- 🔧 **IMPROVED**: Tooltip content refined (removed sales-oriented language)

### Version 2.1.0 (January 2026)
- ✨ **NEW**: Digital Sovereignty Sales Qualifier tool for quick opportunity assessment
- ✨ **NEW**: Paginated section navigation with Next/Previous buttons (wizard-style UX)
- ✨ **NEW**: Visual progress tracking (Section X of 7 with progress bar)
- ✨ **NEW**: 21-question lightweight qualification for sales teams
- ✨ **NEW**: Gap-based inverted scoring (low score = high opportunity)
- ✨ **NEW**: Opportunity scoring with High/Medium/Low priority classification
- ✨ **NEW**: Sales-friendly results with actionable recommendations
- ✨ **NEW**: Auto-save functionality preserving current section and answers
- ✨ **NEW**: Print-optimized results for customer conversations
- ✨ **NEW**: Domain-by-domain gap analysis and opportunity breakdown
- ✨ **NEW**: Keyboard navigation (Arrow keys to navigate, Ctrl+S to save)

### Version 2.0.0 (January 2026)
- ✨ **NEW**: Complete Export/Import functionality for profiles
- ✨ **NEW**: Comprehensive profile validation system
- ✨ **NEW**: Import preview and validation before commit
- ✨ **NEW**: Profile Administration dashboard redesign
- 🔧 **IMPROVED**: Better error messages and user feedback
- 🔧 **IMPROVED**: Enhanced file permission handling
- 🔧 **IMPROVED**: Robust rollback mechanism for failed operations
- 🔧 **IMPROVED**: Removed unnecessary index.php updates (now fully dynamic)
- 🔧 **FIXED**: Permission issues with file operations
- 🔧 **FIXED**: Rollback failures during import errors

### Version 1.x
- Profile creation wizard (4-step process)
- Profile editing capabilities
- Profile administration dashboard
- Enable/Disable functionality
- Profile deletion with automatic cleanup
- Radar chart visualizations using D3.js
- Framework compliance mapping
- Industry-specific recommendations
- Multi-profile assessment support

## Contributing

We welcome contributions! Please follow these guidelines:

### Getting Started
1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Make your changes following coding standards
4. Test changes thoroughly
5. Commit your changes (`git commit -m 'Add amazing feature'`)
6. Push to the branch (`git push origin feature/amazing-feature`)
7. Open a Pull Request

### Development Guidelines
- Follow PSR-12 coding standards for PHP
- Use meaningful variable and function names
- Add comments for complex logic
- Include comprehensive error handling
- Validate all user inputs
- Sanitize all outputs
- Write secure code (prevent XSS, SQL injection, path traversal)
- Add appropriate logging
- Update documentation as needed
- Test changes thoroughly in multiple scenarios

### Code Review Process
- All submissions require code review
- Maintain backwards compatibility when possible
- Update version numbers appropriately
- Add entries to version history

## License

This project is licensed under the terms specified in the [LICENSE](LICENSE) file.

## Support and Contact

- **Repository**: https://github.com/redhat-cop/viewfinder
- **Container Registry**: quay.io/rhn_gps_cjenkins/viewfinder
- **Maintainer**: Chris Jenkins (chrisj@redhat.com)
- **Issues**: Please report bugs and feature requests via GitHub Issues

## Acknowledgments

- Red Hat for PatternFly design system
- D3.js community for visualization libraries
- jQuery and Bootstrap communities
- OpenSSF for security scorecard
- Community contributors and users
- Red Hat Community of Practice

## Disclaimer

This application is provided for informational purposes only. The information is provided "as is" with no guarantee or warranty of accuracy, completeness, or fitness for a particular purpose. Users should conduct their own validation and testing before relying on assessment results for decision-making.

---

**Viewfinder Maturity Assessment** - Empowering organizations to measure, visualize, and improve their technology maturity.

Made with ❤️ by the Red Hat Community of Practice
