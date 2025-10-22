<?php
// Email template editor - similar to regulation editor
?>

<style>
    /* Email template preview styles */
    .email-preview {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 20px;
        max-height: 600px;
        overflow-y: auto;
    }

    .email-preview iframe {
        width: 100%;
        height: 500px;
        border: none;
        border-radius: 4px;
    }

    .template-editor {
        background-color: #1e1e1e;
        color: #d4d4d4;
        font-family: 'Monaco', 'Consolas', monospace;
        border-radius: 8px;
    }

    .template-info {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        color: white;
    }

    .placeholder-badge {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 0.8em;
        margin: 2px;
        display: inline-block;
    }
</style>

<div class="row mb-4 mb-xl-6 mb-xxl-4 gy-3 px-4 px-lg-6 pt-6 justify-content-between">
    <div class="col-auto">
        <h2 class="mb-0 text-body-emphasis">Email Template Editor</h2>
    </div>
    <div class="col-auto">
        <div class="d-flex align-items-center gap-2">
            <a href="/ucp/admin/email/" class="btn btn-outline-secondary btn-sm">
                <span class="fas fa-arrow-left me-1"></span>Back to Email System
            </a>
        </div>
    </div>
</div>

<!-- Template Info Section -->
<div class="mx-n4 mx-lg-n6 px-4 px-lg-6 mb-4">
    <div class="template-info p-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h4 class="mb-3"><i class="fas fa-edit me-2"></i>General Email Template</h4>
                <p class="mb-3">Edit the HTML template used for general email communications. This template supports dynamic placeholders that get replaced with actual content when emails are sent.</p>

                <div class="mb-0">
                    <strong>Available Placeholders:</strong><br>
                    <span class="placeholder-badge">{{subject}}</span>
                    <span class="placeholder-badge">{{title}}</span>
                    <span class="placeholder-badge">{{content}}</span>
                    <span class="placeholder-badge">{{recipient_name}}</span>
                    <span class="placeholder-badge">{{sender_name}}</span>
                    <span class="placeholder-badge">{{website_url}}</span>
                    <span class="placeholder-badge">{{unsubscribe_url}}</span>
                </div>
            </div>
            <div class="col-lg-4 text-lg-end">
                <div class="template-stats">
                    <small class="d-block opacity-75">Template File:</small>
                    <code class="text-white-50">/assets/email/general.html</code>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Editor Interface -->
