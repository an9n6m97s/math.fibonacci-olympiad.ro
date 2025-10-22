<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/backend/headerBackend.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['success' => false, 'message' => 'Method not allowed']);
  exit;
}

try {
  $action = $_POST['action'] ?? '';
  $subject = $_POST['subject'] ?? '';
  $preheader = $_POST['preheader'] ?? '';
  $content = $_POST['content'] ?? '';
  $recipient_type = $_POST['recipient_type'] ?? 'all';
  $specific_recipients = $_POST['specific_users'] ?? [];

  if (empty($subject) || empty($content)) {
    echo json_encode(['success' => false, 'message' => 'Subject and content are required.']);
    exit;
  }

  // Get current user
  $currentUser = getUserData();
  if (!$currentUser) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
  }

  // Process email sending
  if ($action === 'send') {
    $emailHtml = buildEmailTemplate($subject, $preheader, $content);
    $recipients = getEmailRecipients($recipient_type, $specific_recipients);

    if (empty($recipients)) {
      echo json_encode(['success' => false, 'message' => 'No recipients found.']);
      exit;
    }

    $sent = 0;
    $failed = 0;
    $sentEmails = [];

    foreach ($recipients as $recipient) {
      try {
        // Personalize email for each recipient
        $personalizedHtml = str_replace('{{recipient_name}}', htmlspecialchars($recipient['full_name'] ?? $recipient['email']), $emailHtml);

        if (sendEmailWithTemplate($recipient['email'], $subject, $personalizedHtml)) {
          $sent++;
          $sentEmails[] = [
            'recipient' => $recipient['email'],
            'name' => $recipient['full_name'] ?? '',
            'timestamp' => time()
          ];

          // Log email
          logEmail($recipient['email'], $subject, $personalizedHtml, $currentUser['id'] ?? 0);
        } else {
          $failed++;
        }
      } catch (Exception $e) {
        error_log("Failed to send email to {$recipient['email']}: " . $e->getMessage());
        $failed++;
      }
    }

    echo json_encode([
      'success' => true,
      'message' => "Email sent to $sent recipients" . ($failed > 0 ? ", $failed failed" : ""),
      'sent' => $sent,
      'failed' => $failed,
      'recipients' => $sentEmails
    ]);
  } else if ($action === 'preview') {
    $emailHtml = buildEmailTemplate($subject, $preheader, $content);
    $personalizedHtml = str_replace('{{recipient_name}}', 'Preview User', $emailHtml);

    echo json_encode([
      'success' => true,
      'html' => $personalizedHtml,
      'subject' => $subject
    ]);
  } else {
    echo json_encode(['success' => false, 'message' => 'Invalid action.']);
  }
} catch (Exception $e) {
  error_log("Error in email compose: " . $e->getMessage());
  http_response_code(500);
  echo json_encode(['success' => false, 'message' => 'Internal server error: ' . $e->getMessage()]);
}

function buildEmailTemplate($subject, $preheader, $content)
{
  // FOLOSEȘTE ACEEAȘI LOGICĂ CA settings-template.php PENTRU TEMPLATE COMPLET
  // Apoi înlocuiește doar {{main_content_html}} cu conținutul din TinyMCE

  // Încarcă template-ul cu toate datele din $settings prin settings-template.php
  $settingsTemplateUrl = $_SERVER['DOCUMENT_ROOT'] . '/backend/api/private/email/settings-template.php';

  // Simulează cererea GET pentru a obține template-ul complet
  $_GET = []; // Clear GET data
  $_POST = []; // Clear POST data

  ob_start();
  include $settingsTemplateUrl;
  $response = ob_get_clean();

  $templateData = json_decode($response, true);

  if (!$templateData || !$templateData['success']) {
    throw new Exception('Failed to load settings template');
  }

  $template = $templateData['content'];

  // ACUM înlocuiește doar {{main_content_html}} cu conținutul din TinyMCE
  $template = str_replace('{{main_content_html}}', $content, $template);

  // Înlocuiește și recipient_name pentru personalizare
  // (va fi înlocuit per recipient în bucla de trimitere)

  return $template;
}

function getEmailRecipients($recipient_type, $specific_recipients)
{
  switch ($recipient_type) {
    case 'managers':
      return getAllManagers();
    case 'members':
      return getAllMembers();
    case 'specific':
      if (empty($specific_recipients)) {
        return [];
      }
      // Get users by email addresses
      global $conn;
      $placeholders = implode(',', array_fill(0, count($specific_recipients), '?'));
      $query = "SELECT id, email, full_name, created_at, 'user' as role 
                      FROM users WHERE email IN ($placeholders)";
      $stmt = mysqli_prepare($conn, $query);
      if ($stmt) {
        mysqli_stmt_bind_param($stmt, str_repeat('s', count($specific_recipients)), ...$specific_recipients);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $users = [];
        while ($row = mysqli_fetch_assoc($result)) {
          $users[] = $row;
        }
        mysqli_stmt_close($stmt);
        return $users;
      }
      return [];
    case 'all':
    default:
      return getAllUsers();
  }
}

function sendEmailWithTemplate($to, $subject, $htmlContent)
{
  global $mail;

  try {
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);

    // SMTP configuration
    $mail->isSMTP();
    $mail->Host = getenv('MAIL_HOST');
    $mail->SMTPAuth = true;
    $mail->Username = getenv('MAIL_USERNAME');
    $mail->Password = getenv('MAIL_PASSWORD');
    $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
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
    $mail->setFrom(getenv('MAIL_USERNAME'), 'Fibonacci 2026 - Organizing Team');
    $mail->addAddress($to);
    $mail->addReplyTo('office@fibonacci-olympiad.ro', 'Relativity Team');

    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $htmlContent;
    $mail->AltBody = strip_tags($htmlContent); // Plain text version

    return $mail->send();
  } catch (Exception $e) {
    error_log("Email sending error: " . $e->getMessage());
    return false;
  }
}
