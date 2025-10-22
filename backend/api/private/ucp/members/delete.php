<?php
/*
  Member deletion endpoint (mysqli)
  - Uses $conn from /backend/headerBackend.php
  - Requires team_id and member_id (POST)
  - Checks authentication
  - Deletes member from team_members table
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
$memberId = (int)post('member_id');

if ($teamId <= 0 || $memberId <= 0) {
    respond(400, 'Missing or invalid team/member ID.');
}

if (!($conn instanceof mysqli)) {
    respond(500, 'Database connection not available.');
}

$conn->begin_transaction();

try {
    // Optional: check if member exists and belongs to team
    $check = $conn->prepare('SELECT id FROM team_members WHERE id = ? AND team_id = ?');
    if (!$check) throw new Exception('Prepare failed: ' . $conn->error);
    $check->bind_param('ii', $memberId, $teamId);
    $check->execute();
    $check->store_result();
    if ($check->num_rows === 0) {
        $check->close();
        $conn->rollback();
        respond(404, 'Member not found in this team.');
    }
    $check->close();

    // Delete member
    $del = $conn->prepare('DELETE FROM team_members WHERE id = ? AND team_id = ?');
    if (!$del) throw new Exception('Prepare failed: ' . $conn->error);
    $del->bind_param('ii', $memberId, $teamId);
    if (!$del->execute()) {
        throw new Exception('Execute failed: ' . $del->error);
    }
    $del->close();

    $conn->commit();

    respond(200, 'Member deleted successfully.', [
        'member_id' => $memberId,
        'team_id' => $teamId
    ]);
} catch (Throwable $e) {
    $conn->rollback();
    sendDebugNotify(
        'Member delete error',
        "Error during member delete: {$e->getMessage()} | File: {$e->getFile()} | Line: {$e->getLine()}"
    );
    respond(500, 'Database error. Please try again.');
}
