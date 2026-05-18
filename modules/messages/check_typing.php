<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['is_typing' => false]);
    exit();
}

$user_id = $_SESSION['user_id'];
$other_user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

if ($other_user_id == 0) {
    echo json_encode(['is_typing' => false]);
    exit();
}

// Check typing status
$typing_file = sys_get_temp_dir() . "/typing_{$user_id}_{$other_user_id}.txt";
$is_typing = false;

if (file_exists($typing_file)) {
    $timestamp = intval(file_get_contents($typing_file));
    // Typing indicator expires after 3 seconds of no activity
    if (time() - $timestamp < 3) {
        $is_typing = true;
    } else {
        unlink($typing_file);
    }
}

echo json_encode(['is_typing' => $is_typing]);
?>