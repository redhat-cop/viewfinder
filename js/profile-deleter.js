/**
 * Profile Deleter - Frontend JavaScript
 *
 * Handles profile listing, selection, and deletion
 */

(function($) {
    'use strict';

    /**
     * Initialize deleter on page load
     */
    $(document).ready(function() {
        console.log('Profile Deleter initialized');
        loadProfiles();

        // Attach delete button handler
        $('#deleteBtn').on('click', handleDelete);
    });

    /**
     * Load list of deletable profiles
     */
    function loadProfiles() {
        showLoading('Loading profiles...');

        $.post('profile-deleter-handler.php', {
            action: 'list_profiles'
        }, function(response) {
            if (response.success) {
                displayProfiles(response.profiles);
            } else {
                showError('Failed to load profiles: ' + response.error);
            }
        }, 'json').fail(function() {
            showError('Server error occurred while loading profiles');
        });
    }

    /**
     * Display profiles in the list
     */
    function displayProfiles(profiles) {
        const container = $('#profileList');

        if (profiles.length === 0) {
            container.html(`
                <div class="no-profiles">
                    <i class="fa fa-info-circle fa-3x"></i>
                    <p>No custom profiles available to delete.</p>
                    <p>Core profiles (Security, Digital Sovereignty, Template) are protected and cannot be deleted.</p>
                    <a href="profile-creator.php" class="ui-button ui-widget ui-corner-all">
                        <i class="fa fa-plus"></i> Create New Profile
                    </a>
                </div>
            `);
            $('#deleteBtn').prop('disabled', true);
            return;
        }

        let html = '<div class="profile-cards">';

        profiles.forEach(function(profile) {
            const statusBadge = profile.enabled
                ? '<span class="badge badge-success">Enabled</span>'
                : '<span class="badge badge-secondary">Disabled</span>';

            const jsonStatus = profile.json_exists
                ? '<i class="fa fa-check-circle text-success"></i> JSON file exists'
                : '<i class="fa fa-exclamation-triangle text-warning"></i> JSON file missing';

            html += `
                <div class="profile-card" data-profile="${escapeHtml(profile.name)}">
                    <div class="profile-card-header">
                        <input type="radio" name="selected_profile" value="${escapeHtml(profile.name)}" id="profile_${escapeHtml(profile.name)}">
                        <label for="profile_${escapeHtml(profile.name)}">
                            <h3>${escapeHtml(profile.display_name)}</h3>
                        </label>
                    </div>
                    <div class="profile-card-body">
                        <p><strong>Internal Name:</strong> ${escapeHtml(profile.name)}</p>
                        <p><strong>Status:</strong> ${statusBadge}</p>
                        <p><strong>File:</strong> ${jsonStatus}</p>
                    </div>
                </div>
            `;
        });

        html += '</div>';
        container.html(html);

        // Enable delete button when a profile is selected
        $('input[name="selected_profile"]').on('change', function() {
            $('#deleteBtn').prop('disabled', false);
        });

        $('#deleteBtn').prop('disabled', true);
    }

    /**
     * Handle delete button click
     */
    function handleDelete() {
        const selectedProfile = $('input[name="selected_profile"]:checked').val();

        if (!selectedProfile) {
            showError('Please select a profile to delete');
            return;
        }

        // Get display name for confirmation
        const displayName = $(`#profile_${selectedProfile}`).find('h3').text();

        // Confirm deletion
        const confirmMessage = `Are you sure you want to delete the profile "${displayName}"?\n\n` +
                              `This will:\n` +
                              `• Delete the controls-${selectedProfile}.json file\n` +
                              `• Remove the profile from Config.php\n` +
                              `• Remove the navigation button from index.php\n\n` +
                              `This action cannot be easily undone.`;

        if (!confirm(confirmMessage)) {
            return;
        }

        // Disable delete button
        $('#deleteBtn').prop('disabled', true).text('Deleting...');
        showLoading('Deleting profile and updating configuration...');

        $.post('profile-deleter-handler.php', {
            action: 'delete',
            profile_name: selectedProfile
        }, function(response) {
            if (response.success) {
                showSuccess(response.message, selectedProfile);
            } else {
                showError(response.error || 'Deletion failed');
                $('#deleteBtn').prop('disabled', false).text('Delete Selected Profile');
            }
        }, 'json').fail(function() {
            showError('Server error occurred during deletion');
            $('#deleteBtn').prop('disabled', false).text('Delete Selected Profile');
        });
    }

    /**
     * Show loading message
     */
    function showLoading(message) {
        $('#profileList').html(`
            <div class="loading-container">
                <div class="spinner"></div>
                <p>${message}</p>
            </div>
        `);
    }

    /**
     * Show success message
     */
    function showSuccess(message, profileName) {
        let countdown = 5;

        function updateCountdown() {
            $('#countdown-timer').text(countdown);
            if (countdown > 0) {
                countdown--;
                setTimeout(updateCountdown, 1000);
            } else {
                location.reload();
            }
        }

        $('#profileList').html(`
            <div class="result-container success">
                <i class="fa fa-check-circle fa-4x"></i>
                <h2>Success!</h2>
                <p>${escapeHtml(message)}</p>
                <p style="color: #12bbd4; margin-top: 1.5rem;">
                    Refreshing in <span id="countdown-timer">5</span> seconds...
                </p>
                <div class="result-actions">
                    <button onclick="location.reload()" class="ui-button ui-widget ui-corner-all">
                        <i class="fa fa-refresh"></i> Refresh Now
                    </button>
                    <a href="index.php" class="ui-button ui-widget ui-corner-all">
                        <i class="fa fa-home"></i> Return to Home
                    </a>
                    <a href="profile-creator.php" class="ui-button ui-widget ui-corner-all">
                        <i class="fa fa-plus"></i> Create New Profile
                    </a>
                </div>
            </div>
        `);
        $('#deleteBtn').hide();

        // Start countdown
        setTimeout(updateCountdown, 1000);
    }

    /**
     * Show error message
     */
    function showError(message) {
        $('<div class="alert alert-danger">' + escapeHtml(message) + '</div>')
            .prependTo('#profileList')
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

})(jQuery);
