<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../admin_login/admin-login.php');
    exit;
}

require_once '../db_connection.php';

$link = get_db_connection();

// Get filter parameters
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'active';
$search_filter = isset($_GET['search']) ? $_GET['search'] : '';

/**
 * Modified query to include conversations and contact submissions
 * Conversations from messages table and contact submissions that don't have conversations yet
 */

$query = "
SELECT * FROM (
    SELECT c.conversation_id, c.user_name, c.user_email, c.last_message, c.last_message_time, c.unread_count, c.status, 'conversation' as source
    FROM conversations c
    UNION
    SELECT MD5(LOWER(cs.email)) as conversation_id, cs.full_name as user_name, cs.email as user_email, cs.job_details as last_message, cs.submission_date as last_message_time, 1 as unread_count, 'active' as status, 'contact' as source
    FROM contact_submissions cs
    WHERE NOT EXISTS (SELECT 1 FROM conversations c2 WHERE c2.conversation_id = MD5(LOWER(cs.email)))
) as combined
WHERE 1=1
";

$count_query = "
SELECT COUNT(*) as count FROM (
    SELECT c.conversation_id, c.user_name, c.user_email, c.last_message, c.status FROM conversations c
    UNION
    SELECT MD5(LOWER(cs.email)) as conversation_id, cs.full_name as user_name, cs.email as user_email, cs.job_details as last_message, 'active' as status FROM contact_submissions cs
    WHERE NOT EXISTS (SELECT 1 FROM conversations c2 WHERE c2.conversation_id = MD5(LOWER(cs.email)))
) as combined
WHERE 1=1
";
$params = [];
$types = "";

if (!empty($status_filter)) {
    $query .= " AND status = ?";
    $count_query .= " AND status = ?";
    $params[] = $status_filter;
    $types .= "s";
}

if (!empty($search_filter)) {
    $query .= " AND (user_name LIKE ? OR user_email LIKE ? OR last_message LIKE ?)";
    $count_query .= " AND (user_name LIKE ? OR user_email LIKE ? OR last_message LIKE ?)";
    $search_param = "%$search_filter%";
    $params = array_merge($params, [$search_param, $search_param, $search_param]);
    $types .= "sss";
}

$query .= " ORDER BY last_message_time DESC";

// Pagination
$per_page = 10;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$current_page = max(1, $current_page);
$offset = ($current_page - 1) * $per_page;

$count_params = $params;
$count_types = $types;

$query .= " LIMIT ? OFFSET ?";
$params[] = $per_page;
$params[] = $offset;
$types .= "ii";

// Execute count query
$stmt = mysqli_prepare($link, $count_query);
if ($stmt && !empty($count_params)) {
    mysqli_stmt_bind_param($stmt, $count_types, ...$count_params);
}
if ($stmt) {
    mysqli_stmt_execute($stmt);
    $count_result = mysqli_stmt_get_result($stmt);
    $total_conversations = mysqli_fetch_assoc($count_result)['count'];
    mysqli_stmt_close($stmt);
}

$total_pages = ceil($total_conversations / $per_page);

