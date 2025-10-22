<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/backend/headerBackend.php';

if (!isAdminLoggedIn()) {
    respond_error(401, 'Autentificare necesară.');
}

$format = strtolower($_GET['format'] ?? 'csv');
$validFormats = ['csv', 'json', 'list'];
if (!in_array($format, $validFormats, true)) {
    respond_error(400, 'Format invalid.');
}

$query = 'SELECT first_name, last_name, email, phone, city, school, class AS grade_level, created_at FROM participants ORDER BY created_at DESC';
$result = $conn->query($query);
$data = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    $result->free();
}

$filename = 'participanti-' . date('Ymd-His');

switch ($format) {
    case 'json':
        header('Content-Type: application/json; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '.json"');
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        break;
    case 'list':
        header('Content-Type: text/plain; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '.txt"');
        foreach ($data as $row) {
            echo sprintf(
                "%s %s · %s · %s · %s · %s\n",
                $row['first_name'],
                $row['last_name'],
                $row['email'],
                $row['phone'],
                $row['city'],
                $row['school']
            );
        }
        break;
    default:
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Prenume', 'Nume', 'Email', 'Telefon', 'Oraș', 'Școală', 'Clasa', 'Înscris la']);
        foreach ($data as $row) {
            fputcsv($output, [
                $row['first_name'],
                $row['last_name'],
                $row['email'],
                $row['phone'],
                $row['city'],
                $row['school'],
                $row['grade_level'],
                $row['created_at'],
            ]);
        }
        fclose($output);
        break;
}

die();
