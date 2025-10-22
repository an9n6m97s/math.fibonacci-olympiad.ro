<?php

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: /ucp/admin/robots/manage");
    exit;
}

$categories = getCategories();
$robot = getTeamRobotById($_GET["id"]);
$team = getTeamById($robot['team_id']);
$members = getTeamMembers($team['id']);

$category_slugs = !empty($robot['category_slug']) ? array_map('trim', explode(',', $robot['category_slug'])) : [];
$member_ids = !empty($robot['member_ids']) ? array_map('trim', explode(',', $robot['member_ids'])) : [];
?>

<div class="row mb-4 mb-xl-6 mb-xxl-4 gy-3 px-4 px-lg-6 pt-6 justify-content-between">
    <div class="col-auto">
        <h2 class="mb-0 text-body-emphasis">Edit Robot Details</h2>
    </div>
</div>

<div class="row gx-3 px-4 px-lg-6 pt-6">
    <form class="row g-3 needs-validation" novalidate="novalidate" id="admin_robot_edit_form">
        <div class="col-md-3">
            <label class="form-label" for="robot_name">Robot Name</label>
            <input class="form-control" id="robot_name" name="robot_name" type="text" value="<?= $robot['name'] ?>" required="" />
            <div class="valid-feedback">Name looks complete — thank you.</div>
            <div class="invalid-feedback">Please enter your first name.</div>
        </div>
        <div class="col-md-3">
            <label class="form-label" for="robot_category">Robot Category</label>
            <select class="form-select" id="robot_category" data-choices="data-choices" multiple="multiple" size="1" name="robot_category" required="required" data-options='{"removeItemButton":true,"placeholder":true}'>
                <option value="">Select category...</option>
                <?php foreach ($categories as $category) : ?>
                    <option value="category_<?= $category['slug'] ?>" <?= in_array($category['slug'], $category_slugs) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($category['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <div class="invalid-feedback">Please select one or multiple</div>
        </div>
        <div class="d-none d-md-block col-md-3"></div>
        <div class="d-none d-md-block col-md-3"></div>
        <div class="col-md-3">
            <label class="form-label" for="robot_operator">Robot Operator</label>
            <select class="form-select" id="robot_operator" data-choices="data-choices" size="1" required="required" name="robot_operator" data-options='{"removeItemButton":true,"placeholder":true}'>
                <option value="" disabled>Select operator...</option>
                <?php foreach ($members as $member) : ?>
                    <option value="member_<?= $member['id'] ?>" <?= ($robot['operator'] == $member['id']) ? 'selected' : '' ?>><?= htmlspecialchars($member['full_name']) ?></option>
                <?php endforeach; ?>
            </select>
            <div class="valid-feedback">The robot operator has been entered correctly.</div>
            <div class="invalid-feedback">Please complete the name of the robot operator.</div>
        </div>
        <div class="col-md-3">
            <label class="form-label" for="robot_members">Robot Members</label>
            <select class="form-select" id="robot_members" data-choices="data-choices" multiple="multiple" size="1" name="robot_members" required="required" data-options='{"removeItemButton":true,"placeholder":true}'>
                <option value="">Select members...</option>
                <?php foreach ($members as $member) : ?>
                    <option value="member_<?= $member['id'] ?>" <?= in_array($member['id'], $member_ids) ? 'selected' : '' ?>><?= htmlspecialchars($member['full_name']) ?></option>
                <?php endforeach; ?>
            </select>
            <div class="invalid-feedback">Please select one or multiple</div>
        </div>

        <div class="col-12">
            <div class="form-check">
                <input class="form-check-input" id="invalidCheck" type="checkbox" value="" required="" />
                <label class="form-check-label mb-0" for="invalidCheck">By checking this box, I confirm that the robot complies with all safety rules and regulations of the competition.</label>
                <div class="valid-feedback">Consent recorded — thank you.</div>
                <div class="invalid-feedback">You must confirm safety consent before proceeding.</div>
            </div>
        </div>

        <input type="hidden" name="user_id" value="<?= $_SESSION[getenv('SESSION_USER_ID')] ?>">
        <input type="hidden" name="team_id" value="<?= $team['id'] ?>">
        <input type="hidden" name="robot_id" value="<?= $robot['id'] ?>">

        <div class="col-12">
            <button class="btn btn-primary" type="submit">Edit Robot</button>
        </div>
    </form>
</div>