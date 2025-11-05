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
$status = isset($_POST['status']) ? trim($_POST['status']) : '';
$priority = isset($_POST['priority']) ? trim($_POST['priority']) : '';

// Validate input
if (empty($ids) || !is_array($ids)) {
    echo json_encode(['success' => false, 'message' => 'Missing or invalid submission IDs']);
    exit;
}

if (empty($status) && empty($priority)) {
    echo json_encode(['success' => false, 'message' => 'Status or priority must be provided']);
    exit;
}

// Validate status value if provided
if (!empty($status)) {
    $valid_statuses = ['new', 'in-progress', 'contacted', 'completed', 'archived'];
    if (!in_array($status, $valid_statuses)) {
        echo json_encode(['success' => false, 'message' => 'Invalid status value']);
        exit;
    }
}

// Validate priority value if provided
if (!empty($priority)) {
    $valid_priorities = ['low', 'medium', 'high', 'urgent'];
    if (!in_array($priority, $valid_priorities)) {
        echo json_encode(['success' => false, 'message' => 'Invalid priority value']);
        exit;
    }
}

// Include database connection
require_once '../db_connection.php';

// Get database connection
$link = get_db_connection();

// Build query based on provided fields
$query = "UPDATE contact_submissions SET ";
$params = [];
$types = "";

if (!empty($status)) {
    $query .= "status = ?, ";
    $params[] = $status;
    $types .= "s";
}

if (!empty($priority)) {
    $query .= "priority = ?, ";
    $params[] = $priority;
    $types .= "s";
}

// Remove trailing comma and space
$query = rtrim($query, ", ");

// Add WHERE clause
$placeholders = str_repeat('?,', count($ids) - 1) . '?';
$query .= " WHERE id IN ($placeholders)";
$types .= str_repeat('i', count($ids));

// Merge parameters
$params = array_merge($params, $ids);

// Prepare statement to prevent SQL injection
$stmt = mysqli_prepare($link, $query);
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Database error']);
    exit;
}

// Bind parameters and execute
mysqli_stmt_bind_param($stmt, $types, ...$params);
$success = mysqli_stmt_execute($stmt);

// Check if update was successful
if ($success && mysqli_stmt_affected_rows($stmt) > 0) {
    echo json_encode(['success' => true, 'message' => 'Submissions updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update submissions']);
}

// Close statement and connection
mysqli_stmt_close($stmt);
mysqli_close($link);
?>
