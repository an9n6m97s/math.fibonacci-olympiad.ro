<?php
/*
  Robot edit endpoint (mysqli)
  - Uses $conn from /backend/headerBackend.php
  - Validates all fields from the form
  - Updates robots table
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

$robotId = (int)post('robot_id');
$robotName = trim((string)post('robot_name'));
$category = post('robot_category');
if (is_array($category)) {
    $category = implode(',', $category); // Or pick the first element: $category = $category[0];
}
$category = trim((string)$category);
$operator = trim((string)post('robot_operator'));
$members = isset($_POST['robot_members']) ? $_POST['robot_members'] : [];
$teamId = (int)post('team_id');
$safetyAck = 1;

if ($robotId <= 0 || $robotName === '' || $category === '' || $operator === '' || empty($members) || $teamId <= 0) {
    respond(400, 'Please fill all required fields.');
}

if (mb_strlen($robotName) < 2 || mb_strlen($robotName) > 120) {
    respond(400, 'Robot name must be between 2 and 120 characters.');
}

$memberIds = array_map('intval', $members);
$memberIdsStr = implode(',', $memberIds);

if (!($conn instanceof mysqli)) {
    respond(500, 'Database connection not available.');
}

// Check if operator is already assigned to 3 robots (excluding current robot)
$stmt = $conn->prepare('SELECT COUNT(*) FROM robots WHERE operator = ? AND id != ?');
$stmt->bind_param('si', $operator, $robotId);
$stmt->execute();
$stmt->bind_result($opCount);
$stmt->fetch();
$stmt->close();

if (($opCount + 1) > 3) {
    respond(400, 'This user would exceed the maximum of 3 robots as operator. Please select another operator.');
}

// Verifică dacă există deja un robot cu același nume pentru această echipă (excluzând robotul curent)
$dupStmt = $conn->prepare('SELECT COUNT(*) FROM robots WHERE team_id = ? AND name = ? AND id != ?');
$dupStmt->bind_param('isi', $teamId, $robotName, $robotId);
$dupStmt->execute();
$dupStmt->bind_result($dupCount);
$dupStmt->fetch();
$dupStmt->close();

if ($dupCount > 0) {
    respond(409, 'A robot with this name already exists for your team. Please choose a different name.');
}

try {
    $upd = $conn->prepare('
        UPDATE robots
        SET name = ?, category_slug = ?, operator = ?, member_ids = ?, safety_ack = ?
        WHERE id = ? AND team_id = ?
    ');
    if (!$upd) throw new Exception('Prepare failed: ' . $conn->error);

    $upd->bind_param(
        'ssssiii',
        $robotName,
        $category,
        $operator,
        $memberIdsStr,
        $safetyAck,
        $robotId,
        $teamId
    );

    if (!$upd->execute()) {
        throw new Exception('Execute failed: ' . $upd->error);
    }

    $upd->close();

    respond(200, 'Robot updated successfully.', [
        'robot_id' => $robotId,
        'name' => $robotName,
        'category' => $category,
        'operator' => $operator,
        'member_ids' => $memberIdsStr
    ]);
} catch (Throwable $e) {
    sendDebugNotify(
        'Robot edit error',
        "Error during robot edit: {$e->getMessage()} | File: {$e->getFile()} | Line: {$e->getLine()}"
    );
    respond(500, 'Database error. Please try again.');
}
