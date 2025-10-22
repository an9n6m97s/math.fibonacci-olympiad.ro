<?php
$categories = getCategories();
$category_slug = $_GET['c'] ?? '';
$category = null;
$regulations = [];

if (!empty($category_slug)) {
    $category = getCategoryBySlug($category_slug);
    $regulations = getCategoryRegulationBySlug($category_slug);
}
?>

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
</style>

<div class="row mb-4 mb-xl-6 mb-xxl-4 gy-3 px-4 px-lg-6 pt-6 justify-content-between">
    <div class="col-auto">
        <h2 class="mb-0 text-body-emphasis">Edit Regulations</h2>
    </div>
    <div class="col-auto">
        <div class="d-flex align-items-center gap-2">
            <a href="/ucp/admin/regulation/view" class="btn btn-outline-secondary btn-sm">
                <span class="fas fa-arrow-left me-1"></span>Back to Overview
            </a>
        </div>
    </div>
</div>

<?php if (empty($category_slug)) : ?>
    <!-- Category Selection -->
    <div class="mx-n4 mx-lg-n6 px-4 px-lg-6 mb-9 bg-body-emphasis border-y mt-2 position-relative top-1">
        <div class="py-4">
            <h4 class="text-body-emphasis mb-4">Select Regulation Category to Edit</h4>
            <div class="row align-items-end g-3">
                <div class="col-md-8">
                    <label class="form-label">Category</label>
                    <select name="c" id="category-select" class="form-select" required>
                        <option value="" selected disabled>Select category to edit</option>
                        <?php foreach ($categories as $cat) : ?>
                            <option value="<?= htmlspecialchars($cat['slug']) ?>">
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <button id="go-to-category" class="btn btn-primary w-100">
                        <span class="fas fa-arrow-right me-1"></span>Select Category
                    </button>
                </div>
            </div>
        </div>
    </div>

