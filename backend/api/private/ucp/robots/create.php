<?php
/*
  Robot create/update endpoint for your current robots schema
  - Uses $conn from /backend/headerBackend.php
  - INSERT if robot_id <= 0, UPDATE if robot_id > 0
*/

header('Content-Type: application/json; charset=utf-8');
require_once $_SERVER['DOCUMENT_ROOT'] . '/backend/headerBackend.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    respond(405, 'Method Not Allowed');
}

$sessionKey = getenv('SESSION_USER_ID') ?: 'uid';
$userId = (int)($_SESSION[$sessionKey] ?? 0);
if ($userId <= 0) {
    respond(401, 'Not authenticated.');
}

$robotId     = (int)post('robot_id');              // 0 or missing => create
$robotName   = trim((string)post('robot_name'));
$categories  = isset($_POST['robot_category']) ? $_POST['robot_category'] : [];
$operatorId  = (int)post('robot_operator');        // operator is INT in your table
$members     = isset($_POST['robot_members']) ? $_POST['robot_members'] : [];
$teamId      = (int)post('team_id');
$safetyAck   = 1;                                  // you seem to force acknowledge

// Basic validations
if ($robotName === '' || empty($categories) || $operatorId <= 0 || empty($members) || $teamId <= 0) {
    respond(400, 'Please fill all required fields.');
}
if (mb_strlen($robotName) < 2 || mb_strlen($robotName) > 120) {
    respond(400, 'Robot name must be between 2 and 120 characters.');
}

// category: at least one
if (is_array($categories)) {
    $categories = array_values(array_filter(array_map('strval', $categories), fn($c) => trim($c) !== ''));
}
if (count($categories) < 1) {
    respond(400, 'Please select at least one category.');
}
$catSlug = implode(',', $categories); // salvează ca listă separată prin virgulă

// members -> comma list
$memberIds = array_values(array_filter(array_map('intval', (array)$members), fn($v) => $v > 0));
$memberIdsStr = implode(',', $memberIds);
// heads-up: member_ids is VARCHAR(50). Truncate to avoid DB errors if you insist on stuffing too many ids.
if (strlen($memberIdsStr) > 50) {
    respond(400, 'Too many members selected for the current schema (member_ids max length 50).');
}

if (!($conn instanceof mysqli)) {
    respond(500, 'Database connection not available.');
}

/* Operator limit (max 4 robots per operator)
   For update exclude current id; for create count all. */
if ($robotId > 0) {
    $stmt = $conn->prepare('SELECT COUNT(*) FROM robots WHERE operator = ? AND id != ?');
    if (!$stmt) respond(500, 'Database error (opCount, update).');
    $stmt->bind_param('ii', $operatorId, $robotId);
} else {
    $stmt = $conn->prepare('SELECT COUNT(*) FROM robots WHERE operator = ?');
    if (!$stmt) respond(500, 'Database error (opCount, create).');
    $stmt->bind_param('i', $operatorId);
}
$stmt->execute();
$stmt->bind_result($opCount);
$stmt->fetch();
$stmt->close();
if (($opCount + 1) > 4) {
    respond(400, 'This operator would exceed the maximum of 4 robots. Choose another operator.');
}

/* Unique name within team */
if ($robotId > 0) {
    $dupStmt = $conn->prepare('SELECT COUNT(*) FROM robots WHERE team_id = ? AND name = ? AND id != ?');
    if (!$dupStmt) respond(500, 'Database error (dup check, update).');
    $dupStmt->bind_param('isi', $teamId, $robotName, $robotId);
} else {
    $dupStmt = $conn->prepare('SELECT COUNT(*) FROM robots WHERE team_id = ? AND name = ?');
    if (!$dupStmt) respond(500, 'Database error (dup check, create).');
    $dupStmt->bind_param('is', $teamId, $robotName);
}
$dupStmt->execute();
$dupStmt->bind_result($dupCount);
$dupStmt->fetch();
$dupStmt->close();
if ($dupCount > 0) {
    respond(409, 'A robot with this name already exists for this team.');
}

/* Save */
try {
    if ($robotId > 0) {
        // UPDATE only fields that exist in your table
        $upd = $conn->prepare('
            UPDATE robots
               SET name = ?, category_slug = ?, operator = ?, member_ids = ?, safety_ack = ?
             WHERE id = ? AND team_id = ?
        ');
        if (!$upd) throw new Exception('Prepare failed: ' . $conn->error);

        $upd->bind_param(
            'ssissii',
            $robotName,
            $catSlug,
            $operatorId,
            $memberIdsStr,
            $safetyAck,
            $robotId,
            $teamId
        );

        if (!$upd->execute()) throw new Exception('Execute failed: ' . $upd->error);
        $affected = $upd->affected_rows;
        $upd->close();

        if ($affected === 0) {
            // either identical data or wrong id/team combo
            $chk = $conn->prepare('SELECT id FROM robots WHERE id = ? AND team_id = ?');
            if ($chk) {
                $chk->bind_param('ii', $robotId, $teamId);
                $chk->execute();
                $chk->store_result();
                $exists = $chk->num_rows > 0;
                $chk->close();
                if (!$exists) respond(404, 'Robot not found for this team.');
            }
        }

        respond(200, 'Robot updated successfully.', [
            'robot_id'   => $robotId,
            'name'       => $robotName,
            'category'   => $catSlug,
            'operator'   => $operatorId,
            'member_ids' => $memberIdsStr
        ]);
    } else {
        // INSERT only columns that exist; created_at is automatic
        $ins = $conn->prepare('
            INSERT INTO robots (team_id, name, category_slug, operator, member_ids, safety_ack)
            VALUES (?, ?, ?, ?, ?, ?)
        ');
        if (!$ins) throw new Exception('Prepare failed: ' . $conn->error);

        $ins->bind_param(
            'issisi',
            $teamId,
            $robotName,
            $catSlug,
            $operatorId,
            $memberIdsStr,
            $safetyAck
        );

        if (!$ins->execute()) throw new Exception('Execute failed: ' . $ins->error);
        $newId = $ins->insert_id;
        $ins->close();

        respond(201, 'Robot created successfully.', [
            'robot_id'   => $newId,
            'name'       => $robotName,
            'category'   => $catSlug,
            'operator'   => $operatorId,
            'member_ids' => $memberIdsStr
        ]);
    }
} catch (Throwable $e) {
    sendDebugNotify(
        'Robot save error',
        "Error during robot save: {$e->getMessage()} | File: {$e->getFile()} | Line: {$e->getLine()}"
    );
    respond(500, 'Database error. Please try again.');
}
