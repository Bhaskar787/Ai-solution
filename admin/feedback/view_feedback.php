<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../admin_login/admin-login.php');
    exit;
}

// Check if ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Invalid feedback ID');
}

$id = (int)$_GET['id'];

// Include database connection
require_once '../db_connection.php';

// Get database connection
$link = get_db_connection();

// Get feedback details
$query = "SELECT * FROM feedback_submissions WHERE id = ?";
$stmt = mysqli_prepare($link, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$feedback = mysqli_fetch_assoc($result);

// Close connection
mysqli_close($link);

if (!$feedback) {
    die('Feedback not found');
}
?>

<div class="space-y-6">
    <!-- User Information -->
    <div class="bg-gray-50 rounded-lg p-6">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">User Information</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Full Name</label>
                <p class="mt-1 text-sm text-gray-900"><?php echo htmlspecialchars($feedback['full_name']); ?></p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <p class="mt-1 text-sm text-gray-900"><?php echo htmlspecialchars($feedback['email']); ?></p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Company</label>
                <p class="mt-1 text-sm text-gray-900"><?php echo htmlspecialchars($feedback['company'] ?: 'Not provided'); ?></p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Job Title</label>
                <p class="mt-1 text-sm text-gray-900"><?php echo htmlspecialchars($feedback['job_title'] ?: 'Not provided'); ?></p>
            </div>
        </div>
    </div>

    <!-- Feedback Details -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">Feedback Details</h4>
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Project/Service</label>
                <p class="mt-1 text-sm text-gray-900"><?php echo htmlspecialchars($feedback['project'] ?: 'Not specified'); ?></p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Rating</label>
                <div class="mt-1 flex items-center">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <i class="fas fa-star text-lg <?php echo $i <= $feedback['rating'] ? 'text-yellow-400' : 'text-gray-300'; ?>"></i>
                    <?php endfor; ?>
                    <span class="ml-2 text-sm text-gray-600">(<?php echo $feedback['rating']; ?>/5)</span>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Testimonial</label>
                <div class="mt-1 p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-900 whitespace-pre-wrap"><?php echo htmlspecialchars($feedback['testimonial']); ?></p>
                </div>
            </div>

            <?php if ($feedback['attachment']): ?>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Attachment</label>
                    <div class="mt-1">
                        <a href="<?php echo htmlspecialchars($feedback['attachment']); ?>"
                           target="_blank"
                           class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-download mr-2"></i>
                            View Attachment
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Status and Dates -->
    <div class="bg-gray-50 rounded-lg p-6">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">Status & Timeline</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <span class="mt-1 inline-flex px-2 py-1 text-xs font-semibold rounded-full
                    <?php
                    switch ($feedback['status']) {
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
                    <?php echo ucfirst($feedback['status']); ?>
                </span>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Submission Date</label>
                <p class="mt-1 text-sm text-gray-900">
                    <?php echo date('F j, Y \a\t g:i A', strtotime($feedback['submission_date'])); ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
        <button onclick="updateStatus(<?php echo $feedback['id']; ?>, 'reviewed')"
                class="px-4 py-2 bg-yellow-600 text-white text-sm font-medium rounded-md hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
            Mark as Reviewed
        </button>
        <button onclick="updateStatus(<?php echo $feedback['id']; ?>, 'published')"
                class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
            Publish
        </button>
        <button onclick="updateStatus(<?php echo $feedback['id']; ?>, 'archived')"
                class="px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
            Archive
        </button>
        <button onclick="deleteFeedback(<?php echo $feedback['id']; ?>)"
                class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
            Delete
        </button>
    </div>
</div>
