<li class="nav-item">
    <!-- parent pages-->
    <div class="nav-item-wrapper">
        <a class="nav-link label-1" href="/ucp/" role="button" data-bs-toggle="" aria-expanded="false">
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
    <div class="nav-item-wrapper">
        <a class="nav-link label-1" href="#!" onclick="notify('At the moment payment link is not available.', 4000)" role="button" data-bs-toggle="" aria-expanded="false">
            <div class="d-flex align-items-center">
                <span class="nav-link-icon">
                    <span data-feather="dollar-sign"></span>
                </span>
                <span class="nav-link-text-wrapper">
                    <span class="nav-link-text">Amount to pay: <strong><u><?= totalAmountToPay() ?> USD</u></strong></span>
                </span>
            </div>
        </a>
    </div>
    <div class="nav-item-wrapper">
        <a class="nav-link label-1" href="#!" onclick="notify('At the moment payment link is not available.', 4000)" role="button" data-bs-toggle="" aria-expanded="false">
            <div class="d-flex align-items-center">
                <span class="nav-link-icon">
                    <span data-feather="dollar-sign"></span>
                </span>
                <span class="nav-link-text-wrapper">
                    <span class="nav-link-text">Click to pay the Fees</span>
                </span>
            </div>
        </a>
    </div>
</li>

<li class="nav-item">

    <p class="navbar-vertical-label">User details</p>
    <hr class="navbar-vertical-line" />

    <div class="nav-item-wrapper"><a class="nav-link label-1" href="/ucp/team/view" role="button" data-bs-toggle="" aria-expanded="false">
            <div class="d-flex align-items-center">
                <span class="nav-link-icon">
                    <span data-feather="briefcase"></span>
                </span>
                <span class="nav-link-text-wrapper">
                    <span class="nav-link-text">
                        View Team
                        <?php if (!userHasTeam()): ?>
                            <span class="fibo-chip fibo-chip--require fibo-chip--sm">
                                <i></i>REQUIRE
                            </span>
                        <?php endif; ?>
                    </span>
                </span>
            </div>
        </a>
    </div>
    <div class="nav-item-wrapper"><a class="nav-link label-1" href="/ucp/members/view" role="button" data-bs-toggle="" aria-expanded="false">
            <div class="d-flex align-items-center">
                <span class="nav-link-icon">
                    <span data-feather="users"></span>
                </span>
                <span class="nav-link-text-wrapper">
                    <span class="nav-link-text">
                        View Members
                        <?php if (!$team || !teamHasMembers($team['id'])): ?>
                            <span class="fibo-chip fibo-chip--require fibo-chip--sm">
                                <i></i>REQUIRE
                            </span>
                        <?php endif; ?>
                    </span>
                </span>
            </div>
        </a>
    </div>
    <div class="nav-item-wrapper"><a class="nav-link label-1" href="/ucp/robots/view" role="button" data-bs-toggle="" aria-expanded="false">
            <div class="d-flex align-items-center">
                <span class="nav-link-icon">
                    <span data-feather="tool"></span>
                </span>
                <span class="nav-link-text-wrapper">
                    <span class="nav-link-text">
                        View Robots
                        <?php if (!$team || !teamHasRobots($team['id'])): ?>
                            <span class="fibo-chip fibo-chip--require fibo-chip--sm">
                                <i></i>REQUIRE
                            </span>
                        <?php endif; ?>
                    </span>
                </span>
            </div>
        </a>
    </div>

</li>

<li class="nav-item">
    <!-- label-->
    <p class="navbar-vertical-label">Modules</p>
    <hr class="navbar-vertical-line" /><!-- parent pages-->
    <div class="nav-item-wrapper"><a class="nav-link dropdown-indicator label-1" href="#nv-forms" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="nv-forms">
            <div class="d-flex align-items-center">
                <div class="dropdown-indicator-icon-wrapper"><span class="fas fa-caret-right dropdown-indicator-icon"></span></div><span class="nav-link-icon"><span data-feather="file-text"></span></span><span class="nav-link-text">Regulations <span class="fibo-chip fibo-chip--update"><i></i>UPDATED</span></span>
            </div>
        </a>
        <div class="parent-wrapper label-1">
            <ul class="nav collapse parent" data-bs-parent="#navbarVerticalCollapse" id="nv-forms">
                <li class="collapsed-nav-item-title d-none">Regulations</li>

                <?php foreach (getCategories() as $category) : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/ucp/regulation/<?= $category['slug'] ?>">
                            <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="file-text"></span><span class="nav-link-text"><?= $category['name'] ?> </span></div>
                        </a>
                    </li>
                <?php endforeach; ?>

            </ul>
        </div>
    </div><!-- parent pages-->
