<?php
requireAdminLogin();
$stats = getParticipantStatistics();
?>
<section class="admin-section">
    <h1 class="text-white mb-4">Tablou de bord</h1>
    <div class="row g-4 mb-5">
        <div class="col-lg-4 col-md-6">
            <div class="feature-card">
                <div class="feature-icon"><i class="fa-regular fa-users"></i></div>
                <h4>Total înscriși</h4>
                <p class="display-6 fw-bold mb-0"><?= number_format($stats['total']) ?></p>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="feature-card">
                <div class="feature-icon"><i class="fa-regular fa-city"></i></div>
                <h4>Orașe</h4>
                <p class="display-6 fw-bold mb-0"><?= number_format($stats['cities']) ?></p>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="feature-card">
                <div class="feature-icon"><i class="fa-regular fa-school"></i></div>
                <h4>Școli</h4>
                <p class="display-6 fw-bold mb-0"><?= number_format($stats['schools']) ?></p>
            </div>
        </div>
    </div>

    <div class="mb-5">
        <h2 class="text-white mb-3">Participanți recenți</h2>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Elev</th>
                        <th>Oraș</th>
                        <th>Școală</th>
                        <th>Clasa</th>
                        <th>Înscris la</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($stats['latest'])): ?>
                        <?php foreach ($stats['latest'] as $participant): ?>
                            <tr>
                                <td><?= htmlspecialchars($participant['first_name'] . ' ' . $participant['last_name']) ?></td>
                                <td><?= htmlspecialchars($participant['city']) ?></td>
                                <td><?= htmlspecialchars($participant['school'] ?? '') ?></td>
                                <td><?= htmlspecialchars($participant['class'] ?? '') ?></td>
                                <td><?= date('d.m.Y H:i', strtotime($participant['created_at'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">Nu există înscrieri înregistrate momentan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="principles-sidebar">
        <h3 class="mb-3">Date importante</h3>
        <ul class="timeline-list">
            <li>
                <span class="timeline-date"><?= date('d.m.Y', strtotime($settings['registration_open'])) ?></span>
                <span class="timeline-label">Deschidere înscrieri</span>
            </li>
            <li>
                <span class="timeline-date"><?= date('d.m.Y', strtotime($settings['registration_close'])) ?></span>
                <span class="timeline-label">Închidere înscrieri</span>
            </li>
            <li>
                <span class="timeline-date"><?= date('d.m.Y', strtotime($settings['comp_start'])) ?></span>
                <span class="timeline-label">Start olimpiadă</span>
            </li>
            <li>
                <span class="timeline-date"><?= date('d.m.Y', strtotime($settings['comp_end'])) ?></span>
                <span class="timeline-label">Festivitate premiere</span>
            </li>
        </ul>
    </div>
</section>
