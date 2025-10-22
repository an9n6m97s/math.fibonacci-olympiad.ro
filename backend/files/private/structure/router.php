<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions.php';
$page = $_GET['page'] ?? 'index';
$file = resolveFrontendPath('/' . $page);

if (file_exists($file)) {
    include $file;
} else {
    http_response_code(404);
    echo '<h2>404 - Page Not Found</h2>';
}
