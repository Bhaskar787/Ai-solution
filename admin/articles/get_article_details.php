<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo 'Unauthorized access';
    exit;
}

// Include database connection
require_once '../db_connection.php';

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo 'Invalid request method';
    exit;
}

// Get article ID
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

if ($id <= 0) {
    echo 'Invalid article ID';
    exit;
}

// Fetch article details
$link = get_db_connection();
$stmt = mysqli_prepare($link, "SELECT * FROM articles WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$article = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);
mysqli_close($link);

if (!$article) {
    echo 'Article not found';
    exit;
}

// Display article details in modal format
?>
<div class="space-y-4">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">ID</label>
            <p class="text-sm text-gray-900"><?php echo $article['id']; ?></p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Status</label>
            <span class="status-badge status-<?php echo $article['status']; ?>">
                <?php echo ucfirst($article['status']); ?>
            </span>
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Title</label>
        <p class="text-sm text-gray-900"><?php echo htmlspecialchars($article['title']); ?></p>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Author</label>
            <p class="text-sm text-gray-900"><?php echo htmlspecialchars($article['author']); ?></p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Category</label>
            <p class="text-sm text-gray-900"><?php echo ucfirst($article['category']); ?></p>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Publication Date</label>
            <p class="text-sm text-gray-900"><?php echo date('M j, Y', strtotime($article['date'])); ?></p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Reading Time</label>
            <p class="text-sm text-gray-900"><?php echo $article['reading_time']; ?> min read</p>
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Content Preview</label>
        <div class="text-sm text-gray-900 max-h-32 overflow-y-auto border border-gray-200 rounded p-2">
            <?php echo nl2br(htmlspecialchars(substr($article['content'], 0, 300))); ?>
            <?php if (strlen($article['content']) > 300): ?>
                <span class="text-gray-500">... (truncated)</span>
            <?php endif; ?>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4 text-xs text-gray-500">
        <div>
            <label class="block font-medium">Created</label>
            <p><?php echo date('M j, Y H:i', strtotime($article['created_at'])); ?></p>
        </div>
        <div>
            <label class="block font-medium">Last Updated</label>
            <p><?php echo date('M j, Y H:i', strtotime($article['updated_at'])); ?></p>
        </div>
    </div>
</div>
