<?php
require_once '../db_connection.php';

$link = get_db_connection();

// Create messages table
$sql = "CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    conversation_id VARCHAR(255) NOT NULL,
    sender_type ENUM('admin', 'user') NOT NULL,
    message TEXT NOT NULL,
    attachment VARCHAR(500) NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_conversation (conversation_id),
    INDEX idx_created_at (created_at)
)";

if (mysqli_query($link, $sql)) {
    echo "Messages table created successfully<br>";
} else {
    echo "Error creating messages table: " . mysqli_error($link) . "<br>";
}

// Create conversations table to track conversation metadata
$sql2 = "CREATE TABLE IF NOT EXISTS conversations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    conversation_id VARCHAR(255) NOT NULL UNIQUE,
    user_name VARCHAR(255) NOT NULL,
    user_email VARCHAR(255) NOT NULL,
    last_message TEXT,
    last_message_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    unread_count INT DEFAULT 0,
    status ENUM('active', 'archived') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user_email (user_email),
    INDEX idx_status (status),
    INDEX idx_last_message_time (last_message_time)
)";

if (mysqli_query($link, $sql2)) {
    echo "Conversations table created successfully<br>";
} else {
    echo "Error creating conversations table: " . mysqli_error($link) . "<br>";
}

mysqli_close($link);
echo "Database setup complete!";
?>
