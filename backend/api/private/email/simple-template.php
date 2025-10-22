<?php
// SIMPLU API PENTRU TEMPLATE LOADING
require_once $_SERVER['DOCUMENT_ROOT'] . '/backend/headerBackend.php';

header('Content-Type: application/json');

try {
    $templatePath = $_SERVER['DOCUMENT_ROOT'] . '/assets/email/general.html';

    if (!file_exists($templatePath)) {
        echo json_encode(['success' => false, 'message' => 'Template not found']);
        exit;
    }

    $template = file_get_contents($templatePath);

    // Înlocuiește placeholder-urile cu valori default pentru editare
    $template = str_replace('{{subject}}', 'Fibonacci Romania 2026', $template);
    $template = str_replace('{{preheader}}', 'Check event updates 24–48h before arrival.', $template);
    $template = str_replace('{{headline|Fibonacci Romania 2026}}', 'Fibonacci Romania 2026', $template);
    $template = str_replace('{{event_dates|TBA}}', '27 February – 1 March 2026', $template);
    $template = str_replace('{{reg_status|Open}}', 'Open', $template);
    $template = str_replace('{{reg_close|TBA}}', 'TBA', $template);
    $template = str_replace('{{cta_label}}', 'Register Now', $template);
    $template = str_replace('{{cta_url}}', 'https://fibonacci-olympiad.ro/ucp', $template);
    $template = str_replace('{{ucp_url}}', 'https://fibonacci-olympiad.ro/ucp', $template);
    $template = str_replace('{{status_url}}', 'https://status.essenbyte.com/', $template);
    $template = str_replace('{{privacy_url|https://fibonacci-olympiad.ro/legal/privacy}}', 'https://fibonacci-olympiad.ro/legal/privacy', $template);
    $template = str_replace('{{terms_url|https://fibonacci-olympiad.ro/legal/terms}}', 'https://fibonacci-olympiad.ro/legal/terms', $template);
    $template = str_replace('{{unsubscribe_url}}', 'https://fibonacci-olympiad.ro/unsubscribe', $template);
    $template = str_replace('{{#unsubscribe}}', '', $template);
    $template = str_replace('{{/unsubscribe}}', '', $template);

    // Conținut principal default
    $template = str_replace(
        '{{main_content_html}}',
        '<h2>Welcome to the Fibonacci Romania 2026!</h2>
        <p>Dear participants,</p>
        <p>We are excited to announce that the <strong>Fibonacci Romania 2026</strong> is approaching fast! This year\'s competition will be bigger and better than ever, featuring cutting-edge robotics challenges and innovative competition formats.</p>
        <h3>Key Information:</h3>
        <ul>
            <li><strong>Dates:</strong> 27 February – 1 March 2026</li>
            <li><strong>Location:</strong> LTIAM, Buzău, Romania</li>
            <li><strong>Categories:</strong> Sumo, Line Follower, Humanoid, Drag Race</li>
        </ul>
        <p>Best regards,<br><strong>The Relativity Team</strong></p>',
        $template
    );

    echo json_encode([
        'success' => true,
        'content' => $template
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
