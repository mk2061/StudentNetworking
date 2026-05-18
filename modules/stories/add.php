<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$caption = $_POST['caption'] ?? '';

if (!isset($_FILES['story_media']) || $_FILES['story_media']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'error' => 'No file uploaded']);
    exit;
}

$upload_dir = '../../assets/uploads/';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

$file_name = 'story_' . $user_id . '_' . time() . '_' . $_FILES['story_media']['name'];
$file_path = $upload_dir . $file_name;

$file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
$media_type = in_array($file_ext, ['mp4', 'webm', 'ogg']) ? 'video' : 'image';

if (move_uploaded_file($_FILES['story_media']['tmp_name'], $file_path)) {
    $sql = "INSERT INTO stories (user_id, media_url, media_type, caption, created_at, expires_at) 
            VALUES (?, ?, ?, ?, NOW(), DATE_ADD(NOW(), INTERVAL 24 HOUR))";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $user_id, $file_name, $media_type, $caption);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'story_id' => $stmt->insert_id]);
        header('Location: ../dashboard/');
        exit();
    } else {
        echo json_encode(['success' => false, 'error' => 'Database error']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to upload file']);
}

header('Location: ../dashboard/');
exit();

?>