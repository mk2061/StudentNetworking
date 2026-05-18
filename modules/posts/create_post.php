<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$content = $_POST['content'] ?? '';
$visibility = $_POST['visibility'] ?? 'public';
$tags = $_POST['tags'] ?? '';

// Handle file uploads
$media_files = [];
if (isset($_FILES['media_files'])) {
    $upload_dir = '../../assets/uploads/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    foreach ($_FILES['media_files']['tmp_name'] as $key => $tmp_name) {
        if ($_FILES['media_files']['error'][$key] === UPLOAD_ERR_OK) {
            $file_name = time() . '_' . $key . '_' . $_FILES['media_files']['name'][$key];
            $file_path = $upload_dir . $file_name;
            
            if (move_uploaded_file($tmp_name, $file_path)) {
                $media_files[] = $file_name;
            }
        }
    }
}

$media_url = !empty($media_files) ? implode(',', $media_files) : '';

$sql = "INSERT INTO posts (user_id, content, media_url, visibility, tags, created_at) 
        VALUES (?, ?, ?, ?, ?, NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("issss", $user_id, $content, $media_url, $visibility, $tags);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'post_id' => $stmt->insert_id]);
} else {
    echo json_encode(['success' => false, 'error' => 'Database error']);
}
?>