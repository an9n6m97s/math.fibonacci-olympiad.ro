<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/backend/headerBackend.php';

if (!isAdminLoggedIn()) {
    respond_error(401, 'Autentificare necesară.');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond_error(405, 'Metodă neacceptată.');
}

$id = (int)post_int('id');
if ($id <= 0) {
    respond_error(422, 'ID invalid.');
}

$firstName = trim(post_str('first_name'));
$lastName  = trim(post_str('last_name'));
$email     = filter_var(post_str('email'), FILTER_VALIDATE_EMAIL);
$phone     = trim(post_str('phone'));
$city      = trim(post_str('city'));
$school    = trim(post_str('school'));
$grade     = trim(post_str('grade'));

if (!$firstName || !$lastName || !$email || !$phone || !$city || !$school || !$grade) {
    respond_error(422, 'Completează toate câmpurile.');
}

try {
    $stmt = $conn->prepare('UPDATE participants SET first_name = ?, last_name = ?, email = ?, phone = ?, city = ?, school = ?, class = ? WHERE id = ?');
    $stmt->bind_param('sssssssi', $firstName, $lastName, $email, $phone, $city, $school, $grade, $id);
    $stmt->execute();
    $stmt->close();

    respond_ok('Participant actualizat.');
} catch (mysqli_sql_exception $exception) {
    if ((int)$exception->getCode() === 1062) {
        respond_error(409, 'Există deja un participant cu acest email.');
    }
    respond_error(500, 'Nu am putut actualiza participantul.', [
        'error' => $exception->getMessage(),
    ]);
}
