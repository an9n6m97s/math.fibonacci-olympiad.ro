<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/backend/headerBackend.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond_error(405, 'Metodă neacceptată.');
}

$username = trim(post_str('username'));
$password = post_str('password');

if ($username === '' || $password === '') {
    respond_error(422, 'Introdu utilizatorul și parola.');
}

$stmt = $conn->prepare('SELECT id, password_hash, is_active FROM admins WHERE username = ? LIMIT 1');
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result ? $result->fetch_assoc() : null;
$stmt->close();

if (!$admin || !$admin['is_active']) {
    respond_error(401, 'Credențiale invalide.');
}

if (!password_verify($password, $admin['password_hash'])) {
    respond_error(401, 'Credențiale invalide.');
}

setAdminSession((int)$admin['id']);

respond_ok('Autentificare reușită.', [
    'redirect' => '/admin/dashboard',
]);
