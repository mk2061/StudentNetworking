<?php
require_once __DIR__ . '/../config/database.php';

// User authentication functions
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function redirect($url) {
    header("Location: " . SITE_URL . $url);
    exit();
}

// Get user by ID
function getUserById($user_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Get user connections count
function getConnectionsCount($user_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM connections WHERE (sender_id = ? OR receiver_id = ?) AND status = 'accepted'");
    $stmt->execute([$user_id, $user_id]);
    $result = $stmt->get_result();
    return $result->fetch_assoc()['count'];
}

// Get posts for feed
function getFeedPosts($user_id, $limit = 10, $offset = 0) {
    global $conn;
    
    $sql = "SELECT p.*, u.full_name, u.profile_pic, u.major,
            (SELECT COUNT(*) FROM likes WHERE post_id = p.id AND type = 'post') as like_count,
            (SELECT COUNT(*) FROM comments WHERE post_id = p.id) as comment_count,
            (SELECT COUNT(*) FROM likes WHERE post_id = p.id AND user_id = ?) as user_liked
            FROM posts p
            JOIN users u ON p.user_id = u.id
            WHERE p.visibility = 'public' 
            OR (p.visibility = 'connections' AND EXISTS (
                SELECT 1 FROM connections WHERE status = 'accepted' 
                AND ((sender_id = ? AND receiver_id = p.user_id) 
                OR (receiver_id = ? AND sender_id = p.user_id))
            ))
            ORDER BY p.created_at DESC
            LIMIT $limit OFFSET $offset";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id, $user_id, $user_id]);
    return $stmt->get_result();
}

// Create post
function createPost($user_id, $content, $visibility = 'public', $tags = '') {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO posts (user_id, content, visibility, tags) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$user_id, $content, $visibility, $tags]);
}

// Like/unlike post
function toggleLike($user_id, $post_id) {
    global $conn;
    
    // Check if already liked
    $check = $conn->prepare("SELECT id FROM likes WHERE user_id = ? AND post_id = ? AND type = 'post'");
    $check->execute([$user_id, $post_id]);
    $result = $check->get_result();
    
    if ($result->num_rows > 0) {
        // Unlike
        $like = $result->fetch_assoc();
        $delete = $conn->prepare("DELETE FROM likes WHERE id = ?");
        $delete->execute([$like['id']]);
        $conn->query("UPDATE posts SET likes_count = likes_count - 1 WHERE id = $post_id");
        return false;
    } else {
        // Like
        $insert = $conn->prepare("INSERT INTO likes (user_id, post_id, type) VALUES (?, ?, 'post')");
        $insert->execute([$user_id, $post_id]);
        $conn->query("UPDATE posts SET likes_count = likes_count + 1 WHERE id = $post_id");
        return true;
    }
}

// Add comment
function addComment($user_id, $post_id, $content) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)");
    if ($stmt->execute([$post_id, $user_id, $content])) {
        $conn->query("UPDATE posts SET comments_count = comments_count + 1 WHERE id = $post_id");
        return true;
    }
    return false;
}

// Get comments for post
function getComments($post_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT c.*, u.full_name, u.profile_pic FROM comments c JOIN users u ON c.user_id = u.id WHERE c.post_id = ? ORDER BY c.created_at DESC");
    $stmt->execute([$post_id]);
    return $stmt->get_result();
}

// Send connection request
function sendConnectionRequest($sender_id, $receiver_id) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO connections (sender_id, receiver_id, status) VALUES (?, ?, 'pending')");
    return $stmt->execute([$sender_id, $receiver_id]);
}

// Accept connection request
function acceptConnectionRequest($request_id) {
    global $conn;
    $stmt = $conn->prepare("UPDATE connections SET status = 'accepted' WHERE id = ?");
    return $stmt->execute([$request_id]);
}

// Get pending connection requests
function getPendingRequests($user_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT c.*, u.full_name, u.profile_pic, u.major 
                           FROM connections c 
                           JOIN users u ON c.sender_id = u.id 
                           WHERE c.receiver_id = ? AND c.status = 'pending'");
    $stmt->execute([$user_id]);
    return $stmt->get_result();
}

// Get notifications
function getNotifications($user_id, $limit = 20) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT $limit");
    $stmt->execute([$user_id]);
    return $stmt->get_result();
}

// Add notification
function addNotification($user_id, $type, $content, $link = '') {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO notifications (user_id, type, content, link) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$user_id, $type, $content, $link]);
}

// Get suggested connections
function getSuggestedConnections($user_id, $limit = 5) {
    global $conn;
    $sql = "SELECT u.* FROM users u 
            WHERE u.id != ? 
            AND u.id NOT IN (
                SELECT CASE WHEN sender_id = ? THEN receiver_id ELSE sender_id END 
                FROM connections 
                WHERE (sender_id = ? OR receiver_id = ?)
            )
            AND u.major = (SELECT major FROM users WHERE id = ?)
            LIMIT ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id, $user_id, $user_id, $user_id, $user_id, $limit]);
    return $stmt->get_result();
}

