<?php
setPageTitle('Înscriere participanți');

$timezone = $settings['timezone'] ?? 'Europe/Bucharest';
$tz = new DateTimeZone($timezone);
$now = new DateTimeImmutable('now', $tz);
$registrationOpen = new DateTimeImmutable($settings['registration_open'], $tz);
$registrationClose = new DateTimeImmutable($settings['registration_close'], $tz);
?>
<section class="page-title">
    <div class="page-title-icon" style="background-image:url(/assets/images/icons/page-title_icon-1.webp)"></div>
    <div class="page-title-icon-two" style="background-image:url(/assets/images/icons/page-title_icon-2.webp)"></div>
    <div class="page-title-shadow" style="background-image:url(/assets/images/background/page-title-1.webp)"></div>
    <div class="page-title-shadow_two" style="background-image:url(/assets/images/background/page-title-2.webp)"></div>
    <div class="auto-container">
        <h2>Înscriere Fibonacci Romania Math Olympiad</h2>
        <ul class="bread-crumb clearfix">
            <li><a href="/">Acasă</a></li>
            <li>Înscriere</li>
        </ul>
    </div>
</section>

<section class="register-one py-120">
    <div class="auto-container">
        <?php if (isset($_GET['registration']) && $_GET['registration'] === 'success'): ?>
            <div class="register-card">
                <h1 class="register-title">Înscriere trimisă</h1>
                <p class="register-lead">Mulțumim pentru interesul acordat Olimpiadei de Matematică Fibonacci România.</p>
                <p class="register-text">
                    Am înregistrat datele tale și am trimis un email de confirmare la adresa introdusă. Te rugăm să verifici și folderele Spam/Promotions.
                    Vei primi detalii suplimentare privind programul și materialele de pregătire imediat ce perioada de înscriere se încheie.
                </p>
                <a class="template-btn btn-style-one mt-4" href="/">
                    <span class="btn-wrap">
                        <span class="text-one">Înapoi la început</span>
                        <span class="text-two">Înapoi la început</span>
                    </span>
                </a>
            </div>
        <?php elseif ($now < $registrationOpen): ?>
            <div class="register-card">
                <h1 class="register-title">Înscrierile se deschid curând</h1>
                <p class="register-lead">Perioada de înscriere începe la <?= $registrationOpen->format('d F Y') ?>.</p>
                <p class="register-text">
                    Îți recomandăm să pregătești din timp acordul profesorului coordonator și datele de contact. Până atunci, poți consulta regulamentele și resursele recomandate.
                </p>
                <a href="/principles" class="template-btn btn-style-one mt-4">
                    <span class="btn-wrap">
                        <span class="text-one">Vezi structura probelor</span>
                        <span class="text-two">Vezi structura probelor</span>
                    </span>
                </a>
            </div>
        <?php elseif ($now > $registrationClose): ?>
            <div class="register-card">
                <h1 class="register-title">Înscrierile s-au încheiat</h1>
                <p class="register-lead">Lista de participanți pentru ediția curentă este completă.</p>
                <p class="register-text">
                    Îți mulțumim pentru interes și te invităm să ne urmărești pentru anunțurile referitoare la ediția următoare. Dacă ai întrebări, scrie-ne la <?= $settings['contact_email'] ?>.
                </p>
                <a href="/" class="template-btn btn-style-one mt-4">
                    <span class="btn-wrap">
                        <span class="text-one">Înapoi la început</span>
                        <span class="text-two">Înapoi la început</span>
                    </span>
                </a>
            </div>
        <?php else: ?>
            <div class="inner-container">
                <h3 class="text-center mb-3">Formular de înscriere elev</h3>
                <p class="text text-center mb-4">
                    Completează datele de mai jos. După trimitere, vei primi un email de confirmare cu informații despre pașii următori și documentele necesare.
                </p>

                <div class="default-form register-form">
                    <form method="post" id="math-registration-form" novalidate>
                        <div class="row clearfix">
                            <div class="form-group col-lg-6 col-md-6 col-sm-12">
                                <input type="text" name="first_name" placeholder="Prenume" required>
                            </div>
                            <div class="form-group col-lg-6 col-md-6 col-sm-12">
                                <input type="text" name="last_name" placeholder="Nume" required>
                            </div>
                            <div class="form-group col-lg-6 col-md-6 col-sm-12">
                                <input type="email" name="email" placeholder="Email" required>
                            </div>
                            <div class="form-group col-lg-6 col-md-6 col-sm-12">
                                <input type="tel" name="phone" placeholder="Număr de telefon" required>
                            </div>
                            <div class="form-group col-lg-6 col-md-6 col-sm-12">
                                <input type="text" name="city" placeholder="Oraș" required>
                            </div>
                            <div class="form-group col-lg-6 col-md-6 col-sm-12">
                                <input type="text" name="school" placeholder="Liceu / Școală" required>
                            </div>
                            <div class="form-group col-lg-6 col-md-6 col-sm-12">
                                <input type="text" name="grade" placeholder="Clasa (ex: IX, X, XI)" required>
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <button type="submit" class="template-btn btn-style-one">
                                <span class="btn-wrap">
                                    <span class="text-one">Trimite înscrierea</span>
                                    <span class="text-two">Trimite înscrierea</span>
                                </span>
                            </button>
                        </div>
                        <div id="registration-message" class="alert mt-4 d-none" role="alert"></div>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
