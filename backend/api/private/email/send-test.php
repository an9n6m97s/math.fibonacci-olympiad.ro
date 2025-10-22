<?php
// Send test email using template
require_once $_SERVER['DOCUMENT_ROOT'] . '/backend/headerBackend.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        exit;
    }

    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid JSON input']);
        exit;
    }

    $templateContent = $input['template_content'] ?? '';
    $testEmail = $input['test_email'] ?? '';

    if (empty($templateContent)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Template content is required']);
        exit;
    }

    if (empty($testEmail) || !filter_var($testEmail, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Valid test email is required']);
        exit;
    }

    // Test data for email
    $testData = [
        '{{subject}}' => 'Test Email Template - ' . date('Y-m-d H:i:s'),
        '{{title}}' => 'Email Template Test',
        '{{content}}' => 'This is a test email to verify the email template functionality. The template was sent from the Email Template Editor.',
        '{{recipient_name}}' => 'Template Tester',
        '{{sender_name}}' => 'Relativity Email System',
        '{{website_url}}' => 'https://fibonacci-olympiad.ro',
        '{{unsubscribe_url}}' => 'https://fibonacci-olympiad.ro/unsubscribe'
    ];

    // Replace placeholders
    $emailBody = $templateContent;
    foreach ($testData as $placeholder => $value) {
        $emailBody = str_replace($placeholder, $value, $emailBody);
    }

    $subject = $testData['{{subject}}'];

    // Configure PHPMailer
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = getenv('MAIL_HOST');
    $mail->SMTPAuth = true;
    $mail->Username = getenv('MAIL_USERNAME');
    $mail->Password = getenv('MAIL_PASSWORD');
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;

    // Add SSL options for better compatibility
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );

    // Email setup
    $mail->setFrom(getenv('MAIL_USERNAME'), 'Email Template Editor');
    $mail->addAddress($testEmail);
    $mail->addReplyTo('office@fibonacci-olympiad.ro', 'Relativity Team');

    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $emailBody;
    $mail->AltBody = strip_tags($emailBody); // Plain text version

    // Send email
    if ($mail->send()) {
        echo json_encode([
            'success' => true,
            'message' => 'Test email sent successfully',
            'recipient' => $testEmail,
            'subject' => $subject,
            'timestamp' => time()
        ]);
    } else {
        throw new Exception('PHPMailer failed to send: ' . $mail->ErrorInfo);
    }
} catch (Exception $e) {
    error_log("Error in send-test.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to send test email: ' . $e->getMessage()
    ]);
}
