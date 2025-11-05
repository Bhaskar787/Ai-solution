<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

require_once '../db_connection.php';

$link = get_db_connection();

$conversation_id = isset($_POST['conversation_id']) ? $_POST['conversation_id'] : '';

if (empty($conversation_id)) {
    echo json_encode(['success' => false, 'message' => 'Missing conversation_id']);
    exit;
}

// Check if conversation exists, if not create it from contact submission
$stmt = mysqli_prepare($link, "SELECT COUNT(*) as count FROM conversations WHERE conversation_id = ?");
mysqli_stmt_bind_param($stmt, "s", $conversation_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$exists = mysqli_fetch_assoc($result)['count'] > 0;
mysqli_stmt_close($stmt);

if (!$exists) {
    // Check if it's a contact submission
    $stmt = mysqli_prepare($link, "SELECT email, full_name, job_details, submission_date FROM contact_submissions WHERE MD5(LOWER(email)) = ?");
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
        $created_at = $contact_row['submission_date'];

        $stmt = mysqli_prepare($link, "INSERT INTO conversations (conversation_id, user_name, user_email, last_message, last_message_time, unread_count, status) VALUES (?, ?, ?, ?, ?, 1, 'active')");
        mysqli_stmt_bind_param($stmt, "sssss", $conversation_id, $user_name, $user_email, $last_message, $created_at);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Also save the contact submission as the first message
        $stmt = mysqli_prepare($link, "INSERT INTO messages (conversation_id, sender_type, message, is_read, created_at) VALUES (?, 'user', ?, 1, ?)");
        mysqli_stmt_bind_param($stmt, "sss", $conversation_id, $last_message, $created_at);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

// Fetch messages
$stmt = mysqli_prepare($link, "SELECT * FROM messages WHERE conversation_id = ? ORDER BY created_at ASC");
mysqli_stmt_bind_param($stmt, "s", $conversation_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$messages = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

// Mark messages as read
$stmt = mysqli_prepare($link, "UPDATE messages SET is_read = 1 WHERE conversation_id = ? AND sender_type = 'user'");
mysqli_stmt_bind_param($stmt, "s", $conversation_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

// Update unread count in conversations
$stmt = mysqli_prepare($link, "UPDATE conversations SET unread_count = 0 WHERE conversation_id = ?");
mysqli_stmt_bind_param($stmt, "s", $conversation_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

mysqli_close($link);

echo json_encode(['success' => true, 'messages' => $messages]);
?>
