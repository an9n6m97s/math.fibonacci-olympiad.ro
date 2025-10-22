<?php
$uri = $_SERVER['REQUEST_URI'];
$parts = explode('/', trim($uri, '/'));
$category_slug = $parts[2] ?? '';

$category = getCategoryBySlug($category_slug);
?>
<style>
    .data-anchor {
        --text-dark: #0f172a;
        --text-light: #ffffff;
        --link: #0b66d0;
        --link-hover: #063a82;
        --muted: #475569;

        --link-info: #0b4cc0;
        --link-warn: #b45309;
        --link-danger: #b91c1c;
        --link-success: #047857;
    }

    .data-anchor,
    .data-anchor *:not(svg):not([class*="icon"]) {
        color: var(--text-dark) !important;
    }

    .data-anchor a,
    .data-anchor a:visited,
    .data-anchor a:active {
        color: var(--link) !important;
        text-decoration: underline;
        text-decoration-color: rgba(15, 23, 42, .3);
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
        border-top: 1px solid rgba(15, 23, 42, .15);
    }

    .data-anchor table {
        border-collapse: collapse;
        width: 100%;
    }

    .data-anchor th,
    .data-anchor td {
        border: 1px solid rgba(15, 23, 42, .15);
        padding: .5rem;
    }

    .data-anchor blockquote {
        border-left: 3px solid rgba(15, 23, 42, .35);
        padding-left: 12px;
        color: var(--muted);
    }

    .data-anchor code,
    .data-anchor pre {
        background: #f1f5f9;
        color: var(--text-dark) !important;
        font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, "Liberation Mono", monospace;
    }

    .data-anchor pre {
        padding: 12px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        white-space: pre-wrap;
    }

    .data-anchor ::selection {
        background: rgba(11, 102, 208, .15);
        color: var(--text-dark);
    }

    /* Callout overrides */
    .data-anchor .callout-info,
    .data-anchor [style*="background:#eff6ff"],
    .data-anchor [style*="background: #eff6ff"] {
        color: var(--text-dark) !important;
    }

    .data-anchor .callout-warn,
    .data-anchor [style*="background:#fffbeb"],
    .data-anchor [style*="background: #fffbeb"] {
        color: var(--text-dark) !important;
    }

    .data-anchor .callout-danger,
    .data-anchor [style*="background:#fef2f2"],
    .data-anchor [style*="background: #fef2f2"] {
        color: var(--text-dark) !important;
    }

    .data-anchor .callout-success,
    .data-anchor [style*="background:#ecfdf5"],
    .data-anchor [style*="background: #ecfdf5"] {
        color: var(--text-dark) !important;
    }

    .data-anchor .callout-info a,
    .data-anchor [style*="background:#eff6ff"] a {
        color: var(--link-info) !important;
    }

    .data-anchor .callout-warn a,
    .data-anchor [style*="background:#fffbeb"] a {
        color: var(--link-warn) !important;
    }

    .data-anchor .callout-danger a,
    .data-anchor [style*="background:#fef2f2"] a {
        color: var(--link-danger) !important;
    }

    .data-anchor .callout-success a,
    .data-anchor [style*="background:#ecfdf5"] a {
        color: var(--link-success) !important;
    }

    .red {
        color: var(--link-danger) !important;
    }
</style>

<?php
$regulations = getCategoryRegulationBySlug($category_slug);
?>

<h2 class="mb-2">Regulation <?= htmlspecialchars($category['name']) ?></h2>
<div class="card shadow-sm border my-4" data-component-card="data-component-card">
    <div class="card-header p-4 border-bottom bg-body">
        <div class="row g-3 justify-content-between align-items-center">
            <div class="col-12 col-md">
                <h4 class="text-body mb-0" data-anchor="data-anchor">Full Regulations & Technical Manual</h4>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="p-4 code-to-copy">
            <div class="data-anchor">
                <?= $regulations[0]['content'] ?>
            </div>
            <?php if (1 == 2) : ?>
                <h2 class="h5 text-muted">This regulation is based on the official Robochallenge rules, following the structure and principles of the competition, with possible adaptations specific to the event.</h2>
            <?php endif; ?>
        </div>
    </div>
</div>