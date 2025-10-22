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

            <!-- EMAIL CONTENT EDITOR MARE CA LA REGULATIONS -->
            <div class="col-12">
                <div class="card border-0">
                    <div class="card-header bg-secondary text-white p-2">
                        <div class="d-flex align-items-center">
                            <h6 class="mb-0 me-auto">📝 EMAIL CONTENT - Just write your message!</h6>
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-success btn-sm" onclick="previewEmail()">
                                    👁️ Preview Email
                                </button>
                                <button type="button" class="btn btn-info btn-sm" onclick="clearContent()">
                                    �️ Clear Content
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0" style="background: #1a1a1a;">
                        <!-- EDITOR SIMPLU PENTRU CONȚINUT -->
                        <textarea id="email-content" name="content" style="height: 600px; width: 100%;">
Write your email content here! 

Your message will be automatically placed in the complete HTML email template with:
• All competition data from settings (dates, registration status, etc.)
• Professional email design with categories, dates, and contact info
• Responsive layout that works on all devices
• Complete footer with links and branding

Just focus on your message - the template handles the rest! ✨
                        </textarea>
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
    // JAVASCRIPT AUTOMAT PENTRU EMAIL EDITOR CA LA REGULATIONS!
    $(document).ready(function() {
        console.log('Email compose initialized - AUTO MODE');

        // AUTO-POPULARE date la început
        autoPopulateData();

        // TinyMCE MARE ca la regulations cu suport HTML complet
        if (typeof tinymce !== 'undefined') {
            tinymce.init({
                selector: '#email-content',
                license_key: 'gpl',
                height: 600,
                width: '100%',
                skin: 'oxide-dark',
                content_css: 'dark',
                menubar: 'file edit view insert format tools table help',
                plugins: 'advlist autolink lists link image charmap code fullscreen insertdatetime media table wordcount codesample visualchars',
                toolbar: 'undo redo | code | blocks fontsize | bold italic underline | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist | link image media | table | fullscreen | visualchars',
                branding: false,
                resize: true,
                min_height: 600,
                forced_root_block: false,
                force_br_newlines: false,
                force_p_newlines: false,
                convert_urls: false,
                block_formats: 'Paragraph=p; Header 1=h1; Header 2=h2; Header 3=h3; Header 4=h4; Header 5=h5; Header 6=h6; Preformatted=pre; Code=code',
                font_size_formats: '8pt 10pt 12pt 14pt 16pt 18pt 24pt 36pt 48pt',
                code_dialog_height: 400,
                code_dialog_width: 800,
                setup: function(editor) {
                    editor.on('init', function() {
                        console.log('TinyMCE initialized - HTML TEMPLATE MODE');
                    });

                    // Toolbar button pentru switch la view-ul HTML
                    editor.ui.registry.addButton('viewhtml', {
                        text: 'HTML',
                        tooltip: 'View HTML Source',
                        onAction: function() {
                            editor.execCommand('mceCodeEditor');
                        }
                    });
                }
            });
        }

        // Recipients toggle
        $('input[name="recipient_type"]').on('change', function() {
            if ($(this).val() === 'specific') {
                $('#specific-users').slideDown();
            } else {
                $('#specific-users').slideUp();
            }
        });

        // Form submit
        $('#email-form').on('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            // Adaugă action field
            formData.set('action', 'send');
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
                        showStatus('success', '✅ Email sent to ' + data.sent_count + ' recipients!');
                        setTimeout(() => window.location.href = '/ucp/admin/email/logs', 2000);
                    } else {
                        showStatus('error', '❌ Send failed: ' + data.message);
                    }
                })
                .catch(error => {
                    showStatus('error', '❌ Error: ' + error.message);
                });
        });
    });

    // AUTO-POPULARE date în funcție de perioada curentă
    function autoPopulateData() {
        const currentDate = new Date();
        const currentYear = currentDate.getFullYear();
        const nextYear = currentYear + 1;

        // Date AUTOMATE pentru Relativity Challenge
        const autoData = {
            subject: `Fibonacci Romania ${nextYear}`,
            preheader: 'Check event updates 24–48h before arrival.',
            headline: `Fibonacci Romania ${nextYear}`,
            event_dates: `27 Feb – 1 Mar ${nextYear}`,
            reg_status: currentDate.getMonth() < 10 ? 'Open' : 'TBA', // Dacă e înainte de noiembrie = Open
            reg_close: `15 Feb ${nextYear}`,
            cta_label: 'My Dashboard',
            cta_url: 'https://fibonacci-olympiad.ro/ucp',
            location: 'LTIAM, Buzău, Romania',
            categories: 'Sumo, Line Follower, Humanoid, Drag Race'
        };

        // Setează subject automat
        $('#subject').val(autoData.subject);

        console.log('Auto-populated data for year:', nextYear);
        return autoData;
    }

    // LOAD TEMPLATE HTML COMPLET cu date AUTOMATE
    function loadTemplateWithAutoData() {
        console.log('Loading COMPLETE HTML template...');
        showStatus('info', '📂 Loading complete HTML template...');

        const autoData = autoPopulateData();

        fetch('/backend/api/private/email/settings-template.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Set DIRECT template-ul HTML complet în editor
                    if (tinymce.get('email-content')) {
                        tinymce.get('email-content').setContent(data.content);
                    } else {
                        $('#email-content').val(data.content);
                    }

                    // Sync subject
                    $('#subject').val(autoData.subject);

                    showStatus('success', `✅ Complete HTML template loaded with ${data.year} data! Ready for visual editing.`);
                } else {
                    showStatus('error', '❌ Failed to load template: ' + data.message);
                }
            })
            .catch(error => {
                showStatus('error', '❌ Error loading template: ' + error.message);
            });
    } // UPDATE cu date noi automate
    function updateAutoData() {
        const autoData = autoPopulateData();
        let content = tinymce.get('email-content') ? tinymce.get('email-content').getContent() : $('#email-content').val();

        if (!content.trim()) {
            showStatus('error', '❌ Load template first!');
            return;
        }

        // Update toate datele automat
        content = content.replace(/Fibonacci Romania \d{4}/g, autoData.headline);
        content = content.replace(/\d{2} Feb – \d Mar \d{4}/g, autoData.event_dates);
        content = content.replace(/Register Now|Sign Up|Join Now|My Dashboard/g, autoData.cta_label);

        // Update în editor
        if (tinymce.get('email-content')) {
            tinymce.get('email-content').setContent(content);
        } else {
            $('#email-content').val(content);
        }

        $('#subject').val(autoData.subject);
        showStatus('success', `🔄 Updated with ${autoData.headline.split(' ').slice(-1)[0]} data!`);
    }

    // Clear content
    function clearContent() {
        if (tinymce.get('email-content')) {
            tinymce.get('email-content').setContent('');
        } else {
            $('#email-content').val('');
        }
        showStatus('info', '🗑️ Content cleared! Ready for new message.');
    }

    // Preview email
    function previewEmail() {
        const content = tinymce.get('email-content') ? tinymce.get('email-content').getContent() : $('#email-content').val();

        if (!content.trim()) {
            showStatus('error', '❌ No content to preview!');
            return;
        }

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