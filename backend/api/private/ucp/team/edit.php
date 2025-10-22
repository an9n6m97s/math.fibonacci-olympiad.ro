<?php
/*
  Team edit endpoint (mysqli)
  - Uses $conn from /backend/headerBackend.php
  - Validează câmpurile din formular
  - Folosește manager_id din sesiune (nu din POST)
  - NU permite modificarea team_code sau team_logo
  - Updatează doar câmpurile editabile
*/

header('Content-Type: application/json; charset=utf-8');
require_once $_SERVER['DOCUMENT_ROOT'] . '/backend/headerBackend.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    respond(405, 'Method Not Allowed');
}

if (!userHasTeam()) {
    respond(403, 'No team found for this user.');
}

/* Auth */
$sessionKey    = getenv('SESSION_USER_ID') ?: 'uid';
$managerUserId = (int)($_SESSION[$sessionKey] ?? 0);
if ($managerUserId <= 0) {
    respond(401, 'Not authenticated.');
}

/* Helpers */
function normalize_url_https(string $u): string
{
    $u = trim($u);
    if ($u === '') return $u;
    if (!preg_match('~^https?://~i', $u)) {
        $u = 'https://' . $u;
    }
    return $u;
}

/* Gather + normalize */
$name      = trim((string)post('team_name'));
$city      = trim((string)post('team_city'));
$country   = trim((string)post('team_country'));
$org_name  = trim((string)post('org_name'));
$website   = normalize_url_https((string)post('team_website'));
$email = strtolower(trim((string)post('team_email')));
$phone = trim((string)post('team_phone'));

/* Required checks */
if (
    $name === '' || $city === '' || $country === '' || $org_name === '' ||
    $website === '' || $email === '' || $phone === ''
) {
    respond(400, 'Please fill all required fields.');
}

/* Formats */
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    respond(400, 'Please enter a valid email address.');
}
$digits = preg_replace('/\D+/', '', $phone);
if (!(preg_match('/^[+]?[0-9\s().\-]{7,20}$/', $phone) && strlen($digits) >= 7)) {
    respond(400, 'Please enter a valid phone number.');
}
if (!filter_var($website, FILTER_VALIDATE_URL) || !preg_match('~^https?://~i', $website)) {
    respond(400, 'Please enter a valid website URL starting with http(s)://');
}
$namePattern = "/^[A-Za-z0-9À-ÖØ-öø-ÿ'’.\\- &()]{3,}$/u";
if (!preg_match($namePattern, $name)) {
    respond(400, 'Please enter a valid team name (min 3 characters).');
}

/* Length caps from schema */
if (mb_strlen($name) > 120)      respond(400, 'Team Name too long (max 120).');
if (mb_strlen($city) > 100)      respond(400, 'City too long (max 100).');
if (mb_strlen($country) > 100)   respond(400, 'Country too long (max 100).');
if (mb_strlen($org_name) > 190)  respond(400, 'Organization Name too long (max 190).');
if (mb_strlen($website) > 190)   respond(400, 'Website too long (max 190).');
if (mb_strlen($email) > 190) respond(400, 'Email too long (max 190).');
if (mb_strlen($phone) > 40) respond(400, 'Phone too long (max 40).');

/* DB */
if (!($conn instanceof mysqli)) {
    respond(500, 'Database connection not available.');
}

$conn->begin_transaction();

try {
    // Update only editable fields, do not touch team_code or team_logo
    $upd = $conn->prepare('
        UPDATE teams
        SET name = ?, city = ?, country = ?, org_name = ?, website = ?, email = ?, phone = ?, updated_at = NOW()
        WHERE manager_id = ?
        LIMIT 1
    ');
    if (!$upd) throw new Exception('Prepare failed: ' . $conn->error);

    $upd->bind_param(
        'sssssssi',
        $name,
        $city,
        $country,
        $org_name,
        $website,
        $email,
        $phone,
        $managerUserId
    );

    if (!$upd->execute()) {
        throw new Exception('Execute failed: ' . $upd->error);
    }

    if ($upd->affected_rows < 1) {
        $upd->close();
        $conn->rollback();
        respond(404, 'No team found or nothing changed.');
    }

    $upd->close();
    $conn->commit();

    respond(200, 'Team updated successfully.');
} catch (Throwable $e) {
    $conn->rollback();
    sendDebugNotify(
        'Team edit error',
        "Error during team edit: {$e->getMessage()} | File: {$e->getFile()} | Line: {$e->getLine()}"
    );
    respond(500, 'Database error. Please try again.');
}
