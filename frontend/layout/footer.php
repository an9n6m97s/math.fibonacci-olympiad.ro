<?php if (!page('coming-soon') && !page('maintenance')) { ?>

    <footer class="footer-section">
        <div class="map-pattern"></div>
        <div class="footer-wrapper">
            <div class="container">
                <div class="row gy-lg-0 gy-4">
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-widget">
                            <a href="index.html" class="footer-logo"><img src="/assets/images/logo/logo.webp" alt="logo"></a>
                            <p>Fibonacci Romania brings together young innovators, fostering creativity, technology, and fair-play competition in robotics.</p>
                            <ul class="social-share">
                                <li><a href="<?= $settings['social_links']['facebook'] ?>"><i class="fa-brands fa-facebook-f"></i></a></li>
                                <li><a href="<?= $settings['social_links']['youtube'] ?>"><i class="fa-brands fa-x-twitter"></i></a></li>
                                <li><a href="<?= $settings['social_links']['instagram'] ?>"><i class="fa-brands fa-instagram"></i></a></li>
                                <li><a href="<?= $settings['social_links']['tiktok'] ?>"><i class="fa-brands fa-tiktok"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-widget widget-links">
                            <div class="widget-title">
                                <h3>Categories</h3>
                            </div>
                            <ul class="footer-links">
                                <li><a href="/regulation/mega-sumo">Mega Sumo</a></li>
                                <li><a href="/regulation/mini-sumo">Mini Sumo</a></li>
                                <li><a href="/regulation/micro-sumo">Micro Sumo</a></li>
                                <li><a href="/regulation/humanoid-sumo">Humanoid Sumo</a></li>
                                <li><a href="/regulation/humanoid-triathlon">Humanoid Triathlon</a></li>
                                <li><a href="/regulation/line-follower-classic">Line Follower Classic</a></li>
                                <li><a href="/regulation/line-follower-turbo">Line Follower Turbo</a></li>
                                <li><a href="/regulation/line-follower-enhanced">Line Follower Enhanced</a></li>
                                <li><a href="/regulation/drag-race">Drag Race</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-widget">
                            <div class="widget-title">
                                <h3>Get In Touch</h3>
                            </div>
                            <ul class="footer-contact-info">
                                <li>
                                    <p><span>Address:</span>Liceul Teoretic de Informatica "Alexandru Marghiloman" <br> Str. Ivanetu Nr. 7, Buzau, Romania</p>
                                </li>
                                <li>
                                    <p><span>Phone:</span><?= $settings['contact_phone'] ?></p>
                                </li>
                                <li>
                                    <p><span>Mail Us:</span>office@fibonacci-olympiad.ro</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-widget">
                            <div class="widget-title">
                                <h3>Newslatter Signup</h3>
                            </div>
                            <form action="#" class="subscribe-form" novalidate="true">
                                <div class="mc-fields">
                                    <input class="form-control" type="email" name="EMAIL" placeholder="Your Email" required="">
                                    <input type="hidden" name="action" value="mailchimpsubscribe">
                                    <button class="submit"><i class="fa-regular fa-arrow-right-long"></i></button>
                                </div>
                                <div class="clearfix"></div>
                                <div id="subscribe-result">
                                    <div class="subscription-success"></div>
                                    <div class="subscription-error"></div>
                                </div>
                            </form>
                            <p class="mt-20">Subscribe us and get all the benifits from today.</p>
                        </div>
                    </div>
                </div>
            </div>
            <?php if (1 == 2) : ?>
                <div class="running-truck">
                    <div class="truck"></div>
                    <div class="truck-2"></div>
                    <div class="truck-3"></div>
                </div>
            <?php endif; ?>
        </div>
        <div class="copyright-area">
            <div class="footer-copyright">Infrastructure & Hosting by <a href="https://essenbyte.com??utm_source=fibonacci-olympiad.ro&utm_campaign=sponsorship">EssenByte Solutions</a></div>
            <div class="footer-copyright">&copy; <?php echo date('Y'); ?> <a href="/"><?= $settings['competition_name'] ?>.</a> All rights reserved.</div>
        </div>
    </footer>
    <!--/.footer-section-->

    <div id="scrollup">
        <button id="scroll-top" class="scroll-to-top"><i class="fa-regular fa-arrow-up"></i></button>
    </div>
    <!--/.scrollup-->

<?php } ?>