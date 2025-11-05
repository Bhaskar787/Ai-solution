<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo 'Unauthorized access';
    exit;
}

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo 'Invalid request method';
    exit;
}

// Get POST data
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

// Validate input
if (empty($id)) {
    echo 'Missing required parameters';
    exit;
}

// Include database connection
require_once '../db_connection.php';

// Get database connection
$link = get_db_connection();

// Prepare statement to prevent SQL injection
$stmt = mysqli_prepare($link, "SELECT * FROM contact_submissions WHERE id = ?");
if (!$stmt) {
    echo 'Database error';
    exit;
}

// Bind parameters and execute
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

// Get result
$result = mysqli_stmt_get_result($stmt);

// Check if submission exists
if ($row = mysqli_fetch_assoc($result)) {
    // Format the submission date
    $submission_date = date('F j, Y \a\t g:i A', strtotime($row['submission_date']));
    
    // Output the HTML for the modal
    echo '<div class="space-y-4">';
    echo '<div class="grid grid-cols-1 md:grid-cols-2 gap-4">';
    echo '<div>';
    echo '<h4 class="font-semibold text-gray-900">Full Name</h4>';
    echo '<p class="text-gray-700">' . htmlspecialchars($row['full_name']) . '</p>';
    echo '</div>';
    echo '<div>';
    echo '<h4 class="font-semibold text-gray-900">Email</h4>';
    echo '<p class="text-gray-700">' . htmlspecialchars($row['email']) . '</p>';
    echo '</div>';
    echo '<div>';
    echo '<h4 class="font-semibold text-gray-900">Phone</h4>';
    echo '<p class="text-gray-700">' . htmlspecialchars($row['phone']) . '</p>';
    echo '</div>';
    echo '<div>';
    echo '<h4 class="font-semibold text-gray-900">Company</h4>';
    echo '<p class="text-gray-700">' . htmlspecialchars($row['company']) . '</p>';
    echo '</div>';
    echo '<div>';
    echo '<h4 class="font-semibold text-gray-900">Country</h4>';
    echo '<p class="text-gray-700">' . htmlspecialchars($row['country']) . '</p>';
    echo '</div>';
    echo '<div>';
    echo '<h4 class="font-semibold text-gray-900">Job Title</h4>';
    echo '<p class="text-gray-700">' . htmlspecialchars($row['job_title']) . '</p>';
    echo '</div>';
    echo '<div>';
    echo '<h4 class="font-semibold text-gray-900">Submission Date</h4>';
    echo '<p class="text-gray-700">' . $submission_date . '</p>';
    echo '</div>';
    echo '<div>';
    echo '<h4 class="font-semibold text-gray-900">Status</h4>';
    echo '<p><span class="status-badge status-' . $row['status'] . '">' . ucfirst(str_replace('-', ' ', $row['status'])) . '</span></p>';
    echo '</div>';
    echo '<div>';
    echo '<h4 class="font-semibold text-gray-900">Priority</h4>';
    echo '<p><span class="priority-badge priority-' . $row['priority'] . '">' . ucfirst($row['priority']) . '</span></p>';
    echo '</div>';
    echo '</div>';
    echo '<div class="mt-4">';
    echo '<h4 class="font-semibold text-gray-900">Project Details</h4>';
    echo '<p class="text-gray-700 whitespace-pre-wrap">' . htmlspecialchars($row['job_details']) . '</p>';
    echo '</div>';
    
    // Update Status Section
    echo '<div class="mt-4">';
    echo '<h4 class="font-semibold text-gray-900">Update Status</h4>';
    echo '<div class="flex items-center space-x-4 mt-2">';
    echo '<select id="update-status-' . $id . '" class="border border-gray-300 rounded px-3 py-2">';
    echo '<option value="new" ' . ($row['status'] === 'new' ? 'selected' : '') . '>New</option>';
    echo '<option value="in-progress" ' . ($row['status'] === 'in-progress' ? 'selected' : '') . '>In Progress</option>';
    echo '<option value="contacted" ' . ($row['status'] === 'contacted' ? 'selected' : '') . '>Contacted</option>';
    echo '<option value="completed" ' . ($row['status'] === 'completed' ? 'selected' : '') . '>Completed</option>';
    echo '<option value="archived" ' . ($row['status'] === 'archived' ? 'selected' : '') . '>Archived</option>';
    echo '</select>';
    echo '<select id="update-priority-' . $id . '" class="border border-gray-300 rounded px-3 py-2">';
    echo '<option value="low" ' . ($row['priority'] === 'low' ? 'selected' : '') . '>Low</option>';
    echo '<option value="medium" ' . ($row['priority'] === 'medium' ? 'selected' : '') . '>Medium</option>';
    echo '<option value="high" ' . ($row['priority'] === 'high' ? 'selected' : '') . '>High</option>';
    echo '<option value="urgent" ' . ($row['priority'] === 'urgent' ? 'selected' : '') . '>Urgent</option>';
    echo '</select>';
    echo '<button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg" onclick="updateSubmissionStatus(' . $id . ')">Update</button>';
    echo '</div>';
    echo '</div>';
    
    // Response History Section
    echo '<div class="mt-4">';
    echo '<h4 class="font-semibold text-gray-900">Response History</h4>';
    echo '<div class="border border-gray-300 rounded-lg p-4 mt-2">';
    echo '<p class="text-gray-500 italic">No response history available.</p>';
    echo '</div>';
    echo '</div>';
    
    // Response button removed as per new message handling
    /*
    echo '<div class="mt-4">';
    echo '<button class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg" onclick="openResponseModal(' . $id . ', \'' . htmlspecialchars(addslashes($row['email'])) . '\')">';
    echo '<i class="fas fa-reply mr-2"></i>Send Response';
    echo '</button>';
    echo '</div>';
    */
} else {
    echo 'Submission not found';
}

// Close statement and connection
mysqli_stmt_close($stmt);
mysqli_close($link);
?>
