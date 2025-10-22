<?php
/*
  Reset Password endpoint (mysqli)
  - Uses $conn from /backend/headerBackend.php
  - Accepts POST { token, password, confirm }
  - Validates token (sha256 match) and expiry
  - Updates user's password (Argon2id / bcrypt fallback)
  - Deletes token (single-use)
*/

header('Content-Type: application/json; charset=utf-8');
require_once $_SERVER['DOCUMENT_ROOT'] . '/backend/headerBackend.php';

/* ---------- Helpers ---------- */

/*
  Validate password complexity (same policy as register.php)
*/
function is_password_strong(string $pwd): bool
{
    return (bool)preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&.,;:!#()_\-\+\[\]{}]).{8,}$/', $pwd);
}

/*
  Hash password using Argon2id if available, else bcrypt
*/
function make_password_hash(string $pwd)
{
    if (defined('PASSWORD_ARGON2ID')) {
        return password_hash($pwd, PASSWORD_ARGON2ID, ['time_cost' => 4, 'memory_cost' => 1 << 17, 'threads' => 2]);
    }
    return password_hash($pwd, PASSWORD_BCRYPT, ['cost' => 12]);
}

/* ---------- Guard ---------- */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    respond(405, 'Method Not Allowed');
}

/* ---------- Input ---------- */
$token    = post('token');
$password = post('new_password');
$confirm  = post('new_password');

if ($token === '' || $password === '' || $confirm === '') {
    respond(400, 'Please complete all required fields.');
}
if ($password !== $confirm) {
    respond(400, 'Passwords do not match.');
}
if (!is_password_strong($password)) {
    respond(400, 'Password must be at least 8 characters, with lowercase, uppercase, number, and special character.');
}

if (!($conn instanceof mysqli)) {
    respond(500, 'Database connection not available.');
}

/* ---------- Verify token ---------- */
$tokenHash = hash('sha256', $token);

$stmt = $conn->prepare('
    SELECT pr.id, pr.user_id, pr.expires_at, u.email, u.full_name
    FROM password_resets pr
    JOIN users u ON u.id = pr.user_id
    WHERE pr.token_hash = ?
    LIMIT 1
');
if (!$stmt) {
    respond(500, 'Database error.');
}
$stmt->bind_param('s', $tokenHash);
$stmt->execute();
$stmt->bind_result($prId, $userId, $expiresAt, $email, $fullName);
$found = $stmt->fetch();
$stmt->close();

if (!$found) {
    respond(400, 'Invalid or expired token.');
}

$now = new DateTimeImmutable('now');
$exp = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $expiresAt);
if (!$exp || $now > $exp) {
    // token expirat: îl ștergem totuși, ca igienă
    $del = $conn->prepare('DELETE FROM password_resets WHERE id = ?');
    if ($del) {
        $del->bind_param('i', $prId);
        $del->execute();
        $del->close();
    }
    respond(400, 'Invalid or expired token.');
}

/* ---------- Update password & delete token ---------- */
$conn->begin_transaction();
try {
    $hash = make_password_hash($password);
    if ($hash === false) {
        throw new Exception('Failed to hash password.');
    }

    $up = $conn->prepare('UPDATE users SET password_hash = ? WHERE id = ? LIMIT 1');
    if (!$up) throw new Exception('Prepare update failed: ' . $conn->error);
    $up->bind_param('si', $hash, $userId);
    if (!$up->execute() || $up->affected_rows < 1) {
        $up->close();
        throw new Exception('Password update failed.');
    }
    $up->close();

    $del = $conn->prepare('DELETE FROM password_resets WHERE id = ?');
    if ($del) {
        $del->bind_param('i', $prId);
        $del->execute();
        $del->close();
    }

    $conn->commit();

    respond(200, 'Your password has been updated successfully. You can now sign in.');
} catch (Throwable $e) {
    $conn->rollback();
    sendDebugNotify(
        'Reset password error',
        "Error: {$e->getMessage()} | File: {$e->getFile()} | Line: {$e->getLine()} | user_id: {$userId}"
    );
    respond(500, 'Could not update password. Please try again.');
}
