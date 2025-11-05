<?php
require_once '../db_connection.php';

$link = get_db_connection();

// IMAP settings for Gmail
$hostname = '{imap.gmail.com:993/imap/ssl}INBOX';
$username = 'baddepartment434@gmail.com';
$password = 'ihzqyttvdapipryj';

// Connect to IMAP
$inbox = imap_open($hostname, $username, $password) or die('Cannot connect to Gmail: ' . imap_last_error());

// Get all emails that are not seen (unread)
$emails = imap_search($inbox, 'UNSEEN');

if ($emails) {
    // Sort emails from oldest to newest
    rsort($emails);

    foreach ($emails as $email_number) {
        // Fetch email header
        $header = imap_headerinfo($inbox, $email_number);
        $from = $header->from[0];
        $sender_email = $from->mailbox . '@' . $from->host;
        $sender_name = isset($from->personal) ? $from->personal : $sender_email;
        $subject = isset($header->subject) ? imap_utf8($header->subject) : 'No Subject';
        $date = date('Y-m-d H:i:s', strtotime($header->date));

        // Generate conversation ID based on sender email
        $conversation_id = md5(strtolower($sender_email));

        // Fetch email body and handle attachments
        $structure = imap_fetchstructure($inbox, $email_number);
        $body = '';
        $attachment_path = null;

        if ($structure->type == 1) {
            // Multipart message - handle both text and attachments
            $body = getTextBody($inbox, $email_number, $structure);
            $attachment_path = saveAttachments($inbox, $email_number, $structure, $conversation_id);
        } else {
            // Single part message
            $body = imap_body($inbox, $email_number);
            if ($structure->encoding == 4) {
                $body = quoted_printable_decode($body);
            } elseif ($structure->encoding == 3) {
                $body = base64_decode($body);
            }
        }

        // Clean up the body
        $body = imap_utf8($body);
        $body = strip_tags($body); // Remove HTML tags
        $body = trim($body);

        // Check if conversation exists
        $stmt = mysqli_prepare($link, "SELECT COUNT(*) as count FROM conversations WHERE conversation_id = ?");
        mysqli_stmt_bind_param($stmt, "s", $conversation_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $exists = mysqli_fetch_assoc($result)['count'] > 0;
        mysqli_stmt_close($stmt);

        if (!$exists) {
            // Create new conversation
            $stmt = mysqli_prepare($link, "INSERT INTO conversations (conversation_id, user_name, user_email, last_message, last_message_time, unread_count, status) VALUES (?, ?, ?, ?, ?, 1, 'active')");
            mysqli_stmt_bind_param($stmt, "sssss", $conversation_id, $sender_name, $sender_email, $body, $date);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        } else {
            // Update existing conversation
            $stmt = mysqli_prepare($link, "UPDATE conversations SET last_message = ?, last_message_time = ?, unread_count = unread_count + 1 WHERE conversation_id = ?");
            mysqli_stmt_bind_param($stmt, "sss", $body, $date, $conversation_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }

        // Store message in database
        $stmt = mysqli_prepare($link, "INSERT INTO messages (conversation_id, sender_type, message, is_read, created_at) VALUES (?, 'user', ?, 0, ?)");
        mysqli_stmt_bind_param($stmt, "sss", $conversation_id, $body, $date);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Save attachment path in database linked to the message if any
        if ($attachment_path) {
            $stmt = mysqli_prepare($link, "UPDATE messages SET attachment = ? WHERE conversation_id = ? ORDER BY created_at DESC LIMIT 1");
            mysqli_stmt_bind_param($stmt, "ss", $attachment_path, $conversation_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }

        // Mark email as read in Gmail to avoid processing again
        imap_setflag_full($inbox, $email_number, "\\Seen");
    }
}

// Close IMAP connection
imap_close($inbox);

mysqli_close($link);

echo "Email fetching completed successfully.";

// Helper function to get text body from multipart email
function getTextBody($inbox, $email_number, $structure) {
    $body = '';
    foreach ($structure->parts as $part_number => $part) {
        if ($part->type == 0) { // text
            $part_body = imap_fetchbody($inbox, $email_number, $part_number + 1);
            if ($part->encoding == 4) {
                $part_body = quoted_printable_decode($part_body);
            } elseif ($part->encoding == 3) {
                $part_body = base64_decode($part_body);
            }
            $body .= $part_body;
        }
    }
    return $body;
}

// Helper function to save attachments from multipart email
function saveAttachments($inbox, $email_number, $structure, $conversation_id) {
    $upload_dir = __DIR__ . '/../uploads/attachments/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    foreach ($structure->parts as $part_number => $part) {
        if ($part->ifdparameters) {
            foreach ($part->dparameters as $object) {
                if (strtolower($object->attribute) == 'filename') {
                    $filename = $object->value;
                    $attachment = imap_fetchbody($inbox, $email_number, $part_number + 1);
                    if ($part->encoding == 3) {
                        $attachment = base64_decode($attachment);
                    } elseif ($part->encoding == 4) {
                        $attachment = quoted_printable_decode($attachment);
                    }
                    $safe_filename = preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $filename);
                    $filepath = $upload_dir . $safe_filename;
                    file_put_contents($filepath, $attachment);
                    // Return relative path for storage in DB
                    return 'admin/uploads/attachments/' . $safe_filename;
                }
            }
        }
    }
    return null;
}
