<?php
header('Content-Type: application/json; charset=utf-8');
require_once $_SERVER['DOCUMENT_ROOT'] . '/backend/headerBackend.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/* ---------- helpers ---------- */

function get_client_ip(): string
{
    $keys = ['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'REMOTE_ADDR'];
    foreach ($keys as $k) {
        if (!empty($_SERVER[$k])) {
            $val = trim((string)$_SERVER[$k]);
            if ($k === 'HTTP_X_FORWARDED_FOR') {
                foreach (array_map('trim', explode(',', $val)) as $p) {
                    if (filter_var($p, FILTER_VALIDATE_IP)) return $p;
                }
            }
            if (filter_var($val, FILTER_VALIDATE_IP)) return $val;
        }
    }
    return '0.0.0.0';
}

function ipwho_lookup(string $ip): array
{
    $out = ['location' => 'Unknown', 'isp_org' => 'Unknown'];
    if (!filter_var($ip, FILTER_VALIDATE_IP)) return $out;

    $url = "http://ipwho.is/{$ip}?lang=en";
    $ch  = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 3,
        CURLOPT_CONNECTTIMEOUT => 2,
    ]);
    $res = curl_exec($ch);
    curl_close($ch);
    if (!$res) return $out;

    $j = json_decode($res, true);
    if (!is_array($j) || !($j['success'] ?? false)) return $out;

    $city    = trim((string)($j['city'] ?? ''));
    $region  = trim((string)($j['region'] ?? ''));
    $country = trim((string)($j['country'] ?? ''));
    $flag    = trim((string)($j['flag']['emoji'] ?? ''));
    $parts   = array_filter([$city, $region, $country]);
    $locStr  = $parts ? implode(', ', $parts) : 'Unknown';
    if ($flag) $locStr .= " {$flag}";

    $isp  = trim((string)($j['connection']['isp'] ?? ''));
    $org  = trim((string)($j['connection']['org'] ?? ''));
    $asn  = trim((string)($j['connection']['asn'] ?? ''));
    $io   = array_filter([$isp, $org, $asn ? "AS{$asn}" : '']);
    $ispOrg = $io ? implode(' • ', $io) : 'Unknown';

    return ['location' => $locStr, 'isp_org' => $ispOrg];
}

/**
 * Trimite emailul de "Failed sign-in" cu PHPMailer, log cu logEmails(), viewInBrowserUrl.
 */
function send_login_failed_email(int $userId, string $toEmail, string $fullName): void
{
    $baseUrl = rtrim(getenv('BASE_URL') ?: 'https://fibonacci-olympiad.ro', '/');
    $templatePath = $_SERVER['DOCUMENT_ROOT'] . '/assets/email/login-failed.html';
    $htmlContent  = @file_get_contents($templatePath);
    if ($htmlContent === false) {
        return;
    }

    $emailUID = generateEmailUID();
    $viewInBrowserUrl = $baseUrl . '/email?view=' . $emailUID;

    $ip = get_client_ip();
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    $page = $_SERVER['HTTP_REFERER'] ?? ($baseUrl . '/ucp/login');
    $geo = ipwho_lookup($ip);

    // Înlocuire placeholder-e exact ca în template
    $replacements = [
        '{{name}}'                => $fullName !== '' ? $fullName : 'there',
        '{{email}}'               => $toEmail,
        '{{when_utc}}'            => gmdate('Y-m-d H:i:s') . ' UTC',
        '{{ip}}'                  => $ip,
        '{{location}}'            => $geo['location'],
        '{{isp_org}}'             => $geo['isp_org'],
        '{{ua}}'                  => $ua,
        '{{page}}'                => $page,
        '{{secure_url}}'          => $baseUrl . '/ucp/settings',
        '{{view_in_browser_url}}' => $viewInBrowserUrl,
    ];
    $body = strtr($htmlContent, $replacements);

    // PHPMailer config identic cu register.php
    $to = $toEmail;
    $subject = 'Security alert: Failed sign-in attempt';

    $mailHost = getenv('MAIL_HOST');
    $mailUser = getenv('MAIL_USERNAME');

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = $mailHost;
        $mail->SMTPAuth   = true;
        $mail->Username   = $mailUser;
        $mail->Password   = getenv('MAIL_PASSWORD');
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        // Add SSL options for better compatibility
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        $mail->setFrom($mailUser, 'Fibonacci Romania');
        $mail->addReplyTo('office@fibonacci-olympiad.ro', 'Information');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        if ($mail->send()) {
            // logEmails(to, subject, html, userId, emailUID)
            logEmails($to, $subject, $body, $userId, $emailUID);
        }
    } catch (Exception $e) {
        // Silent fail
    }
}

/* ---------- request ---------- */

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    respond(405, 'Method Not Allowed');
}

$email    = strtolower(post('email'));
$password = post('password');
$remember = filter_var(post('remember'), FILTER_VALIDATE_BOOLEAN);

if ($email === '' || $password === '') {
    respond(400, 'Please enter your email and password.');
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    respond(400, 'Invalid email format.');
}

$stmt = $conn->prepare("SELECT id, password_hash, is_email_verified, full_name FROM users WHERE email = ? LIMIT 1");
if (!$stmt) {
    respond(500, 'Database error: ' . $conn->error);
}
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $stmt->close();
    respond(401, 'Invalid email or password.', ['error_code' => 'INVALID_CREDENTIALS']);
}

$user = $result->fetch_assoc();
$stmt->close();

if ((int)$user['is_email_verified'] !== 1) {
    respond(403, 'Your email address is not verified. Please check your inbox.');
}

if (!password_verify($password, $user['password_hash'])) {
    // Dacă vrei, poți adăuga un rate-limit server-side aici (ex. per email+IP la 10 min)
    send_login_failed_email((int)$user['id'], $email, (string)($user['full_name'] ?? ''));
    respond(401, 'Invalid email or password.', ['error_code' => 'WRONG_PASSWORD']);
}

/* Success */
session_regenerate_id(true);
$_SESSION[getenv('SESSION_USER_ID')] = (int)$user['id'];

if ($remember) {
    $token     = bin2hex(random_bytes(32));
    $tokenHash = hash('sha256', $token);
    $expiry    = date('Y-m-d H:i:s', time() + (30 * 24 * 60 * 60)); // 30 zile

    $ins = $conn->prepare("INSERT INTO user_remember_tokens (user_id, token_hash, expires_at)
                           VALUES (?, ?, ?)
                           ON DUPLICATE KEY UPDATE token_hash = VALUES(token_hash), expires_at = VALUES(expires_at)");
    $ins->bind_param('iss', $user['id'], $tokenHash, $expiry);
    $ins->execute();
    $ins->close();

    setcookie(
        getenv('REMEMBER_ME_COOKIE'),
        $user['id'] . ':' . $token,
        [
            'expires'  => time() + (30 * 24 * 60 * 60),
            'path'     => '/',
            'secure'   => true,
            'httponly' => true,
            'samesite' => 'Strict'
        ]
    );
}

respond(200, 'Login successful.', ['redirect' => '/ucp/dashboard']);
