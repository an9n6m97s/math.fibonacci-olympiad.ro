<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/backend/headerBackend.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond_error(405, 'Metodă neacceptată.');
}

clearAdminSession();

respond_ok('Delogat cu succes.', [
    'redirect' => '/admin/login',
]);
