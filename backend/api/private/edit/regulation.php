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

    // Check if regulation with this category exists
    $stmt = $conn->prepare("SELECT id FROM regulations WHERE category = ?");
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(['message' => 'Statement preparation failed.']);
        exit;
    }
    $stmt->bind_param("s", $category);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Update existing regulation - first create backup
        $stmt->close();

        // Create backup before updating
        if (backupRegulation($category)) {
            error_log("Backup created successfully for category: " . $category);
        } else {
            error_log("Failed to create backup for category: " . $category);
        }

        $updateStmt = $conn->prepare("UPDATE regulations SET content = ? WHERE category = ?");
        if (!$updateStmt) {
            http_response_code(500);
            echo json_encode(['message' => 'Statement preparation failed.']);
            exit;
        }
        $updateStmt->bind_param("ss", $content, $category);
        if ($updateStmt->execute()) {
            echo json_encode(['message' => 'Regulation successfully updated.']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Failed to update regulation.']);
        }
        $updateStmt->close();
    } else {
        // Insert new regulation
        $stmt->close();
        $insertStmt = $conn->prepare("INSERT INTO regulations (category, content) VALUES (?, ?)");
        if (!$insertStmt) {
            http_response_code(500);
            echo json_encode(['message' => 'Statement preparation failed.']);
            exit;
        }
        $insertStmt->bind_param("ss", $category, $content);
        if ($insertStmt->execute()) {
            echo json_encode(['message' => 'Regulation successfully added.']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Failed to insert regulation.']);
        }
        $insertStmt->close();
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Unexpected error: ' . $e->getMessage()]);
}
