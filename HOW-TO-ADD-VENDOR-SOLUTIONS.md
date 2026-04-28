# How to Add Vendor Solutions - Team Guide

## Quick Start

Both JSON files now have **empty placeholders** ready for you to fill in:

- **controls-DigitalSovereignty.json**: 42 empty slots (14 already filled)
- **controls-Security.json**: 56 empty slots (ready to fill)

## How to Find Empty Slots

### Method 1: Search for Empty Strings
Open the JSON file and search for:
```
"vendor-solution": ""
```

### Method 2: Look for Paired Empty Fields
Every empty slot has two fields together:
```json
"3-vendor-solution": "",
"3-vendor-description": "",
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
"3-vendor-solution": "",           ← Fill this in
"3-vendor-description": "",        ← Fill this in
```

### Step 2: Add the Vendor Solution Name
Replace the empty string with the product/solution name:

```json
"3-vendor-solution": "Enterprise Kubernetes Platform",
```

### Step 3: Add the Description
Write 1-2 sentences explaining how it addresses this capability:

```json
"3-vendor-description": "Enterprise Kubernetes platform built on open standards (Kubernetes, OCI, CRI-O), enabling vendor-neutral container orchestration and application deployment.",
```

## Complete Example

### Before (Empty):
```json
"5": "Hardware and Infrastructure Source Verification",
"5-summary": "...",
"5-tier": "Strategic",
"5-points": "5",
"5-recommendation": "...",
"5-vendor-solution": "",
"5-vendor-description": "",
```

### After (Filled):
```json
"5": "Hardware and Infrastructure Source Verification",
"5-summary": "...",
"5-tier": "Strategic",
"5-points": "5",
"5-recommendation": "...",
"5-vendor-solution": "Certified Hardware Program",
"5-vendor-description": "Hardware certification program ensures compatibility and provides verified hardware configurations with full supply chain transparency and vendor attestation.",
```

## Writing Guidelines

### For Solution Names:
- ✅ Use official product names
- ✅ Be specific (e.g., "Advanced Cluster Security for Kubernetes" not just "ACS")
- ✅ Include vendor name if appropriate for branding
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

❌ **Bad**: "Amazing product that does lots of things and is really great for security."

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
   - Complete an assessment and view the results
   - Check that solutions display correctly in recommendations
   - Verify styling looks good in both dark and light themes

## Current Status

### Digital Sovereignty Profile
- ✅ 14 capabilities filled
- 📝 42 capabilities empty
- 📊 56 total capabilities

Already filled solutions include:
- Data Sovereignty: Observability Platform
- Technical Sovereignty: Enterprise Linux & Container Platform, Trusted Software Supply Chain, etc.
- Operational Sovereignty: Automation Platform, Professional Training
- Open Source: OSPO Framework, Upstream Contribution Program
- And more...

### Security Profile
- 📝 56 capabilities empty
- 📊 56 total capabilities

All capabilities ready for security solutions like:
- Advanced Cluster Security for Kubernetes
- Enterprise Linux Operating System
- Container Platform Plus
- Automation Platform
- Security Insights
- And more...

## Tips for the Team

1. **Assign domains**: Split the work by domain (5-7 domains per profile)
2. **Review together**: Have someone review descriptions for consistency
3. **Use existing examples**: Look at the 14 already-filled solutions as templates
4. **Check product names**: Verify official names from vendor documentation
5. **Keep it simple**: Don't overthink it - direct and clear is best

## Questions?

- Check existing filled solutions for examples
- Review `IMPLEMENTATION-SUMMARY.md` for technical details
- Test your changes by completing an assessment

## Don't Fill These

Leave empty if:
- ❌ No relevant vendor solution exists
- ❌ The capability is too generic
- ❌ You're not sure - better to leave empty than guess

The display code handles empty fields gracefully - they simply won't show a vendor solution box.

## After Completion

Once all solutions are filled:
1. Validate JSON files
2. Test by completing assessments
3. Review in both Dark (results.php) and Light (report) themes
4. Check mobile display
5. Get feedback from sales/product teams
6. Deploy! 🚀
