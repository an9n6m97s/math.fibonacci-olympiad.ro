<?php if (userHasTeam()) header("Location: /ucp/team/view"); ?>

<div class="row mb-4 mb-xl-6 mb-xxl-4 gy-3 px-4 px-lg-6 pt-6 justify-content-between">
    <div class="col-auto">
        <h2 class="mb-0 text-body-emphasis">Add Team Details</h2>
    </div>
</div>

<div class="row gx-3 px-4 px-lg-6 pt-6">
    <form class="row g-3 needs-validation" novalidate="" id="team_create_form">
        <div class="col-md-4">
            <label class="form-label" for="team_code">Team code (Auto Generated | Read-only)</label>
            <input class="form-control" id="team_code" type="text" name="team_code" id="team_code" value="<?= generateTeamCode() ?>" required="" readonly />
            <div class="valid-feedback">Looks good!</div>
            <div class="invalid-feedback">Please choose a username.</div>
        </div>
        <div class="col-md-4">
            <label class="form-label" for="team_name">Team Name</label>
            <input class="form-control" id="team_name" name="team_name" type="text" value="" placeholder="e.g. Relativity" required="" />
            <div class="valid-feedback">Nice pick — your team name works great.</div>
            <div class="invalid-feedback">Enter a proper team name to continue.</div>
        </div>
        <div class="col-md-4">
            <label class="form-label" for="team_city">City</label>
            <input class="form-control" id="team_city" name="team_city" type="text" value="" placeholder="e.g. Buzau" required="" />
            <div class="valid-feedback">City looks good — thanks!</div>
            <div class="invalid-feedback">Please type a valid city name.</div>
        </div>
        <div class="col-md-4">
            <label class="form-label" for="team_country">Country</label>
            <input class="form-control" id="team_country" name="team_country" type="text" value="" placeholder="e.g. Romania" required="" />
            <div class="valid-feedback">Great — country confirmed.</div>
            <div class="invalid-feedback">Choose or enter a valid country.</div>
        </div>
        <div class="col-md-4">
            <label class="form-label" for="org_name">Organization Name</label>
            <input class="form-control" id="org_name" name="org_name" type="text" value="" placeholder="e.g. School/Company" required="" />
            <div class="valid-feedback">Organization name accepted — all set.</div>
            <div class="invalid-feedback">Please provide your organization’s name.</div>
        </div>
        <div class="col-md-4">
            <label class="form-label" for="team_website">Website</label>
            <div class="input-group has-validation">
                <span class="input-group-text" id="inputGroupPrepend"><strong>https://</strong></span>
                <input class="form-control" id="team_website" name="team_website" type="text" aria-describedby="inputGroupPrepend" required="" value="" placeholder="e.g. fibonacci-olympiad.ro" />
                <div class="valid-feedback">Perfect — website address looks fine.</div>
                <div class="invalid-feedback">Please enter a valid website URL (e.g. example.com).</div>
            </div>
        </div>
        <div class="col-md-4">
            <label class="form-label" for="team_email">Team Email</label>
            <input class="form-control" id="team_email" name="team_email" type="text" value="" required="" placeholder="e.g. john.doe@domain.tld" />
            <div class="valid-feedback">Great — email address is valid.</div>
            <div class="invalid-feedback">Enter a proper email address (e.g. name@domain.com).</div>
        </div>
        <div class="col-md-4">
            <label class="form-label" for="team_phone">Team Phone Number</label>
            <input class="form-control" id="team_phone" name="team_phone" type="tel" value="" required="" pattern="^\+?[1-9]\d{1,14}$" placeholder="e.g. +40 xxxxxxxxx" />
            <div class="valid-feedback">Phone number looks correct.</div>
            <div class="invalid-feedback">Please provide a valid phone number, including country code if required.</div>
        </div>
        <div class="col-md-4">
            <label class="form-label">Team Logo</label>
            <div class="dropzone dropzone-single p-0" id="team_logo_dropzone" data-dropzone="data-dropzone" data-options='{"url":"valid/url","maxFiles":1,"maxFilesize":5,"acceptedFiles":"image/*","dictDefaultMessage":"Choose or Drop a logo here"}'>
                <div class="fallback"><input type="file" name="file" /></div>
                <div class="dz-message" data-dz-message="data-dz-message">
                    <div class="dz-message-text"><img class="me-2" src="/assets/ucp/img/icons/cloud-upload.svg" width="25" alt="" />Drop your logo here</div><button class="btn dz-upload-btn border-0 position-absolute z-5 bg-black bg-opacity-50 text-white mt-3 ms-3 px-3" data-dz-message="data-dz-message">Change Picture<span class="fa-solid fa-camera fs-10 ms-1"></span></button>
                </div>
                <div class="dz-preview d-block m-0">
                    <div class="rounded-2 position-relative" style="height: auto"><img class="rounded-2 w-100 h-100 object-fit-cover" src="/assets/ucp/img/icons/file-bg.webp" alt="..." data-dz-thumbnail="data-dz-thumbnail" /><button class="btn border-0 position-absolute top-0 end-0 z-5 bg-black bg-opacity-50 text-white mt-3 me-3 px-3 cursor-pointer" data-dz-remove="data-dz-remove"><span class="fa-solid fa-xmark cursor-pointer"></span></button></div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="form-check">
                <input class="form-check-input" id="invalidCheck" type="checkbox" value="" required="" />
                <label class="form-check-label mb-0" for="invalidCheck">Agree to terms and conditions</label>
            </div>
        </div>

        <input type="hidden" name="user_id" value="<?= $_SESSION[getenv('SESSION_USER_ID')] ?>">

        <div class="col-12">
            <button class="btn btn-primary" type="submit">Create Team</button>
        </div>
    </form>
</div>