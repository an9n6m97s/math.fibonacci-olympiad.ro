<?php
// Get email template content
require_once $_SERVER['DOCUMENT_ROOT'] . '/backend/headerBackend.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        exit;
    }

    $template = $_GET['template'] ?? 'general';

    // Validate template name
    if ($template !== 'general') {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid template name']);
        exit;
    }

    $templatePath = $_SERVER['DOCUMENT_ROOT'] . '/assets/email/general.html';

    if (!file_exists($templatePath)) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Template file not found']);
        exit;
    }

    if (!is_readable($templatePath)) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Template file not readable']);
        exit;
    }

    $content = file_get_contents($templatePath);

    if ($content === false) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to read template file']);
        exit;
    }

    echo json_encode([
        'success' => true,
        'content' => $content,
        'file_path' => '/assets/email/general.html',
        'file_size' => filesize($templatePath),
        'last_modified' => filemtime($templatePath)
    ]);
} catch (Exception $e) {
    error_log("Error in get-template.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Internal server error']);
}
