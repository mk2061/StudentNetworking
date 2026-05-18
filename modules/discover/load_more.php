<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

if (!isLoggedIn()) {
    exit();
}

$user_id = $_SESSION['user_id'];
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$offset = ($page - 1) * 10;

$sql = "SELECT u.*, 
        (SELECT COUNT(*) FROM connections 
         WHERE (sender_id = ? AND receiver_id = u.id) 
            OR (receiver_id = ? AND sender_id = u.id) AND status = 'accepted') as is_connected,
        (SELECT COUNT(*) FROM connections 
         WHERE sender_id = ? AND receiver_id = u.id AND status = 'pending') as request_sent,
        (SELECT COUNT(*) FROM connections 
         WHERE sender_id = u.id AND receiver_id = ? AND status = 'pending') as request_received,
        (SELECT COUNT(*) FROM connections c2 
         WHERE (c2.sender_id = ? AND c2.receiver_id = u.id AND c2.status = 'accepted')
            OR (c2.receiver_id = ? AND c2.sender_id = u.id AND c2.status = 'accepted')) as mutual_count
        FROM users u 
        WHERE u.id != ?";

$params = [$user_id, $user_id, $user_id, $user_id, $user_id, $user_id, $user_id];
$types = "iiiiiii";

if (!empty($search)) {
    $sql .= " AND (u.full_name LIKE ? OR u.major LIKE ? OR u.university LIKE ?)";
    $search_param = "%$search%";
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= "sss";
}

if ($filter == 'same_major') {
    $user_major = getUserById($user_id)['major'];
    $sql .= " AND u.major = ?";
    $params[] = $user_major;
    $types .= "s";
} elseif ($filter == 'same_university') {
    $user_university = getUserById($user_id)['university'];
    $sql .= " AND u.university = ?";
    $params[] = $user_university;
    $types .= "s";
} elseif ($filter == 'suggested') {
    $sql .= " AND (SELECT COUNT(*) FROM connections 
             WHERE ((sender_id = ? AND receiver_id = u.id) 
                OR (receiver_id = ? AND sender_id = u.id)) AND status = 'accepted') = 0";
    $params[] = $user_id;
    $params[] = $user_id;
    $types .= "ii";
}

$sql .= " ORDER BY u.full_name LIMIT 10 OFFSET ?";
$params[] = $offset;
$types .= "i";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$students = $stmt->get_result();

if ($students->num_rows > 0):
    while($student = $students->fetch_assoc()):
?>
<div class="student-card" data-student-id="<?php echo $student['id']; ?>">
    <div class="card-content">
        <div class="profile-section">
            <div class="avatar-wrapper">
                <img src="<?php echo SITE_URL; ?>assets/uploads/<?php echo htmlspecialchars($student['profile_pic'] ?? 'default-avatar.png'); ?>" 
                     class="student-avatar" alt="Profile">
                <?php if(rand(0, 1)): ?>
                <div class="online-badge"></div>
                <?php endif; ?>
                <?php if($student['is_verified'] ?? false): ?>
                <div class="verified-badge">
                    <i class="bi bi-check-lg"></i>
                </div>
                <?php endif; ?>
            </div>
            <div class="student-info">
                <div class="student-name">
                    <?php echo htmlspecialchars($student['full_name']); ?>
                    <?php if($student['year_of_study']): ?>
                    <span class="badge bg-light text-dark" style="font-size: 10px;">
                        Year <?php echo $student['year_of_study']; ?>
                    </span>
                    <?php endif; ?>
                </div>
                <div class="student-major">
                    <i class="bi bi-mortarboard"></i> <?php echo htmlspecialchars($student['major']); ?>
                </div>
                <div class="student-university">
                    <i class="bi bi-building"></i> <?php echo htmlspecialchars($student['university']); ?>
                </div>
            </div>
        </div>
        
        <?php if(!empty($student['bio'])): ?>
        <div class="student-bio">
            <?php echo htmlspecialchars(substr($student['bio'], 0, 100)); ?><?php echo strlen($student['bio']) > 100 ? '...' : ''; ?>
        </div>
        <?php endif; ?>
        
        <div class="stats-row">
            <div class="stat-item">
                <i class="bi bi-people"></i>
                <span class="stat-value"><?php echo $student['mutual_count'] ?? 0; ?></span>
                <span>mutual</span>
            </div>
            <div class="stat-item">
                <i class="bi bi-calendar"></i>
                <span>Joined <?php echo date('M Y', strtotime($student['created_at'])); ?></span>
            </div>
        </div>
        
        <?php if(!empty($student['interests'])): ?>
        <div class="interests-section">
            <?php 
            $interests = explode(',', $student['interests']);
            foreach(array_slice($interests, 0, 3) as $interest): 
            ?>
            <span class="interests-tag">#<?php echo htmlspecialchars(trim($interest)); ?></span>
            <?php endforeach; ?>
            <?php if(count($interests) > 3): ?>
            <span class="interests-tag">+<?php echo count($interests) - 3; ?> more</span>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <div class="action-buttons">
            <?php if($student['is_connected'] > 0): ?>
                <button class="btn-connect connected" disabled>
                    <i class="bi bi-check-lg"></i> Connected
                </button>
            <?php elseif($student['request_sent'] > 0): ?>
                <button class="btn-connect pending" disabled>
                    <i class="bi bi-clock"></i> Request Sent
                </button>
            <?php elseif($student['request_received'] > 0): ?>
                <button class="btn-connect" onclick="acceptRequestFromDiscover(<?php echo $student['id']; ?>, this)">
                    <i class="bi bi-check-lg"></i> Accept Request
                </button>
            <?php else: ?>
                <button class="btn-connect" onclick="sendConnectionRequest(<?php echo $student['id']; ?>, this)">
                    <i class="bi bi-person-plus"></i> Connect
                </button>
            <?php endif; ?>
            
            <button class="btn-message" onclick="sendMessage(<?php echo $student['id']; ?>)">
                <i class="bi bi-chat"></i> Message
            </button>
        </div>
    </div>
</div>
<?php 
    endwhile;
endif;
?>