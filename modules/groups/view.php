<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

if (!isLoggedIn()) {
    redirect('modules/auth/login.php');
}

$group_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$user_id = $_SESSION['user_id'];

// Get group details
$group_sql = "SELECT g.*, u.full_name as creator_name 
              FROM study_groups g
              JOIN users u ON g.created_by = u.id
              WHERE g.id = ?";
$group_stmt = $conn->prepare($group_sql);
$group_stmt->bind_param("i", $group_id);
$group_stmt->execute();
$group = $group_stmt->get_result()->fetch_assoc();

if (!$group) {
    redirect('modules/groups/');
}

// Check if user is a member
$member_check = $conn->prepare("SELECT * FROM group_members WHERE group_id = ? AND user_id = ?");
$member_check->bind_param("ii", $group_id, $user_id);
$member_check->execute();
$is_member = $member_check->get_result()->num_rows > 0;

// Get group members
$members_sql = "SELECT gm.*, u.full_name, u.profile_pic, u.major 
                FROM group_members gm
                JOIN users u ON gm.user_id = u.id
                WHERE gm.group_id = ?
                ORDER BY gm.role DESC, u.full_name";
$members_stmt = $conn->prepare($members_sql);
$members_stmt->bind_param("i", $group_id);
$members_stmt->execute();
$members = $members_stmt->get_result();

// Get group discussions/posts
$discussions_sql = "SELECT p.*, u.full_name, u.profile_pic 
                    FROM posts p
                    JOIN users u ON p.user_id = u.id
                    WHERE p.group_id = ? AND p.post_type = 'group_discussion'
                    ORDER BY p.created_at DESC";
$discussions_stmt = $conn->prepare($discussions_sql);
$discussions_stmt->bind_param("i", $group_id);
$discussions_stmt->execute();
$discussions = $discussions_stmt->get_result();
?>
<?php include_once '../../includes/header.php'; ?>

<div class="group-view-container">
    <!-- Group Header -->
    <div class="bg-primary text-white p-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h4 class="fw-bold mb-2"><?php echo htmlspecialchars($group['name']); ?></h4>
                <p class="mb-1"><i class="bi bi-book"></i> <?php echo htmlspecialchars($group['subject']); ?></p>
                <p class="mb-0 small">Created by <?php echo htmlspecialchars($group['creator_name']); ?></p>
            </div>
            <?php if(!$is_member): ?>
            <button class="btn btn-light rounded-pill" onclick="joinGroup(<?php echo $group_id; ?>)">
                <i class="bi bi-person-plus"></i> Join Group
            </button>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Group Info -->
    <div class="bg-white p-3 border-bottom">
        <div class="row text-center">
            <div class="col">
                <div class="fw-bold h5 mb-0"><?php echo $members->num_rows; ?></div>
                <small class="text-muted">Members</small>
            </div>
            <div class="col">
                <div class="fw-bold h5 mb-0"><?php echo $group['max_members']; ?></div>
                <small class="text-muted">Max Capacity</small>
            </div>
            <div class="col">
                <div class="fw-bold h5 mb-0"><?php echo $discussions->num_rows; ?></div>
                <small class="text-muted">Discussions</small>
            </div>
        </div>
    </div>
    
    <div class="p-3">
        <!-- Group Description -->
        <div class="card mb-3 border-0 shadow-sm">
            <div class="card-body">
                <h6 class="fw-bold mb-2">About this group</h6>
                <p class="small text-muted"><?php echo nl2br(htmlspecialchars($group['description'])); ?></p>
                <?php if(!empty($group['meeting_schedule'])): ?>
                <hr>
                <div class="d-flex align-items-center">
                    <i class="bi bi-calendar-week text-primary me-2"></i>
                    <span class="small">Meeting Schedule: <?php echo htmlspecialchars($group['meeting_schedule']); ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Discussions -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0">Discussions</h6>
            <?php if($is_member): ?>
            <button class="btn btn-sm btn-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#createDiscussionModal">
                <i class="bi bi-plus-lg"></i> New Post
            </button>
            <?php endif; ?>
        </div>
        
        <?php if($discussions->num_rows > 0): ?>
            <?php while($post = $discussions->fetch_assoc()): ?>
            <div class="card mb-3 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex gap-3 mb-2">
                        <img src="<?php echo SITE_URL; ?>assets/uploads/<?php echo htmlspecialchars($post['profile_pic'] ?? 'default-avatar.png'); ?>" 
                             class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                        <div>
                            <h6 class="mb-0 fw-bold"><?php echo htmlspecialchars($post['full_name']); ?></h6>
                            <small class="text-muted"><?php echo timeAgo($post['created_at']); ?></small>
                        </div>
                    </div>
                    <p class="mb-0"><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
        <div class="text-center py-4 bg-light rounded-3">
            <i class="bi bi-chat-dots fs-1 text-muted"></i>
            <p class="text-muted mt-2">No discussions yet. Start the conversation!</p>
        </div>
        <?php endif; ?>
        
        <!-- Members List -->
        <h6 class="fw-bold mb-3 mt-4">Members</h6>
        <div class="row g-2">
            <?php while($member = $members->fetch_assoc()): ?>
            <div class="col-6 col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-2 text-center">
                        <img src="<?php echo SITE_URL; ?>assets/uploads/<?php echo htmlspecialchars($member['profile_pic'] ?? 'default-avatar.png'); ?>" 
                             class="rounded-circle mb-2" style="width: 50px; height: 50px; object-fit: cover;">
                        <h6 class="mb-0 small fw-bold"><?php echo htmlspecialchars($member['full_name']); ?></h6>
                        <small class="text-muted"><?php echo htmlspecialchars($member['major']); ?></small>
                        <?php if($member['role'] == 'admin'): ?>
                        <span class="badge bg-primary d-block mt-1">Admin</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<!-- Create Discussion Modal -->
<div class="modal fade" id="createDiscussionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Start Discussion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo SITE_URL; ?>modules/groups/discuss.php" method="POST">
                <input type="hidden" name="group_id" value="<?php echo $group_id; ?>">
                <div class="modal-body">
                    <textarea name="content" class="form-control" rows="4" placeholder="What would you like to discuss?" required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Post Discussion</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once '../../includes/footer.php'; ?>

<script>
function joinGroup(groupId) {
    $.ajax({
        url: '<?php echo SITE_URL; ?>modules/groups/join.php',
        method: 'POST',
        data: {group_id: groupId},
        success: function(response) {
            if(response == 'success') {
                location.reload();
            }
        }
    });
}
</script>
