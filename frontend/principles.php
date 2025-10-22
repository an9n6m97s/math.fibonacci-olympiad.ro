<?php setPageTitle('Principii de organizare'); ?>
<section class="page-title">
    <div class="page-title-icon" style="background-image:url(/assets/images/icons/page-title_icon-1.webp)"></div>
    <div class="page-title-icon-two" style="background-image:url(/assets/images/icons/page-title_icon-2.webp)"></div>
    <div class="page-title-shadow" style="background-image:url(/assets/images/background/page-title-1.webp)"></div>
    <div class="page-title-shadow_two" style="background-image:url(/assets/images/background/page-title-2.webp)"></div>
    <div class="auto-container">
        <h2>Principiile Fibonacci Romania Math Olympiad</h2>
        <ul class="bread-crumb clearfix">
            <li><a href="/">Acasă</a></li>
            <li>Principii</li>
        </ul>
    </div>
</section>

<section class="py-120">
    <div class="auto-container">
        <div class="row gy-5 align-items-start">
            <div class="col-lg-8">
                <div class="section-heading mb-4">
                    <h3 class="sub-heading is-border border-anim">Structură inspirată din seria Fibonacci</h3>
                    <h2 class="text-anim" data-effect="fade-in-bottom">Construim etape progresive pentru a evidenția evoluția fiecărui elev</h2>
                    <p>
                        Fiecare probă pornește de la concepte fundamentale și adaugă treptat niveluri noi de dificultate, exact ca termenii
                        seriei Fibonacci. Participanții sunt încurajați să își explice strategiile și să își argumenteze soluțiile, punând accent pe
                        claritate și rigoare matematică.
                    </p>
                </div>

                <div class="principles-grid">
                    <article class="principle-card">
                        <div class="principle-icon"><i class="fa-regular fa-seedling"></i></div>
                        <h4>Etapa I · Fundament</h4>
                        <p>Probleme de încălzire care verifică intuiția și înțelegerea conceptelor de bază. Rezolvările sunt discutate în echipă.</p>
                    </article>
                    <article class="principle-card">
                        <div class="principle-icon"><i class="fa-regular fa-layer-group"></i></div>
                        <h4>Etapa II · Dezvoltare</h4>
                        <p>Subiecte intermediare ce combină arii diferite ale matematicii și încurajează colaborarea între membri.</p>
                    </article>
                    <article class="principle-card">
                        <div class="principle-icon"><i class="fa-regular fa-sparkles"></i></div>
                        <h4>Etapa III · Excelență</h4>
                        <p>Provocări avansate, cu accent pe demonstrații originale și metode creative. Juriul oferă feedback personalizat.</p>
                    </article>
                </div>

                <div class="mt-5">
                    <h3 class="mb-3">Cum se acordă punctajul</h3>
                    <ul class="fibo-list">
                        <li><strong>Corectitudine (50%)</strong> – modul în care soluția respectă cerința și demonstrează rezultatul.</li>
                        <li><strong>Creativitate (30%)</strong> – alternative ingenioase, observații suplimentare, optimizări.</li>
                        <li><strong>Claritate (20%)</strong> – prezentarea logică a pașilor, organizarea ideilor și argumentelor.</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4">
                <aside class="principles-sidebar">
                    <h4>Calendar orientativ</h4>
                    <ul class="timeline-list">
                        <li>
                            <span class="timeline-date"><?= date('d.m.Y', strtotime($settings['registration_open'])) ?></span>
                            <span class="timeline-label">Deschiderea înscrierilor</span>
                        </li>
                        <li>
                            <span class="timeline-date"><?= date('d.m.Y', strtotime($settings['registration_close'])) ?></span>
                            <span class="timeline-label">Închiderea înscrierilor</span>
                        </li>
                        <li>
                            <span class="timeline-date"><?= date('d.m.Y', strtotime($settings['comp_start'])) ?></span>
                            <span class="timeline-label">Deschiderea oficială</span>
                        </li>
                        <li>
                            <span class="timeline-date"><?= date('d.m.Y', strtotime($settings['comp_end'])) ?></span>
                            <span class="timeline-label">Festivitatea de premiere</span>
                        </li>
                    </ul>

                    <div class="mt-5">
                        <h4>Resurse recomandate</h4>
                        <ul class="fibo-list">
                            <li><a href="https://artofproblemsolving.com/" target="_blank" rel="noopener">Art of Problem Solving</a></li>
                            <li><a href="https://www.cut-the-knot.org/" target="_blank" rel="noopener">Cut the Knot · Intuiție matematică</a></li>
                            <li><a href="https://math.stackexchange.com/" target="_blank" rel="noopener">Math StackExchange</a></li>
                        </ul>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</section>
