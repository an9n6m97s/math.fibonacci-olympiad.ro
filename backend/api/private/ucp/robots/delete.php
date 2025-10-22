<?php
/*
  Robot deletion endpoint (mysqli)
  - Uses $conn from /backend/headerBackend.php
  - Requires team_id and robot_id (POST)
  - Checks authentication
  - Deletes robot from robots table
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

/* Gather + validate */
$teamId = (int)post('team_id');
$robotId = (int)post('robot_id');

if ($teamId <= 0 || $robotId <= 0) {
    respond(400, 'Missing or invalid team/robot ID.');
}

if (!($conn instanceof mysqli)) {
    respond(500, 'Database connection not available.');
}

$conn->begin_transaction();

try {
    // Check if robot exists and belongs to team
    $check = $conn->prepare('SELECT id FROM robots WHERE id = ? AND team_id = ?');
    if (!$check) throw new Exception('Prepare failed: ' . $conn->error);
    $check->bind_param('ii', $robotId, $teamId);
    $check->execute();
    $check->store_result();
    if ($check->num_rows === 0) {
        $check->close();
        $conn->rollback();
        respond(404, 'Robot not found in this team.');
    }
    $check->close();

    // Delete robot
    $del = $conn->prepare('DELETE FROM robots WHERE id = ? AND team_id = ?');
    if (!$del) throw new Exception('Prepare failed: ' . $conn->error);
    $del->bind_param('ii', $robotId, $teamId);
    if (!$del->execute()) {
        throw new Exception('Execute failed: ' . $del->error);
    }
    $del->close();

    $conn->commit();

    respond(200, 'Robot deleted successfully.', [
        'robot_id' => $robotId,
        'team_id' => $teamId
    ]);
} catch (Throwable $e) {
    $conn->rollback();
    sendDebugNotify(
        'Robot delete error',
        "Error during robot delete: {$e->getMessage()} | File: {$e->getFile()} | Line: {$e->getLine()}"
    );
    respond(500, 'Database error. Please try again.');
}
