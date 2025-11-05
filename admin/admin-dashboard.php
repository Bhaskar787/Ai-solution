<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login/admin-login.php');
    exit;
}

// Include database connection
require_once 'db_connection.php';

$link = get_db_connection();

// Fetch statistics
// Articles
$articles_total = mysqli_fetch_assoc(mysqli_query($link, "SELECT COUNT(*) as count FROM articles"))['count'];
$articles_published = mysqli_fetch_assoc(mysqli_query($link, "SELECT COUNT(*) as count FROM articles WHERE status = 'published'"))['count'];
$articles_draft = mysqli_fetch_assoc(mysqli_query($link, "SELECT COUNT(*) as count FROM articles WHERE status = 'draft'"))['count'];

// Events
$events_total = mysqli_fetch_assoc(mysqli_query($link, "SELECT COUNT(*) as count FROM events"))['count'];
$events_upcoming = mysqli_fetch_assoc(mysqli_query($link, "SELECT COUNT(*) as count FROM events WHERE status = 'upcoming'"))['count'];
$events_past = mysqli_fetch_assoc(mysqli_query($link, "SELECT COUNT(*) as count FROM events WHERE status = 'past'"))['count'];

// Contact Submissions
$contacts_new = mysqli_fetch_assoc(mysqli_query($link, "SELECT COUNT(*) as count FROM contact_submissions WHERE status = 'new'"))['count'];
$contacts_pending = mysqli_fetch_assoc(mysqli_query($link, "SELECT COUNT(*) as count FROM contact_submissions WHERE status IN ('new', 'contacted')"))['count'];
$contacts_in_progress = mysqli_fetch_assoc(mysqli_query($link, "SELECT COUNT(*) as count FROM contact_submissions WHERE status = 'in-progress'"))['count'];
$start_of_month = date('Y-m-01');
$contacts_resolved_month = mysqli_fetch_assoc(mysqli_query($link, "SELECT COUNT(*) as count FROM contact_submissions WHERE status = 'completed' AND submission_date >= '$start_of_month'"))['count'];

// Feedback
$feedback_total = mysqli_fetch_assoc(mysqli_query($link, "SELECT COUNT(*) as count FROM feedback_submissions"))['count'];

// Conversations
$conversations_total = mysqli_fetch_assoc(mysqli_query($link, "SELECT COUNT(*) as count FROM conversations"))['count'];
$conversations_active = mysqli_fetch_assoc(mysqli_query($link, "SELECT COUNT(*) as count FROM conversations WHERE status = 'active'"))['count'];
$conversations_unread = mysqli_fetch_assoc(mysqli_query($link, "SELECT SUM(unread_count) as count FROM conversations"))['count'] ?: 0;

// Reports (if table exists)
$reports_total = 0;
if (mysqli_query($link, "SELECT 1 FROM reports LIMIT 1")) {
    $reports_total = mysqli_fetch_assoc(mysqli_query($link, "SELECT COUNT(*) as count FROM reports"))['count'];
}

// For charts - contact submissions status distribution
$contact_status_data = [];
$result = mysqli_query($link, "SELECT status, COUNT(*) as count FROM contact_submissions GROUP BY status");
while ($row = mysqli_fetch_assoc($result)) {
    $contact_status_data[$row['status']] = $row['count'];
}

// Articles by category
$articles_category_data = [];
$result = mysqli_query($link, "SELECT category, COUNT(*) as count FROM articles GROUP BY category");
while ($row = mysqli_fetch_assoc($result)) {
    $articles_category_data[$row['category']] = $row['count'];
}