// Execute main query
$stmt = mysqli_prepare($link, $query);
if ($stmt && !empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
if ($stmt) {
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $conversations = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
}

mysqli_close($link);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conversations | AI-Solutions Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../../css/admin-dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="dashboard-bg">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <?php include '../components/admin-navbar.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Conversations</h1>
                    <p class="text-gray-600">Manage email conversations with users</p>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-600">Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</span>
                    <a href="../admin-logout.php" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </a>
                </div>
            </div>

            <!-- Filters and Search -->
            <div class="card p-6 mb-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex flex-wrap gap-3">
                        <select id="status-filter" class="border border-gray-300 rounded-lg px-4 py-2">
                            <option value="active" <?php echo $status_filter === 'active' ? 'selected' : ''; ?>>Active</option>
                            <option value="archived" <?php echo $status_filter === 'archived' ? 'selected' : ''; ?>>Archived</option>
                        </select>
                        <button id="clear-filters" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg">Clear Filters</button>
                        <button id="refresh-button" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                            <i class="fas fa-sync-alt mr-2"></i>Refresh
                        </button>
                        <button id="fetch-emails-button" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                            <i class="fas fa-envelope mr-2"></i>Fetch Emails
                        </button>
                    </div>
                    <div class="relative w-full md:w-64">
                        <input type="text" id="search-input" placeholder="Search conversations..." class="search-input w-full border border-gray-300 rounded-lg pl-10 pr-4 py-2" value="<?php echo htmlspecialchars($search_filter); ?>">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>
            </div>

            <!-- Conversations Table -->
            <div class="card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Message</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unread</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php if (empty($conversations)): ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No conversations found</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($conversations as $conversation): ?>
                                    <tr class="table-row">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($conversation['user_name']); ?></div>
                                            <div class="text-sm text-gray-500"><?php echo htmlspecialchars($conversation['user_email']); ?></div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500"><?php echo htmlspecialchars(substr($conversation['last_message'], 0, 50)); ?>...</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo date('M j, Y H:i', strtotime($conversation['last_message_time'])); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php if ($conversation['unread_count'] > 0): ?>
                                                <span class="bg-red-500 text-white px-2 py-1 rounded-full text-xs"><?php echo $conversation['unread_count']; ?></span>
                                            <?php else: ?>
                                                <span class="text-gray-400">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="status-badge status-<?php echo $conversation['status']; ?>"><?php echo ucfirst($conversation['status']); ?></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button class="action-btn bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg" onclick="openChat(<?php echo json_encode($conversation['conversation_id']); ?>)">
                                                <i class="fas fa-comments"></i> Chat
                                            </button>
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
                        <div class="text-sm text-gray-700">Page <?php echo $current_page; ?> of <?php echo $total_pages; ?></div>
                        <div class="flex space-x-2">
                            <?php if ($current_page > 1): ?>
                                <a href="?page=<?php echo $current_page - 1; ?>&status=<?php echo urlencode($status_filter); ?>&search=<?php echo urlencode($search_filter); ?>" class="px-3 py-1 rounded border border-gray-300 hover:bg-gray-100">Previous</a>
                            <?php endif; ?>
                            <?php for ($i = max(1, $current_page - 2); $i <= min($total_pages, $current_page + 2); $i++): ?>
                                <?php if ($i == $current_page): ?>
                                    <span class="px-3 py-1 rounded bg-blue-500 text-white"><?php echo $i; ?></span>
                                <?php else: ?>
                                    <a href="?page=<?php echo $i; ?>&status=<?php echo urlencode($status_filter); ?>&search=<?php echo urlencode($search_filter); ?>" class="px-3 py-1 rounded border border-gray-300 hover:bg-gray-100"><?php echo $i; ?></a>
                                <?php endif; ?>
                            <?php endfor; ?>
                            <?php if ($current_page < $total_pages): ?>
                                <a href="?page=<?php echo $current_page + 1; ?>&status=<?php echo urlencode($status_filter); ?>&search=<?php echo urlencode($search_filter); ?>" class="px-3 py-1 rounded border border-gray-300 hover:bg-gray-100">Next</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Chat Modal -->
    <div id="chat-modal" class="fixed inset-0 z-50 hidden overflow-auto bg-black bg-opacity-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg w-full max-w-4xl mx-auto p-6 max-h-screen overflow-hidden">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-900">Chat with <span id="chat-user-name"></span></h3>
                    <button onclick="closeChat()" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div id="chat-messages" class="h-96 overflow-y-auto border border-gray-300 rounded-lg p-4 mb-4 bg-gray-50"></div>
                <form id="chat-form" enctype="multipart/form-data" class="space-y-2">
                    <input type="hidden" id="chat-conversation-id" name="conversation_id">
                    <textarea id="chat-message" name="message" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Type your message..."></textarea>
                    <div class="flex gap-2 items-center">
                        <input type="file" id="attachment" name="attachment" class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm" accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.txt">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openChat(conversationId) {
            document.getElementById('chat-conversation-id').value = conversationId;
            document.getElementById('chat-modal').classList.remove('hidden');
            loadMessages(conversationId);
        }

        function closeChat() {
            document.getElementById('chat-modal').classList.add('hidden');
            document.getElementById('chat-messages').innerHTML = '';
            // Refresh the page when chat is closed to update read/unread status
            location.reload();
        }

        function loadMessages(conversationId) {
            fetch('get_messages.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ conversation_id: conversationId }).toString()
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const messagesDiv = document.getElementById('chat-messages');
                    messagesDiv.innerHTML = '';
            data.messages.forEach(msg => {
                const msgDiv = document.createElement('div');
                msgDiv.className = 'mb-2 ' + (msg.sender_type === 'admin' ? 'text-right' : 'text-left');
                let attachmentHtml = '';
                if (msg.attachment) {
                    let attachmentUrl = msg.attachment;
                    // Fix attachment URL to be root-relative if not absolute
                    if (!attachmentUrl.startsWith('http') && !attachmentUrl.startsWith('/')) {
                        attachmentUrl = '/' + attachmentUrl;
                    }
                    const fileName = attachmentUrl.split('/').pop();
                    attachmentHtml = `<div class="mt-1"><a href="${attachmentUrl}" target="_blank" class="underline text-blue-600 hover:text-blue-800">${fileName}</a></div>`;
                }
                msgDiv.innerHTML = `<div class="inline-block max-w-xs px-3 py-2 rounded-lg ${msg.sender_type === 'admin' ? 'bg-blue-500 text-white' : 'bg-gray-300 text-gray-900'}">
<div class="text-sm">${msg.message}${attachmentHtml}</div>
<div class="text-xs opacity-75">${new Date(msg.created_at).toLocaleString()}</div>
</div>`;
                messagesDiv.appendChild(msgDiv);
            });
                    messagesDiv.scrollTop = messagesDiv.scrollHeight;
                }
            });
        }

        document.getElementById('chat-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch('send_reply.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('chat-message').value = '';
                    document.getElementById('attachment').value = ''; // Clear file input
                    loadMessages(formData.get('conversation_id'));
                } else {
                    alert('Failed to send message: ' + data.message);
                }
            });
        });

        // Filter functionality
        document.getElementById('status-filter').addEventListener('change', applyFilters);
        document.getElementById('search-input').addEventListener('input', applyFilters);
        document.getElementById('clear-filters').addEventListener('click', () => window.location.href = 'conversations.php');

        function applyFilters() {
            const status = document.getElementById('status-filter').value;
            const search = document.getElementById('search-input').value;
            let url = 'conversations.php?';
            const params = [];
            if (status !== 'active') params.push(`status=${encodeURIComponent(status)}`);
            if (search) params.push(`search=${encodeURIComponent(search)}`);
            if (params.length > 0) url += params.join('&');
            window.location.href = url;
        }

        document.getElementById('refresh-button').addEventListener('click', () => location.reload());

        // Fetch emails functionality
        function fetchEmails() {
            const button = document.getElementById('fetch-emails-button');
            if (button) {
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Fetching...';
            }

            fetch('fetch_emails.php', {
                method: 'GET'
            })
            .then(response => response.text())
            .then(data => {
                console.log('Email fetching completed: ' + data);
                if (button) {
                    button.disabled = false;
                    button.innerHTML = '<i class="fas fa-envelope mr-2"></i>Fetch Emails';
                }
                // Refresh the page to show new emails
                location.reload();
            })
            .catch(error => {
                console.error('Error fetching emails: ' + error.message);
                if (button) {
                    button.disabled = false;
                    button.innerHTML = '<i class="fas fa-envelope mr-2"></i>Fetch Emails';
                }
            });
        }

        document.getElementById('fetch-emails-button').addEventListener('click', fetchEmails);

        // Auto-fetch emails every 5 minutes (300000 milliseconds)
        setInterval(fetchEmails, 300000);

        // Also refresh the page every 5 minutes to show any updates
        setInterval(() => {
            location.reload();
        }, 300000);


    </script>
</body>
</html>
