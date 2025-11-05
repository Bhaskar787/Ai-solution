-- Create reports table for storing report configurations and metadata
CREATE TABLE IF NOT EXISTS reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    type ENUM('contact', 'feedback', 'articles', 'events', 'messages', 'custom') NOT NULL,
    description TEXT,
    filters JSON, -- Store filter parameters as JSON
    date_range_start DATE,
    date_range_end DATE,
    created_by VARCHAR(100),
    created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_generated TIMESTAMP NULL,
    status ENUM('active', 'inactive', 'archived') DEFAULT 'active',
    export_format ENUM('csv', 'pdf', 'both') DEFAULT 'csv',
    scheduled BOOLEAN DEFAULT FALSE,
    schedule_frequency ENUM('daily', 'weekly', 'monthly') NULL,
    email_recipients JSON NULL, -- Store email addresses as JSON array
    INDEX idx_type (type),
    INDEX idx_status (status),
    INDEX idx_created_date (created_date)
);

-- Create report_data table for storing generated report data (optional, for caching)
CREATE TABLE IF NOT EXISTS report_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    report_id INT NOT NULL,
    data JSON, -- Store report data as JSON
    generated_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    file_path VARCHAR(500) NULL, -- Path to exported file if saved
    FOREIGN KEY (report_id) REFERENCES reports(id) ON DELETE CASCADE,
    INDEX idx_report_id (report_id),
    INDEX idx_generated_date (generated_date)
);

-- Insert sample report configurations
INSERT INTO reports (name, type, description, filters, date_range_start, date_range_end, created_by, status) VALUES
('Monthly Contact Submissions Report', 'contact', 'Monthly summary of contact form submissions', '{"status": ["new", "in-progress", "completed"]}', DATE_SUB(CURDATE(), INTERVAL 30 DAY), CURDATE(), 'admin', 'active'),
('Feedback Analysis Report', 'feedback', 'Analysis of user feedback and ratings', '{"status": ["new", "reviewed", "published"]}', DATE_SUB(CURDATE(), INTERVAL 90 DAY), CURDATE(), 'admin', 'active'),
('Published Articles Report', 'articles', 'Report of published articles by category', '{"status": ["published"]}', DATE_SUB(CURDATE(), INTERVAL 365 DAY), CURDATE(), 'admin', 'active');
