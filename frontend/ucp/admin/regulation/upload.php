<?php
$categories = getCategories();
?>

<section class="team-detail_form">
    <div class="auto-container mt-5">
        <div class="row clearfix">

            <div class="column col-lg-12 col-md-12 col-sm-12">
                <div class="default-form contact-form">
                    <div class="mb-4">
                        <h3>Add New Regulation</h3>
                        <p>Create regulation content for a category.</p>

                        <!-- Quick Navigation -->
                        <div class="d-flex gap-3 mb-4">
                            <a href="/ucp/admin/regulation/view" class="template-btn btn-style-four">
                                <span class="btn-wrap">
                                    <span class="text-one">View Regulations</span>
                                    <span class="text-two">View Regulations</span>
                                </span>
                            </a>
                            <a href="/ucp/admin/regulation/edit" class="template-btn btn-style-four">
                                <span class="btn-wrap">
                                    <span class="text-one">Edit Regulations</span>
                                    <span class="text-two">Edit Regulations</span>
                                </span>
                            </a>
                        </div>
                    </div>

                    <form method="post" id="create-regulation-form" enctype="multipart/form-data">
                        <div class="row clearfix">

                            <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                <select name="category" id="category" class="custom-select-box" data-validation="required">
                                    <option value="" selected disabled>Regulation for Category*</option>
                                    <?php foreach ($categories as $category) : ?>
                                        <option value="<?= htmlspecialchars($category['slug']) ?>">
                                            <?= htmlspecialchars($category['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Editor TinyMCE -->
                            <div class="form-group col-lg-12">
                                <label for="regulation-editor" class="sec-title_heading h5">Content Regulation:</label>
                                <textarea id="regulation-editor" name="content"></textarea>
                            </div>

                            <!-- Submit -->
                            <div class="form-group col-lg-12">
                                <button type="submit" class="template-btn btn-style-one" id="submit-btn">
                                    <span class="btn-wrap">
                                        <span class="text-one">Add regulation</span>
                                        <span class="text-two">Add regulation</span>
                                    </span>
                                </button>
                            </div>

                            <!-- Status Messages -->
                            <div class="form-group col-lg-12">
                                <div class="loading-indicator" id="loading-message" style="display: none; color: #ffa500; font-weight: bold;">
                                    <i class="fa fa-spinner fa-spin"></i> Adding regulation...
                                </div>
                                <div class="success-message" id="success-message" style="display: none; color: #28a745; font-weight: bold;">
                                    <i class="fa fa-check"></i> <span id="success-text">Regulation added successfully!</span>
                                </div>
                                <div class="error-message" id="error-message" style="display: none; color: #dc3545; font-weight: bold;">
                                    <i class="fa fa-exclamation-triangle"></i> <span id="error-text">Error adding regulation.</span>
                                </div>
                            </div>

                        </div>
                    </form>

                    <!-- Preview Area -->
                    <div class="mt-5" id="preview-section" style="display: none;">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4>Preview</h4>
                            <button type="button" id="hide-preview" class="template-btn btn-style-four">
                                <span class="btn-wrap">
                                    <span class="text-one">Hide Preview</span>
                                    <span class="text-two">Hide Preview</span>
                                </span>
                            </button>
                        </div>
                        <div class="card shadow-none border" data-component-card="data-component-card">
                            <div class="card-header p-4 border-bottom bg-body">
                                <div class="row g-3 justify-content-between align-items-center">
                                    <div class="col-12 col-md">
                                        <h4 class="text-body mb-0" data-anchor="data-anchor">Full Regulations & Technical Manual</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="p-4 code-to-copy">
                                    <div class="data-anchor" id="regulation-preview">
                                        <p>Preview will appear here...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

<style>
    /* Styles for regulation preview - same as /ucp/regulation.php */
    strong {
        color: white !important;
    }

    p {
        color: white !important;
    }

    pre {
        background-color: #f4f4f4;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .red {
        color: red !important;
    }

    .data-anchor {
        --text-dark: #0f172a;
        --text-light: #ffffff;
        --link: #a6c8ff;
        --link-hover: #e1ecff;
        --muted: #cbd5e1;
        --link-info: #0b4cc0;
        --link-warn: #8a5a00;
        --link-danger: #9f1239;
        --link-success: #065f46;
    }

    .data-anchor,
    .data-anchor *:not(svg):not([class*="icon"]) {
        color: var(--text-light) !important;
    }

    .data-anchor a,
    .data-anchor a:visited,
    .data-anchor a:active {
        color: var(--link) !important;
        text-decoration: underline;
        text-decoration-color: rgba(255, 255, 255, .55);
    }

    .data-anchor a:hover,
    .data-anchor a:focus {
        color: var(--link-hover) !important;
        text-decoration-color: currentColor;
    }

    .data-anchor li::marker {
        color: var(--muted);
    }

    .data-anchor hr {
        border: 0;
        border-top: 1px solid rgba(255, 255, 255, .18);
    }

    .data-anchor table {
        border-collapse: collapse;
        width: 100%;
    }

    .data-anchor th,
    .data-anchor td {
        border: 1px solid rgba(255, 255, 255, .18);
        padding: .5rem;
    }

    .data-anchor blockquote {
        border-left: 3px solid rgba(255, 255, 255, .35);
        padding-left: 12px;
    }

    .data-anchor code,
    .data-anchor pre {
        background: rgba(255, 255, 255, .06);
        color: inherit !important;
    }

    .data-anchor ::selection {
        background: rgba(255, 255, 255, .25);
        color: #fff;
    }

    .data-anchor .surface-white,
    .data-anchor [style*="background:#fff"],
    .data-anchor [style*="background: #fff"],
    .data-anchor [style*="background:#ffffff"],
    .data-anchor [style*="background: #ffffff"],
    .data-anchor .callout-info {
        color: var(--text-dark) !important;
    }

    .data-anchor [style*="background:#fffbeb"],
    .data-anchor [style*="background: #fffbeb"],
    .data-anchor .callout-warn {
        color: var(--text-dark) !important;
    }

    .data-anchor [style*="background:#fef2f2"],
    .data-anchor [style*="background: #fef2f2"],
    .data-anchor .callout-danger {
        color: var(--text-dark) !important;
    }

    .data-anchor [style*="background:#ecfdf5"],
    .data-anchor [style*="background: #ecfdf5"],
    .data-anchor .callout-success {
        color: var(--text-dark) !important;
    }

    .data-anchor .callout-info a,
    .data-anchor [style*="background:#eff6ff"] a {
        color: var(--link-info) !important;
        text-decoration-color: rgba(16, 24, 40, .4);
    }

    .data-anchor .callout-warn a,
    .data-anchor [style*="background:#fffbeb"] a {
        color: var(--link-warn) !important;
        text-decoration-color: rgba(16, 24, 40, .4);
    }

    .data-anchor .callout-danger a,
    .data-anchor [style*="background:#fef2f2"] a {
        color: var(--link-danger) !important;
        text-decoration-color: rgba(16, 24, 40, .4);
    }

    .data-anchor .callout-success a,
    .data-anchor [style*="background:#ecfdf5"] a {
        color: var(--link-success) !important;
        text-decoration-color: rgba(16, 24, 40, .4);
    }
</style>

<script>
    $(document).ready(function() {
        // Initialize TinyMCE
        initializeTinyMCE();

        function initializeTinyMCE() {
            if (typeof tinymce !== 'undefined') {
                tinymce.init({
                    selector: '#regulation-editor',
                    license_key: 'gpl',
                    height: 500,
                    menubar: 'file edit view insert format tools table help',
                    plugins: [
                        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                        'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                        'insertdatetime', 'media', 'table', 'help', 'wordcount', 'textcolor',
                        'colorpicker', 'textpattern', 'noneditable'
                    ],
                    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | ' +
                        'alignleft aligncenter alignright alignjustify | outdent indent | ' +
                        'bullist numlist | forecolor backcolor | link image media | ' +
                        'removeformat help | fullscreen preview | code | custompreview',
                    content_style: `
                    body { 
                        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif; 
                        font-size: 14px;
                        background-color: #1a1a1a;
                        color: #ffffff;
                    }
                    .data-anchor { color: #ffffff !important; }
                    .data-anchor * { color: #ffffff !important; }
                    strong { color: white !important; }
                    p { color: white !important; }
                `,
                    skin: 'oxide-dark',
                    content_css: 'dark',
                    setup: function(editor) {
                        editor.on('change', function() {
                            editor.save();
                        });

                        // Add custom preview button
                        editor.ui.registry.addButton('custompreview', {
                            text: 'Live Preview',
                            onAction: function() {
                                showPreview();
                            }
                        });
                    },
                    style_formats: [{
                            title: 'Red text',
                            inline: 'span',
                            styles: {
                                color: '#ff4444'
                            }
                        },
                        {
                            title: 'Blue text',
                            inline: 'span',
                            styles: {
                                color: '#a6c8ff'
                            }
                        },
                        {
                            title: 'Success text',
                            inline: 'span',
                            styles: {
                                color: '#065f46'
                            }
                        },
                        {
                            title: 'Warning text',
                            inline: 'span',
                            styles: {
                                color: '#8a5a00'
                            }
                        },
                        {
                            title: 'Danger text',
                            inline: 'span',
                            styles: {
                                color: '#9f1239'
                            }
                        },
                        {
                            title: 'Code block',
                            block: 'pre',
                            styles: {
                                'background-color': 'rgba(255, 255, 255, .06)',
                                'padding': '10px',
                                'border-radius': '5px'
                            }
                        }
                    ]
                });
            }
        }

        // Preview functions
        function showPreview() {
            if (typeof tinymce !== 'undefined' && tinymce.get('regulation-editor')) {
                const content = tinymce.get('regulation-editor').getContent();
                $('#regulation-preview').html(content);
                $('#preview-section').show();

                // Smooth scroll to preview
                $('html, body').animate({
                    scrollTop: $('#preview-section').offset().top - 100
                }, 500);
            }
        }

        $('#hide-preview').on('click', function() {
            $('#preview-section').hide();
        });

        // Status message functions
        function showStatus(type, message) {
            $('#loading-message, #success-message, #error-message').hide();

            switch (type) {
                case 'loading':
                    $('#loading-message').show();
                    $('#submit-btn').prop('disabled', true);
                    break;
                case 'success':
                    $('#success-message').show();
                    if (message) $('#success-text').text(message);
                    $('#submit-btn').prop('disabled', false);
                    setTimeout(() => $('#success-message').hide(), 5000);
                    break;
                case 'error':
                    $('#error-message').show();
                    if (message) $('#error-text').text(message);
                    $('#submit-btn').prop('disabled', false);
                    setTimeout(() => $('#error-message').hide(), 8000);
                    break;
            }
        }
    });
</script>