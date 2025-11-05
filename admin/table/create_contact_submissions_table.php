<?php
// Include database connection
require_once 'db_connection.php';

// Get database connection
$link = get_db_connection();

// SQL to create contact_submissions table
$createTableSQL = "CREATE TABLE IF NOT EXISTS contact_submissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    company VARCHAR(100) NOT NULL,
    country VARCHAR(50) NOT NULL,
    job_title VARCHAR(100) NOT NULL,
    job_details TEXT NOT NULL,
    submission_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('new', 'in-progress', 'contacted', 'completed', 'archived') DEFAULT 'new',
    priority ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium'
)";

// Execute create table query
if (mysqli_query($link, $createTableSQL)) {
    echo "Table 'contact_submissions' created or already exists.\n";
} else {
    echo "Error creating table: " . mysqli_error($link) . "\n";
}

// Insert sample data for testing
$sampleDataSQL = "INSERT INTO contact_submissions (full_name, email, phone, company, country, job_title, job_details, status, priority) VALUES 
('Sarah Johnson', 'sarah.johnson@techcorp.com', '+44 7123 456789', 'TechCorp Solutions', 'UK', 'CTO', 'Looking to implement AI-powered employee onboarding system for 500+ employees. Need automated document processing, personalized training paths, and integration with existing HR systems.', 'new', 'high'),
('Michael Chen', 'm.chen@innovate.ca', '+1 416 555 0123', 'Innovate Inc', 'CA', 'Operations Director', 'Seeking AI solution for customer service automation. Current volume: 1000+ tickets/day. Need chatbot integration, sentiment analysis, and escalation workflows.', 'in-progress', 'medium'),
('Emma Rodriguez', 'e.rodriguez@globaltech.de', '+49 30 12345678', 'GlobalTech GmbH', 'DE', 'HR Manager', 'Need AI-driven performance management system. Features required: goal tracking, feedback automation, predictive analytics for employee retention.', 'contacted', 'urgent'),
('James Wilson', 'j.wilson@startup.com', '+1 555 123 4567', 'StartupCo', 'US', 'Founder', 'Small team (20 people) looking for affordable AI tools for project management and team collaboration. Budget conscious but need scalable solution.', 'completed', 'low'),
('Sophie Martin', 's.martin@enterprise.fr', '+33 1 23 45 67 89', 'Enterprise Solutions', 'FR', 'IT Director', 'Large enterprise (2000+ employees) needs comprehensive AI transformation. Multiple departments involved: HR, Finance, Operations. Looking for phased implementation approach.', 'in-progress', 'high')
ON DUPLICATE KEY UPDATE full_name=full_name";

// Execute insert query
if (mysqli_query($link, $sampleDataSQL)) {
    echo "Sample data inserted or already exists.\n";
} else {
    echo "Error inserting sample data: " . mysqli_error($link) . "\n";
}

// Close connection
mysqli_close($link);

echo "Contact submissions table setup completed.\n";
?>
