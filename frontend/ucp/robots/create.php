<?php if (!userHasTeam()) { ?>
    <div class="fibo-alert-card fibo-click" role="alert" data-href="/ucp/team/create">
        <div class="fibo-alert-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="fibo-alert-body">
            <h5>Action Required</h5>
            <p>You need to add team details before creating robots.</p>

            <a class="fibo-action" href="/ucp/team/create">
                <i class="fas fa-plus"></i>
                <span>Add Team Details</span>
            </a>
        </div>
    </div>
<?php return;
}  ?>

<?php if (!teamHasMembers($team['id'])) { ?>
    <div class="fibo-alert-card fibo-click" role="alert" data-href="/ucp/members/create">
        <div class="fibo-alert-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="fibo-alert-body">
            <h5>Action Required</h5>
            <p>You need to add team members before creating robots.</p>

            <a class="fibo-action" href="/ucp/members/create">
                <i class="fas fa-plus"></i>
                <span>Add Team Members</span>
            </a>
        </div>
    </div>
<?php return;
}  ?>

<?php
$categories = getCategories();
$members = getTeamMembers($team['id']);
$singleMember = (count($members) === 1);
?>

<div class="row mb-4 mb-xl-6 mb-xxl-4 gy-3 px-4 px-lg-6 pt-6 justify-content-between">
    <div class="col-auto">
        <h2 class="mb-0 text-body-emphasis">Add Robot Details</h2>
    </div>
</div>

<?php if ($singleMember): ?>
    <div class="alert alert-info mb-4">
        <i class="fas fa-info-circle me-2"></i>
        There is only one member in your team. Operator and members are automatically set to <strong><?= htmlspecialchars($members[0]['full_name']) ?></strong>.
    </div>
<?php endif; ?>

<div class="row gx-3 px-4 px-lg-6 pt-6">
    <form class="row g-3 needs-validation" novalidate="novalidate" id="robot_create_form">
        <div class="col-md-3">
            <label class="form-label" for="robot_name">Robot Name</label>
            <input class="form-control" id="robot_name" name="robot_name" type="text" placeholder="e.g. Robot125" required="" />
            <div class="valid-feedback">Name looks complete — thank you.</div>
            <div class="invalid-feedback">Please enter your first name.</div>
        </div>
        <div class="col-md-3">
            <label class="form-label" for="robot_category">Robot Category</label>
            <select class="form-select" id="robot_category" data-choices="data-choices" multiple="multiple" size="1" name="robot_category" required="required" data-options='{"removeItemButton":true,"placeholder":true}'>
                <option value="">Select category...</option>
                <?php foreach ($categories as $category) : ?>
                    <option value="category_<?= $category['slug'] ?>"><?= $category['name'] ?></option>
                <?php endforeach; ?>
            </select>
            <div class="invalid-feedback">Please select one or multiple</div>
        </div>
        <div class="d-none d-md-block col-md-3"></div>
        <div class="d-none d-md-block col-md-3"></div>
        <div class="col-md-3" id="robot_operator_group" <?= $singleMember ? 'style="display:none;"' : '' ?>>
            <label class="form-label" for="robot_operator">Robot Operator</label>
            <select class="form-select" id="robot_operator" data-choices="data-choices" size="1" required="required" name="robot_operator" data-options='{"removeItemButton":true,"placeholder":true}'>
                <option value="" disabled>Select operator...</option>
                <?php foreach ($members as $member) : ?>
                    <option value="member_<?= $member['id'] ?>"><?= htmlspecialchars($member['full_name']) ?></option>
                <?php endforeach; ?>
            </select>
            <div class="valid-feedback">The robot operator has been entered correctly.</div>
            <div class="invalid-feedback">Please complete the name of the robot operator.</div>
        </div>
        <div class="col-md-3" id="robot_members_group" <?= $singleMember ? 'style="display:none;"' : '' ?>>
            <label class="form-label" for="robot_members">Robot Members</label>
            <select class="form-select" id="robot_members" data-choices="data-choices" multiple="multiple" size="1" name="robot_members" required="required" data-options='{"removeItemButton":true,"placeholder":true}'>
                <option value="">Select members...</option>
                <?php foreach ($members as $member) : ?>
                    <option value="member_<?= $member['id'] ?>"><?= htmlspecialchars($member['full_name']) ?></option>
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
        <?php if ($singleMember): ?>
            <input type="hidden" name="robot_operator" value="<?= $members[0]['id'] ?>">
            <input type="hidden" name="robot_members[]" value="<?= $members[0]['id'] ?>">
        <?php endif; ?>

        <div class="col-12">
            <button class="btn btn-primary" type="submit">Add Robot</button>
        </div>
    </form>
</div>