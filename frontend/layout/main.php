<?php

/**
 * Main layout file for the frontend of the website.
 * 
 * This file sets up the structure of the webpage, including the inclusion of
 * necessary PHP files for functionality and layout components such as the head,
 * header, footer, and dynamic content. It also handles 404 error pages if the
 * requested file does not exist.
 * 
 * Included Files:
 * - /functions.php: Contains utility functions used across the website.
 * - /backend/files/private/structure/page-include.php: Handles dynamic page inclusion logic.
 * - /frontend/layout/head.php: Contains the <head> section of the HTML document.
 * - /frontend/layout/header.php: Contains the header section of the webpage.
 * - /frontend/components/cookie-notice.php: Displays a cookie notice to the user.
 * - /frontend/layout/footer.php: Contains the footer section of the webpage.
 * - /frontend/404.php: Displays a 404 error page if the requested file is not found.
 * 
 * Logic:
 * - Dynamically includes a file specified by the `$file` variable if it exists.
 * - If the file does not exist, sets the HTTP response code to 404 and includes
 *   the 404 error page.
 * 
 * HTML Structure:
 * - The HTML document is structured with a DOCTYPE declaration, language attribute,
 *   and a body class for styling purposes.
 * 
 * Usage:
 * - This file is intended to be used as the main layout template for the frontend
 *   of the website.
 */

ob_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/functions.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/backend/files/private/structure/page-include.php';

// === EARLY EXIT pentru exporturi (list/csv/pdf) ===
$__fmt  = isset($_GET['format']) ? strtolower(trim($_GET['format'])) : null;
$__type = isset($_GET['type'])   ? strtolower(trim($_GET['type']))   : null;

if ($__fmt && in_array($__fmt, ['list', 'csv', 'pdf'], true) && $__type) {
    // Oprește orice layout sau zgomot
    define('RRC_NO_LAYOUT', true);

    // Curăță TOT ce s-a bufferizat până acum
    if (function_exists('ob_get_level')) {
        while (ob_get_level() > 0) {
            ob_end_clean();
        }
    }

    // Eliberează sesiunea dacă e deschisă
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_write_close();
    }

    // $file este calculat în page-include.php. Rulează direct conținutul paginii de export.
    if (isset($file) && file_exists($file)) {
        include $file; // acest fișier trebuie să trimită header-urile corecte și să facă exit
    } else {
        http_response_code(404);
        header('Content-Type: text/plain; charset=utf-8');
        echo 'Not found.';
    }
    exit; // STOP layout
}
// === END EARLY EXIT ===

headerCalls();
?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="en">
<!--<![endif]-->
<html lang="en" dir="ltr" data-navigation-type="default" data-navbar-horizontal-shape="default">

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/frontend/layout/head.php'; ?>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/frontend/layout/css.php'; ?>

<body>

    <?php // require_once $_SERVER['DOCUMENT_ROOT'] . '/frontend/components/loader.php'; 
    ?>


    <?php
    $uriPath = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '';
    $isAdminArea = preg_match('#^/admin(?:/|$)#', $uriPath);
    if ((!page('coming-soon') && !page('maintenance')) && !page('email') && !preg_match('#^/ucp(?:/[^/]+)*/?$#', $uriPath) && !$isAdminArea) {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/frontend/layout/header.php';
    } elseif (!page('login')  && !page('registration')  && !page('forgot-password')  && !page('reset-password') && preg_match('#^/ucp(?:/[^/]+)*/?$#', $uriPath)) {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/frontend/layout/ucp/header.php';
    } elseif ($isAdminArea) {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/frontend/admin/layout/header.php';
    }
    ?>

    <?php if (!preg_match('#^/ucp(?:/[^/]+)*/?$#', $uriPath) && !$isAdminArea) : ?>
        <div class="page-wrapper">
            <!-- Cursor -->
            <div class="cursor"></div>
            <div class="cursor-follower"></div>
            <!-- Cursor End -->
        <?php endif; ?>
        <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/frontend/components/cookie-notice.php'; ?>
        <?php

        if (file_exists($file)) {
            include $file;
        } else {

            http_response_code(404);
            include $_SERVER['DOCUMENT_ROOT'] . '/frontend/404.php';
        }
        ?>
        <?php if (!preg_match('#^/ucp(?:/[^/]+)*/?$#', $uriPath) && !$isAdminArea) : ?>
        </div>
    <?php endif; ?>

    <?php
    if ((!page('coming-soon') && !page('maintenance')) && !page('email') && !preg_match('#^/ucp(?:/[^/]+)*/?$#', $uriPath) && !$isAdminArea) {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/frontend/layout/footer.php';
    } elseif (!page('login')  && !page('registration')  && !page('forgot-password')  && !page('reset-password') && preg_match('#^/ucp(?:/[^/]+)*/?$#', $uriPath)) {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/frontend/layout/ucp/footer.php';
    } elseif ($isAdminArea) {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/frontend/admin/layout/footer.php';
    }
    ?>


    <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/frontend/layout/script.php'; ?>

</body>

</html>
<? ob_end_flush(); ?>