<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $receiver_id = intval($_POST['receiver_id']);
    $is_typing = intval($_POST['typing']);
    
    // Store typing status in session or cache
    // For simplicity, using a temporary table or you can use Redis/Memcached
    
    // Using a simple file-based approach for demo
    $typing_file = sys_get_temp_dir() . "/typing_{$receiver_id}_{$_SESSION['user_id']}.txt";
    
    if ($is_typing) {
        file_put_contents($typing_file, time());
    } else {
        if (file_exists($typing_file)) {
            unlink($typing_file);
        }
    }
    
    echo 'success';
}
?>