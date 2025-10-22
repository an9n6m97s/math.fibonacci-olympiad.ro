<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/backend/headerBackend.php';

if (!isAdminLoggedIn()) {
    respond_error(401, 'Autentificare necesară.');
}

$query = 'SELECT id, first_name, last_name, email, phone, city, school, class AS grade_level, created_at, updated_at FROM participants ORDER BY created_at DESC';
$result = $conn->query($query);
$participants = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $participants[] = $row;
    }
    $result->free();
}

respond_ok('Lista participanților', [
    'participants' => $participants,
]);
