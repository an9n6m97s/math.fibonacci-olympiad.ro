<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/backend/headerBackend.php';

if (!isAdminLoggedIn()) {
    respond_error(401, 'Autentificare necesară.');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond_error(405, 'Metodă neacceptată.');
}

try {
    $registrationOpen  = new DateTimeImmutable(post_str('registration_open'));
    $registrationClose = new DateTimeImmutable(post_str('registration_close'));
    $compStart         = new DateTimeImmutable(post_str('comp_start'));
    $compEnd           = new DateTimeImmutable(post_str('comp_end'));
} catch (Throwable $exception) {
    respond_error(422, 'Verifică formatul datelor introduse.');
}

$updates = [
    ['registration_open', $registrationOpen->format('Y-m-d H:i:s')],
    ['registration_close', $registrationClose->format('Y-m-d H:i:s')],
    ['comp_start', $compStart->format('Y-m-d')],
    ['comp_end', $compEnd->format('Y-m-d')],
];

try {
    $stmt = $conn->prepare('UPDATE settings SET value = ?, updated_at = NOW(), updated_by = ? WHERE `key` = ? LIMIT 1');
    $updatedBy = getAdminProfile()['full_name'] ?? 'Administrator';
    foreach ($updates as [$key, $value]) {
        $stmt->bind_param('sss', $value, $updatedBy, $key);
        $stmt->execute();
    }
    $stmt->close();

    respond_ok('Setările au fost actualizate.');
} catch (Throwable $exception) {
    respond_error(500, 'Nu am putut salva setările.', [
        'error' => $exception->getMessage(),
    ]);
}
