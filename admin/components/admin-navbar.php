<?php
// This file contains the admin sidebar component
// Determine which page is currently active
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!-- Sidebar -->
<div class="sidebar w-64 min-h-screen p-6 text-white">
    <div class="mb-10">
        <div class="flex items-center">
            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                <i class="fas fa-brain text-white"></i>
            </div>
            <h1 class="text-xl font-bold">AI-Solutions</h1>
        </div>
        <p class="text-blue-200 text-sm mt-1">Admin Dashboard</p>
    </div>

    <nav class="space-y-2">
        <a href="/admin/admin-dashboard.php" class="sidebar-item flex items-center p-3 rounded-lg <?php echo ($current_page == 'admin-dashboard.php') ? 'active' : ''; ?>">
            <i class="fas fa-tachometer-alt mr-3"></i>
            <span>Dashboard</span>
        </a>
        <a href="/admin/contact_submission/contact_submissions.php" class="sidebar-item flex items-center p-3 rounded-lg <?php echo ($current_page == 'contact_submissions.php') ? 'active' : ''; ?>">
            <i class="fas fa-inbox mr-3"></i>
            <span>Contact Submissions</span>
        </a>
        <a href="/admin/message/conversations.php" class="sidebar-item flex items-center p-3 rounded-lg <?php echo ($current_page == 'conversations.php') ? 'active' : ''; ?>">
            <i class="fas fa-envelope mr-3"></i>
            <span>Messages</span>
        </a>

        <a href="/admin/feedback/manage_feedback.php" class="sidebar-item flex items-center p-3 rounded-lg <?php echo ($current_page == 'manage_feedback.php') ? 'active' : ''; ?>">
            <i class="fas fa-comments mr-3"></i>
            <span>Feedback</span>
        </a>

        <a href="/admin/events/manage_events.php" class="sidebar-item flex items-center p-3 rounded-lg <?php echo ($current_page == 'manage_events.php') ? 'active' : ''; ?>">
            <i class="fas fa-calendar-alt mr-3"></i>
            <span>Events</span>
        </a>

 

        <a href="/admin/articles/manage_articles.php" class="sidebar-item flex items-center p-3 rounded-lg <?php echo ($current_page == 'manage_articles.php') ? 'active' : ''; ?>">
            <i class="fas fa-newspaper mr-3"></i>
            <span>Articles</span>
        </a>

        <a href="/admin/reports/manage_reports.php" class="sidebar-item flex items-center p-3 rounded-lg <?php echo ($current_page == 'manage_reports.php' || $current_page == 'add_report.php' || $current_page == 'edit_report.php' || $current_page == 'view_report.php' || $current_page == 'view_temp_report.php') ? 'active' : ''; ?>">
            <i class="fas fa-chart-bar mr-3"></i>
            <span>Reports</span>
        </a>

        <a href="#" class="sidebar-item flex items-center p-3 rounded-lg">
            <i class="fas fa-cog mr-3"></i>
            <span>Settings</span>
        </a>
        <a href="/admin/admin-logout.php" class="sidebar-item flex items-center p-3 rounded-lg text-red-300">
            <i class="fas fa-sign-out-alt mr-3"></i>
            <span>Logout</span>
        </a>
    </nav>
</div>
