<?php
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get POST data and sanitize
    $fullName = isset($_POST['fullName']) ? trim($_POST['fullName']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $company = isset($_POST['company']) ? trim($_POST['company']) : '';
    $country = isset($_POST['country']) ? trim($_POST['country']) : '';
    $jobTitle = isset($_POST['jobTitle']) ? trim($_POST['jobTitle']) : '';
    $jobDetails = isset($_POST['jobDetails']) ? trim($_POST['jobDetails']) : '';

    // Validate required fields
    if (empty($fullName) || empty($email) || empty($phone) || empty($company) || empty($country) || empty($jobTitle) || empty($jobDetails)) {
        header('Location: ../../contact.php?error=missing_fields');
        exit;
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: ../../contact.php?error=invalid_email');
        exit;
    }

    // Include database connection
    require_once '../db_connection.php';

    // Get database connection
    $link = get_db_connection();

    // Prepare statement to prevent SQL injection
    $stmt = mysqli_prepare($link, "INSERT INTO contact_submissions (full_name, email, phone, company, country, job_title, job_details) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        header('Location: ../../contact.php?error=db_error');
        exit;
    }

    // Bind parameters and execute
    mysqli_stmt_bind_param($stmt, "sssssss", $fullName, $email, $phone, $company, $country, $jobTitle, $jobDetails);

    if (mysqli_stmt_execute($stmt)) {
        // Success - redirect with success message
        header('Location: ../../contact.php?success=1');
    } else {
        // Error - redirect with error message
        header('Location: ../../contact.php?error=submit_failed');
    }

    // Close statement and connection
    mysqli_stmt_close($stmt);
    mysqli_close($link);
    exit;
}

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
$count_query = "SELECT COUNT(*) as count FROM contact_submissions WHERE 1=1";
$params = [];
$types = "";

if (!empty($status_filter)) {
    $query .= " AND status = ?";
    $count_query .= " AND status = ?";
    $params[] = $status_filter;
    $types .= "s";
}

if (!empty($priority_filter)) {
    $query .= " AND priority = ?";
    $count_query .= " AND priority = ?";
    $params[] = $priority_filter;
    $types .= "s";
}

if (!empty($search_filter)) {
    $query .= " AND (full_name LIKE ? OR email LIKE ? OR company LIKE ?)";
    $count_query .= " AND (full_name LIKE ? OR email LIKE ? OR company LIKE ?)";
    $search_param = "%$search_filter%";
    $params = array_merge($params, [$search_param, $search_param, $search_param]);
    $types .= "sss";
}

$query .= " ORDER BY submission_date DESC";

