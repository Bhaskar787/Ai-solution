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

// Get feedback submissions with pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 10;
$offset = ($page - 1) * $per_page;

// Get total count for pagination
$total_query = "SELECT COUNT(*) as total FROM feedback_submissions";
$total_result = mysqli_query($link, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_submissions = $total_row['total'];
$total_pages = ceil($total_submissions / $per_page);

// Get submissions for current page
$query = "SELECT id, full_name, company, email, project, rating, submission_date, status, attachment
          FROM feedback_submissions
          ORDER BY submission_date DESC
          LIMIT ? OFFSET ?";
$stmt = mysqli_prepare($link, $query);
mysqli_stmt_bind_param($stmt, "ii", $per_page, $offset);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$submissions = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Close connection
mysqli_close($link);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Feedback | AI-Solutions Admin</title>
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
                    <h1 class="text-3xl font-bold text-gray-900">Manage Feedback</h1>
                    <p class="text-gray-600">Review and manage user feedback submissions</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                        Total: <?php echo $total_submissions; ?> submissions
                    </div>
                </div>
            </div>

            <!-- Feedback Table -->
            <div class="card">
                <div class="p-6">
                    <?php if (empty($submissions)): ?>
                        <div class="text-center py-12">
                            <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                            <h3 class="text-xl font-semibold text-gray-600 mb-2">No Feedback Submissions</h3>
                            <p class="text-gray-500">There are no feedback submissions to display yet.</p>
                        </div>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table class="w-full table-auto">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php foreach ($submissions as $submission): ?>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                                            <span class="text-white font-medium text-sm">
                                                                <?php echo strtoupper(substr($submission['full_name'], 0, 2)); ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            <?php echo htmlspecialchars($submission['full_name']); ?>
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            <?php echo htmlspecialchars($submission['email']); ?>
                                                        </div>
                                                        <?php if ($submission['company']): ?>
                                                            <div class="text-xs text-gray-400">
                                                                <?php echo htmlspecialchars($submission['company']); ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    <?php echo htmlspecialchars($submission['project'] ?: 'Not specified'); ?>
                                                </div>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                        <i class="fas fa-star text-sm <?php echo $i <= $submission['rating'] ? 'text-yellow-400' : 'text-gray-300'; ?>"></i>
                                                    <?php endfor; ?>
                                                    <span class="ml-2 text-sm text-gray-600">(<?php echo $submission['rating']; ?>/5)</span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <?php echo date('M j, Y', strtotime($submission['submission_date'])); ?>
                                                <div class="text-xs text-gray-400">
                                                    <?php echo date('g:i A', strtotime($submission['submission_date'])); ?>
                                                </div>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    <?php
                                                    switch ($submission['status']) {
                                                        case 'new':
                                                            echo 'bg-blue-100 text-blue-800';
                                                            break;
                                                        case 'reviewed':
                                                            echo 'bg-yellow-100 text-yellow-800';
                                                            break;
                                                        case 'published':
                                                            echo 'bg-green-100 text-green-800';
                                                            break;
                                                        case 'archived':
                                                            echo 'bg-gray-100 text-gray-800';
                                                            break;
                                                        default:
                                                            echo 'bg-gray-100 text-gray-800';
                                                    }
                                                    ?>">
                                                    <?php echo ucfirst($submission['status']); ?>
                                                </span>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex items-center space-x-2">
                                                    <button onclick="viewFeedback(<?php echo $submission['id']; ?>)"
                                                            class="text-blue-600 hover:text-blue-900">
                                                        <i class="fas fa-eye"></i> View
                                                    </button>
                                                    <select onchange="updateStatus(<?php echo $submission['id']; ?>, this.value)"
                                                            class="text-xs border border-gray-300 rounded px-2 py-1">
                                                        <option value="new" <?php echo $submission['status'] === 'new' ? 'selected' : ''; ?>>New</option>
                                                        <option value="reviewed" <?php echo $submission['status'] === 'reviewed' ? 'selected' : ''; ?>>Reviewed</option>
                                                        <option value="published" <?php echo $submission['status'] === 'published' ? 'selected' : ''; ?>>Published</option>
                                                        <option value="archived" <?php echo $submission['status'] === 'archived' ? 'selected' : ''; ?>>Archived</option>
                                                    </select>
                                                    <button onclick="deleteFeedback(<?php echo $submission['id']; ?>)"
                                                            class="text-red-600 hover:text-red-900">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <?php if ($total_pages > 1): ?>
                            <div class="flex items-center justify-between mt-6">
                                <div class="text-sm text-gray-700">
                                    Showing <?php echo ($offset + 1); ?> to <?php echo min($offset + $per_page, $total_submissions); ?> of <?php echo $total_submissions; ?> results
                                </div>
                                <div class="flex space-x-2">
                                    <?php if ($page > 1): ?>
                                        <a href="?page=<?php echo ($page - 1); ?>" class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                            Previous
                                        </a>
                                    <?php endif; ?>

                                    <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                                        <a href="?page=<?php echo $i; ?>"
                                           class="px-3 py-2 text-sm font-medium <?php echo $i === $page ? 'text-blue-600 bg-blue-50 border-blue-500' : 'text-gray-500 bg-white border-gray-300'; ?> border rounded-md hover:bg-gray-50">
                                            <?php echo $i; ?>
                                        </a>
                                    <?php endfor; ?>

                                    <?php if ($page < $total_pages): ?>
                                        <a href="?page=<?php echo ($page + 1); ?>" class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                            Next
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Details Modal -->
    <div id="details-modal" class="modal fixed inset-0 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen bg-black bg-opacity-50">
            <div class="modal-content bg-white rounded-lg w-full max-w-4xl mx-4 max-h-screen overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-900">Feedback Details</h3>
                        <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700 text-xl">
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

        // View feedback details
        function viewFeedback(id) {
            const modal = document.getElementById('details-modal');
            const modalContent = document.getElementById('modal-content');

            // Show loading
            modalContent.innerHTML = '<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i><p class="mt-2 text-gray-600">Loading...</p></div>';
            modal.classList.remove('hidden');

            // Fetch feedback details
            fetch(`view_feedback.php?id=${id}`)
                .then(response => response.text())
                .then(data => {
                    modalContent.innerHTML = data;
                })
                .catch(error => {
                    modalContent.innerHTML = '<div class="text-center py-8 text-red-600"><i class="fas fa-exclamation-triangle text-2xl"></i><p class="mt-2">Error loading feedback details</p></div>';
                });
        }

        // Update feedback status
        function updateStatus(id, status) {
            if (confirm('Are you sure you want to update the status of this feedback?')) {
                fetch('update_feedback_status.php', {
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
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showNotification('Error updating status', 'error');
                    }
                })
                .catch(error => {
                    showNotification('Error updating status', 'error');
                });
            } else {
                // Reset select to original value
                location.reload();
            }
        }

        // Delete feedback
        function deleteFeedback(id) {
            if (confirm('Are you sure you want to delete this feedback? This action cannot be undone.')) {
                fetch('delete_feedback.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id=${id}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Feedback deleted successfully', 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showNotification('Error deleting feedback', 'error');
                    }
                })
                .catch(error => {
                    showNotification('Error deleting feedback', 'error');
                });
            }
        }

        // Close modal
        function closeModal() {
            document.getElementById('details-modal').classList.add('hidden');
        }

        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            const modal = document.getElementById('details-modal');
            if (event.target === modal) {
                closeModal();
            }
        });
    </script>
</body>
</html>
