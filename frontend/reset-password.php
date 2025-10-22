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