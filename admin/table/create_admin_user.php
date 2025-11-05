<?php
// Include database connection
require_once '../db_connection.php';

// Get database connection
$link = get_db_connection();

// SQL to create admin table
$createTableSQL = "CREATE TABLE IF NOT EXISTS admin(
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
    echo "Table 'admin' created or already exists.\n";
} else {
    echo "Error creating table: " . mysqli_error($link) . "\n";
}

// Hash the password
$hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);

// Define values
$username = "admin";
$email = "admin@example.com";

// SQL to insert admin user using prepared statement
$stmt = mysqli_prepare($link, "INSERT INTO admin (username, email, password) 
    VALUES (?, ?, ?) 
    ON DUPLICATE KEY UPDATE username=username");

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashedPassword);

    if (mysqli_stmt_execute($stmt)) {
        echo "Admin user '$username' created or already exists.\n";
    } else {
        echo "Error inserting user: " . mysqli_stmt_error($stmt) . "\n";
    }

    mysqli_stmt_close($stmt);
} else {
    echo "Error preparing statement: " . mysqli_error($link) . "\n";
}

// Close connection
mysqli_close($link);

echo "Database setup completed.\n";
?>
