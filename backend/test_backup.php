<?php
require_once __DIR__ . '/../settings.php';
require_once __DIR__ . '/../functions.php';

// Test backup functionality
echo "Testing regulation backup system...\n\n";

// Check if regulation_backups table exists
$query = "SHOW TABLES LIKE 'regulation_backups'";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) > 0) {
    echo "✅ Table 'regulation_backups' exists\n";
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

    if (mysqli_query($conn, $createTable)) {
        echo "✅ Table 'regulation_backups' created successfully\n";
    } else {
        echo "❌ Failed to create table: " . mysqli_error($conn) . "\n";
        exit;
    }
}

// Test backup function
echo "\nTesting backup function:\n";

$categories = getCategories();
if (!empty($categories)) {
    $testCategory = $categories[0]['slug'];
    echo "Testing with category: " . $testCategory . "\n";

    $result = backupRegulation($testCategory);
    if ($result) {
        echo "✅ Backup created successfully!\n";

        // Check if backup was actually inserted
        $checkQuery = "SELECT COUNT(*) as count FROM regulation_backups WHERE category = ?";
        $stmt = mysqli_prepare($conn, $checkQuery);
        mysqli_stmt_bind_param($stmt, "s", $testCategory);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        echo "Total backups for this category: " . $row['count'] . "\n";
    } else {
        echo "❌ Backup failed!\n";
    }
} else {
    echo "No categories found to test with.\n";
}

echo "\nDone!\n";