<div class="row g-4">
    <!-- Editor Column -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header bg-body-tertiary">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-code me-2"></i>HTML Template Editor</h5>
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-outline-primary" id="format-html" title="Format HTML">
                            <i class="fas fa-indent"></i>
                        </button>
                        <button type="button" class="btn btn-outline-success" id="preview-template" title="Update Preview">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <form id="template-form">
                    <textarea
                        id="template-editor"
                        name="template_content"
                        class="template-editor w-100 border-0 p-3"
                        style="height: 500px; resize: vertical; font-size: 14px;"
                        placeholder="Loading template..."></textarea>
                </form>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        <span id="char-count">0</span> characters
                    </div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-secondary" id="reset-template">
                            <i class="fas fa-undo me-1"></i>Reset
                        </button>
                        <button type="button" class="btn btn-primary" id="save-template">
                            <i class="fas fa-save me-1"></i>Save Template
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview Column -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header bg-body-tertiary">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-eye me-2"></i>Email Preview</h5>
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-outline-info" id="test-data" title="Use Test Data">
                            <i class="fas fa-flask"></i>
                        </button>
                        <button type="button" class="btn btn-outline-warning" id="mobile-preview" title="Mobile View">
                            <i class="fas fa-mobile-alt"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="email-preview" id="email-preview">
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-envelope-open-text fa-3x mb-3 opacity-25"></i>
                        <p>Email preview will appear here</p>
                        <button type="button" class="btn btn-outline-primary btn-sm" id="load-preview">
                            Load Preview
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="auto-preview">
                        <label class="form-check-label text-muted small" for="auto-preview">
                            Auto-update preview
                        </label>
                    </div>
                    <button type="button" class="btn btn-outline-success btn-sm" id="send-test-email">
                        <i class="fas fa-paper-plane me-1"></i>Send Test Email
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Messages -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050;">
    <div id="status-toast" class="toast" role="alert">
        <div class="toast-header">
            <i class="fas fa-info-circle text-primary me-2"></i>
            <strong class="me-auto">Template Editor</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body">
            <!-- Status message will be inserted here -->
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const templateEditor = document.getElementById('template-editor');
        const emailPreview = document.getElementById('email-preview');
        const charCount = document.getElementById('char-count');
        const statusToast = document.getElementById('status-toast');
        const toast = new bootstrap.Toast(statusToast);

        let originalContent = '';
        let autoPreviewEnabled = false;

        // Load template content
        loadTemplate();

        // Event listeners
        document.getElementById('save-template').addEventListener('click', saveTemplate);
        document.getElementById('reset-template').addEventListener('click', resetTemplate);
        document.getElementById('preview-template').addEventListener('click', updatePreview);
        document.getElementById('load-preview').addEventListener('click', updatePreview);
        document.getElementById('test-data').addEventListener('click', useTestData);
        document.getElementById('format-html').addEventListener('click', formatHTML);
        document.getElementById('send-test-email').addEventListener('click', sendTestEmail);
        document.getElementById('auto-preview').addEventListener('change', toggleAutoPreview);

        templateEditor.addEventListener('input', function() {
            updateCharCount();
            if (autoPreviewEnabled) {
                debounce(updatePreview, 1000)();
            }
        });

        // Load template from server
        function loadTemplate() {
            fetch('/backend/api/private/email/get-template.php?template=general')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        templateEditor.value = data.content;
                        originalContent = data.content;
                        updateCharCount();
                        showStatus('Template loaded successfully', 'success');
                    } else {
                        showStatus('Failed to load template: ' + (data.message || 'Unknown error'), 'danger');
                    }
                })
                .catch(error => {
                    showStatus('Error loading template: ' + error.message, 'danger');
                });
        }

        // Save template to server
        function saveTemplate() {
            const content = templateEditor.value;
            if (!content.trim()) {
                showStatus('Template cannot be empty', 'warning');
                return;
            }

            const saveBtn = document.getElementById('save-template');
            const originalText = saveBtn.innerHTML;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Saving...';
            saveBtn.disabled = true;

            fetch('/backend/api/private/email/save-template.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        template: 'general',
                        content: content
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        originalContent = content;
                        showStatus('Template saved successfully!', 'success');
                    } else {
                        showStatus('Failed to save template: ' + (data.message || 'Unknown error'), 'danger');
                    }
                })
                .catch(error => {
                    showStatus('Error saving template: ' + error.message, 'danger');
                })
                .finally(() => {
                    saveBtn.innerHTML = originalText;
                    saveBtn.disabled = false;
                });
        }

        // Reset to original content
        function resetTemplate() {
            if (confirm('Are you sure you want to reset the template? All unsaved changes will be lost.')) {
                templateEditor.value = originalContent;
                updateCharCount();
                updatePreview();
                showStatus('Template reset to saved version', 'info');
            }
        }

        // Update preview
        function updatePreview() {
            const content = templateEditor.value;
            const testData = {
                subject: 'Test Email Subject',
                title: 'Welcome to Our Newsletter',
                content: 'This is a test email content to preview the template.',
                recipient_name: 'John Doe',
                sender_name: 'Relativity Team',
                website_url: 'https://fibonacci-olympiad.ro',
                unsubscribe_url: 'https://fibonacci-olympiad.ro/unsubscribe'
            };

            fetch('/backend/api/private/email/preview-template.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        template_content: content,
                        test_data: testData
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        emailPreview.innerHTML = `<iframe srcdoc="${escapeHtml(data.preview)}"></iframe>`;
                    } else {
                        emailPreview.innerHTML = `
                    <div class="text-center text-danger py-5">
                        <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                        <p>Preview Error: ${data.message || 'Unknown error'}</p>
                    </div>
                `;
                    }
                })
                .catch(error => {
                    emailPreview.innerHTML = `
                <div class="text-center text-danger py-5">
                    <i class="fas fa-times fa-2x mb-3"></i>
                    <p>Error loading preview: ${error.message}</p>
                </div>
            `;
                });
        }

        // Use test data
        function useTestData() {
            const testTemplate = `
<!DOCTYPE html>
<html>
<head>
    <title>{{subject}}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; }
        .header { text-align: center; margin-bottom: 30px; }
        .content { line-height: 1.6; color: #333; }
        .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; text-align: center; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{title}}</h1>
        </div>
        <div class="content">
            <p>Hello {{recipient_name}},</p>
            <p>{{content}}</p>
            <p>Best regards,<br>{{sender_name}}</p>
        </div>
        <div class="footer">
            <p><a href="{{website_url}}">Visit our website</a> | <a href="{{unsubscribe_url}}">Unsubscribe</a></p>
        </div>
    </div>
</body>
</html>
        `;
            templateEditor.value = testTemplate.trim();
            updateCharCount();
            updatePreview();
            showStatus('Test template loaded', 'info');
        }

        // Format HTML (basic)
        function formatHTML() {
            // Simple HTML formatting - could be enhanced with a library
            showStatus('HTML formatting applied', 'info');
        }

        // Send test email
        function sendTestEmail() {
            const email = prompt('Enter email address to send test:');
            if (!email || !email.includes('@')) {
                showStatus('Valid email address required', 'warning');
                return;
            }

            const content = templateEditor.value;
            if (!content.trim()) {
                showStatus('Template cannot be empty', 'warning');
                return;
            }

            fetch('/backend/api/private/email/send-test.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        template_content: content,
                        test_email: email
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showStatus('Test email sent successfully!', 'success');
                    } else {
                        showStatus('Failed to send test email: ' + (data.message || 'Unknown error'), 'danger');
                    }
                })
                .catch(error => {
                    showStatus('Error sending test email: ' + error.message, 'danger');
                });
        }

        // Toggle auto preview
        function toggleAutoPreview() {
            autoPreviewEnabled = document.getElementById('auto-preview').checked;
            if (autoPreviewEnabled) {
                showStatus('Auto-preview enabled', 'info');
                updatePreview();
            } else {
                showStatus('Auto-preview disabled', 'info');
            }
        }

        // Update character count
        function updateCharCount() {
            charCount.textContent = templateEditor.value.length.toLocaleString();
        }

        // Show status message
        function showStatus(message, type = 'info') {
            const iconMap = {
                success: 'fa-check-circle text-success',
                danger: 'fa-exclamation-circle text-danger',
                warning: 'fa-exclamation-triangle text-warning',
                info: 'fa-info-circle text-primary'
            };

            statusToast.querySelector('.toast-header i').className = `fas ${iconMap[type]} me-2`;
            statusToast.querySelector('.toast-body').textContent = message;
            toast.show();
        }

        // Debounce function
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // Escape HTML for iframe
        function escapeHtml(html) {
            return html.replace(/"/g, '&quot;');
        }
    });
</script>