// Prepare and execute count query
$stmt = mysqli_prepare($link, $count_query);
if ($stmt && !empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
if ($stmt && empty($params)) {
    mysqli_stmt_execute($stmt);
} elseif ($stmt) {
    mysqli_stmt_execute($stmt);
}
$count_result = mysqli_stmt_get_result($stmt);
$total_submissions = mysqli_fetch_assoc($count_result)['count'];

// Pagination
$per_page = 10;
$total_pages = ceil($total_submissions / $per_page);
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$current_page = max(1, min($current_page, $total_pages));
$offset = ($current_page - 1) * $per_page;

$query .= " LIMIT ? OFFSET ?";
$params[] = $per_page;
$params[] = $offset;
$types .= "ii";

// Prepare and execute main query
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

// Fetch statistics from database using the existing connection
// Get today's date for monthly filter
$start_of_month = date('Y-m-01');

// New Inquiries (status = 'new')
$new_inquiries_query = "SELECT COUNT(*) as count FROM contact_submissions WHERE status = 'new'";
$new_inquiries_result = mysqli_query($link, $new_inquiries_query);
$new_inquiries_count = mysqli_fetch_assoc($new_inquiries_result)['count'];

// Pending (status = 'new' or 'contacted')
$pending_query = "SELECT COUNT(*) as count FROM contact_submissions WHERE status IN ('new', 'contacted')";
$pending_result = mysqli_query($link, $pending_query);
$pending_count = mysqli_fetch_assoc($pending_result)['count'];

// In Progress (status = 'in-progress')
$in_progress_query = "SELECT COUNT(*) as count FROM contact_submissions WHERE status = 'in-progress'";
$in_progress_result = mysqli_query($link, $in_progress_query);
$in_progress_count = mysqli_fetch_assoc($in_progress_result)['count'];

// Resolved (status = 'completed')
$resolved_query = "SELECT COUNT(*) as count FROM contact_submissions WHERE status = 'completed'";
$resolved_result = mysqli_query($link, $resolved_query);
$resolved_count = mysqli_fetch_assoc($resolved_result)['count'];

// Resolved This Month (status = 'completed' and submission_date in current month)
$resolved_this_month_query = "SELECT COUNT(*) as count FROM contact_submissions WHERE status = 'completed' AND submission_date >= '$start_of_month'";
$resolved_this_month_result = mysqli_query($link, $resolved_this_month_query);
$resolved_this_month_count = mysqli_fetch_assoc($resolved_this_month_result)['count'];

// Close connection
mysqli_close($link);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Submissions | AI-Solutions Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../../css/admin-dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="dashboard-bg">
    <!-- Notification Container -->
    <div id="notification-container"></div>

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <?php include '../components/admin-navbar.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Contact Submissions</h1>
                    <p class="text-gray-600">Manage and respond to customer inquiries</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <span class="text-gray-600 mr-2">Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</span>
                    </div>
                    <a href="../admin-logout.php" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </a>
                </div>
            </div>

            <!-- Summary Statistics -->
            <div class="card p-6 mb-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                    <h2 class="text-xl font-bold text-gray-900">Manage and respond to user inquiries</h2>
                    <div class="flex space-x-3">
                        <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center" id="export-button">
                            <i class="fas fa-download mr-2"></i>Export
                        </button>
                        <button class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg flex items-center" id="import-button">
                            <i class="fas fa-upload mr-2"></i>Import
                        </button>
                        <button id="refresh-stats" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg flex items-center">
                            <i class="fas fa-sync-alt mr-2"></i>Refresh
                        </button>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <!-- New Inquiries Card -->
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <div class="text-2xl font-bold text-blue-600"><?php echo $new_inquiries_count; ?></div>
                        <div class="text-gray-600 mt-1">New Inquiries</div>
                    </div>
                    
                    <!-- Pending Card -->
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <div class="text-2xl font-bold text-yellow-600"><?php echo $pending_count; ?></div>
                        <div class="text-gray-600 mt-1">Pending</div>
                        <div class="text-sm text-gray-500">Awaiting review</div>
                    </div>
                    
                    <!-- In Progress Card -->
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <div class="text-2xl font-bold text-orange-600"><?php echo $in_progress_count; ?></div>
                        <div class="text-gray-600 mt-1">In Progress</div>
                        <div class="text-sm text-gray-500">Being handled</div>
                    </div>
                    
                    <!-- Resolved Card -->
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <div class="text-2xl font-bold text-green-600"><?php echo $resolved_this_month_count; ?></div>
                        <div class="text-gray-600 mt-1">Resolved</div>
                        <div class="text-sm text-gray-500">This month</div>
                    </div>
                </div>
            </div>

            <!-- Import Modal -->
            <div id="import-modal" class="modal fixed inset-0 z-50 hidden overflow-auto bg-black bg-opacity-50">
                <div class="flex items-center justify-center min-h-screen p-4">
                    <div class="modal-content bg-white rounded-lg w-full max-w-2xl mx-auto p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-bold text-gray-900">Import Contact Submissions</h3>
                            <button id="close-import-modal" class="text-gray-500 hover:text-gray-700">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <form id="import-form" enctype="multipart/form-data">
                            <div class="mb-4">
                                <label for="import-file" class="block font-semibold mb-1">CSV File</label>
                                <input type="file" id="import-file" name="import_file" accept=".csv" class="w-full border border-gray-300 rounded px-3 py-2" required>
                                <p class="text-sm text-gray-500 mt-1">Upload a CSV file with contact submission data</p>
                            </div>
                            <div class="mb-4">
                                <label class="flex items-center">
                                    <input type="checkbox" id="import-overwrite" name="overwrite" class="mr-2">
                                    <span>Overwrite existing submissions</span>
                                </label>
                            </div>
                            <div class="flex justify-end space-x-3">
                                <button type="button" id="cancel-import" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg">Cancel</button>
                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">Import</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Filters and Search -->
            <div class="card p-6 mb-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex flex-wrap gap-3">
                        <select id="status-filter" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Statuses</option>
                            <option value="new" <?php echo $status_filter === 'new' ? 'selected' : ''; ?>>New</option>
                            <option value="in-progress" <?php echo $status_filter === 'in-progress' ? 'selected' : ''; ?>>In Progress</option>
                            <option value="contacted" <?php echo $status_filter === 'contacted' ? 'selected' : ''; ?>>Contacted</option>
                            <option value="completed" <?php echo $status_filter === 'completed' ? 'selected' : ''; ?>>Completed</option>
                            <option value="archived" <?php echo $status_filter === 'archived' ? 'selected' : ''; ?>>Archived</option>
                        </select>

                        <select id="priority-filter" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Priorities</option>
                            <option value="low" <?php echo $priority_filter === 'low' ? 'selected' : ''; ?>>Low</option>
                            <option value="medium" <?php echo $priority_filter === 'medium' ? 'selected' : ''; ?>>Medium</option>
                            <option value="high" <?php echo $priority_filter === 'high' ? 'selected' : ''; ?>>High</option>
                            <option value="urgent" <?php echo $priority_filter === 'urgent' ? 'selected' : ''; ?>>Urgent</option>
                        </select>

                        <button id="clear-filters" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg">
                            Clear Filters
                        </button>
                        
                        <button id="refresh-button" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                            <i class="fas fa-sync-alt mr-2"></i>Refresh
                        </button>
                    </div>

                    <div class="relative w-full md:w-64">
                        <input 
                            type="text" 
                            id="search-input" 
                            placeholder="Search submissions..." 
                            class="search-input w-full border border-gray-300 rounded-lg pl-10 pr-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            value="<?php echo htmlspecialchars($search_filter); ?>"
                        >
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>
            </div>

            <!-- Bulk Actions -->
            <div class="card p-6 mb-8 hidden" id="bulk-actions">
                <div class="flex flex-wrap items-center gap-4">
                    <h3 class="text-lg font-semibold">Bulk Actions:</h3>
                    <button id="bulk-delete" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg">
                        <i class="fas fa-trash mr-2"></i>Delete Selected
                    </button>
                    <div class="flex items-center gap-2">
                        <select id="bulk-status" class="border border-gray-300 rounded-lg px-3 py-2">
                            <option value="">Update Status To</option>
                            <option value="new">New</option>
                            <option value="in-progress">In Progress</option>
                            <option value="contacted">Contacted</option>
                            <option value="completed">Completed</option>
                            <option value="archived">Archived</option>
                        </select>
                        <button id="apply-status" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                            Apply
                        </button>
                    </div>
                    <div class="flex items-center gap-2">
                        <select id="bulk-priority" class="border border-gray-300 rounded-lg px-3 py-2">
                            <option value="">Update Priority To</option>
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                        <button id="apply-priority" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                            Apply
                        </button>
                    </div>
                </div>
            </div>

            <!-- Submissions Table -->
            <div class="card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <input type="checkbox" id="select-all-checkbox" class="form-checkbox h-4 w-4 text-blue-600">
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php if (empty($submissions)): ?>
                                <tr>
                                    <td colspan="9" class="px-6 py-4 text-center text-gray-500">
                                        No submissions found
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($submissions as $submission): ?>
                                    <tr class="table-row">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <input type="checkbox" class="form-checkbox h-4 w-4 text-blue-600 select-checkbox" data-id="<?php echo $submission['id']; ?>">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo $submission['id']; ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($submission['full_name']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($submission['email']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($submission['company']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo date('M j, Y', strtotime($submission['submission_date'])); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="status-badge status-<?php echo $submission['status']; ?>">
                                                <?php echo ucfirst(str_replace('-', ' ', $submission['status'])); ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="priority-badge priority-<?php echo $submission['priority']; ?>">
                                                <?php echo ucfirst($submission['priority']); ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <button 
                                                    class="action-btn bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg"
                                                    onclick="viewDetails(<?php echo $submission['id']; ?>)"
                                                >
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <select 
                                                    class="status-select border border-gray-300 rounded px-2 py-1 text-xs"
                                                    onchange="updateStatus(<?php echo $submission['id']; ?>, this.value)"
                                                    data-original="<?php echo $submission['status']; ?>"
                                                >
                                                    <option value="new" <?php echo $submission['status'] === 'new' ? 'selected' : ''; ?>>New</option>
                                                    <option value="in-progress" <?php echo $submission['status'] === 'in-progress' ? 'selected' : ''; ?>>In Progress</option>
                                                    <option value="contacted" <?php echo $submission['status'] === 'contacted' ? 'selected' : ''; ?>>Contacted</option>
                                                    <option value="completed" <?php echo $submission['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                                    <option value="archived" <?php echo $submission['status'] === 'archived' ? 'selected' : ''; ?>>Archived</option>
                                                </select>
                                            </div>
                                            <div class="flex space-x-2 mt-2">
                                                <!-- Response button removed as per new message handling -->
                                                <!--
                                                <button 
                                                    class="response-btn bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-lg"
                                                    onclick="openResponseModal(<?php echo $submission['id']; ?>, '<?php echo htmlspecialchars(addslashes($submission['email'])); ?>')"
                                                >
                                                    <i class="fas fa-reply"></i> Response
                                                </button>
                                                -->
                                                <button 
                                                    class="delete-btn bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg"
                                                    onclick="deleteSubmission(<?php echo $submission['id']; ?>)"
                                                >
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                    <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Showing page <span class="font-medium"><?php echo $current_page; ?></span> of <span class="font-medium"><?php echo $total_pages; ?></span>
                        </div>
                        <div class="flex space-x-2">
                            <?php if ($current_page > 1): ?>
                                <a href="?page=<?php echo $current_page - 1; ?>&status=<?php echo urlencode($status_filter); ?>&priority=<?php echo urlencode($priority_filter); ?>&search=<?php echo urlencode($search_filter); ?>" class="px-3 py-1 rounded border border-gray-300 hover:bg-gray-100">
                                    Previous
                                </a>
                            <?php endif; ?>

                            <?php for ($i = max(1, $current_page - 2); $i <= min($total_pages, $current_page + 2); $i++): ?>
                                <?php if ($i == $current_page): ?>
                                    <span class="px-3 py-1 rounded bg-blue-500 text-white"><?php echo $i; ?></span>
                                <?php else: ?>
                                    <a href="?page=<?php echo $i; ?>&status=<?php echo urlencode($status_filter); ?>&priority=<?php echo urlencode($priority_filter); ?>&search=<?php echo urlencode($search_filter); ?>" class="px-3 py-1 rounded border border-gray-300 hover:bg-gray-100"><?php echo $i; ?></a>
                                <?php endif; ?>
                            <?php endfor; ?>

                            <?php if ($current_page < $total_pages): ?>
                                <a href="?page=<?php echo $current_page + 1; ?>&status=<?php echo urlencode($status_filter); ?>&priority=<?php echo urlencode($priority_filter); ?>&search=<?php echo urlencode($search_filter); ?>" class="px-3 py-1 rounded border border-gray-300 hover:bg-gray-100">
                                    Next
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Details Modal -->
    <div id="details-modal" class="modal fixed inset-0 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen">
            <div class="modal-content w-full max-w-2xl mx-4">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-gray-900">Submission Details</h3>
                        <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div id="modal-content">
                        <!-- Content will be loaded via AJAX -->
                    </div>
                </div>
            </div>
        </div>
    </div>

            <!-- Response Modal removed as per new message handling -->
            <!--
            <div id="response-modal" class="modal fixed inset-0 z-50 hidden overflow-auto bg-black bg-opacity-50">
                <div class="flex items-center justify-center min-h-screen p-4">
                    <div class="modal-content bg-white rounded-lg w-full max-w-2xl mx-auto p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-bold text-gray-900">Send Response</h3>
                            <button onclick="closeResponseModal()" class="text-gray-500 hover:text-gray-700">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <form id="response-form" enctype="multipart/form-data">
                            <input type="hidden" id="response-id" name="id" value="">
                            <div class="mb-4">
                                <label for="response-type" class="block font-semibold mb-1">Response Type</label>
                                <select id="response-type" name="response_type" class="border border-gray-300 rounded px-3 py-2 w-full">
                                    <option value="reply">Reply to User</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="response-message" class="block font-semibold mb-1">Message</label>
                                <textarea id="response-message" name="message" rows="5" class="border border-gray-300 rounded px-3 py-2 w-full" placeholder="Type your response here..."></textarea>
                            </div>
                            <div class="mb-4">
                                <label for="response-attachments" class="block font-semibold mb-1">Attachments</label>
                                <input type="file" id="response-attachments" name="attachments[]" multiple class="w-full">
                            </div>
                            <div class="mb-4 flex items-center">
                                <input type="checkbox" id="auto-update-status" name="auto_update_status" checked class="mr-2">
                                <label for="auto-update-status" class="text-sm">Automatically update status to "In Progress"</label>
                            </div>
                            <div class="flex justify-end space-x-3">
                                <button type="button" onclick="saveDraft()" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg">Save Draft</button>
                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">Send Response</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            -->

    <script>
        // Update status function
        function updateStatus(id, status) {
            fetch('update_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${id}&status=${status}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Status updated successfully', 'success');
                    // Update the status badge without page refresh
                    const row = document.querySelector(`tr[data-id="${id}"]`);
                    if (row) {
                        const statusCell = row.cells[5];
                        statusCell.innerHTML = `<span class="status-badge status-${status}">${status.charAt(0).toUpperCase() + status.slice(1)}</span>`;
                    }
                } else {
                    showNotification('Failed to update status: ' + data.message, 'error');
                    // Revert the select to original value
                    const select = event.target;
                    select.value = select.getAttribute('data-original');
                }
            })
            .catch(error => {
                showNotification('Error updating status', 'error');
                console.error('Error:', error);
            });
        }

        // View details function
        function viewDetails(id) {
            fetch('get_submission_details.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${id}`
            })
            .then(response => response.text())
            .then(html => {
                document.getElementById('modal-content').innerHTML = html;
                document.getElementById('details-modal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            })
            .catch(error => {
                showNotification('Error loading details', 'error');
                console.error('Error:', error);
            });
        }

        // Close modal function
        function closeModal() {
            document.getElementById('details-modal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Show notification function
        function showNotification(message, type) {
            const container = document.getElementById('notification-container');
            const notification = document.createElement('div');
            notification.className = `notification ${type} show`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
                    <span>${message}</span>
                </div>
            `;
            container.appendChild(notification);

            // Remove notification after 3 seconds
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => {
                    container.removeChild(notification);
                }, 300);
            }, 3000);
        }

        // Filter functionality
        document.getElementById('status-filter').addEventListener('change', applyFilters);
        document.getElementById('priority-filter').addEventListener('change', applyFilters);
        document.getElementById('search-input').addEventListener('input', applyFilters);
        document.getElementById('clear-filters').addEventListener('click', clearFilters);

        function applyFilters() {
            const status = document.getElementById('status-filter').value;
            const priority = document.getElementById('priority-filter').value;
            const search = document.getElementById('search-input').value;
            
            let url = 'contact_submissions.php?';
            const params = [];
            
            if (status) params.push(`status=${encodeURIComponent(status)}`);
            if (priority) params.push(`priority=${encodeURIComponent(priority)}`);
            if (search) params.push(`search=${encodeURIComponent(search)}`);
            
            if (params.length > 0) {
                url += params.join('&');
                window.location.href = url;
            } else {
                window.location.href = 'contact_submissions.php';
            }
        }

        function clearFilters() {
            window.location.href = 'contact_submissions.php';
        }

        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            const modal = document.getElementById('details-modal');
            if (event.target === modal) {
                closeModal();
            }
        });

        // Update submission status and priority
        function updateSubmissionStatus(id) {
            const status = document.getElementById('update-status-' + id).value;
            const priority = document.getElementById('update-priority-' + id).value;
            
            // Create FormData object
            const formData = new FormData();
            formData.append('id', id);
            formData.append('status', status);
            formData.append('priority', priority);
            
            // Update both status and priority
            fetch('update_status.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Status and priority updated successfully', 'success');
                    // Close the modal
                    closeModal();
                    // Reload the page to show updated status
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    showNotification('Failed to update status: ' + data.message, 'error');
                }
            })
            .catch(error => {
                showNotification('Error updating status', 'error');
                console.error('Error:', error);
            });
        }

        // Delete submission function
        function deleteSubmission(id) {
            if (!confirm('Are you sure you want to delete this submission?')) {
                return;
            }
            
            fetch('delete_submissions.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `ids[]=${id}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Submission deleted successfully', 'success');
                    // Reload the page to show updated list
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    showNotification('Failed to delete submission: ' + data.message, 'error');
                }
            })
            .catch(error => {
                showNotification('Error deleting submission', 'error');
                console.error('Error:', error);
            });
        }
        
        // Response modal functions removed as per new message handling
        /*
        function openResponseModal(id, email) {
            document.getElementById('response-id').value = id;
            document.getElementById('response-message').value = '';
            document.getElementById('response-attachments').value = '';
            document.getElementById('auto-update-status').checked = true;
            document.getElementById('response-modal').classList.remove('hidden');
        }

        function closeResponseModal() {
            document.getElementById('response-modal').classList.add('hidden');
        }

        function saveDraft() {
            alert('Save Draft functionality is not implemented yet.');
        }

        document.getElementById('response-form').addEventListener('submit', function(event) {
            event.preventDefault();

            const formData = new FormData(this);

            fetch('send_response.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Response sent successfully.');
                    closeResponseModal();
                    // Optionally update status to "In Progress" if checkbox is checked
                    const autoUpdateStatus = document.getElementById('auto-update-status').checked;
                    const submissionId = document.getElementById('response-id').value;
                    if (autoUpdateStatus) {
                        updateStatus(submissionId, 'in-progress');
                    }
                } else {
                    alert('Failed to send response: ' + data.message);
                }
            })
            .catch(error => {
                alert('Error sending response.');
                console.error('Error:', error);
            });
        });
        */
        
        // Refresh button functionality
        document.getElementById('refresh-button').addEventListener('click', function() {
            location.reload();
        });
        
        // Refresh stats button functionality
        document.getElementById('refresh-stats').addEventListener('click', function() {
            location.reload();
        });
        
        // Export button functionality
        document.getElementById('export-button').addEventListener('click', function() {
            // Get current filter parameters
            const status = document.getElementById('status-filter').value;
            const priority = document.getElementById('priority-filter').value;
            const search = document.getElementById('search-input').value;
            
            // Build URL with parameters
            let url = 'export_submissions.php?';
            const params = [];
            
            if (status) params.push(`status=${encodeURIComponent(status)}`);
            if (priority) params.push(`priority=${encodeURIComponent(priority)}`);
            if (search) params.push(`search=${encodeURIComponent(search)}`);
            
            if (params.length > 0) {
                url += params.join('&');
            }
            
            // Redirect to export script
            window.location.href = url;
        });
        
        // Import button functionality
        document.getElementById('import-button').addEventListener('click', function() {
            document.getElementById('import-modal').classList.remove('hidden');
        });
        
        // Close import modal
        document.getElementById('close-import-modal').addEventListener('click', function() {
            document.getElementById('import-modal').classList.add('hidden');
        });
        
        // Cancel import
        document.getElementById('cancel-import').addEventListener('click', function() {
            document.getElementById('import-modal').classList.add('hidden');
            document.getElementById('import-form').reset();
        });
        
        // Handle import form submission
        document.getElementById('import-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('import_submissions.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Submissions imported successfully', 'success');
                    document.getElementById('import-modal').classList.add('hidden');
                    document.getElementById('import-form').reset();
                    // Reload the page to show updated data
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    showNotification('Import failed: ' + data.message, 'error');
                }
            })
            .catch(error => {
                showNotification('Error importing submissions', 'error');
                console.error('Error:', error);
            });
        });
        
        // Select all checkboxes functionality
        document.getElementById('select-all-checkbox').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.select-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            
            // Show/hide bulk actions based on selection
            const bulkActions = document.getElementById('bulk-actions');
            if (this.checked && checkboxes.length > 0) {
                bulkActions.classList.remove('hidden');
            } else if (!this.checked) {
                bulkActions.classList.add('hidden');
            }
        });
        
        // Individual checkbox change event
        document.querySelectorAll('.select-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const bulkActions = document.getElementById('bulk-actions');
                const anyChecked = document.querySelectorAll('.select-checkbox:checked').length > 0;
                const allChecked = document.querySelectorAll('.select-checkbox').length === document.querySelectorAll('.select-checkbox:checked').length;
                
                // Update select all checkbox state
                document.getElementById('select-all-checkbox').checked = allChecked;
                
                // Show/hide bulk actions
                if (anyChecked) {
                    bulkActions.classList.remove('hidden');
                } else {
                    bulkActions.classList.add('hidden');
                }
            });
        });
        
        // Bulk delete functionality
        document.getElementById('bulk-delete').addEventListener('click', function() {
            const selectedIds = Array.from(document.querySelectorAll('.select-checkbox:checked')).map(checkbox => checkbox.dataset.id);
            
            if (selectedIds.length === 0) {
                showNotification('Please select at least one submission to delete', 'error');
                return;
            }
            
            if (!confirm(`Are you sure you want to delete ${selectedIds.length} submission(s)?`)) {
                return;
            }
            
            fetch('delete_submissions.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `ids[]=${selectedIds.join('&ids[]=')}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(`${selectedIds.length} submission(s) deleted successfully`, 'success');
                    // Reload the page to show updated list
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    showNotification('Failed to delete submissions: ' + data.message, 'error');
                }
            })
            .catch(error => {
                showNotification('Error deleting submissions', 'error');
                console.error('Error:', error);
            });
        });
        
        // Bulk update status functionality
        document.getElementById('apply-status').addEventListener('click', function() {
            const selectedIds = Array.from(document.querySelectorAll('.select-checkbox:checked')).map(checkbox => checkbox.dataset.id);
            const status = document.getElementById('bulk-status').value;
            
            if (selectedIds.length === 0) {
                showNotification('Please select at least one submission to update', 'error');
                return;
            }
            
            if (!status) {
                showNotification('Please select a status to apply', 'error');
                return;
            }
            
            fetch('update_multiple_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `ids[]=${selectedIds.join('&ids[]=')}&status=${status}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(`Status updated for ${selectedIds.length} submission(s)`, 'success');
                    // Reload the page to show updated status
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    showNotification('Failed to update status: ' + data.message, 'error');
                }
            })
            .catch(error => {
                showNotification('Error updating status', 'error');
                console.error('Error:', error);
            });
        });
        
        // Bulk update priority functionality
        document.getElementById('apply-priority').addEventListener('click', function() {
            const selectedIds = Array.from(document.querySelectorAll('.select-checkbox:checked')).map(checkbox => checkbox.dataset.id);
            const priority = document.getElementById('bulk-priority').value;
            
            if (selectedIds.length === 0) {
                showNotification('Please select at least one submission to update', 'error');
                return;
            }
            
            if (!priority) {
                showNotification('Please select a priority to apply', 'error');
                return;
            }
            
            fetch('update_multiple_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `ids[]=${selectedIds.join('&ids[]=')}&priority=${priority}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(`Priority updated for ${selectedIds.length} submission(s)`, 'success');
                    // Reload the page to show updated priority
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    showNotification('Failed to update priority: ' + data.message, 'error');
                }
            })
            .catch(error => {
                showNotification('Error updating priority', 'error');
                console.error('Error:', error);
            });
        });
    </script>
</body>
</html>
