<?php
include __DIR__ . '/../../../../vendor/autoload.php';
include __DIR__ . '/../../../headerBackend.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo 'Method not allowed';
    exit;
}

$emailUid = $_GET['uid'] ?? null;

if (!$emailUid) {
    http_response_code(400);
    echo '<html><body><div style="padding:20px;">Invalid email UID</div></body></html>';
    exit;
}

// Get email content from existing emails_log table (take first entry for preview)
$query = "SELECT content FROM emails_log WHERE email_uid = ? LIMIT 1";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 's', $emailUid);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) === 0) {
    http_response_code(404);
    echo '<html><body><div style="padding:20px;">Email not found</div></body></html>';
    exit;
}

$email = mysqli_fetch_assoc($result);

// Output the HTML content directly
header('Content-Type: text/html; charset=utf-8');
echo $email['content'];
