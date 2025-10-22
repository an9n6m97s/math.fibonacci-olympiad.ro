<?php
// Save email template content
require_once $_SERVER['DOCUMENT_ROOT'] . '/backend/headerBackend.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        exit;
    }

    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid JSON input']);
        exit;
    }

    $template = $input['template'] ?? '';
    $content = $input['content'] ?? '';

    // Validate inputs
    if ($template !== 'general') {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid template name']);
        exit;
    }

    if (empty($content)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Template content cannot be empty']);
        exit;
    }

    $templatePath = $_SERVER['DOCUMENT_ROOT'] . '/assets/email/general.html';
    $backupPath = $_SERVER['DOCUMENT_ROOT'] . '/assets/email/backups/general_' . date('Y-m-d_H-i-s') . '.html';

    // Create backup directory if it doesn't exist
    $backupDir = dirname($backupPath);
    if (!is_dir($backupDir)) {
        if (!mkdir($backupDir, 0755, true)) {
            error_log("Failed to create backup directory: $backupDir");
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to create backup directory']);
            exit;
        }
    }

    // Create backup if original file exists
    if (file_exists($templatePath)) {
        if (!copy($templatePath, $backupPath)) {
            error_log("Failed to create backup of template file");
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to create backup']);
            exit;
        }
    }

    // Save new content
    if (file_put_contents($templatePath, $content) === false) {
        error_log("Failed to save template file: $templatePath");
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to save template file']);
        exit;
    }

    // Clean up old backups (keep last 10)
    $backupFiles = glob($backupDir . '/general_*.html');
    if (count($backupFiles) > 10) {
        usort($backupFiles, function ($a, $b) {
            return filemtime($a) <=> filemtime($b);
        });

        $filesToDelete = array_slice($backupFiles, 0, -10);
        foreach ($filesToDelete as $file) {
            unlink($file);
        }
    }

    echo json_encode([
        'success' => true,
        'message' => 'Template saved successfully',
        'backup_created' => $backupPath,
        'file_size' => filesize($templatePath),
        'timestamp' => time()
    ]);
} catch (Exception $e) {
    error_log("Error in save-template.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Internal server error']);
}
