<?php
/*
    Changelog deletion endpoint (mysqli)
    - Uses $conn from /backend/headerBackend.php
    - Requires id (GET)
    - Checks authentication
    - Deletes record from changelogs table
*/

header('Content-Type: application/json; charset=utf-8');

require_once $_SERVER['DOCUMENT_ROOT'] . '/backend/headerBackend.php';

/* Helpers */
if (!function_exists('respond')) {
    function respond(int $code, $message, array $extra = []): void
    {
        http_response_code($code);
        $payload = is_array($message) ? $message : ['message' => $message];
        echo json_encode(array_merge($payload, $extra), JSON_UNESCAPED_UNICODE);
        exit;
    }
}

function toNullIfEmpty(?string $v): ?string
{
    $v = is_string($v) ? trim($v) : '';
    return $v === '' ? null : $v;
}
function slugify(string $text, int $maxLen = 160): string
{
    $text = trim($text);
    $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
    $text = strtolower($text);
    $text = preg_replace('~[^a-z0-9]+~', '-', $text);
    $text = trim($text, '-');
    if ($text === '') $text = 'change';
    if (strlen($text) > $maxLen) $text = substr($text, 0, $maxLen);
    return $text;
}
function uniqueSlug(mysqli $conn, string $base, int $maxLen = 160): string
{
    $slug = $base;
    $i = 2;
    $stmt = $conn->prepare('SELECT COUNT(*) FROM changelogs WHERE slug = ? LIMIT 1');
    if (!$stmt) return $slug;
    while (true) {
        $stmt->bind_param('s', $slug);
        $stmt->execute();
        $stmt->bind_result($cnt);
        $stmt->fetch();
        $stmt->free_result();
        if ((int)$cnt === 0) break;
        $suffix = '-' . $i++;
        $slug = substr($base, 0, max(1, $maxLen - strlen($suffix))) . $suffix;
        if ($i > 500) break; // sanity
    }
    $stmt->close();
    return $slug;
}
function parseDatetime(?string $in): ?string
{
    if (!$in) return null;
    $ts = strtotime($in);
    if ($ts === false) return null;
    return date('Y-m-d H:i:s', $ts);
}

/* Method check */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    respond(405, 'Method Not Allowed');
}

/* Gather input */
$title       = trim((string) post('title'));
$area        = trim((string) post('area'));
$version     = toNullIfEmpty(post('version'));
$visible_to  = trim((string) post('visible_to'));
$status      = trim((string) post('status'));
$is_pinned   = (string) post('is_pinned') === '1' ? 1 : 0;
$is_breaking = (string) post('is_breaking') === '1' ? 1 : 0;
$scheduledAt = toNullIfEmpty(post('scheduled_at'));
$description = trim((string) post('description'));
$posted_by   = (int) ($_POST['posted_by_admin_id'] ?? $adminId);

/* Validate enums */
$validAreas = ['website', 'ucp', 'registration', 'scoring', 'security', 'infrastructure'];
$validVisible = ['all', 'admin'];
$validStatus = ['draft', 'scheduled', 'published', 'archived'];

if ($title === '' || mb_strlen($title) < 3) respond(400, 'Invalid title.');
if (!in_array($area, $validAreas, true)) respond(400, 'Invalid area.');
if (!in_array($visible_to, $validVisible, true)) respond(400, 'Invalid visible_to.');
if (!in_array($status, $validStatus, true)) respond(400, 'Invalid status.');
if ($description === '' || $description === '<p><br></p>') respond(400, 'Description required.');

/* Datetime logic */
$scheduled_at = null;
$published_at = null;

if ($status === 'scheduled') {
    $scheduled_at = parseDatetime($scheduledAt);
    if (!$scheduled_at) respond(400, 'Scheduled time required for status "scheduled".');
}
if ($status === 'published') {
    // allow scheduled_at to future-publish if provided, else publish now
    $scheduled_at = parseDatetime($scheduledAt) ?: null;
    $published_at = date('Y-m-d H:i:s');
}

/* DB ready */
if (!isset($conn) || !($conn instanceof mysqli)) {
    respond(500, 'Database connection not initialized.');
}

/* Build slug */
$baseSlug = slugify($title, 160);
$slug = uniqueSlug($conn, $baseSlug, 160);

/* Insert */
$sql = "INSERT INTO changelogs
        (slug, title, description, area, version, is_breaking, visible_to, posted_by_admin_id, status, is_pinned, created_at, updated_at, scheduled_at, published_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?, ?)";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    respond(500, 'Failed to prepare statement.');
}

$stmt->bind_param(
    'sssssisissss',
    $slug,
    $title,
    $description,
    $area,
    $version,
    $is_breaking,
    $visible_to,
    $posted_by,
    $status,
    $is_pinned,
    $scheduled_at,
    $published_at
);

if (!$stmt->execute()) {
    $err = $conn->errno . ': ' . $conn->error;
    $stmt->close();
    respond(500, 'Failed to create changelog.', ['error' => $err]);
}

$insertId = $stmt->insert_id;
$stmt->close();

/* Success */
respond(201, 'Changelog created.', [
    'id' => $insertId,
    'slug' => $slug,
    'redirect' => '/ucp/admin/changelogs/manage'
]);
