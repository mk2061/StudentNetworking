<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

if (!isLoggedIn()) {
    exit();
}

$user_id = $_SESSION['user_id'];
$other_user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
$last_id = isset($_GET['last_id']) ? intval($_GET['last_id']) : 0;
$get_all = isset($_GET['all']) ? intval($_GET['all']) : 0;

if ($other_user_id == 0) {
    exit();
}

// Get messages - either all or only new ones
if ($get_all == 1) {
    // Get all messages for initial load
    $sql = "SELECT m.*, u.full_name, u.profile_pic 
            FROM messages m 
            JOIN users u ON m.sender_id = u.id 
            WHERE (m.sender_id = ? AND m.receiver_id = ?) OR (m.sender_id = ? AND m.receiver_id = ?)
            ORDER BY m.created_at ASC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiii", $user_id, $other_user_id, $other_user_id, $user_id);
} else {
    // Get only messages after last_id (new messages)
    $sql = "SELECT m.*, u.full_name, u.profile_pic 
            FROM messages m 
            JOIN users u ON m.sender_id = u.id 
            WHERE ((m.sender_id = ? AND m.receiver_id = ?) OR (m.sender_id = ? AND m.receiver_id = ?))
            AND m.id > ?
            ORDER BY m.created_at ASC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiiii", $user_id, $other_user_id, $other_user_id, $user_id, $last_id);
}

$stmt->execute();
$messages = $stmt->get_result();

while($msg = $messages->fetch_assoc()):
    $is_sent = $msg['sender_id'] == $user_id;
    ?>
    <div class="message <?php echo $is_sent ? 'sent' : 'received'; ?>" data-message-id="<?php echo $msg['id']; ?>">
        <div class="message-bubble">
            <p class="message-text"><?php echo nl2br(htmlspecialchars($msg['message'])); ?></p>
            <small class="message-time">
                <?php echo date('h:i A', strtotime($msg['created_at'])); ?>
                <?php if($is_sent && $msg['is_read']): ?>
                <i class="bi bi-check-all"></i>
                <?php elseif($is_sent): ?>
                <i class="bi bi-check"></i>
                <?php endif; ?>
            </small>
        </div>
    </div>
<?php endwhile; ?>