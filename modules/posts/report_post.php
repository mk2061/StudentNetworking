<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

if (!isLoggedIn()) {
    echo json_encode(['error' => 'Not logged in']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $post_id = intval($_POST['post_id']);
    $reason = sanitize($_POST['reason']);
    
    // Check if already reported
    $check = $conn->prepare("SELECT id FROM post_reports WHERE post_id = ? AND user_id = ?");
    $check->execute([$post_id, $user_id]);
    
    if ($check->get_result()->num_rows == 0) {
        $stmt = $conn->prepare("INSERT INTO post_reports (post_id, user_id, reason) VALUES (?, ?, ?)");
        
        if ($stmt->execute([$post_id, $user_id, $reason])) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'Failed to report post']);
        }
    } else {
        echo json_encode(['error' => 'Already reported']);
    }
}
?>