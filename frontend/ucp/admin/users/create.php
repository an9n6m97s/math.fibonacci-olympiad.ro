<div class="row mb-4 mb-xl-6 mb-xxl-4 gy-3 px-4 px-lg-6 pt-6 justify-content-between">
    <div class="col-auto">
        <h2 class="mb-0 text-body-emphasis">Add Coach Details</h2>
    </div>
</div>

<div class="row gx-3 px-4 px-lg-6 pt-6">
    <form class="row g-3 needs-validation" novalidate="" id="admin_create_coach">
        <div class="col-md-3">
            <label class="form-label" for="user_full">Coach Full Name</label>
            <input class="form-control" id="user_full" name="user_full" type="text" value="" placeholder="e.g. John" required="" />
        </div>
        <div class="col-md-3">
            <label class="form-label" for="user_password">Coach Password</label>
            <input class="form-control" id="user_password" name="user_password" type="password" value="" required="" placeholder="e.g. 1234567890" />
        </div>
        <div class="col-md-3">
            <label class="form-label" for="user_email">Coach Email</label>
            <input class="form-control" id="user_email" name="user_email" type="email" value="" required="" placeholder="e.g. john.doe@domain.tld" />
        </div>
        <div class="col-md-3">
            <label class="form-label" for="user_phone">Coach Phone Number</label>
            <input class="form-control" id="user_phone" name="user_phone" type="tel" value="" required="" pattern="^\+?[1-9]\d{1,14}$" placeholder="e.g. +40 xxxxxxxxx" />
        </div>
        <div class="col-md-3">
            <label class="form-label" for="user_organization">Organization Type</label>
            <select class="form-select" id="user_organization" data-choices="data-choices" size="1" required="required" name="user_organization" data-options='{"removeItemButton":true,"placeholder":true}'>
                <option value="" disabled selected>Select type...</option>
                <option value="School">School</option>
                <option value="Club">Club</option>
                <option value="Company">Company</option>
                <option value="Independent">Independent</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label" for="user_org_name">Organization Name</label>
            <input class="form-control" id="user_org_name" name="user_org_name" type="text" value="" placeholder="e.g. Relativity" required="" />
        </div>
        <div class="col-md-3">
            <label class="form-label" for="user_country">Country</label>
            <input class="form-control" id="user_country" name="user_country" type="text" value="" placeholder="e.g. Romania" required="" />
        </div>
        <div class="col-md-3">
            <label class="form-label" for="user_city">City</label>
            <input class="form-control" id="user_city" name="user_city" type="text" value="" placeholder="e.g. Buzau" required="" />
        </div>

        <input type="hidden" name="user_role" id="user_role" value="coach" />

        <div class="col-12">
            <button class="btn btn-primary" type="submit">Add Coach</button>
        </div>
    </form>
</div>