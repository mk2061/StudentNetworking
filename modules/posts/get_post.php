<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

if (!isLoggedIn()) {
    echo json_encode(['error' => 'Not logged in']);
    exit();
}

if (isset($_GET['post_id'])) {
    $post_id = intval($_GET['post_id']);
    
    $stmt = $conn->prepare("
        SELECT p.*, u.full_name, u.profile_pic, u.major,
        (SELECT COUNT(*) FROM likes WHERE post_id = p.id) as like_count,
        (SELECT COUNT(*) FROM comments WHERE post_id = p.id) as comment_count,
        (SELECT COUNT(*) FROM likes WHERE post_id = p.id AND user_id = ?) as user_liked
        FROM posts p
        JOIN users u ON p.user_id = u.id
        WHERE p.id = ?
    ");
    
    $user_id = $_SESSION['user_id'];
    $stmt->execute([$user_id, $post_id]);
    $result = $stmt->get_result();
    
    if ($post = $result->fetch_assoc()) {
        echo json_encode(['success' => true, 'post' => $post]);
    } else {
        echo json_encode(['error' => 'Post not found']);
    }
}
?>