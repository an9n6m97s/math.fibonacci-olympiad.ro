<div class="row mb-4 mb-xl-6 mb-xxl-4 gy-3 px-4 px-lg-6 pt-6 justify-content-between">
    <div class="col-auto">
        <h2 class="mb-0 text-body-emphasis">Timeline</h2>
    </div>
</div>

<div class="px-4 px-lg-6 pb-6">

    <style>
        :root {
            --fibo-red: #d00000;
            /* align with header */
            --fibo-red-700: #a80000;
            --fibo-red-soft: rgba(208, 0, 0, .08);
            --fibo-line: rgba(0, 0, 0, .12);
            --fibo-line-light: rgba(0, 0, 0, .06);
        }

        /* Glassy header, but red-tinted */
        .fibo-glass {
            background: linear-gradient(135deg, rgba(208, 0, 0, 0.06), rgba(0, 0, 0, 0.02));
            border: 1px solid rgba(0, 0, 0, 0.06);
            box-shadow: 0 8px 28px rgba(0, 0, 0, 0.08);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border-radius: 16px;
        }

        .fibo-header {
            background:
                radial-gradient(1000px 600px at 0% -20%, rgba(208, 0, 0, .14), transparent 60%),
                radial-gradient(1000px 600px at 100% -20%, rgba(168, 0, 0, .14), transparent 60%);
            border-radius: 16px;
        }

        .fibo-day-label {
            font-weight: 700;
            letter-spacing: .2px;
        }

        .fibo-nav-btn {
            border-radius: 12px;
            border: 1px solid var(--fibo-line);
            background: #fff;
            padding: .55rem .8rem;
            line-height: 1;
            transition: transform .06s ease, box-shadow .2s ease, background .2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 44px;
            min-height: 44px;
        }

        .fibo-nav-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, .08);
            background: #fff5f5;
        }

        .fibo-nav-btn:active {
            transform: translateY(0);
        }

        /* Timeline */
        .fibo-day {
            display: none;
        }

        .fibo-day.active {
            display: block;
            animation: fiboFade .18s ease-out;
        }

        @keyframes fiboFade {
            from {
                opacity: .65;
                transform: translateY(2px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fibo-tl {
            position: relative;
            padding-left: 1.25rem;
        }

        .fibo-tl::before {
            content: "";
            position: absolute;
            left: .35rem;
            top: .25rem;
            bottom: .25rem;
            width: 2px;
            background: linear-gradient(to bottom, var(--fibo-line), var(--fibo-line-light));
        }

        .fibo-item {
            position: relative;
            padding: .75rem 0 .9rem 0;
        }

        .fibo-item+.fibo-item {
            border-top: 1px dashed var(--fibo-line-light);
        }

        .fibo-dot {
            position: absolute;
            left: -0.25rem;
            top: .85rem;
            width: .8rem;
            height: .8rem;
            border-radius: 999px;
            background: var(--fibo-red-soft);
        }

        .fibo-dot::after {
            content: "";
            position: absolute;
            inset: .16rem;
            background: var(--fibo-red);
            border-radius: 999px;
        }

        .fibo-grid {
            display: grid;
            grid-template-columns: 10ch 1fr;
            grid-column-gap: .75rem;
            align-items: start;
        }

        .fibo-time {
            font-variant-numeric: tabular-nums;
            white-space: nowrap;
            color: var(--bs-secondary-color);
        }

        .fibo-title {
            margin: 0;
            font-weight: 600;
            word-break: normal;
        }

        .fibo-desc {
            margin: .25rem 0 0;
        }

        .fibo-muted {
            color: var(--bs-secondary-color);
        }

        /* Red branded controls */
        .btn-danger {
            --bs-btn-bg: var(--fibo-red);
            --bs-btn-border-color: var(--fibo-red);
        }

        .btn-danger:hover {
            --bs-btn-hover-bg: var(--fibo-red-700);
            --bs-btn-hover-border-color: var(--fibo-red-700);
        }

        .badge-fibo {
            background: var(--fibo-red);
            color: #fff;
        }

        .badge-fibo-soft {
            background: #fff;
            color: var(--fibo-red);
            border: 1px solid var(--fibo-line);
        }

        .text-fibo {
            color: var(--fibo-red) !important;
        }

        /* Mobile */
        @media (max-width:576px) {
            .fibo-header .card-body {
                padding: .75rem .9rem !important;
            }

            .fibo-day-label {
                font-size: 1rem;
            }

            #fiboDayBadge {
                font-size: .8rem;
            }

            .fibo-tl {
                padding-left: .9rem;
            }

            .fibo-tl::before {
                left: .25rem;
            }

            .fibo-dot {
                left: -.35rem;
                top: .95rem;
                width: .72rem;
                height: .72rem;
            }

            .fibo-dot::after {
                inset: .15rem;
            }

            .fibo-grid {
                grid-template-columns: 1fr;
                grid-row-gap: .25rem;
            }

            .fibo-time {
                white-space: normal;
                font-size: .95rem;
            }

            .fibo-title {
                line-height: 1.25;
            }

            .badge {
                border-radius: .5rem;
                padding: .25rem .45rem;
                font-size: .72rem;
            }

            h6.fibo-title {
                font-size: 1rem;
            }
        }

        /* Compact summary stacked on mobile */
        @media (max-width:576px) {
            .table-responsive table.table.table-sm thead {
                display: none;
            }

            .table-responsive table.table.table-sm tr {
                display: block;
                padding: .6rem .5rem;
                border-bottom: 1px solid var(--fibo-line-light);
            }

            .table-responsive table.table.table-sm td {
                display: block;
                padding: .15rem 0;
            }

            .table-responsive table.table.table-sm td:nth-child(1) {
                font-weight: 600;
            }

            .table-responsive table.table.table-sm td:nth-child(3) {
                color: var(--bs-secondary-color);
            }
        }
    </style>

    <!-- Header with day navigation -->
    <div class="card fibo-glass fibo-header mb-3">
        <div class="card-body py-3 d-flex align-items-center justify-content-between gap-2 flex-wrap">
            <div class="d-flex align-items-center gap-3">
                <button class="fibo-nav-btn" id="fiboPrev" aria-label="Previous day" title="Previous day">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <path d="M15 19L8 12L15 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
                <div>
                    <div class="fibo-day-label h5 mb-0" id="fiboDayTitle">Friday, 27 February 2026 (EET)</div>
                    <div class="small fibo-muted">Use ← → to switch days</div>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge badge-fibo-soft" id="fiboDayBadge">Day 1</span>
            </div>
        </div>
    </div>

    <!-- Days container -->
    <div id="fiboDays">

        <!-- Ziua 1: 27 Feb 2026 -->
        <div class="card fibo-glass fibo-day active" id="day-2026-02-27" data-title="Friday, 27 February 2026 (EET)" data-badge="Day 1">
            <div class="card-body">
                <div class="fibo-tl">

                    <!-- Team Check-in -->
                    <div class="fibo-tl-item text-info">
                        <div class="fibo-tl-dot"></div>
                        <div class="d-flex gap-3 align-items-start">
                            <div class="fibo-time fw-semibold text-body">09:00–12:00</div>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge bg-info-subtle text-info">Check-in</span>
                                    <h6 class="mb-0 fibo-title">Team Check-in</h6>
                                </div>
                                <p class="mb-1">Team registration, badge pickup, document validation, pit/booth allocation.</p>
                                <div class="small fibo-muted">Organizer: Relativity Robotics Challenge • Location: L.T.I. “Al. Marghiloman”, Buzău</div>
                            </div>
                        </div>
                    </div>

                    <!-- Robot Testing -->
                    <div class="fibo-tl-item text-primary">
                        <div class="fibo-tl-dot"></div>
                        <div class="d-flex gap-3 align-items-start">
                            <div class="fibo-time fw-semibold text-body">09:00–16:00</div>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge bg-primary-subtle text-primary">Testing</span>
                                    <h6 class="mb-0 fibo-title">Robot Testing on Available Arenas</h6>
                                </div>
                                <p class="mb-0">Access to arenas for safety checks, sensor calibration, and final tuning.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Public Access & Vendor Booth Setup -->
                    <div class="fibo-tl-item text-success">
                        <div class="fibo-tl-dot"></div>
                        <div class="d-flex gap-3 align-items-start">
                            <div class="fibo-time fw-semibold text-body">12:30–16:00</div>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge bg-success-subtle text-success">Expo</span>
                                    <h6 class="mb-0 fibo-title">Public Access & Vendor Booth Setup</h6>
                                </div>
                                <p class="mb-0">Expo area open to visitors. Team demos, sponsor booths, and Q&A.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Lunch Break -->
                    <div class="fibo-tl-item text-warning">
                        <div class="fibo-tl-dot"></div>
                        <div class="d-flex gap-3 align-items-start">
                            <div class="fibo-time fw-semibold text-body">12:30–13:00</div>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge bg-warning text-dark">Break</span>
                                    <h6 class="mb-0 fibo-title">Lunch Break</h6>
                                </div>
                                <p class="mb-0">Catering window. Staggered flow to keep pits staffed.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Opening Ceremony -->
                    <div class="fibo-tl-item text-danger">
                        <div class="fibo-tl-dot"></div>
                        <div class="d-flex gap-3 align-items-start">
                            <div class="fibo-time fw-semibold text-body">16:30–18:00</div>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge bg-danger-subtle text-danger">Ceremony</span>
                                    <h6 class="mb-0 fibo-title">Opening Ceremony</h6>
                                </div>
                                <p class="mb-0">Welcome remarks, general briefing, venue rules, and competition walkthrough.</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Ziua 2: 28 Feb 2026 -->
        <div class="card fibo-glass fibo-day" id="day-2026-02-28" data-title="Saturday, 28 February 2026 (EET)" data-badge="Day 2">
            <div class="card-body">
                <div class="fibo-tl">

                    <div class="fibo-tl-item text-info">
                        <div class="fibo-tl-dot"></div>
                        <div class="d-flex gap-3 align-items-start">
                            <div class="fibo-time fw-semibold text-body">09:00–09:30</div>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge bg-info-subtle text-info">Check-in</span>
                                    <h6 class="mb-0 fibo-title">Team Check-in</h6>
                                </div>
                                <p class="mb-0">Day 2 arrivals and late badge pickup.</p>
                            </div>
                        </div>
                    </div>

                    <div class="fibo-tl-item text-primary">
                        <div class="fibo-tl-dot"></div>
                        <div class="d-flex gap-3 align-items-start">
                            <div class="fibo-time fw-semibold text-body">09:00–10:00</div>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge bg-primary-subtle text-primary">Testing</span>
                                    <h6 class="mb-0 fibo-title">Robot Testing on Available Arenas</h6>
                                </div>
                                <p class="mb-0">Final practice window before inspections and matches.</p>
                            </div>
                        </div>
                    </div>

                    <div class="fibo-tl-item text-danger">
                        <div class="fibo-tl-dot"></div>
                        <div class="d-flex gap-3 align-items-start">
                            <div class="fibo-time fw-semibold text-body">10:30–11:00</div>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge bg-danger-subtle text-danger">Inspection</span>
                                    <h6 class="mb-0 fibo-title">Inspections: Mini Sumo Eliminations, Humanoid Sumo Eliminations, Line Follower Classic, Line Follower Enhanced</h6>
                                </div>
                                <p class="mb-0">Rule compliance checks: weight, dimensions, safety, start procedure, firmware.</p>
                            </div>
                        </div>
                    </div>

                    <div class="fibo-tl-item text-success">
                        <div class="fibo-tl-dot"></div>
                        <div class="d-flex gap-3 align-items-start">
                            <div class="fibo-time fw-semibold text-body">10:30–14:30</div>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge bg-success-subtle text-success">Races</span>
                                    <h6 class="mb-0 fibo-title">Line Follower Classic</h6>
                                </div>
                                <p class="mb-0">Matches on standard tracks. Photo gates, officiating, and manual review for ties.</p>
                            </div>
                        </div>
                    </div>

                    <div class="fibo-tl-item text-warning">
                        <div class="fibo-tl-dot"></div>
                        <div class="d-flex gap-3 align-items-start">
                            <div class="fibo-time fw-semibold text-body">12:30–13:30</div>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge bg-warning text-dark">Break</span>
                                    <h6 class="mb-0 fibo-title">Lunch Break</h6>
                                </div>
                                <p class="mb-0">Catering window. Heats resume immediately after.</p>
                            </div>
                        </div>
                    </div>

                    <div class="fibo-tl-item text-primary">
                        <div class="fibo-tl-dot"></div>
                        <div class="d-flex gap-3 align-items-start">
                            <div class="fibo-time fw-semibold text-body">13:30–15:30</div>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge bg-primary-subtle text-primary">Bouts</span>
                                    <h6 class="mb-0 fibo-title">Humanoid Sumo</h6>
                                </div>
                                <p class="mb-0">Group stage and eliminations. Bracket posted at the arena.</p>
                            </div>
                        </div>
                    </div>

                    <div class="fibo-tl-item text-success">
                        <div class="fibo-tl-dot"></div>
                        <div class="d-flex gap-3 align-items-start">
                            <div class="fibo-time fw-semibold text-body">14:45–16:30</div>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge bg-success-subtle text-success">Races</span>
                                    <h6 class="mb-0 fibo-title">Line Follower Enhanced</h6>
                                </div>
                                <p class="mb-0">Advanced tracks with intersections and speed segments. Penalties for faults.</p>
                            </div>
                        </div>
                    </div>

                    <div class="fibo-tl-item text-danger">
                        <div class="fibo-tl-dot"></div>
                        <div class="d-flex gap-3 align-items-start">
                            <div class="fibo-time fw-semibold text-body">17:30–18:30</div>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge bg-danger-subtle text-danger">Awards</span>
                                    <h6 class="mb-0 fibo-title">Awards</h6>
                                </div>
                                <p class="mb-0">Day 2 awards ceremony. Photos and media in the expo area.</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Ziua 3: 1 Mar 2026 -->
        <div class="card fibo-glass fibo-day" id="day-2026-03-01" data-title="Sunday, 1 March 2026 (EET)" data-badge="Day 3">
            <div class="card-body">
                <div class="fibo-tl">

                    <div class="fibo-tl-item text-info">
                        <div class="fibo-tl-dot"></div>
                        <div class="d-flex gap-3 align-items-start">
                            <div class="fibo-time fw-semibold text-body">09:00–09:30</div>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge bg-info-subtle text-info">Check-in</span>
                                    <h6 class="mb-0 fibo-title">Team Check-in</h6>
                                </div>
                                <p class="mb-0">Day 3 arrivals. Quick pit readiness check.</p>
                            </div>
                        </div>
                    </div>

                    <div class="fibo-tl-item text-primary">
                        <div class="fibo-tl-dot"></div>
                        <div class="d-flex gap-3 align-items-start">
                            <div class="fibo-time fw-semibold text-body">09:00–10:00</div>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge bg-primary-subtle text-primary">Testing</span>
                                    <h6 class="mb-0 fibo-title">Robot Testing on Available Arenas</h6>
                                </div>
                                <p class="mb-0">Final tests before finals and races.</p>
                            </div>
                        </div>
                    </div>

                    <div class="fibo-tl-item text-danger">
                        <div class="fibo-tl-dot"></div>
                        <div class="d-flex gap-3 align-items-start">
                            <div class="fibo-time fw-semibold text-body">10:00–10:30</div>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge bg-danger-subtle text-danger">Inspection</span>
                                    <h6 class="mb-0 fibo-title">Inspections: Mega Sumo, Mini Sumo, Line Follower Turbo, Drag Race, Humanoid Triathlon, Micro Sumo</h6>
                                </div>
                                <p class="mb-0">Final compliance checks for heavy and speed classes. Batteries and safety systems verified.</p>
                            </div>
                        </div>
                    </div>

                    <div class="fibo-tl-item text-success">
                        <div class="fibo-tl-dot"></div>
                        <div class="d-flex gap-3 align-items-start">
                            <div class="fibo-time fw-semibold text-body">10:30–12:30</div>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge bg-success-subtle text-success">Finals</span>
                                    <h6 class="mb-0 fibo-title">Mini Sumo — Finals</h6>
                                </div>
                                <p class="mb-0">Final bracket to podium. Best-of-three bouts; video review as needed.</p>
                            </div>
                        </div>
                    </div>

                    <div class="fibo-tl-item text-primary">
                        <div class="fibo-tl-dot"></div>
                        <div class="d-flex gap-3 align-items-start">
                            <div class="fibo-time fw-semibold text-body">10:30–12:30</div>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge bg-primary-subtle text-primary">Bouts</span>
                                    <h6 class="mb-0 fibo-title">Mega Sumo — Groups & Eliminations</h6>
                                </div>
                                <p class="mb-0">Group matches and eliminations for the heavy class. Push-out rules per fibo spec.</p>
                            </div>
                        </div>
                    </div>

                    <div class="fibo-tl-item text-primary">
                        <div class="fibo-tl-dot"></div>
                        <div class="d-flex gap-3 align-items-start">
                            <div class="fibo-time fw-semibold text-body">10:30–12:00</div>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge bg-primary-subtle text-primary">Races</span>
                                    <h6 class="mb-0 fibo-title">Drag Race</h6>
                                </div>
                                <p class="mb-0">Parallel-lane sprints. Electronic timing; false start penalties.</p>
                            </div>
                        </div>
                    </div>

                    <div class="fibo-tl-item text-primary">
                        <div class="fibo-tl-dot"></div>
                        <div class="d-flex gap-3 align-items-start">
                            <div class="fibo-time fw-semibold text-body">10:30–12:30</div>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge bg-primary-subtle text-primary">Bouts</span>
                                    <h6 class="mb-0 fibo-title">Micro Sumo</h6>
                                </div>
                                <p class="mb-0">Micro Sumo matches per rulebook. Table officiating.</p>
                            </div>
                        </div>
                    </div>

                    <div class="fibo-tl-item text-primary">
                        <div class="fibo-tl-dot"></div>
                        <div class="d-flex gap-3 align-items-start">
                            <div class="fibo-time fw-semibold text-body">13:30–14:00</div>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge bg-primary-subtle text-primary">Final</span>
                                    <h6 class="mb-0 fibo-title">Micro Sumo — Final</h6>
                                </div>
                                <p class="mb-0">Championship match and podium decision.</p>
                            </div>
                        </div>
                    </div>

                    <div class="fibo-tl-item text-success">
                        <div class="fibo-tl-dot"></div>
                        <div class="d-flex gap-3 align-items-start">
                            <div class="fibo-time fw-semibold text-body">13:30–15:00</div>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge bg-success-subtle text-success">Races</span>
                                    <h6 class="mb-0 fibo-title">Line Follower Turbo</h6>
                                </div>
                                <p class="mb-0">High-speed tracks. Time-based ranking with off-track penalties.</p>
                            </div>
                        </div>
                    </div>

                    <div class="fibo-tl-item text-primary">
                        <div class="fibo-tl-dot"></div>
                        <div class="d-flex gap-3 align-items-start">
                            <div class="fibo-time fw-semibold text-body">10:30–15:30</div>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge bg-primary-subtle text-primary">Trials</span>
                                    <h6 class="mb-0 fibo-title">Humanoid Triathlon</h6>
                                </div>
                                <p class="mb-0">Multiple trials for humanoids: mobility, balance, speed. Aggregate scoring.</p>
                            </div>
                        </div>
                    </div>

                    <div class="fibo-tl-item text-danger">
                        <div class="fibo-tl-dot"></div>
                        <div class="d-flex gap-3 align-items-start">
                            <div class="fibo-time fw-semibold text-body">16:00–17:00</div>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge bg-danger-subtle text-danger">Awards</span>
                                    <h6 class="mb-0 fibo-title">Awards Ceremony</h6>
                                </div>
                                <p class="mb-0">Final awards ceremony. Official photo and event close.</p>
                            </div>
                        </div>
                    </div>

                    <div class="fibo-tl-item text-warning">
                        <div class="fibo-tl-dot"></div>
                        <div class="d-flex gap-3 align-items-start">
                            <div class="fibo-time fw-semibold text-body">12:30–13:30</div>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge bg-warning text-dark">Break</span>
                                    <h6 class="mb-0 fibo-title">Lunch Break</h6>
                                </div>
                                <p class="mb-0">Catering window. Closing activities follow.</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>


    <!-- Compact Summary (opțional, îl poți păstra sub timeline) -->
    <div class="card fibo-glass mt-3">
        <div class="card-body">
            <h5 class="card-title mb-3">Compact Summary</h5>
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead>
                        <tr>
                            <th>When (EET)</th>
                            <th>Item</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Feb 27 • 09:00–12:00</td>
                            <td><strong>Team Check-in</strong></td>
                            <td>Badges, docs, pits</td>
                        </tr>
                        <tr>
                            <td>Feb 27 • 09:00–16:00</td>
                            <td><strong>Robot Testing</strong></td>
                            <td>Arenas open for tuning</td>
                        </tr>
                        <tr>
                            <td>Feb 27 • 12:30–16:00</td>
                            <td><strong>Public Access & Vendor Setup</strong></td>
                            <td>Expo & demos</td>
                        </tr>
                        <tr>
                            <td>Feb 27 • 12:30–13:00</td>
                            <td><strong>Lunch Break</strong></td>
                            <td>Staggered flow</td>
                        </tr>
                        <tr>
                            <td>Feb 27 • 16:30–18:00</td>
                            <td><strong>Opening Ceremony</strong></td>
                            <td>Briefing & rules</td>
                        </tr>
                        <tr>
                            <td>Feb 28 • 09:00–09:30</td>
                            <td><strong>Team Check-in</strong></td>
                            <td>Late arrivals</td>
                        </tr>
                        <tr>
                            <td>Feb 28 • 09:00–10:00</td>
                            <td><strong>Robot Testing</strong></td>
                            <td>Final practice</td>
                        </tr>
                        <tr>
                            <td>Feb 28 • 10:30–11:00</td>
                            <td><strong>Inspections</strong></td>
                            <td>Mini/MH Sumo, LF Classic/Enhanced</td>
                        </tr>
                        <tr>
                            <td>Feb 28 • 10:30–14:30</td>
                            <td><strong>Line Follower Classic</strong></td>
                            <td>Standard tracks</td>
                        </tr>
                        <tr>
                            <td>Feb 28 • 12:30–13:30</td>
                            <td><strong>Lunch Break</strong></td>
                            <td>Catering window</td>
                        </tr>
                        <tr>
                            <td>Feb 28 • 13:30–15:30</td>
                            <td><strong>Humanoid Sumo</strong></td>
                            <td>Groups & eliminations</td>
                        </tr>
                        <tr>
                            <td>Feb 28 • 14:45–16:30</td>
                            <td><strong>Line Follower Enhanced</strong></td>
                            <td>Advanced tracks</td>
                        </tr>
                        <tr>
                            <td>Feb 28 • 17:30–18:30</td>
                            <td><strong>Awards</strong></td>
                            <td>Day 2 ceremony</td>
                        </tr>
                        <tr>
                            <td>Mar 1 • 09:00–09:30</td>
                            <td><strong>Team Check-in</strong></td>
                            <td>Day 3 arrivals</td>
                        </tr>
                        <tr>
                            <td>Mar 1 • 09:00–10:00</td>
                            <td><strong>Robot Testing</strong></td>
                            <td>Final tests</td>
                        </tr>
                        <tr>
                            <td>Mar 1 • 10:00–10:30</td>
                            <td><strong>Inspections</strong></td>
                            <td>Mega/Mini Sumo, LFT, Drag, HT, Micro</td>
                        </tr>
                        <tr>
                            <td>Mar 1 • 10:30–12:30</td>
                            <td><strong>Mini Sumo — Finals</strong></td>
                            <td>Best-of-three</td>
                        </tr>
                        <tr>
                            <td>Mar 1 • 10:30–12:30</td>
                            <td><strong>Mega Sumo — Groups & Eliminations</strong></td>
                            <td>Heavy class</td>
                        </tr>
                        <tr>
                            <td>Mar 1 • 10:30–12:00</td>
                            <td><strong>Drag Race</strong></td>
                            <td>Electronic timing</td>
                        </tr>
                        <tr>
                            <td>Mar 1 • 10:30–12:30</td>
                            <td><strong>Micro Sumo</strong></td>
                            <td>Per rulebook</td>
                        </tr>
                        <tr>
                            <td>Mar 1 • 13:30–14:00</td>
                            <td><strong>Micro Sumo — Final</strong></td>
                            <td>Championship</td>
                        </tr>
                        <tr>
                            <td>Mar 1 • 13:30–15:00</td>
                            <td><strong>Line Follower Turbo</strong></td>
                            <td>High-speed</td>
                        </tr>
                        <tr>
                            <td>Mar 1 • 10:30–15:30</td>
                            <td><strong>Humanoid Triathlon</strong></td>
                            <td>Aggregate scoring</td>
                        </tr>
                        <tr>
                            <td>Mar 1 • 16:00–17:00</td>
                            <td><strong>Awards Ceremony</strong></td>
                            <td>Event close</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script>
    (function() {
        const dayEls = Array.from(document.querySelectorAll('.fibo-day'));
        if (!dayEls.length) return;

        const titleEl = document.getElementById('fiboDayTitle');
        const badgeEl = document.getElementById('fiboDayBadge');
        const prevBtn = document.getElementById('fiboPrev');

        // inject Next button on the right cluster
        const rightCluster = document.querySelector('.fibo-header .card-body .d-flex.align-items-center.gap-2');
        const nextBtn = document.createElement('button');
        nextBtn.className = 'fibo-nav-btn';
        nextBtn.id = 'fiboNext';
        nextBtn.setAttribute('aria-label', 'Next day');
        nextBtn.title = 'Next day';
        nextBtn.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M9 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
        rightCluster.appendChild(nextBtn);

        const idOrder = dayEls.map(el => el.id);
        const getIndexFromHash = () => {
            const h = (location.hash || '').replace('#', '');
            const idx = idOrder.indexOf(h);
            return idx >= 0 ? idx : 0;
        };

        let idx = getIndexFromHash();
        const isMobile = () => matchMedia('(max-width: 576px)').matches;

        function activate(i) {
            idx = Math.max(0, Math.min(i, dayEls.length - 1));
            dayEls.forEach((el, k) => el.classList.toggle('active', k === idx));
            const active = dayEls[idx];
            if (titleEl) titleEl.textContent = active.dataset.title || active.id;
            if (badgeEl) badgeEl.textContent = active.dataset.badge || `Day ${idx+1}`;
            const id = active.id;
            if (id) history.replaceState(null, '', '#' + id);
            (idx === 0 ? prevBtn : nextBtn).blur();
            if (isMobile()) document.querySelector('.fibo-header')?.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }

        prevBtn.addEventListener('click', () => activate(idx - 1));
        nextBtn.addEventListener('click', () => activate(idx + 1));
        window.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') {
                e.preventDefault();
                activate(idx - 1);
            }
            if (e.key === 'ArrowRight') {
                e.preventDefault();
                activate(idx + 1);
            }
        });
        window.addEventListener('hashchange', () => activate(getIndexFromHash()));

        // Build Compact Summary automatically from DOM
        function buildSummary() {
            const tbody = document.querySelector('#fiboSummary tbody');
            if (!tbody) return;
            tbody.innerHTML = '';
            dayEls.forEach(day => {
                const dayLabel = day.dataset.title?.split('(')[0]?.trim() || day.id;
                day.querySelectorAll('.fibo-item').forEach(item => {
                    const time = item.querySelector('.fibo-time')?.textContent?.trim() || '';
                    const title = item.querySelector('.fibo-title')?.textContent?.trim() || '';
                    const notes = item.querySelector('.fibo-desc')?.textContent?.trim() || '';
                    const tr = document.createElement('tr');
                    tr.innerHTML = `<td>${dayLabel} • ${time}</td><td><strong>${title}</strong></td><td>${notes}</td>`;
                    tbody.appendChild(tr);
                });
            });
        }

        // Init
        activate(idx);
        buildSummary();
    })();
</script>