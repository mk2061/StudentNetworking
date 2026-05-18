<?php
require_once '../config/database.php';
require_once 'functions.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['error' => 'Not logged in']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $notification_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    
    if ($notification_id > 0) {
        // Mark single notification as read
        $stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $notification_id, $user_id);
        $stmt->execute();
    } else {
        // Mark all as read
        $stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
    }
    
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Invalid request method']);
}
?>