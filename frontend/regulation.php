<?php
$uri = $_SERVER['REQUEST_URI'];
$parts = explode('/', trim($uri, '/'));
$category_slug = $parts[1] ?? '';

$category = getCategoryBySlug($category_slug);
?>

<div class="container mt-5">
    <!-- Page header -->
    <div class="row gy-3 px-4 px-lg-6 pt-6 mb-3 mb-xl-4 justify-content-between align-items-end">
        <div class="col-auto">
            <h2 class="mb-0 text-body-emphasis">Regulation <?= htmlspecialchars($category['name']) ?></h2>
        </div>
    </div>
    <!-- End Page Title -->

    <style>
        :root {
            --eb-bg: #f8fafc;
            /* page background */
            --eb-surface: #ffffff;
            /* cards/blocks */
            --eb-border: #e2e8f0;
            /* subtle borders */
            --eb-text: #0f172a;
            /* primary text (slate-900) */
            --eb-muted: #475569;
            /* secondary text (slate-600) */
            --eb-link: #0b66d0;
            /* EssenByte blue */
            --eb-link-hover: #063a82;
            /* darker EssenByte blue */
            --eb-accent: #0b66d0;
            --eb-danger: #b91c1c;
            /* red-700 */
            --eb-code-bg: #f1f5f9;
            /* code/pre background */
        }

        body {
            background-color: var(--eb-bg);
            color: var(--eb-text);
        }

        /* Page title: ensure readability on light theme */
        .page-title {
            color: var(--eb-text);
            padding: 60px 0 30px;
            position: relative;
            background: linear-gradient(180deg, #ffffff 0%, #f6f9ff 100%);
            border-bottom: 1px solid var(--eb-border);
        }

        .page-title h2 {
            margin: 0 0 10px 0;
            color: var(--eb-text);
        }

        .bread-crumb li,
        .bread-crumb li a {
            color: var(--eb-muted);
        }

        .bread-crumb li a {
            text-decoration: none;
        }

        .bread-crumb li a:hover {
            color: var(--eb-link-hover);
            text-decoration: underline;
        }

        /* Content typography */
        .data-one {
            padding: 10px 0 60px;
        }

        .info-block_one-inner {
            background: var(--eb-surface);
            border: 1px solid var(--eb-border);
            border-radius: 12px;
            padding: 28px;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
        }

        .info-block_one-inner h1,
        .info-block_one-inner h2,
        .info-block_one-inner h3,
        .info-block_one-inner h4,
        .info-block_one-inner h5,
        .info-block_one-inner h6 {
            color: var(--eb-text);
            margin-top: 1.2em;
            margin-bottom: .6em;
        }

        .info-block_one-inner p {
            color: var(--eb-muted);
            line-height: 1.7;
            margin: 0 0 1rem 0;
        }

        .info-block_one-inner ul,
        .info-block_one-inner ol {
            color: var(--eb-muted);
        }

        strong,
        b {
            color: var(--eb-text);
            font-weight: 700;
        }

        a {
            color: var(--eb-link);
        }

        a:hover {
            color: var(--eb-link-hover);
        }

        .red {
            color: var(--eb-danger);
        }

        pre,
        code,
        kbd,
        samp {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        }

        pre {
            background-color: var(--eb-code-bg);
            padding: 14px 16px;
            border: 1px solid var(--eb-border);
            border-radius: 10px;
            overflow: auto;
            color: var(--eb-text);
        }

        code {
            background-color: var(--eb-code-bg);
            padding: 2px 6px;
            border-radius: 6px;
            border: 1px solid var(--eb-border);
        }

        /* Utility overrides from dark mode leftovers */
        .text-white {
            color: var(--eb-text) !important;
        }
    </style>

    <?php
    $regulations = getCategoryRegulationBySlug($category_slug);
    ?>
    <section class="data-one mt-5">
        <div class="auto-container">
            <div class="info-block_one-inner text-start">
                <?= $regulations[0]['content'] ?>
            </div>

            <div class="mt-5 mb-3 text-center">
                <h5></h5>
            </div>

            <?php if (1 == 2) : ?>
                <div class="mt-5 mb-3 text-center">
                    <h2 class="h5">This regulation is based on the official Robochallenge rules, following the structure and principles of the competition, with possible adaptations specific to the event.</h2>
                </div>
            <?php endif; ?>
        </div>
    </section>
</div>