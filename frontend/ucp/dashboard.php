<?php
$managerTeamData = getUserDataById(getUserId());

?>
<div class="row mb-4 mb-xl-6 mb-xxl-4 gy-3 px-4 px-lg-6 pt-6 justify-content-between">
    <div class="col-auto">
        <h2 class="mb-0 text-body-emphasis">Fibonacci Romania Dashboard • <?= $settings['scoring_algorithm_version'] ?></h2>
    </div>
    <div class="col-auto">
        <div class="d-flex gap-3">
            <a class="btn btn-phoenix-primary" href="/ucp/members/add">
                <span class="fa-solid fa-plus me-2"></span>
                Add Team Member
            </a>
            <a class="btn btn-phoenix-primary" href="/ucp/robots/add">
                <span class="fa-solid fa-plus me-2"></span>
                Add Robot
            </a>
        </div>
    </div>
</div>

<div class="row gx-3 px-4 px-lg-6 pt-6 pb-9">
    <div class="col-xxl-7">
        <div class="row gx-7 pe-xxl-3">
            <div class="col-12 col-xl-5 col-xxl-12">
                <div class="row g-0">
                    <div class="col-6 col-xl-12 col-xxl-6 border-bottom border-end border-end-xl-0 border-end-xxl pb-4 pt-4 pt-xl-0 pt-xxl-4 pe-4 pe-sm-5 pe-xl-0 pe-xxl-5">
                        <h5 class="text-body mb-4">Robots — <?= $settings['competition_short_name'] ?> <?= $settings['edition_year'] ?> </h5>
                        <div class="d-md-flex flex-between-center">
                            <div class="echart-booking-value order-1 order-sm-0 order-md-1" style="height:54px; width: 90px"></div>
                            <div class="mt-4 mt-md-0">
                                <h3 class="text-body-highlight mb-2"><?= getTotalRobots() ?> in <?= getMonthlyRobotGrowthPercent()['current_month'] ?></h3>
                                <span class="badge badge-phoenix badge-phoenix-primary me-2 fs-10">
                                    <span class="fa-solid fa-plus me-1"></span>
                                    <?= getMonthlyRobotGrowthPercent()['percent'] ?>% <!-- +- roboti in ultima luna  -->
                                </span>
                                <span class="fs-9 text-body-secondary d-block d-sm-inline mt-1">Compared to <?= getMonthlyRobotGrowthPercent()['compare_month'] ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-12 col-xxl-6 border-bottom py-4 ps-4 ps-sm-5 ps-xl-0 ps-xxl-5">
                        <h5 class="text-body mb-4">Members — <?= $settings['competition_short_name'] ?> <?= $settings['edition_year'] ?> </h5>
                        <div class="d-md-flex flex-between-center">
                            <div class="mt-3 mt-md-0">
                                <h3 class="text-body-highlight mb-2"><?= getTotalMembers() ?> in <?= getMonthlyMemberGrowthPercent()['current_month'] ?></h3>
                                <span class="badge badge-phoenix badge-phoenix-success me-2 fs-10">
                                    <span class="fa-solid fa-plus me-1"></span>
                                    <?= getMonthlyMemberGrowthPercent()['percent'] ?>%
                                </span>
                                <span class="fs-9 text-body-secondary text-nowrap d-block d-sm-inline mt-1">Compared to <?= getMonthlyMemberGrowthPercent()['compare_month'] ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-12 col-xxl-6 border-bottom-xl border-bottom-xxl-0 border-end border-end-xl-0 border-end-xxl py-4 pe-4 pe-sm-5 pe-xl-0 pe-xxl-5">
                        <h5 class="text-body mb-4">Robots — Team Total</h5>
                        <div class="d-md-flex flex-between-center">
                            <div class="echart-commission order-sm-0 order-md-1" style="height: 54px; width: 54px"></div>
                            <div class="mt-3 mt-md-0">
                                <h3 class="text-body-highlight mb-2"><?= getTotalTeamRobots($team['id']) ?></h3>
                                <span class="badge badge-phoenix badge-phoenix-primary me-2 fs-10">
                                    <span class="fa-solid fa-plus me-1"></span>
                                    <?= getTeamTotalCategories($team['id']) ?></span>
                                <span class="fs-9 text-body-secondary d-block d-sm-inline mt-1">total team categories </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-12 col-xxl-6 py-4 ps-4 ps-sm-5 ps-xl-0 ps-xxl-5">
                        <h5 class="text-body mb-4">Members — Team Total</h5>
                        <div class="d-md-flex flex-between-center">
                            <div class="chart-cancel-booking order-sm-0 order-md-1" style="height:54px; width:78px"></div>
                            <div class="mt-3 mt-md-0">
                                <h3 class="text-body-highlight mb-2"><?= getTotalTeamMembers($team['id']) ?></h3>
                                <span class="badge badge-phoenix badge-phoenix-success me-2 fs-10"><span class="fa-solid fa-plus me-1"></span>
                                    <?= getTotalMembersLast30d($team['id']) ?>
                                </span>
                                <span class="fs-9 text-body-secondary d-block d-sm-inline mt-1">Last 30 Days • <strong><?= getTotalMembers() ?></strong> in <?= $settings['competition_short_name'] ?> <?= $settings['edition_year'] ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Another div -->


        </div>
    </div>
    <div class="col-xxl-5">
        <div class="row g-3">
            <div class="col-12 col-md-6 col-xxl-12">
                <div class="card border h-100 w-100 overflow-hidden">
                    <div class="bg-holder d-block bg-card" style="background-image:url(/assets/ucp/img/spot-illustrations/32.png);background-position: top right;"></div>
                    <!--/.bg-holder-->
                    <div class="d-dark-none">
                        <div class="bg-holder d-none d-sm-block d-xl-none d-xxl-block bg-card" style="background-image:url(/assets/ucp/img/spot-illustrations/21.png);background-position: bottom right; background-size: auto;"></div>
                        <!--/.bg-holder-->
                    </div>
                    <div class="d-light-none">
                        <div class="bg-holder d-none d-sm-block d-xl-none d-xxl-block bg-card" style="background-image:url(/assets/ucp/img/spot-illustrations/dark_21.png);background-position: bottom right; background-size: auto;"></div>
                        <!--/.bg-holder-->
                    </div>

                    <div class="card-body px-5 position-relative">
                        <div class="badge badge-phoenix fs-10 badge-phoenix-primary mb-4">
                            <span class="fw-bold">Team Profile</span><span class="fa-solid fa-users ms-1"></span>
                        </div>

                        <h3 class="mb-1"><?= $team['name'] ?></h3>
                        <p class="text-body-tertiary fw-semibold mb-4">
                            Team Code:
                            <span class="badge bg-dark-subtle text-light rounded-pill"><?= $team['code'] ?></span>
                            • Edition: <strong><?= $settings['competition_short_name'] ?> <?= $settings['edition_year'] ?></strong>
                            • Status: <span class="badge bg-dark text-light"><?= $team['status'] ?></span>
                        </p>

                        <div class="row g-3 small">
                            <div class="col-md-6 d-flex align-items-center gap-2">
                                <i class="fa-solid fa-location-dot text-body-tertiary"></i>
                                <span><?= $team['city'] ?>, <?= $team['country'] ?></span>
                            </div>
                            <div class="col-md-6 d-flex align-items-center gap-2">
                                <i class="fa-solid fa-user-tie text-body-tertiary"></i>
                                <span><?= $managerTeamData['full_name'] ?></span>
                                <a class="ms-2" href="mailto:<?= $managerTeamData['email'] ?>"><?= $managerTeamData['email'] ?></a>
                            </div>
                            <div class="col-md-6 d-flex align-items-center gap-2">
                                <i class="fa-solid fa-building text-body-tertiary"></i>
                                <span><?= $team['org_name'] ?></span>
                            </div>
                            <div class="col-md-6 d-flex align-items-center gap-2">
                                <i class="fa-solid fa-envelope text-body-tertiary"></i>
                                <a href="mailto:<?= $team['email'] ?>"><?= $team['email'] ?></a>
                                <span class="text-body-tertiary">•</span>
                                <i class="fa-solid fa-phone text-body-tertiary"></i>
                                <span><?= $team['phone'] ?></span>
                            </div>
                            <div class="col-md-6 d-flex align-items-center gap-2">
                                <i class="fa-solid fa-globe text-body-tertiary"></i>
                                <a href="<?= $team['website'] ?>" target="_blank" rel="noopener"><?= $team['website'] ?></a>
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="small text-body-tertiary mb-1">Registration progress</div>
                            <div class="progress" style="height:8px;">
                                <div class="progress-bar" role="progressbar" style="width:<?= getRegistrationProgress() ?>%;" aria-valuenow="<?= getRegistrationProgress() ?>" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="small mt-1"><?= getRegistrationProgress() ?>% complete</div>
                            <div class="mt-2 text-body-tertiary">Registration next step: <strong><?= registrationNextStep() ?></strong></div>
                        </div>
                    </div>

                    <div class="card-footer border-0 py-3 px-5 z-1">
                        <div class="row g-3">
                            <div class="col-6 col-md-3">
                                <div class="p-2 rounded border bg-body-tertiary d-flex justify-content-between align-items-center">
                                    <span class="small text-body-tertiary">Robots <br><?= $settings['competition_short_name'] ?> <?= $settings['edition_year'] ?></span>
                                    <span class="fw-semibold"><?= getTotalRobots() ?></span>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="p-2 rounded border bg-body-tertiary d-flex justify-content-between align-items-center">
                                    <span class="small text-body-tertiary">Members <br><?= $settings['competition_short_name'] ?> <?= $settings['edition_year'] ?></span>
                                    <span class="fw-semibold"><?= getTotalMembers() ?></span>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="p-2 rounded border bg-body-tertiary d-flex justify-content-between align-items-center">
                                    <span class="small text-body-tertiary">Robots <br>Team Total</span>
                                    <span class="fw-semibold"><?= getTotalTeamRobots($team['id']) ?></span>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="p-2 rounded border bg-body-tertiary d-flex justify-content-between align-items-center">
                                    <span class="small text-body-tertiary">Members <br>Team Total</span>
                                    <span class="fw-semibold"><?= getTotalTeamMembers($team['id']) ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-2 mt-3">
                            <span class="badge bg-secondary-subtle text-primary">Team Categories: <strong class="ms-1"><?= getTeamTotalCategories($team['id']) ?></strong></span>
                            <span class="badge bg-warning-subtle text-warning">Pending invites: <strong class="ms-1">Unavailable</strong></span>
                            <span class="badge bg-info-subtle text-info">Documents: <strong class="ms-1">Not required</strong></span>
                            <span class="badge bg-warning-subtle text-warning">Payment amount: <strong class="ms-1"><?= totalAmountToPay() ?> USD</strong></span>
                        </div>

                        <div class="mt-3 d-flex flex-wrap gap-2">
                            <a href="/ucp/team/edit" class="btn btn-sm btn-primary">Edit Team</a>
                            <a href="/ucp/robots/view" class="btn btn-sm btn-outline-secondary">Manage Robots</a>
                            <a href="/ucp/members/view" class="btn btn-sm btn-outline-secondary">Manage Members</a>
                        </div>
                    </div>
                </div>

            </div>


            <!-- Another div -->



        </div>
    </div>
</div>