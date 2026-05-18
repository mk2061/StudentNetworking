<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

if (!isLoggedIn()) {
    redirect('modules/auth/login.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $name = sanitize($_POST['name']);
    $subject = sanitize($_POST['subject']);
    $description = sanitize($_POST['description']);
    $max_members = intval($_POST['max_members']);
    $meeting_schedule = sanitize($_POST['meeting_schedule'] ?? '');
    
    $stmt = $conn->prepare("INSERT INTO study_groups (name, subject, description, created_by, max_members, meeting_schedule) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiis", $name, $subject, $description, $user_id, $max_members, $meeting_schedule);
    
    if ($stmt->execute()) {
        $group_id = $conn->insert_id;
        // Add creator as admin
        $member_stmt = $conn->prepare("INSERT INTO group_members (group_id, user_id, role) VALUES (?, ?, 'admin')");
        $member_stmt->bind_param("ii", $group_id, $user_id);
        $member_stmt->execute();
        
        $_SESSION['success'] = "Study group created successfully!";
        redirect('modules/groups/view.php?id=' . $group_id);
    } else {
        $_SESSION['error'] = "Failed to create group. Please try again.";
        redirect('modules/groups/');
    }
}
?>