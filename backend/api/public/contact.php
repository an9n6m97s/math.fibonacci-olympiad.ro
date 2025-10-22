<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/backend/headerBackend.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['message' => 'Method not allowed']);
    exit;
}

$required = ['name', 'email', 'phone', 'type', 'message'];
foreach ($required as $field) {
    if (empty($_POST[$field])) {
        http_response_code(400);
        echo json_encode(['message' => "Missing field: $field"]);
        exit;
    }
}

$name    = htmlspecialchars($_POST['name']);
$email   = htmlspecialchars($_POST['email']);
$phone   = htmlspecialchars($_POST['phone']);
$type    = htmlspecialchars($_POST['type']);
$message = htmlspecialchars($_POST['message']);

$toMails = [
    'cyberbrainsrobotics@gmail.com',
    getenv('MAIL_USERNAME'),
    'info@essenbyte.com'
];

$body = <<<BODY
<b>New contact form submission:</b><br><br>
<b>Name:</b> $name<br>
<b>Email:</b> $email<br>
<b>Phone:</b> $phone<br>
<b>Type:</b> $type<br>
<b>Message:</b><br>$message
BODY;

try {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = getenv('MAIL_HOST');
    $mail->SMTPAuth   = true;
    $mail->Username   = getenv('MAIL_USERNAME');
    $mail->Password   = getenv('MAIL_PASSWORD'); // 🔒 move to env in production
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;

    $mail->setFrom(getenv('MAIL_USERNAME'), 'Contact Form');
    $mail->addReplyTo($email, $name);
    foreach ($toMails as $to) {
        $mail->addAddress($to);
    }

    $mail->isHTML(true);
    $mail->Subject = 'Contact Form Submission';
    $mail->Body    = $body;

    $mail->send();

    echo json_encode(['success' => true, 'message' => 'Email sent']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Mail error: ' . $mail->ErrorInfo]);
}
