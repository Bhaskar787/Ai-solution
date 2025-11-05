<?php
// Include database connection
require_once '../db_connection.php';

// Get database connection
$link = get_db_connection();

// SQL to create feedback_submissions table
$createTableSQL = "CREATE TABLE IF NOT EXISTS feedback_submissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    company VARCHAR(100),
    email VARCHAR(100) NOT NULL,
    job_title VARCHAR(100),
    project VARCHAR(50),
    rating INT NOT NULL,
    testimonial TEXT NOT NULL,
    attachment VARCHAR(255),
    submission_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('new', 'reviewed', 'published', 'archived') DEFAULT 'new'
)";

// Execute create table query
if (mysqli_query($link, $createTableSQL)) {
    echo "Table 'feedback_submissions' created or already exists.\n";
} else {
    echo "Error creating table: " . mysqli_error($link) . "\n";
}

// Close connection
mysqli_close($link);

echo "Feedback submissions table setup completed.\n";
?>
