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

if (!isset($_FILES['media']) || $_FILES['media']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'error' => 'No file uploaded']);
    exit;
}

$upload_dir = '../../assets/uploads/';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

$file_name = 'media_' . $user_id . '_' . time() . '_' . $_FILES['media']['name'];
$file_path = $upload_dir . $file_name;

if (move_uploaded_file($_FILES['media']['tmp_name'], $file_path)) {
    $sql = "INSERT INTO posts (user_id, content, media_url, post_type, created_at) 
            VALUES (?, ?, ?, 'media', NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $user_id, $caption, $file_name);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'post_id' => $stmt->insert_id]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Database error']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to upload file']);
}

header('Location: ../dashboard/');
exit();

?>