<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$post_id = $_POST['post_id'] ?? 0;
$comment = $_POST['comment'] ?? '';

if (!$post_id || empty($comment)) {
    echo json_encode(['success' => false, 'error' => 'Invalid data']);
    exit;
}

$sql = "INSERT INTO comments (post_id, user_id, content, created_at) VALUES (?, ?, ?, NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $post_id, $user_id, $comment);

if ($stmt->execute()) {
    // Update post comment count
    $update_sql = "UPDATE posts SET comments_count = comments_count + 1 WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("i", $post_id);
    $update_stmt->execute();
    
    echo json_encode(['success' => true, 'comment_id' => $stmt->insert_id]);
} else {
    echo json_encode(['success' => false, 'error' => 'Database error']);
}
?>