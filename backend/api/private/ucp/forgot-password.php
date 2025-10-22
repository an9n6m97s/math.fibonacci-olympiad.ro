<?php
/*
  Forgot Password endpoint (mysqli)
  - Uses $conn from /backend/headerBackend.php
  - Accepts POST { email }
  - Always responds generically (no user enumeration)
  - Creates one-time token, stores hash + expiry in password_resets
  - Sends HTML email using /assets/email/forgot-password.html
*/

header('Content-Type: application/json; charset=utf-8');
require_once $_SERVER['DOCUMENT_ROOT'] . '/backend/headerBackend.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


/* ---------- Config ---------- */

const RESET_TOKEN_TTL_MINUTES = 60;
const RESET_EMAIL_TEMPLATE = '/assets/email/forgot-password.html';
const RESET_FRONTEND_URL = 'https://fibonacci-olympiad.ro/ucp/reset-password';

/* ---------- Helpers ---------- */
function make_reset_token(): array
{
    $token = bin2hex(random_bytes(32)); // 64 hex chars
    $hash  = hash('sha256', $token);
    return [$token, $hash];
}

function client_ip(): string
{
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '';
    $parts = explode(',', $ip);
    return trim($parts[0]);
}

/* ---------- Guard ---------- */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    respond(405, 'Method Not Allowed');
}

/* ---------- Input ---------- */
$email = strtolower(post('email'));
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    respond(200, 'If this email is registered, you will receive reset instructions shortly.');
}

/* ---------- Lookup user ---------- */
$userId = null;
$fullName = null;
$dbEmail = null;

if ($conn instanceof mysqli) {
    $stmt = $conn->prepare('SELECT id, full_name, email FROM users WHERE email = ? LIMIT 1');
    if ($stmt) {
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->bind_result($uid, $name, $eml);
        if ($stmt->fetch()) {
            $userId   = (int)$uid;
            $fullName = (string)$name;
            $dbEmail  = (string)$eml;
        }
        $stmt->close();
    }
}

/* ---------- If no user, still respond generic ---------- */
if (!$userId) {
    respond(200, 'If this email is registered, you will receive reset instructions shortly.');
}

/* ---------- Create/Store token ---------- */
try {
    [$token, $tokenHash] = make_reset_token();

    // cleanup old tokens for user
    $del = $conn->prepare('DELETE FROM password_resets WHERE user_id = ? OR expires_at < NOW()');
    if ($del) {
        $del->bind_param('i', $userId);
        $del->execute();
        $del->close();
    }

    $expiresAt = (new DateTimeImmutable('+' . RESET_TOKEN_TTL_MINUTES . ' minutes'))->format('Y-m-d H:i:s');
    $ua = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255);
    $ip = client_ip();

    $ins = $conn->prepare('
        INSERT INTO password_resets (user_id, token_hash, expires_at, request_ip, user_agent, created_at)
        VALUES (?, ?, ?, ?, ?, NOW())
    ');
    if ($ins) {
        $ins->bind_param('issss', $userId, $tokenHash, $expiresAt, $ip, $ua);
        $ins->execute();
        $ins->close();
    } else {
        sendDebugNotify('Reset token prepare failed', 'Error: ' . $conn->error);
    }

    /* ---------- Build email ---------- */
    $emailUID = generateEmailUID();
    $viewInBrowserUrl = 'https://fibonacci-olympiad.ro/email?view=' . $emailUID;
    $resetUrl = RESET_FRONTEND_URL . '?token=' . urlencode($token);

    $templatePath = $_SERVER['DOCUMENT_ROOT'] . RESET_EMAIL_TEMPLATE;
    $htmlContent = file_get_contents($templatePath);

    if ($htmlContent === false || $htmlContent === '') {
        sendDebugNotify('Forgot-password template missing', 'Path: ' . $templatePath);
        respond(200, 'If this email is registered, you will receive reset instructions shortly.');
    }

    $htmlContent = str_replace(
        ['{{name}}', '{{reset_url}}', '{{ttl_minutes}}', '{{view_in_browser_url}}', '{{email_decoded}}'],
        [$fullName, $resetUrl, RESET_TOKEN_TTL_MINUTES, $viewInBrowserUrl, $dbEmail],
        $htmlContent
    );

    /* ---------- Send email ---------- */
    $subject = 'Password Reset Instructions - Fibonacci Romania';
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
        $mail->addAddress($dbEmail, $fullName ?? '');
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $htmlContent;

        if ($mail->send()) {
            logEmails($dbEmail, $subject, $htmlContent, $userId, $emailUID);
        } else {
            sendDebugNotify('Password reset email failed', "Failed to send to $dbEmail: {$mail->ErrorInfo}");
        }
    } catch (Exception $e) {
        sendDebugNotify('Password reset mailer error', "Failed to send to $dbEmail: {$e->getMessage()}");
    }

    respond(200, 'If this email is registered, you will receive reset instructions shortly.');
} catch (Throwable $e) {
    sendDebugNotify('Forgot password error', "Error: {$e->getMessage()} | File: {$e->getFile()} | Line: {$e->getLine()}");
    respond(200, 'If this email is registered, you will receive reset instructions shortly.');
}
