<?php if (!userHasTeam()) header("Location: /ucp/team/create"); ?>

<div class="row mb-4 mb-xl-6 mb-xxl-4 gy-3 px-4 px-lg-6 pt-6 justify-content-between">
    <div class="col-auto">
        <h2 class="mb-0 text-body-emphasis">Edit Team Details</h2>
    </div>
</div>

<div class="row gx-3 px-4 px-lg-6 pt-6 pb-9">
    <form class="row g-3 needs-validation" novalidate="" id="team_edit_form">
        <div class="col-md-4">
            <label class="form-label" for="team_code">Team code (Auto Generated | Read-only)</label>
            <input class="form-control" id="team_code" type="text" name="team_code" id="team_code" value="<?= $team['code'] ?>" required="" readonly />
            <div class="valid-feedback">Looks good!</div>
            <div class="invalid-feedback">Please choose a username.</div>
        </div>
        <div class="col-md-4">
            <label class="form-label" for="team_name">Team Name</label>
            <input class="form-control" id="team_name" name="team_name" type="text" value="<?= $team['name'] ?>" required="" />
            <div class="valid-feedback">Nice pick — your team name works great.</div>
            <div class="invalid-feedback">Enter a proper team name to continue.</div>
        </div>
        <div class="col-md-4">
            <label class="form-label" for="team_city">City</label>
            <input class="form-control" id="team_city" name="team_city" type="text" value="<?= $team['city'] ?>" required="" />
            <div class="valid-feedback">City looks good — thanks!</div>
            <div class="invalid-feedback">Please type a valid city name.</div>
        </div>
        <div class="col-md-4">
            <label class="form-label" for="team_country">Country</label>
            <input class="form-control" id="team_country" name="team_country" type="text" value="<?= $team['country'] ?>" required="" />
            <div class="valid-feedback">Great — country confirmed.</div>
            <div class="invalid-feedback">Choose or enter a valid country.</div>
        </div>
        <div class="col-md-4">
            <label class="form-label" for="org_name">Organization Name</label>
            <input class="form-control" id="org_name" name="org_name" type="text" value="<?= $team['org_name'] ?>" placeholder="School/Company" required="" />
            <div class="valid-feedback">Organization name accepted — all set.</div>
            <div class="invalid-feedback">Please provide your organization’s name.</div>
        </div>
        <div class="col-md-4">
            <label class="form-label" for="team_website">Website</label>
            <div class="input-group has-validation">
                <input class="form-control" id="team_website" name="team_website" type="text" aria-describedby="inputGroupPrepend" required="" value="<?= $team['website'] ?>" />
                <div class="valid-feedback">Perfect — website address looks fine.</div>
                <div class="invalid-feedback">Please enter a valid website URL (e.g. example.com).</div>
            </div>
        </div>
        <div class="col-md-4">
            <label class="form-label" for="team_email">Team Email</label>
            <input class="form-control" id="team_email" name="team_email" type="text" value="<?= $team['email'] ?>" required="" />
            <div class="valid-feedback">Great — email address is valid.</div>
            <div class="invalid-feedback">Enter a proper email address (e.g. name@domain.com).</div>
        </div>
        <div class="col-md-4">
            <label class="form-label" for="team_phone">Team Phone Number</label>
            <input class="form-control" id="team_phone" name="team_phone" type="tel" value="<?= $team['phone'] ?>" required="" pattern="^\+?[1-9]\d{1,14}$" />
            <div class="valid-feedback">Phone number looks correct.</div>
            <div class="invalid-feedback">Please provide a valid phone number, including country code if required.</div>
        </div>
        <div class="col-md-4">
            <label class="form-label">Team Logo (Read-only | Cannot be changed)</label>
            <img src="<?= $team['logo'] ?? '/assets/images/users/placeholder.jpg' ?>" alt="<?= $team['name'] ?>">
        </div>

        <input type="hidden" name="user_id" value="<?= $_SESSION[getenv('SESSION_USER_ID')] ?>">
        <input type="hidden" name="team_id" value="<?= $team['id'] ?>">

        <div class="col-12">
            <button class="btn btn-primary" type="submit">Update Team Details</button>
        </div>
    </form>
</div>