<?php else : ?>
    <!-- Edit Interface -->
    <div class="mx-n4 mx-lg-n6 px-4 px-lg-6 mb-9 bg-body-emphasis border-y mt-2 position-relative top-1">
        <div class="py-4">
            <!-- Category Switcher -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="text-body-emphasis mb-1">Edit Regulation</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 fs-9">
                            <li class="breadcrumb-item"><a href="/ucp/admin/regulation/view">All Categories</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($category['name'] ?? 'Unknown') ?></li>
                        </ol>
                    </nav>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <select name="c" id="category-select" class="form-select form-select-sm">
                        <option value="">Switch to different category</option>
                        <?php foreach ($categories as $cat) : ?>
                            <option value="<?= htmlspecialchars($cat['slug']) ?>"
                                <?= ($cat['slug'] === $category_slug) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button id="go-to-category" class="btn btn-outline-primary btn-sm">
                        <span class="fas fa-exchange-alt me-1"></span>Switch
                    </button>
                </div>
            </div>

            <!-- Mode Toggle Buttons -->
            <div class="d-flex gap-2 mb-4">
                <button type="button" id="preview-btn" class="btn btn-primary">
                    <span data-feather="eye" class="me-1"></span>Preview Mode
                </button>
                <button type="button" id="edit-btn" class="btn btn-outline-primary">
                    <span data-feather="edit-3" class="me-1"></span>Edit Mode
                </button>
            </div>

            <!-- Preview Mode -->
            <div id="preview-container" class="d-block">
                <div class="card shadow-none border">
                    <div class="card-header p-4 border-bottom bg-body">
                        <div class="row g-3 justify-content-between align-items-center">
                            <div class="col-12 col-md">
                                <h4 class="text-body mb-0">Full Regulations & Technical Manual</h4>
                                <p class="mb-0 text-body-tertiary fs-9">Category: <?= htmlspecialchars($category['name'] ?? 'Unknown') ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="p-4">
                            <div class="data-anchor" id="regulation-preview">
                                <?= isset($regulations[0]['content']) ? $regulations[0]['content'] : '<p>No regulation content found for this category.</p>' ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Mode -->
            <div id="edit-container" class="d-none">
                <form method="post" id="edit-regulation-form" enctype="multipart/form-data">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="category" class="form-label">Regulation Category</label>
                            <select name="category" id="category" class="form-select" required>
                                <option value="" disabled>Select Category</option>
                                <?php foreach ($categories as $cat) : ?>
                                    <option value="<?= htmlspecialchars($cat['slug']) ?>"
                                        <?= ($cat['slug'] === $category_slug) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cat['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-12">
                            <label for="regulation-editor" class="form-label">Regulation Content</label>
                            <textarea id="regulation-editor" name="content"><?= isset($regulations[0]['content']) ? $regulations[0]['content'] : '' ?></textarea>
                        </div>

                        <!-- Action Buttons -->
                        <div class="col-12">
                            <div class="d-flex gap-2 flex-wrap">
                                <button type="submit" class="btn btn-primary" id="save-btn">
                                    <span data-feather="save" class="me-1"></span>Save Changes
                                </button>
                                <button type="button" id="preview-changes-btn" class="btn btn-success">
                                    <span data-feather="eye" class="me-1"></span>Preview Changes
                                </button>
                                <a href="/ucp/admin/regulation/view?c=<?= $category_slug ?>" class="btn btn-outline-secondary">
                                    <span data-feather="x" class="me-1"></span>Cancel
                                </a>
                            </div>
                        </div>

                        <!-- Status Messages -->
                        <div class="col-12">
                            <div id="loading-message" class="alert alert-info d-none">
                                <span class="fas fa-spinner fa-spin me-2"></span>Saving changes...
                            </div>
                            <div id="success-message" class="alert alert-success d-none">
                                <span class="fas fa-check me-2"></span><span id="success-text">Changes saved successfully!</span>
                            </div>
                            <div id="error-message" class="alert alert-danger d-none">
                                <span class="fas fa-exclamation-triangle me-2"></span><span id="error-text">Error saving changes.</span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
    $(document).ready(function() {
        // Category selection handler
        $('#go-to-category').on('click', function() {
            const selectedCategory = $('#category-select').val();
            if (selectedCategory) {
                window.location.href = '/ucp/admin/regulation/edit?c=' + selectedCategory;
            } else {
                notify('Please select a category first.', 'error');
            }
        });

        <?php if (!empty($category_slug)) : ?>
            // Mode switching
            $('#preview-btn').on('click', function() {
                $('#preview-container').removeClass('d-none').addClass('d-block');
                $('#edit-container').removeClass('d-block').addClass('d-none');
                $(this).removeClass('btn-outline-primary').addClass('btn-primary');
                $('#edit-btn').removeClass('btn-primary').addClass('btn-outline-primary');
            });

            $('#edit-btn').on('click', function() {
                $('#preview-container').removeClass('d-block').addClass('d-none');
                $('#edit-container').removeClass('d-none').addClass('d-block');
                $(this).removeClass('btn-outline-primary').addClass('btn-primary');
                $('#preview-btn').removeClass('btn-primary').addClass('btn-outline-primary');
            });

            // Preview changes button handler
            $('#preview-changes-btn').on('click', function() {
                if (typeof tinymce !== 'undefined' && tinymce.get('regulation-editor')) {
                    const content = tinymce.get('regulation-editor').getContent();
                    $('#regulation-preview').html(content);
                    $('#preview-btn').click();
                }
            });

            // Initialize TinyMCE
            initializeTinyMCE();

            // Form submit handler
            $('#edit-regulation-form').on('submit', function(e) {
                e.preventDefault();

                // Get form data
                const formData = new FormData(this);

                // Get content from TinyMCE
                if (typeof tinymce !== 'undefined' && tinymce.get('regulation-editor')) {
                    formData.set('content', tinymce.get('regulation-editor').getContent());
                }

                // Show loading
                showStatus('loading');

                // Submit via AJAX
                fetch('/backend/api/private/edit/regulation.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.message) {
                            showStatus('success', data.message);
                            // Redirect after 2 seconds
                            setTimeout(() => {
                                window.location.href = '/ucp/admin/regulation/view?c=<?= $category_slug ?>';
                            }, 2000);
                        } else {
                            showStatus('error', 'Unknown error occurred.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showStatus('error', 'Network error or server unavailable.');
                    });
            });

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
                            'removeformat help | fullscreen preview | code',
                        // Allow style tags and preserve them
                        valid_elements: '*[*]',
                        extended_valid_elements: 'style[type|media|scoped],script[src|async|defer|type|charset]',
                        custom_elements: 'style',
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

            // Status message functions
            function showStatus(type, message) {
                $('#loading-message, #success-message, #error-message').addClass('d-none');

                switch (type) {
                    case 'loading':
                        $('#loading-message').removeClass('d-none');
                        $('#save-btn').prop('disabled', true);
                        break;
                    case 'success':
                        $('#success-message').removeClass('d-none');
                        if (message) $('#success-text').text(message);
                        $('#save-btn').prop('disabled', false);
                        setTimeout(() => $('#success-message').addClass('d-none'), 5000);
                        break;
                    case 'error':
                        $('#error-message').removeClass('d-none');
                        if (message) $('#error-text').text(message);
                        $('#save-btn').prop('disabled', false);
                        setTimeout(() => $('#error-message').addClass('d-none'), 8000);
                        break;
                }
            }
        <?php endif; ?>
    });

    // Initialize feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
</script>