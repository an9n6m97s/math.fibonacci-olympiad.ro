<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/backend/headerBackend.php';

header('Content-Type: application/json');

try {
    // Allow only POST requests
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['message' => 'Invalid request method.']);
        exit;
    }

    $category = isset($_POST['category']) ? trim($_POST['category']) : '';
    $content = isset($_POST['content']) ? trim($_POST['content']) : '';

    if (empty($category) || empty($content)) {
        http_response_code(400);
        echo json_encode(['message' => 'Category and content are required.']);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO regulations (category, content) VALUES (?, ?)");
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(['message' => 'Statement preparation failed.']);
        exit;
    }

    $stmt->bind_param("ss", $category, $content);

    if ($stmt->execute()) {
        echo json_encode(['message' => 'Regulation successfully added.']);
    } else {
        http_response_code(500);
        echo json_encode(['message' => 'Failed to insert regulation.']);
    }

    $stmt->close();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Unexpected error: ' . $e->getMessage()]);
}
