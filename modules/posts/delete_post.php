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
    
    // Check if user owns the post
    $check = $conn->prepare("SELECT media_url FROM posts WHERE id = ? AND user_id = ?");
    $check->execute([$post_id, $user_id]);
    $result = $check->get_result();
    
    if ($post = $result->fetch_assoc()) {
        // Delete associated media files
        if (!empty($post['media_url'])) {
            $media_files = explode(',', $post['media_url']);
            foreach ($media_files as $file) {
                $file_path = UPLOAD_PATH . $file;
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }
        }
        
        // Delete post
        $delete = $conn->prepare("DELETE FROM posts WHERE id = ?");
        
        if ($delete->execute([$post_id])) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'Failed to delete post']);
        }
    } else {
        echo json_encode(['error' => 'Unauthorized']);
    }
}
?>