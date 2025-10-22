<!-- Page Title -->
<section class="page-title">
    <div class="page-title-icon" style="background-image:url(assets/images/icons/page-title_icon-1.webp)"></div>
    <div class="page-title-icon-two" style="background-image:url(assets/images/icons/page-title_icon-2.webp)"></div>
    <div class="page-title-shadow" style="background-image:url(assets/images/background/page-title-1.webp)"></div>
    <div class="page-title-shadow_two" style="background-image:url(assets/images/background/page-title-2.webp)"></div>
    <div class="auto-container">
        <h2>Register now </h2>
        <ul class="bread-crumb clearfix">
            <li><a href="/">Home</a></li>
            <li>Register now</li>
        </ul>
    </div>
</section>
<!-- End Page Title -->


<section class="register-one">
    <div class="auto-container">
        <?php
        if (isset($_GET['registration']) && $_GET['registration'] === 'success') { ?>
            <div class="register-card">
                <h1 class="register-title">Registration Successful</h1>

                <p class="register-lead">
                    Your account for the <strong>Fibonacci Romania 2026</strong> has been created.
                </p>

                <p class="register-text">
                    We’ve sent a verification email to your inbox. <br> Please open it and click the verification link to activate your account.
                    <br>If you don’t see the email after a few minutes, check your <strong>Spam</strong>/<strong>Promotions</strong> folders.
                </p>


                <p class="register-text">
                    See you in <strong>Buzău, Romania</strong>, from
                    <time datetime="2026-02-27">February 27</time> to
                    <time datetime="2026-03-01">March 1, 2026</time>.
                </p>

                <p class="register-text"><strong>Add to calendar:</strong></p>
                <div class="register-actions">
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

                <a class="template-btn btn-style-one mt-5" href="/ucp/login.php">
                    <span class="btn-wrap">
                        <span class="text-one">Go to Login</span>
                        <span class="text-two">Go to Login</span>
                    </span>
                </a>
            </div>
        <?php } else  if (strtotime(date('Y-m-d')) < strtotime($settings['registration_open'])) {
            echo '<audio id="registrationSound" src="/assets/sounds/ucp/registration-not-opened.mp3" autoplay></audio>';
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

            <div class="inner-container">
                <h3 class="text-center mb-3">Registration</h3>
                <p class="text text-center mb-3">
                    <strong><u>ONLY</u> One account per team.</strong> <br> Please do <u>not</u> create separate accounts for each member.<br>
                    <strong><u>IMPORTANT</u>:</strong> The Coach (or the person who registered the team) cannot be assigned to any robot.<br>
                    If they also want to participate as a team member, they must be added again using a different email and phone number.
                </p>


                <div class="default-form register-form">
                    <form method="post" id="registration-form">

                        <div class="row clearfix">
                            <div class="form-group col-lg-6 col-md-6 col-sm-6">
                                <input type="text" name="fullName" id="fullName" value="" placeholder="Full Name*" required>
                            </div>

                            <div class="form-group col-lg-6 col-md-6 col-sm-6">
                                <input type="email" name="email" id="email" value="" placeholder="Email*" required>
                            </div>

                            <div class="form-group col-lg-6 col-md-6 col-sm-6">
                                <input type="password" name="password" id="password" value="" placeholder="Password*" required>
                            </div>

                            <div class="form-group col-lg-6 col-md-6 col-sm-6">
                                <input type="password" name="confirm-password" id="confirm-password" value="" placeholder="Confirm password*" required>
                            </div>

                            <div class="form-group col-lg-6 col-md-6 col-sm-6">
                                <input type="text" name="phone" id="phone" value="" placeholder="Phone number*" required>
                            </div>

                            <div class="form-group col-lg-6 col-md-6 col-sm-6">
                                <select name="role" id="role" class="custom-select-box" data-validation="required">
                                    <option value="" selected disabled>Select your role*</option>
                                    <option value="coach">Coach</option>
                                    <option disabled value="team_leader">Team leader</option>
                                    <option disabled value="member">Member</option>
                                </select>
                            </div>

                            <div class="form-group col-lg-6 col-md-6 col-sm-6">
                                <select name="org_type" id="org_type" class="custom-select-box" data-validation="required">
                                    <option value="" selected disabled>Organization Type*</option>
                                    <option value="School">School</option>
                                    <option value="Club">Club</option>
                                    <option value="Company">Company</option>
                                    <option value="Independent">Independent</option>
                                </select>
                            </div>

                            <div class="form-group col-lg-6 col-md-6 col-sm-6">
                                <input type="text" name="org_name" id="org_name" value="" placeholder="Organization Name*" required>
                            </div>

                            <div class="form-group col-lg-6 col-md-6 col-sm-6">
                                <input type="text" name="country" id="country" value="" placeholder="Country*" required>
                            </div>

                            <div class="form-group col-lg-6 col-md-6 col-sm-6">
                                <input type="text" name="city" id="city" value="" placeholder="City*" required>
                            </div>


                        </div>


                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4"></div>
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                    <!-- Button Box -->
                                    <button type="submit" class="btn-style-one submit-btn template-btn">
                                        <span class="btn-wrap">
                                            <span class="text-one">Register Now</span>
                                            <span class="text-two">Register Now</span>
                                        </span>
                                    </button>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4"></div>
                        </div>

                        <div class="form-group">
                            <div class="creat-account">Do you already have an account? <a href="/ucp/login.php">Log in now</a></div>
                        </div>

                    </form>

                </div>
            </div>

        <?php } ?>
    </div>
</section>