// Safe echo function to prevent null errors
function safeEcho($value, $default = '') {
    return htmlspecialchars($value ?? $default);
}

// Update the sanitize function
function sanitize($input) {
    global $conn;
    if ($input === null) {
        return '';
    }
    return htmlspecialchars(strip_tags(trim($conn->real_escape_string($input))));
}

// Update timeAgo function to handle null
function timeAgo($timestamp) {
    if (empty($timestamp)) {
        return "Just now";
    }
    
    $time_ago = strtotime($timestamp);
    $current_time = time();
    $time_difference = $current_time - $time_ago;
    $seconds = $time_difference;
    
    $minutes = round($seconds / 60);
    $hours = round($seconds / 3600);
    $days = round($seconds / 86400);
    $weeks = round($seconds / 604800);
    $months = round($seconds / 2629440);
    $years = round($seconds / 31553280);
    
    if ($seconds <= 60) {
        return "Just now";
    } else if ($minutes <= 60) {
        return ($minutes == 1) ? "1 minute ago" : "$minutes minutes ago";
    } else if ($hours <= 24) {
        return ($hours == 1) ? "1 hour ago" : "$hours hours ago";
    } else if ($days <= 7) {
        return ($days == 1) ? "yesterday" : "$days days ago";
    } else if ($weeks <= 4.3) {
        return ($weeks == 1) ? "1 week ago" : "$weeks weeks ago";
    } else if ($months <= 12) {
        return ($months == 1) ? "1 month ago" : "$months months ago";
    } else {
        return ($years == 1) ? "1 year ago" : "$years years ago";
    }
}







// Add these functions to your includes/functions.php file

// Get media type from filename
function getMediaType($filename) {
    if (empty($filename)) return 'unknown';
    
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $image_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
    $video_extensions = ['mp4', 'webm', 'ogg', 'mov', 'avi'];
    
    if (in_array($ext, $image_extensions)) return 'image';
    if (in_array($ext, $video_extensions)) return 'video';
    return 'unknown';
}

// Delete media files from server
function deleteMediaFiles($media_url) {
    if (empty($media_url)) return true;
    
    $files = explode(',', $media_url);
    $success = true;
    
    foreach ($files as $file) {
        $file_path = UPLOAD_PATH . $file;
        if (file_exists($file_path)) {
            if (!unlink($file_path)) {
                $success = false;
            }
        }
    }
    
    return $success;
}

// Get post media as array
function getPostMedia($post_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT media_url FROM posts WHERE id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($post = $result->fetch_assoc()) {
        if (!empty($post['media_url'])) {
            return explode(',', $post['media_url']);
        }
    }
    
    return [];
}

// Check if user saved a post
function isPostSaved($user_id, $post_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT id FROM post_saves WHERE user_id = ? AND post_id = ?");
    $stmt->bind_param("ii", $user_id, $post_id);
    $stmt->execute();
    return $stmt->get_result()->num_rows > 0;
}

// Save/unsave post
function toggleSavePost($user_id, $post_id) {
    global $conn;
    
    if (isPostSaved($user_id, $post_id)) {
        $stmt = $conn->prepare("DELETE FROM post_saves WHERE user_id = ? AND post_id = ?");
        $stmt->bind_param("ii", $user_id, $post_id);
        $stmt->execute();
        return false; // Unsaved
    } else {
        $stmt = $conn->prepare("INSERT INTO post_saves (user_id, post_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $user_id, $post_id);
        $stmt->execute();
        return true; // Saved
    }
}

// Get trending posts
function getTrendingPosts($limit = 10) {
    global $conn;
    $sql = "SELECT p.*, u.full_name, u.profile_pic, u.major,
            (SELECT COUNT(*) FROM likes WHERE post_id = p.id) as like_count,
            (SELECT COUNT(*) FROM comments WHERE post_id = p.id) as comment_count,
            (SELECT COUNT(*) FROM post_shares WHERE post_id = p.id) as share_count
            FROM posts p
            JOIN users u ON p.user_id = u.id
            WHERE p.created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
            ORDER BY (like_count * 2 + comment_count + share_count * 3) DESC
            LIMIT ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    return $stmt->get_result();
}



// Get unread notifications count
function getUnreadNotificationsCount($user_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM notifications WHERE user_id = ? AND is_read = 0");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc()['count'];
}

// Get notification icon class
function getNotificationIconClass($type) {
    $icons = [
        'like' => 'bi-heart-fill',
        'comment' => 'bi-chat-fill',
        'connection' => 'bi-person-plus-fill',
        'message' => 'bi-envelope-fill',
        'event' => 'bi-calendar-event-fill',
        'group' => 'bi-people-fill'
    ];
    return $icons[$type] ?? 'bi-bell-fill';
}

// Get notification color class
function getNotificationColorClass($type) {
    $colors = [
        'like' => 'danger',
        'comment' => 'primary',
        'connection' => 'success',
        'message' => 'warning',
        'event' => 'info',
        'group' => 'secondary'
    ];
    return $colors[$type] ?? 'secondary';
}
?>