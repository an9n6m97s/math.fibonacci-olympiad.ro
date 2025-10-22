<div class="container mb-5 mt-5"><!-- Page header -->
    <div class="row mb-4 mb-xl-6 mb-xxl-4 gy-3 px-4 px-lg-6 pt-6 justify-content-between">
        <div class="col-auto">
            <h2 class="mb-0 text-body-emphasis">Eligibility</h2>
        </div>
    </div>

    <div class="px-4 px-lg-6 pb-6">

        <style>
            :root {
                --fibo-red: #d00000;
                --fibo-red-700: #a80000;
            }

            .btn-danger {
                --bs-btn-bg: var(--fibo-red);
                --bs-btn-border-color: var(--fibo-red);
                --bs-btn-hover-bg: var(--fibo-red-700);
                --bs-btn-hover-border-color: var(--fibo-red-700);
            }

            a.btn-danger {
                color: #fff;
            }

            .badge-fibo {
                background: var(--fibo-red);
                color: #fff;
            }

            .badge-fibo-soft {
                background: #fff;
                color: var(--fibo-red);
                border: 1px solid rgba(0, 0, 0, .1);
            }
        </style>

        <!-- Who can participate / Team composition -->
        <div class="row g-3 mb-4">
            <div class="col-12 col-lg-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Who can participate</h5>
                        <ul class="mb-0">
                            <li>Open to schools, clubs, universities, individuals, and companies — each team must have an adult mentor.</li>
                            <li>Recommended minimum age: <strong>6 years</strong> (not mandatory).</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Team composition</h5>
                        <ul class="mb-0">
                            <li><strong>Manager</strong> — registers team, members, and robots. Must be <strong>18+</strong>. Not counted as a member.</li>
                            <li>If the manager also competes, they must create a <strong>separate member profile</strong> with identical data.</li>
                            <li><strong>Members</strong> — added by the manager; can be linked to robots.</li>
                            <li><strong>Robots</strong> — each robot must have one <strong>operator</strong> and up to <strong>4 members</strong> assigned.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories (dynamic) -->
        <div class="row g-3 mb-4">
            <?php foreach (getCategories() as $category): ?>
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column">
                            <div class="mb-2">
                                <span class="badge badge-fibo-soft">
                                    <?= htmlspecialchars($category['group'] ?? 'Category') ?>
                                </span>
                            </div>
                            <h5 class="card-title mb-2"><?= htmlspecialchars($category['name']) ?></h5>
                            <p class="mb-3"><?= nl2br(htmlspecialchars($category['description'])) ?></p>
                            <div class="mt-auto">
                                <img
                                    class="img-fluid rounded"
                                    src="/assets/images/rules/<?= htmlspecialchars($category['slug']) ?>.webp"
                                    alt="<?= htmlspecialchars($category['name']) ?>"
                                    loading="lazy" />
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- General safety rules -->
        <div class="row g-3 mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">General safety rules</h5>
                        <ul class="mb-0">
                            <li>Secure batteries, connectors, and moving parts.</li>
                            <li>No open flames, corrosives, or damaging tools.</li>
                            <li>Robots must be powered off outside arenas unless instructed.</li>
                        </ul>
                        <div class="mt-3">
                            <a class="btn btn-danger" href="/regulations">Full Regulations</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>