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

try {
    $stmt = $conn->prepare('DELETE FROM participants WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();

    respond_ok('Participant șters.');
} catch (Throwable $exception) {
    respond_error(500, 'Nu am putut șterge participantul.', [
        'error' => $exception->getMessage(),
    ]);
}
