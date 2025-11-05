<?php
// admin/message/messages.php
// Fetch incoming emails from Gmail inbox using IMAP and store in messages and conversations tables

session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

require_once '../db_connection.php';

$link = get_db_connection();

// Gmail IMAP connection settings
$hostname = '{imap.gmail.com:993/imap/ssl}INBOX';
$username = 'baddepartment434@gmail.com';
$password = 'ihzqyttvdapipryj';

// Connect to IMAP
$inbox = imap_open($hostname, $username, $password) or die('Cannot connect to Gmail: ' . imap_last_error());

// Search for unseen emails
$emails = imap_search($inbox, 'UNSEEN');

if ($emails) {
    rsort($emails); // Process newest first

    foreach ($emails as $email_number) {
        $overview = imap_fetch_overview($inbox, $email_number, 0)[0];
        $structure = imap_fetchstructure($inbox, $email_number);

        $message_id = $overview->message_id ?? uniqid('msg_', true);
        $from = $overview->from ?? '';
        $subject = $overview->subject ?? '';
        $date = date('Y-m-d H:i:s', strtotime($overview->date));

        // Parse from email address
        preg_match('/<(.+)>/', $from, $matches);
        $from_email = $matches[1] ?? $from;

        // Fetch body (plain text preferred)
        $body = '';
        if (!isset($structure->parts)) {
            $body = imap_body($inbox, $email_number);
        } else {
            foreach ($structure->parts as $part_number => $part) {
                if ($part->type == 0) { // text
                    $body = imap_fetchbody($inbox, $email_number, $part_number + 1);
                    if ($part->encoding == 3) {
                        $body = base64_decode($body);
                    } elseif ($part->encoding == 4) {
                        $body = quoted_printable_decode($body);
                    }
                    break;
                }
            }
        }
        $body = trim($body);

        // Generate conversation_id based on from_email (simple approach)
        $conversation_id = md5(strtolower($from_email));

        // Check if conversation exists
        $stmt = mysqli_prepare($link, "SELECT id FROM conversations WHERE conversation_id = ?");
        mysqli_stmt_bind_param($stmt, "s", $conversation_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $conversation_exists = mysqli_stmt_num_rows($stmt) > 0;
        mysqli_stmt_close($stmt);

        if (!$conversation_exists) {
            // Insert new conversation
            $stmt = mysqli_prepare($link, "INSERT INTO conversations (conversation_id, user_name, user_email, last_message, last_message_time, unread_count, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $user_name = $from; // Use full from string as name
            $unread_count = 1;
            $status = 'active';
            mysqli_stmt_bind_param($stmt, "sssssis", $conversation_id, $user_name, $from_email, $subject, $date, $unread_count, $status);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        } else {
            // Update existing conversation
            $stmt = mysqli_prepare($link, "UPDATE conversations SET last_message = ?, last_message_time = ?, unread_count = unread_count + 1 WHERE conversation_id = ?");
            mysqli_stmt_bind_param($stmt, "sss", $subject, $date, $conversation_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }

        // Insert message
        $sender_type = 'user';
        $attachment = NULL; // For simplicity, attachments not handled here
        $stmt = mysqli_prepare($link, "INSERT INTO messages (conversation_id, sender_type, message, attachment, is_read, created_at) VALUES (?, ?, ?, ?, 0, ?)");
        mysqli_stmt_bind_param($stmt, "sssss", $conversation_id, $sender_type, $body, $attachment, $date);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Mark email as seen
        imap_setflag_full($inbox, $email_number, "\\Seen");
    }
}

imap_close($inbox);

echo json_encode(['success' => true, 'message' => 'Emails fetched and stored successfully']);
?>
