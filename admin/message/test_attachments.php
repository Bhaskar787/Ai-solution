<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../admin_login/admin-login.php');
    exit;
}

require_once '../db_connection.php';

$link = get_db_connection();

// Test database structure
echo "<h1>Attachment Handling Test</h1>";

// Check if messages table has attachment column
$result = mysqli_query($link, "DESCRIBE messages");
$has_attachment = false;
while ($row = mysqli_fetch_assoc($result)) {
    if ($row['Field'] === 'attachment') {
        $has_attachment = true;
        break;
    }
}

echo "<h2>Database Structure Check</h2>";
echo "Messages table has attachment column: " . ($has_attachment ? "YES" : "NO") . "<br>";

// Check upload directory
$upload_dir = __DIR__ . '../admin/uploads/attachment/';
$dir_exists = is_dir($upload_dir);
$dir_writable = is_writable($upload_dir);

echo "<h2>Upload Directory Check</h2>";
echo "Upload directory exists: " . ($dir_exists ? "YES" : "NO") . "<br>";
echo "Upload directory writable: " . ($dir_writable ? "YES" : "NO") . "<br>";

// Test file upload form
echo "<h2>Test File Upload</h2>";
echo "<form method='POST' enctype='multipart/form-data'>";
echo "<input type='file' name='test_file' accept='.jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.txt'><br>";
echo "<input type='submit' value='Test Upload'>";
echo "</form>";

// Handle test upload
if (isset($_FILES['test_file']) && $_FILES['test_file']['error'] == UPLOAD_ERR_OK) {
    $file_name = $_FILES['test_file']['name'];
    $file_tmp = $_FILES['test_file']['tmp_name'];
    $file_size = $_FILES['test_file']['size'];
    $file_type = $_FILES['test_file']['type'];

    echo "<h3>Upload Test Results</h3>";
    echo "File name: $file_name<br>";
    echo "File size: $file_size bytes<br>";
    echo "File type: $file_type<br>";

    // Validate file
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'text/plain'];
    $max_size = 10 * 1024 * 1024; // 10MB

    $type_valid = in_array($file_type, $allowed_types);
    $size_valid = $file_size <= $max_size;

    echo "File type valid: " . ($type_valid ? "YES" : "NO") . "<br>";
    echo "File size valid: " . ($size_valid ? "YES" : "NO") . "<br>";

    if ($type_valid && $size_valid) {
        // Generate safe filename
        $safe_filename = preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $file_name);
        $unique_filename = time() . '_' . $safe_filename;
        $filepath = $upload_dir . $unique_filename;

        if (move_uploaded_file($file_tmp, $filepath)) {
            echo "File uploaded successfully: $unique_filename<br>";
            echo "Full path: $filepath<br>";
            echo "Relative path: admin/uploads/attachments/$unique_filename<br>";

            // Test database insertion
            $test_conversation_id = 'test_' . time();
            $stmt = mysqli_prepare($link, "INSERT INTO messages (conversation_id, sender_type, message, attachment, is_read, created_at) VALUES (?, 'admin', 'Test message with attachment', ?, 1, NOW())");
            $attachment_path = 'admin/uploads/attachments/' . $unique_filename;
            mysqli_stmt_bind_param($stmt, "ss", $test_conversation_id, $attachment_path);
            if (mysqli_stmt_execute($stmt)) {
                echo "Database insertion successful<br>";
                $inserted_id = mysqli_insert_id($link);

                // Test retrieval
                $stmt = mysqli_prepare($link, "SELECT attachment FROM messages WHERE id = ?");
                mysqli_stmt_bind_param($stmt, "i", $inserted_id);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $row = mysqli_fetch_assoc($result);
                echo "Retrieved attachment path: " . $row['attachment'] . "<br>";

                // Clean up test data
                mysqli_query($link, "DELETE FROM messages WHERE conversation_id = '$test_conversation_id'");
                unlink($filepath);
                echo "Test cleanup completed<br>";
            } else {
                echo "Database insertion failed: " . mysqli_error($link) . "<br>";
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "File upload failed<br>";
        }
    }
}

mysqli_close($link);
?>
