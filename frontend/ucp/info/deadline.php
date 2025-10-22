<!-- Page header -->
<div class="row gy-3 px-4 px-lg-6 pt-6 mb-3 mb-xl-4 justify-content-between align-items-end">
    <div class="col-auto">
        <h2 class="mb-0 text-body-emphasis">Deadlines</h2>
        <p class="page-kicker mb-0">All dates shown in <?= $settings['timezone'] ?>.</p>
    </div>
</div>

<div class="px-4 px-lg-6 pb-6">

    <style>
        :root {
            --fibo-red: #d00000;
            --fibo-red-700: #a80000;
            --card-radius: 14px;
            --card-border: rgba(0, 0, 0, .08);
            --muted: #6b7280;
        }

        .btn-danger {
            --bs-btn-bg: var(--fibo-red);
            --bs-btn-border-color: var(--fibo-red);
            --bs-btn-hover-bg: var(--fibo-red-700);
            --bs-btn-hover-border-color: var(--fibo-red-700);
            border-radius: 10px;
        }

        .btn-danger:focus {
            box-shadow: 0 0 0 .2rem rgba(208, 0, 0, .18)
        }

        .badge {
            border-radius: 999px;
            padding: .35rem .55rem;
            font-weight: 600
        }

        .badge-fibo {
            background: var(--fibo-red);
            color: #fff
        }

        .badge-fibo-soft {
            background: #fff;
            color: var(--fibo-red);
            border: 1px solid rgba(0, 0, 0, .12)
        }

        .badge-muted {
            background: #f5f5f7;
            color: #374151;
            border: 1px solid rgba(0, 0, 0, .06)
        }

        .kpi-card {
            border: 1px solid var(--card-border);
            border-radius: var(--card-radius);
            box-shadow: 0 4px 14px rgba(16, 24, 40, .06)
        }

        .kpi-card .card-body {
            display: flex;
            flex-direction: column;
            gap: .25rem;
            padding: 1.1rem 1.1rem
        }

        .kpi-card .kpi-date {
            font-size: 1.05rem;
            font-weight: 700;
            letter-spacing: .2px
        }

        .kpi-actions {
            margin-top: auto
        }

        .kpi-list {
            margin: .25rem 0 .25rem .25rem;
            padding-left: 1rem
        }

        .kpi-list li {
            margin: .1rem 0
        }

        .table>:not(caption)>*>* {
            padding: .8rem 1rem
        }

        @media (max-width: 575.98px) {
            .table>:not(caption)>*>* {
                padding: .55rem .6rem
            }
        }

        .table tbody tr {
            border-top: 1px solid rgba(0, 0, 0, .06)
        }

        .table thead {
            border-bottom: 1px solid rgba(0, 0, 0, .08)
        }

        .summary-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: .75rem;
            flex-wrap: wrap
        }

        .page-kicker {
            color: var(--muted);
            font-size: .9rem
        }
    </style>

    <!-- Primary KPIs -->
    <div class="row g-3 mb-4">
        <!-- Registration opens -->
        <div class="col-12 col-md-6 col-xl-6">
            <div class="card h-100 kpi-card">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="badge bg-success">Start</span>
                        <span class="badge badge-muted">Registration</span>
                    </div>
                    <h5 class="card-title mb-1">Registration opens</h5>
                    <p class="kpi-date mb-2">
                        <?= date('j F Y H:i (T)', strtotime($settings['registration_open'])); ?> ( <?= $settings['timezone'] ?> )
                    </p>
                    <ul class="kpi-list">
                        <li>Teams can register</li>
                        <li>Managers can add members and robots</li>
                    </ul>
                    <div class="kpi-actions">
                        <a class="btn btn-danger w-100" href="/ucp">Open UCP</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Registration closes -->
        <div class="col-12 col-md-6 col-xl-6">
            <div class="card h-100 kpi-card">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="badge bg-warning text-dark">Critical</span>
                        <span class="badge badge-fibo-soft">Cutoff</span>
                    </div>
                    <h5 class="card-title mb-1">Registration closes</h5>
                    <p class="kpi-date mb-2">
                        <?= date('j F Y H:i (T)', strtotime($settings['registration_close'])); ?> ( <?= $settings['timezone'] ?> )
                    </p>
                    <ul class="kpi-list">
                        <li>Last day to add/remove members</li>
                        <li>Last day to add/remove robots</li>
                        <li>No edits possible after this deadline</li>
                    </ul>
                    <div class="kpi-actions">
                        <a class="btn btn-danger w-100" href="/ucp">Manage team</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Participation confirmation -->
        <div class="col-12 col-md-6 col-xl-6">
            <div class="card h-100 kpi-card">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="badge badge-fibo">Action</span>
                        <span class="badge badge-muted">Attendance</span>
                    </div>
                    <h5 class="card-title mb-1">Participation confirmation</h5>
                    <p class="kpi-date mb-1">
                        <?= date('j F Y H:i (T)', strtotime($settings['participation_confirmation'])); ?> ( <?= $settings['timezone'] ?> )
                    </p>
                    <p class="mb-2 text-body-secondary small">Teams must confirm attendance in the UCP before this date.</p>
                    <div class="kpi-actions">
                        <a class="btn btn-danger w-100" href="/ucp">Confirm participation</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment deadline -->
        <div class="col-12 col-md-6 col-xl-6">
            <div class="card h-100 kpi-card">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="badge badge-fibo-soft">Payment</span>
                        <span class="badge badge-muted">Finance</span>
                    </div>
                    <h5 class="card-title mb-1">Fee payment deadline</h5>
                    <p class="kpi-date mb-1">
                        <?= date('j F Y H:i (T)', strtotime($settings['payment_date'])); ?> ( <?= $settings['timezone'] ?> )
                    </p>
                    <p class="mb-0 text-body-secondary small">Registration fee must be paid to validate participation.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Competition period -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-xl-6">
            <div class="card h-100 kpi-card">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="badge badge-fibo">Event</span>
                        <span class="badge badge-muted">Start</span>
                    </div>
                    <h5 class="card-title mb-1">Competition starts</h5>
                    <p class="kpi-date mb-0">
                        <?= date('j F Y H:i (T)', strtotime($settings['comp_start'])); ?> ( <?= $settings['timezone'] ?> )
                    </p>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-6">
            <div class="card h-100 kpi-card">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="badge badge-fibo">Event</span>
                        <span class="badge badge-muted">End</span>
                    </div>
                    <h5 class="card-title mb-1">Competition ends</h5>
                    <p class="kpi-date mb-0">
                        <?= date('j F Y H:i (T)', strtotime($settings['comp_end'])); ?> ( <?= $settings['timezone'] ?> )
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary table -->
    <div class="row">
        <div class="col-12">
            <div class="card kpi-card">
                <div class="card-body">
                    <div class="summary-head mb-2">
                        <h5 class="card-title mb-0">Summary</h5>
                        <a class="btn btn-danger btn-sm" href="/ucp/info/timeline">View full timeline</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:28%">Milestone</th>
                                    <th style="width:32%">Date</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Registration opens</td>
                                    <td><strong><?= date('j F Y H:i (T)', strtotime($settings['registration_open'])); ?></strong></td>
                                    <td>Teams and robots can be created in UCP</td>
                                </tr>
                                <tr>
                                    <td>Registration closes</td>
                                    <td><strong><?= date('j F Y H:i (T)', strtotime($settings['registration_close'])); ?></strong></td>
                                    <td>No further edits possible</td>
                                </tr>
                                <tr>
                                    <td>Participation confirmation</td>
                                    <td><strong><?= date('j F Y H:i (T)', strtotime($settings['participation_confirmation'])); ?></strong></td>
                                    <td>Teams must confirm attendance</td>
                                </tr>
                                <tr>
                                    <td>Fee payment deadline</td>
                                    <td><strong><?= date('j F Y H:i (T)', strtotime($settings['payment_date'])); ?></strong></td>
                                    <td>Registration fee must be paid</td>
                                </tr>
                                <tr>
                                    <td>Competition period</td>
                                    <td>
                                        <strong>
                                            <?= date('j F Y H:i (T)', strtotime($settings['comp_start'])); ?> –
                                            <?= date('j F Y H:i (T)', strtotime($settings['comp_end'])); ?>
                                        </strong>
                                    </td>
                                    <td>All matches, inspections, and finals</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div> <!-- /card-body -->
            </div>
        </div>
    </div>

</div>