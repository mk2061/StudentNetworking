<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['error' => 'Not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Get pending requests count
$pending_sql = "SELECT COUNT(*) as count FROM connections WHERE receiver_id = ? AND status = 'pending'";
$pending_stmt = $conn->prepare($pending_sql);
$pending_stmt->bind_param("i", $user_id);
$pending_stmt->execute();
$pending_count = $pending_stmt->get_result()->fetch_assoc()['count'];

// Get connections count
$connections_sql = "SELECT COUNT(*) as count FROM connections WHERE (sender_id = ? OR receiver_id = ?) AND status = 'accepted'";
$connections_stmt = $conn->prepare($connections_sql);
$connections_stmt->bind_param("ii", $user_id, $user_id);
$connections_stmt->execute();
$connections_count = $connections_stmt->get_result()->fetch_assoc()['count'];

echo json_encode([
    'success' => true,
    'pending_count' => $pending_count,
    'connections_count' => $connections_count
]);
?>