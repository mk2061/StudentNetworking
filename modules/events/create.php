<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

if (!isLoggedIn()) {
    redirect('modules/auth/login.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $title = sanitize($_POST['title']);
    $description = sanitize($_POST['description']);
    $event_date = $_POST['event_date'];
    $location = sanitize($_POST['location']);
    $max_attendees = !empty($_POST['max_attendees']) ? intval($_POST['max_attendees']) : NULL;
    
    $stmt = $conn->prepare("INSERT INTO events (title, description, event_date, location, max_attendees, created_by) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssii", $title, $description, $event_date, $location, $max_attendees, $user_id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Event created successfully!";
    } else {
        $_SESSION['error'] = "Failed to create event. Please try again.";
    }
    
    redirect('modules/events/');
}
?>