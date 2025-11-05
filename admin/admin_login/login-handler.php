<?php
// Enable error reporting for debugging (disable display_errors to prevent HTML output)
error_reporting(E_ALL);
ini_set('display_errors', 0);

// Set JSON header
header('Content-Type: application/json');

// Include database connection
require_once '../db_connection.php';

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get POST data
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';

// Validate input
if (empty($username) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Username and password are required']);
    exit;
}

// Get database connection
$link = get_db_connection();
if (!$link) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// Prepare statement to prevent SQL injection
$stmt = mysqli_prepare($link, "SELECT id, username, password FROM admin WHERE username = ?");
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Database error']);
    exit;
}

// Bind parameters and execute
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);

// Get result
$result = mysqli_stmt_get_result($stmt);

// Check if user exists
if ($row = mysqli_fetch_assoc($result)) {
    // Verify password using password_verify (assuming password is hashed)
    if (password_verify($password, $row['password'])) {
        // Login successful - start session and set session variables
        session_start();
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $row['username'];
        $_SESSION['admin_id'] = $row['id'];
        echo json_encode(['success' => true, 'message' => 'Login successful']);
    } else {
        // Invalid password
        echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
    }
} else {
    // User not found
    echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
}

// Close statement and connection
mysqli_stmt_close($stmt);
mysqli_close($link);
?>
