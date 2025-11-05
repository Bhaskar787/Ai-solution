<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get POST data
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

// Validate input
if (!$id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid feedback ID']);
    exit;
}

// Include database connection
require_once '../db_connection.php';

// Get database connection
$link = get_db_connection();

// First, get the attachment path to delete the file
$query = "SELECT attachment FROM feedback_submissions WHERE id = ?";
$stmt = mysqli_prepare($link, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$feedback = mysqli_fetch_assoc($result);

// Delete the feedback record
$delete_query = "DELETE FROM feedback_submissions WHERE id = ?";
$delete_stmt = mysqli_prepare($link, $delete_query);
mysqli_stmt_bind_param($delete_stmt, "i", $id);

if (mysqli_stmt_execute($delete_stmt)) {
    // Check if any row was affected
    if (mysqli_stmt_affected_rows($delete_stmt) > 0) {
        // Delete attachment file if it exists
        if ($feedback && $feedback['attachment'] && file_exists($feedback['attachment'])) {
            unlink($feedback['attachment']);
        }

        echo json_encode(['success' => true, 'message' => 'Feedback deleted successfully']);
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Feedback not found']);
    }
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error']);
}

// Close connections
mysqli_stmt_close($stmt);
mysqli_stmt_close($delete_stmt);
mysqli_close($link);
?>
