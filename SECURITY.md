# Security Policy

## Supported Versions

We provide security updates for the following versions:

| Version | Supported          |
| ------- | ------------------ |
| 3.1.x   | :white_check_mark: |
| 3.0.x   | :white_check_mark: |
| < 3.0   | :x:                |

## Reporting a Vulnerability

We take security vulnerabilities seriously. If you discover a security issue in Viewfinder, please report it responsibly.

### How to Report

**DO NOT** open a public GitHub issue for security vulnerabilities.

Instead, please report security issues by:

1. **Email**: Send details to the maintainers (see repository contact information)
2. **GitHub Security Advisory**: Use GitHub's [private vulnerability reporting](https://github.com/redhat-cop/viewfinder/security/advisories/new)

### What to Include

Please include the following information in your report:

- **Description**: Clear description of the vulnerability
- **Impact**: What could an attacker do with this vulnerability?
- **Steps to Reproduce**: Detailed steps to reproduce the issue
- **Affected Versions**: Which versions are affected
- **Suggested Fix**: If you have a fix in mind, please share it
- **Your Contact Information**: How we can reach you for follow-up

### Example Report

```
Subject: [SECURITY] SQL Injection in Profile Import

Description:
The profile import functionality does not properly sanitize file names,
allowing potential SQL injection attacks.

Impact:
An attacker could execute arbitrary SQL commands by uploading a specially
crafted profile file with a malicious filename.

Steps to Reproduce:
1. Create a profile file with name: test'; DROP TABLE users;--
2. Import the profile via the admin interface
3. Observe SQL error in logs

Affected Versions:
3.0.0 - 3.1.0

Suggested Fix:
Use prepared statements and sanitize all file name inputs before
processing them.
```

### Response Timeline

- **Acknowledgment**: Within 48 hours
- **Initial Assessment**: Within 1 week
- **Status Updates**: Every 2 weeks until resolved
- **Fix Timeline**: Varies by severity (see below)

### Severity Levels

We use the following severity classifications:

#### Critical (Fix within 24-48 hours)
- Remote code execution
- SQL injection
- Authentication bypass
- Privilege escalation

#### High (Fix within 1 week)
- Cross-site scripting (XSS)
- Cross-site request forgery (CSRF)
- Information disclosure of sensitive data
- Path traversal vulnerabilities

#### Medium (Fix within 2-4 weeks)
- Denial of service
- Information disclosure of non-sensitive data
- Security misconfiguration

#### Low (Fix in next release)
- Minor information leaks
- UI spoofing
- Non-exploitable bugs

## Security Best Practices

### For Users

When deploying Viewfinder in production:

1. **Keep Updated**: Always run the latest version
2. **Use HTTPS**: Never run in production without TLS/SSL
3. **Restrict Access**: Use authentication/authorization where appropriate
4. **Review Permissions**: Ensure file permissions are correctly set
5. **Scan Dependencies**: Regularly check for vulnerable dependencies
6. **Monitor Logs**: Review application logs for suspicious activity
7. **Backup Regularly**: Maintain regular backups of assessment data

### For Developers

When contributing to Viewfinder:

1. **Input Validation**: Validate and sanitize all user inputs
2. **Output Encoding**: Escape all output to prevent XSS
3. **Parameterized Queries**: Use prepared statements (if database is added)
4. **Path Traversal**: Validate file paths and prevent directory traversal
5. **Authentication**: Never hardcode credentials
6. **Least Privilege**: Run with minimum required permissions
7. **Security Headers**: Include appropriate security headers
8. **Dependency Review**: Check dependencies for known vulnerabilities

## Known Security Considerations

### Current Architecture

Viewfinder is designed as a self-hosted assessment tool with the following security characteristics:

- **No Database**: Uses JSON file storage (no SQL injection risk)
- **Local Processing**: All data processed locally (no external API calls)
- **No User Authentication**: Designed for trusted internal environments
- **File-Based Storage**: Assessment results stored as JSON files

### Deployment Recommendations

For production deployments:

- Deploy behind a VPN or firewall
- Use web server authentication (Basic Auth, SSO, etc.)
- Restrict file system permissions
- Use read-only containers where possible
- Monitor file system for unauthorized changes

## Security Updates

Security fixes are released as:

1. **Patch Releases**: For minor/low severity issues (e.g., 3.1.1)
2. **Hotfix Releases**: For critical/high severity issues (immediate)

Security updates are announced via:
- GitHub Security Advisories
- GitHub Releases
- Repository README

## Disclosure Policy

We follow responsible disclosure:

1. **Private Fix**: We develop fixes privately
2. **Coordinated Release**: We coordinate with reporters before public disclosure
3. **Public Announcement**: We announce security issues after fixes are available
4. **CVE Assignment**: We request CVEs for significant vulnerabilities
5. **Credit**: We credit security researchers (with permission)

## Security Hall of Fame

We maintain a list of security researchers who have responsibly disclosed vulnerabilities:

<!-- Security researchers will be listed here with their permission -->

## Questions?

If you have questions about this security policy, please:

- Review our [Contributing Guidelines](CONTRIBUTING.md)
- Open a GitHub Discussion (for non-sensitive questions)
- Contact the maintainers directly (for sensitive topics)

## References

- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [CWE Top 25](https://cwe.mitre.org/top25/)
- [GitHub Security Best Practices](https://docs.github.com/en/code-security)

---

Thank you for helping keep Viewfinder and its users safe!
