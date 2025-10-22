<?php

$admin = new Admin($conn);
$users = $admin->getUsers();

?>

<div class="row mb-4 mb-xl-6 mb-xxl-4 gy-3 px-4 px-lg-6 pt-6 justify-content-between">
    <div class="col-auto">
        <h2 class="mb-0 text-body-emphasis">Manage Coaches</h2>
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
            <div class="d-flex align-items-center"><a class="btn btn-primary" href="/ucp/admin/users/create"><span class="fas fa-plus me-2"></span>Add coach</a></div>
        </div>
    </div>
    <h4 class="text-center">
        Note: The <span class="red bold">Coach</span> (or the <span class="red bold">person who registered the team</span>) <span class="red bold">cannot be assigned</span> to any robot. <br>
        If this person also wishes to participate as a team member, they must be added again with different details for email and phone number.
    </h4>
    <div class="mx-n4 mx-lg-n6 px-4 px-lg-6 mb-9 bg-body-emphasis border-y mt-2 position-relative top-1">
        <div class="table-responsive scrollbar ms-n1 ps-1">
            <table class="table table-sm fs-9 mb-0">
                <thead>
                    <tr>
                        <th class="white-space-nowrap fs-9 align-middle ps-0">
                            <div class="form-check mb-0 fs-8"><input class="form-check-input" id="checkbox-bulk-members-select" type="checkbox" data-bulk-select='{"body":"members-table-body"}' /></div>
                        </th>
                        <th class="sort align-middle" scope="col" data-sort="fullName" style="width:15%; min-width:200px;">FULL NAME</th>
                        <th class="sort align-middle" scope="col" data-sort="email" style="width:15%; min-width:200px;">EMAIL</th>
                        <th class="sort align-middle pe-3" scope="col" data-sort="phone_number" style="width:20%; min-width:200px;">PHONE NUMBER</th>
                        <th class="sort align-middle" scope="col" data-sort="team" style="width:10%;">TEAM</th>
                        <th class="sort align-middle text-center" scope="col" data-sort="members" style="width:21%;  min-width:200px;">MEMBERS</th>
                        <th class="sort align-middle pe-0 text-center" scope="col" data-sort="robots" style="width:19%;  min-width:200px;">ROBOTS</th>
                        <th class="sort align-middle pe-0" scope="col" data-sort="edit_coach" style="width:19%;  min-width:200px;">EDIT MEMBER</th>
                    </tr>
                </thead>
                <tbody class="list" id="members-table-body">
                    <?php foreach ($users as $user): ?>
                        <?php
                        $team = getTeamByUser($user["id"]);
                        ?>
                        <tr class="hover-actions-trigger btn-reveal-trigger position-static">
                            <td class="fs-9 align-middle ps-0 py-3">
                                <div class="form-check mb-0 fs-8"><input class="form-check-input" type="checkbox" data-bulk-select-row='{"customer":{"avatar":"<?= $team['logo'] ?? '/assets/images/users/placeholder.jpg' ?>","fullName":"<?= $member['full_name'] ?>"},"role":"<?= $member['role'] ?>"},"email":"<?= $member['email'] ?>","phone_number":"<?= $member['phone'] ?>","dob":"<?= $member['dob'] ?>","tshirt":"<?= $member['tshirt'] ?>","emergency_contact":"<?= $member['emergency_contact'] ?>"}' /></div>
                            </td>
                            <td class="customer align-middle white-space-nowrap">
                                <a class="d-flex align-items-center text-body text-hover-1000" href="#!">
                                    <div class="avatar avatar-m m-2"><img class="rounded-circle" src="<?= $team['logo'] ?? '/assets/images/users/placeholder.jpg' ?>" alt="<?= $team['name'] ?>" /></div>
                                    <h6 class="mb-0 ms-3 fw-semibold text-wrap" style="word-break: break-word;">
                                        <?= strlen($user['full_name']) > 20 ? wordwrap($user['full_name'], 20, '<br>', true) : $user['full_name'] ?>
                                    </h6>
                                </a>
                                <div class="text-center"><span class="fibo-badge fibo-<?= strtolower($user['role']) ?> fibo-badge--sm"><?= strtolower($user['role']) ?></span></div>
                            </td>
                            <td class="email align-middle white-space-nowrap"><a class="fw-semibold" href="mailto:<?= $user['email'] ?>"><?= $user['email'] ?></a></td>
                            <td class="phone_number align-middle white-space-nowrap"><a class="fw-bold text-body-emphasis" href="tel:<?= $user['phone'] ?>"><?= $user['phone'] ?></a></td>
                            <td class="team align-middle white-space-nowrap text-body"><a href="/ucp/admin/team/edit?c=<?= $team['code'] ?>"><?= $team['name'] ?> • <span class="fibo-teamcode"><?= $team['code'] ?></span></a></td>
                            <td class="members align-middle white-space-nowrap text-body-tertiary text-center"><a href="/ucp/admin/members/manage"><span class="fibo-badge fibo-marketing"><?= getTotalTeamMembers($team['id']) ?></span></a></td>
                            <td class="robots align-middle white-space-nowrap text-body-tertiary text-center"><a href="/ucp/admin/robots/manage"><span class="fibo-badge fibo-media"><?= getTotalTeamRobots($team['id']) ?></span></a></td>
                            <td class="edit_member align-middle white-space-nowrap text-body-tertiary"><a href="/ucp/admin/users/edit?id=<?= $user['id'] ?>"><span data-feather="edit-3"></span> Edit member</a></td>
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