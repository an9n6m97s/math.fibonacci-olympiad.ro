<?php if (!userHasTeam()) { ?>
    <div class="fibo-alert-card fibo-click" role="alert" data-href="/ucp/team/create">
        <div class="fibo-alert-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="fibo-alert-body">
            <h5>Action Required</h5>
            <p>You need to add team details before adding members.</p>

            <a class="fibo-action" href="/ucp/team/create">
                <i class="fas fa-plus"></i>
                <span>Add Team Details</span>
            </a>
        </div>
    </div>
<?php return;
}  ?>

<?php
$teamMembers = getTeamMembers($team['id']);

?>

<div class="row mb-4 mb-xl-6 mb-xxl-4 gy-3 px-4 px-lg-6 pt-6 justify-content-between">
    <div class="col-auto">
        <h2 class="mb-0 text-body-emphasis">View Members</h2>
    </div>
</div>

<div id="members" data-list='{"valueNames":["fullName","role","email","phone_number","dob","tshirt","emergency_contact"],"page":10,"pagination":true}'>
    <div class="row align-items-center justify-content-between g-3 mb-4">
        <div class="col col-auto">
            <div class="search-box">
                <form class="position-relative"><input class="form-control search-input search" type="search" placeholder="Search members" aria-label="Search" />
                    <span class="fas fa-search search-box-icon"></span>
                </form>
            </div>
        </div>
        <div class="col-auto">
            <div class="d-flex align-items-center"><a class="btn btn-primary" href="/ucp/members/create"><span class="fas fa-plus me-2"></span>Add member</a></div>
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
                        <th class="sort align-middle" scope="col" data-sort="dob" style="width:10%;">DATE OF BIRTH</th>
                        <th class="sort align-middle text-center" scope="col" data-sort="tshirt" style="width:21%;  min-width:200px;">T-SHIRT</th>
                        <th class="sort align-middle pe-0" scope="col" data-sort="emergency_contact" style="width:19%;  min-width:200px;">EMERGENCY CONTACT</th>
                        <th class="sort align-middle pe-0" scope="col" data-sort="edit_member" style="width:19%;  min-width:200px;">EDIT MEMBER</th>
                        <th class="sort align-middle pe-0" scope="col" data-sort="delete_member" style="width:19%;  min-width:200px;">DELETE MEMBER</th>
                    </tr>
                </thead>
                <tbody class="list" id="members-table-body">
                    <tr class="hover-actions-trigger btn-reveal-trigger position-static">
                        <td class="fs-9 align-middle ps-0 py-3">
                            <div class="form-check mb-0 fs-8"><input class="form-check-input" type="checkbox" data-bulk-select-row='{"customer":{"avatar":"<?= $team['logo'] ?? '/assets/images/users/placeholder.jpg' ?>","fullName":"<?= $userData['full_name'] ?>"},"role":"Coach"},"email":"<?= $userData['email'] ?>","phone_number":"<?= $userData['phone'] ?>","dob":"N/A","tshirt":"N/A","emergency_contact":"N/A"}' /></div>
                        </td>
                        <td class="customer align-middle white-space-nowrap">
                            <a class="d-flex align-items-center text-body text-hover-1000" href="#!">
                                <div class="avatar avatar-m m-2"><img class="rounded-circle" src="<?= $team['logo'] ?? '/assets/images/users/placeholder.jpg' ?>" alt="<?= $team['name'] ?>" /></div>
                                <h6 class="mb-0 ms-3 fw-semibold"><?= $userData['full_name'] ?></h6>
                            </a>
                            <div class="text-center"><span class="fibo-badge fibo-coach fibo-badge--sm">Coach</span></div>
                        </td>
                        <td class="email align-middle white-space-nowrap"><a class="fw-semibold" href="mailto:<?= $userData['email'] ?>"><?= $userData['email'] ?></a></td>
                        <td class="phone_number align-middle white-space-nowrap"><a class="fw-bold text-body-emphasis" href="tel:<?= $userData['phone'] ?>"><?= $userData['phone'] ?></a></td>
                        <td class="dob align-middle white-space-nowrap text-body">N/A</td>
                        <td class="tshirt align-middle white-space-nowrap text-body-tertiary text-center">N/A</td>
                        <td class="emergency_contact align-middle white-space-nowrap text-body-tertiary">N/A</td>
                        <td class="edit_member align-middle white-space-nowrap text-body-tertiary"><a href="/ucp/settings"><span data-feather="edit-3"></span> Edit member</a></td>
                        <td class="delete_member align-middle white-space-nowrap text-body-tertiary"><a href="#!" onclick="notify('Invalid action, you cannot delete coach from this table', 4000)"><span data-feather="trash-2"></span> Delete member</a></td>
                    </tr>
                    <?php foreach ($teamMembers as $member): ?>
                        <tr class="hover-actions-trigger btn-reveal-trigger position-static">
                            <td class="fs-9 align-middle ps-0 py-3">
                                <div class="form-check mb-0 fs-8"><input class="form-check-input" type="checkbox" data-bulk-select-row='{"customer":{"avatar":"<?= $team['logo'] ?? '/assets/images/users/placeholder.jpg' ?>","fullName":"<?= $member['full_name'] ?>"},"role":"<?= $member['role'] ?>"},"email":"<?= $member['email'] ?>","phone_number":"<?= $member['phone'] ?>","dob":"<?= $member['dob'] ?>","tshirt":"<?= $member['tshirt'] ?>","emergency_contact":"<?= $member['emergency_contact'] ?>"}' /></div>
                            </td>
                            <td class="customer align-middle white-space-nowrap">
                                <a class="d-flex align-items-center text-body text-hover-1000" href="#!">
                                    <div class="avatar avatar-m m-2"><img class="rounded-circle" src="<?= $team['logo'] ?? '/assets/images/users/placeholder.jpg' ?>" alt="<?= $team['name'] ?>" /></div>
                                    <h6 class="mb-0 ms-3 fw-semibold"><?= $member['full_name'] ?></h6>
                                </a>
                                <div class="text-center"><span class="fibo-badge fibo-<?= strtolower($member['role']) ?> fibo-badge--sm"><?= strtolower($member['role']) ?></span></div>
                            </td>
                            <td class="email align-middle white-space-nowrap"><a class="fw-semibold" href="mailto:<?= $member['email'] ?>"><?= $member['email'] ?></a></td>
                            <td class="phone_number align-middle white-space-nowrap"><a class="fw-bold text-body-emphasis" href="tel:<?= $member['phone'] ?>"><?= $member['phone'] ?></a></td>
                            <td class="dob align-middle white-space-nowrap text-body"><?= $member['dob'] ?></td>
                            <td class="tshirt align-middle white-space-nowrap text-body-tertiary text-center"><?= $member['tshirt'] ?></td>
                            <td class="emergency_contact align-middle white-space-nowrap text-body-tertiary"><?= $member['emergency_contact'] ?></td>
                            <td class="edit_member align-middle white-space-nowrap text-body-tertiary"><a href="/ucp/members/edit?id=<?= $member['id'] ?>"><span data-feather="edit-3"></span> Edit member</a></td>
                            <td class="delete_member align-middle white-space-nowrap text-body-tertiary"><a href="#!" onclick="deleteMember(<?= $team['id'] ?>, <?= $member['id'] ?>)"><span data-feather="trash-2"></span> Delete member</a></td>
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