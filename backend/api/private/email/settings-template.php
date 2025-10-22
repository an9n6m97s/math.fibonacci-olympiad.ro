<?php
// API PENTRU TEMPLATE HTML COMPLET CU VALORILE DIN $settings
require_once $_SERVER['DOCUMENT_ROOT'] . '/backend/headerBackend.php';

header('Content-Type: application/json');

try {
    $templatePath = $_SERVER['DOCUMENT_ROOT'] . '/assets/email/general.html';

    if (!file_exists($templatePath)) {
        echo json_encode(['success' => false, 'message' => 'Template not found']);
        exit;
    }

    $template = file_get_contents($templatePath);

    // VALORILE DIN $settings
    $currentYear = date('Y');
    $nextYear = $currentYear + 1;

    // Status registration din $settings
    $currentDate = date('Y-m-d');
    $regOpen = isset($settings['registration_open']) ? $settings['registration_open'] : '2025-09-01';
    $regClose = isset($settings['registration_close']) ? $settings['registration_close'] : '2026-02-15';
    $regStatus = (strtotime($currentDate) >= strtotime($regOpen) && strtotime($currentDate) <= strtotime($regClose)) ? 'Open' : 'Closed';

    // Date din $settings
    $compStart = isset($settings['comp_start']) ? $settings['comp_start'] : '2026-02-27 09:00:00';
    $compEnd = isset($settings['comp_end']) ? $settings['comp_end'] : '2026-03-01 18:00:00';
    $partConfirm = isset($settings['participation_confirmation']) ? $settings['participation_confirmation'] : '2026-02-20 23:59:59';
    $announcementBanner = isset($settings['announcement_banner']) ? $settings['announcement_banner'] : 'Important: check event updates 24–48h before arrival.';

    // Formatează datele pentru email
    $eventDates = date('j M', strtotime($compStart)) . ' – ' . date('j M Y', strtotime($compEnd));
    $regCloseFormatted = date('j M Y', strtotime($regClose));
    $partConfirmFormatted = date('j M Y', strtotime($partConfirm));

    // ÎNLOCUIEȘTE cu valorile din $settings
    $template = str_replace('{{subject}}', "Fibonacci Romania $nextYear", $template);
    $template = str_replace('{{preheader}}', 'Check event updates 24–48h before arrival.', $template);
    $template = str_replace('{{headline|Fibonacci Romania 2026}}', "Fibonacci Romania $nextYear", $template);
    $template = str_replace('{{event_dates|TBA}}', $eventDates, $template);
    $template = str_replace('{{reg_status|Open}}', $regStatus, $template);
    $template = str_replace('{{reg_close|TBA}}', $regCloseFormatted, $template);
    $template = str_replace('{{subheadline|Buzău, Romania • Building the future of robotics}}', 'Buzău, Romania • Building the future of robotics', $template);
    $template = str_replace('{{info_banner|Important: check event updates 24–48h before arrival.}}', $announcementBanner, $template);

    // URL-uri actualizate conform cerințelor
    $template = str_replace('{{cta_url}}', 'https://fibonacci-olympiad.ro/ucp', $template);
    $template = str_replace('{{ucp_url}}', 'https://fibonacci-olympiad.ro/ucp', $template);
    $template = str_replace('{{status_url}}', 'https://status.essenbyte.com/', $template);
    $template = str_replace('{{privacy_url|https://fibonacci-olympiad.ro/privacy}}', 'https://fibonacci-olympiad.ro/legal/privacy', $template);
    $template = str_replace('{{terms_url|https://fibonacci-olympiad.ro/terms}}', 'https://fibonacci-olympiad.ro/legal/terms', $template);

    // Elimină unsubscribe conform cerințelor
    $template = str_replace('{{#unsubscribe}}', '', $template);
    $template = str_replace('{{/unsubscribe}}', '', $template);
    $template = str_replace('{{unsubscribe_url}}', '', $template);

    // LAS MAIN_CONTENT_HTML GOL pentru ca userul să scrie
    // NU mai înlocuim {{main_content_html}} - rămâne gol pentru editare manuală    // Adaugă label pentru butonul CTA - SCHIMBAT pentru utilizatori înregistrați
    if (strpos($template, '{{cta_label}}') !== false) {
        $template = str_replace('{{cta_label}}', 'My Dashboard', $template);
    }

    // URL pentru dashboard în loc de înregistrare
    $template = str_replace('{{cta_url}}', 'https://fibonacci-olympiad.ro/ucp', $template);

    echo json_encode([
        'success' => true,
        'content' => $template,
        'year' => $nextYear,
        'reg_status' => $regStatus,
        'event_dates' => $eventDates,
        'settings_used' => true,
        'template_type' => 'complete_html_with_settings',
        'message' => "Complete HTML template loaded with $nextYear data from settings!"
    ]);
} catch (Exception $e) {
    error_log("HTML template API error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage()
    ]);
}
