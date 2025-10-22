<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/backend/headerBackend.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['message' => 'Invalid request method.']);
    exit;
}

// Sanitize and validate inputs
$author = isset($_POST['author']) ? trim($_POST['author']) : '';
$role   = isset($_POST['role'])   ? trim($_POST['role'])   : '';
$team   = isset($_POST['team'])   ? trim($_POST['team'])   : '';
$review = isset($_POST['review']) ? trim($_POST['review']) : '';
$stars  = isset($_POST['stars'])  ? (int) $_POST['stars']   : 0;

if (empty($author) || empty($review) || $stars < 1 || $stars > 5) {
    echo json_encode(['message' => 'Invalid input data.']);
    exit;
}

if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['message' => 'File upload error.']);
    exit;
}

$fileTmp  = $_FILES['photo']['tmp_name'];
$fileName = basename($_FILES['photo']['name']);
$fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
$allowed  = ['jpg', 'jpeg', 'png', 'gif'];

if (!in_array($fileExt, $allowed)) {
    echo json_encode(['message' => 'Invalid file type.']);
    exit;
}

$targetDir = $_SERVER['DOCUMENT_ROOT'] . '/assets/images/review-author/';
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0755, true);
}

$newName    = uniqid('testimonial_') . '.' . $fileExt;
$targetPath = $targetDir . $newName;

if (!move_uploaded_file($fileTmp, $targetPath)) {
    echo json_encode(['message' => 'Failed to move uploaded file.']);
    exit;
}

$photoPath = 'assets/images/review-author/' . $newName;

$stmt = $conn->prepare(
    "INSERT INTO testimonials (author, role, team, photo, review, stars) VALUES (?, ?, ?, ?, ?, ?)"
);
if ($stmt === false) {
    echo json_encode(['message' => 'Database prepare error: ' . $conn->error]);
    exit;
}
$stmt->bind_param('sssssi', $author, $role, $team, $photoPath, $review, $stars);
if (!$stmt->execute()) {
    echo json_encode(['message' => 'Database execute error: ' . $stmt->error]);
    $stmt->close();
    exit;
}
$stmt->close();

echo json_encode(['message' => 'Testimonial added successfully.']);

if ($settings['admin_notification']['testimonials'])
    sendNotify(
        'Testimonial Added',
        "A new testimonial has been added by **$author** with a rating of **$stars** stars."
    );

exit;
