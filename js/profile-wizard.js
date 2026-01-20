/**
 * Profile Wizard - Frontend JavaScript
 *
 * Handles wizard navigation, validation, and AJAX communication
 */

(function($) {
    'use strict';

    // Wizard step constants
    const WizardSteps = {
        METADATA: 0,
        DOMAIN_1: 1,
        DOMAIN_2: 2,
        DOMAIN_3: 3,
        DOMAIN_4: 4,
        DOMAIN_5: 5,
        DOMAIN_6: 6,
        DOMAIN_7: 7,
        REVIEW: 8
    };

    // State
    let currentStep = 0;
    let wizardData = {
        metadata: {},
        domains: {}
    };

    /**
     * Initialize wizard on page load
     */
    $(document).ready(function() {
        console.log('Profile Wizard initialized');
        loadStep(currentStep);
        updateProgress();

        // Attach event handlers
        $('#prevBtn').on('click', handlePrevious);
        $('#nextBtn').on('click', handleNext);
        $('#generateBtn').on('click', handleGenerate);
    });

    /**
     * Load and display a specific step
     */
    function loadStep(step) {
        currentStep = step;

        const content = getStepContent(step);
        $('#wizardContent').html(content);

        updateProgress();
        updateButtonVisibility();

        // Scroll to top of wizard container
        $('html, body').animate({ scrollTop: $('.wizard-container').offset().top - 20 }, 300);

        // Attach step-specific handlers
        if (step === WizardSteps.METADATA) {
            initMetadataStep();
        } else if (step >= WizardSteps.DOMAIN_1 && step <= WizardSteps.DOMAIN_7) {
            initDomainStep(step);
        } else if (step === WizardSteps.REVIEW) {
            initReviewStep();
        }
    }

    /**
     * Get HTML content for a step
     */
    function getStepContent(step) {
        switch(step) {
            case WizardSteps.METADATA:
                return getMetadataHTML();
            case WizardSteps.DOMAIN_1:
            case WizardSteps.DOMAIN_2:
            case WizardSteps.DOMAIN_3:
            case WizardSteps.DOMAIN_4:
            case WizardSteps.DOMAIN_5:
            case WizardSteps.DOMAIN_6:
            case WizardSteps.DOMAIN_7:
                return getDomainHTML(step);
            case WizardSteps.REVIEW:
                return getReviewHTML();
            default:
                return '<p>Invalid step</p>';
        }
    }

    /**
     * Generate HTML for metadata step
     */
    function getMetadataHTML() {
        return `
            <div class="wizard-step">
                <h2>Profile Information</h2>
                <p>Create a new assessment profile by providing basic information.</p>

                <div class="form-group">
                    <label for="profile_name">Profile Name (internal identifier):</label>
                    <textarea id="profile_name" class="form-control" rows="1"
                           placeholder="e.g., Automation" pattern="[a-zA-Z0-9_]+" required></textarea>
                    <small>Use only letters, numbers, and underscores. No spaces.</small>
                    <div id="name_validation" class="validation-message"></div>
                </div>

                <div class="form-group">
                    <label for="display_name">Display Name (shown in UI):</label>
                    <textarea id="display_name" class="form-control" rows="1"
                           placeholder="e.g., Automation & DevOps" required></textarea>
                    <small>This is the name that will appear in navigation buttons.</small>
                </div>

                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" id="enabled" value="1">
                        Enable profile immediately
                    </label>
                    <small>If unchecked, profile will be created but disabled (can be enabled later in Config.php)</small>
                </div>
            </div>
        `;
    }

    /**
     * Generate HTML for domain step
     */
    function getDomainHTML(step) {
        const domainNum = step;
        return `
            <div class="wizard-step">
                <h2>Domain ${domainNum} Configuration</h2>
                <p>Define the domain and its 8 capabilities (maturity levels).</p>

                <div class="form-group">
                    <label for="domain_title">Domain Title:</label>
                    <textarea id="domain_title" class="form-control" rows="2"
                           placeholder="e.g., Automation Infrastructure" required></textarea>
                </div>

                <div class="form-group">
                    <label for="domain_overview">Domain Overview:</label>
                    <textarea id="domain_overview" class="form-control" rows="4"
                              placeholder="Brief description of this domain..." required></textarea>
                </div>

                <h3>Capabilities (Maturity Levels 1-8)</h3>
                <p class="help-text">Define 8 capabilities representing increasing maturity levels. Typically: 1-3 = Foundation, 4-6 = Strategic, 7-8 = Advanced</p>
                ${generateCapabilityFields()}
            </div>
        `;
    }

    /**
     * Generate capability input fields
     */
    function generateCapabilityFields() {
        let html = '';
        for (let i = 1; i <= 8; i++) {
            const tier = i <= 3 ? 'Foundation' : (i <= 6 ? 'Strategic' : 'Advanced');
            const defaultTier = tier;

            html += `
                <div class="capability-group">
                    <h4>Capability ${i} <span class="tier-badge tier-${tier.toLowerCase()}">${tier}</span></h4>
                    <div class="form-row">
                        <div class="form-group col-md-8">
                            <label>Capability Name:</label>
                            <textarea class="form-control cap-name" data-cap="${i}" rows="2"
                                   placeholder="e.g., Basic Script Automation" required></textarea>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Tier:</label>
                            <select class="form-control cap-tier" data-cap="${i}">
                                <option value="Foundation" ${defaultTier === 'Foundation' ? 'selected' : ''}>Foundation</option>
                                <option value="Strategic" ${defaultTier === 'Strategic' ? 'selected' : ''}>Strategic</option>
                                <option value="Advanced" ${defaultTier === 'Advanced' ? 'selected' : ''}>Advanced</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Summary (brief description):</label>
                        <textarea class="form-control cap-summary" data-cap="${i}" rows="3"
                                  placeholder="One or two sentence summary shown in tooltip"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Recommendation (HTML content):</label>
                        <textarea class="form-control cap-recommendation" data-cap="${i}" rows="5"
                                  placeholder="HTML content with recommendations, best practices, etc."></textarea>
                        <small>You can use HTML tags like &lt;h2&gt;, &lt;p&gt;, &lt;ul&gt;, &lt;li&gt;, etc.</small>
                    </div>
                </div>
            `;
        }
        return html;
    }

    /**
     * Generate HTML for review step
     */
    function getReviewHTML() {
        let html = '<div class="wizard-step"><h2>Review Your Profile</h2>';
        html += '<p>Please review all information before generating the profile.</p>';

        // Metadata section
        html += '<div class="review-section">';
        html += '<h3>Profile Information</h3>';
        html += `<p><strong>Name:</strong> ${wizardData.metadata.profile_name || 'N/A'}</p>`;
        html += `<p><strong>Display Name:</strong> ${wizardData.metadata.display_name || 'N/A'}</p>`;
        html += `<p><strong>Enabled:</strong> ${wizardData.metadata.enabled ? 'Yes' : 'No'}</p>`;
        html += `<button class="ui-button ui-widget ui-corner-all btn-sm" onclick="profileWizard.editStep(0)">Edit</button>`;
        html += '</div>';

        // Domain sections
        for (let i = 1; i <= 7; i++) {
            const domain = wizardData.domains[i];
            if (domain) {
                html += `
                    <div class="review-section">
                        <h3>Domain ${i}: ${escapeHtml(domain.title)}</h3>
                        <p>${escapeHtml(domain.overview)}</p>
                        <details>
                            <summary>View ${Object.keys(domain.capabilities || {}).length} capabilities</summary>
                            <ul class="capability-list">
                `;

                for (let cap = 1; cap <= 8; cap++) {
                    const capData = domain.capabilities[cap];
                    if (capData) {
                        html += `<li><strong>${cap}.</strong> ${escapeHtml(capData.name)} <em>(${capData.tier})</em></li>`;
                    }
                }

                html += `
                            </ul>
                        </details>
                        <button class="ui-button ui-widget ui-corner-all btn-sm" onclick="profileWizard.editStep(${i})">Edit Domain</button>
                    </div>
                `;
            } else {
                html += `
                    <div class="review-section warning">
                        <h3>Domain ${i}: Not Configured</h3>
                        <p>This domain has not been configured yet.</p>
                        <button class="ui-button ui-widget ui-corner-all btn-sm" onclick="profileWizard.editStep(${i})">Configure Domain</button>
                    </div>
                `;
            }
        }

        html += '<div id="json-preview-container"></div>';
        html += '</div>';
        return html;
    }

    /**
     * Initialize metadata step
     */
    function initMetadataStep() {
        // Restore saved data
        if (wizardData.metadata.profile_name) {
            $('#profile_name').val(wizardData.metadata.profile_name);
            $('#display_name').val(wizardData.metadata.display_name);
            $('#enabled').prop('checked', wizardData.metadata.enabled || false);
        }

        // Real-time validation on profile name
        $('#profile_name').on('blur', function() {
            const name = $(this).val();
            if (name) {
                validateProfileName(name);
            }
        });

        // Auto-fill display name from profile name if empty
        $('#profile_name').on('input', function() {
            const profileName = $(this).val();
            if (!$('#display_name').val()) {
                $('#display_name').val(profileName);
            }
        });
    }

    /**
     * Validate profile name via AJAX
     */
    function validateProfileName(name) {
        $('#name_validation').html('<span class="text-info">Checking availability...</span>');

        $.post('profile-creator-handler.php', {
            action: 'validate_name',
            profile_name: name
        }, function(response) {
            if (response.valid) {
                $('#name_validation').html('<span class="text-success">✓ Name is available</span>');
            } else {
                $('#name_validation').html('<span class="text-danger">✗ ' + escapeHtml(response.message) + '</span>');
            }
        }, 'json').fail(function() {
            $('#name_validation').html('<span class="text-danger">✗ Validation failed</span>');
        });
    }

    /**
     * Initialize domain step
     */
    function initDomainStep(step) {
        const domainNum = step;
        const domain = wizardData.domains[domainNum];

        if (domain) {
            // Restore saved data
            $('#domain_title').val(domain.title || '');
            $('#domain_overview').val(domain.overview || '');

            for (let i = 1; i <= 8; i++) {
                const cap = domain.capabilities[i];
                if (cap) {
                    $(`.cap-name[data-cap="${i}"]`).val(cap.name || '');
                    $(`.cap-tier[data-cap="${i}"]`).val(cap.tier || 'Foundation');
                    $(`.cap-summary[data-cap="${i}"]`).val(cap.summary || '');
                    $(`.cap-recommendation[data-cap="${i}"]`).val(cap.recommendation || '');
                }
            }
        }
    }

    /**
     * Initialize review step
     */
    function initReviewStep() {
        // Load JSON preview
        $.post('profile-creator-handler.php', {
            action: 'get_preview'
        }, function(response) {
            if (response.success && response.json) {
                const preview = `
                    <div class="review-section">
                        <h3>JSON Preview</h3>
                        <details>
                            <summary>View generated JSON structure</summary>
                            <pre class="json-preview">${escapeHtml(response.json)}</pre>
                        </details>
                    </div>
                `;
                $('#json-preview-container').html(preview);
            }
        }, 'json').fail(function() {
            $('#json-preview-container').html('<p class="text-danger">Failed to load preview</p>');
        });
    }

    /**
     * Handle Next button click
     */
    function handleNext() {
        if (!validateCurrentStep()) {
            return;
        }

        saveCurrentStep();

        if (currentStep < WizardSteps.REVIEW) {
            loadStep(currentStep + 1);
        }
    }

    /**
     * Handle Previous button click
     */
    function handlePrevious() {
        if (currentStep > WizardSteps.METADATA) {
            saveCurrentStep();
            loadStep(currentStep - 1);
        }
    }

    /**
     * Handle Generate button click
     */
    function handleGenerate() {
        if (!confirm('Generate profile? This will create files and update system configuration.')) {
            return;
        }

        $('#generateBtn').prop('disabled', true).text('Generating...');
        showLoading('Generating profile files and updating configuration...');

        $.post('profile-creator-handler.php', {
            action: 'generate'
        }, function(response) {
            if (response.success) {
                showSuccess(response.message, response.profile_name);
            } else {
                showError(response.error || 'Generation failed');
                $('#generateBtn').prop('disabled', false).text('Generate Profile');
            }
        }, 'json').fail(function() {
            showError('Server error occurred during generation');
            $('#generateBtn').prop('disabled', false).text('Generate Profile');
        });
    }

    /**
     * Validate current step
     */
    function validateCurrentStep() {
        const requiredFields = $('#wizardContent').find('[required]');
        let valid = true;
        let firstError = null;

        requiredFields.each(function() {
            if (!$(this).val() || $(this).val().trim() === '') {
                $(this).addClass('error');
                valid = false;
                if (!firstError) {
                    firstError = $(this);
                }
            } else {
                $(this).removeClass('error');
            }
        });

        if (!valid) {
            showError('Please fill in all required fields');
            if (firstError) {
                firstError.focus();
            }
        }

        return valid;
    }

    /**
     * Save current step data
     */
    function saveCurrentStep() {
        if (currentStep === WizardSteps.METADATA) {
            wizardData.metadata = {
                profile_name: $('#profile_name').val(),
                display_name: $('#display_name').val(),
                enabled: $('#enabled').is(':checked')
            };
        } else if (currentStep >= WizardSteps.DOMAIN_1 && currentStep <= WizardSteps.DOMAIN_7) {
            const domainNum = currentStep;
            wizardData.domains[domainNum] = {
                title: $('#domain_title').val(),
                overview: $('#domain_overview').val(),
                capabilities: {}
            };

            for (let i = 1; i <= 8; i++) {
                wizardData.domains[domainNum].capabilities[i] = {
                    name: $(`.cap-name[data-cap="${i}"]`).val(),
                    tier: $(`.cap-tier[data-cap="${i}"]`).val(),
                    summary: $(`.cap-summary[data-cap="${i}"]`).val(),
                    recommendation: $(`.cap-recommendation[data-cap="${i}"]`).val()
                };
            }
        }

        // Save to server session
        $.post('profile-creator-handler.php', {
            action: 'save_step',
            step: currentStep,
            data: currentStep === 0 ? wizardData.metadata : wizardData.domains[currentStep]
        });
    }

    /**
     * Update progress bar
     */
    function updateProgress() {
        const totalSteps = 9;
        const progress = ((currentStep + 1) / totalSteps) * 100;
        $('#wizardProgress').css('width', progress + '%');
        $('#currentStep').text(currentStep + 1);
    }

    /**
     * Update button visibility
     */
    function updateButtonVisibility() {
        $('#prevBtn').toggle(currentStep > WizardSteps.METADATA);
        $('#nextBtn').toggle(currentStep < WizardSteps.REVIEW);
        $('#generateBtn').toggle(currentStep === WizardSteps.REVIEW);
    }

    /**
     * Show loading message
     */
    function showLoading(message) {
        $('#wizardContent').html(`
            <div class="wizard-loading">
                <div class="spinner"></div>
                <p>${message}</p>
            </div>
        `);
        $('#prevBtn, #nextBtn, #generateBtn').hide();
    }

    /**
     * Show success message
     */
    function showSuccess(message, profileName) {
        $('#wizardContent').html(`
            <div class="wizard-result success">
                <h2>✓ Success!</h2>
                <p>${escapeHtml(message)}</p>
                <div class="result-actions">
                    <a href="index.php?profile=${profileName}" class="ui-button ui-widget ui-corner-all">
                        View New Profile
                    </a>
                    <a href="index.php" class="ui-button ui-widget ui-corner-all">
                        Return to Home
                    </a>
                    <a href="profile-creator.php" class="ui-button ui-widget ui-corner-all">
                        Create Another Profile
                    </a>
                </div>
            </div>
        `);
        $('#prevBtn, #nextBtn, #generateBtn').hide();
        updateProgress();
    }

    /**
     * Show error message
     */
    function showError(message) {
        $('<div class="alert alert-danger">' + escapeHtml(message) + '</div>')
            .prependTo('#wizardContent')
            .delay(5000)
            .fadeOut(function() { $(this).remove(); });
    }

    /**
     * Escape HTML for safe display
     */
    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return String(text).replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    /**
     * Public API
     */
    window.profileWizard = {
        editStep: function(step) {
            saveCurrentStep();
            loadStep(step);
        }
    };

})(jQuery);
