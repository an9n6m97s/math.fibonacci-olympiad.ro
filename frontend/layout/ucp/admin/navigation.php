<?php $admin = new Admin($conn); ?>

<li class="nav-item">
    <!-- parent pages-->
    <div class="nav-item-wrapper">
        <a class="nav-link label-1" href="/ucp/admin" role="button" data-bs-toggle="" aria-expanded="false">
            <div class="d-flex align-items-center">
                <span class="nav-link-icon">
                    <span data-feather="home"></span>
                </span>
                <span class="nav-link-text-wrapper">
                    <span class="nav-link-text">Dashboard</span>
                </span>
            </div>
        </a>
    </div>
</li>


<li class="nav-item">

    <p class="navbar-vertical-label">Management</p>
    <hr class="navbar-vertical-line" />

    <div class="nav-item-wrapper"><a class="nav-link label-1" href="/ucp/admin/users/manage" role="button" data-bs-toggle="" aria-expanded="false">
            <div class="d-flex align-items-center">
                <span class="nav-link-icon">
                    <span data-feather="user"></span>
                </span>
                <span class="nav-link-text-wrapper">
                    <span class="nav-link-text">Manage Coaches</span>
                    <span class="fibo-chip fibo-chip--new fibo-chip--sm"><b>NEW</b><em class="fibo-comet fibo-chip--sweep fibo-chip--softglow"></em></span>
                </span>
            </div>
        </a>
    </div>
    <div class="nav-item-wrapper"><a class="nav-link label-1" href="/ucp/admin/team/manage" role="button" data-bs-toggle="" aria-expanded="false">
            <div class="d-flex align-items-center">
                <span class="nav-link-icon">
                    <span data-feather="briefcase"></span>
                </span>
                <span class="nav-link-text-wrapper">
                    <span class="nav-link-text">Manage Teams</span>
                    <span class="fibo-chip fibo-chip--new fibo-chip--sm"><b>NEW</b><em class="fibo-comet fibo-chip--sweep fibo-chip--softglow"></em></span>
                </span>
            </div>
        </a>
    </div>
    <div class="nav-item-wrapper"><a class="nav-link label-1" href="/ucp/admin/members/manage" role="button" data-bs-toggle="" aria-expanded="false">
            <div class="d-flex align-items-center">
                <span class="nav-link-icon">
                    <span data-feather="user"></span>
                </span>
                <span class="nav-link-text-wrapper">
                    <span class="nav-link-text">Manage Members</span>
                    <span class="fibo-chip fibo-chip--new fibo-chip--sm"><b>NEW</b><em class="fibo-comet fibo-chip--sweep fibo-chip--softglow"></em></span>
                </span>
            </div>
        </a>
    </div>
    <div class="nav-item-wrapper"><a class="nav-link label-1" href="/ucp/admin/robots/manage" role="button" data-bs-toggle="" aria-expanded="false">
            <div class="d-flex align-items-center">
                <span class="nav-link-icon">
                    <span data-feather="tool"></span>
                </span>
                <span class="nav-link-text-wrapper">
                    <span class="nav-link-text">Manage Robots</span>
                    <span class="fibo-chip fibo-chip--new fibo-chip--sm"><b>NEW</b><em class="fibo-comet fibo-chip--sweep fibo-chip--softglow"></em></span>
                </span>
            </div>
        </a>
    </div>

</li>

<li class="nav-item">

    <p class="navbar-vertical-label">Data Management</p>
    <hr class="navbar-vertical-line" />

    <div class="nav-item-wrapper">
        <a class="nav-link label-1" href="/ucp/admin/export-data" role="button" data-bs-toggle="" aria-expanded="false">
            <div class="d-flex align-items-center">
                <span class="nav-link-icon">
                    <span data-feather="git-merge"></span>
                </span>
                <span class="nav-link-text-wrapper">
                    <span class="nav-link-text">Export Data</span>
                    <span class="fibo-chip fibo-chip--new fibo-chip--sm"><b>NEW</b><em class="fibo-comet fibo-chip--sweep fibo-chip--softglow"></em></span>
                </span>
            </div>
        </a>
    </div>

</li>


<li class="nav-item">

    <p class="navbar-vertical-label">Infos</p>
    <hr class="navbar-vertical-line" />

    <div class="nav-item-wrapper">
        <a class="nav-link label-1" href="/ucp/admin/changelogs" role="button" data-bs-toggle="" aria-expanded="false">
            <div class="d-flex align-items-center">
                <span class="nav-link-icon">
                    <span data-feather="git-merge"></span>
                </span>
                <span class="nav-link-text-wrapper">
                    <span class="nav-link-text">Dev Changelogs</span>
                    <span class="fibo-chip fibo-chip--new fibo-chip--sm"><b>NEW</b><em class="fibo-comet fibo-chip--sweep fibo-chip--softglow"></em></span>
                </span>
            </div>
        </a>
    </div>

</li>


<?php if ($admin->getAdminRole($userData['id']) == 'developer') : ?>
    <li class="nav-item">

        <p class="navbar-vertical-label">Developers area</p>
        <hr class="navbar-vertical-line" />

        <div class="nav-item-wrapper">
            <a class="nav-link label-1" href="/ucp/admin/changelogs/manage" role="button" data-bs-toggle="" aria-expanded="false">
                <div class="d-flex align-items-center">
                    <span class="nav-link-icon">
                        <span data-feather="git-merge"></span>
                    </span>
                    <span class="nav-link-text-wrapper">
                        <span class="nav-link-text">Manage Changelogs</span>
                    </span>
                </div>
            </a>
        </div>

    </li>
<?php endif; ?>

<li class="nav-item">

    <p class="navbar-vertical-label">Other</p>
    <hr class="navbar-vertical-line" />

    <div class="nav-item-wrapper"><a class="nav-link label-1" href="/ucp" role="button" data-bs-toggle="" aria-expanded="false">
            <div class="d-flex align-items-center">
                <span class="nav-link-icon">
                    <span data-feather="calendar"></span>
                </span>
                <span class="nav-link-text-wrapper">
                    <span class="nav-link-text">Back to UCP</span>
                </span>
            </div>
        </a>
    </div>
    <div class="nav-item-wrapper"><a class="nav-link label-1" href="/" role="button" data-bs-toggle="" aria-expanded="false">
            <div class="d-flex align-items-center">
                <span class="nav-link-icon">
                    <span data-feather="calendar"></span>
                </span>
                <span class="nav-link-text-wrapper">
                    <span class="nav-link-text">Back to frontend</span>
                </span>
            </div>
        </a>
    </div>

</li>