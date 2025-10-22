<?php
if (!isset($_GET["token"]) || empty($_GET["token"])) {
    header("Location: /forgot-password");
    exit;
}
$token = $_GET["token"];
?>

<!-- Page Title -->
<section class="page-title">
    <div class="page-title-icon" style="background-image:url(assets/images/icons/page-title_icon-1.png)"></div>
    <div class="page-title-icon-two" style="background-image:url(assets/images/icons/page-title_icon-2.png)"></div>
    <div class="page-title-shadow" style="background-image:url(assets/images/background/page-title-1.png)"></div>
    <div class="page-title-shadow_two" style="background-image:url(assets/images/background/page-title-2.png)"></div>
    <div class="auto-container">
        <h2>Reset password</h2>
        <ul class="bread-crumb clearfix">
            <li><a href="/">Home</a></li>
            <li>Reset password</li>
        </ul>
    </div>
</section>
<!-- End Page Title -->

<!-- Register One -->
<section class="register-one">
    <div class="auto-container">
        <div class="inner-container">
            <h3>Reset password</h3>
            <div class="text">Your password will not be published.</div>

            <!-- Register Form -->
            <div class="register-form">
                <form method="post" id="reset-password">

                    <div class="form-group">
                        <label>New Password*</label>
                        <input type="password" name="new_password" id="new-password" placeholder="Enter new password" required="">
                    </div>
                    <div class="form-group">
                        <label>Confirm Password*</label>
                        <input type="password" name="confirm_password" id="confirm-password" placeholder="Confirm new password" required="">
                    </div>

                    <div class="form-group">
                        <!-- Button Box -->
                        <button type="submit" class="btn-style-one submit-btn template-btn">
                            <span class="btn-wrap">
                                <span class="text-one">Submit now</span>
                                <span class="text-two">Submit now</span>
                            </span>
                        </button>
                    </div>

                    <input type="hidden" name="token" id="token" value="<?php echo htmlspecialchars($token); ?>">

                    <div class="form-group">
                        <div class="creat-account">Back to <a href="/ucp/login">login</a></div>
                    </div>

                </form>
            </div>
            <!-- End Default Form -->

        </div>
    </div>
</section>
<!-- End Register One -->

<div class="container-fluid bg-body-tertiary dark__bg-gray-1200">
    <div class="bg-holder bg-auth-card-overlay" style="background-image:url(/assets/ucp//img/bg/37.png);"></div>
    <!--/.bg-holder-->
    <div class="row flex-center position-relative min-vh-100 g-0 py-5">
        <div class="col-11 col-sm-10 col-xl-8">
            <div class="card border border-translucent auth-card">
                <div class="card-body pe-md-0">
                    <div class="row align-items-center gx-0 gy-7">
                        <div class="col-auto bg-body-highlight dark__bg-gray-1100 rounded-3 position-relative overflow-hidden auth-title-box">
                            <div class="bg-holder" style="background-image:url(/assets/ucp//img/bg/38.png);"></div>
                            <!--/.bg-holder-->
                            <div class="position-relative px-4 px-lg-7 pt-7 pb-7 pb-sm-5 text-center text-md-start pb-lg-7">
                                <h3 class="mb-3 text-body-emphasis fs-7">Phoenix Authentication</h3>
                                <p class="text-body-tertiary">Give yourself some hassle-free development process with the uniqueness of Phoenix!</p>
                                <ul class="list-unstyled mb-0 w-max-content w-md-auto">
                                    <li class="d-flex align-items-center"><span class="uil uil-check-circle text-success me-2"></span><span class="text-body-tertiary fw-semibold">Fast</span></li>
                                    <li class="d-flex align-items-center"><span class="uil uil-check-circle text-success me-2"></span><span class="text-body-tertiary fw-semibold">Simple</span></li>
                                    <li class="d-flex align-items-center"><span class="uil uil-check-circle text-success me-2"></span><span class="text-body-tertiary fw-semibold">Responsive</span></li>
                                </ul>
                            </div>
                            <div class="position-relative z-n1 mb-6 d-none d-md-block text-center mt-md-15"><img class="auth-title-box-img d-dark-none" src="/assets/ucp//img/spot-illustrations/auth.png" alt="" /><img class="auth-title-box-img d-light-none" src="/assets/ucp//img/spot-illustrations/auth-dark.png" alt="" /></div>
                        </div>
                        <div class="col mx-auto">
                            <div class="auth-form-box">
                                <div class="text-center mb-7"><a class="d-flex flex-center text-decoration-none mb-4" href="../../../index.html">
                                        <div class="d-flex align-items-center fw-bolder fs-3 d-inline-block"><img src="/assets/images/logo/logo.webp" alt="phoenix" width="58" /></div>
                                    </a>
                                    <h4 class="text-body-highlight">Reset new password</h4>
                                    <p class="text-body-tertiary">Type your new password</p>
                                </div>
                                <form class="mt-5" id="reset-password">
                                    <div class="position-relative mb-2" data-password="data-password">
                                        <input class="form-control form-icon-input pe-6" name="new_password" id="new-password" type="password" placeholder="Type new password" data-password-input="data-password-input" />
                                        <button class="btn px-3 py-0 h-100 position-absolute top-0 end-0 fs-7 text-body-tertiary" data-password-toggle="data-password-toggle"><span class="uil uil-eye show"></span><span class="uil uil-eye-slash hide"></span></button>
                                    </div>
                                    <div class="position-relative mb-4" data-password="data-password">
                                        <input class="form-control form-icon-input pe-6" name="confirm_password" id="confirm-password" type="password" placeholder="Cofirm new password" data-password-input="data-password-input" />
                                        <button class="btn px-3 py-0 h-100 position-absolute top-0 end-0 fs-7 text-body-tertiary" data-password-toggle="data-password-toggle"><span class="uil uil-eye show"></span><span class="uil uil-eye-slash hide"></span></button>
                                    </div><button class="btn btn-primary w-100" type="submit">Set Password</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>