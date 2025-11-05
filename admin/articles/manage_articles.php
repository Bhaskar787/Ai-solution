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

// Get filter parameters
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';
$search_filter = isset($_GET['search']) ? $_GET['search'] : '';

// Build query based on filters
$query = "SELECT * FROM articles WHERE 1=1";
$count_query = "SELECT COUNT(*) as count FROM articles WHERE 1=1";
$params = [];
$types = "";

if (!empty($status_filter)) {
    $query .= " AND status = ?";
    $count_query .= " AND status = ?";
    $params[] = $status_filter;
    $types .= "s";
}

if (!empty($category_filter)) {
    $query .= " AND category = ?";
    $count_query .= " AND category = ?";
    $params[] = $category_filter;
    $types .= "s";
}

if (!empty($search_filter)) {
    $query .= " AND (title LIKE ? OR content LIKE ? OR author LIKE ?)";
    $count_query .= " AND (title LIKE ? OR content LIKE ? OR author LIKE ?)";
    $search_param = "%$search_filter%";
    $params = array_merge($params, [$search_param, $search_param, $search_param]);
    $types .= "sss";
}

$query .= " ORDER BY date DESC";

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
$total_articles = mysqli_fetch_assoc($count_result)['count'];

// Pagination
$per_page = 10;
$total_pages = ceil($total_articles / $per_page);
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
$articles = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Fetch statistics
$published_count = mysqli_fetch_assoc(mysqli_query($link, "SELECT COUNT(*) as count FROM articles WHERE status = 'published'"))['count'];
$draft_count = mysqli_fetch_assoc(mysqli_query($link, "SELECT COUNT(*) as count FROM articles WHERE status = 'draft'"))['count'];

