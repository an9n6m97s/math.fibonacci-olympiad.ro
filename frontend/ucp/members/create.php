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

<div class="row mb-4 mb-xl-6 mb-xxl-4 gy-3 px-4 px-lg-6 pt-6 justify-content-between">
    <div class="col-auto">
        <h2 class="mb-0 text-body-emphasis">Add Member Details</h2>
    </div>
</div>

<div class="row gx-3 px-4 px-lg-6 pt-6">
    <form class="row g-3 needs-validation" novalidate="" id="member_create_form">
        <div class="col-md-3">
            <label class="form-label" for="member_first_name">Member First Name</label>
            <input class="form-control" id="member_first_name" name="member_first_name" type="text" value="" placeholder="e.g. John" required="" />
            <div class="valid-feedback">Name looks complete — thank you.</div>
            <div class="invalid-feedback">Please enter your first name.</div>
        </div>
        <div class="col-md-3">
            <label class="form-label" for="member_last_name">Member Last Name</label>
            <input class="form-control" id="member_last_name" name="member_last_name" type="text" value="" required="" placeholder="e.g. Doe" />
            <div class="valid-feedback">Name looks complete — thank you.</div>
            <div class="invalid-feedback">Please enter your last name.</div>
        </div>
        <div class="col-md-3">
            <label class="form-label" for="member_email">Member Email </label>
            <input class="form-control" id="member_email" name="member_email" type="email" value="" required="" placeholder="e.g. john.doe@domain.tld" />
            <div class="valid-feedback">Valid email detected.</div>
            <div class="invalid-feedback">Enter a correct email address (e.g. user@example.com).</div>
        </div>
        <div class="col-md-3">
            <label class="form-label" for="member_phone">Member Phone Number</label>
            <input class="form-control" id="member_phone" name="member_phone" type="tel" value="" required="" pattern="^\+?[1-9]\d{1,14}$" placeholder="e.g. +40 xxxxxxxxx" />
            <div class="valid-feedback">Phone number accepted.</div>
            <div class="invalid-feedback">Please provide a valid phone number, including country code if needed.</div>
        </div>
        <div class="col-md-3">
            <label class="form-label" for="member_dob">Member Date of Birth</label>
            <input class="form-control datetimepicker" id="member_dob" name="member_dob" type="text" placeholder="e.g. D/M/Y" required="required" data-options='{"disableMobile":true,"allowInput":true}' />
            <div class="valid-feedback">Date of birth confirmed.</div>
            <div class="invalid-feedback">Please select a valid date of birth.</div>
        </div>
        <div class="col-md-3">
            <label class="form-label" for="member_tshirt">Member T-Shirt Size</label>
            <select class="form-select" id="member_tshirt" data-choices="data-choices" size="1" required="required" name="member_tshirt" data-options='{"removeItemButton":true,"placeholder":true}'>
                <option value="" disabled selected>Select size...</option>
                <option value="XS">XS</option>
                <option value="S">S</option>
                <option value="M">M</option>
                <option value="L">L</option>
                <option value="XL">XL</option>
                <option value="XXL">XXL</option>
            </select>
            <div class="valid-feedback">Size selected — all good.</div>
            <div class="invalid-feedback">Please choose your T-shirt size.</div>
        </div>
        <div class="col-md-3">
            <label class="form-label" for="member_emergency_phone">Member Emergency Phone Number</label>
            <input class="form-control" id="member_emergency_phone" name="member_emergency_phone" type="tel" value="" required="" pattern="^\+?[1-9]\d{1,14}$" placeholder="e.g. +40 xxxxxxxxx" />
            <div class="valid-feedback">Phone emergency number accepted.</div>
            <div class="invalid-feedback">Please provide a valid emergency phone number, including country code if needed.</div>
        </div>
        <div class="col-md-3">
            <label class="form-label" for="member_role">Member Role</label>
            <select class="form-select" id="member_role" data-choices="data-choices" size="1" required="required" name="member_role" data-options='{"removeItemButton":true,"placeholder":true}'>
                <option value="" disabled selected>Select role...</option>
                <option value="Leader">Leader</option>
                <option value="Member">Member</option>
            </select>
            <div class="valid-feedback">Role selected successfully.</div>
            <div class="invalid-feedback">Please choose a role to continue.</div>
        </div>

        <div class="col-12">
            <div class="form-check">
                <input class="form-check-input" id="invalidCheck" type="checkbox" value="" required="" />
                <label class="form-check-label mb-0" for="invalidCheck">I give my consent for photos of me to be taken and used.</label>
                <div class="valid-feedback">Consent recorded — thank you.</div>
                <div class="invalid-feedback">You must confirm photo consent before proceeding.</div>
            </div>
        </div>

        <input type="hidden" name="user_id" value="<?= $_SESSION[getenv('SESSION_USER_ID')] ?>">
        <input type="hidden" name="team_id" value="<?= $team['id'] ?>">

        <div class="col-12">
            <button class="btn btn-primary" type="submit">Add Member</button>
        </div>
    </form>
</div>