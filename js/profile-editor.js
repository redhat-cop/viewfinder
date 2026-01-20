/**
 * Profile Editor - Frontend JavaScript
 *
 * Extends wizard pattern for editing existing profiles
 * Based on profile-wizard.js with modifications for editing mode
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
    let profileName = '';
    let editMode = 'edit'; // edit or readonly
    let isReadOnly = false;

    /**
     * Initialize editor on page load
     */
    $(document).ready(function() {
        console.log('Profile Editor initialized');

        // Get profile context from hidden inputs
        profileName = $('#profileName').val();
        editMode = $('#editMode').val();
        isReadOnly = (editMode === 'readonly');

        // Load existing profile data
        loadExistingProfile();

        // Attach event handlers
        $('#prevBtn').on('click', handlePrevious);
        $('#nextBtn').on('click', handleNext);
        $('#updateBtn').on('click', handleUpdate);
    });

    /**
     * Load existing profile data via AJAX
     */
    function loadExistingProfile() {
        showLoading('Loading profile data...');

        $.post('profile-editor-handler.php', {
            action: 'load_profile',
            profile_name: profileName
        }, function(response) {
            if (response.success) {
                wizardData = {
                    metadata: response.metadata,
                    domains: response.domains
                };

                console.log('Profile loaded successfully:', response.profile_name);

                // Load first step
                loadStep(currentStep);
                updateProgress();
            } else {
                showError(response.error || 'Failed to load profile');
            }
        }, 'json').fail(function() {
            showError('Server error occurred while loading profile');
        });
    }

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

        // If readonly mode, disable all inputs
        if (isReadOnly) {
            disableAllInputs();
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
                <p>${isReadOnly ? 'View basic profile information.' : 'Edit profile information.'}</p>

                <div class="form-group">
                    <label for="profile_name">Profile Name (internal identifier):</label>
                    <textarea id="profile_name" class="form-control locked" rows="1"
                           readonly
                           title="Profile name cannot be changed"></textarea>
                    <small>Profile name is locked and cannot be changed during editing.</small>
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
                        Enable profile
                    </label>
                    <small>If unchecked, profile will be disabled and hidden from main navigation.</small>
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
                <p>${isReadOnly ? 'View domain and capability details.' : 'Edit the domain and its 8 capabilities (maturity levels).'}</p>

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
                <p class="help-text">8 capabilities representing increasing maturity levels. Typically: 1-3 = Foundation, 4-6 = Strategic, 7-8 = Advanced</p>
                ${generateCapabilityFields()}
            </div>
        `;
    }

    /**
     * Generate capability input fields (1-8)
     */
    function generateCapabilityFields() {
        let html = '';
        for (let i = 1; i <= 8; i++) {
            html += `
                <div class="capability-group">
                    <h4>Capability ${i}</h4>

                    <div class="form-group">
                        <label for="cap${i}_name">Name:</label>
                        <textarea id="cap${i}_name" class="form-control" rows="2"
                                  placeholder="e.g., Configuration Management" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="cap${i}_summary">Summary:</label>
                        <textarea id="cap${i}_summary" class="form-control" rows="3"
                                  placeholder="Brief description..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="cap${i}_tier">Tier:</label>
                        <select id="cap${i}_tier" class="form-control" required>
                            <option value="Foundation">Foundation (Basic capabilities)</option>
                            <option value="Strategic">Strategic (Intermediate capabilities)</option>
                            <option value="Advanced">Advanced (Expert capabilities)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="cap${i}_recommendation">Recommendation HTML (optional):</label>
                        <textarea id="cap${i}_recommendation" class="form-control" rows="4"
                                  placeholder="HTML-formatted recommendations..."></textarea>
                        <small>Can include HTML formatting for display.</small>
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
        let html = '<div class="wizard-step">';
        html += '<h2>Review & Update</h2>';
        html += '<p>Review your changes and click "Update Profile" to save.</p>';

        html += '<h3>Profile Information</h3>';
        html += '<table class="summary-table">';
        html += `<tr><th>Profile Name:</th><td>${escapeHtml(wizardData.metadata.profile_name || 'N/A')}</td></tr>`;
        html += `<tr><th>Display Name:</th><td>${escapeHtml(wizardData.metadata.display_name || 'N/A')}</td></tr>`;
        html += `<tr><th>Enabled:</th><td>${wizardData.metadata.enabled ? 'Yes' : 'No'}</td></tr>`;
        html += '</table>';

        html += '<h3>Domains</h3>';
        for (let i = 1; i <= 7; i++) {
            const domain = wizardData.domains[i];
            if (domain) {
                html += `<div class="domain-summary">`;
                html += `<h4>Domain ${i}: ${escapeHtml(domain.title || 'N/A')}</h4>`;
                html += `<p>${escapeHtml(domain.overview || 'N/A')}</p>`;
                html += `<p><small>${Object.keys(domain.capabilities || {}).length} capabilities defined</small></p>`;
                html += `</div>`;
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
        // Populate saved data
        if (wizardData.metadata.profile_name) {
            $('#profile_name').val(wizardData.metadata.profile_name);
            $('#display_name').val(wizardData.metadata.display_name);
            $('#enabled').prop('checked', wizardData.metadata.enabled || false);
        }

        // Profile name is always locked in editor
        $('#profile_name').prop('readonly', true).addClass('locked');
    }

    /**
     * Initialize domain step
     */
    function initDomainStep(step) {
        const domainNum = step;
        const domain = wizardData.domains[domainNum];

        if (domain) {
            // Populate domain fields
            $('#domain_title').val(domain.title || '');
            $('#domain_overview').val(domain.overview || '');

            // Populate capability fields
            if (domain.capabilities) {
                for (let i = 1; i <= 8; i++) {
                    const cap = domain.capabilities[i];
                    if (cap) {
                        $(`#cap${i}_name`).val(cap.name || '');
                        $(`#cap${i}_summary`).val(cap.summary || '');
                        $(`#cap${i}_tier`).val(cap.tier || 'Foundation');
                        $(`#cap${i}_recommendation`).val(cap.recommendation || '');
                    }
                }
            }
        }
    }

    /**
     * Initialize review step
     */
    function initReviewStep() {
        // Load JSON preview
        $.post('profile-editor-handler.php', {
            action: 'get_preview'
        }, function(response) {
            if (response.success) {
                const preview = `
                    <h3>JSON Preview</h3>
                    <div class="json-preview">
                        <pre>${escapeHtml(response.json)}</pre>
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
        if (!isReadOnly && !validateCurrentStep()) {
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
            if (!isReadOnly) {
                saveCurrentStep();
            }
            loadStep(currentStep - 1);
        }
    }

    /**
     * Handle Update button click
     */
    function handleUpdate() {
        if (isReadOnly) {
            alert('Profile is in read-only mode and cannot be updated.');
            return;
        }

        if (!confirm('Update this profile? This will modify the JSON file and configuration.')) {
            return;
        }

        $('#updateBtn').prop('disabled', true).text('Updating...');
        showLoading('Updating profile...');

        $.post('profile-editor-handler.php', {
            action: 'update'
        }, function(response) {
            if (response.success) {
                showSuccess(response.message, response.profile_name);
                // Redirect to admin dashboard after 3 seconds
                setTimeout(function() {
                    window.location.href = 'profile-admin.php';
                }, 3000);
            } else {
                showError(response.error || 'Update failed');
                $('#updateBtn').prop('disabled', false).text('Update Profile');
            }
        }, 'json').fail(function() {
            showError('Server error occurred during update');
            $('#updateBtn').prop('disabled', false).text('Update Profile');
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
                    name: $(`#cap${i}_name`).val(),
                    summary: $(`#cap${i}_summary`).val(),
                    tier: $(`#cap${i}_tier`).val(),
                    recommendation: $(`#cap${i}_recommendation`).val()
                };
            }
        }

        // Save to server session
        $.post('profile-editor-handler.php', {
            action: 'save_step',
            step: currentStep,
            data: currentStep === WizardSteps.METADATA ? wizardData.metadata : wizardData.domains[currentStep]
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
        // Previous button
        if (currentStep === WizardSteps.METADATA) {
            $('#prevBtn').hide();
        } else {
            $('#prevBtn').show();
        }

        // Next and Update buttons
        if (currentStep === WizardSteps.REVIEW) {
            $('#nextBtn').hide();
            if (isReadOnly) {
                $('#updateBtn').hide();
            } else {
                $('#updateBtn').show();
            }
        } else {
            $('#nextBtn').show();
            $('#updateBtn').hide();
        }
    }

    /**
     * Disable all inputs for readonly mode
     */
    function disableAllInputs() {
        $('#wizardContent').find('input, textarea, select, button').prop('disabled', true);
        $('#prevBtn, #nextBtn').prop('disabled', false); // Allow navigation
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
    }

    /**
     * Show error message
     */
    function showError(message) {
        const errorHtml = `
            <div class="wizard-message error">
                <i class="fa fa-exclamation-triangle"></i>
                <h3>Error</h3>
                <p>${escapeHtml(message)}</p>
            </div>
        `;
        $('#wizardContent').html(errorHtml);
    }

    /**
     * Show success message
     */
    function showSuccess(message, profileName) {
        const successHtml = `
            <div class="wizard-message success">
                <i class="fa fa-check-circle"></i>
                <h3>Success!</h3>
                <p>${escapeHtml(message)}</p>
                <p>Redirecting to admin dashboard...</p>
            </div>
        `;
        $('#wizardContent').html(successHtml);
    }

    /**
     * Escape HTML for safe display
     */
    function escapeHtml(text) {
        if (typeof text !== 'string') return '';
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, m => map[m]);
    }

})(jQuery);
