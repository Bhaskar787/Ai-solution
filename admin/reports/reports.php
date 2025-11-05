<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../admin_login/admin-login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports | AI-Solutions Admin</title>
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
                    <h1 class="text-3xl font-bold text-gray-900">Reports</h1>
                    <p class="text-gray-600">View and manage reports</p>
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

            <!-- Reports Content -->
            <div class="card p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Reports Overview</h2>
                <p class="text-gray-600 mb-6">This section is for displaying various reports. Content to be implemented.</p>
                <!-- Placeholder for reports -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Sample Report 1</h3>
                        <p class="text-gray-600">Placeholder for report data.</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Sample Report 2</h3>
                        <p class="text-gray-600">Placeholder for report data.</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Sample Report 3</h3>
                        <p class="text-gray-600">Placeholder for report data.</p>
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
    </script>
</body>
</html>
