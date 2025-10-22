<!--[if lt IE 8]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->

<div id="preloader-wrap">
    <svg viewBox="0 0 1000 1000" preserveAspectRatio="none">
        <path id="preloader-bg" d="M0,1005S175,995,500,995s500,5,500,5V0H0Z"></path>
    </svg>
    <div class="site-preloader preloader-text">
        <span data-text="F">F</span>
        <span data-text="I">I</span>
        <span data-text="B">B</span>
        <span data-text="O">O</span>
        <span data-text="N">N</span>
        <span data-text="A">A</span>
        <span data-text="C">C</span>
        <span data-text="C">C</span>
        <span data-text="I">I</span>
    </div>
</div>
<!-- Site Preloader -->

<header class="main-header">
    <div class="container">
        <div class="main-header-wapper">
            <div class="site-logo">
                <a href="/"><img src="/assets/images/logo/logo.webp" alt="Logo" /></a>
            </div>
            <div class="main-header-info">
                <div class="top-header">
                    <ul class="top-left">
                        <li><i class="fa-regular fa-phone"></i><a href="tel:<?= str_replace(' ', '', $settings['contact_phone']) ?>"><?= $settings['contact_phone'] ?></a></li>
                        <li><i class="fa-regular fa-envelope-dot"></i><?= $settings['contact_email'] ?></li>
                    </ul>
                    <div class="top-right">
                        <ul class="top-header-nav">
                            <li><a href="/contact">Contact</a></li>
                            <!-- <li><a href="contact.html">Support</a></li> -->
                            <li><a href="/faq">FAQ</a></li>
                        </ul>
                        <ul class="header-social-share">
                            <li><a href="<?= $settings['social_links']['facebook'] ?>"><i class="fa-brands fa-facebook-f"></i></a></li>
                            <li><a href="<?= $settings['social_links']['youtube'] ?>"><i class="fa-brands fa-youtube"></i></a></li>
                            <li><a href="<?= $settings['social_links']['instagram'] ?>"><i class="fa-brands fa-instagram"></i></a></li>
                            <li><a href="<?= $settings['social_links']['tiktok'] ?>"><i class="fa-brands fa-tiktok"></i></a></li>
                        </ul>
                    </div>
                </div>
                <!--/.top-header-->

                <div class="header-menu-wrap">
                    <ul class="nav-menu">
                        <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/frontend/layout/navigation.php'; ?>
                    </ul>
                    <div class="menu-right-item">

                        <div class="mobile-menu-icon">
                            <i class="fa-regular fa-ellipsis-vertical"></i>
                        </div>
                        <a href="/registration" class="default-btn d-none d-lg-block d-md-block"><i class="fa-solid fa-right-to-bracket"></i>Înscrie-te</a>
                    </div>
                </div>
                <!--/.header-menu-wrap-->
            </div>
        </div>
    </div>
</header>
<!--/.main-header-->