<?php
/*
    Changelog deletion endpoint (mysqli)
    - Uses $conn from /backend/headerBackend.php
    - Requires id (GET)
    - Checks authentication
    - Deletes record from changelogs table
*/

header('Content-Type: application/json; charset=utf-8');
require_once $_SERVER['DOCUMENT_ROOT'] . '/backend/headerBackend.php';


/* Gather + validate */
$id = (int)(filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?: 0);
if ($id <= 0) {
    respond(400, 'Missing or invalid id.');
}

if (!($conn instanceof mysqli)) {
    respond(500, 'Database connection not available.');
}

$conn->begin_transaction();

try {
    // Check if changelog exists
    $check = $conn->prepare('SELECT id FROM changelogs WHERE id = ?');
    if (!$check) throw new Exception('Prepare failed: ' . $conn->error);
    $check->bind_param('i', $id);
    $check->execute();
    $check->store_result();
    if ($check->num_rows === 0) {
        $check->close();
        $conn->rollback();
        respond(404, 'Changelog entry not found.');
    }
    $check->close();

    // Delete changelog entry
    $del = $conn->prepare('DELETE FROM changelogs WHERE id = ?');
    if (!$del) throw new Exception('Prepare failed: ' . $conn->error);
    $del->bind_param('i', $id);
    if (!$del->execute()) {
        throw new Exception('Execute failed: ' . $del->error);
    }
    $deleted = $del->affected_rows;
    $del->close();

    $conn->commit();

    respond(200, 'Changelog entry deleted successfully.', [
        'id' => $id,
        'deleted' => $deleted
    ]);
} catch (Throwable $e) {
    $conn->rollback();
    sendDebugNotify(
        'Changelog delete error',
        "Error during changelog delete: {$e->getMessage()} | File: {$e->getFile()} | Line: {$e->getLine()}"
    );
    respond(500, 'Database error. Please try again.');
}
