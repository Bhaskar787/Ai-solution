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

    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $author = trim($_POST['author']);
    $category = $_POST['category'];
    $date = $_POST['date'];
    $status = $_POST['status'];
    $reading_time = (int)$_POST['reading_time'];

    // Validate input
    $errors = [];
    if (empty($title)) $errors[] = "Title is required";
    if (empty($content)) $errors[] = "Content is required";
    if (empty($author)) $errors[] = "Author is required";
    if (empty($category)) $errors[] = "Category is required";
    if (empty($date)) $errors[] = "Date is required";

    if (empty($errors)) {
        // Insert article
        $stmt = mysqli_prepare($link, "INSERT INTO articles (title, content, author, category, date, status, reading_time) VALUES (?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssssssi", $title, $content, $author, $category, $date, $status, $reading_time);

        if (mysqli_stmt_execute($stmt)) {
            $success_message = "Article added successfully!";
            // Redirect to manage articles
            header('Location: manage_articles.php?success=1');
            exit;
        } else {
            $error_message = "Failed to add article: " . mysqli_error($link);
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
    <title>Add Article | AI-Solutions Admin</title>
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
                    <h1 class="text-3xl font-bold text-gray-900">Add New Article</h1>
                    <p class="text-gray-600">Create a new article for the website</p>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="manage_articles.php" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Articles
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
                        <!-- Title -->
                        <div class="md:col-span-2">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                            <input
                                type="text"
                                id="title"
                                name="title"
                                required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>"
                            >
                        </div>

                        <!-- Author -->
                        <div>
                            <label for="author" class="block text-sm font-medium text-gray-700 mb-2">Author *</label>
                            <input
                                type="text"
                                id="author"
                                name="author"
                                required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                value="<?php echo isset($_POST['author']) ? htmlspecialchars($_POST['author']) : ''; ?>"
                            >
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                            <select
                                id="category"
                                name="category"
                                required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            >
                                <option value="">Select Category</option>
                                <option value="ai" <?php echo (isset($_POST['category']) && $_POST['category'] === 'ai') ? 'selected' : ''; ?>>Artificial Intelligence</option>
                                <option value="experience" <?php echo (isset($_POST['category']) && $_POST['category'] === 'experience') ? 'selected' : ''; ?>>Employee Experience</option>
                                <option value="innovation" <?php echo (isset($_POST['category']) && $_POST['category'] === 'innovation') ? 'selected' : ''; ?>>Innovation</option>
                                <option value="research" <?php echo (isset($_POST['category']) && $_POST['category'] === 'research') ? 'selected' : ''; ?>>Research</option>
                                <option value="technology" <?php echo (isset($_POST['category']) && $_POST['category'] === 'technology') ? 'selected' : ''; ?>>Technology</option>
                            </select>
                        </div>

                        <!-- Date -->
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Publication Date *</label>
                            <input
                                type="date"
                                id="date"
                                name="date"
                                required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                value="<?php echo isset($_POST['date']) ? htmlspecialchars($_POST['date']) : date('Y-m-d'); ?>"
                            >
                        </div>

                        <!-- Reading Time -->
                        <div>
                            <label for="reading_time" class="block text-sm font-medium text-gray-700 mb-2">Reading Time (minutes)</label>
                            <input
                                type="number"
                                id="reading_time"
                                name="reading_time"
                                min="1"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                value="<?php echo isset($_POST['reading_time']) ? htmlspecialchars($_POST['reading_time']) : '5'; ?>"
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
                                <option value="draft" <?php echo (isset($_POST['status']) && $_POST['status'] === 'draft') || !isset($_POST['status']) ? 'selected' : ''; ?>>Draft</option>
                                <option value="published" <?php echo (isset($_POST['status']) && $_POST['status'] === 'published') ? 'selected' : ''; ?>>Published</option>
                            </select>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="mt-6">
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Content *</label>
                        <textarea
                            id="content"
                            name="content"
                            rows="10"
                            required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Write your article content here..."
                        ><?php echo isset($_POST['content']) ? htmlspecialchars($_POST['content']) : ''; ?></textarea>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="mt-8 flex justify-end space-x-4">
                        <a href="manage_articles.php" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                            Cancel
                        </a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg">
                            <i class="fas fa-save mr-2"></i>Add Article
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

        // Check for success parameter in URL
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('success') === '1') {
            showNotification('Article added successfully!', 'success');
        }
    </script>
</body>
</html>
