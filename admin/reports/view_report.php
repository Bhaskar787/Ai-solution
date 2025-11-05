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

// Get report configuration
$stmt = mysqli_prepare($link, "SELECT * FROM reports WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $report_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$report = mysqli_fetch_assoc($result);

if (!$report) {
    header('Location: manage_reports.php');
    exit;
}

// Get report data
$stmt_data = mysqli_prepare($link, "SELECT data FROM report_data WHERE report_id = ? ORDER BY generated_date DESC LIMIT 1");
mysqli_stmt_bind_param($stmt_data, "i", $report_id);
mysqli_stmt_execute($stmt_data);
$data_result = mysqli_stmt_get_result($stmt_data);
$data_row = mysqli_fetch_assoc($data_result);

$report_data = [];
if ($data_row) {
    $report_data = json_decode($data_row['data'], true);
}

$filters = json_decode($report['filters'], true);

mysqli_close($link);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($report['name']); ?> | AI-Solutions Admin</title>
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
                    <h1 class="text-3xl font-bold text-gray-900"><?php echo htmlspecialchars($report['name']); ?></h1>
                    <p class="text-gray-600">Saved report for <?php echo ucfirst($report['type']); ?> data</p>
                </div>
                <div class="flex items-center space-x-4">
                    <button onclick="exportReport('csv')" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg flex items-center">
                        <i class="fas fa-download mr-2"></i>Export CSV
                    </button>
                    <button onclick="exportReport('pdf')" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
                        <i class="fas fa-file-pdf mr-2"></i>Export PDF
                    </button>
                    <a href="edit_report.php?id=<?php echo $report['id']; ?>" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg flex items-center">
                        <i class="fas fa-edit mr-2"></i>Edit Report
                    </a>
                    <a href="manage_reports.php" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Reports
                    </a>
                    <a href="../admin-logout.php" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </a>
                </div>
            </div>

            <!-- Report Summary -->
            <div class="card p-6 mb-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <div class="text-2xl font-bold text-blue-600"><?php echo count($report_data); ?></div>
                        <div class="text-gray-600 mt-1">Total Records</div>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <div class="text-2xl font-bold text-green-600"><?php echo date('M j, Y', strtotime($report['date_range_start'])); ?></div>
                        <div class="text-gray-600 mt-1">Start Date</div>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <div class="text-2xl font-bold text-purple-600"><?php echo date('M j, Y', strtotime($report['date_range_end'])); ?></div>
                        <div class="text-gray-600 mt-1">End Date</div>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <div class="text-2xl font-bold text-orange-600"><?php echo ucfirst($report['type']); ?></div>
                        <div class="text-gray-600 mt-1">Report Type</div>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Report Details</h3>
                        <div class="space-y-2">
                            <div><strong>Created By:</strong> <?php echo htmlspecialchars($report['created_by']); ?></div>
                            <div><strong>Created Date:</strong> <?php echo date('M j, Y \a\t g:i A', strtotime($report['created_date'])); ?></div>
                            <div><strong>Status:</strong> <span class="status-badge status-<?php echo $report['status']; ?>"><?php echo ucfirst($report['status']); ?></span></div>
                            <div><strong>Export Format:</strong> <?php echo strtoupper($report['export_format']); ?></div>
                        </div>
                    </div>

                    <?php if (!empty($filters)): ?>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Applied Filters</h3>
                            <div class="flex flex-wrap gap-2">
                                <?php foreach ($filters as $key => $value): ?>
                                    <?php if (is_array($value)): ?>
                                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                                            <?php echo ucfirst(str_replace('_', ' ', $key)); ?>: <?php echo implode(', ', $value); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                                            <?php echo ucfirst(str_replace('_', ' ', $key)); ?>: <?php echo $value; ?>
                                        </span>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ($report['description']): ?>
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Description</h3>
                        <p class="text-gray-700"><?php echo htmlspecialchars($report['description']); ?></p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Report Data Table -->
            <div class="card overflow-hidden">
                <div class="overflow-x-auto">
                    <?php if (empty($report_data)): ?>
                        <div class="text-center py-12">
                            <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                            <h3 class="text-xl font-semibold text-gray-600 mb-2">No Data Found</h3>
                            <p class="text-gray-500">No records match the specified criteria for this report.</p>
                            <div class="mt-4">
                                <a href="edit_report.php?id=<?php echo $report['id']; ?>" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                                    <i class="fas fa-edit mr-2"></i>Edit Report Configuration
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <?php
                                    // Get column headers based on report type
                                    $columns = [];
                                    switch ($report['type']) {
                                        case 'contact':
                                            $columns = ['ID', 'Name', 'Email', 'Company', 'Country', 'Job Title', 'Status', 'Priority', 'Date'];
                                            break;
                                        case 'feedback':
                                            $columns = ['ID', 'Name', 'Email', 'Company', 'Project', 'Rating', 'Status', 'Date'];
                                            break;
                                        case 'articles':
                                            $columns = ['ID', 'Title', 'Author', 'Category', 'Status', 'Date', 'Reading Time'];
                                            break;
                                    }
                                    foreach ($columns as $column): ?>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <?php echo $column; ?>
                                        </th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php foreach ($report_data as $row): ?>
                                    <tr class="table-row">
                                        <?php
                                        // Display data based on report type
                                        switch ($report['type']) {
                                            case 'contact':
                                                echo '<td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">' . $row['id'] . '</td>';
                                                echo '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">' . htmlspecialchars($row['full_name']) . '</td>';
                                                echo '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">' . htmlspecialchars($row['email']) . '</td>';
                                                echo '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">' . htmlspecialchars($row['company'] ?: 'N/A') . '</td>';
                                                echo '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">' . htmlspecialchars($row['country'] ?: 'N/A') . '</td>';
                                                echo '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">' . htmlspecialchars($row['job_title'] ?: 'N/A') . '</td>';
                                                echo '<td class="px-6 py-4 whitespace-nowrap"><span class="status-badge status-' . $row['status'] . '">' . ucfirst(str_replace('-', ' ', $row['status'])) . '</span></td>';
                                                echo '<td class="px-6 py-4 whitespace-nowrap"><span class="priority-badge priority-' . $row['priority'] . '">' . ucfirst($row['priority']) . '</span></td>';
                                                echo '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">' . date('M j, Y', strtotime($row['submission_date'])) . '</td>';
                                                break;
                                            case 'feedback':
                                                echo '<td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">' . $row['id'] . '</td>';
                                                echo '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">' . htmlspecialchars($row['full_name']) . '</td>';
                                                echo '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">' . htmlspecialchars($row['email']) . '</td>';
                                                echo '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">' . htmlspecialchars($row['company'] ?: 'N/A') . '</td>';
                                                echo '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">' . htmlspecialchars($row['project'] ?: 'N/A') . '</td>';
                                                echo '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">';
                                                for ($i = 1; $i <= 5; $i++) {
                                                    echo '<i class="fas fa-star ' . ($i <= $row['rating'] ? 'text-yellow-400' : 'text-gray-300') . '"></i>';
                                                }
                                                echo ' (' . $row['rating'] . '/5)</td>';
                                                echo '<td class="px-6 py-4 whitespace-nowrap"><span class="status-badge status-' . $row['status'] . '">' . ucfirst($row['status']) . '</span></td>';
                                                echo '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">' . date('M j, Y', strtotime($row['submission_date'])) . '</td>';
                                                break;
                                            case 'articles':
                                                echo '<td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">' . $row['id'] . '</td>';
                                                echo '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">' . htmlspecialchars($row['title']) . '</td>';
                                                echo '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">' . htmlspecialchars($row['author']) . '</td>';
                                                echo '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">' . ucfirst($row['category']) . '</td>';
                                                echo '<td class="px-6 py-4 whitespace-nowrap"><span class="status-badge status-' . $row['status'] . '">' . ucfirst($row['status']) . '</span></td>';
                                                echo '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">' . date('M j, Y', strtotime($row['date'])) . '</td>';
                                                echo '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">' . ($row['reading_time'] ?: 'N/A') . ' min</td>';
                                                break;
                                        }
                                        ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
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

        // Export report function
        function exportReport(format) {
            const reportId = <?php echo $report['id']; ?>;
            window.location.href = `export_report.php?id=${reportId}&format=${format}`;
        }
    </script>
</body>
</html>
