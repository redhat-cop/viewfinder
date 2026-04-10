# How to Add Red Hat Solutions - Team Guide

## Quick Start

Both JSON files now have **empty placeholders** ready for you to fill in:

- **controls-DigitalSovereignty.json**: 42 empty slots (14 already filled)
- **controls-Security.json**: 56 empty slots (ready to fill)

## How to Find Empty Slots

### Method 1: Search for Empty Strings
Open the JSON file and search for:
```
"redhat-solution": ""
```

### Method 2: Look for Paired Empty Fields
Every empty slot has two fields together:
```json
"3-redhat-solution": "",
"3-redhat-description": "",
```

## How to Fill In a Solution

### Step 1: Find the Capability
Look at the capability name above the empty fields:

```json
"3": "Standardised Technical Framework Adoption",
"3-summary": "...",
"3-tier": "Foundation",
"3-points": "3",
"3-recommendation": "...",
"3-redhat-solution": "",           ← Fill this in
"3-redhat-description": "",        ← Fill this in
```

### Step 2: Add the Red Hat Solution Name
Replace the empty string with the Red Hat product/solution name:

```json
"3-redhat-solution": "Red Hat OpenShift",
```

### Step 3: Add the Description
Write 1-2 sentences explaining how it addresses this capability:

```json
"3-redhat-description": "Enterprise Kubernetes platform built on open standards (Kubernetes, OCI, CRI-O), enabling vendor-neutral container orchestration and application deployment.",
```

## Complete Example

### Before (Empty):
```json
"5": "Hardware and Infrastructure Source Verification",
"5-summary": "...",
"5-tier": "Strategic",
"5-points": "5",
"5-recommendation": "...",
"5-redhat-solution": "",
"5-redhat-description": "",
```

### After (Filled):
```json
"5": "Hardware and Infrastructure Source Verification",
"5-summary": "...",
"5-tier": "Strategic",
"5-points": "5",
"5-recommendation": "...",
"5-redhat-solution": "Red Hat Certified Hardware",
"5-redhat-description": "Red Hat hardware certification program ensures compatibility and provides verified hardware configurations with full supply chain transparency and vendor attestation.",
```

## Writing Guidelines

### For Solution Names:
- ✅ Use official Red Hat product names
- ✅ Be specific (e.g., "Red Hat Advanced Cluster Security for Kubernetes" not just "ACS")
- ✅ Include "Red Hat" prefix for branding
- ❌ Don't use abbreviations unless widely known

### For Descriptions:
- ✅ Keep to 1-2 sentences
- ✅ Focus on HOW it addresses the capability
- ✅ Mention key features relevant to the capability
- ✅ Use benefit language (what it enables/provides)
- ❌ Don't write marketing fluff
- ❌ Don't list features without context
- ❌ Don't exceed 250 characters if possible

## Good Description Examples

✅ **Good**: "Comprehensive supply chain security solution providing code signing, provenance tracking, verification, and source code protection throughout the development lifecycle."

✅ **Good**: "Enterprise Kubernetes platform built on open standards enabling vendor-neutral container orchestration and application deployment across any infrastructure."

❌ **Bad**: "Red Hat's amazing product that does lots of things and is really great for security."

❌ **Bad**: "This solution has features A, B, C, D, E, F, G, and more!"

## Validation

After filling in solutions:

1. **Validate JSON syntax**:
   ```bash
   python3 -m json.tool controls-DigitalSovereignty.json > /dev/null
   python3 -m json.tool controls-Security.json > /dev/null
   ```

2. **Check for empty quotes**:
   Search for `""` to find any remaining empty fields

3. **Test in the application**:
   - Load test-redhat-quick.php or test-redhat-solutions.php
   - Check that solutions display correctly
   - Verify styling looks good

## Current Status

### Digital Sovereignty Profile
- ✅ 14 capabilities filled
- 📝 42 capabilities empty
- 📊 56 total capabilities

Already filled solutions include:
- Data Sovereignty: OpenShift Observability
- Technical Sovereignty: RHEL & OpenShift, Trusted Software Supply Chain, etc.
- Operational Sovereignty: Ansible Automation Platform, Red Hat Training
- Open Source: OSPO Framework, Upstream Contribution Program
- And more...

### Security Profile
- 📝 56 capabilities empty
- 📊 56 total capabilities

All capabilities ready for Red Hat security solutions like:
- Advanced Cluster Security for Kubernetes
- Red Hat Enterprise Linux (RHEL)
- OpenShift Platform Plus
- Ansible Automation Platform
- Red Hat Insights
- And more...

## Tips for the Team

1. **Assign domains**: Split the work by domain (7 domains per profile)
2. **Review together**: Have someone review descriptions for consistency
3. **Use existing examples**: Look at the 14 already-filled solutions as templates
4. **Check product names**: Verify official names on redhat.com
5. **Keep it simple**: Don't overthink it - direct and clear is best

## Questions?

- Check existing filled solutions for examples
- Review `IMPLEMENTATION-SUMMARY.md` for technical details
- See `REDHAT-SOLUTIONS.md` for display information
- Test your changes with `test-redhat-quick.php`

## Don't Fill These

Leave empty if:
- ❌ No relevant Red Hat solution exists
- ❌ The capability is too generic
- ❌ You're not sure - better to leave empty than guess

The display code handles empty fields gracefully - they simply won't show a Red Hat solution box.

## After Completion

Once all solutions are filled:
1. Validate JSON files
2. Test with both test files
3. Review in both Dark (results.php) and Light (report) themes
4. Check mobile display
5. Get feedback from sales/product teams
6. Deploy! 🚀
