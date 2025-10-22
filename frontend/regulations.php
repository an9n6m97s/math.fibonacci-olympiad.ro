<!-- Page Title -->
<div class="container py-3">
    <h2 class="fw-bold text-dark mb-1">Regulations</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="/" class="link-primary text-decoration-none">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Regulations</li>
        </ol>
    </nav>
</div>
<!-- End Page Title -->

<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <?php $delay = 0.1;
            foreach (getCategories() as $category): ?>
                <div class="col-12 col-sm-6 col-lg-4 wow fadeInUp"
                    data-wow-delay="<?= number_format($delay, 1) ?>s" data-wow-duration="1s">

                    <div class="card fibo-card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column p-4">
                            <h3 class="h4 fw-bold mb-2 text-dark">
                                <?= htmlspecialchars($category['name']) ?>
                            </h3>
                            <p class="text-secondary mb-3 flex-grow-1">
                                <?= htmlspecialchars($category['description']) ?>
                            </p>

                            <div class="mb-4">
                                <img
                                    src="/assets/images/rules/<?= htmlspecialchars($category['slug']) ?>.webp"
                                    alt="<?= htmlspecialchars($category['name']) ?>"
                                    class="img-fluid fibo-card-image">
                            </div>

                            <div class="d-grid gap-3 mt-auto">
                                <a href="/regulation/<?= htmlspecialchars($category['slug']) ?>" target="_blank"
                                    class="btn btn-danger d-inline-flex align-items-center justify-content-center gap-2">
                                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" class="opacity-75">
                                        <rect x="3" y="3" width="18" height="18" rx="2" stroke="currentColor" stroke-width="2" />
                                        <path d="M12 3v18" stroke="currentColor" stroke-width="2" />
                                        <path d="M7.5 8h3M7.5 12h3M13.5 8h3M13.5 12h3"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                    </svg>
                                    <span>Read rules</span>
                                </a>

                                <?php
                                $pdfPath = $_SERVER['DOCUMENT_ROOT'] . "/assets/regulations/" . $category['slug'] . ".pdf";
                                if (file_exists($pdfPath)): ?>
                                    <a href="/assets/regulations/<?= htmlspecialchars($category['slug']) ?>.pdf"
                                        class="btn btn-outline-primary d-inline-flex align-items-center justify-content-center gap-2"
                                        download>
                                        <i class="fa fa-file-pdf opacity-75"></i>
                                        <span>Download PDF</span>
                                    </a>
                                <?php else: ?>
                                    <div class="alert alert-warning mb-0" role="alert">
                                        This regulation is not available in PDF format.
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                </div>
            <?php $delay += 0.1;
            endforeach; ?>
        </div>
    </div>
</section>