<?php
include __DIR__ . '/../../../../vendor/autoload.php';
include __DIR__ . '/../../../headerBackend.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

$emailUid = $_GET['uid'] ?? null;

if (!$emailUid) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Valid email UID required']);
    exit;
}

// Get email details using existing emails_log table
$query = "SELECT el.*
          FROM emails_log el 
          WHERE el.email_uid = ?
          ORDER BY el.email ASC";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 's', $emailUid);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) === 0) {
    http_response_code(404);
    echo json_encode(['success' => false, 'error' => 'Email not found']);
    exit;
}

$emails = [];
while ($row = mysqli_fetch_assoc($result)) {
    $emails[] = $row;
}

echo json_encode([
    'success' => true,
    'emails' => $emails,
    'sender_name' => 'System Admin'
]);
