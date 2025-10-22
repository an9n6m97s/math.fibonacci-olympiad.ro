<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/backend/headerBackend.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond_error(405, 'Metodă neacceptată.');
}

$requiredFields = ['first_name', 'last_name', 'email', 'phone', 'city', 'school', 'grade'];
foreach ($requiredFields as $field) {
    if (empty($_POST[$field])) {
        respond_error(422, 'Te rugăm să completezi toate câmpurile obligatorii.');
    }
}

$now = new DateTimeImmutable('now', new DateTimeZone($settings['timezone'] ?? 'Europe/Bucharest'));
$openDate = new DateTimeImmutable($settings['registration_open']);
$closeDate = new DateTimeImmutable($settings['registration_close']);

if ($now < $openDate) {
    respond_error(403, 'Înscrierile nu sunt încă deschise.');
}

if ($now > $closeDate) {
    respond_error(403, 'Înscrierile s-au încheiat pentru această ediție.');
}

$firstName = trim($_POST['first_name']);
$lastName  = trim($_POST['last_name']);
$email     = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
$phone     = trim($_POST['phone']);
$city      = trim($_POST['city']);
$school    = trim($_POST['school']);
$grade     = trim($_POST['grade']);

if (!$email) {
    respond_error(422, 'Adresa de email introdusă nu este validă.');
}

if (!preg_match('/^[0-9+ ()-]{6,}$/', $phone)) {
    respond_error(422, 'Numărul de telefon nu pare valid.');
}

try {
    $stmtCheck = $conn->prepare('SELECT COUNT(*) AS total FROM participants WHERE email = ?');
    $stmtCheck->bind_param('s', $email);
    $stmtCheck->execute();
    $result = $stmtCheck->get_result();
    $existing = $result ? (int)($result->fetch_assoc()['total'] ?? 0) : 0;
    $stmtCheck->close();

    if ($existing > 0) {
        respond_error(409, 'Există deja o înscriere cu această adresă de email.');
    }

    $stmt = $conn->prepare('INSERT INTO participants (first_name, last_name, email, phone, city, school, class) VALUES (?, ?, ?, ?, ?, ?, ?)');
    $stmt->bind_param('sssssss', $firstName, $lastName, $email, $phone, $city, $school, $grade);
    $stmt->execute();
    $participantId = $stmt->insert_id;
    $stmt->close();

    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = getenv('MAIL_HOST');
    $mail->SMTPAuth   = true;
    $mail->Username   = getenv('MAIL_USERNAME');
    $mail->Password   = getenv('MAIL_PASSWORD');
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = getenv('MAIL_PORT') ?: 465;

    $mail->setFrom(getenv('MAIL_FROM') ?: $settings['contact_email'], $settings['competition_name']);
    $mail->addAddress($email, $firstName . ' ' . $lastName);
    $mail->isHTML(true);
    $mail->Subject = 'Confirmare înscriere · Fibonacci Romania Math Olympiad';

    $eventDates = sprintf('%s – %s', date('d.m.Y', strtotime($settings['comp_start'])), date('d.m.Y', strtotime($settings['comp_end'])));

    $mail->Body = <<<HTML
        <p>Bună {$firstName},</p>
        <p>Îți mulțumim pentru înscrierea la <strong>{$settings['competition_name']}</strong>. Am înregistrat datele tale și îți vom transmite în curând programul complet și materialele de pregătire.</p>
        <p><strong>Detalii eveniment:</strong><br>
        Data: {$eventDates}<br>
        Locație: Liceul Teoretic de Informatică „Alexandru Marghiloman”, Buzău</p>
        <p>Dacă ai întrebări suplimentare, ne poți scrie la <a href="mailto:{$settings['contact_email']}">{$settings['contact_email']}</a>.</p>
        <p>Cu prietenie,<br>Echipa Fibonacci România</p>
    HTML;

    $mail->send();

    respond_ok('Înscrierea a fost înregistrată. Verifică emailul pentru confirmare.', [
        'id' => $participantId,
    ]);
} catch (mysqli_sql_exception $exception) {
    if ((int)$exception->getCode() === 1062) {
        respond_error(409, 'Există deja o înscriere cu această adresă de email.');
    }
    respond_error(500, 'Nu am putut înregistra înscrierea. Încearcă din nou sau contactează-ne.', [
        'error' => $exception->getMessage(),
    ]);
}
