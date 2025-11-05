<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../admin_login/admin-login.php');
    exit;
}

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get event ID from POST data
$event_id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

if (!$event_id) {
    echo json_encode(['success' => false, 'message' => 'Invalid event ID']);
    exit;
}

// Include database connection
require_once '../db_connection.php';

// Get database connection
$link = get_db_connection();

// First, get the event data to check if image exists
$stmt = mysqli_prepare($link, "SELECT image_path FROM events WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $event_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$event = mysqli_fetch_assoc($result);

if (!$event) {
    echo json_encode(['success' => false, 'message' => 'Event not found']);
    mysqli_close($link);
    exit;
}

// Delete the event from database
$stmt = mysqli_prepare($link, "DELETE FROM events WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $event_id);

if (mysqli_stmt_execute($stmt)) {
    // If event had an image, delete the file
    if (!empty($event['image_path']) && file_exists('../' . $event['image_path'])) {
        unlink('../' . $event['image_path']);
    }

    echo json_encode(['success' => true, 'message' => 'Event deleted successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to delete event']);
}

// Close statement and connection
mysqli_stmt_close($stmt);
mysqli_close($link);
?>
