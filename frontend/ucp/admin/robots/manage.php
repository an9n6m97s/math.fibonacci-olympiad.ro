<?php

$admin = new Admin($conn);
$robots = $admin->getRobots();

?>


<div class="row mb-4 mb-xl-6 mb-xxl-4 gy-3 px-4 px-lg-6 pt-6 justify-content-between">
    <div class="col-auto">
        <h2 class="mb-0 text-body-emphasis">Manage Robots</h2>
    </div>
</div>

<div id="robots" data-list='{"valueNames":["name","category","operator","members"],"page":10,"pagination":true}'>
    <div class="row align-items-center justify-content-between g-3 mb-4">
        <div class="col col-auto">
            <div class="search-box">
                <form class="position-relative"><input class="form-control search-input search" type="search" placeholder="Search members" aria-label="Search" />
                    <span class="fas fa-search search-box-icon"></span>
                </form>
            </div>
        </div>
        <div class="col-auto">
            <div class="d-flex align-items-center"><a class="btn btn-primary" href="/ucp/admin/robots/create"><span class="fas fa-plus me-2"></span>Add robot</a></div>
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
                        <th class="sort align-middle" scope="col" data-sort="name" style="width:15%; min-width:200px;">NAME</th>
                        <th class="sort align-middle" scope="col" data-sort="role" style="width:15%; min-width:200px;">CATEGORY</th>
                        <th class="sort align-middle" scope="col" data-sort="operator" style="width:15%; min-width:200px;">OPERATOR</th>
                        <th class="sort align-middle pe-3" scope="col" data-sort="members" style="width:20%; min-width:200px;">MEMBERS</th>
                        <th class="sort align-middle pe-0" scope="col" data-sort="edit_member" style="width:19%;  min-width:200px;">EDIT ROBOT</th>
                        <th class="sort align-middle pe-0" scope="col" data-sort="delete_member" style="width:19%;  min-width:200px;">DELETE ROBOT</th>
                    </tr>
                </thead>
                <tbody class="list" id="robots-table-body">
                    <?php foreach ($robots as $robot): ?>
                        <?php
                        $team = getTeamById($robot['team_id']);

                        // Build members list
                        $member_ids = !empty($robot['member_ids']) ? array_map('trim', explode(',', $robot['member_ids'])) : [];
                        $members = array_map(function ($id) {
                            $member = getTeamMemberById($id);
                            return $member ? $member['full_name'] : null;
                        }, $member_ids);
                        $members = array_filter($members); // remove nulls
                        $members_string = implode(', ', $members);

                        // Build categories list
                        $category_slugs = !empty($robot['category_slug']) ? array_map('trim', explode(',', $robot['category_slug'])) : [];
                        $categories = array_map(function ($slug) {
                            $cat = getCategoryBySlug($slug);
                            return $cat ? $cat['name'] : null;
                        }, $category_slugs);
                        $categories = array_filter($categories);
                        $categories_string = implode(', ', $categories);

                        // Operator
                        $operator = getTeamMemberById($robot['operator']);
                        $operator_name = $operator ? $operator['full_name'] : '';
                        ?>
                        <tr class="hover-actions-trigger btn-reveal-trigger position-static">
                            <td class="fs-9 align-middle ps-0 py-3">
                                <div class="form-check mb-0 fs-8">
                                    <input class="form-check-input" type="checkbox"
                                        data-bulk-select-row='{
                            "customer": {"avatar":"<?= $team['logo'] ?? '/assets/images/users/placeholder.jpg' ?>","name":"<?= $robot['name'] ?>"},
                            "category":"<?= $categories_string ?>",
                            "operator":"<?= $operator_name ?>",
                            "members":"<?= $members_string ?>"
                        }' />
                                </div>
                            </td>
                            <td class="customer align-middle white-space-nowrap">
                                <a class="d-flex align-items-center text-body text-hover-1000" href="/ucp/admin/robots/edit?id=<?= $robot['id'] ?>">
                                    <div class="avatar avatar-m m-2">
                                        <img class="rounded-circle" src="<?= $team['logo'] ?? '/assets/images/users/placeholder.jpg' ?>" alt="<?= $team['name'] ?>" />
                                    </div>
                                    <?= strlen($robot['name']) > 20 ? wordwrap($robot['name'], 20, '<br>', true) : $robot['name'] ?>
                                </a>
                            </td>
                            <td class="category align-middle" style="white-space: normal;">
                                <?= implode('<br>', array_map(fn($c) => htmlspecialchars($c, ENT_QUOTES, 'UTF-8'), $categories)) ?>
                            </td>
                            <td class="operator align-middle white-space-nowrap text-body"><?= $operator_name ?></td>
                            <td class="members align-middle white-space-nowrap text-body-tertiary"><?= $members_string ?></td>
                            <td class="edit_robot align-middle white-space-nowrap text-body-tertiary">
                                <a href="/ucp/admin/robots/edit?id=<?= $robot['id'] ?>">
                                    <span data-feather="edit-3"></span> Edit robot
                                </a>
                            </td>
                            <td class="delete_member align-middle white-space-nowrap text-body-tertiary"><a href="#!" onclick="adminDeleteRobot(<?= $robot['team_id'] ?>, <?= $robot['id'] ?>)"><span data-feather="trash-2"></span> Delete robot</a></td>

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