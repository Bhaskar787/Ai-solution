<?php
// Include database connection
require_once '../db_connection.php';

// Get database connection
$link = get_db_connection();

// SQL to create admin_users table
$createTableSQL = "CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL DEFAULT NULL,
    status ENUM('active', 'inactive', 'suspended') DEFAULT 'active'
)";

// Execute create table query
if (mysqli_query($link, $createTableSQL)) {
    echo "Table 'admin_users' created or already exists.\n";
} else {
    echo "Error creating table: " . mysqli_error($link) . "\n";
}

// Insert sample data for testing
$sampleUsers = [
    ['john_doe', 'john.doe@example.com', 'hashed_password_1', 'active'],
    ['sarah_miller', 'sarah.miller@example.com', 'hashed_password_2', 'active'],
    ['mike_johnson', 'mike.johnson@example.com', 'hashed_password_3', 'inactive'],
    ['jane_smith', 'jane.smith@example.com', 'hashed_password_4', 'active'],
    ['robert_brown', 'robert.brown@example.com', 'hashed_password_5', 'suspended']
];

foreach ($sampleUsers as $user) {
    $stmt = mysqli_prepare($link, "INSERT INTO admin_users (username, email, password, status, last_login) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE username=username");
    if ($stmt) {
        $lastLogin = rand(0, 1) ? date('Y-m-d H:i:s', strtotime('-' . rand(1, 30) . ' days')) : null;
        mysqli_stmt_bind_param($stmt, "sssss", $user[0], $user[1], $user[2], $user[3], $lastLogin);
        if (!mysqli_stmt_execute($stmt)) {
            echo "Error inserting user " . $user[0] . ": " . mysqli_stmt_error($stmt) . "\n";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing statement: " . mysqli_error($link) . "\n";
    }
}

// Close connection
mysqli_close($link);

echo "Admin users table setup completed.\n";
?>
