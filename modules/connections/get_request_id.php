<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['error' => 'Not logged in']);
    exit();
}

if (isset($_GET['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $other_user_id = intval($_GET['user_id']);
    
    $stmt = $conn->prepare("SELECT id FROM connections WHERE sender_id = ? AND receiver_id = ? AND status = 'pending'");
    $stmt->bind_param("ii", $other_user_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($request = $result->fetch_assoc()) {
        echo json_encode(['success' => true, 'request_id' => $request['id']]);
    } else {
        echo json_encode(['success' => false, 'error' => 'No pending request found']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'User ID required']);
}
?>