<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'dingores_inventory');
define('DB_USER', 'dingores_admin');
define('DB_PASS', 'password1298@'); // Set your MySQL root password here

// Disable detailed error reporting in production
if (getenv('APP_ENV') === 'production') {
    error_reporting(0);
    ini_set('display_errors', '0');
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
}

// Create a new PDO connection
try {
    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    // Set error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Set default fetch mode
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

/**
 * Get a mysqli database connection
 *
 * @return mysqli
 */
function get_db_connection() {
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($mysqli->connect_error) {
        // Throw exception instead of die to avoid HTML output
        throw new Exception("MySQL connection failed: " . $mysqli->connect_error);
    }
    return $mysqli;
}
