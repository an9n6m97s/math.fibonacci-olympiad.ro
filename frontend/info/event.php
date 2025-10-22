<div class="container mb-5 mt-5">
    <!-- Page header -->
    <div class="row mb-4 mb-xl-6 mb-xxl-4 gy-3 px-4 px-lg-6 pt-6 justify-content-between">
        <div class="col-auto">
            <h2 class="mb-0 text-body-emphasis">Fibonacci Romania 2026 — Regional Event</h2>
        </div>
    </div>

    <!-- Content -->
    <div class="px-4 px-lg-6 pb-6">

        <!-- Key details -->
        <div class="row g-3 mb-4">
            <div class="col-12 col-lg-8">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="mb-2">
                            <span class="badge bg-danger">Official</span>
                            <span class="badge bg-light text-danger">On-site</span>
                            <span class="badge bg-secondary">Regional 2026</span>
                        </div>
                        <div style="font-size:1.25rem;font-weight:700;" class="mb-1">
                            Dates: 27 Feb – 1 Mar 2026
                        </div>
                        <div style="font-size:1.25rem;font-weight:700;" class="mb-3">
                            Location: Liceul Teoretic de Informatică “Alexandru Marghiloman”, Buzău, Romania
                        </div>
                        <p class="mb-3">
                            The Fibonacci Robot Olympiad — Romania Regional 2026 will convene teams for three days of inspections,
                            qualification rounds, finals, and awards. The event is hosted at Liceul Teoretic de Informatică
                            “Alexandru Marghiloman” in Buzău, with access for participants, staff, and guests aligned to the official timeline.
                        </p>
                        <div class="d-flex gap-2 flex-wrap">
                            <a class="btn btn-danger" href="/info/timeline">View Timeline</a>
                            <a class="btn btn-danger" href="/info/travel">Travel & Access</a>
                            <a class="btn btn-danger" href="/info/accommodation">Accommodation</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Quick facts</h5>
                        <ul class="mb-3">
                            <li>Team check-in: Friday morning</li>
                            <li>Qualifiers: Fri–Sat</li>
                            <li>Finals & Awards: Sunday</li>
                            <li>Rulebooks available in UCP</li>
                        </ul>
                        <a class="btn btn-danger" href="/info/eligibility">Eligibility & Categories</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Access, Zones, Rules -->
        <div class="row g-3 mb-4">
            <div class="col-12 col-md-6 col-xl-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Access & badges</h5>
                        <p class="mb-2">
                            At check-in, each team must present either the QR code (available in the app shortly before the
                            competition) or their unique participation code. This code is <strong>different from the team code</strong>,
                            is unique for every team, and cannot be known by anyone else. Badges must remain visible at all times
                            inside restricted zones.
                        </p>
                        <a class="btn btn-danger" href="/info/travel">Access details</a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Venue & Map</h5>
                        <p class="mb-2">
                            A detailed map of the venue will be published before the event, showing the full layout of the
                            high school and the role of each room (arenas, pits, inspection, public areas, etc.). This will
                            help participants and visitors navigate the competition areas efficiently.
                        </p>
                        <a class="btn btn-danger" href="/info/faq">What to bring</a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Payments</h5>
                        <p class="mb-2">
                            Participation in this competition is <strong>completely free of charge</strong>. No registration
                            fees apply. The only costs to be covered by the teams are accommodation and transport.
                        </p>
                        <a class="btn btn-danger" href="/info/accommodation">Accommodation options</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Links -->
        <div class="row g-3">
            <div class="col-12 col-sm-6 col-xl-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Rules & safety</h5>
                        <p class="mb-3">
                            All official rules and safety guidelines are available directly in your UCP (User Control Panel)
                            or on the main competition website under
                            <a href="/regulations">/regulations</a>.
                        </p>
                        <a class="btn btn-danger" href="/regulations">See rules</a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Deadlines</h5>
                        <p class="mb-3">
                            <span class="badge bg-warning text-dark me-1">Key</span>
                            Registration and modifications close on: <br>
                            <strong><?= date('j F Y H:i (T)', strtotime($settings['registration_close'])); ?> ( <?= $settings['timezone'] ?> )</strong>
                        </p>
                        <a class="btn btn-danger" href="/info/deadline">All dates</a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Travel & logistics</h5>
                        <p class="mb-3">
                            For information on how to reach the venue and plan your trip, please visit the dedicated travel
                            page available at <a href="/info/travel">/info/travel</a>.
                        </p>
                        <a class="btn btn-danger" href="/info/travel">Travel information</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>