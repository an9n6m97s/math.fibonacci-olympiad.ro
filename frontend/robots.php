<div class="container mt-5">
    <!-- Page Title -->
    <div class="row mb-4 mb-xl-6 mb-xxl-4 gy-3 px-4 px-lg-6 pt-6 justify-content-between">
        <div class="col-auto">
            <h2 class="mb-0 text-body-emphasis">Accommodation</h2>
        </div>
    </div>
    <!-- End Page Title -->
    <?php
    $categories = getCategories();
    ?>
    <section class="data-two">
        <div class="auto-container">

            <?php foreach ($categories as $category) : ?>
                <h2 class="fibo-cat-title"><?= htmlspecialchars($category['name']) ?></h2>

                <?php
                $robots = getRobotsByCategory($category['slug']);
                usort($robots, function ($a, $b) {
                    return strcmp($a['name'], $b['name']);
                });
                ?>
                <div class="fibo-robots-wrap">
                    <?php if (!empty($robots)) : ?>
                        <div class="fibo-grid">
                            <?php foreach ($robots as $robot) : ?>
                                <?php
                                $team = getTeamById($robot['team_id'] ?? $robot['id']);

                                $rName   = $robot['name']          ?? '';
                                $tName   = $team['name']           ?? '';
                                $tCode   = $team['code']      ?? '';
                                $city    = $team['city']           ?? '';
                                $country = $team['country']        ?? '';
                                $logo    = $team['logo']      ?? '';
                                ?>
                                <article class="fibo-card">
                                    <div class="fibo-body">
                                        <div class="fibo-avatar">
                                            <?php if (!empty($logo)) : ?>
                                                <img src="<?= htmlspecialchars($logo) ?>"
                                                    alt="<?= htmlspecialchars($tName) ?> logo"
                                                    loading="lazy"
                                                    onerror="this.style.display='none'; this.previousElementSibling?.remove?.();" />
                                            <?php else: ?>
                                                <svg width="36" height="36" viewBox="0 0 24 24" fill="#c4a2ff" aria-hidden="true">
                                                    <path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5Zm0 2c-4.4 0-8 2.3-8 5v1h16v-1c0-2.7-3.6-5-8-5Z" />
                                                </svg>
                                            <?php endif; ?>
                                        </div>

                                        <div class="fibo-meta">
                                            <h3 class="fibo-robot"><?= htmlspecialchars($rName) ?></h3>

                                            <p class="fibo-teamline">
                                                <span class="fibo-teamname"><?= htmlspecialchars($tName) ?></span>
                                                <?php if (!empty($tCode)) : ?>
                                                    <span class="fibo-teamcode"><?= htmlspecialchars($tCode) ?></span>
                                                <?php endif; ?>
                                            </p>

                                            <p class="fibo-loc">
                                                <?php if (!empty($city)) : ?>
                                                    <span class="fibo-chip" title="City">
                                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="#c4a2ff" aria-hidden="true">
                                                            <path d="M12 2a7 7 0 0 0-7 7c0 5.25 7 13 7 13s7-7.75 7-13a7 7 0 0 0-7-7Zm0 9.5A2.5 2.5 0 1 1 14.5 9 2.5 2.5 0 0 1 12 11.5Z" />
                                                        </svg>
                                                        <span><?= htmlspecialchars($city) ?></span>
                                                    </span>
                                                <?php endif; ?>

                                                <?php if (!empty($country)) : ?>
                                                    <span class="fibo-chip" title="Country">
                                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="#c4a2ff" aria-hidden="true">
                                                            <path d="M4 4h8l1 2h7v12h-7l-1-2H4z" />
                                                        </svg>
                                                        <span><?= htmlspecialchars($country) ?></span>
                                                    </span>
                                                <?php endif; ?>
                                            </p>
                                        </div>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    <?php else : ?>
                        <div class="fibo-empty">There are no robots in this category yet.</div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

        </div>
    </section>
</div>