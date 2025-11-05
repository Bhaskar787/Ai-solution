<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Include database connection
require_once '../db_connection.php';

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Check if file was uploaded
if (!isset($_FILES['import_file']) || $_FILES['import_file']['error'] !== UPLOAD_ERR_OK) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'File upload error']);
    exit;
}

// Check file type
$file_type = mime_content_type($_FILES['import_file']['tmp_name']);
if ($file_type !== 'text/csv' && $file_type !== 'text/plain') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid file type. Please upload a CSV file.']);
    exit;
}

// Get database connection
$link = get_db_connection();

// Get overwrite option
$overwrite = isset($_POST['overwrite']) ? true : false;

// Open CSV file
$file = fopen($_FILES['import_file']['tmp_name'], 'r');
if (!$file) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Failed to open uploaded file']);
    exit;
}

// Read header row
$header = fgetcsv($file);
if (!$header) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Empty CSV file']);
    exit;
}

// Validate header
$expected_header = ['ID', 'Full Name', 'Email', 'Phone', 'Company', 'Country', 'Job Title', 'Job Details', 'Submission Date', 'Status', 'Priority'];
if ($header !== $expected_header) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid CSV format. Header does not match expected format.']);
    exit;
}

// Prepare statement for inserting data
if ($overwrite) {
    $stmt = mysqli_prepare($link, "INSERT INTO contact_submissions (id, full_name, email, phone, company, country, job_title, job_details, submission_date, status, priority) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE full_name = VALUES(full_name), email = VALUES(email), phone = VALUES(phone), company = VALUES(company), country = VALUES(country), job_title = VALUES(job_title), job_details = VALUES(job_details), submission_date = VALUES(submission_date), status = VALUES(status), priority = VALUES(priority)");
} else {
    $stmt = mysqli_prepare($link, "INSERT INTO contact_submissions (id, full_name, email, phone, company, country, job_title, job_details, submission_date, status, priority) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
}

if (!$stmt) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Failed to prepare statement: ' . mysqli_error($link)]);
    exit;
}

$imported_count = 0;
$error_count = 0;
$errors = [];

// Process each row
while (($row = fgetcsv($file)) !== false) {
    // Skip empty rows
    if (count($row) === 1 && $row[0] === null) {
        continue;
    }
    
    // Validate row data
    if (count($row) !== 11) {
        $error_count++;
        $errors[] = "Row " . ($imported_count + $error_count) . " has incorrect number of columns";
        continue;
    }
    
    // Bind parameters and execute
    mysqli_stmt_bind_param($stmt, "sssssssssss", 
        $row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8], $row[9], $row[10]);
    
    if (mysqli_stmt_execute($stmt)) {
        $imported_count++;
    } else {
        $error_count++;
        $errors[] = "Row " . ($imported_count + $error_count) . ": " . mysqli_stmt_error($stmt);
    }
}

// Close file and statement
fclose($file);
mysqli_stmt_close($stmt);
mysqli_close($link);

// Return response
header('Content-Type: application/json');
if ($error_count === 0) {
    echo json_encode([
        'success' => true, 
        'message' => "Successfully imported $imported_count submissions",
        'imported_count' => $imported_count
    ]);
} else {
    echo json_encode([
        'success' => false, 
        'message' => "Imported $imported_count submissions with $error_count errors",
        'imported_count' => $imported_count,
        'error_count' => $error_count,
        'errors' => $errors
    ]);
}
?>
