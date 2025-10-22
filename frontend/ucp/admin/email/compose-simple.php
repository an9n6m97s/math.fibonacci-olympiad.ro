<?php
// SIMPLU COMPOSER DE EMAIL - FARA COMPLICATII!
$managers = getAllManagers();
$members = getAllMembers();
$allUsers = getAllUsers();

$combinedUsers = [];
$emailsSeen = [];
foreach ($allUsers as $user) {
    if (!in_array($user['email'], $emailsSeen)) {
        $combinedUsers[] = $user;
        $emailsSeen[] = $user['email'];
    }
}
?>

<div class="row mb-4 px-4 px-lg-6 pt-6">
    <div class="col-auto">
        <h2 class="mb-0">📧 Compose Email</h2>
    </div>
    <div class="col-auto ms-auto">
        <a href="/ucp/admin/email/logs" class="btn btn-outline-secondary btn-sm">📋 Email Logs</a>
    </div>
</div>

<div class="px-4 px-lg-6 mb-9">
    <form id="email-form" method="post">
        <div class="row g-3">

            <!-- Subject -->
            <div class="col-12">
                <label class="form-label fw-bold">Subject</label>
                <input type="text" name="subject" id="subject" class="form-control form-control-lg"
                    placeholder="Email subject..." required>
            </div>

            <!-- Recipients -->
            <div class="col-12">
                <label class="form-label fw-bold">Send to</label>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-check">
                            <input type="radio" name="recipient_type" value="all" id="all-users" class="form-check-input" checked>
                            <label class="form-check-label" for="all-users">🌍 All Users (<?= count($combinedUsers) ?>)</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input type="radio" name="recipient_type" value="managers" id="managers" class="form-check-input">
                            <label class="form-check-label" for="managers">👥 Managers (<?= count($managers) ?>)</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input type="radio" name="recipient_type" value="members" id="members" class="form-check-input">
                            <label class="form-check-label" for="members">🎯 Members (<?= count($members) ?>)</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input type="radio" name="recipient_type" value="specific" id="specific" class="form-check-input">
                            <label class="form-check-label" for="specific">✅ Specific Users</label>
                        </div>
                    </div>
                </div>

                <div id="specific-users" class="mt-3" style="display: none;">
                    <select name="specific_users[]" class="form-control" multiple size="6">
                        <?php foreach ($combinedUsers as $user): ?>
                            <option value="<?= $user['email'] ?>"><?= htmlspecialchars($user['full_name']) ?> (<?= $user['email'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                    <small class="text-muted">Hold Ctrl/Cmd to select multiple users</small>
                </div>
            </div>

            <!-- TEMPLATE EDITOR SIMPLU -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-dark text-white">
                        <div class="row align-items-center">
                            <div class="col">
                                <h5 class="mb-0">📝 Email Template Editor</h5>
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-sm btn-primary" onclick="loadTemplate()">
                                    📂 Load Template
                                </button>
                                <button type="button" class="btn btn-sm btn-success" onclick="previewEmail()">
                                    👁️ Preview
                                </button>
                                <button type="button" class="btn btn-sm btn-info" onclick="toggleQuickEdit()">
                                    ⚙️ Quick Edit
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-1">
                        <div class="row">
                            <!-- Editor Principal -->
                            <div class="col-lg-9">
                                <textarea id="email-content" name="content" style="height: 500px;">
Click "Load Template" to load the email template for direct editing!
                                </textarea>
                            </div>

                            <!-- Quick Edit Panel -->
                            <div class="col-lg-3" id="quick-edit-panel" style="display: none;">
                                <div class="p-3 bg-light h-100">
                                    <h6 class="fw-bold">⚙️ Quick Settings</h6>

                                    <div class="mb-2">
                                        <label class="form-label small fw-bold">Subject:</label>
                                        <input type="text" class="form-control form-control-sm" id="q-subject"
                                            value="Relativity Challenge 2026">
                                    </div>

                                    <div class="mb-2">
                                        <label class="form-label small fw-bold">Event Dates:</label>
                                        <input type="text" class="form-control form-control-sm" id="q-dates"
                                            value="27 Feb – 1 Mar 2026">
                                    </div>

                                    <div class="mb-2">
                                        <label class="form-label small fw-bold">Registration:</label>
                                        <select class="form-control form-control-sm" id="q-reg">
                                            <option value="Open">Open</option>
                                            <option value="Closed">Closed</option>
                                            <option value="TBA">TBA</option>
                                        </select>
                                    </div>

                                    <div class="mb-2">
                                        <label class="form-label small fw-bold">CTA Button:</label>
                                        <input type="text" class="form-control form-control-sm" id="q-cta"
                                            value="Register Now">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Main Content:</label>
                                        <textarea class="form-control form-control-sm" id="q-content" rows="6">Welcome to the Fibonacci Romania 2026!

We're excited to announce this year's competition with cutting-edge robotics challenges and innovative competition formats.

Key Information:
• Dates: 27 Feb – 1 Mar 2026  
• Location: LTIAM, Buzău, Romania
• Categories: Sumo, Line Follower, Humanoid, Drag Race

Best regards,
The Relativity Team</textarea>
                                    </div>

                                    <button type="button" class="btn btn-warning w-100 btn-sm" onclick="applyQuickEdit()">
                                        🔄 Apply Changes
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="col-12">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">
                        📤 Send Email
                    </button>
                    <a href="/ucp/admin" class="btn btn-outline-secondary">❌ Cancel</a>
                </div>
            </div>

            <!-- Status Messages -->
            <div class="col-12">
                <div id="status-alert" style="display: none;"></div>
            </div>
        </div>
    </form>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">👁️ Email Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div id="preview-content" style="height: 70vh; overflow-y: auto;">
                    Loading preview...
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" onclick="sendFromPreview()">📤 Send This Email</button>
            </div>
        </div>
    </div>
</div>

<script>
    // JAVASCRIPT SIMPLU SI FUNCTIONAL!
    $(document).ready(function() {

        // TinyMCE simplu
        if (typeof tinymce !== 'undefined') {
            tinymce.init({
                selector: '#email-content',
                license_key: 'gpl',
                height: 500,
                menubar: 'edit view insert format tools',
                plugins: 'advlist autolink lists link image charmap code fullscreen table',
                toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | bullist numlist | link image | code fullscreen'
            });
        }

        // Recipients toggle
        $('input[name="recipient_type"]').on('change', function() {
            if ($(this).val() === 'specific') {
                $('#specific-users').show();
            } else {
                $('#specific-users').hide();
            }
        });

        // Form submit
        $('#email-form').on('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            if (tinymce.get('email-content')) {
                formData.set('content', tinymce.get('email-content').getContent());
            }

            showStatus('info', '📤 Sending email...');

            fetch('/backend/api/private/email/compose.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showStatus('success', '✅ Email sent successfully to ' + data.sent_count + ' recipients!');
                        setTimeout(() => window.location.href = '/ucp/admin/email/logs', 2000);
                    } else {
                        showStatus('error', '❌ Failed to send: ' + data.message);
                    }
                })
                .catch(error => {
                    showStatus('error', '❌ Error: ' + error.message);
                });
        });
    });

    // Load template function
    function loadTemplate() {
        fetch('/backend/api/private/email/simple-template.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (tinymce.get('email-content')) {
                        tinymce.get('email-content').setContent(data.content);
                    } else {
                        $('#email-content').val(data.content);
                    }
                    showStatus('success', '✅ Template loaded! You can now edit it directly.');
                } else {
                    showStatus('error', '❌ Failed to load template: ' + data.message);
                }
            })
            .catch(error => {
                showStatus('error', '❌ Error: ' + error.message);
            });
    }

    // Toggle quick edit panel
    function toggleQuickEdit() {
        $('#quick-edit-panel').slideToggle();
    }

    // Apply quick edit changes
    function applyQuickEdit() {
        let content = tinymce.get('email-content') ? tinymce.get('email-content').getContent() : $('#email-content').val();

        // Apply changes
        content = content.replace(/\{\{subject[^}]*\}\}/g, $('#q-subject').val());
        content = content.replace(/\{\{headline[^}]*\}\}/g, $('#q-subject').val());
        content = content.replace(/\{\{event_dates[^}]*\}\}/g, $('#q-dates').val());
        content = content.replace(/\{\{reg_status[^}]*\}\}/g, $('#q-reg').val());
        content = content.replace(/\{\{cta_label[^}]*\}\}/g, $('#q-cta').val());
        content = content.replace(/\{\{main_content_html[^}]*\}\}/g, $('#q-content').val().replace(/\n/g, '<br>'));

        // Update editor and sync subject
        if (tinymce.get('email-content')) {
            tinymce.get('email-content').setContent(content);
        } else {
            $('#email-content').val(content);
        }
        $('#subject').val($('#q-subject').val());

        showStatus('success', '✅ Quick changes applied!');
    }

    // Preview email
    function previewEmail() {
        const content = tinymce.get('email-content') ? tinymce.get('email-content').getContent() : $('#email-content').val();
        $('#preview-content').html(`<iframe srcdoc="${content.replace(/"/g, '&quot;')}" style="width:100%;height:100%;border:none;"></iframe>`);
        $('#previewModal').modal('show');
    }

    // Send from preview
    function sendFromPreview() {
        $('#previewModal').modal('hide');
        $('#email-form').submit();
    }

    // Status messages
    function showStatus(type, message) {
        const colors = {
            'info': 'alert-info',
            'success': 'alert-success',
            'error': 'alert-danger'
        };

        $('#status-alert')
            .removeClass('alert-info alert-success alert-danger')
            .addClass('alert ' + colors[type])
            .html(message)
            .show();

        if (type === 'success') {
            setTimeout(() => $('#status-alert').fadeOut(), 5000);
        }
    }
</script>