</li>

<?php if (isAdmin()) : ?>

    <p class="navbar-vertical-label">Informations</p>
    <hr class="navbar-vertical-line" />

    <div class="nav-item-wrapper">
        <a class="nav-link label-1"
            href="/ucp/info/event"
            role="button" data-bs-toggle="" aria-expanded="false">
            <div class="d-flex align-items-center">
                <span class="nav-link-icon">
                    <span data-feather="bookmark"></span>
                </span>
                <span class="nav-link-text-wrapper">
                    <span class="nav-link-text">Event Details</span>
                    <span class="fibo-chip fibo-chip--hot fibo-chip--sm">
                        <i></i>HOT
                    </span>
                </span>
            </div>
        </a>
    </div>

    <div class="nav-item-wrapper">
        <a class="nav-link label-1"
            href="/ucp/info/timeline"
            role="button" data-bs-toggle="" aria-expanded="false">
            <div class="d-flex align-items-center">
                <span class="nav-link-icon">
                    <span data-feather="calendar"></span>
                </span>
                <span class="nav-link-text-wrapper">
                    <span class="nav-link-text">Timeline</span>

                </span>
            </div>
        </a>
    </div>

    <div class="nav-item-wrapper">
        <a class="nav-link label-1"
            href="/ucp/info/deadline"
            role="button" data-bs-toggle="" aria-expanded="false">
            <div class="d-flex align-items-center">
                <span class="nav-link-icon">
                    <span data-feather="settings"></span>
                </span>
                <span class="nav-link-text-wrapper">
                    <span class="nav-link-text">Deadline</span>
                    <span class="fibo-chip fibo-chip--new fibo-chip--sm">
                        <b>NEW</b>
                        <em class="fibo-comet fibo-chip--sweep fibo-chip--softglow"></em>
                    </span>
                </span>
            </div>
        </a>
    </div>

    <div class="nav-item-wrapper">
        <a class="nav-link label-1"
            href="/ucp/info/eligibility"
            role="button" data-bs-toggle="" aria-expanded="false">
            <div class="d-flex align-items-center">
                <span class="nav-link-icon">
                    <span data-feather="alert-octagon"></span>
                </span>
                <span class="nav-link-text-wrapper">
                    <span class="nav-link-text">Eligibility</span>
                    <span class="fibo-chip fibo-chip--new fibo-chip--sm">
                        <b>NEW</b>
                        <em class="fibo-comet fibo-chip--sweep fibo-chip--softglow"></em>
                    </span>
                </span>
            </div>
        </a>
    </div>

    <div class="nav-item-wrapper">
        <a class="nav-link label-1"
            href="/ucp/info/fees"
            role="button" data-bs-toggle="" aria-expanded="false">
            <div class="d-flex align-items-center">
                <span class="nav-link-icon">
                    <span data-feather="credit-card"></span>
                </span>
                <span class="nav-link-text-wrapper">
                    <span class="nav-link-text">Fees</span>
                    <span class="fibo-chip fibo-chip--new fibo-chip--sm">
                        <b>NEW</b>
                        <em class="fibo-comet fibo-chip--sweep fibo-chip--softglow"></em>
                    </span>
                </span>
            </div>
        </a>
    </div>

    <div class="nav-item-wrapper">
        <a class="nav-link label-1"
            href="/ucp/info/travel"
            role="button" data-bs-toggle="" aria-expanded="false">
            <div class="d-flex align-items-center">
                <span class="nav-link-icon">
                    <span data-feather="briefcase"></span>
                </span>
                <span class="nav-link-text-wrapper">
                    <span class="nav-link-text">Travel</span>
                    <span class="fibo-chip fibo-chip--new fibo-chip--sm">
                        <b>NEW</b>
                        <em class="fibo-comet fibo-chip--sweep fibo-chip--softglow"></em>
                    </span>
                </span>
            </div>
        </a>
    </div>

    <div class="nav-item-wrapper">
        <a class="nav-link label-1"
            href="/ucp/info/accommodation"
            role="button" data-bs-toggle="" aria-expanded="false">
            <div class="d-flex align-items-center">
                <span class="nav-link-icon">
                    <span data-feather="home"></span>
                </span>
                <span class="nav-link-text-wrapper">
                    <span class="nav-link-text">Accommodation</span>
                    <span class="fibo-chip fibo-chip--new fibo-chip--sm">
                        <b>NEW</b>
                        <em class="fibo-comet fibo-chip--sweep fibo-chip--softglow"></em>
                    </span>
                </span>
            </div>
        </a>
    </div>

    </li>