mysqli_close($link);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | AI-Solutions</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="../css/admin-dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="dashboard-bg">
    <!-- Notification Container -->
    <div id="notification-container"></div>

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <?php include 'components/admin-navbar.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Admin Dashboard</h1>
                    <p class="text-gray-600">Overview and Analytics of AI-Solutions Admin Data</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <span class="text-gray-600 mr-2">Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</span>
                    </div>
                    <a href="admin-logout.php" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </a>
                </div>
            </div>

            <!-- Overview Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Articles -->
                <div class="card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Articles</h3>
                            <p class="text-2xl font-bold text-blue-600"><?php echo $articles_total; ?></p>
                            <p class="text-sm text-gray-600">Published: <?php echo $articles_published; ?> | Draft: <?php echo $articles_draft; ?></p>
                        </div>
                        <i class="fas fa-newspaper text-3xl text-blue-500"></i>
                    </div>
                </div>

                <!-- Events -->
                <div class="card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Events</h3>
                            <p class="text-2xl font-bold text-green-600"><?php echo $events_total; ?></p>
                            <p class="text-sm text-gray-600">Upcoming: <?php echo $events_upcoming; ?> | Past: <?php echo $events_past; ?></p>
                        </div>
                        <i class="fas fa-calendar text-3xl text-green-500"></i>
                    </div>
                </div>

                <!-- Contact Submissions -->
                <div class="card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Contacts</h3>
                            <p class="text-2xl font-bold text-purple-600"><?php echo $contacts_new + $contacts_pending + $contacts_in_progress + $contacts_resolved_month; ?></p>
                            <p class="text-sm text-gray-600">New: <?php echo $contacts_new; ?> | Resolved (Month): <?php echo $contacts_resolved_month; ?></p>
                        </div>
                        <i class="fas fa-envelope text-3xl text-purple-500"></i>
                    </div>
                </div>

                <!-- Feedback -->
                <div class="card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Feedback</h3>
                            <p class="text-2xl font-bold text-orange-600"><?php echo $feedback_total; ?></p>
                            <p class="text-sm text-gray-600">Total Submissions</p>
                        </div>
                        <i class="fas fa-star text-3xl text-orange-500"></i>
                    </div>
                </div>

                <!-- Conversations -->
                <div class="card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Conversations</h3>
                            <p class="text-2xl font-bold text-indigo-600"><?php echo $conversations_total; ?></p>
                            <p class="text-sm text-gray-600">Active: <?php echo $conversations_active; ?> | Unread: <?php echo $conversations_unread; ?></p>
                        </div>
                        <i class="fas fa-comments text-3xl text-indigo-500"></i>
                    </div>
                </div>

                <!-- Reports -->
                <div class="card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Reports</h3>
                            <p class="text-2xl font-bold text-red-600"><?php echo $reports_total; ?></p>
                            <p class="text-sm text-gray-600">Total Reports</p>
                        </div>
                        <i class="fas fa-chart-bar text-3xl text-red-500"></i>
                    </div>
                </div>
            </div>

            <!-- Analytics Charts -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Contact Submissions Status Pie Chart -->
                <div class="card p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Contact Submissions Status</h3>
                    <canvas id="contactStatusChart" width="400" height="300"></canvas>
                </div>

                <!-- Articles by Category Bar Chart -->
                <div class="card p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Articles by Category</h3>
                    <canvas id="articlesCategoryChart" width="400" height="300"></canvas>
                </div>
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

        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            const modal = document.getElementById('details-modal');
            if (event.target === modal) {
                closeModal();
            }
        });

        // Chart.js data from PHP
        const contactStatusData = <?php echo json_encode($contact_status_data); ?>;
        const articlesCategoryData = <?php echo json_encode($articles_category_data); ?>;

        // Contact Status Pie Chart
        const ctx1 = document.getElementById('contactStatusChart').getContext('2d');
        new Chart(ctx1, {
            type: 'pie',
            data: {
                labels: Object.keys(contactStatusData),
                datasets: [{
                    data: Object.values(contactStatusData),
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });

        // Articles Category Bar Chart
        const ctx2 = document.getElementById('articlesCategoryChart').getContext('2d');
        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: Object.keys(articlesCategoryData),
                datasets: [{
                    label: 'Number of Articles',
                    data: Object.values(articlesCategoryData),
                    backgroundColor: '#36A2EB',
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
