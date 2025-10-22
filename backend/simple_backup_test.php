<?php
// Simple database connection for testing
$servername = "localhost";
$username = "root"; // adjust if needed
$password = ""; // adjust if needed
$dbname = "challenge_relativity"; // adjust database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Testing regulation backup system...\n\n";

// Check if regulation_backups table exists
$query = "SHOW TABLES LIKE 'regulation_backups'";
$result = $conn->query($query);
if ($result->num_rows > 0) {
    echo "✅ Table 'regulation_backups' exists\n";

    // Show current backups
    $backupQuery = "SELECT COUNT(*) as count FROM regulation_backups";
    $backupResult = $conn->query($backupQuery);
    $row = $backupResult->fetch_assoc();
    echo "Total backups in table: " . $row['count'] . "\n";
} else {
    echo "❌ Table 'regulation_backups' does NOT exist\n";
    echo "Creating table...\n";

    $createTable = "
    CREATE TABLE `regulation_backups` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `category` varchar(255) NOT NULL,
      `content` longtext NOT NULL,
      `backup_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      KEY `idx_category` (`category`),
      KEY `idx_backup_date` (`backup_date`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";

    if ($conn->query($createTable)) {
        echo "✅ Table 'regulation_backups' created successfully\n";
    } else {
        echo "❌ Failed to create table: " . $conn->error . "\n";
        exit;
    }
}

$conn->close();
echo "\nDone!\n";