<?php endif; ?>

<li class="nav-item">

    <p class="navbar-vertical-label">Others</p>
    <hr class="navbar-vertical-line" />

    <div class="nav-item-wrapper">
        <a class="nav-link label-1"
            href="/ucp/settings"
            role="button" data-bs-toggle="" aria-expanded="false">
            <div class="d-flex align-items-center">
                <span class="nav-link-icon">
                    <span data-feather="settings"></span>
                </span>
                <span class="nav-link-text-wrapper">
                    <span class="nav-link-text">Settings</span>
                    <span class="fibo-chip fibo-chip--new fibo-chip--sm">
                        <b>NEW</b>
                        <em class="fibo-comet fibo-chip--sweep fibo-chip--softglow"></em>
                    </span>
                </span>
            </div>
        </a>
    </div>
    <div class="nav-item-wrapper">
        <a class="nav-link label-1" href="/ucp/changelogs" role="button" data-bs-toggle="" aria-expanded="false">
            <div class="d-flex align-items-center">
                <span class="nav-link-icon">
                    <span data-feather="git-merge"></span>
                </span>
                <span class="nav-link-text-wrapper">
                    <span class="nav-link-text">Dev Changelogs</span>
                    <span class="fibo-chip fibo-chip--new fibo-chip--sm">
                        <b>NEW</b>
                        <em class="fibo-comet fibo-chip--sweep fibo-chip--softglow"></em>
                    </span>
                </span>
            </div>
        </a>
    </div>

</li>

<?php if (isAdmin()) : ?>

    <li class="nav-item">

        <p class="navbar-vertical-label">Admin</p>
        <hr class="navbar-vertical-line" />

        <div class="nav-item-wrapper"><a class="nav-link label-1" href="/ucp/admin" role="button" data-bs-toggle="" aria-expanded="false">
                <div class="d-flex align-items-center">
                    <span class="nav-link-icon">
                        <span data-feather="terminal"></span>
                    </span>
                    <span class="nav-link-text-wrapper">
                        <span class="nav-link-text">Go to admin panel</span>
                    </span>
                </div>
            </a>
        </div>

    </li>
<?php endif; ?>

