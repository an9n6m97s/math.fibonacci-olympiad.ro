<?php

$admin = new Admin($conn);
$teams = $admin->getTeams();

?>

<div class="row mb-4 mb-xl-6 mb-xxl-4 gy-3 px-4 px-lg-6 pt-6 justify-content-between">
    <div class="col-auto">
        <h2 class="mb-0 text-body-emphasis">Manage Teams</h2>
    </div>
</div>

<div id="members" data-list='{"valueNames":["fullName","role","email","phone_number","team","members","robots"],"page":10,"pagination":true}'>
    <div class="row align-items-center justify-content-between g-3 mb-4">
        <div class="col col-auto">
            <div class="search-box">
                <form class="position-relative"><input class="form-control search-input search" type="search" placeholder="Search members" aria-label="Search" />
                    <span class="fas fa-search search-box-icon"></span>
                </form>
            </div>
        </div>
        <div class="col-auto">
            <div class="d-flex align-items-center"><a class="btn btn-primary" href="#!" onclick="notify('This step cannot be made by admin, only users can create their teams.')"><span class="fas fa-plus me-2"></span>Add team</a></div>
        </div>
    </div>
    <div class="mx-n4 mx-lg-n6 px-4 px-lg-6 mb-9 bg-body-emphasis border-y mt-2 position-relative top-1">
        <div class="table-responsive scrollbar ms-n1 ps-1">
            <table class="table table-sm fs-9 mb-0">
                <thead>
                    <tr>
                        <th class="white-space-nowrap fs-9 align-middle ps-0">
                            <div class="form-check mb-0 fs-8"><input class="form-check-input" id="checkbox-bulk-members-select" type="checkbox" data-bulk-select='{"body":"members-table-body"}' /></div>
                        </th>
                        <th class="sort align-middle" scope="col" data-sort="teamName" style="width:15%; min-width:200px;">TEAM NAME</th>
                        <th class="sort align-middle" scope="col" data-sort="coach" style="width:15%; min-width:200px;">COACH</th>
                        <th class="sort align-middle" scope="col" data-sort="email" style="width:15%; min-width:200px;">EMAIL</th>
                        <th class="sort align-middle pe-3" scope="col" data-sort="phone_number" style="width:20%; min-width:200px;">PHONE NUMBER</th>
                        <th class="sort align-middle pe-3" scope="col" data-sort="org_name" style="width:20%; min-width:200px;">ORGANIZATION NAME</th>
                        <th class="sort align-middle" scope="col" data-sort="city" style="width:10%;">CITY</th>
                        <th class="sort align-middle text-center" scope="col" data-sort="country" style="width:21%;  min-width:200px;">COUNTRY</th>
                        <th class="sort align-middle pe-0 text-center" scope="col" data-sort="website" style="width:19%;  min-width:200px;">WEBSITE</th>
                        <th class="sort align-middle pe-0" scope="col" data-sort="edit_team" style="width:19%;  min-width:200px;">EDIT TEAM</th>
                    </tr>
                </thead>
                <tbody class="list" id="members-table-body">
                    <?php foreach ($teams as $team): ?>

                        <tr class="hover-actions-trigger btn-reveal-trigger position-static">
                            <td class="fs-9 align-middle ps-0 py-3">
                                <div class="form-check mb-0 fs-8"><input class="form-check-input" type="checkbox" data-bulk-select-row='{"customer":{"avatar":"<?= $team['logo'] ?? '/assets/images/users/placeholder.jpg' ?>","fullName":"<?= $member['full_name'] ?>"},"role":"<?= $member['role'] ?>"},"email":"<?= $member['email'] ?>","phone_number":"<?= $member['phone'] ?>","dob":"<?= $member['dob'] ?>","tshirt":"<?= $member['tshirt'] ?>","emergency_contact":"<?= $member['emergency_contact'] ?>"}' /></div>
                            </td>
                            <td class="customer align-middle white-space-nowrap">
                                <a class="d-flex align-items-center text-body text-hover-1000" href="/ucp/admin/team/edit?c=<?= $team['code'] ?>">
                                    <div class="avatar avatar-m m-2"><img class="rounded-circle" src="<?= $team['logo'] ?? '/assets/images/users/placeholder.jpg' ?>" alt="<?= $team['name'] ?>" /></div>
                                    <h6 class="mb-0 ms-3 fw-semibold text-wrap" style="word-break: break-word;">
                                        <?= strlen($team['name']) > 20 ? wordwrap($team['name'], 20, '<br>', true) : $team['name'] ?>
                                    </h6>
                                </a>
                                <div class="text-center"><span class="fibo-teamcode"><?= $team['code'] ?></span></div>
                            </td>
                            <td class="coach align-middle white-space-nowrap"><a class="fw-semibold" href="/ucp/admin/users/edit?id=<?= $team['manager_id'] ?>"><?= strlen(getUserDataById($team['manager_id'])['full_name']) > 20 ? wordwrap(getUserDataById($team['manager_id'])['full_name'], 20, '<br>', true) : getUserDataById($team['manager_id'])['full_name'] ?></a></td>
                            <td class="email align-middle white-space-nowrap"><a class="fw-bold text-body-emphasis" href="mailto:<?= $team['email'] ?>"><?= $team['email'] ?></a></td>
                            <td class="phone_number align-middle white-space-nowrap"><a class="fw-bold text-body-emphasis" href="tel:<?= $team['phone'] ?>"><?= $team['phone'] ?></a></td>
                            <td class="org_name align-middle white-space-nowrap text-body"><?= strlen($team['org_name']) > 20 ? wordwrap($team['org_name'], 20, '<br>', true) : $team['org_name'] ?></td>
                            <td class="city align-middle white-space-nowrap text-body-tertiary text-center"><?= $team['city'] ?></td>
                            <td class="country align-middle white-space-nowrap text-body-tertiary text-center"><?= $team['country'] ?></td>
                            <td class="website align-middle white-space-nowrap text-body-tertiary text-center"><a href="<?= $team['website'] ?>"></span><?= strlen($team['website']) > 15 ? wordwrap($team['website'], 15, '<br>', true) : $team['website'] ?></a></td>
                            <td class="edit_team align-middle white-space-nowrap text-body-tertiary"><a href="/ucp/admin/team/edit?c=<?= $team['code'] ?>"><span data-feather="edit-3"></span> Edit team</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="row align-items-center justify-content-between py-2 pe-0 fs-9">
            <div class="col-auto d-flex">
                <p class="mb-0 d-none d-sm-block me-3 fw-semibold text-body" data-list-info="data-list-info"></p><a class="fw-semibold" href="#!" data-list-view="*">View all<span class="fas fa-angle-right ms-1" data-fa-transform="down-1"></span></a><a class="fw-semibold d-none" href="#!" data-list-view="less">View Less<span class="fas fa-angle-right ms-1" data-fa-transform="down-1"></span></a>
            </div>
            <div class="col-auto d-flex"><button class="page-link" data-list-pagination="prev"><span class="fas fa-chevron-left"></span></button>
                <ul class="mb-0 pagination"></ul><button class="page-link pe-0" data-list-pagination="next"><span class="fas fa-chevron-right"></span></button>
            </div>
        </div>
    </div>
</div>