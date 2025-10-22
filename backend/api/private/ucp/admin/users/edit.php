<?php
/*
  Edit user endpoint (mysqli)
  - Uses $conn from /backend/headerBackend.php
  - Server-side validation (name/email/phone/org fields)
  - Optional password update with Argon2id hashing (bcrypt fallback)
  - Transactional updates
*/

header('Content-Type: application/json; charset=utf-8');
require_once $_SERVER['DOCUMENT_ROOT'] . '/backend/headerBackend.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    respond(405, 'Method Not Allowed');
}

/* Gather + validate */
$userId   = post('user_id');
$fullName = post('fullName');
$email    = strtolower(post('email'));
$password = post('password');
$confirm  = post('confirm-password');
$phone    = post('phone');
$orgType  = post('org_type');
$orgName  = post('org_name');
$country  = post('country');
$city     = post('city');

if ($userId === '' || !is_numeric($userId)) {
    respond(400, 'Invalid user ID.');
}

if (
    $fullName === '' || $email === '' || $phone === '' ||
    $orgType === '' || $orgName === '' ||
    $country === '' || $city === ''
) {
    respond(400, 'Please fill all required fields.');
}

$namePattern = "/^[A-Za-zÀ-ÖØ-öø-ÿ'’.\\-]+(?:\\s+[A-Za-zÀ-ÖØ-öø-ÿ'’.\\-]+)+$/u";
if (!preg_match($namePattern, $fullName) || mb_strlen($fullName) < 3) {
    respond(400, 'Please enter your full name (first and last).');
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    respond(400, 'Please enter a valid email address.');
}
$phoneDigits = preg_replace('/\D+/', '', $phone);
if (!(preg_match('/^[+]?[0-9\s().\-]{7,20}$/', $phone) && strlen($phoneDigits) >= 7)) {
    respond(400, 'Please enter a valid phone number.');
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

/* Optional password validation */
$passwordHash = null;
if ($password !== '' || $confirm !== '') {
    if ($password !== $confirm) {
        respond(400, 'Passwords do not match.');
    }
    $pwdOk = (bool)preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&.,;:!#()_\-\+\[\]{}]).{8,}$/', $password);
    if (!$pwdOk) {
        respond(400, 'Password must be at least 8 characters, with lowercase, uppercase, number, and special character.');
    }
    if (defined('PASSWORD_ARGON2ID')) {
        $passwordHash = password_hash($password, PASSWORD_ARGON2ID, ['time_cost' => 4, 'memory_cost' => 1 << 17, 'threads' => 2]);
    } else {
        $passwordHash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }
    if ($passwordHash === false) {
        respond(500, 'Failed to hash password.');
    }
}

/* Transaction */
if (!($conn instanceof mysqli)) {
    respond(500, 'Database connection not available.');
}
$conn->begin_transaction();

try {
    /* Update user */
    $query = 'UPDATE users SET email = ?, full_name = ?, phone = ?, org_type = ?, org_name = ?, country = ?, city = ?';
    $params = [$email, $fullName, $phone, $orgType, $orgName, $country, $city];
    $types = 'sssssss';

    if ($passwordHash !== null) {
        $query .= ', password_hash = ?';
        $params[] = $passwordHash;
        $types .= 's';
    }

    $query .= ' WHERE id = ?';
    $params[] = $userId;
    $types .= 'i';

    $stmt = $conn->prepare($query);
    if (!$stmt) throw new Exception('Prepare failed: ' . $conn->error);

    $stmt->bind_param($types, ...$params);
    if (!$stmt->execute()) {
        throw new Exception('Execute failed: ' . $stmt->error);
    }

    $stmt->close();
    $conn->commit();

    respond(200, 'User updated successfully.');
} catch (Throwable $e) {
    $conn->rollback();
    sendDebugNotify(
        'Edit user error',
        "Error during user update: {$e->getMessage()} | File: {$e->getFile()} | Line: {$e->getLine()}"
    );
    respond(500, 'Database error. Please try again.');
}