<?php if (1 == 2) : ?>
    <li class="nav-item">
        <!-- label-->
        <p class="navbar-vertical-label">Apps</p>
        <hr class="navbar-vertical-line" /><!-- parent pages-->
        <div class="nav-item-wrapper"><a class="nav-link dropdown-indicator label-1" href="#nv-e-commerce" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="nv-e-commerce">
                <div class="d-flex align-items-center">
                    <div class="dropdown-indicator-icon-wrapper"><span class="fas fa-caret-right dropdown-indicator-icon"></span></div><span class="nav-link-icon"><span data-feather="shopping-cart"></span></span><span class="nav-link-text">E commerce</span>
                </div>
            </a>
            <div class="parent-wrapper label-1">
                <ul class="nav collapse parent" data-bs-parent="#navbarVerticalCollapse" id="nv-e-commerce">
                    <li class="collapsed-nav-item-title d-none">E commerce</li>
                    <li class="nav-item"><a class="nav-link dropdown-indicator" href="#nv-admin" data-bs-toggle="collapse" aria-expanded="true" aria-controls="nv-admin">
                            <div class="d-flex align-items-center">
                                <div class="dropdown-indicator-icon-wrapper"><span class="fas fa-caret-right dropdown-indicator-icon"></span></div><span class="nav-link-text">Admin</span>
                            </div>
                        </a><!-- more inner pages-->
                        <div class="parent-wrapper">
                            <ul class="nav collapse parent show" data-bs-parent="#e-commerce" id="nv-admin">
                                <li class="nav-item"><a class="nav-link" href="apps/e-commerce/admin/add-product.html">
                                        <div class="d-flex align-items-center"><span class="nav-link-text">Add product</span></div>
                                    </a><!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link" href="apps/e-commerce/admin/products.html">
                                        <div class="d-flex align-items-center"><span class="nav-link-text">Products</span></div>
                                    </a><!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link" href="apps/e-commerce/admin/customers.html">
                                        <div class="d-flex align-items-center"><span class="nav-link-text">Customers</span></div>
                                    </a><!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link" href="apps/e-commerce/admin/customer-details.html">
                                        <div class="d-flex align-items-center"><span class="nav-link-text">Customer details</span></div>
                                    </a><!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link" href="apps/e-commerce/admin/orders.html">
                                        <div class="d-flex align-items-center"><span class="nav-link-text">Orders</span></div>
                                    </a><!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link" href="apps/e-commerce/admin/order-details.html">
                                        <div class="d-flex align-items-center"><span class="nav-link-text">Order details</span></div>
                                    </a><!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link" href="apps/e-commerce/admin/refund.html">
                                        <div class="d-flex align-items-center"><span class="nav-link-text">Refund</span></div>
                                    </a><!-- more inner pages-->
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item"><a class="nav-link dropdown-indicator" href="#nv-customer" data-bs-toggle="collapse" aria-expanded="true" aria-controls="nv-customer">
                            <div class="d-flex align-items-center">
                                <div class="dropdown-indicator-icon-wrapper"><span class="fas fa-caret-right dropdown-indicator-icon"></span></div><span class="nav-link-text">Customer</span>
                            </div>
                        </a><!-- more inner pages-->
                        <div class="parent-wrapper">
                            <ul class="nav collapse parent show" data-bs-parent="#e-commerce" id="nv-customer">
                                <li class="nav-item"><a class="nav-link" href="apps/e-commerce/landing/homepage.html">
                                        <div class="d-flex align-items-center"><span class="nav-link-text">Homepage</span></div>
                                    </a><!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link" href="apps/e-commerce/landing/product-details.html">
                                        <div class="d-flex align-items-center"><span class="nav-link-text">Product details</span></div>
                                    </a><!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link" href="apps/e-commerce/landing/products-filter.html">
                                        <div class="d-flex align-items-center"><span class="nav-link-text">Products filter</span></div>
                                    </a><!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link" href="apps/e-commerce/landing/cart.html">
                                        <div class="d-flex align-items-center"><span class="nav-link-text">Cart</span></div>
                                    </a><!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link" href="apps/e-commerce/landing/checkout.html">
                                        <div class="d-flex align-items-center"><span class="nav-link-text">Checkout</span></div>
                                    </a><!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link" href="apps/e-commerce/landing/shipping-info.html">
                                        <div class="d-flex align-items-center"><span class="nav-link-text">Shipping info</span></div>
                                    </a><!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link" href="apps/e-commerce/landing/profile.html">
                                        <div class="d-flex align-items-center"><span class="nav-link-text">Profile</span></div>
                                    </a><!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link" href="apps/e-commerce/landing/favourite-stores.html">
                                        <div class="d-flex align-items-center"><span class="nav-link-text">Favourite stores</span></div>
                                    </a><!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link" href="apps/e-commerce/landing/wishlist.html">
                                        <div class="d-flex align-items-center"><span class="nav-link-text">Wishlist</span></div>
                                    </a><!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link" href="apps/e-commerce/landing/order-tracking.html">
                                        <div class="d-flex align-items-center"><span class="nav-link-text">Order tracking</span></div>
                                    </a><!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link" href="apps/e-commerce/landing/invoice.html">
                                        <div class="d-flex align-items-center"><span class="nav-link-text">Invoice</span></div>
                                    </a><!-- more inner pages-->
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div><!-- parent pages-->

        <div class="nav-item-wrapper"><a class="nav-link label-1" href="apps/calendar.html" role="button" data-bs-toggle="" aria-expanded="false">
                <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="calendar"></span></span><span class="nav-link-text-wrapper"><span class="nav-link-text">Calendar</span></span></div>
            </a></div>
    </li>
<?php endif; ?>