<?php if (!page('coming-soon') && !page('maintenance')) { ?>

    <footer class="footer-section">
        <div class="map-pattern"></div>
        <div class="footer-wrapper">
            <div class="container">
                <div class="row gy-lg-0 gy-4">
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-widget">
                            <a href="index.html" class="footer-logo"><img src="/assets/images/logo/logo.webp" alt="logo"></a>
                            <p>Fibonacci Romania Math Olympiad aduce împreună elevi pasionați de matematică din toată țara, într-o experiență competițională dedicată logicii, creativității și excelenței academice.</p>
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
                                <h3>Secțiuni olimpice</h3>
                            </div>
                            <ul class="footer-links">
                                <li><a href="/about">Despre Olimpiadă</a></li>
                                <li><a href="/principles">Principii & Format</a></li>
                                <li><a href="/registration">Înscriere participanți</a></li>
                                <li><a href="/contact">Locație & Contact</a></li>
                                <li><a href="/admin/login">Acces Administrație</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-widget">
                            <div class="widget-title">
                                <h3>Detalii contact</h3>
                            </div>
                            <ul class="footer-contact-info">
                                <li>
                                    <p><span>Adresa:</span>Liceul Teoretic de Informatică „Alexandru Marghiloman” <br> Str. Ivănețu nr. 7, Buzău, România</p>
                                </li>
                                <li>
                                    <p><span>Telefon:</span><?= $settings['contact_phone'] ?></p>
                                </li>
                                <li>
                                    <p><span>Email:</span><?= $settings['contact_email'] ?></p>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-widget">
                            <div class="widget-title">
                                <h3>Resurse utile</h3>
                            </div>
                            <ul class="footer-links">
                                <li><a href="/database.sql">Structură bază de date</a></li>
                                <li><a href="/regulations">Regulament oficial</a></li>
                                <li><a href="/contact">Program eveniment</a></li>
                            </ul>
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