<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../admin_login/admin-login.php');
    exit;
}

// Get event ID from URL
$event_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$event_id) {
    header('Location: manage_events.php?error=invalid_id');
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
    header('Location: manage_events.php?error=event_not_found');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get POST data and sanitize
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $date = isset($_POST['date']) ? trim($_POST['date']) : '';
    $location = isset($_POST['location']) ? trim($_POST['location']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $status = isset($_POST['status']) ? trim($_POST['status']) : 'upcoming';
    $category = isset($_POST['category']) ? trim($_POST['category']) : '';

    // Validate required fields
    if (empty($title) || empty($date) || empty($location)) {
        header("Location: edit_event.php?id=$event_id&error=missing_fields");
        exit;
    }

    // Handle image upload
    $image_path = $event['image_path']; // Keep existing image by default
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $file_name = uniqid() . '.' . $file_extension;
        $target_file = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image_path = 'admin/uploads/' . $file_name;
            // Optionally delete old image file
            if (!empty($event['image_path']) && file_exists('../' . $event['image_path'])) {
                unlink('../' . $event['image_path']);
            }
        }
    }

    // Prepare statement to prevent SQL injection
    $stmt = mysqli_prepare($link, "UPDATE events SET title = ?, date = ?, location = ?, description = ?, status = ?, image_path = ?, category = ? WHERE id = ?");
    if (!$stmt) {
        header("Location: edit_event.php?id=$event_id&error=db_error");
        exit;
    }

    // Bind parameters and execute
    mysqli_stmt_bind_param($stmt, "sssssssi", $title, $date, $location, $description, $status, $image_path, $category, $event_id);

    if (mysqli_stmt_execute($stmt)) {
        // Success - redirect with success message
        header('Location: manage_events.php?success=2');
    } else {
        // Error - redirect with error message
        header("Location: edit_event.php?id=$event_id&error=update_failed");
    }

    // Close statement and connection
    mysqli_stmt_close($stmt);
    mysqli_close($link);
    exit;
}

// Close connection
mysqli_close($link);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event | AI-Solutions Admin</title>
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
                    <h1 class="text-3xl font-bold text-gray-900">Edit Event</h1>
                    <p class="text-gray-600">Update event details for the Photo Gallery</p>
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

            <!-- Edit Event Form -->
            <div class="card p-8">
                <form action="edit_event.php?id=<?php echo $event_id; ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Title -->
                        <div class="md:col-span-2">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Event Title *</label>
                            <input
                                type="text"
                                id="title"
                                name="title"
                                required
                                value="<?php echo htmlspecialchars($event['title']); ?>"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Enter event title"
                            >
                        </div>

                        <!-- Date -->
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Event Date *</label>
                            <input
                                type="date"
                                id="date"
                                name="date"
                                required
                                value="<?php echo $event['date']; ?>"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            >
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select
                                id="status"
                                name="status"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            >
                                <option value="upcoming" <?php echo $event['status'] === 'upcoming' ? 'selected' : ''; ?>>Upcoming</option>
                                <option value="past" <?php echo $event['status'] === 'past' ? 'selected' : ''; ?>>Past Event</option>
                            </select>
                        </div>

                        <!-- Location -->
                        <div class="md:col-span-2">
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Location *</label>
                            <input
                                type="text"
                                id="location"
                                name="location"
                                required
                                value="<?php echo htmlspecialchars($event['location']); ?>"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Enter event location"
                            >
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                            <input
                                type="text"
                                id="category"
                                name="category"
                                value="<?php echo htmlspecialchars($event['category']); ?>"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="e.g., conference, summit, expo"
                            >
                        </div>

                        <!-- Current Image -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Current Image</label>
                            <?php if (!empty($event['image_path'])): ?>
                                <div class="mb-2">
                                    <img src="../../<?php echo htmlspecialchars($event['image_path']); ?>" alt="Current event image" class="w-24 h-24 object-cover rounded-lg border">
                                </div>
                                <p class="text-sm text-gray-500">Leave blank to keep current image</p>
                            <?php else: ?>
                                <p class="text-sm text-gray-500">No image uploaded</p>
                            <?php endif; ?>
                        </div>

                        <!-- Image Upload -->
                        <div class="md:col-span-2">
                            <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Update Event Image</label>
                            <input
                                type="file"
                                id="image"
                                name="image"
                                accept="image/*"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            >
                            <p class="text-sm text-gray-500 mt-1">Upload a new image to replace the current one (optional)</p>
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea
                                id="description"
                                name="description"
                                rows="6"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Enter event description"
                            ><?php echo htmlspecialchars($event['description']); ?></textarea>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                        <a href="manage_events.php" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-3 rounded-lg font-medium">
                            Cancel
                        </a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-medium">
                            <i class="fas fa-save mr-2"></i>Update Event
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

        // Check for success/error messages in URL
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('error')) {
            const error = urlParams.get('error');
            let errorMessage = 'An error occurred while updating the event.';
            if (error === 'missing_fields') {
                errorMessage = 'Please fill in all required fields.';
            } else if (error === 'db_error') {
                errorMessage = 'Database error occurred.';
            } else if (error === 'update_failed') {
                errorMessage = 'Failed to update event.';
            }
            showNotification(errorMessage, 'error');
        }
    </script>
</body>
</html>
