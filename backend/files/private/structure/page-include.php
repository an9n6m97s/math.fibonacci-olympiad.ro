<?php

/**
 * This script processes the incoming request URI to determine the appropriate PHP file to include.
 * It normalizes the request URI, extracts the path, and maps it to a corresponding file in the frontend directory.
 *
 * Steps:
 * 1. Retrieve the request URI from the server and normalize it by replacing multiple slashes with a single slash.
 * 2. Parse the URL to extract the path and remove any leading slashes.
 * 3. Determine the requested page name by removing query strings, fragments, and file extensions.
 * 4. Set the default page to 'index' if no path is provided.
 * 5. Construct the file path to the corresponding PHP file in the frontend directory:
 *    - If the page is 'index', map it to "homepage.php".
 *    - If the page corresponds to a directory with an "index.php" file, map it to that file.
 * 6. The resulting file path is stored in the `$file` variable for further processing.
 *
 * Variables:
 * - `$requestUri`: The raw request URI from the server.
 * - `$parsedUrl`: The parsed components of the request URI.
 * - `$requestPath`: The normalized and trimmed path from the request URI.
 * - `$page`: The name of the requested page, derived from the path.
 * - `$file`: The resolved file path to the corresponding PHP file in the frontend directory.
 */

$requestUri = $_SERVER['REQUEST_URI'];

$requestUri = preg_replace('#/+#', '/', $requestUri);

$parsedUrl = parse_url($requestUri);
$requestPath = isset($parsedUrl['path']) ? ltrim($parsedUrl['path'], '/') : '';
$requestPath = strtok($requestPath, '?');

if (empty($requestPath)) {
    $requestPath = 'index';
}

$page = strpos($requestPath, '.php') !== false ? basename($requestPath, '.php') : $requestPath;
$page = strtok($page, '#');


if (strpos($requestPath, 'backend') === 0) {
    $file = $_SERVER['DOCUMENT_ROOT'] . "/$requestPath";
} else {
    $file = resolveFrontendPath($requestPath);
}
