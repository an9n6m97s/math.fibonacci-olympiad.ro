<?php
require_once '../functions.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Maintenance - Fibonacci Challenge</title>
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/main.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Arial', sans-serif;
        }

        .maintenance-container {
            text-align: center;
            color: white;
            max-width: 600px;
            padding: 2rem;
        }

        .maintenance-icon {
            font-size: 5rem;
            margin-bottom: 2rem;
        }

        .maintenance-title {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .maintenance-message {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .btn-home {
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid white;
            color: white;
            padding: 0.75rem 2rem;
            text-decoration: none;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .btn-home:hover {
            background: white;
            color: #667eea;
        }
    </style>
</head>

<body>
    <div class="maintenance-container">
        <div class="maintenance-icon">
            🔧
        </div>
        <h1 class="maintenance-title">Site Under Maintenance</h1>
        <p class="maintenance-message">
            We are currently performing scheduled maintenance to improve our services.
            We'll be back online shortly. Thank you for your patience!
        </p>
        <p class="maintenance-message">
            <small>Please check back in a few minutes.</small>
        </p>
    </div>
</body>

</html>