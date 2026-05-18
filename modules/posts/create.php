<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

if (!isLoggedIn()) {
    redirect('modules/auth/login.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $content = sanitize($_POST['content']);
    $visibility = sanitize($_POST['visibility']);
    $tags = sanitize($_POST['tags']);
    
    if (createPost($user_id, $content, $visibility, $tags)) {
        $_SESSION['success'] = "Post created successfully!";
    } else {
        $_SESSION['error'] = "Failed to create post. Please try again.";
    }
    
    redirect('modules/dashboard/');
}
?>