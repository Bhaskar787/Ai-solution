-- Create events table for Photo Gallery admin panel
CREATE TABLE IF NOT EXISTS events (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    date DATE NOT NULL,
    location VARCHAR(255) NOT NULL,
    description TEXT,
    status ENUM('upcoming', 'past') DEFAULT 'upcoming',
    image_path VARCHAR(500),
    category VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert sample events (matching the hardcoded ones in events.php)
INSERT INTO events (title, date, location, description, status, category) VALUES
('AI Summit London 2024', '2024-03-15', 'ExCeL London, UK', 'Join industry leaders discussing the future of AI in business. Our CEO will present "Transforming Employee Experience with AI" on Day 2.', 'upcoming', 'conference'),
('TechNorth Conference', '2024-04-08', 'Newcastle, UK', 'Regional tech conference focusing on Northern England innovation. We\'ll be showcasing our latest AI solutions and hosting a workshop on digital transformation.', 'upcoming', 'conference'),
('Digital Health Summit', '2024-05-22', 'Manchester, UK', 'Healthcare technology conference where we\'ll demonstrate our NHS AI assistant and discuss the future of patient care automation.', 'upcoming', 'conference'),
('AI Expo London 2023', '2023-11-14', 'Olympia London, UK', 'Successfully presented our AI solutions to over 5,000 attendees. Won "Best Innovation Award" for our NHS Digital Assistant platform.', 'past', 'expo'),
('EdTech Innovation Summit', '2023-09-05', 'Birmingham, UK', 'Demonstrated our Smart Learning Platform to education leaders. Secured partnerships with 3 major universities for pilot programs.', 'past', 'summit'),
('Manufacturing 4.0 Expo', '2023-06-12', 'Coventry, UK', 'Showcased our Production Optimizer to manufacturing leaders. Live demo resulted in immediate interest from 15+ companies.', 'past', 'expo');
