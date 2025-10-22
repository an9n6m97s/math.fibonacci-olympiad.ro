<div class="row vh-100 g-0">
    <div class="col-lg-6 position-relative d-none d-lg-block">
        <div class="bg-holder" style="background-image:url(/assets/ucp/img/bg/32.png);"></div>
        <!--/.bg-holder-->
    </div>
    <div class="col-lg-6">
        <div class="row flex-center h-100 g-0 px-4 px-sm-0">
            <div class="col col-sm-6 col-lg-7 col-xl-6"><a class="d-flex flex-center text-decoration-none mb-4" href="/">
                    <div class="d-flex align-items-center fw-bolder fs-3 d-inline-block"><img src="/assets/images/logo/logo.webp" alt="phoenix" width="58" /></div>
                </a>
                <div class="text-center mb-7">
                    <h3 class="text-body-highlight">Sign Up</h3>
                    <p class="text-body-tertiary">Create your account today</p>
                </div><button class="btn btn-phoenix-secondary w-100 mb-3" onclick="notify('This option is currently disabled.', 4000)"><span class="fab fa-google text-danger me-2 fs-9"></span>Sign up with google</button><button class="btn btn-phoenix-secondary w-100" onclick="notify('This option is currently disabled.', 4000)"><span class="fab fa-facebook text-primary me-2 fs-9"></span>Sign up with facebook</button>
                <div class="position-relative mt-4">
                    <hr class="bg-body-secondary" />
                    <div class="divider-content-center">or use email</div>
                </div>
                <form method="post" id="registration-form">
                    <div class="mb-3 text-start"><label class="form-label" for="fullName">Full Name</label><input class="form-control" id="fullName" name="fullName" type="text" placeholder="Full Name*" required /></div>
                    <div class="mb-3 text-start"><label class="form-label" for="email">Email address</label><input class="form-control" id="email" name="email" type="email" placeholder="Email*" required /></div>
                    <div class="row g-3 mb-3">
                        <div class="col-sm-6"><label class="form-label" for="password">Password</label>
                            <div class="position-relative" data-password="data-password"><input class="form-control form-icon-input pe-6" id="password" name="password" type="password" placeholder="Password*" required data-password-input="data-password-input" /><button class="btn px-3 py-0 h-100 position-absolute top-0 end-0 fs-7 text-body-tertiary" data-password-toggle="data-password-toggle"><span class="uil uil-eye show"></span><span class="uil uil-eye-slash hide"></span></button></div>
                        </div>
                        <div class="col-sm-6"><label class="form-label" for="confirm-password">Confirm Password</label>
                            <div class="position-relative" data-password="data-password"><input class="form-control form-icon-input pe-6" id="confirm-password" name="confirm-password" type="password" placeholder="Confirm password*" required data-password-input="data-password-input" /><button class="btn px-3 py-0 h-100 position-absolute top-0 end-0 fs-7 text-body-tertiary" data-password-toggle="data-password-toggle"><span class="uil uil-eye show"></span><span class="uil uil-eye-slash hide"></span></button></div>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-sm-6"><label class="form-label" for="phone">Phone Number</label><input class="form-control" id="phone" name="phone" type="text" placeholder="Phone number*" required /></div>
                        <div class="col-sm-6"><label class="form-label" for="role">Role</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="" selected disabled>Select your role*</option>
                                <option value="coach">Coach</option>
                                <option disabled value="team_leader">Team leader</option>
                                <option disabled value="member">Member</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-sm-6"><label class="form-label" for="org_type">Organization Type</label>
                            <select class="form-select" id="org_type" name="org_type" required>
                                <option value="" selected disabled>Organization Type*</option>
                                <option value="School">School</option>
                                <option value="Club">Club</option>
                                <option value="Company">Company</option>
                                <option value="Independent">Independent</option>
                            </select>
                        </div>
                        <div class="col-sm-6"><label class="form-label" for="org_name">Organization Name</label><input class="form-control" id="org_name" name="org_name" type="text" placeholder="Organization Name*" required /></div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-sm-6"><label class="form-label" for="country">Country</label><input class="form-control" id="country" name="country" type="text" placeholder="Country*" required /></div>
                        <div class="col-sm-6"><label class="form-label" for="city">City</label><input class="form-control" id="city" name="city" type="text" placeholder="City*" required /></div>
                    </div>
                    <div class="form-check mb-3"><input class="form-check-input" id="termsService" type="checkbox" required /><label class="form-label fs-9 text-transform-none" for="termsService">I accept the <a href="#!">terms </a>and <a href="#!">privacy policy</a></label></div>
                    <button class="btn btn-primary w-100 mb-3">Register Now</button>
                    <div class="text-center"><a class="fs-9 fw-bold" href="/ucp/login">Sign in to an existing account</a></div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="support-chat-container">
    <div class="container-fluid support-chat">
        <div class="card bg-body-emphasis">
            <div class="card-header d-flex flex-between-center px-4 py-3 border-bottom border-translucent">
                <h5 class="mb-0 d-flex align-items-center gap-2">Demo widget<span class="fa-solid fa-circle text-success fs-11"></span></h5>
                <div class="btn-reveal-trigger"><button class="btn btn-link p-0 dropdown-toggle dropdown-caret-none transition-none d-flex" type="button" id="support-chat-dropdown" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h text-body"></span></button>
                    <div class="dropdown-menu dropdown-menu-end py-2" aria-labelledby="support-chat-dropdown"><a class="dropdown-item" href="#!">Request a callback</a><a class="dropdown-item" href="#!">Search in chat</a><a class="dropdown-item" href="#!">Show history</a><a class="dropdown-item" href="#!">Report to Admin</a><a class="dropdown-item btn-support-chat" href="#!">Close Support</a></div>
                </div>
            </div>
            <div class="card-body chat p-0">
                <div class="d-flex flex-column-reverse scrollbar h-100 p-3">
                    <div class="text-end mt-6"><a class="mb-2 d-inline-flex align-items-center text-decoration-none text-body-emphasis bg-body-hover rounded-pill border border-primary py-2 ps-4 pe-3" href="#!">
                            <p class="mb-0 fw-semibold fs-9">I need help with something</p><span class="fa-solid fa-paper-plane text-primary fs-9 ms-3"></span>
                        </a><a class="mb-2 d-inline-flex align-items-center text-decoration-none text-body-emphasis bg-body-hover rounded-pill border border-primary py-2 ps-4 pe-3" href="#!">
                            <p class="mb-0 fw-semibold fs-9">I can’t reorder a product I previously ordered</p><span class="fa-solid fa-paper-plane text-primary fs-9 ms-3"></span>
                        </a><a class="mb-2 d-inline-flex align-items-center text-decoration-none text-body-emphasis bg-body-hover rounded-pill border border-primary py-2 ps-4 pe-3" href="#!">
                            <p class="mb-0 fw-semibold fs-9">How do I place an order?</p><span class="fa-solid fa-paper-plane text-primary fs-9 ms-3"></span>
                        </a><a class="false d-inline-flex align-items-center text-decoration-none text-body-emphasis bg-body-hover rounded-pill border border-primary py-2 ps-4 pe-3" href="#!">
                            <p class="mb-0 fw-semibold fs-9">My payment method not working</p><span class="fa-solid fa-paper-plane text-primary fs-9 ms-3"></span>
                        </a></div>
                    <div class="text-center mt-auto">
                        <div class="avatar avatar-3xl status-online"><img class="rounded-circle border border-3 border-light-subtle" src="/assets/ucp/img/team/30.webp" alt="" /></div>
                        <h5 class="mt-2 mb-3">Eric</h5>
                        <p class="text-center text-body-emphasis mb-0">Ask us anything – we’ll get back to you here or by email within 24 hours.</p>
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center gap-2 border-top border-translucent ps-3 pe-4 py-3">
                <div class="d-flex align-items-center flex-1 gap-3 border border-translucent rounded-pill px-4"><input class="form-control outline-none border-0 flex-1 fs-9 px-0" type="text" placeholder="Write message" /><label class="btn btn-link d-flex p-0 text-body-quaternary fs-9 border-0" for="supportChatPhotos"><span class="fa-solid fa-image"></span></label><input class="d-none" type="file" accept="image/*" id="supportChatPhotos" /><label class="btn btn-link d-flex p-0 text-body-quaternary fs-9 border-0" for="supportChatAttachment"> <span class="fa-solid fa-paperclip"></span></label><input class="d-none" type="file" id="supportChatAttachment" /></div><button class="btn p-0 border-0 send-btn"><span class="fa-solid fa-paper-plane fs-9"></span></button>
            </div>
        </div>
    </div><button class="btn btn-support-chat p-0 border border-translucent"><span class="fs-8 btn-text text-primary text-nowrap">Chat demo</span><span class="ping-icon-wrapper mt-n4 ms-n6 mt-sm-0 ms-sm-2 position-absolute position-sm-relative"><span class="ping-icon-bg"></span><span class="fa-solid fa-circle ping-icon"></span></span><span class="fa-solid fa-headset text-primary fs-8 d-sm-none"></span><span class="fa-solid fa-chevron-down text-primary fs-7"></span></button>
</div>