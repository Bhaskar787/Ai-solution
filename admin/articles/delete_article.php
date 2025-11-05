<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../admin_login/admin-login.php');
    exit;
}

// Include database connection
require_once '../db_connection.php';

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: manage_articles.php');
    exit;
}

// Get article ID
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid article ID']);
    exit;
}

// Delete article
$link = get_db_connection();
$stmt = mysqli_prepare($link, "DELETE FROM articles WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => true, 'message' => 'Article deleted successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to delete article: ' . mysqli_error($link)]);
}

mysqli_stmt_close($stmt);
mysqli_close($link);
?>
