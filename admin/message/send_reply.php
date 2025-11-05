<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

require_once '../db_connection.php';

$link = get_db_connection();

$conversation_id = isset($_POST['conversation_id']) ? $_POST['conversation_id'] : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

if (empty($conversation_id) || empty($message)) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

// Handle file upload
$attachment_path = null;
if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == UPLOAD_ERR_OK) {
    $upload_dir = __DIR__ . '/../uploads/attachments/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $file_name = $_FILES['attachment']['name'];
    $file_tmp = $_FILES['attachment']['tmp_name'];
    $file_size = $_FILES['attachment']['size'];
    $file_type = $_FILES['attachment']['type'];

    // Validate file
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'text/plain'];
    $max_size = 10 * 1024 * 1024; // 10MB

    if (!in_array($file_type, $allowed_types)) {
        echo json_encode(['success' => false, 'message' => 'Invalid file type. Only images, PDF, DOC, DOCX, and TXT files are allowed.']);
        exit;
    }

    if ($file_size > $max_size) {
        echo json_encode(['success' => false, 'message' => 'File size too large. Maximum size is 10MB.']);
        exit;
    }

    // Generate safe filename
    $safe_filename = preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $file_name);
    $unique_filename = time() . '_' . $safe_filename;
    $filepath = $upload_dir . $unique_filename;

    if (move_uploaded_file($file_tmp, $filepath)) {
        $attachment_path = 'admin/uploads/attachments/' . $unique_filename;
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to save uploaded file.']);
        exit;
    }
}

// Get user email from conversations table
$stmt = mysqli_prepare($link, "SELECT user_email FROM conversations WHERE conversation_id = ?");
mysqli_stmt_bind_param($stmt, "s", $conversation_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
$user_email = $row ? $row['user_email'] : null;
mysqli_stmt_close($stmt);

// If conversation doesn't exist, check if it's a contact submission
if (!$user_email) {
    $stmt = mysqli_prepare($link, "SELECT email, full_name, job_details FROM contact_submissions WHERE MD5(LOWER(email)) = ?");
    mysqli_stmt_bind_param($stmt, "s", $conversation_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $contact_row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if ($contact_row) {
        // Create conversation for this contact submission
        $user_email = $contact_row['email'];
        $user_name = $contact_row['full_name'];
        $last_message = $contact_row['job_details'];
        $created_at = date('Y-m-d H:i:s');

        $stmt = mysqli_prepare($link, "INSERT INTO conversations (conversation_id, user_name, user_email, last_message, last_message_time, unread_count, status) VALUES (?, ?, ?, ?, ?, 1, 'active')");
        mysqli_stmt_bind_param($stmt, "sssss", $conversation_id, $user_name, $user_email, $last_message, $created_at);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Also save the contact submission as the first message
        $stmt = mysqli_prepare($link, "INSERT INTO messages (conversation_id, sender_type, message, is_read, created_at) VALUES (?, 'user', ?, 1, ?)");
        mysqli_stmt_bind_param($stmt, "sss", $conversation_id, $last_message, $created_at);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    } else {
        echo json_encode(['success' => false, 'message' => 'Conversation not found']);
        exit;
    }
}

// Set content type header for JSON response to avoid HTML output interfering with JSON
header('Content-Type: application/json; charset=utf-8');

// Send email using PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once '../../PHPMailer/src/Exception.php';
require_once '../../PHPMailer/src/PHPMailer.php';
require_once '../../PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'baddepartment434@gmail.com';
    $mail->Password = 'ihzqyttvdapipryj';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('baddepartment434@gmail.com', 'AI-Solutions Admin');
    $mail->addAddress($user_email);

    $mail->isHTML(false);
    $mail->Subject = 'Response from AI-Solutions Admin';
    $mail->Body = $message;

    // Add attachment if uploaded
    if ($attachment_path) {
        $full_path = __DIR__ . '/../uploads/attachments/' . basename($attachment_path);
        if (file_exists($full_path)) {
            $mail->addAttachment($full_path);
        }
    }

    $mail->send();
    $emailSent = true;
} catch (Exception $e) {
    $emailSent = false;
    error_log("Email sending failed: " . $mail->ErrorInfo);
}

if ($emailSent) {
    // Save message in DB
    $sender_type = 'admin';
    $attachment = $attachment_path;
    $created_at = date('Y-m-d H:i:s');

    $stmt = mysqli_prepare($link, "INSERT INTO messages (conversation_id, sender_type, message, attachment, is_read, created_at) VALUES (?, ?, ?, ?, 1, ?)");
    mysqli_stmt_bind_param($stmt, "sssss", $conversation_id, $sender_type, $message, $attachment, $created_at);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Update conversations table
    $stmt = mysqli_prepare($link, "UPDATE conversations SET last_message = ?, last_message_time = ? WHERE conversation_id = ?");
    mysqli_stmt_bind_param($stmt, "sss", $message, $created_at, $conversation_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    mysqli_close($link);
    echo json_encode(['success' => true, 'message' => 'Reply sent successfully']);
} else {
    mysqli_close($link);
    echo json_encode(['success' => false, 'message' => 'Failed to send email']);
}
?>
