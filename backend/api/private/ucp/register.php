<?php
/*
  Registration endpoint (mysqli)
  - Uses $conn from /backend/headerBackend.php
  - Server-side validation (name/email/password/phone/role/org fields)
  - Argon2id hashing (bcrypt fallback)
  - 26-char public_id (hex)
  - Duplicate checks and transaction
*/

header('Content-Type: application/json; charset=utf-8');
require_once $_SERVER['DOCUMENT_ROOT'] . '/backend/headerBackend.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


function make_public_id(): string
{
    return bin2hex(random_bytes(13)); // 26 chars
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    respond(405, 'Method Not Allowed');
}

/* Gather + validate */
$fullName = post('fullName');
$email    = strtolower(post('email'));
$password = post('password');
$confirm  = post('confirm-password');
$phone    = post('phone');
$role     = post('role');
$orgType  = post('org_type');
$orgName  = post('org_name');
$country  = post('country');
$city     = post('city');

if (
    $fullName === '' || $email === '' || $password === '' ||
    $phone === '' || $role === '' || $orgType === '' || $orgName === '' ||
    $country === '' || $city === ''
) {
    respond(400, 'Please fill all required fields.' . $fullName . ' ' . $email . ' ' . $password . ' ' . $phone . ' ' . $role . ' ' . $orgType . ' ' . $orgName . ' ' . $country . ' ' . $city);
}

$namePattern = "/^[A-Za-zÀ-ÖØ-öø-ÿ'’.\\-]+(?:\\s+[A-Za-zÀ-ÖØ-öø-ÿ'’.\\-]+)+$/u";
if (!preg_match($namePattern, $fullName) || mb_strlen($fullName) < 3) {
    respond(400, 'Please enter your full name (first and last).');
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    respond(400, 'Please enter a valid email address.');
}
$pwdOk = (bool)preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&.,;:!#()_\-\+\[\]{}]).{8,}$/', $password);
if (!$pwdOk) {
    respond(400, 'Password must be at least 8 characters, with lowercase, uppercase, number, and special character.');
}
if ($password !== $confirm) {
    respond(400, 'Passwords do not match.');
}
$phoneDigits = preg_replace('/\D+/', '', $phone);
if (!(preg_match('/^[+]?[0-9\s().\-]{7,20}$/', $phone) && strlen($phoneDigits) >= 7)) {
    respond(400, 'Please enter a valid phone number.');
}

$allowedRoles = ['coach', 'team_leader', 'member'];
if (!in_array($role, $allowedRoles, true)) {
    respond(400, 'Invalid role selected.');
}
$allowedOrg = ['School', 'Club', 'Company', 'Independent'];
if (!in_array($orgType, $allowedOrg, true)) {
    respond(400, 'Invalid organization type.');
}

/* Length caps per schema */
if (mb_strlen($email) > 190)     respond(400, 'Email is too long (max 190 chars).');
if (mb_strlen($fullName) > 190)  respond(400, 'Full Name is too long (max 190 chars).');
if (mb_strlen($phone) > 40)      respond(400, 'Phone is too long (max 40 chars).');
if (mb_strlen($orgName) > 190)   respond(400, 'Organization Name is too long (max 190 chars).');
if (mb_strlen($country) > 100)   respond(400, 'Country is too long (max 100 chars).');
if (mb_strlen($city) > 100)      respond(400, 'City is too long (max 100 chars).');

/* Password hash */
if (defined('PASSWORD_ARGON2ID')) {
    $passwordHash = password_hash($password, PASSWORD_ARGON2ID, ['time_cost' => 4, 'memory_cost' => 1 << 17, 'threads' => 2]);
} else {
    $passwordHash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
}
if ($passwordHash === false) {
    respond(500, 'Failed to hash password.');
}

/* Transaction */
if (!($conn instanceof mysqli)) {
    respond(500, 'Database connection not available.');
}
$conn->begin_transaction();

