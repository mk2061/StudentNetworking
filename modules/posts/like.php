<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['error' => 'Not logged in']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $post_id = intval($_POST['post_id']);
    
    $liked = toggleLike($user_id, $post_id);
    
    // Get updated like count
    $result = $conn->query("SELECT likes_count FROM posts WHERE id = $post_id");
    $likes = $result->fetch_assoc()['likes_count'];
    
    // If liked, send notification to post owner
    if ($liked) {
        $post = $conn->query("SELECT user_id FROM posts WHERE id = $post_id")->fetch_assoc();
        if ($post['user_id'] != $user_id) {
            addNotification($post['user_id'], 'like', 
                $_SESSION['user_name'] . ' liked your post', 
                'modules/dashboard/#post-' . $post_id);
        }
    }
    
    echo json_encode(['liked' => $liked, 'likes' => $likes]);
}
?>