<?php
// Test script to insert sample feedback data
// This is for testing purposes only - remove in production

require_once '../db_connection.php';

$link = get_db_connection();

// Sample feedback data
$sample_feedback = [
    [
        'full_name' => 'John Smith',
        'company' => 'TechCorp Inc.',
        'email' => 'john.smith@techcorp.com',
        'job_title' => 'CTO',
        'project' => 'ai-assistant',
        'rating' => 5,
        'testimonial' => 'The AI assistant has revolutionized our customer service operations. We\'ve seen a 40% reduction in response times and our customer satisfaction scores have never been higher. The implementation was seamless and the support team was excellent throughout the process.',
        'attachment' => null,
        'status' => 'new'
    ],
    [
        'full_name' => 'Sarah Johnson',
        'company' => 'EduLearn Ltd',
        'email' => 'sarah.johnson@edulearn.com',
        'job_title' => 'Head of Digital Learning',
        'project' => 'learning-platform',
        'rating' => 4,
        'testimonial' => 'The smart learning platform has transformed how we deliver education to our students. The AI-powered personalization features have significantly improved student engagement and learning outcomes. We\'re seeing 25% better retention rates.',
        'attachment' => null,
        'status' => 'reviewed'
    ],
    [
        'full_name' => 'Mike Chen',
        'company' => 'Manufacturing Plus',
        'email' => 'mike.chen@mfgplus.com',
        'job_title' => 'Operations Director',
        'project' => 'production-optimizer',
        'rating' => 5,
        'testimonial' => 'This production optimizer exceeded all our expectations. We\'ve achieved a 30% reduction in waste and our efficiency levels are at an all-time high. The ROI was evident within the first month of implementation.',
        'attachment' => null,
        'status' => 'published'
    ]
];

$query = "INSERT INTO feedback_submissions (full_name, company, email, job_title, project, rating, testimonial, attachment, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

foreach ($sample_feedback as $feedback) {
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "sssssisss",
        $feedback['full_name'],
        $feedback['company'],
        $feedback['email'],
        $feedback['job_title'],
        $feedback['project'],
        $feedback['rating'],
        $feedback['testimonial'],
        $feedback['attachment'],
        $feedback['status']
    );

    if (mysqli_stmt_execute($stmt)) {
        echo "Sample feedback inserted successfully for: " . $feedback['full_name'] . "<br>";
    } else {
        echo "Error inserting feedback for: " . $feedback['full_name'] . " - " . mysqli_error($link) . "<br>";
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($link);

echo "<br><strong>Test data insertion completed!</strong><br>";
echo "<a href='manage_feedback.php'>View Feedback Management</a>";
?>
