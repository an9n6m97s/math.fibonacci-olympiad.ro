<?php
/*
  Member creation endpoint (mysqli)
  - Uses $conn from /backend/headerBackend.php
  - Validates all fields from the form
  - Checks for duplicate email/phone using existEmailInDatabase/existPhoneInDatabase
  - Inserts into team_members table
*/

header('Content-Type: application/json; charset=utf-8');
require_once $_SERVER['DOCUMENT_ROOT'] . '/backend/headerBackend.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    respond(405, 'Method Not Allowed');
}

/* Auth */
$sessionKey = getenv('SESSION_USER_ID') ?: 'uid';
$userId = (int)($_SESSION[$sessionKey] ?? 0);
if ($userId <= 0) {
    respond(401, 'Not authenticated.');
}


/* Gather + normalize */
$fullName = trim((string)post('member_fullname'));
$email = strtolower(trim((string)post('member_email')));
$phone = trim((string)post('member_phone'));
$dob = trim((string)post('member_dob'));
$tshirt = trim((string)post('member_tshirt'));
$emergencyContact = trim((string)post('member_emergency_phone'));
$role = trim((string)post('member_role'));
$photoConsent = isset($_POST['photo_consent']) ? 1 : 1; // always 1 if checked
$parentalConsent = isset($_POST['parental_consent']) ? 1 : 0;
$teamId = (int)post('team_id');

/* Required checks */
if (
    $fullName === '' || $email === '' || $phone === '' ||
    $dob === '' || $tshirt === '' || $emergencyContact === '' || $role === '' || $teamId <= 0
) {
    respond(400, 'Please fill all required fields.');
}

/* Formats */
if (mb_strlen($fullName) < 2 || mb_strlen($fullName) > 100) {
    respond(400, 'Name must be between 2 and 100 characters.');
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    respond(400, 'Please enter a valid email address.');
}
if (existEmailInDatabase($email)) {
    respond(409, 'This email address is already registered.');
}
$digits = preg_replace('/\D+/', '', $phone);
if (!(preg_match('/^[+]?[0-9\s().\-]{7,20}$/', $phone) && strlen($digits) >= 7)) {
    respond(400, 'Please enter a valid phone number.');
}
if (existPhoneInDatabase($phone)) {
    respond(409, 'This phone number is already registered.');
}
$emDigits = preg_replace('/\D+/', '', $emergencyContact);
if (!(preg_match('/^[+]?[0-9\s().\-]{7,20}$/', $emergencyContact) && strlen($emDigits) >= 7)) {
    respond(400, 'Please enter a valid emergency phone number.');
}
if (!in_array($tshirt, ['XS', 'S', 'M', 'L', 'XL', 'XXL'], true)) {
    respond(400, 'Please select a valid T-shirt size.');
}
if (!in_array($role, ['Leader', 'Member'], true)) {
    respond(400, 'Please select a valid member role.');
}

/* Date of birth: must not be in the future, must be valid date */
$dobParts = preg_split('/[\/\-\.]/', $dob);
if (count($dobParts) === 3) {
    // Accept d/m/y or y-m-d
    if (strlen($dobParts[2]) === 4) {
        // d/m/y
        $dobFormatted = sprintf('%04d-%02d-%02d', (int)$dobParts[2], (int)$dobParts[1], (int)$dobParts[0]);
    } else {
        // y-m-d
        $dobFormatted = sprintf('%04d-%02d-%02d', (int)$dobParts[0], (int)$dobParts[1], (int)$dobParts[2]);
    }
} else {
    $dobFormatted = $dob;
}
$dobDate = strtotime($dobFormatted);
if (!$dobDate) {
    respond(400, 'Please enter a valid date of birth.');
}
if ($dobDate > strtotime('today')) {
    respond(400, 'Date of birth cannot be in the future.');
}

/* DB */
if (!($conn instanceof mysqli)) {
    respond(500, 'Database connection not available.');
}

$conn->begin_transaction();

try {
    $ins = $conn->prepare('
        INSERT INTO team_members
          (team_id, full_name, email, phone, dob, tshirt, emergency_contact, photo_consent, parental_consent, role)
        VALUES
          (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ');
    if (!$ins) throw new Exception('Prepare failed: ' . $conn->error);

    $ins->bind_param(
        'issssssiis',
        $teamId,
        $fullName,
        $email,
        $phone,
        $dobFormatted,
        $tshirt,
        $emergencyContact,
        $photoConsent,
        $parentalConsent,
        $role
    );

    if (!$ins->execute()) {
        if ($ins->errno === 1062) {
            $ins->close();
            $conn->rollback();
            respond(409, 'Duplicate entry. Member already exists.');
        }
        throw new Exception('Execute failed: ' . $ins->error);
    }

    $memberId = (int)$conn->insert_id;
    $ins->close();
    $conn->commit();

    // Email notification to member
    $emailUID = generateEmailUID();
    $viewInBrowserUrl = 'https://fibonacci-olympiad.ro/email?view=' . $emailUID;
    $htmlContent = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/assets/email/new-member.html');
    $htmlContent = str_replace(
        [
            '{{full_name}}',
            '{{email}}',
            '{{phone}}',
            '{{dob}}',
            '{{tshirt}}',
            '{{role}}',
            '{{view_in_browser_url}}'
        ],
        [
            $fullName,
            $email,
            $phone,
            $dobFormatted,
            $tshirt,
            $role,
            $viewInBrowserUrl
        ],
        $htmlContent
    );

    $to = $email;
    $subject = 'You’ve been added to a team - Fibonacci Romania';

    $mail = new PHPMailer(true);
    $mail->CharSet = 'UTF-8';
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
            logEmails($to, $subject, $htmlContent, 0, $emailUID);
        } else {
            sendDebugNotify(
                'Email sending failed',
                "Failed to send new member email to $to: {$mail->ErrorInfo}"
            );
        }
    } catch (Exception $e) {
        sendDebugNotify(
            'Email sending error',
            "Failed to send new member email to $to: {$mail->ErrorInfo}"
        );
    }

    respond(201, 'Member created successfully.', [
        'member_id' => $memberId,
        'full_name' => $fullName,
        'email' => $email,
        'phone' => $phone
    ]);
} catch (Throwable $e) {
    $conn->rollback();
    sendDebugNotify(
        'Member create error',
        "Error during member create: {$e->getMessage()} | File: {$e->getFile()} | Line: {$e->getLine()}"
    );
    respond(500, 'Database error. Please try again.');
}
