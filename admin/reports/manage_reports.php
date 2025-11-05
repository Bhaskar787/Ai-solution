<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../admin_login/admin-login.php');
    exit;
}

// Include database connection
require_once '../db_connection.php';

$link = get_db_connection();

// Fetch all reports
$query = "SELECT id, name, type, description, created_by, created_date, status FROM reports ORDER BY created_date DESC";
$result = mysqli_query($link, $query);
$reports = mysqli_fetch_all($result, MYSQLI_ASSOC);

mysqli_close($link);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manage Reports | AI-Solutions Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../../css/admin-dashboard.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
</head>
<body class="dashboard-bg">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <?php include '../components/admin-navbar.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Manage Reports</h1>
                    <p class="text-gray-600">View, edit, and manage your saved reports</p>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="add_report.php" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
                        <i class="fas fa-plus mr-2"></i>New Report
                    </a>
                    <a href="../admin-logout.php" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </a>
                </div>
            </div>

            <?php if (empty($reports)): ?>
                <div class="text-center py-12">
                    <i class="fas fa-file-alt text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">No Reports Found</h3>
                    <p class="text-gray-500">You have not created any reports yet.</p>
                </div>
            <?php else: ?>
                <div class="card overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created By</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php foreach ($reports as $report): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo htmlspecialchars($report['name']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo ucfirst($report['type']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($report['description']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($report['created_by']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo date('M j, Y', strtotime($report['created_date'])); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="status-badge status-<?php echo $report['status']; ?>">
                                                <?php echo ucfirst($report['status']); ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="view_report.php?id=<?php echo $report['id']; ?>" class="action-btn bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg" title="View Report">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="edit_report.php?id=<?php echo $report['id']; ?>" class="action-btn bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-lg" title="Edit Report">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="export_report.php?id=<?php echo $report['id']; ?>&format=csv" class="action-btn bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-lg" title="Export CSV">
                                                    <i class="fas fa-file-csv"></i>
                                                </a>
                                                <a href="export_report.php?id=<?php echo $report['id']; ?>&format=pdf" class="action-btn bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg" title="Export PDF">
                                                    <i class="fas fa-file-pdf"></i>
                                                </a>
                                                <button onclick="deleteReport(<?php echo $report['id']; ?>)" class="action-btn bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded-lg" title="Delete Report">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function deleteReport(id) {
            if (!confirm('Are you sure you want to delete this report?')) {
                return;
            }

            fetch('delete_report.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${id}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Report deleted successfully');
                    location.reload();
                } else {
                    alert('Failed to delete report: ' + data.message);
                }
            })
            .catch(error => {
                alert('Error deleting report');
                console.error('Error:', error);
            });
        }
    </script>
</body>
</html>
