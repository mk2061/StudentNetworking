<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

if (!isLoggedIn()) {
    echo 'error';
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sender_id = $_SESSION['user_id'];
    $receiver_id = intval($_POST['user_id']);
    
    if (sendConnectionRequest($sender_id, $receiver_id)) {
        $sender_info = getUserById($sender_id);
        $notification_content = $sender_info['full_name'] . ' wants to connect with you';
        addNotification($receiver_id, 'connection', $notification_content, 'modules/connections/');
        echo 'success';
    } else {
        echo 'error';
    }
}
?>