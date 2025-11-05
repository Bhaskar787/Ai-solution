<?php
// Include database connection
require_once '../db_connection.php';

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../contact.php?error=invalid_request');
    exit;
}

// Get POST data and sanitize
$fullName = isset($_POST['fullName']) ? trim($_POST['fullName']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$company = isset($_POST['company']) ? trim($_POST['company']) : '';
$country = isset($_POST['country']) ? trim($_POST['country']) : '';
$jobTitle = isset($_POST['jobTitle']) ? trim($_POST['jobTitle']) : '';
$jobDetails = isset($_POST['jobDetails']) ? trim($_POST['jobDetails']) : '';

// Validate required fields
if (empty($fullName) || empty($email) || empty($phone) || empty($company) || empty($country) || empty($jobTitle) || empty($jobDetails)) {
    header('Location: ../contact.php?error=missing_fields');
    exit;
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ../contact.php?error=invalid_email');
    exit;
}

// Get database connection
$link = get_db_connection();

// Prepare statement to prevent SQL injection
$stmt = mysqli_prepare($link, "INSERT INTO contact_submissions (full_name, email, phone, company, country, job_title, job_details) VALUES (?, ?, ?, ?, ?, ?, ?)");
if (!$stmt) {
    header('Location: ../contact.php?error=db_error');
    exit;
}

// Bind parameters and execute
mysqli_stmt_bind_param($stmt, "sssssss", $fullName, $email, $phone, $company, $country, $jobTitle, $jobDetails);

if (mysqli_stmt_execute($stmt)) {
    // Also insert into messages and conversations tables for chat system
    $conversation_id = md5(strtolower($email));
    $sender_type = 'user';
    $message = $jobDetails;
    $attachment = NULL;
    $created_at = date('Y-m-d H:i:s');

    // Check if conversation exists
    $check_stmt = mysqli_prepare($link, "SELECT id FROM conversations WHERE conversation_id = ?");
    mysqli_stmt_bind_param($check_stmt, "s", $conversation_id);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);
    $conversation_exists = mysqli_stmt_num_rows($check_stmt) > 0;
    mysqli_stmt_close($check_stmt);

    if (!$conversation_exists) {
        // Insert new conversation
        $conv_stmt = mysqli_prepare($link, "INSERT INTO conversations (conversation_id, user_name, user_email, last_message, last_message_time, unread_count, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $user_name = $fullName;
        $unread_count = 1;
        $status = 'active';
        mysqli_stmt_bind_param($conv_stmt, "sssssis", $conversation_id, $user_name, $email, $message, $created_at, $unread_count, $status);
        mysqli_stmt_execute($conv_stmt);
        mysqli_stmt_close($conv_stmt);
    } else {
        // Update existing conversation
        $update_stmt = mysqli_prepare($link, "UPDATE conversations SET last_message = ?, last_message_time = ?, unread_count = unread_count + 1 WHERE conversation_id = ?");
        mysqli_stmt_bind_param($update_stmt, "sss", $message, $created_at, $conversation_id);
        mysqli_stmt_execute($update_stmt);
        mysqli_stmt_close($update_stmt);
    }

    // Insert message
    $msg_stmt = mysqli_prepare($link, "INSERT INTO messages (conversation_id, sender_type, message, attachment, is_read, created_at) VALUES (?, ?, ?, ?, 0, ?)");
    mysqli_stmt_bind_param($msg_stmt, "sssss", $conversation_id, $sender_type, $message, $attachment, $created_at);
    mysqli_stmt_execute($msg_stmt);
    mysqli_stmt_close($msg_stmt);

    // Success - redirect with success message
    header('Location: ../contact.php?success=1');
} else {
    // Error - redirect with error message
    header('Location: ../contact.php?error=submit_failed');
}

// Close statement and connection
mysqli_stmt_close($stmt);
mysqli_close($link);
?>
