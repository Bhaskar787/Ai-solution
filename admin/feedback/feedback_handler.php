<?php
// Include database connection
require_once '../db_connection.php';

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../feedback.php?error=invalid_request');
    exit;
}

// Get POST data and sanitize
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$company = isset($_POST['company']) ? trim($_POST['company']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$job_title = isset($_POST['job-title']) ? trim($_POST['job-title']) : '';
$project = isset($_POST['project']) ? trim($_POST['project']) : '';
$rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;
$testimonial = isset($_POST['testimonial']) ? trim($_POST['testimonial']) : '';
$consent = isset($_POST['consent']) ? true : false;

// Validate required fields
if (empty($name) || empty($email) || empty($testimonial) || !$consent || $rating < 1 || $rating > 5) {
    header('Location: ../../feedback.php?error=missing_fields');
    exit;
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ../../feedback.php?error=invalid_email');
    exit;
}

// Handle file upload
$attachment_path = null;
if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['attachment'];

    // Validate file type
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    if (!in_array($file['type'], $allowed_types)) {
        header('Location: ../../feedback.php?error=invalid_file_type');
        exit;
    }

    // Validate file size (max 5MB)
    if ($file['size'] > 5 * 1024 * 1024) {
        header('Location: ../../feedback.php?error=file_too_large');
        exit;
    }

    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid('feedback_', true) . '.' . $extension;
    $upload_dir = '../uploads/attachments/';
    $attachment_path = $upload_dir . $filename;

    // Move uploaded file
    if (!move_uploaded_file($file['tmp_name'], $attachment_path)) {
        header('Location: ../../feedback.php?error=upload_failed');
        exit;
    }
}

// Get database connection
$link = get_db_connection();

// Prepare statement to prevent SQL injection
$stmt = mysqli_prepare($link, "INSERT INTO feedback_submissions (full_name, company, email, job_title, project, rating, testimonial, attachment) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
if (!$stmt) {
    header('Location: ../../feedback.php?error=db_error');
    exit;
}

// Bind parameters and execute
mysqli_stmt_bind_param($stmt, "sssssiss", $name, $company, $email, $job_title, $project, $rating, $testimonial, $attachment_path);

if (mysqli_stmt_execute($stmt)) {
    // Success - redirect with success message
    header('Location: ../../feedback.php?success=1');
} else {
    // Error - redirect with error message
    header('Location: ../../feedback.php?error=submit_failed');
}

// Close statement and connection
mysqli_stmt_close($stmt);
mysqli_close($link);
?>
