<h2 class="mb-2 lh-sm">Admin Changelog</h2>

<div class="row gx-3 px-4 px-lg-6 pt-6">
    <form class="row g-3 needs-validation" novalidate="novalidate" method="get" action="/ucp/admin/export-data/download">

        <div class="col-md-3" id="robot_operator_group">
            <label class="form-label" for="robot_operator">Select data that you want to export</label>
            <select class="form-select" data-choices="data-choices" size="1" required="required" name="type" data-options='{"removeItemButton":true,"placeholder":true}'>
                <option value="coaches">Coaches / Managers</option>
                <option value="members">Members</option>
                <option value="teams">Teams</option>
                <option value="robots">Robots</option>
                <option value="allmembers">Coaches & Members</option>
                <option value="all">All data</option>
            </select>
        </div>
        <div class="col-12">
            <button class="btn btn-primary" type="submit">Select data</button>
        </div>
    </form>
</div>