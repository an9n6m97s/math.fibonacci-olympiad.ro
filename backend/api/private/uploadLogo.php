<?php
header('Content-Type: application/json; charset=utf-8');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['ok' => false, 'message' => 'Method not allowed']);
        exit;
    }

    if (empty($_FILES['logo']) || $_FILES['logo']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('No file uploaded or upload error.');
    }

    $file = $_FILES['logo'];

    if ($file['size'] > 5 * 1024 * 1024) {
        throw new Exception('File too large. Max 5 MB.');
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime  = $finfo->file($file['tmp_name']);
    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp'
    ];
    if (!isset($allowed[$mime])) {
        throw new Exception('Invalid image type. Allowed: JPG, PNG, WEBP.');
    }

    if (@getimagesize($file['tmp_name']) === false) {
        throw new Exception('Corrupted or non-image file.');
    }

    $ext = $allowed[$mime];
    $name = bin2hex(random_bytes(8)) . '-' . time() . '.' . $ext;

    $destDirAbs = $_SERVER['DOCUMENT_ROOT'] . '/assets/images/users/';
    $destRel    = '/assets/images/users/' . $name;

    if (!is_dir($destDirAbs) && !mkdir($destDirAbs, 0755, true)) {
        throw new Exception('Upload folder is not writable.');
    }

    if (!move_uploaded_file($file['tmp_name'], $destDirAbs . $name)) {
        throw new Exception('Failed to save the uploaded file.');
    }

    @chmod($destDirAbs . $name, 0644);

    echo json_encode(['ok' => true, 'path' => $destRel, 'filename' => $name]);
} catch (Throwable $e) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'message' => $e->getMessage()]);
}
