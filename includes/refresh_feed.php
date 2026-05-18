<?php
require_once '../config/database.php';
require_once 'functions.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['error' => 'Not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Get new stories count
$stories_sql = "SELECT COUNT(*) as count FROM stories WHERE expires_at > NOW()";
$stories_result = $conn->query($stories_sql);
$stories_count = $stories_result->fetch_assoc()['count'];

// Get new posts count (posts from last 5 minutes)
$posts_sql = "SELECT COUNT(*) as count FROM posts WHERE created_at > DATE_SUB(NOW(), INTERVAL 5 MINUTE)";
$posts_result = $conn->query($posts_sql);
$new_posts = $posts_result->fetch_assoc()['count'];

// Get new notifications count
$notif_sql = "SELECT COUNT(*) as count FROM notifications WHERE user_id = ? AND is_read = 0 AND created_at > DATE_SUB(NOW(), INTERVAL 5 MINUTE)";
$notif_stmt = $conn->prepare($notif_sql);
$notif_stmt->bind_param("i", $user_id);
$notif_stmt->execute();
$notif_result = $notif_stmt->get_result();
$new_notifications = $notif_result->fetch_assoc()['count'];

echo json_encode([
    'success' => true,
    'stories_count' => $stories_count,
    'new_posts' => $new_posts,
    'new_notifications' => $new_notifications,
    'timestamp' => time()
]);
?>