<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../admin_login/admin-login.php');
    exit;
}

require_once '../db_connection.php';

$link = get_db_connection();

$report_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$report_id) {
    header('Location: manage_reports.php');
    exit;
}

// Fetch report data
$stmt = mysqli_prepare($link, "SELECT * FROM reports WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $report_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$report = mysqli_fetch_assoc($result);

if (!$report) {
    header('Location: manage_reports.php');
    exit;
}

$filters = json_decode($report['filters'], true);

mysqli_close($link);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $link = get_db_connection();

    $name = trim($_POST['report_name']);
    $description = trim($_POST['description']);
    $date_start = $_POST['date_start'];
    $date_end = $_POST['date_end'];
    $export_format = $_POST['export_format'];
    $status = $_POST['status'];

    // Build filters based on report type
    $filters = [];

    switch ($report['type']) {
        case 'contact':
            if (!empty($_POST['contact_status'])) $filters['status'] = $_POST['contact_status'];
            if (!empty($_POST['contact_priority'])) $filters['priority'] = $_POST['contact_priority'];
            if (!empty($_POST['contact_country'])) $filters['country'] = $_POST['contact_country'];
            break;
        case 'feedback':
            if (!empty($_POST['feedback_status'])) $filters['status'] = $_POST['feedback_status'];
            if (!empty($_POST['feedback_rating_min'])) $filters['rating_min'] = (int)$_POST['feedback_rating_min'];
            if (!empty($_POST['feedback_rating_max'])) $filters['rating_max'] = (int)$_POST['feedback_rating_max'];
            break;
        case 'articles':
            if (!empty($_POST['article_status'])) $filters['status'] = $_POST['article_status'];
            if (!empty($_POST['article_category'])) $filters['category'] = $_POST['article_category'];
            if (!empty($_POST['article_author'])) $filters['author'] = $_POST['article_author'];
            break;
    }

    // Validate required fields
    $errors = [];
    if (empty($name)) $errors[] = "Report name is required";
    if (empty($date_start) || empty($date_end)) $errors[] = "Date range is required";

    if (empty($errors)) {
        // Update report configuration
        $stmt = mysqli_prepare($link, "UPDATE reports SET name = ?, description = ?, filters = ?, date_range_start = ?, date_range_end = ?, export_format = ?, status = ? WHERE id = ?");
        $filters_json = json_encode($filters);
        mysqli_stmt_bind_param($stmt, "sssssssi", $name, $description, $filters_json, $date_start, $date_end, $export_format, $status, $report_id);

        if (mysqli_stmt_execute($stmt)) {
            $success_message = "Report configuration updated successfully!";
        } else {
            $error_message = "Failed to update report configuration: " . mysqli_error($link);
        }
        mysqli_stmt_close($stmt);
    }

    mysqli_close($link);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Report | AI-Solutions Admin</title>
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
                    <h1 class="text-3xl font-bold text-gray-900">Edit Report</h1>
                    <p class="text-gray-600">Modify report configuration and settings</p>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="manage_reports.php" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Reports
                    </a>
                    <a href="../admin-logout.php" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </a>
                </div>
            </div>

            <!-- Form -->
            <div class="card p-8">
                <?php if (isset($success_message)): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                        <?php echo $success_message; ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($error_message)): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($errors)): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Report Name -->
                        <div class="md:col-span-2">
                            <label for="report_name" class="block text-sm font-medium text-gray-700 mb-2">Report Name *</label>
                            <input
                                type="text"
                                id="report_name"
                                name="report_name"
                                required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                value="<?php echo htmlspecialchars($report['name']); ?>"
                            >
                        </div>

                        <!-- Report Type (Read-only) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Report Type</label>
                            <input
                                type="text"
                                readonly
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-gray-100"
                                value="<?php echo ucfirst($report['type']); ?>"
                            >
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select
                                id="status"
                                name="status"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            >
                                <option value="active" <?php echo ($report['status'] === 'active') ? 'selected' : ''; ?>>Active</option>
                                <option value="inactive" <?php echo ($report['status'] === 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                                <option value="archived" <?php echo ($report['status'] === 'archived') ? 'selected' : ''; ?>>Archived</option>
                            </select>
                        </div>

                        <!-- Export Format -->
                        <div>
                            <label for="export_format" class="block text-sm font-medium text-gray-700 mb-2">Export Format</label>
                            <select
                                id="export_format"
                                name="export_format"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            >
                                <option value="csv" <?php echo ($report['export_format'] === 'csv' || !$report['export_format']) ? 'selected' : ''; ?>>CSV</option>
                                <option value="pdf" <?php echo ($report['export_format'] === 'pdf') ? 'selected' : ''; ?>>PDF</option>
                                <option value="both" <?php echo ($report['export_format'] === 'both') ? 'selected' : ''; ?>>Both</option>
                            </select>
                        </div>

                        <!-- Date Range -->
                        <div>
                            <label for="date_start" class="block text-sm font-medium text-gray-700 mb-2">Start Date *</label>
                            <input
                                type="date"
                                id="date_start"
                                name="date_start"
                                required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                value="<?php echo htmlspecialchars($report['date_range_start']); ?>"
                            >
                        </div>

                        <div>
                            <label for="date_end" class="block text-sm font-medium text-gray-700 mb-2">End Date *</label>
                            <input
                                type="date"
                                id="date_end"
                                name="date_end"
                                required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                value="<?php echo htmlspecialchars($report['date_range_end']); ?>"
                            >
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea
                                id="description"
                                name="description"
                                rows="3"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            ><?php echo htmlspecialchars($report['description']); ?></textarea>
                        </div>
                    </div>

                    <!-- Dynamic Filters -->
                    <div id="filters-section" class="mt-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Filters</h3>
                        <div class="bg-gray-50 p-6 rounded-lg">

                            <!-- Contact Filters -->
                            <?php if ($report['type'] === 'contact'): ?>
                                <div id="contact-filters" class="filter-group">
                                    <h4 class="font-medium text-gray-700 mb-3">Contact Submission Filters</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                            <select name="contact_status[]" multiple class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                <option value="new" <?php echo (isset($filters['status']) && in_array('new', $filters['status'])) ? 'selected' : ''; ?>>New</option>
                                                <option value="in-progress" <?php echo (isset($filters['status']) && in_array('in-progress', $filters['status'])) ? 'selected' : ''; ?>>In Progress</option>
                                                <option value="contacted" <?php echo (isset($filters['status']) && in_array('contacted', $filters['status'])) ? 'selected' : ''; ?>>Contacted</option>
                                                <option value="completed" <?php echo (isset($filters['status']) && in_array('completed', $filters['status'])) ? 'selected' : ''; ?>>Completed</option>
                                                <option value="archived" <?php echo (isset($filters['status']) && in_array('archived', $filters['status'])) ? 'selected' : ''; ?>>Archived</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                                            <select name="contact_priority[]" multiple class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                <option value="low" <?php echo (isset($filters['priority']) && in_array('low', $filters['priority'])) ? 'selected' : ''; ?>>Low</option>
                                                <option value="medium" <?php echo (isset($filters['priority']) && in_array('medium', $filters['priority'])) ? 'selected' : ''; ?>>Medium</option>
                                                <option value="high" <?php echo (isset($filters['priority']) && in_array('high', $filters['priority'])) ? 'selected' : ''; ?>>High</option>
                                                <option value="urgent" <?php echo (isset($filters['priority']) && in_array('urgent', $filters['priority'])) ? 'selected' : ''; ?>>Urgent</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                                            <input type="text" name="contact_country[]" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Filter by country" value="<?php echo isset($filters['country']) ? htmlspecialchars(implode(', ', $filters['country'])) : ''; ?>">
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Feedback Filters -->
                            <?php if ($report['type'] === 'feedback'): ?>
                                <div id="feedback-filters" class="filter-group">
                                    <h4 class="font-medium text-gray-700 mb-3">Feedback Filters</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                            <select name="feedback_status[]" multiple class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                <option value="new" <?php echo (isset($filters['status']) && in_array('new', $filters['status'])) ? 'selected' : ''; ?>>New</option>
                                                <option value="reviewed" <?php echo (isset($filters['status']) && in_array('reviewed', $filters['status'])) ? 'selected' : ''; ?>>Reviewed</option>
                                                <option value="published" <?php echo (isset($filters['status']) && in_array('published', $filters['status'])) ? 'selected' : ''; ?>>Published</option>
                                                <option value="archived" <?php echo (isset($filters['status']) && in_array('archived', $filters['status'])) ? 'selected' : ''; ?>>Archived</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Min Rating</label>
                                            <select name="feedback_rating_min" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                <option value="">Any</option>
                                                <option value="1" <?php echo (isset($filters['rating_min']) && $filters['rating_min'] == 1) ? 'selected' : ''; ?>>1 Star</option>
                                                <option value="2" <?php echo (isset($filters['rating_min']) && $filters['rating_min'] == 2) ? 'selected' : ''; ?>>2 Stars</option>
                                                <option value="3" <?php echo (isset($filters['rating_min']) && $filters['rating_min'] == 3) ? 'selected' : ''; ?>>3 Stars</option>
                                                <option value="4" <?php echo (isset($filters['rating_min']) && $filters['rating_min'] == 4) ? 'selected' : ''; ?>>4 Stars</option>
                                                <option value="5" <?php echo (isset($filters['rating_min']) && $filters['rating_min'] == 5) ? 'selected' : ''; ?>>5 Stars</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Max Rating</label>
                                            <select name="feedback_rating_max" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                <option value="">Any</option>
                                                <option value="1" <?php echo (isset($filters['rating_max']) && $filters['rating_max'] == 1) ? 'selected' : ''; ?>>1 Star</option>
                                                <option value="2" <?php echo (isset($filters['rating_max']) && $filters['rating_max'] == 2) ? 'selected' : ''; ?>>2 Stars</option>
                                                <option value="3" <?php echo (isset($filters['rating_max']) && $filters['rating_max'] == 3) ? 'selected' : ''; ?>>3 Stars</option>
                                                <option value="4" <?php echo (isset($filters['rating_max']) && $filters['rating_max'] == 4) ? 'selected' : ''; ?>>4 Stars</option>
                                                <option value="5" <?php echo (isset($filters['rating_max']) && $filters['rating_max'] == 5) ? 'selected' : ''; ?>>5 Stars</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Articles Filters -->
                            <?php if ($report['type'] === 'articles'): ?>
                                <div id="articles-filters" class="filter-group">
                                    <h4 class="font-medium text-gray-700 mb-3">Articles Filters</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                            <select name="article_status[]" multiple class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                <option value="draft" <?php echo (isset($filters['status']) && in_array('draft', $filters['status'])) ? 'selected' : ''; ?>>Draft</option>
                                                <option value="published" <?php echo (isset($filters['status']) && in_array('published', $filters['status'])) ? 'selected' : ''; ?>>Published</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                                            <select name="article_category[]" multiple class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                <option value="ai" <?php echo (isset($filters['category']) && in_array('ai', $filters['category'])) ? 'selected' : ''; ?>>Artificial Intelligence</option>
                                                <option value="experience" <?php echo (isset($filters['category']) && in_array('experience', $filters['category'])) ? 'selected' : ''; ?>>Employee Experience</option>
                                                <option value="innovation" <?php echo (isset($filters['category']) && in_array('innovation', $filters['category'])) ? 'selected' : ''; ?>>Innovation</option>
                                                <option value="research" <?php echo (isset($filters['category']) && in_array('research', $filters['category'])) ? 'selected' : ''; ?>>Research</option>
                                                <option value="technology" <?php echo (isset($filters['category']) && in_array('technology', $filters['category'])) ? 'selected' : ''; ?>>Technology</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Author</label>
                                            <input type="text" name="article_author[]" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Filter by author" value="<?php echo isset($filters['author']) ? htmlspecialchars(implode(', ', $filters['author'])) : ''; ?>">
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="mt-8 flex justify-end space-x-4">
                        <a href="manage_reports.php" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                            Cancel
                        </a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg">
                            <i class="fas fa-save mr-2"></i>Update Report
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
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
    </script>
</body>
</html>
