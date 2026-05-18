<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

if (!isLoggedIn()) {
    echo 'error';
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $event_id = intval($_POST['event_id']);
    
    // Check if already RSVP'd
    $check = $conn->prepare("SELECT id FROM event_attendees WHERE event_id = ? AND user_id = ?");
    $check->bind_param("ii", $event_id, $user_id);
    $check->execute();
    
    if ($check->get_result()->num_rows == 0) {
        $stmt = $conn->prepare("INSERT INTO event_attendees (event_id, user_id, status) VALUES (?, ?, 'going')");
        $stmt->bind_param("ii", $event_id, $user_id);
        
        if ($stmt->execute()) {
            // Notify event creator
            $event = $conn->query("SELECT created_by, title FROM events WHERE id = $event_id")->fetch_assoc();
            if ($event['created_by'] != $user_id) {
                addNotification($event['created_by'], 'event', 
                    $_SESSION['user_name'] . ' is going to your event: ' . $event['title'], 
                    'modules/events/');
            }
            echo 'success';
        } else {
            echo 'error';
        }
    } else {
        echo 'already_rsvpd';
    }
}
?>