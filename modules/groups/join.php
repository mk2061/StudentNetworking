<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

if (!isLoggedIn()) {
    echo 'error';
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $group_id = intval($_POST['group_id']);
    
    // Check if already a member
    $check = $conn->prepare("SELECT id FROM group_members WHERE group_id = ? AND user_id = ?");
    $check->bind_param("ii", $group_id, $user_id);
    $check->execute();
    
    if ($check->get_result()->num_rows == 0) {
        $stmt = $conn->prepare("INSERT INTO group_members (group_id, user_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $group_id, $user_id);
        
        if ($stmt->execute()) {
            echo 'success';
        } else {
            echo 'error';
        }
    } else {
        echo 'already_member';
    }
}
?>