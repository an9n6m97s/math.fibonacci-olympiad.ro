<?php
$participantStats = getParticipantStatistics();
?>
<section class="slider-section math-hero">
    <div class="container">
        <div class="row align-items-center gy-5">
            <div class="col-lg-6">
                <div class="hero-copy">
                    <h1 class="hero-title">Fibonacci Romania Math Olympiad</h1>
                    <p class="hero-lead">
                        Olimpiada națională dedicată elevilor pasionați de matematică, logică și gândire creativă. Inspirați de
                        seria Fibonacci, provocările noastre sunt construite pentru a pune în valoare curiozitatea, rigoarea
                        și eleganța soluțiilor.
                    </p>
                    <ul class="hero-highlights">
                        <li><i class="fa-solid fa-circle-check"></i> Probe individuale și pe echipe, structurate pe niveluri de studiu.</li>
                        <li><i class="fa-solid fa-circle-check"></i> Feedback detaliat din partea profesorilor coordonatori.</li>
                        <li><i class="fa-solid fa-circle-check"></i> Ateliere de pregătire și sesiuni inspiraționale cu invitați speciali.</li>
                    </ul>
                    <div class="hero-actions">
                        <a class="default-btn" href="/registration"><i class="fa-solid fa-pen"></i>Înscrie-te acum</a>
                        <a class="default-btn btn-style-two" href="/principles"><i class="fa-regular fa-lightbulb"></i>Vezi principiile</a>
                    </div>
                    <div class="hero-meta">
                        <div class="meta-item">
                            <span class="meta-label">Perioada înscrierilor</span>
                            <span class="meta-value">
                                <?= date('d.m.Y', strtotime($settings['registration_open'])) ?> –
                                <?= date('d.m.Y', strtotime($settings['registration_close'])) ?>
                            </span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Perioada Olimpiadei</span>
                            <span class="meta-value">
                                <?= date('d.m.Y', strtotime($settings['comp_start'])) ?> –
                                <?= date('d.m.Y', strtotime($settings['comp_end'])) ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-visual" role="presentation">
                    <img src="/assets/images/general/hero-background.jpg" alt="Ilustrație Olimpiadă de Matematică" class="img-fluid" />
                </div>
            </div>
        </div>
    </div>
</section>

