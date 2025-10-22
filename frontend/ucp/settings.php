<?php

if (!isAdmin()) {
    header("Location: /ucp/dashboard");
    exit;
}

?>

<section class="pt-5 pb-9">
    <div class="container-small">
        <div class="row align-items-center justify-content-between g-3 mb-4">
            <div class="col-auto">
                <h2 class="mb-0">Profile</h2>
            </div>
            <div class="col-auto">
                <div class="row g-2 g-sm-3">
                    <div class="col-auto"><button class="btn btn-phoenix-danger" onclick="notify('For this action please contact our team via contact form or phone number.', 4000)"><span class="fas fa-trash-alt me-2"></span>Delete team & account</button></div>
                    <div class="col-auto"><button class="btn btn-phoenix-secondary" onclick="notify('If the above form didn\'t work please contact our team via contact form or phone number.', 4000)"><span class="fas fa-key me-2"></span>Reset password</button></div>
                </div>
            </div>
        </div>
        <div class="row g-3 mb-6">
            <div class="col-12 col-lg-8">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="border-bottom border-dashed pb-4">
                            <div class="row align-items-center g-3 g-sm-5 text-center text-sm-start">
                                <div class="col-12 col-sm-auto"><input class="d-none" id="avatarFile" type="file" /><label class="cursor-pointer avatar avatar-5xl" for="avatarFile"><img class="rounded-circle" src="<?= $team['logo'] ?? '/assets/images/users/placeholder.jpg' ?>" alt="" /></label></div>
                                <div class="col-12 col-sm-auto flex-1">
                                    <div class="d-flex align-center gap-2 mb-2">
                                        <h3><?= $userData['full_name'] ?></h3>
                                        <span class="fibo-badge fibo-coach">Coach</span>
                                    </div>
                                    <p class="text-body-secondary">
                                        <?= $team["name"] ?> <br>
                                        Joined <?= userJoinedTimeAgo() ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-between-center pt-4">
                            <div>
                                <h6 class="mb-2 text-body-secondary">Public ID</h6>
                                <h4 class="fs-7 text-body-highlight mb-0"><?= $userData['public_id'] ?></h4>
                            </div>
                            <div class="text-end">
                                <h6 class="mb-2 text-body-secondary text-center">Team Code</h6>
                                <h4 class="fs-7 text-body-highlight mb-0 text-center"><span class="fibo-teamcode"><?= $team['code'] ?></span></h4>
                            </div>
                            <div class="text-end">
                                <h6 class="mb-2 text-body-secondary text-center">Email status</h6>
                                <h4 class="fs-7 text-body-highlight mb-0 text-center"><span class="fibo-badge fibo-email-verified">Verified</span></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="border-bottom border-dashed">
                            <h4 class="mb-3">Team Details<button class="btn btn-link p-0" type="button" onclick="window.location.href='/ucp/team/view';"> <span class="fas fa-edit fs-9 ms-3 text-body-quaternary"></span></button></h4>
                        </div>
                        <div class="pt-4 mb-7 mb-lg-4 mb-xl-7">
                            <div class="row justify-content-between">
                                <div class="col-auto">
                                    <h5 class="text-body-highlight">Team Name</h5>
                                </div>
                                <div class="col-auto">
                                    <p class="text-body-secondary"><span class="fibo-badge fibo-team-name"><?= $team['name'] ?></span></p>
                                </div>
                            </div>
                        </div>
                        <div class="border-top border-dashed pt-4">
                            <div class="row flex-between-center mb-2">
                                <div class="col-auto">
                                    <h5 class="text-body-highlight mb-0">Email</h5>
                                </div>
                                <div class="col-auto"><a class="lh-1" href="mailto:<?= $team['email'] ?>"><?= $team['email'] ?></a></div>
                            </div>
                            <div class="row flex-between-center">
                                <div class="col-auto">
                                    <h5 class="text-body-highlight mb-0">Phone</h5>
                                </div>
                                <div class="col-auto"><a href="tel:<?= $team['phone'] ?>"><?= $team['phone'] ?></a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <div class="scrollbar">
                <ul class="nav nav-underline fs-9 flex-nowrap mb-3 pb-1" id="myTab" role="tablist">
                    <li class="nav-item me-3"><a class="nav-link text-nowrap active" id="personal-info-tab" data-bs-toggle="tab" href="#tab-personal-info" role="tab" aria-controls="tab-personal-info" aria-selected="true"><span class="fas fa-user me-2"></span>Personal info</a></li>
                </ul>
            </div>
            <form class="tab-content" id="profileUpdateForm">
                <div class="tab-pane fade show active" id="tab-personal-info" role="tabpanel" aria-labelledby="personal-info-tab">
                    <div class="row gx-3 gy-4 mb-5">
                        <div class="col-12 col-lg-6">
                            <label class="form-label text-body-highlight fs-8 ps-0 text-capitalize lh-sm" for="fullName">Full name</label>
                            <input class="form-control" id="fullName" type="text" placeholder="Full name" value="<?= $userData['full_name'] ?>" />
                        </div>
                        <div class="col-12 col-lg-6">
                            <label class="form-label text-body-highlight fs-8 ps-0 text-capitalize lh-sm" for="password">New Password</label>
                            <input class="form-control" id="password" type="text" placeholder="Leave blank if you don't want to change it" />
                        </div>

                        <div class="col-12 col-lg-6">
                            <label class="form-label text-body-highlight fs-8 ps-0 text-capitalize lh-sm" for="email">Email</label>
                            <input class="form-control" id="email" type="text" placeholder="Email" value="<?= $userData['email'] ?>" />
                        </div>

                        <div class="col-12 col-lg-6">
                            <label class="form-label text-body-highlight fs-8 ps-0 text-capitalize lh-sm" for="phone">Phone Number</label>
                            <input class="form-control" id="phone" type="text" placeholder="Email" value="<?= $userData['phone'] ?>" />
                        </div>

                        <div class="col-12 col-lg-6">
                            <label class="form-label text-body-highlight fs-8 ps-0 text-capitalize lh-sm" for="org_type">Organization Type</label>
                            <select class="form-select" id="org_type" data-choices="data-choices" size="1" required="required" name="org_type" data-options='{"removeItemButton":true,"placeholder":true}'>
                                <option value="" disabled>Select type...</option>
                                <option value="School" <?= $userData['org_type'] == 'School' ? 'selected' : '' ?>>School</option>
                                <option value="Club" <?= $userData['org_type'] == 'Club' ? 'selected' : '' ?>>Club</option>
                                <option value="Company" <?= $userData['org_type'] == 'Company' ? 'selected' : '' ?>>Company</option>
                                <option value="Independent" <?= $userData['org_type'] == 'Independent' ? 'selected' : '' ?>>Independent</option>
                            </select>
                        </div>

                        <div class="col-12 col-lg-6">
                            <label class="form-label text-body-highlight fs-8 ps-0 text-capitalize lh-sm" for="org_name">Organization Name</label>
                            <input class="form-control" id="org_name" type="text" placeholder="Email" value="<?= $userData['org_name'] ?>" />
                        </div>

                        <div class="col-12 col-lg-6">
                            <label class="form-label text-body-highlight fs-8 ps-0 text-capitalize lh-sm" for="country">Country</label>
                            <input class="form-control" id="country" type="text" placeholder="Email" value="<?= $userData['country'] ?>" />
                        </div>

                        <div class="col-12 col-lg-6">
                            <label class="form-label text-body-highlight fs-8 ps-0 text-capitalize lh-sm" for="city">City</label>
                            <input class="form-control" id="city" type="text" placeholder="Email" value="<?= $userData['city'] ?>" />
                        </div>

                        <input type="hidden" name="user_id" id="user_id" value="<?= $userData['id'] ?>">

                    </div>
                    <div class="text-end"><button class="btn btn-primary px-7">Save changes</button></div>
                </div>
            </form>
        </div>
    </div><!-- end of .container-->
</section>