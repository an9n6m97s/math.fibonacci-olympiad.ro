<?php
// Preview email template with test data
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

    $templateContent = $input['template_content'] ?? '';
    $testData = $input['test_data'] ?? [];
    $useGeneralTemplate = $input['use_general_template'] ?? false;

    // If using general template, load it
    if ($useGeneralTemplate) {
        $generalTemplatePath = $_SERVER['DOCUMENT_ROOT'] . '/assets/email/general.html';
        if (file_exists($generalTemplatePath)) {
            $templateContent = file_get_contents($generalTemplatePath);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'General template file not found']);
            exit;
        }
    }

    if (empty($templateContent)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Template content is required']);
        exit;
    }

    // Default test data
    $defaultTestData = [
        '{{subject}}' => 'Test Email Subject',
        '{{title}}' => 'Welcome to Our Newsletter',
        '{{content}}' => 'This is a sample email content. You can include <strong>HTML formatting</strong> and <a href="#">links</a> in your email content.',
        '{{recipient_name}}' => 'John Doe',
        '{{sender_name}}' => 'Relativity Team',
        '{{website_url}}' => 'https://fibonacci-olympiad.ro',
        '{{unsubscribe_url}}' => 'https://fibonacci-olympiad.ro/unsubscribe'
    ];

    // Merge test data with defaults
    foreach ($testData as $key => $value) {
        $placeholder = '{{' . $key . '}}';
        $defaultTestData[$placeholder] = $value;
    }

    // Replace placeholders in template
    $preview = $templateContent;
    foreach ($defaultTestData as $placeholder => $value) {
        $preview = str_replace($placeholder, $value, $preview);
    }

    echo json_encode([
        'success' => true,
        'preview' => $preview,
        'placeholders_used' => array_keys($defaultTestData),
        'template_size' => strlen($templateContent)
    ]);
} catch (Exception $e) {
    error_log("Error in preview-template.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Internal server error: ' . $e->getMessage()]);
}
