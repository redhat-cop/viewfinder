# Testing Red Hat Solutions Display

## 🚀 Quick Start (Easiest Method)

### Option 1: Quick Test (3 Solutions)
Visit: `http://localhost/viewfinder-redhat/test-redhat-quick.php`

This shows **3 key Red Hat solutions**:
- Data Sovereignty - OpenShift Observability
- Technical Sovereignty - Trusted Software Supply Chain
- Managed Services - OpenShift Service Mesh

**Perfect for**: Quick verification that Red Hat solutions are displaying correctly.

### Option 2: Full Test (All 14 Solutions)
Visit: `http://localhost/viewfinder-redhat/test-redhat-solutions.php`

This shows **all 14 Red Hat solutions** across all 7 domains.

**Perfect for**: Comprehensive testing of all Red Hat solution displays.

### Option 3: Manual Assessment

1. Navigate to the Viewfinder application
2. Start a new **Digital Sovereignty** assessment
3. Complete the assessment, making sure to mark some capabilities as incomplete (0-2 on the slider)
4. Submit the assessment

**Perfect for**: Testing real user workflows.

## 🔍 What to Look For

After loading either test file, you should see:

### In the Recommendations Tab (Details)
1. Click the **Details** (Recommendations) tab
2. Expand the accordion sections for domains with Red Hat solutions
3. Look for **red-bordered boxes** after the capability recommendation text
4. Each box should have:
   - 🧊 Cube icon
   - "Red Hat Solution" heading in red
   - Product name in bold white text
   - Description in light gray

### In the Detailed Report
1. Click the **Report** button to open the detailed report
2. Scroll to "Domain Analysis & Recommendations" section
3. Look for **light pink boxes** with red borders
4. Should have print-friendly styling (darker text)

### 2. Check Recommendations Tab (Manual Testing)

1. Go to the **Details** (Recommendations) tab
2. Open the accordion for any domain that has Red Hat solutions:
   - **Data Sovereignty** → Look for "Data Flow and Transfer Auditing"
   - **Technical Sovereignty** → Look for "Code and Intellectual Property Control"
   - **Operational Sovereignty** → Look for "Operational Process Documentation"
   - **Managed Services** → Look for "Configuration-as-Code Ownership"

3. You should see a **red-bordered box** with:
   - Cube icon
   - "Red Hat Solution" heading in red
   - Solution name in bold white text
   - Description in light gray text

### 3. Check Detailed Report

1. Click the **Report** button to open the detailed PDF report
2. Scroll through the "Domain Analysis & Recommendations" section
3. Look for the light pink boxes with red borders containing Red Hat solutions

### 4. Visual Examples

#### In results.php (Dark Theme)
```
┌─────────────────────────────────────────────────┐
│ 🧊 Red Hat Solution                             │ ← Red heading
│                                                  │
│ Red Hat OpenShift Observability                 │ ← Bold white
│                                                  │
│ Provides comprehensive monitoring, logging,     │ ← Light gray
│ and distributed tracing...                      │
└─────────────────────────────────────────────────┘
  Red gradient background with red left border
```

#### In report/index.php (Print Theme)
```
┌─────────────────────────────────────────────────┐
│ 🧊 Red Hat Solution                             │ ← Red heading
│                                                  │
│ Red Hat OpenShift Observability                 │ ← Dark gray bold
│                                                  │
│ Provides comprehensive monitoring, logging,     │ ← Medium gray
│ and distributed tracing...                      │
└─────────────────────────────────────────────────┘
  Light pink background with red left border
```

## Capabilities with Red Hat Solutions

To ensure you see the Red Hat solution boxes, test these specific capabilities:

| Domain | Capability # | Capability Name | Red Hat Solution |
|--------|--------------|-----------------|------------------|
| Data Sovereignty | 7 | Data Flow and Transfer Auditing | OpenShift Observability |
| Technical Sovereignty | 1 | Technology Stack Ownership | RHEL & OpenShift |
| Technical Sovereignty | 3 | Standardised Framework Adoption | OpenShift |
| Technical Sovereignty | 4 | Interoperability Strategy | OpenShift + Ansible |
| Technical Sovereignty | 7 | Code & IP Control | Trusted Software Supply Chain |
| Operational Sovereignty | 1 | Process Documentation | Ansible Automation Platform |
| Operational Sovereignty | 4 | Skills Development | Red Hat Training & Certification |
| Assurance Sovereignty | 1 | Security Audits | Advanced Cluster Security |
| Open Source | 2 | Internal OSS Skills | OSPO Framework |
| Open Source | 6 | OSS Contributions | Upstream Contribution Program |
| Executive Oversight | 3 | Budget Allocation | Red Hat Consulting Services |
| Managed Services | 2 | Container Registry | Red Hat Quay |
| Managed Services | 5 | Network Path Control | OpenShift Service Mesh |
| Managed Services | 6 | Configuration-as-Code | Ansible + OpenShift GitOps |

## Expected Behavior

### When Red Hat Solution IS Available:
- Box appears after the capability recommendation
- Red Hat branding is clearly visible
- Solution name and description are displayed
- Styling matches the theme (dark or light)

### When Red Hat Solution is NOT Available:
- No Red Hat solution box appears
- Standard recommendation text only
- No errors or blank sections

## Troubleshooting

### Red Hat solutions don't appear:
1. Check that you completed an assessment with incomplete capabilities
2. Verify the capability has a Red Hat solution (see table above)
3. Check browser console for JavaScript errors
4. Clear browser cache and reload

### Styling looks wrong:
1. Verify CSS is loading correctly
2. Check for font-awesome icon library loading
3. Inspect element to see if styles are being overridden

### JSON errors:
```bash
# Validate JSON syntax
python3 -m json.tool controls-DigitalSovereignty.json > /dev/null
```

## Updating Solutions

To modify or add new Red Hat solutions:

1. Open `controls-DigitalSovereignty.json`
2. Find the domain and capability
3. Add or update these fields:
   ```json
   "X-redhat-solution": "Product Name",
   "X-redhat-description": "Description of how it helps"
   ```
4. Validate JSON syntax
5. Refresh the application and test

## Next Steps

After testing, you can:
1. Update solution names to match exact Red Hat product names
2. Refine descriptions for better clarity
3. Add more solutions to other capabilities
4. Consider adding links to Red Hat product pages
5. Add similar solutions to the Security profile
