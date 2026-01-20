/**
 * Profile Admin Dashboard - Frontend JavaScript
 *
 * Handles profile management: listing, toggling, editing, deleting
 */

(function($) {
    'use strict';

    let profiles = [];
    let stats = {};
    let deleteTarget = null;
    let deleteCountdownTimer = null;

    /**
     * Initialize dashboard on page load
     */
    $(document).ready(function() {
        console.log('Profile Admin Dashboard initialized');

        // Load all profiles
        loadAllProfiles();

        // Attach event handlers
        $('#refresh-btn').on('click', loadAllProfiles);
        $('#cancel-delete').on('click', closeDeleteModal);
        $('#confirm-delete').on('click', confirmDelete);
        $('#import-btn').on('click', openImportModal);
        $('#cancel-import').on('click', closeImportModal);
        $('#validate-import').on('click', validateImportFile);
        $('#import-form').on('submit', handleImportSubmit);

        // Close modals on background click
        $('#delete-modal').on('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });
        $('#import-modal').on('click', function(e) {
            if (e.target === this) {
                closeImportModal();
            }
        });
    });

    /**
     * Load all profiles via AJAX
     */
    function loadAllProfiles() {
        showLoading();

        $.post('profile-admin-handler.php', {
            action: 'list_all_profiles'
        }, function(response) {
            if (response.success) {
                profiles = response.profiles;
                stats = response.stats;

                console.log('Loaded profiles:', profiles.length);

                updateStatistics();
                renderProfileGrid();
            } else {
                showError(response.error || 'Failed to load profiles');
            }
        }, 'json').fail(function() {
            showError('Server error occurred while loading profiles');
        });
    }

    /**
     * Update statistics bar (removed from UI)
     */
    function updateStatistics() {
        // Stats bar removed to save screen space
        // Stats are still tracked internally if needed
    }

    /**
     * Render profile grid
     */
    function renderProfileGrid() {
        const $grid = $('#profile-grid');
        const $loading = $('#loading-indicator');
        const $empty = $('#empty-state');

        $loading.hide();

        if (profiles.length === 0) {
            $grid.hide();
            $empty.show();
            return;
        }

        $empty.hide();
        $grid.empty().show();

        profiles.forEach(function(profile) {
            const $card = renderProfileCard(profile);
            $grid.append($card);
        });

        // Attach event handlers to cards
        attachCardEventHandlers();
    }

    /**
     * Render individual profile card
     */
    function renderProfileCard(profile) {
        const isProtected = profile.protected;
        const isEnabled = profile.enabled;
        const hasJson = profile.json_exists;

        const $card = $('<div>')
            .addClass('admin-profile-card')
            .addClass(isProtected ? 'protected' : 'custom')
            .attr('data-profile', profile.name);

        // Card Header
        const $header = $('<div>').addClass('card-header');
        const $title = $('<h3>').text(profile.display_name);
        const $badges = $('<div>').addClass('badges');

        const $enabledBadge = $('<span>')
            .addClass('badge')
            .addClass(isEnabled ? 'badge-success' : 'badge-secondary')
            .text(isEnabled ? 'Enabled' : 'Disabled');

        const $typeBadge = $('<span>')
            .addClass('badge')
            .addClass(isProtected ? 'badge-warning' : 'badge-info')
            .text(isProtected ? 'Protected' : 'Custom');

        $badges.append($enabledBadge, $typeBadge);
        $header.append($title, $badges);

        // Card Body
        const $body = $('<div>').addClass('card-body');
        $body.append($('<p>').html('<strong>Internal Name:</strong> ' + escapeHtml(profile.name)));

        const jsonStatus = hasJson ?
            '<i class="fa fa-check-circle text-success"></i> Exists' :
            '<i class="fa fa-exclamation-triangle text-danger"></i> Missing';
        $body.append($('<p>').html('<strong>JSON File:</strong> ' + jsonStatus));

        // Card Footer
        const $footer = $('<div>').addClass('card-footer');

        // Toggle Switch (disabled for protected profiles)
        const $toggle = $('<label>').addClass('toggle-switch');
        const $toggleInput = $('<input>')
            .attr('type', 'checkbox')
            .addClass('profile-toggle')
            .attr('data-profile', profile.name)
            .prop('checked', isEnabled)
            .prop('disabled', isProtected);

        const $slider = $('<span>').addClass('slider');
        const $toggleLabel = $('<span>').addClass('toggle-label').text('Enabled');

        $toggle.append($toggleInput, $slider, $toggleLabel);

        // Action Buttons
        const $actions = $('<div>').addClass('action-buttons');

        if (isProtected) {
            // Protected profiles can only be viewed and exported
            const $viewBtn = $('<button>')
                .addClass('btn-view')
                .attr('data-profile', profile.name)
                .html('<i class="fa fa-eye"></i> View');

            const $exportBtn = $('<button>')
                .addClass('btn-export')
                .attr('data-profile', profile.name)
                .html('<i class="fa fa-download"></i> Export');

            $actions.append($viewBtn, $exportBtn);
        } else {
            // Custom profiles can be edited, exported, and deleted
            const $editBtn = $('<button>')
                .addClass('btn-edit')
                .attr('data-profile', profile.name)
                .html('<i class="fa fa-edit"></i> Edit');

            const $exportBtn = $('<button>')
                .addClass('btn-export')
                .attr('data-profile', profile.name)
                .html('<i class="fa fa-download"></i> Export');

            const $deleteBtn = $('<button>')
                .addClass('btn-delete')
                .attr('data-profile', profile.name)
                .html('<i class="fa fa-trash"></i> Delete');

            $actions.append($editBtn, $exportBtn, $deleteBtn);
        }

        $footer.append($toggle, $actions);

        // Assemble card
        $card.append($header, $body, $footer);

        return $card;
    }

    /**
     * Attach event handlers to card elements
     */
    function attachCardEventHandlers() {
        // Toggle enabled status
        $('.profile-toggle').on('change', function() {
            const profileName = $(this).attr('data-profile');
            const newStatus = $(this).is(':checked');
            handleToggleEnabled(profileName, newStatus, $(this));
        });

        // Edit button
        $('.btn-edit').on('click', function() {
            const profileName = $(this).attr('data-profile');
            handleEdit(profileName);
        });

        // View button
        $('.btn-view').on('click', function() {
            const profileName = $(this).attr('data-profile');
            handleView(profileName);
        });

        // Export button
        $('.btn-export').on('click', function() {
            const profileName = $(this).attr('data-profile');
            handleExport(profileName);
        });

        // Delete button
        $('.btn-delete').on('click', function() {
            const profileName = $(this).attr('data-profile');
            handleDelete(profileName);
        });
    }

    /**
     * Handle toggle enabled status
     */
    function handleToggleEnabled(profileName, newStatus, $toggle) {
        // Optimistically update UI
        const $card = $('.admin-profile-card[data-profile="' + profileName + '"]');
        const $badge = $card.find('.badge-success, .badge-secondary');

        $.post('profile-admin-handler.php', {
            action: 'toggle_enabled',
            profile_name: profileName
        }, function(response) {
            if (response.success) {
                // Update badge
                $badge.removeClass('badge-success badge-secondary');
                $badge.addClass(response.new_status ? 'badge-success' : 'badge-secondary');
                $badge.text(response.new_status ? 'Enabled' : 'Disabled');

                // Update stats
                if (response.new_status) {
                    stats.enabled++;
                    stats.disabled--;
                } else {
                    stats.enabled--;
                    stats.disabled++;
                }
                updateStatistics();

                // Show notification
                showNotification(response.message, 'success');
            } else {
                // Revert toggle on error
                $toggle.prop('checked', !newStatus);
                showNotification(response.error || 'Failed to toggle profile', 'error');
            }
        }, 'json').fail(function() {
            // Revert toggle on error
            $toggle.prop('checked', !newStatus);
            showNotification('Server error occurred', 'error');
        });
    }

    /**
     * Handle edit button click
     */
    function handleEdit(profileName) {
        window.location.href = 'profile-editor.php?profile=' + encodeURIComponent(profileName) + '&mode=edit';
    }

    /**
     * Handle view button click
     */
    function handleView(profileName) {
        window.location.href = 'profile-editor.php?profile=' + encodeURIComponent(profileName) + '&mode=readonly';
    }

    /**
     * Handle delete button click
     */
    function handleDelete(profileName) {
        const profile = profiles.find(p => p.name === profileName);
        if (!profile) {
            showNotification('Profile not found', 'error');
            return;
        }

        deleteTarget = profileName;

        // Update modal content
        $('#delete-message').html(
            'Are you sure you want to permanently delete the profile <strong>' +
            escapeHtml(profile.display_name) + '</strong>?'
        );

        $('#delete-profile-details').html(
            '<p><strong>Internal Name:</strong> ' + escapeHtml(profile.name) + '</p>' +
            '<p><strong>JSON File:</strong> ' + (profile.json_exists ? 'Will be deleted' : 'Not found') + '</p>' +
            '<p class="warning-text"><i class="fa fa-exclamation-triangle"></i> This action cannot be undone!</p>'
        );

        // Show modal
        $('#delete-modal').fadeIn(200);
    }

    /**
     * Confirm delete action
     */
    function confirmDelete() {
        if (!deleteTarget) {
            return;
        }

        $('#confirm-delete').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Deleting...');

        $.post('profile-admin-handler.php', {
            action: 'delete_profile',
            profile_name: deleteTarget
        }, function(response) {
            if (response.success) {
                // Show success message
                showNotification(response.message, 'success');

                // Close modal
                closeDeleteModal();

                // Start countdown and auto-reload
                startDeleteCountdown();
            } else {
                showNotification(response.error || 'Failed to delete profile', 'error');
                $('#confirm-delete').prop('disabled', false).html('<i class="fa fa-trash"></i> Delete');
            }
        }, 'json').fail(function() {
            showNotification('Server error occurred during deletion', 'error');
            $('#confirm-delete').prop('disabled', false).html('<i class="fa fa-trash"></i> Delete');
        });
    }

    /**
     * Start countdown and auto-reload after deletion
     */
    function startDeleteCountdown() {
        let countdown = 3;
        const $toast = $('#notification-toast .toast-content span');

        deleteCountdownTimer = setInterval(function() {
            countdown--;
            if (countdown > 0) {
                $toast.text('Profile deleted. Refreshing in ' + countdown + '...');
            } else {
                clearInterval(deleteCountdownTimer);
                window.location.reload();
            }
        }, 1000);
    }

    /**
     * Close delete modal
     */
    function closeDeleteModal() {
        $('#delete-modal').fadeOut(200);
        deleteTarget = null;
        $('#confirm-delete').prop('disabled', false).html('<i class="fa fa-trash"></i> Delete');
    }

    /**
     * Show loading state
     */
    function showLoading() {
        $('#loading-indicator').show();
        $('#profile-grid').hide();
        $('#empty-state').hide();
    }

    /**
     * Show error message
     */
    function showError(message) {
        $('#loading-indicator').hide();
        $('#profile-grid').html(
            '<div class="error-message">' +
            '<i class="fa fa-exclamation-triangle"></i>' +
            '<h3>Error</h3>' +
            '<p>' + escapeHtml(message) + '</p>' +
            '</div>'
        ).show();
    }

    /**
     * Show notification toast
     */
    function showNotification(message, type) {
        const $toast = $('#notification-toast');
        const $icon = $toast.find('i');

        // Update icon based on type
        $icon.removeClass('fa-check-circle fa-exclamation-triangle');
        $toast.removeClass('success error');

        if (type === 'success') {
            $icon.addClass('fa-check-circle');
            $toast.addClass('success');
        } else {
            $icon.addClass('fa-exclamation-triangle');
            $toast.addClass('error');
        }

        // Update message
        $toast.find('#toast-message').text(message);

        // Show toast
        $toast.addClass('show');

        // Hide after 5 seconds (unless it's a delete countdown)
        if (!deleteCountdownTimer) {
            setTimeout(function() {
                $toast.removeClass('show');
            }, 5000);
        }
    }

    /**
     * Handle export button click
     */
    function handleExport(profileName) {
        const profile = profiles.find(p => p.name === profileName);
        if (!profile) {
            showNotification('Profile not found', 'error');
            return;
        }

        // Show notification that download is starting
        showNotification('Exporting profile: ' + profile.display_name, 'success');

        // Create a hidden form and submit it to trigger download
        const $form = $('<form>')
            .attr('method', 'POST')
            .attr('action', 'profile-admin-handler.php')
            .attr('target', '_blank');

        $form.append($('<input>').attr('type', 'hidden').attr('name', 'action').val('export_profile'));
        $form.append($('<input>').attr('type', 'hidden').attr('name', 'profile_name').val(profileName));

        $('body').append($form);
        $form.submit();
        $form.remove();
    }

    /**
     * Open import modal
     */
    function openImportModal() {
        // Reset form
        $('#import-form')[0].reset();
        $('#import-validation-result').hide().html('');

        // Show modal
        $('#import-modal').fadeIn(200);
    }

    /**
     * Close import modal
     */
    function closeImportModal() {
        $('#import-modal').fadeOut(200);
        $('#import-form')[0].reset();
        $('#import-validation-result').hide().html('');
        $('#confirm-import').prop('disabled', false).html('<i class="fa fa-upload"></i> Import');
    }

    /**
     * Validate import file before uploading
     */
    function validateImportFile() {
        const fileInput = $('#profile-file')[0];
        if (!fileInput.files || fileInput.files.length === 0) {
            showNotification('Please select a file to validate', 'error');
            return;
        }

        const formData = new FormData();
        formData.append('action', 'validate_import');
        formData.append('profile_file', fileInput.files[0]);

        const customName = $('#custom-name').val().trim();
        if (customName) {
            formData.append('custom_name', customName);
        }

        $('#validate-import').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Validating...');

        $.ajax({
            url: 'profile-admin-handler.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                displayValidationResult(response);
                $('#validate-import').prop('disabled', false).html('<i class="fa fa-check"></i> Validate');
            },
            error: function() {
                showNotification('Server error during validation', 'error');
                $('#validate-import').prop('disabled', false).html('<i class="fa fa-check"></i> Validate');
            }
        });
    }

    /**
     * Display validation result
     */
    function displayValidationResult(result) {
        const $resultDiv = $('#import-validation-result');
        $resultDiv.empty();

        if (result.valid) {
            // Valid profile
            $resultDiv.addClass('validation-success').removeClass('validation-error');
            $resultDiv.html(
                '<i class="fa fa-check-circle"></i> <strong>Validation Successful</strong>' +
                '<p><strong>Profile Name:</strong> ' + escapeHtml(result.profile_name) + '</p>' +
                '<p><strong>Domains:</strong> ' + result.domains + '</p>' +
                '<p><strong>Capabilities:</strong> ' + result.capabilities + '</p>' +
                '<p><strong>File Size:</strong> ' + formatBytes(result.size) + '</p>' +
                (result.exists ? '<p class="warning-text"><i class="fa fa-exclamation-triangle"></i> Profile already exists. Check "Overwrite" to replace it.</p>' : '') +
                (result.protected ? '<p class="error-text"><i class="fa fa-ban"></i> This is a protected profile and cannot be imported.</p>' : '')
            );
        } else {
            // Invalid profile
            $resultDiv.addClass('validation-error').removeClass('validation-success');
            $resultDiv.html(
                '<i class="fa fa-exclamation-triangle"></i> <strong>Validation Failed</strong>' +
                '<p>' + escapeHtml(result.message) + '</p>'
            );
        }

        $resultDiv.show();
    }

    /**
     * Handle import form submission
     */
    function handleImportSubmit(e) {
        e.preventDefault();

        const fileInput = $('#profile-file')[0];
        if (!fileInput.files || fileInput.files.length === 0) {
            showNotification('Please select a file to import', 'error');
            return;
        }

        const formData = new FormData();
        formData.append('action', 'import_profile');
        formData.append('profile_file', fileInput.files[0]);

        const customName = $('#custom-name').val().trim();
        if (customName) {
            formData.append('custom_name', customName);
        }

        const displayName = $('#display-name').val().trim();
        if (displayName) {
            formData.append('display_name', displayName);
        }

        const overwrite = $('#overwrite-existing').is(':checked');
        formData.append('overwrite', overwrite ? 'true' : 'false');

        const enabled = $('#enable-profile').is(':checked');
        formData.append('enabled', enabled ? 'true' : 'false');

        $('#confirm-import').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Importing...');

        $.ajax({
            url: 'profile-admin-handler.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    showNotification(response.message, 'success');
                    closeImportModal();

                    // Reload profiles after 2 seconds
                    setTimeout(function() {
                        window.location.reload();
                    }, 2000);
                } else {
                    showNotification(response.error || 'Import failed', 'error');
                    $('#confirm-import').prop('disabled', false).html('<i class="fa fa-upload"></i> Import');
                }
            },
            error: function() {
                showNotification('Server error during import', 'error');
                $('#confirm-import').prop('disabled', false).html('<i class="fa fa-upload"></i> Import');
            }
        });
    }

    /**
     * Format bytes to human-readable size
     */
    function formatBytes(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
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
