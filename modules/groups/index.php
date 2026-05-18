<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

if (!isLoggedIn()) {
    redirect('modules/auth/login.php');
}

$user_id = $_SESSION['user_id'];
$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'my_groups';

// Get user's groups
$my_groups_sql = "SELECT g.*, gm.role, 
                  (SELECT COUNT(*) FROM group_members WHERE group_id = g.id) as member_count
                  FROM study_groups g
                  JOIN group_members gm ON g.id = gm.group_id
                  WHERE gm.user_id = ?
                  ORDER BY g.created_at DESC";
$my_groups = $conn->prepare($my_groups_sql);
$my_groups->bind_param("i", $user_id);
$my_groups->execute();
$user_groups = $my_groups->get_result();

// Get available groups to join
$available_sql = "SELECT g.*, u.full_name as creator_name,
                  (SELECT COUNT(*) FROM group_members WHERE group_id = g.id) as member_count
                  FROM study_groups g
                  JOIN users u ON g.created_by = u.id
                  WHERE g.id NOT IN (SELECT group_id FROM group_members WHERE user_id = ?)
                  ORDER BY g.created_at DESC LIMIT 10";
$available = $conn->prepare($available_sql);
$available->bind_param("i", $user_id);
$available->execute();
$available_groups = $available->get_result();
?>
<?php include_once '../../includes/header.php'; ?>

<div class="groups-container">
    <!-- Header -->
    <div class="bg-white border-bottom p-3 sticky-top">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0">Study Groups</h5>
            <button class="btn btn-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#createGroupModal">
                <i class="bi bi-plus-lg"></i> Create Group
            </button>
        </div>
    </div>
    
    <!-- Tabs -->
    <ul class="nav nav-tabs px-3 pt-2">
        <li class="nav-item">
            <a class="nav-link <?php echo $active_tab == 'my_groups' ? 'active' : ''; ?>" href="?tab=my_groups">
                <i class="bi bi-people-fill"></i> My Groups (<?php echo $user_groups->num_rows; ?>)
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo $active_tab == 'available' ? 'active' : ''; ?>" href="?tab=available">
                <i class="bi bi-search"></i> Available Groups
            </a>
        </li>
    </ul>
    
    <div class="p-3">
        <?php if($active_tab == 'my_groups'): ?>
            <?php if($user_groups->num_rows > 0): ?>
                <?php while($group = $user_groups->fetch_assoc()): ?>
                <div class="card mb-3 border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="fw-bold mb-0"><?php echo htmlspecialchars($group['name']); ?></h6>
                            <?php if($group['role'] == 'admin'): ?>
                            <span class="badge bg-primary">Admin</span>
                            <?php endif; ?>
                        </div>
                        <p class="small text-muted mb-2"><?php echo htmlspecialchars(substr($group['description'], 0, 100)); ?>...</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="small text-muted">
                                <i class="bi bi-people"></i> <?php echo $group['member_count']; ?> members
                                <span class="mx-2">•</span>
                                <i class="bi bi-book"></i> <?php echo htmlspecialchars($group['subject']); ?>
                            </div>
                            <a href="view.php?id=<?php echo $group['id']; ?>" class="btn btn-sm btn-outline-primary rounded-pill">
                                View Group
                            </a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
            <div class="text-center py-5">
                <i class="bi bi-people fs-1 text-muted"></i>
                <p class="text-muted mt-2">You haven't joined any study groups yet</p>
                <button class="btn btn-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#createGroupModal">
                    Create Your First Group
                </button>
            </div>
            <?php endif; ?>
            
        <?php else: ?>
            <?php if($available_groups->num_rows > 0): ?>
                <?php while($group = $available_groups->fetch_assoc()): ?>
                <div class="card mb-3 border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <h6 class="fw-bold mb-1"><?php echo htmlspecialchars($group['name']); ?></h6>
                        <p class="small text-muted mb-2">
                            Created by <?php echo htmlspecialchars($group['creator_name']); ?>
                        </p>
                        <p class="small mb-2"><?php echo htmlspecialchars(substr($group['description'], 0, 100)); ?>...</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="small text-muted">
                                <i class="bi bi-people"></i> <?php echo $group['member_count']; ?>/<?php echo $group['max_members']; ?> members
                                <span class="mx-2">•</span>
                                <i class="bi bi-book"></i> <?php echo htmlspecialchars($group['subject']); ?>
                            </div>
                            <button class="btn btn-sm btn-success rounded-pill" onclick="joinGroup(<?php echo $group['id']; ?>, this)">
                                <i class="bi bi-person-plus"></i> Join Group
                            </button>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
            <div class="text-center py-5">
                <i class="bi bi-inbox fs-1 text-muted"></i>
                <p class="text-muted mt-2">No available groups to join</p>
                <button class="btn btn-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#createGroupModal">
                    Create a Group
                </button>
            </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Create Group Modal -->
<div class="modal fade" id="createGroupModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Study Group</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo SITE_URL; ?>modules/groups/create.php" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Group Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Subject/Course</label>
                        <input type="text" name="subject" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Max Members</label>
                        <input type="number" name="max_members" class="form-control" value="20" min="2" max="100">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Meeting Schedule (Optional)</label>
                        <input type="text" name="meeting_schedule" class="form-control" placeholder="e.g., Every Tuesday 5PM">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Group</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once '../../includes/footer.php'; ?>

<script>
function joinGroup(groupId, element) {
    $.ajax({
        url: '<?php echo SITE_URL; ?>modules/groups/join.php',
        method: 'POST',
        data: {group_id: groupId},
        success: function(response) {
            if(response == 'success') {
                $(element).closest('.card').fadeOut();
                showToast('Joined group successfully!', 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast('Failed to join group', 'error');
            }
        }
    });
}
</script>