try {
    /* Unique email check */
    $stmt = $conn->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
    if (!$stmt) throw new Exception('Prepare failed: ' . $conn->error);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->close();
        $conn->rollback();
        respond(409, 'This email is already registered.');
    }
    $stmt->close();

    /* Unique phone number check */
    $stmt = $conn->prepare('SELECT id FROM users WHERE phone = ? LIMIT 1');
    if (!$stmt) throw new Exception('Prepare failed: ' . $conn->error);
    $stmt->bind_param('s', $phone);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->close();
        $conn->rollback();
        respond(409, 'This phone number is already registered.');
    }
    $stmt->close();

    /* Unique public_id generation */
    $publicId = '';
    $check = $conn->prepare('SELECT id FROM users WHERE public_id = ? LIMIT 1');
    if (!$check) throw new Exception('Prepare failed: ' . $conn->error);
    for ($i = 0; $i < 5; $i++) {
        $candidate = make_public_id();
        $check->bind_param('s', $candidate);
        $check->execute();
        $check->store_result();
        if ($check->num_rows === 0) {
            $publicId = $candidate;
            break;
        }
    }
    $check->close();
    if ($publicId === '') {
        $conn->rollback();
        respond(500, 'Could not generate a unique ID. Please try again.');
    }

    /* Unique secure_token generation */
    $secureToken = '';
    $checkToken = $conn->prepare('SELECT id FROM users WHERE secure_token = ? LIMIT 1');
    if (!$checkToken) throw new Exception('Prepare failed: ' . $conn->error);
    for ($i = 0; $i < 5; $i++) {
        $candidateToken = generateSecureToken();
        $checkToken->bind_param('s', $candidateToken);
        $checkToken->execute();
        $checkToken->store_result();
        if ($checkToken->num_rows === 0) {
            $secureToken = $candidateToken;
            break;
        }
    }
    $checkToken->close();
    if ($secureToken === '') {
        $conn->rollback();
        respond(500, 'Could not generate a unique secure token. Please try again.');
    }

    /* Insert */
    $ins = $conn->prepare('
    INSERT INTO users
      (public_id, email, password_hash, full_name, phone, role, admin_role, org_type, org_name, country, city, secure_token, is_email_verified)
    VALUES
      (?, ?, ?, ?, ?, ?, DEFAULT, ?, ?, ?, ?, ?, 0)
  ');
    if (!$ins) throw new Exception('Prepare failed: ' . $conn->error);

    $ins->bind_param(
        'sssssssssss',
        $publicId,
        $email,
        $passwordHash,
        $fullName,
        $phone,
        $role,
        $orgType,
        $orgName,
        $country,
        $city,
        $secureToken,
    );

    if (!$ins->execute()) {
        if ($ins->errno === 1062) {
            $ins->close();
            $conn->rollback();
            respond(409, 'Duplicate entry. Email or ID already exists.');
        }
        throw new Exception('Execute failed: ' . $ins->error);
    }

    /* Get AUTO_INCREMENT id from users.id */
    $userId = (int)$conn->insert_id;

    /* Fallback (very rare): resolve via unique public_id BEFORE commit */
    if ($userId <= 0) {
        $ref = $conn->prepare('SELECT id FROM users WHERE public_id = ? LIMIT 1');
        if (!$ref) throw new Exception('Prepare failed: ' . $conn->error);
        $ref->bind_param('s', $publicId);
        $ref->execute();
        $ref->bind_result($userId);
        $ref->fetch();
        $ref->close();
    }

    if ($userId <= 0) {
        $ins->close();
        $conn->rollback();
        respond(500, 'Failed to retrieve user ID after insert.');
    }

    $ins->close();
    $conn->commit();

    $emailUID = generateEmailUID();
    $viewInBrowserUrl = 'https://fibonacci-olympiad.ro/email?view=' . $emailUID;

    $htmlContent = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/assets/email/registration.html');

    $htmlContent = str_replace(
        ['{{name}}', '{{view_in_browser_url}}', '{{secured_token}}', '{{public_token}}', '{{email}}', '{{email_decoded}}'],
        [$fullName, $viewInBrowserUrl, $secureToken, $publicId, urlencode($email), $email],
        $htmlContent
    );

    $to = $email;
    $content = $htmlContent;
    $subject = 'Registration Successful - Fibonacci Romania 2026';

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = getenv('MAIL_HOST');
        $mail->SMTPAuth   = true;
        $mail->Username   = getenv('MAIL_USERNAME');
        $mail->Password   = getenv('MAIL_PASSWORD');
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        $mail->setFrom(getenv('MAIL_USERNAME'), 'Fibonacci Romania');
        $mail->addReplyTo('office@fibonacci-olympiad.ro', 'Information');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $htmlContent;

        if ($mail->send()) {
            logEmails($to, $subject, $htmlContent, $userId, $emailUID);
        } else {
            sendDebugNotify(
                'Email sending failed',
                "Failed to send registration email to $to: {$mail->ErrorInfo}"
            );
        }
    } catch (Exception $e) {
        sendDebugNotify(
            'Email sending error',
            "Failed to send registration email to $to: {$mail->ErrorInfo}"
        );
    }

    respond(201, 'Registration successful.', [
        'public_id' => $publicId,
        'email'     => $email,
        'full_name' => $fullName
    ]);
} catch (Throwable $e) {
    if ($conn->errno) { /* no-op */
    }
    $conn->rollback();
    sendDebugNotify(
        'Registration error',
        "Error during registration: {$e->getMessage()} | File: {$e->getFile()} | Line: {$e->getLine()}"
    );
    respond(500, 'Database error. Please try again.');
}
