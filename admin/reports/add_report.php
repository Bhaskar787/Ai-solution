<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../admin_login/admin-login.php');
    exit;
}

// Include database connection
require_once '../db_connection.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $link = get_db_connection();

    $name = trim($_POST['report_name']);
    $type = $_POST['report_type'];
    $description = trim($_POST['description']);
    $date_start = $_POST['date_start'];
    $date_end = $_POST['date_end'];
    $export_format = $_POST['export_format'];
    $save_config = isset($_POST['save_config']) ? 1 : 0;

    // Build filters based on report type
    $filters = [];

    switch ($type) {
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
    if (empty($type)) $errors[] = "Report type is required";
    if (empty($date_start) || empty($date_end)) $errors[] = "Date range is required";

    if (empty($errors)) {
        // Generate report data
        $report_data = generate_report_data($link, $type, $filters, $date_start, $date_end);

        if ($save_config) {
            // Save report configuration
            $stmt = mysqli_prepare($link, "INSERT INTO reports (name, type, description, filters, date_range_start, date_range_end, created_by, export_format) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $filters_json = json_encode($filters);
            $created_by = $_SESSION['admin_username'] ?? 'admin';
            mysqli_stmt_bind_param($stmt, "ssssssss", $name, $type, $description, $filters_json, $date_start, $date_end, $created_by, $export_format);

            if (mysqli_stmt_execute($stmt)) {
                $report_id = mysqli_insert_id($link);
                $success_message = "Report configuration saved successfully!";

                // Store generated data
                $data_json = json_encode($report_data);
                $stmt_data = mysqli_prepare($link, "INSERT INTO report_data (report_id, data) VALUES (?, ?)");
                mysqli_stmt_bind_param($stmt_data, "is", $report_id, $data_json);
                mysqli_stmt_execute($stmt_data);
                mysqli_stmt_close($stmt_data);
            } else {
                $error_message = "Failed to save report configuration: " . mysqli_error($link);
            }
            mysqli_stmt_close($stmt);
        } else {
            // Just generate report without saving
            $_SESSION['temp_report'] = [
                'name' => $name,
                'type' => $type,
                'data' => $report_data,
                'date_start' => $date_start,
                'date_end' => $date_end,
                'filters' => $filters
            ];
            header('Location: view_temp_report.php');
            exit;
        }
    }

    mysqli_close($link);
}

// Function to generate report data
function generate_report_data($link, $type, $filters, $date_start, $date_end) {
    $data = [];

    switch ($type) {
        case 'contact':
            $query = "SELECT id, full_name, email, company, country, job_title, status, priority, submission_date FROM contact_submissions WHERE submission_date BETWEEN ? AND ?";
            $params = [$date_start, $date_end];
            $types = "ss";

            if (!empty($filters['status'])) {
                $query .= " AND status IN (" . str_repeat('?,', count($filters['status']) - 1) . "?)";
                $params = array_merge($params, $filters['status']);
                $types .= str_repeat("s", count($filters['status']));
            }

            if (!empty($filters['priority'])) {
                $query .= " AND priority IN (" . str_repeat('?,', count($filters['priority']) - 1) . "?)";
                $params = array_merge($params, $filters['priority']);
                $types .= str_repeat("s", count($filters['priority']));
            }

            if (!empty($filters['country'])) {
                $query .= " AND country = ?";
                $params[] = $filters['country'][0];
                $types .= "s";
            }

            $stmt = mysqli_prepare($link, $query);
            if (!empty($params)) {
                mysqli_stmt_bind_param($stmt, $types, ...$params);
            }
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
            mysqli_stmt_close($stmt);
            break;

        case 'feedback':
            $query = "SELECT id, full_name, email, company, project, rating, submission_date, status FROM feedback_submissions WHERE submission_date BETWEEN ? AND ?";
            $params = [$date_start, $date_end];
            $types = "ss";

            if (!empty($filters['status'])) {
                $query .= " AND status IN (" . str_repeat('?,', count($filters['status']) - 1) . "?)";
                $params = array_merge($params, $filters['status']);
                $types .= str_repeat("s", count($filters['status']));
            }

            if (!empty($filters['rating_min'])) {
                $query .= " AND rating >= ?";
                $params[] = $filters['rating_min'];
                $types .= "i";
            }

            if (!empty($filters['rating_max'])) {
                $query .= " AND rating <= ?";
                $params[] = $filters['rating_max'];
                $types .= "i";
            }

            $stmt = mysqli_prepare($link, $query);
            if (!empty($params)) {
                mysqli_stmt_bind_param($stmt, $types, ...$params);
            }
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
            mysqli_stmt_close($stmt);
            break;

        case 'articles':
            $query = "SELECT id, title, author, category, status, date, reading_time FROM articles WHERE date BETWEEN ? AND ?";
            $params = [$date_start, $date_end];
            $types = "ss";

            if (!empty($filters['status'])) {
                $query .= " AND status IN (" . str_repeat('?,', count($filters['status']) - 1) . "?)";
                $params = array_merge($params, $filters['status']);
                $types .= str_repeat("s", count($filters['status']));
            }

            if (!empty($filters['category'])) {
                $query .= " AND category IN (" . str_repeat('?,', count($filters['category']) - 1) . "?)";
                $params = array_merge($params, $filters['category']);
                $types .= str_repeat("s", count($filters['category']));
            }

            if (!empty($filters['author'])) {
                $query .= " AND author = ?";
                $params[] = $filters['author'][0];
                $types .= "s";
            }

            $stmt = mysqli_prepare($link, $query);
            if (!empty($params)) {
                mysqli_stmt_bind_param($stmt, $types, ...$params);
            }
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
            mysqli_stmt_close($stmt);
            break;
    }

    return $data;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Report | AI-Solutions Admin</title>
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
                    <h1 class="text-3xl font-bold text-gray-900">Generate New Report</h1>
                    <p class="text-gray-600">Configure and generate reports from your website data</p>
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
                                value="<?php echo isset($_POST['report_name']) ? htmlspecialchars($_POST['report_name']) : ''; ?>"
                                placeholder="e.g., Monthly Contact Report"
                            >
                        </div>

                        <!-- Report Type -->
                        <div>
                            <label for="report_type" class="block text-sm font-medium text-gray-700 mb-2">Report Type *</label>
                            <select
                                id="report_type"
                                name="report_type"
                                required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                onchange="toggleFilters()"
                            >
                                <option value="">Select Report Type</option>
                                <option value="contact" <?php echo (isset($_POST['report_type']) && $_POST['report_type'] === 'contact') ? 'selected' : ''; ?>>Contact Submissions</option>
                                <option value="feedback" <?php echo (isset($_POST['report_type']) && $_POST['report_type'] === 'feedback') ? 'selected' : ''; ?>>Feedback</option>
                                <option value="articles" <?php echo (isset($_POST['report_type']) && $_POST['report_type'] === 'articles') ? 'selected' : ''; ?>>Articles</option>
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
                                <option value="csv" <?php echo (isset($_POST['export_format']) && $_POST['export_format'] === 'csv') || !isset($_POST['export_format']) ? 'selected' : ''; ?>>CSV</option>
                                <option value="pdf" <?php echo (isset($_POST['export_format']) && $_POST['export_format'] === 'pdf') ? 'selected' : ''; ?>>PDF</option>
                                <option value="both" <?php echo (isset($_POST['export_format']) && $_POST['export_format'] === 'both') ? 'selected' : ''; ?>>Both</option>
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
                                value="<?php echo isset($_POST['date_start']) ? htmlspecialchars($_POST['date_start']) : date('Y-m-d', strtotime('-30 days')); ?>"
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
                                value="<?php echo isset($_POST['date_end']) ? htmlspecialchars($_POST['date_end']) : date('Y-m-d'); ?>"
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
                                placeholder="Optional description for this report"
                            ><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                        </div>
                    </div>

                    <!-- Dynamic Filters -->
                    <div id="filters-section" class="mt-8" style="display: none;">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Filters</h3>
                        <div class="bg-gray-50 p-6 rounded-lg">

                            <!-- Contact Filters -->
                            <div id="contact-filters" class="filter-group" style="display: none;">
                                <h4 class="font-medium text-gray-700 mb-3">Contact Submission Filters</h4>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                        <select name="contact_status[]" multiple class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="new">New</option>
                                            <option value="in-progress">In Progress</option>
                                            <option value="contacted">Contacted</option>
                                            <option value="completed">Completed</option>
                                            <option value="archived">Archived</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                                        <select name="contact_priority[]" multiple class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="low">Low</option>
                                            <option value="medium">Medium</option>
                                            <option value="high">High</option>
                                            <option value="urgent">Urgent</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                                        <input type="text" name="contact_country[]" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Filter by country">
                                    </div>
                                </div>
                            </div>

                            <!-- Feedback Filters -->
                            <div id="feedback-filters" class="filter-group" style="display: none;">
                                <h4 class="font-medium text-gray-700 mb-3">Feedback Filters</h4>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                        <select name="feedback_status[]" multiple class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="new">New</option>
                                            <option value="reviewed">Reviewed</option>
                                            <option value="published">Published</option>
                                            <option value="archived">Archived</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Min Rating</label>
                                        <select name="feedback_rating_min" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="">Any</option>
                                            <option value="1">1 Star</option>
                                            <option value="2">2 Stars</option>
                                            <option value="3">3 Stars</option>
                                            <option value="4">4 Stars</option>
                                            <option value="5">5 Stars</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Max Rating</label>
                                        <select name="feedback_rating_max" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="">Any</option>
                                            <option value="1">1 Star</option>
                                            <option value="2">2 Stars</option>
                                            <option value="3">3 Stars</option>
                                            <option value="4">4 Stars</option>
                                            <option value="5">5 Stars</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Articles Filters -->
                            <div id="articles-filters" class="filter-group" style="display: none;">
                                <h4 class="font-medium text-gray-700 mb-3">Articles Filters</h4>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                        <select name="article_status[]" multiple class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="draft">Draft</option>
                                            <option value="published">Published</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                                        <select name="article_category[]" multiple class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="ai">Artificial Intelligence</option>
                                            <option value="experience">Employee Experience</option>
                                            <option value="innovation">Innovation</option>
                                            <option value="research">Research</option>
                                            <option value="technology">Technology</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Author</label>
                                        <input type="text" name="article_author[]" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Filter by author">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Options -->
                    <div class="mt-8">
                        <div class="flex items-center">
                            <input type="checkbox" id="save_config" name="save_config" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" checked>
                            <label for="save_config" class="ml-2 block text-sm text-gray-900">
                                Save this report configuration for future use
                            </label>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="mt-8 flex justify-end space-x-4">
                        <a href="manage_reports.php" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                            Cancel
                        </a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg">
                            <i class="fas fa-chart-bar mr-2"></i>Generate Report
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

        // Toggle filters based on report type
        function toggleFilters() {
            const reportType = document.getElementById('report_type').value;
            const filtersSection = document.getElementById('filters-section');
            const filterGroups = document.querySelectorAll('.filter-group');

            // Hide all filter groups
            filterGroups.forEach(group => group.style.display = 'none');

            if (reportType) {
                filtersSection.style.display = 'block';
                const activeGroup = document.getElementById(reportType + '-filters');
                if (activeGroup) {
                    activeGroup.style.display = 'block';
                }
            } else {
                filtersSection.style.display = 'none';
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleFilters();
        });

        // Check for success parameter in URL
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('success') === '1') {
            showNotification('Report generated successfully!', 'success');
        }
    </script>
</body>
</html>
