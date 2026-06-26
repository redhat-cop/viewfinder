# Contributing to the Tool

Thank you for your interest in contributing to Viewfinder! We welcome contributions from the community to help improve this project.

## Table of Contents

- [Code of Conduct](#code-of-conduct)
- [How Can I Contribute?](#how-can-i-contribute)
- [Development Setup](#development-setup)
- [Contribution Workflow](#contribution-workflow)
- [Coding Standards](#coding-standards)
- [Pull Request Process](#pull-request-process)
- [Reporting Bugs](#reporting-bugs)
- [Suggesting Enhancements](#suggesting-enhancements)

## Code of Conduct

This project adheres to a Code of Conduct that all contributors are expected to follow. Please read [CODE_OF_CONDUCT.md](CODE_OF_CONDUCT.md) before contributing.

## How Can I Contribute?

### Types of Contributions

We welcome many types of contributions:

- **Bug Reports**: Help us identify and fix issues
- **Feature Requests**: Suggest new features or enhancements
- **Code Contributions**: Submit bug fixes or new features
- **Documentation**: Improve or expand documentation
- **Assessment Profiles**: Create new maturity assessment profiles
- **Translations**: Help translate the application
- **Testing**: Test new features and report issues

### Good First Issues

Look for issues labeled `good-first-issue` in our GitHub issue tracker. These are issues that are well-suited for new contributors.

## Development Setup

### Prerequisites

- PHP 8.1 or higher
- Composer
- Apache or Nginx web server
- Git

### Local Development Setup

1. **Fork and Clone the Repository**

```bash
# Fork the repository on GitHub first
git clone https://github.com/YOUR_USERNAME/viewfinder.git
cd viewfinder

# Add upstream remote
git remote add upstream https://github.com/redhat-cop/viewfinder.git
```

2. **Install Dependencies**

```bash
composer install
```

3. **Set File Permissions**

```bash
chmod 755 .
chmod 644 *.php *.json
chmod 755 includes/ css/ js/
chmod 644 includes/*.php css/* js/*
```

4. **Start Development Server**

```bash
php -S localhost:8080
```

5. **Access the Application**

Open your browser to `http://localhost:8080`

## Contribution Workflow

### 1. Create a Branch

Create a feature branch for your work:

```bash
git checkout -b feature/your-feature-name
```

Branch naming conventions:
- `feature/feature-name` - New features
- `fix/bug-description` - Bug fixes
- `docs/documentation-update` - Documentation changes
- `refactor/code-improvement` - Code refactoring

### 2. Make Your Changes

- Write clear, commented code
- Follow the coding standards (see below)
- Test your changes thoroughly
- Update documentation as needed

### 3. Commit Your Changes

Write clear, descriptive commit messages:

```bash
git add .
git commit -m "Add feature: brief description

- Detailed point 1
- Detailed point 2
- Related issue: #123"
```

Commit message guidelines:
- Use the imperative mood ("Add feature" not "Added feature")
- First line: brief summary (50 characters or less)
- Blank line, then detailed description if needed
- Reference related issues

### 4. Keep Your Fork Updated

```bash
git fetch upstream
git rebase upstream/main
```

### 5. Push to Your Fork

```bash
git push origin feature/your-feature-name
```

### 6. Create a Pull Request

- Go to GitHub and create a pull request from your fork
- Fill out the pull request template
- Link to related issues
- Wait for review and address feedback

## Coding Standards

### PHP Code Style

We follow [PSR-12](https://www.php-fig.org/psr/psr-12/) coding standards:

- Use 4 spaces for indentation (no tabs)
- Opening braces on same line for functions/methods
- Use meaningful variable and function names
- Add PHPDoc comments for classes and methods
- Maximum line length: 120 characters

**Example:**

```php
<?php
/**
 * Calculate maturity rating based on score
 *
 * @param int $score The maturity score
 * @return string The maturity rating
 */
public function getRating(int $score): string
{
    if ($score >= 80) {
        return 'Optimizing';
    }
    // ... rest of logic
}
```

### JavaScript Code Style

- Use 2 spaces for indentation
- Use `const` and `let` (avoid `var`)
- Use meaningful variable names
- Add comments for complex logic
- Use ES6+ features appropriately

### CSS/HTML

- Use 2 spaces for indentation
- Follow BEM naming convention where appropriate
- Keep selectors specific but not overly complex
- Use semantic HTML elements

### Security Best Practices

**CRITICAL**: Always follow security best practices:

- Validate and sanitize all user inputs
- Use prepared statements for database queries (if applicable)
- Escape output to prevent XSS attacks
- Prevent path traversal attacks
- Never hardcode secrets or credentials
- Use HTTPS in production
- Follow principle of least privilege

### File Organization

- Place new PHP classes in `includes/` directory
- Place CSS files in `css/` directory
- Place JavaScript files in `js/` directory
- Place assessment profiles in root as `controls-{ProfileName}.json`
- Keep code organized and modular

## Pull Request Process

### Before Submitting

- [ ] Code follows project coding standards
- [ ] All tests pass (if tests exist)
- [ ] Documentation is updated
- [ ] No personal information or secrets in code
- [ ] Commit messages are clear and descriptive
- [ ] Code is well-commented
- [ ] Changes are tested locally

### PR Description

Your pull request should include:

1. **Summary**: What does this PR do?
2. **Motivation**: Why is this change needed?
3. **Changes**: List of changes made
4. **Testing**: How was this tested?
5. **Screenshots**: If UI changes, include before/after screenshots
6. **Related Issues**: Link to related issues

### Review Process

1. A maintainer will review your PR
2. Address any feedback or requested changes
3. Once approved, a maintainer will merge your PR
4. Your contribution will be included in the next release

### After Merge

- Delete your feature branch
- Update your fork's main branch
- Celebrate! 🎉

## Reporting Bugs

### Before Submitting a Bug Report

- Check existing issues to avoid duplicates
- Try to reproduce the issue on the latest version
- Collect relevant information (version, environment, steps to reproduce)

### Bug Report Template

**Title**: Brief, descriptive title

**Environment:**
- Viewfinder Version: [e.g., 3.1.0]
- PHP Version: [e.g., 8.1.0]
- Web Server: [e.g., Apache 2.4]
- Browser: [e.g., Firefox 115]
- OS: [e.g., RHEL 9]

**Description:**
Clear description of the bug

**Steps to Reproduce:**
1. Go to '...'
2. Click on '...'
3. See error

**Expected Behavior:**
What you expected to happen

**Actual Behavior:**
What actually happened

**Screenshots:**
If applicable, add screenshots

**Additional Context:**
Any other relevant information

## Suggesting Enhancements

### Before Submitting

- Check existing feature requests
- Consider if this fits the project scope
- Think about how this benefits users

### Enhancement Request Template

**Title**: Brief, descriptive title

**Problem Statement:**
What problem does this solve?

**Proposed Solution:**
How should this work?

**Alternatives Considered:**
What other approaches did you consider?

**Benefits:**
Who benefits from this and how?

**Additional Context:**
Any other relevant information

## Creating Assessment Profiles

Assessment profiles are a great way to contribute! To create a new profile:

1. **Copy the Template**

```bash
cp controls-Template.json controls-NewProfile.json
```

2. **Define Your Profile Structure**

- 7 domains recommended (Domain-1 through Domain-7)
- 8 capabilities per domain (progressive maturity levels)
- Clear, actionable recommendations

3. **Follow the JSON Schema**

See README.md for the complete profile JSON format.

4. **Test Your Profile**

- Import via Profile Administration
- Run a complete assessment
- Verify all calculations
- Check PDF export

5. **Submit a Pull Request**

Include documentation about:
- Profile purpose and target audience
- Domain descriptions
- Industry weightings (if applicable)

## Testing

### Manual Testing

Before submitting code:

1. Test all affected functionality
2. Test in multiple browsers (Chrome, Firefox, Safari)
3. Test responsive design on mobile
4. Test with different profiles
5. Verify no console errors
6. Test error handling

### Automated Testing

We welcome contributions to automated testing:

- Unit tests (PHPUnit)
- Integration tests
- End-to-end tests
- Security scanning

## Documentation

Good documentation is as important as good code:

- Update README.md for new features
- Add inline code comments
- Update user guides
- Add examples where helpful
- Keep documentation current

## Questions?

If you have questions:

- Check existing documentation
- Search closed issues
- Open a GitHub Discussion
- Reach out to maintainers

## Recognition

All contributors will be recognized in our release notes and CONTRIBUTORS file. Thank you for helping improve Viewfinder!

## License

By contributing to Viewfinder, you agree that your contributions will be licensed under the same license as the project (see [LICENSE](LICENSE) file).

---

**Thank you for contributing to Viewfinder!** Your efforts help organizations worldwide improve their technology maturity. 🚀
