<?php
/*
  Team creation endpoint (mysqli)
  - Uses $conn from /backend/headerBackend.php
  - Validează câmpurile din formular
  - NU folosește user_id din POST; ia managerul din sesiune
  - Generează/validează code (unic, CHAR(8))
  - Setează pending_invites_json la [] (JSON gol)
  - Lasă status/docs_status/created_at/updated_at pe default
*/

header('Content-Type: application/json; charset=utf-8');
require_once $_SERVER['DOCUMENT_ROOT'] . '/backend/headerBackend.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    respond(405, 'Method Not Allowed');
}

if (userHasTeam()) {
    respond(403, 'You already have a team.');
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

function make_team_code_candidate(int $len = 8): string
{
    // A-Z + 0-9 fără caractere confuze
    $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    $out = '';
    $max = strlen($alphabet) - 1;
    for ($i = 0; $i < $len; $i++) {
        $out .= $alphabet[random_int(0, $max)];
    }
    return $out;
}

function validate_logo_path_or_fail(string $relPath): void
{
    // Imaginea e obligatorie, încărcată anterior în /assets/images/users/
    if ($relPath === '' || $relPath[0] !== '/') {
        respond(400, 'Invalid logo path.');
    }
    $base = realpath($_SERVER['DOCUMENT_ROOT'] . '/assets/images/users/');
    if ($base === false) respond(500, 'Upload base folder is not available.');

    $target = realpath($_SERVER['DOCUMENT_ROOT'] . $relPath);
    if ($target === false || !is_file($target)) {
        respond(400, 'Team logo not found. Please re-upload.');
    }
    if (strpos($target, $base) !== 0) {
        respond(400, 'Invalid logo location.');
    }
    $imgType = @exif_imagetype($target);
    $allowed = [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP];
    if ($imgType === false || !in_array($imgType, $allowed, true)) {
        respond(400, 'Logo must be JPG, PNG, or WEBP.');
    }
}

/* Gather + normalize */
$code = strtoupper(trim((string)post('code')));
$name      = trim((string)post('team_name'));
$city      = trim((string)post('team_city'));
$country   = trim((string)post('team_country'));
$org_name  = trim((string)post('org_name'));
$website   = normalize_url_https((string)post('team_website'));
$email = strtolower(trim((string)post('email')));
$phone = trim((string)post('phone'));
$logo = trim((string)post('logo')); // obligatoriu, dar NU se stochează în acest tabel

/* Required checks */
if (
    $name === '' || $city === '' || $country === '' || $org_name === '' ||
    $website === '' || $email === '' || $phone === '' || $logo === ''
) {
    respond(400, 'Please fill all required fields.');
}

/* Formats */
if (!preg_match('/^[A-Z0-9\-]{4,32}$/', $code)) {
    // invalid din client? îl regenerăm noi
    $code = '';
}
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

/* Imaginea chiar există și e imagine? */
validate_logo_path_or_fail($logo);

/* DB */
if (!($conn instanceof mysqli)) {
    respond(500, 'Database connection not available.');
}

$conn->begin_transaction();

try {
    /* code unic; char(8). Dacă nu e bun sau există, generăm până iese unic. */
    $check = $conn->prepare('SELECT id FROM teams WHERE code = ? LIMIT 1');
    if (!$check) throw new Exception('Prepare failed: ' . $conn->error);

    $finalCode = $code;
    $tries = 0;
    while ($tries < 12) {
        if ($finalCode === '' || strlen($finalCode) !== 8) {
            $finalCode = make_team_code_candidate(8);
        }
        $check->bind_param('s', $finalCode);
        $check->execute();
        $check->store_result();
        if ($check->num_rows === 0) break; // unic
        $finalCode = ''; // forțează regenerare
        $tries++;
    }
    $check->close();

    if ($finalCode === '' || strlen($finalCode) !== 8) {
        $conn->rollback();
        respond(500, 'Could not allocate a unique team code.');
    }

    /* Insert: status/docs_status/created_at/updated_at merg pe DEFAULT
       pending_invites_json -> '[]' (JSON gol) */
    $ins = $conn->prepare('
        INSERT INTO teams
          (manager_id, code, name, city, country, org_name, website, email, phone, logo, pending_invites_json)
        VALUES
          (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ');
    if (!$ins) throw new Exception('Prepare failed: ' . $conn->error);

    $emptyInvites = '[]';
    $ins->bind_param(
        'issssssssss',
        $managerUserId,
        $finalCode,
        $name,
        $city,
        $country,
        $org_name,
        $website,
        $email,
        $phone,
        $logo,
        $emptyInvites
    );

    if (!$ins->execute()) {
        if ($ins->errno === 1062) {
            $ins->close();
            $conn->rollback();
            respond(409, 'Duplicate entry. Team code already exists.');
        }
        throw new Exception('Execute failed: ' . $ins->error);
    }

    $teamId = (int)$conn->insert_id;
    $ins->close();
    $conn->commit();

    // Returnăm și logo-ul doar ca echo back, nu e în tabelul teams
    respond(201, 'Team created successfully.', [
        'team_id'   => $teamId,
        'code' => $finalCode,
        'name'      => $name,
        'logo'      => $logo
    ]);
} catch (Throwable $e) {
    $conn->rollback();
    sendDebugNotify(
        'Team create error',
        "Error during team create: {$e->getMessage()} | File: {$e->getFile()} | Line: {$e->getLine()}"
    );
    respond(500, 'Database error. Please try again.');
}
