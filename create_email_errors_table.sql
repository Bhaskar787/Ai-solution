CREATE TABLE email_errors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    conversation_id VARCHAR(255) NOT NULL,
    user_email VARCHAR(255) NOT NULL,
    error_message TEXT NOT NULL,
    error_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
