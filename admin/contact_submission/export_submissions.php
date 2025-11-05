<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../admin_login/admin-login.php');
    exit;
}

// Include database connection
require_once '../db_connection.php';

// Get database connection
$link = get_db_connection();

// Get filter parameters
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$priority_filter = isset($_GET['priority']) ? $_GET['priority'] : '';
$search_filter = isset($_GET['search']) ? $_GET['search'] : '';

// Build query based on filters
$query = "SELECT * FROM contact_submissions WHERE 1=1";
$params = [];
$types = "";

if (!empty($status_filter)) {
    $query .= " AND status = ?";
    $params[] = $status_filter;
    $types .= "s";
}

if (!empty($priority_filter)) {
    $query .= " AND priority = ?";
    $params[] = $priority_filter;
    $types .= "s";
}

if (!empty($search_filter)) {
    $query .= " AND (full_name LIKE ? OR email LIKE ? OR company LIKE ?)";
    $search_param = "%$search_filter%";
    $params = array_merge($params, [$search_param, $search_param, $search_param]);
    $types .= "sss";
}

$query .= " ORDER BY submission_date DESC";

// Prepare and execute query
$stmt = mysqli_prepare($link, $query);
if ($stmt && !empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
if ($stmt && empty($params)) {
    mysqli_stmt_execute($stmt);
} elseif ($stmt) {
    mysqli_stmt_execute($stmt);
}
$result = mysqli_stmt_get_result($stmt);
$submissions = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Close connection
mysqli_close($link);

// Set headers for CSV download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="contact_submissions_' . date('Y-m-d_H-i-s') . '.csv"');

// Create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// Output the column headings
fputcsv($output, ['ID', 'Full Name', 'Email', 'Phone', 'Company', 'Country', 'Job Title', 'Job Details', 'Submission Date', 'Status', 'Priority']);

// Loop through the submissions and output each one as a row
foreach ($submissions as $submission) {
    fputcsv($output, [
        $submission['id'],
        $submission['full_name'],
        $submission['email'],
        $submission['phone'],
        $submission['company'],
        $submission['country'],
        $submission['job_title'],
        $submission['job_details'],
        $submission['submission_date'],
        $submission['status'],
        $submission['priority']
    ]);
}

// Close the file pointer
fclose($output);
exit;
?>
