<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    http_response_code(403);
    echo 'Unauthorized';
    exit;
}

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Method not allowed';
    exit;
}

// Get event ID from POST data
$event_id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

if (!$event_id) {
    echo 'Invalid event ID';
    exit;
}

// Include database connection
require_once '../db_connection.php';

// Get database connection
$link = get_db_connection();

// Fetch event data
$stmt = mysqli_prepare($link, "SELECT * FROM events WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $event_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$event = mysqli_fetch_assoc($result);

if (!$event) {
    echo 'Event not found';
    mysqli_close($link);
    exit;
}

// Close connection
mysqli_close($link);
?>

<div class="space-y-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <h4 class="font-semibold text-gray-900">Event Title</h4>
            <p class="text-gray-700"><?php echo htmlspecialchars($event['title']); ?></p>
        </div>
        <div>
            <h4 class="font-semibold text-gray-900">Date</h4>
            <p class="text-gray-700"><?php echo date('F j, Y', strtotime($event['date'])); ?></p>
        </div>
        <div>
            <h4 class="font-semibold text-gray-900">Location</h4>
            <p class="text-gray-700"><?php echo htmlspecialchars($event['location']); ?></p>
        </div>
        <div>
            <h4 class="font-semibold text-gray-900">Status</h4>
            <span class="status-badge status-<?php echo $event['status']; ?>">
                <?php echo ucfirst($event['status']); ?>
            </span>
        </div>
        <?php if (!empty($event['category'])): ?>
        <div>
            <h4 class="font-semibold text-gray-900">Category</h4>
            <p class="text-gray-700"><?php echo htmlspecialchars($event['category']); ?></p>
        </div>
        <?php endif; ?>
        <div>
            <h4 class="font-semibold text-gray-900">Created</h4>
            <p class="text-gray-700"><?php echo date('F j, Y g:i A', strtotime($event['created_at'])); ?></p>
        </div>
    </div>

    <?php if (!empty($event['image_path'])): ?>
    <div>
        <h4 class="font-semibold text-gray-900 mb-2">Event Image</h4>
        <img src="../../<?php echo htmlspecialchars($event['image_path']); ?>" alt="Event image" class="w-full max-w-sm h-48 object-cover rounded-lg border">
    </div>
    <?php endif; ?>

    <?php if (!empty($event['description'])): ?>
    <div>
        <h4 class="font-semibold text-gray-900 mb-2">Description</h4>
        <div class="text-gray-700 bg-gray-50 p-4 rounded-lg">
            <?php echo nl2br(htmlspecialchars($event['description'])); ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
        <a href="edit_event.php?id=<?php echo $event['id']; ?>" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">
            <i class="fas fa-edit mr-2"></i>Edit Event
        </a>
        <button onclick="deleteEvent(<?php echo $event['id']; ?>)" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm">
            <i class="fas fa-trash mr-2"></i>Delete Event
        </button>
    </div>
</div>
