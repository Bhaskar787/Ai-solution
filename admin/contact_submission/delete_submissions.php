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
$ids = isset($_POST['ids']) ? $_POST['ids'] : [];

// Validate input
if (empty($ids) || !is_array($ids)) {
    echo json_encode(['success' => false, 'message' => 'Missing or invalid submission IDs']);
    exit;
}

// Include database connection
require_once '../db_connection.php';

// Get database connection
$link = get_db_connection();

// Prepare statement to prevent SQL injection
$placeholders = str_repeat('?,', count($ids) - 1) . '?';
$stmt = mysqli_prepare($link, "DELETE FROM contact_submissions WHERE id IN ($placeholders)");
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Database error']);
    exit;
}

// Bind parameters and execute
$types = str_repeat('i', count($ids));
mysqli_stmt_bind_param($stmt, $types, ...$ids);
$success = mysqli_stmt_execute($stmt);

// Check if delete was successful
if ($success && mysqli_stmt_affected_rows($stmt) > 0) {
    echo json_encode(['success' => true, 'message' => 'Submissions deleted successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to delete submissions']);
}

// Close statement and connection
mysqli_stmt_close($stmt);
mysqli_close($link);
?>