// Close connection
mysqli_close($link);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Articles | AI-Solutions Admin</title>
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
                    <h1 class="text-3xl font-bold text-gray-900">Manage Articles</h1>
                    <p class="text-gray-600">Add, edit, and manage articles for the website</p>
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
                    <h2 class="text-xl font-bold text-gray-900">Article Overview</h2>
                    <div class="flex space-x-3">
                        <a href="add_article.php" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
                            <i class="fas fa-plus mr-2"></i>Add Article
                        </a>
                        <button id="refresh-button" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg flex items-center">
                            <i class="fas fa-sync-alt mr-2"></i>Refresh
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Total Articles Card -->
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <div class="text-2xl font-bold text-blue-600"><?php echo $total_articles; ?></div>
                        <div class="text-gray-600 mt-1">Total Articles</div>
                    </div>

                    <!-- Published Articles Card -->
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <div class="text-2xl font-bold text-green-600"><?php echo $published_count; ?></div>
                        <div class="text-gray-600 mt-1">Published Articles</div>
                    </div>

                    <!-- Draft Articles Card -->
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <div class="text-2xl font-bold text-gray-600"><?php echo $draft_count; ?></div>
                        <div class="text-gray-600 mt-1">Draft Articles</div>
                    </div>
                </div>
            </div>

            <!-- Filters and Search -->
            <div class="card p-6 mb-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex flex-wrap gap-3">
                        <select id="status-filter" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Statuses</option>
                            <option value="published" <?php echo $status_filter === 'published' ? 'selected' : ''; ?>>Published</option>
                            <option value="draft" <?php echo $status_filter === 'draft' ? 'selected' : ''; ?>>Draft</option>
                        </select>

                        <select id="category-filter" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Categories</option>
                            <option value="ai" <?php echo $category_filter === 'ai' ? 'selected' : ''; ?>>Artificial Intelligence</option>
                            <option value="experience" <?php echo $category_filter === 'experience' ? 'selected' : ''; ?>>Employee Experience</option>
                            <option value="innovation" <?php echo $category_filter === 'innovation' ? 'selected' : ''; ?>>Innovation</option>
                            <option value="research" <?php echo $category_filter === 'research' ? 'selected' : ''; ?>>Research</option>
                            <option value="technology" <?php echo $category_filter === 'technology' ? 'selected' : ''; ?>>Technology</option>
                        </select>

                        <button id="clear-filters" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg">
                            Clear Filters
                        </button>
                    </div>

                    <div class="relative w-full md:w-64">
                        <input
                            type="text"
                            id="search-input"
                            placeholder="Search articles..."
                            class="search-input w-full border border-gray-300 rounded-lg pl-10 pr-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            value="<?php echo htmlspecialchars($search_filter); ?>"
                        >
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>
            </div>

            <!-- Articles Table -->
            <div class="card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php if (empty($articles)): ?>
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                        No articles found
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($articles as $article): ?>
                                    <tr class="table-row">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo $article['id']; ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($article['title']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($article['author']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo ucfirst($article['category']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo date('M j, Y', strtotime($article['date'])); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="status-badge status-<?php echo $article['status']; ?>">
                                                <?php echo ucfirst($article['status']); ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <button
                                                    class="action-btn bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg"
                                                    onclick="viewDetails(<?php echo $article['id']; ?>)"
                                                >
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <a href="edit_article.php?id=<?php echo $article['id']; ?>" class="action-btn bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-lg">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button
                                                    class="delete-btn bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg"
                                                    onclick="deleteArticle(<?php echo $article['id']; ?>)"
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
                                <a href="?page=<?php echo $current_page - 1; ?>&status=<?php echo urlencode($status_filter); ?>&category=<?php echo urlencode($category_filter); ?>&search=<?php echo urlencode($search_filter); ?>" class="px-3 py-1 rounded border border-gray-300 hover:bg-gray-100">
                                    Previous
                                </a>
                            <?php endif; ?>

                            <?php for ($i = max(1, $current_page - 2); $i <= min($total_pages, $current_page + 2); $i++): ?>
                                <?php if ($i == $current_page): ?>
                                    <span class="px-3 py-1 rounded bg-blue-500 text-white"><?php echo $i; ?></span>
                                <?php else: ?>
                                    <a href="?page=<?php echo $i; ?>&status=<?php echo urlencode($status_filter); ?>&category=<?php echo urlencode($category_filter); ?>&search=<?php echo urlencode($search_filter); ?>" class="px-3 py-1 rounded border border-gray-300 hover:bg-gray-100"><?php echo $i; ?></a>
                                <?php endif; ?>
                            <?php endfor; ?>

                            <?php if ($current_page < $total_pages): ?>
                                <a href="?page=<?php echo $current_page + 1; ?>&status=<?php echo urlencode($status_filter); ?>&category=<?php echo urlencode($category_filter); ?>&search=<?php echo urlencode($search_filter); ?>" class="px-3 py-1 rounded border border-gray-300 hover:bg-gray-100">
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
                        <h3 class="text-xl font-bold text-gray-900">Article Details</h3>
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

    <script>
        // View details function
        function viewDetails(id) {
            fetch('get_article_details.php', {
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
        document.getElementById('category-filter').addEventListener('change', applyFilters);
        document.getElementById('search-input').addEventListener('input', applyFilters);
        document.getElementById('clear-filters').addEventListener('click', clearFilters);

        function applyFilters() {
            const status = document.getElementById('status-filter').value;
            const category = document.getElementById('category-filter').value;
            const search = document.getElementById('search-input').value;

            let url = 'manage_articles.php?';
            const params = [];

            if (status) params.push(`status=${encodeURIComponent(status)}`);
            if (category) params.push(`category=${encodeURIComponent(category)}`);
            if (search) params.push(`search=${encodeURIComponent(search)}`);

            if (params.length > 0) {
                url += params.join('&');
                window.location.href = url;
            } else {
                window.location.href = 'manage_articles.php';
            }
        }

        function clearFilters() {
            window.location.href = 'manage_articles.php';
        }

        // Delete article function
        function deleteArticle(id) {
            if (!confirm('Are you sure you want to delete this article?')) {
                return;
            }

            fetch('delete_article.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${id}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Article deleted successfully', 'success');
                    // Reload the page to show updated list
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    showNotification('Failed to delete article: ' + data.message, 'error');
                }
            })
            .catch(error => {
                showNotification('Error deleting article', 'error');
                console.error('Error:', error);
            });
        }

        // Refresh button functionality
        document.getElementById('refresh-button').addEventListener('click', function() {
            location.reload();
        });

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
