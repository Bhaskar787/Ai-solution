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
$status = isset($_POST['status']) ? trim($_POST['status']) : '';
$priority = isset($_POST['priority']) ? trim($_POST['priority']) : '';

// Validate input
if (empty($id) || empty($status)) {
    echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
    exit;
}

// Validate status value
$valid_statuses = ['new', 'in-progress', 'contacted', 'completed', 'archived'];
if (!in_array($status, $valid_statuses)) {
    echo json_encode(['success' => false, 'message' => 'Invalid status value']);
    exit;
}

// Validate priority value if provided
$valid_priorities = ['low', 'medium', 'high', 'urgent'];
if (!empty($priority) && !in_array($priority, $valid_priorities)) {
    echo json_encode(['success' => false, 'message' => 'Invalid priority value']);
    exit;
}

// Include database connection
require_once '../db_connection.php';

// Get database connection
$link = get_db_connection();

// Prepare statement to update status (and priority if provided)
if (!empty($priority)) {
    // Update both status and priority
    $stmt = mysqli_prepare($link, "UPDATE contact_submissions SET status = ?, priority = ? WHERE id = ?");
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Database error']);
        exit;
    }
    // Bind parameters and execute
    mysqli_stmt_bind_param($stmt, "ssi", $status, $priority, $id);
} else {
    // Update only status
    $stmt = mysqli_prepare($link, "UPDATE contact_submissions SET status = ? WHERE id = ?");
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Database error']);
        exit;
    }
    // Bind parameters and execute
    mysqli_stmt_bind_param($stmt, "si", $status, $id);
}

$success = mysqli_stmt_execute($stmt);

// Check if update was successful
if ($success) {
    $affected_rows = mysqli_stmt_affected_rows($stmt);
    if ($affected_rows > 0) {
        if (!empty($priority)) {
            echo json_encode(['success' => true, 'message' => 'Status and priority updated successfully']);
        } else {
            echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
        }
    } else {
        // No rows were affected, but the query was successful
        // This can happen when updating with the same values
        if (!empty($priority)) {
            echo json_encode(['success' => true, 'message' => 'Status and priority already set to these values']);
        } else {
            echo json_encode(['success' => true, 'message' => 'Status already set to this value']);
        }
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update status']);
}

// Close statement and connection
mysqli_stmt_close($stmt);
mysqli_close($link);
?>
