<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

if (!isLoggedIn()) {
    redirect('modules/auth/login.php');
}

$user_id = $_SESSION['user_id'];

// Get all notifications
$sql = "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 50";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$notifications = $stmt->get_result();

// Mark all as read when viewing
$update = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ?");
$update->bind_param("i", $user_id);
$update->execute();
?>
<?php include_once '../../includes/header.php'; ?>

<style>
.notification-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    background: white;
    border-bottom: 1px solid #e5e7eb;
    transition: background 0.2s;
    cursor: pointer;
    text-decoration: none;
    color: inherit;
}

.notification-item:active {
    background: #f3f4f6;
}

.notification-icon {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f3f4f6;
}

.notification-icon i {
    font-size: 24px;
}

.notification-icon.like { background: #fee2e2; color: #ef4444; }
.notification-icon.comment { background: #dbeafe; color: #3b82f6; }
.notification-icon.connection { background: #dcfce7; color: #10b981; }
.notification-icon.message { background: #fef3c7; color: #f59e0b; }
.notification-icon.event { background: #e0e7ff; color: #6366f1; }
.notification-icon.group { background: #f3e8ff; color: #a855f7; }

.notification-content {
    flex: 1;
}

.notification-text {
    font-size: 14px;
    color: #374151;
    margin-bottom: 4px;
}

.notification-time {
    font-size: 11px;
    color: #9ca3af;
}

.empty-state {
    text-align: center;
    padding: 60px 24px;
}

.empty-icon {
    font-size: 64px;
    color: #d1d5db;
    margin-bottom: 16px;
}

.empty-title {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 8px;
}

.empty-text {
    color: #6b7280;
    font-size: 14px;
}
</style>

<div class="notifications-page">
    <!-- Header -->
    <div class="bg-white border-bottom p-3 sticky-top" style="top: 0; z-index: 100;">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0">Notifications</h5>
            <?php if($notifications->num_rows > 0): ?>
            <button class="btn btn-sm btn-link text-primary" onclick="markAllAsRead()">
                Mark all as read
            </button>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Notifications List -->
    <?php if($notifications->num_rows > 0): ?>
        <?php while($notif = $notifications->fetch_assoc()): ?>
        <a href="<?php echo SITE_URL . ($notif['link'] ?? 'modules/dashboard/'); ?>" class="notification-item <?php echo !$notif['is_read'] ? 'unread' : ''; ?>">
            <div class="notification-icon <?php echo getNotificationIconClass($notif['type']); ?>">
                <i class="<?php echo getNotificationIcon($notif['type']); ?>"></i>
            </div>
            <div class="notification-content">
                <div class="notification-text"><?php echo htmlspecialchars($notif['content']); ?></div>
                <div class="notification-time"><?php echo timeAgo($notif['created_at']); ?></div>
            </div>
            <?php if(!$notif['is_read']): ?>
            <div class="unread-dot">
                <div style="width: 8px; height: 8px; background: #6366f1; border-radius: 50%;"></div>
            </div>
            <?php endif; ?>
        </a>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="empty-state">
            <div class="empty-icon">
                <i class="bi bi-bell-slash"></i>
            </div>
            <div class="empty-title">No notifications yet</div>
            <div class="empty-text">When you get notifications, they'll show up here</div>
        </div>
    <?php endif; ?>
</div>

<?php include_once '../../includes/footer.php'; ?>

<script>
function markAllAsRead() {
    fetch('<?php echo SITE_URL; ?>includes/mark_read.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'id=0'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

// Helper functions for notification icons
function getNotificationIcon(type) {
    const icons = {
        'like': 'bi bi-heart-fill',
        'comment': 'bi bi-chat-fill',
        'connection': 'bi bi-person-plus-fill',
        'message': 'bi bi-envelope-fill',
        'event': 'bi bi-calendar-event-fill',
        'group': 'bi bi-people-fill'
    };
    return icons[type] || 'bi bi-bell-fill';
}

function getNotificationIconClass(type) {
    const classes = {
        'like': 'like',
        'comment': 'comment',
        'connection': 'connection',
        'message': 'message',
        'event': 'event',
        'group': 'group'
    };
    return classes[type] || '';
}
</script>
