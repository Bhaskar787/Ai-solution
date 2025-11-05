<?php
session_start();

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid report ID']);
    exit;
}

$report_id = (int)$_POST['id'];

require_once '../db_connection.php';

$link = get_db_connection();

// Delete report and associated data
$stmt = mysqli_prepare($link, "DELETE FROM reports WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $report_id);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => mysqli_error($link)]);
}

mysqli_stmt_close($stmt);
mysqli_close($link);
?>
