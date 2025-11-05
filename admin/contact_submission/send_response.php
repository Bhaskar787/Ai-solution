<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get POST data
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$responseType = isset($_POST['response_type']) ? $_POST['response_type'] : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';
$autoUpdateStatus = isset($_POST['auto_update_status']);

// Validate input
if (empty($id) || empty($responseType) || empty($message)) {
    echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
    exit;
}

// Include database connection
require_once '../db_connection.php';

// Get database connection
$link = get_db_connection();

// Get submission details
$stmt = mysqli_prepare($link, "SELECT full_name, email FROM contact_submissions WHERE id = ?");
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Database error']);
    exit;
}

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$row = mysqli_fetch_assoc($result)) {
    echo json_encode(['success' => false, 'message' => 'Submission not found']);
    exit;
}

// Close statement
mysqli_stmt_close($stmt);

// Get user details
$userName = $row['full_name'];
$userEmail = $row['email'];

// Send email using PHPMailer with Gmail SMTP
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once '../../PHPMailer/src/Exception.php';
require_once '../../PHPMailer/src/PHPMailer.php';
require_once '../../PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'baddepartment434@gmail.com';
    $mail->Password = 'ihzqyttvdapipryj';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Recipients
    $mail->setFrom('baddepartment434@gmail.com', 'AI-Solutions Admin');
    $mail->addAddress($userEmail, $userName);

    // Content
    $mail->isHTML(false);
    $mail->Subject = 'Response to Your Contact Submission';
    $mail->Body = $message;

    // Handle attachments
    if (isset($_FILES['attachments']) && !empty($_FILES['attachments']['name'][0])) {
        $attachments = $_FILES['attachments'];
        $fileCount = count($attachments['name']);

        for ($i = 0; $i < $fileCount; $i++) {
            if ($attachments['error'][$i] === UPLOAD_ERR_OK) {
                $mail->addAttachment($attachments['tmp_name'][$i], $attachments['name'][$i]);
            }
        }
    }

    $mail->send();
    $emailSent = true;
} catch (Exception $e) {
    $emailSent = false;
    error_log("Email sending failed: " . $mail->ErrorInfo);
}

// Update status to "In Progress" if requested
if ($autoUpdateStatus) {
    $stmt = mysqli_prepare($link, "UPDATE contact_submissions SET status = 'in-progress' WHERE id = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

// Save reply message in messages table
if ($emailSent) {
    // Generate conversation_id based on user email
    $conversation_id = md5(strtolower($userEmail));
    $sender_type = 'admin';
    $attachment = NULL; // For simplicity, attachments not saved in DB here
    $created_at = date('Y-m-d H:i:s');

    $stmt = mysqli_prepare($link, "INSERT INTO messages (conversation_id, sender_type, message, attachment, is_read, created_at) VALUES (?, ?, ?, ?, 1, ?)");
    mysqli_stmt_bind_param($stmt, "sssss", $conversation_id, $sender_type, $message, $attachment, $created_at);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Update conversations table last_message and last_message_time
    $stmt = mysqli_prepare($link, "UPDATE conversations SET last_message = ?, last_message_time = ?, unread_count = 0 WHERE conversation_id = ?");
    mysqli_stmt_bind_param($stmt, "sss", $message, $created_at, $conversation_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    mysqli_close($link);

    echo json_encode(['success' => true, 'message' => 'Response sent successfully']);
} else {
    mysqli_close($link);
    echo json_encode(['success' => false, 'message' => 'Failed to send response email']);
}
?>
