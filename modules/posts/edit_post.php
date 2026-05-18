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
    $content = sanitize($_POST['content']);
    $visibility = sanitize($_POST['visibility']);
    $tags = sanitize($_POST['tags']);
    
    // Check if user owns the post
    $check = $conn->prepare("SELECT id FROM posts WHERE id = ? AND user_id = ?");
    $check->execute([$post_id, $user_id]);
    
    if ($check->get_result()->num_rows > 0) {
        $update = $conn->prepare("UPDATE posts SET content = ?, visibility = ?, tags = ?, updated_at = NOW() WHERE id = ?");
        
        if ($update->execute([$content, $visibility, $tags, $post_id])) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'Failed to update post']);
        }
    } else {
        echo json_encode(['error' => 'Unauthorized']);
    }
}
?>