<section class="about-one py-120">
    <div class="auto-container">
        <div class="row align-items-center gy-5">
            <div class="col-lg-6">
                <div class="about-image">
                    <div class="about-card">Seria Fibonacci & excelență în educație</div>
                    <img src="/assets/images/general/team-1.jpg" alt="Elevi la competiție" class="img-fluid rounded-4" />
                </div>
            </div>
            <div class="col-lg-6">
                <div class="section-heading">
                    <h3 class="sub-heading is-border border-anim">Despre competiție</h3>
                    <h2 class="text-anim" data-effect="fade-in-bottom">Construim o comunitate în jurul matematicii</h2>
                    <p>
                        Fibonacci Romania Math Olympiad reunește anual elevi de gimnaziu și liceu pentru o experiență completă de
                        concurs, mentorat și colaborare. Structura probelor urmează filosofia seriei Fibonacci: provocările cresc
                        gradual în complexitate, combinând atât intuiția, cât și rigurozitatea demonstrativă.
                    </p>
                    <div class="about-stats">
                        <div class="stat-item">
                            <span class="stat-value"><?= number_format($participantStats['total']) ?></span>
                            <span class="stat-label">Înscriși confirmat</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value"><?= number_format($participantStats['cities']) ?></span>
                            <span class="stat-label">Orașe reprezentate</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value"><?= number_format($participantStats['schools']) ?></span>
                            <span class="stat-label">Școli partenere</span>
                        </div>
                    </div>
                    <a href="/about" class="default-btn mt-4"><i class="fa-regular fa-circle-info"></i>Află mai multe</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="features-two py-120 bg-light">
    <div class="auto-container">
        <div class="section-heading text-center">
            <h3 class="sub-heading is-border border-anim">Principii de organizare</h3>
            <h2 class="text-anim" data-effect="fade-in-bottom">Matematica într-un format modern și prietenos</h2>
            <p>
                Fiecare etapă este construită astfel încât să valorifice progresul elevilor, să ofere feedback rapid și să încurajeze
                colaborarea. Rezultatele sunt comunicate transparent, iar echipa de coordonare este alături de participanți pe tot parcursul.
            </p>
        </div>
        <div class="row gy-4">
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fa-regular fa-chart-mixed"></i></div>
                    <h4>Progres gradual</h4>
                    <p>Problemele urmează logica seriei Fibonacci: pornesc de la concepte accesibile și cresc rapid în profunzime.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fa-regular fa-users"></i></div>
                    <h4>Mentorat dedicat</h4>
                    <p>Profesorii coordonatori oferă sesiuni de pregătire, corectură și recomandări personalizate pentru fiecare elev.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fa-regular fa-medal"></i></div>
                    <h4>Recunoaștere reală</h4>
                    <p>Diplomele și premiile sunt susținute de partenerii educaționali, cu oportunități de burse și tabere tematice.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="location-section py-120">
    <div class="auto-container">
        <div class="row gy-5 align-items-center">
            <div class="col-lg-6">
                <div class="section-heading">
                    <h3 class="sub-heading is-border border-anim">Locație</h3>
                    <h2 class="text-anim" data-effect="fade-in-bottom">Buzău · Liceul Teoretic de Informatică „Alexandru Marghiloman”</h2>
                    <p>
                        Olimpiada are loc într-un campus modern, cu săli dedicate probelor scrise și spații de colaborare pentru echipe. Accesul este facil,
                        atât cu trenul, cât și cu transport rutier, iar partenerii noștri locali oferă pachete speciale de cazare pentru participanți.
                    </p>
                    <ul class="list-style-two">
                        <li><i class="fa-solid fa-location-dot"></i>Str. Ivănețu nr. 7, Buzău, România</li>
                        <li><i class="fa-solid fa-calendar-days"></i><?= date('d F Y', strtotime($settings['comp_start'])) ?> – <?= date('d F Y', strtotime($settings['comp_end'])) ?></li>
                        <li><i class="fa-solid fa-clock"></i>Check-in participanți: ora 08:30 · Deschiderea oficială: ora 10:00</li>
                    </ul>
                    <a href="/contact" class="default-btn mt-3"><i class="fa-regular fa-map-location-dot"></i>Vezi indicațiile complete</a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="map-wrapper">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d225.68918610503897!2d26.84427709478381!3d45.15022860948328!2m3!1f0!2f39.40618408720114!3f0!3m2!1i1024!2i768!4f35!3m3!1m2!1s0x40b15fcd09a43519%3A0xe24f4d4a4da9a4fe!2sLiceul%20Teoretic%20Alexandru%20Marghiloman!5e1!3m2!1sro!2sro!4v1741377752673!5m2!1sro!2sro" width="100%" height="360" style="border:0; border-radius: 24px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="latest-participants py-120 bg-light">
    <div class="auto-container">
        <div class="section-heading text-center">
            <h3 class="sub-heading is-border border-anim">Participanți recenți</h3>
            <h2 class="text-anim" data-effect="fade-in-bottom">Elevi care s-au înscris în ultimele zile</h2>
            <p>Actualizăm zilnic lista pentru a evidenția diversitatea comunității Fibonacci România.</p>
        </div>
        <div class="row gy-4 justify-content-center">
            <?php if (!empty($participantStats['latest'])): ?>
                <?php foreach ($participantStats['latest'] as $participant): ?>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="participant-card">
                            <div class="avatar-placeholder" aria-hidden="true">
                                <span class="avatar-initials">
                                    <?= strtoupper(substr($participant['first_name'], 0, 1) . substr($participant['last_name'], 0, 1)) ?>
                                </span>
                            </div>
                            <h5><?= htmlspecialchars($participant['first_name'] . ' ' . $participant['last_name']) ?></h5>
                            <p class="text-muted mb-1"><i class="fa-regular fa-city"></i> <?= htmlspecialchars($participant['city']) ?></p>
                            <p class="text-muted mb-2"><i class="fa-regular fa-school"></i> <?= htmlspecialchars($participant['school'] ?? '') ?></p>
                            <span class="badge badge-soft-primary">Înscris la <?= date('d.m.Y', strtotime($participant['created_at'])) ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-lg-6">
                    <div class="alert alert-info text-center">Încă nu există participanți afișați. Fii printre primii care se înscriu!</div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
