<!-- Page Title -->
<section class="page-title">
    <div class="page-title-icon" style="background-image:url(/assets/images/icons/page-title_icon-1.webp)"></div>
    <div class="page-title-icon-two" style="background-image:url(/assets/images/icons/page-title_icon-2.webp)"></div>
    <div class="page-title-shadow" style="background-image:url(/assets/images/background/page-title-1.webp)"></div>
    <div class="page-title-shadow_two" style="background-image:url(/assets/images/background/page-title-2.webp)"></div>
    <div class="auto-container">
        <h2>Clients testimonials</h2>
        <ul class="bread-crumb clearfix">
            <li><a href="/">Home</a></li>
            <li>Testimonial</li>
        </ul>
        <h2 class="h3">Have you participated in a past edition?<br>
            Share your experience and inspire future competitors!<br>
            Click below to add a review:</h2>
        <a href="/testimonial/add">
            <div class="testimonial-block_one-rating">
                <span class="fa fa-star fa-2xl"></span>
                <span class="fa fa-star fa-2xl"></span>
                <span class="fa fa-star fa-2xl"></span>
                <span class="fa fa-star fa-2xl"></span>
                <span class="fa-regular fa-star fa-2xl"></span>
            </div>
        </a>

    </div>
</section>
<!-- End Page Title -->

<!-- Testimonial Four -->
<?php
$perPage = 9;
$page    = max(1, intval($_GET['page'] ?? 1));
$allTestimonials = getTestimonials();
$totalItems      = count($allTestimonials);
$totalPages      = max(1, ceil($totalItems / $perPage));
$offset          = ($page - 1) * $perPage;
$testimonials    = array_slice($allTestimonials, $offset, $perPage);
?>

<section class="testimonial-four">
    <div class="auto-container">

        <div class="row clearfix">
            <?php $delay = 0.1;
            foreach ($testimonials as $testimonial): ?>
                <!-- Testimonial Block One -->
                <div class="col-md-4">
                    <div class="testimonial-block_one wow fadeInUp" data-wow-delay="<?= number_format($delay, 1) ?>s" data-wow-duration="1.5s">
                        <div class="testimonial-block_one-inner">
                            <h4 class="sec-title_heading"><?= htmlspecialchars($testimonial['team']) ?></h4>
                            <br>

                            <!-- Rating -->
                            <div class="testimonial-block_one-rating">
                                <?php
                                $stars = intval($testimonial['stars']);
                                for ($i = 1; $i <= 5; $i++) {
                                    echo $i <= $stars
                                        ? '<span class="fa fa-star fa-2xl"></span>'
                                        : '<span class="fa-regular fa-star fa-2xl"></span>';
                                }
                                ?>
                            </div>

                            <div class="testimonial-block_one-text">
                                <?= nl2br(htmlspecialchars($testimonial['review'])) ?>
                            </div>

                            <div class="testimonial-block_one-author_box">
                                <div class="testimonial-block_one-author-image">
                                    <img src="/<?= htmlspecialchars($testimonial['photo']) ?>" alt="" />
                                </div>
                                <?= htmlspecialchars($testimonial['author']) ?>
                                <span><?= htmlspecialchars($testimonial['role']) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if ($totalPages > 1): ?>
            <ul class="styled-pagination text-center mt-4">

                <?php if ($page > 1): ?>
                    <li class="prev">
                        <a href="?page=<?= $page - 1 ?>">
                            <span class="fa-solid fa-angle-left fa-fw"></span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                    <li>
                        <a href="?page=<?= $p ?>" class="<?= $p == $page ? 'active' : '' ?>">
                            <?= $p ?>
                        </a>
                    </li>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <li class="next">
                        <a href="?page=<?= $page + 1 ?>">
                            <span class="fa-solid fa-angle-right fa-fw"></span>
                        </a>
                    </li>
                <?php endif; ?>

            </ul>
        <?php endif; ?>

    </div>
</section>

<!-- End Testimonial Four -->