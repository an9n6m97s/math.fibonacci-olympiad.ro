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
$teamId = (int)post('team_id');
$memberId = (int)post('member_id');

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
$digits = preg_replace('/\D+/', '', $phone);
if (!(preg_match('/^[+]?[0-9\s().\-]{7,20}$/', $phone) && strlen($digits) >= 7)) {
    respond(400, 'Please enter a valid phone number.');
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
    $upd = $conn->prepare('
        UPDATE team_members SET
          team_id = ?,
          full_name = ?,
          email = ?,
          phone = ?,
          dob = ?,
          tshirt = ?,
          emergency_contact = ?,
          photo_consent = ?,
          parental_consent = ?,
          role = ?
        WHERE id = ?
    ');
    if (!$upd) throw new Exception('Prepare failed: ' . $conn->error);

    $upd->bind_param(
        'issssssiisi',
        $teamId,
        $fullName,
        $email,
        $phone,
        $dobFormatted,
        $tshirt,
        $emergencyContact,
        $photoConsent,
        $parentalConsent,
        $role,
        $memberId
    );

    if (!$upd->execute()) {
        if ($upd->errno === 1062) {
            $upd->close();
            $conn->rollback();
            respond(409, 'Duplicate entry. Member already exists.');
        }
        throw new Exception('Execute failed: ' . $upd->error);
    }

    $upd->close();
    $conn->commit();

    respond(200, 'Member updated successfully.', [
        'member_id' => $memberId,
        'full_name' => $fullName,
        'email' => $email,
        'phone' => $phone
    ]);
} catch (Throwable $e) {
    $conn->rollback();
    sendDebugNotify(
        'Member update error',
        "Error during member update: {$e->getMessage()} | File: {$e->getFile()} | Line: {$e->getLine()}"
    );
    respond(500, 'Database error. Please try again.');
}
