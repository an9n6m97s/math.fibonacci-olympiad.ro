<!-- Page Title -->
<section class="page-title">
    <div class="page-title-icon" style="background-image:url(assets/images/icons/page-title_icon-1.webp)"></div>
    <div class="pag e-title-icon-two" style="background-image:url(assets/images/icons/page-title_icon-2.webp)"></div>
    <div class="page-title-shadow" style="background-image:url(assets/images/background/page-title-1.webp)"></div>
    <div class="page-title-shadow_two" style="background-image:url(assets/images/background/page-title-2.webp)"></div>
    <div class="auto-container">
        <h2>Log in now</h2>
        <ul class="bread-crumb clearfix">
            <li><a href="/">Home</a></li>
            <li>Log in now</li>
        </ul>
    </div>
</section>
<!-- End Page Title -->

<!-- Register One -->
<section class="register-one">
    <div class="auto-container">
        <div class="inner-container">

            <?php if (strtotime(date('Y-m-d')) < strtotime($settings['registration_open'])) {
                echo '<audio id="registrationSound" src="/assets/sounds/registration-not-opened.mp3" autoplay></audio>';
            ?>
                <div class="register-card">
                    <h1 class="register-title">Registrations Open on September 1, 2025</h1>
                    <p class="register-lead">
                        Sorry—registrations for the <strong>Fibonacci Romania 2026</strong> haven’t opened yet.
                    </p>
                    <p class="register-text">
                        Registration opens on <strong>September 1, 2025 at 12:00 AM</strong> (EEST, UTC+3).
                        Mark your calendar and check back to secure your spot.
                    </p>
                    <p class="register-text">
                        See you in <strong>Buzău, Romania</strong>, from
                        <time datetime="2026-02-27">February 27</time> to
                        <time datetime="2026-03-01">March 1, 2026</time>.
                    </p>
                    <p class="register-text"><strong>Add to calendar:</strong></p>
                    <div class="register-actions">
                        <!-- Google Calendar -->
                        <a
                            class="template-btn btn-style-one"
                            target="_blank" rel="noopener"
                            href="https://calendar.google.com/calendar/render?action=TEMPLATE&text=Relativity%20Robotics%20Challenge%202026%2C%20Buz%C4%83u%2C%20Romania&dates=20260227/20260302&location=Buz%C4%83u%2C%20Romania&ctz=Europe/Bucharest&details=Relativity%20Robotics%20Challenge%202026%20in%20Buz%C4%83u%2C%20Romania">
                            <span class="btn-wrap">
                                <span class="text-one">Google Calendar</span>
                                <span class="text-two">Google Calendar</span>
                            </span>
                        </a>
                    </div>
                    <a href="/" class="template-btn btn-style-one mt-5">
                        <span class="btn-wrap">
                            <span class="text-one">Back to Home</span>
                            <span class="text-two">Back to Home</span>
                        </span>
                    </a>
                </div>

            <?php
            } else if (strtotime(date('Y-m-d')) > strtotime($settings['registration_close'])) {
            ?>
                <div class="register-card">
                    <h1 class="register-title">Registrations Are Closed</h1>
                    <p class="register-lead">
                        Registration for the <strong>Fibonacci Romania 2026</strong> has ended.
                    </p>
                    <p class="register-text">
                        Thank you to everyone who signed up! We look forward to seeing all registered participants at the competition.
                        For pre-event updates and instructions, please check our website.
                    </p>
                    <p class="register-text">
                        See you in <strong>Buzău, Romania</strong>, from
                        <time datetime="2026-02-27">February 27</time> to
                        <time datetime="2026-03-01">March 1, 2026</time>.
                    </p>
                    <p class="register-text"><strong>Add to calendar:</strong></p>
                    <div class="register-actions">
                        <!-- Google Calendar -->
                        <a
                            class="template-btn btn-style-one"
                            target="_blank" rel="noopener"
                            href="https://calendar.google.com/calendar/render?action=TEMPLATE&text=Relativity%20Robotics%20Challenge%202026%2C%20Buz%C4%83u%2C%20Romania&dates=20260227/20260302&location=Buz%C4%83u%2C%20Romania&ctz=Europe/Bucharest&details=Relativity%20Robotics%20Challenge%202026%20in%20Buz%C4%83u%2C%20Romania">
                            <span class="btn-wrap">
                                <span class="text-one">Google Calendar</span>
                                <span class="text-two">Google Calendar</span>
                            </span>
                        </a>
                    </div>
                    <a href="/" class="template-btn btn-style-one mt-5">
                        <span class="btn-wrap">
                            <span class="text-one">Back to Home</span>
                            <span class="text-two">Back to Home</span>
                        </span>
                    </a>
                </div>
            <?php } else { ?>

                <h3>Logging</h3>
                <!-- Register Form -->
                <div class="register-form">
                    <form id="loginForm" method="post">
                        <div class="form-group col-lg-12 col-md-12 col-sm-12 mt-4">
                            <input type="email" name="email" id="email" value="" placeholder="Email*" required>
                        </div>

                        <div class="form-group col-lg-12 col-md-12 col-sm-12">
                            <input type="password" name="password" id="password" value="" placeholder="Password*" required>
                        </div>

                        <div class="form-group">
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <div class="check-box">
                                    <input type="checkbox" name="remember-password" id="remember-password">
                                    <label for="remember-password">Remember Me</label>
                                </div>
                                <a class="forgot-psw" href="/forgot-password">Forgot Password?</a>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4"></div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <!-- Button Box -->
                                    <button type="submit" class="submit-btn btn-style-one">
                                        <span class="btn-wrap">
                                            <span class="text-one">Login now</span>
                                            <span class="text-two">Login now</span>
                                        </span>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-4"></div>
                        </div>

                        <div class="form-group">
                            <div class="creat-account">Don't have an account? <a href="/ucp/registration">Register now</a></div>
                        </div>
                    </form>

                </div>
                <!-- End Default Form -->

            <?php } ?>
        </div>
    </div>
</section>
<!-- End Register One -->