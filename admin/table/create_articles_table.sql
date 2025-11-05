-- Create articles table for AI-Solutions website
CREATE TABLE IF NOT EXISTS articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    author VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL,
    date DATE NOT NULL,
    status ENUM('published', 'draft') DEFAULT 'draft',
    reading_time INT DEFAULT 5,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert sample articles (matching the static content in articles.php)
INSERT INTO articles (title, content, author, category, date, status, reading_time) VALUES
('The Future of AI in Employee Experience: Transforming Workplace Productivity', 'Discover how artificial intelligence is revolutionizing the modern workplace, from intelligent automation to personalized employee journeys. Our comprehensive analysis reveals the key trends shaping the future of work and how organizations can leverage AI to create exceptional employee experiences.', 'Dr. Sarah Richardson', 'ai', '2024-01-15', 'published', 8),
('Implementing AI Chatbots: A Complete Guide for Modern Businesses', 'Learn how to successfully implement AI chatbots in your organization. From planning and development to deployment and optimization, discover the key strategies for creating intelligent conversational experiences.', 'Michael Johnson', 'ai', '2024-01-12', 'published', 6),
('Creating Seamless Digital Employee Journeys in 2024', 'Explore the latest trends in employee experience design. From onboarding automation to personalized learning paths, discover how to create digital journeys that engage and retain top talent.', 'Emma Wilson', 'experience', '2024-01-10', 'published', 5),
('Innovation Labs: Building Tomorrow\'s Workplace Solutions Today', 'Take a behind-the-scenes look at our innovation process. Learn how we identify emerging workplace challenges and develop cutting-edge AI solutions that transform employee experiences.', 'Dr. Sarah Richardson', 'innovation', '2024-01-08', 'published', 7),
('2024 Workplace AI Adoption Study: Key Findings and Insights', 'Our comprehensive study of 500+ organizations reveals surprising trends in AI adoption. Discover which industries are leading the transformation and what barriers still exist.', 'Alex Thompson', 'research', '2024-01-05', 'published', 10),
('Machine Learning in HR: Transforming Talent Management', 'Explore how machine learning algorithms are revolutionizing recruitment, performance management, and employee development. Real-world case studies and implementation strategies included.', 'Robert Parker', 'technology', '2024-01-03', 'published', 8),
('The Future of Remote Work: AI-Powered Collaboration Tools', 'As remote work becomes permanent, discover how AI is enhancing virtual collaboration. From intelligent meeting assistants to automated project management, the future is here.', 'Lisa Martinez', 'ai', '2023-12-28', 'published', 6);
