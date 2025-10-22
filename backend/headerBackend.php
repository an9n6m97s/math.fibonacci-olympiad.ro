<?php
// CORS + JSON headers (o singură dată)
if (!headers_sent()) {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');
    header('Access-Control-Expose-Headers: Content-Length, X-JSON');
    header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization');
    // if ($_SERVER['SCRIPT_NAME'] !== '/backend/api/public/ucp/registration-open-campagne.php') {
    //     echo 'https://fibonacci-olympiad.ro/backend/api/public/ucp/registration-open-campagne.php';
    // }
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/settings.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions.php';

$csrf = new CsrfToken();

function respond(int $code, string $message, array $data = []): void
{
    while (ob_get_level() > 0) {
        ob_end_clean();
    }

    if (!headers_sent()) {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
    }

    $payload = $data + [
        'status'  => $code < 400 ? 'ok' : 'error',
        'message' => $message,
    ];

    echo json_encode(
        $payload,
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE
    );
    exit;
}

function respond_ok(string $message, array $data = []): void
{
    respond(200, $message, $data);
}
function respond_error(int $code, string $message, array $data = []): void
{
    if ($code < 400) $code = 400;
    respond($code, $message, $data);
}

function post(string $key, $default = '')
{
    if (!isset($_POST[$key])) return $default;
    $v = $_POST[$key];
    return is_array($v) ? $v : trim((string)$v);
}

function post_str(string $key, string $default = ''): string
{
    if (!isset($_POST[$key]) || is_array($_POST[$key])) return $default;
    return trim((string)$_POST[$key]);
}

function post_int(string $key, int $default = 0): int
{
    if (!isset($_POST[$key]) || is_array($_POST[$key])) return $default;
    return (int)$_POST[$key];
}

function post_array(string $key): array
{
    if (!isset($_POST[$key])) return [];
    $v = $_POST[$key];
    if (is_array($v)) return $v;
    $v = trim((string)$v);
    return $v === '' ? [] : [$v];
}

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    respond(204, 'No Content');
}
