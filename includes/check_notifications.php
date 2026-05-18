<?php
require_once '../config/database.php';
require_once 'functions.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['error' => 'Not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Get unread notifications count
$sql = "SELECT COUNT(*) as count FROM notifications WHERE user_id = ? AND is_read = 0";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$count = $result->fetch_assoc()['count'];

// Get latest notifications for preview
$notifications_sql = "SELECT id, type, content, link, created_at, is_read 
                      FROM notifications 
                      WHERE user_id = ? 
                      ORDER BY created_at DESC 
                      LIMIT 5";
$notif_stmt = $conn->prepare($notifications_sql);
$notif_stmt->bind_param("i", $user_id);
$notif_stmt->execute();
$notifications = $notif_stmt->get_result();

$notifications_list = [];
while ($notif = $notifications->fetch_assoc()) {
    $notifications_list[] = [
        'id' => $notif['id'],
        'type' => $notif['type'],
        'content' => $notif['content'],
        'link' => $notif['link'],
        'time_ago' => timeAgo($notif['created_at']),
        'is_read' => (bool)$notif['is_read']
    ];
}

echo json_encode([
    'success' => true,
    'count' => $count,
    'notifications' => $notifications_list
]);
?>