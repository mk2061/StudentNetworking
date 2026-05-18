<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

header('Content-Type: text/plain');

if (!isLoggedIn()) {
    echo 'error';
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sender_id = $_SESSION['user_id'];
    $receiver_id = intval($_POST['receiver_id']);
    $message = sanitize($_POST['message']);
    
    if (empty($message)) {
        echo 'error';
        exit();
    }
    
    $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message, created_at) VALUES (?, ?, ?, NOW())");
    
    if ($stmt->execute([$sender_id, $receiver_id, $message])) {
        // Send notification
        addNotification($receiver_id, 'message', 
            $_SESSION['user_name'] . ' sent you a message', 
            'modules/messages/?user=' . $sender_id);
        echo 'success';
    } else {
        echo 'error';
    }
}
?>