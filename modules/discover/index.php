<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

if (!isLoggedIn()) {
    redirect('modules/auth/login.php');
}

$user_id = $_SESSION['user_id'];
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';

// Build query based on filters
$sql = "SELECT u.*, 
        (SELECT COUNT(*) FROM connections 
         WHERE (sender_id = ? AND receiver_id = u.id) 
            OR (receiver_id = ? AND sender_id = u.id)) as is_connected,
        (SELECT COUNT(*) FROM connections 
         WHERE sender_id = ? AND receiver_id = u.id AND status = 'pending') as request_sent,
        (SELECT COUNT(*) FROM connections 
         WHERE sender_id = u.id AND receiver_id = ? AND status = 'pending') as request_received,
        (SELECT COUNT(*) FROM connections c2 
         WHERE (c2.sender_id = ? AND c2.receiver_id = u.id AND c2.status = 'accepted')
            OR (c2.receiver_id = ? AND c2.sender_id = u.id AND c2.status = 'accepted')) as mutual_count
        FROM users u 
        WHERE u.id != ?";

// Add search condition
if (!empty($search)) {
    $sql .= " AND (u.full_name LIKE ? OR u.major LIKE ? OR u.university LIKE ?)";
}

// Add filter conditions
if ($filter == 'same_major') {
    $user_major = getUserById($user_id)['major'];
    $sql .= " AND u.major = ?";
} elseif ($filter == 'same_university') {
    $user_university = getUserById($user_id)['university'];
    $sql .= " AND u.university = ?";
} elseif ($filter == 'new') {
    $sql .= " AND u.created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
} elseif ($filter == 'suggested') {
    $sql .= " AND (SELECT COUNT(*) FROM connections 
             WHERE (sender_id = ? AND receiver_id = u.id) 
                OR (receiver_id = ? AND sender_id = u.id)) = 0";
}

$sql .= " ORDER BY u.full_name LIMIT 30";

// Prepare statement
$stmt = $conn->prepare($sql);

// Bind parameters based on filters
if (!empty($search)) {
    $search_param = "%$search%";
    if ($filter == 'same_major') {
        $user_major = getUserById($user_id)['major'];
        $stmt->bind_param("iiiiiiiss", $user_id, $user_id, $user_id, $user_id, $user_id, $user_id, $user_id, $search_param, $search_param, $user_major);
    } elseif ($filter == 'same_university') {
        $user_university = getUserById($user_id)['university'];
        $stmt->bind_param("iiiiiiiss", $user_id, $user_id, $user_id, $user_id, $user_id, $user_id, $user_id, $search_param, $search_param, $user_university);
    } elseif ($filter == 'suggested') {
        $stmt->bind_param("iiiiiiiiiss", $user_id, $user_id, $user_id, $user_id, $user_id, $user_id, $user_id, $user_id, $user_id, $search_param, $search_param);
    } else {
        $stmt->bind_param("iiiiiiiss", $user_id, $user_id, $user_id, $user_id, $user_id, $user_id, $user_id, $search_param, $search_param);
    }
} else {
    if ($filter == 'same_major') {
        $user_major = getUserById($user_id)['major'];
        $stmt->bind_param("iiiiiii", $user_id, $user_id, $user_id, $user_id, $user_id, $user_id, $user_id, $user_major);
    } elseif ($filter == 'same_university') {
        $user_university = getUserById($user_id)['university'];
        $stmt->bind_param("iiiiiii", $user_id, $user_id, $user_id, $user_id, $user_id, $user_id, $user_id, $user_university);
    } elseif ($filter == 'suggested') {
        $stmt->bind_param("iiiiiiiii", $user_id, $user_id, $user_id, $user_id, $user_id, $user_id, $user_id, $user_id, $user_id);
    } else {
        $stmt->bind_param("iiiiiii", $user_id, $user_id, $user_id, $user_id, $user_id, $user_id, $user_id);
    }
}

$stmt->execute();
$students = $stmt->get_result();

// Get user's info for profile
$current_user = getUserById($user_id);
?>
<?php include_once '../../includes/header.php'; ?>

<style>
/* ============================================
   Discover Page Styles
   ============================================ */
.discover-container {
    min-height: 100vh;
    background: #f9fafb;
    padding-bottom: 80px;
}

/* Header */
.discover-header {
    background: white;
    padding: 16px;
    border-bottom: 1px solid #e5e7eb;
    position: sticky;
    top: 0;
    z-index: 100;
}

/* Search Bar */
.search-wrapper {
    position: relative;
    margin-bottom: 16px;
}

.search-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    font-size: 18px;
}

.search-input {
    width: 100%;
    padding: 12px 16px 12px 48px;
    background: #f3f4f6;
    border: none;
    border-radius: 30px;
    font-size: 14px;
    transition: all 0.2s;
}

.search-input:focus {
    outline: none;
    background: #e5e7eb;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

/* Filter Chips */
.filter-chips {
    display: flex;
    gap: 8px;
    overflow-x: auto;
    scrollbar-width: none;
    padding: 4px 0;
}

.filter-chips::-webkit-scrollbar {
    display: none;
}

.filter-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    background: #f3f4f6;
    border: none;
    border-radius: 30px;
    font-size: 13px;
    font-weight: 500;
    color: #6b7280;
    white-space: nowrap;
    cursor: pointer;
    transition: all 0.2s;
}

.filter-chip:active {
    transform: scale(0.95);
}

.filter-chip.active {
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    color: white;
}

.filter-chip i {
    font-size: 14px;
}

/* Student Cards */
.student-card {
    background: white;
    margin: 12px 16px;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    transition: all 0.2s;
    animation: fadeInUp 0.3s ease;
}

.student-card:active {
    transform: scale(0.98);
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card-content {
    padding: 16px;
}

.profile-section {
    display: flex;
    gap: 12px;
    margin-bottom: 12px;
}

.avatar-wrapper {
    position: relative;
}

.student-avatar {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.online-badge {
    position: absolute;
    bottom: 2px;
    right: 2px;
    width: 14px;
    height: 14px;
    background: #10b981;
    border-radius: 50%;
    border: 2px solid white;
}

.verified-badge {
    position: absolute;
    bottom: 0;
    right: -5px;
    background: #6366f1;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid white;
}

.verified-badge i {
    font-size: 10px;
    color: white;
}

.student-info {
    flex: 1;
}

.student-name {
    font-size: 16px;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 4px;
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}

.student-major {
    font-size: 12px;
    color: #6b7280;
    margin-bottom: 2px;
}

.student-university {
    font-size: 11px;
    color: #9ca3af;
    margin-bottom: 8px;
}

.student-bio {
    font-size: 12px;
    color: #6b7280;
    line-height: 1.4;
    margin-bottom: 8px;
}

/* Stats Row */
.stats-row {
    display: flex;
    gap: 16px;
    margin-bottom: 12px;
    padding: 8px 0;
    border-top: 1px solid #f3f4f6;
    border-bottom: 1px solid #f3f4f6;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 11px;
    color: #9ca3af;
}

.stat-item i {
    font-size: 12px;
}

.stat-value {
    font-weight: 600;
    color: #4b5563;
}

/* Interests Tags */
.interests-section {
    margin-bottom: 12px;
}

.interests-tag {
    display: inline-block;
    padding: 4px 10px;
    background: #f3f4f6;
    border-radius: 20px;
    font-size: 10px;
    color: #6366f1;
    margin-right: 6px;
    margin-bottom: 6px;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 10px;
}

.btn-connect {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 10px 16px;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    border: none;
    border-radius: 30px;
    color: white;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-connect:active {
    transform: scale(0.96);
}

.btn-connect.pending {
    background: #f3f4f6;
    color: #6b7280;
    cursor: default;
}

.btn-connect.connected {
    background: #10b981;
}

.btn-message {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 10px 20px;
    background: #f3f4f6;
    border: none;
    border-radius: 30px;
    color: #4b5563;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-message:active {
    transform: scale(0.96);
    background: #e5e7eb;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 24px;
    background: white;
    margin: 16px;
    border-radius: 24px;
}

.empty-icon {
    width: 80px;
    height: 80px;
    background: #f3f4f6;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
}

.empty-icon i {
    font-size: 40px;
    color: #9ca3af;
}

.empty-title {
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 8px;
    color: #1f2937;
}

.empty-text {
    color: #6b7280;
    font-size: 14px;
    margin-bottom: 24px;
}

/* Loading Skeleton */
.skeleton-card {
    background: white;
    margin: 12px 16px;
    border-radius: 20px;
    padding: 16px;
    animation: pulse 1.5s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.skeleton-avatar {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    background: #e5e7eb;
}

.skeleton-text {
    height: 12px;
    background: #e5e7eb;
    border-radius: 6px;
    margin-bottom: 8px;
}

/* Responsive */
@media (max-width: 768px) {
    .student-avatar {
        width: 60px;
        height: 60px;
    }
    
    .btn-connect, .btn-message {
        padding: 8px 12px;
        font-size: 12px;
    }
    
    .stats-row {
        gap: 12px;
    }
}
</style>
<style>
/* Additional styles for loaded cards */
.student-card {
    background: white;
    margin: 12px 16px;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    transition: all 0.2s;
    animation: fadeInUp 0.3s ease;
}

.student-card:active {
    transform: scale(0.98);
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card-content {
    padding: 16px;
}

.profile-section {
    display: flex;
    gap: 12px;
    margin-bottom: 12px;
}

.avatar-wrapper {
    position: relative;
}

.student-avatar {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.online-badge {
    position: absolute;
    bottom: 2px;
    right: 2px;
    width: 14px;
    height: 14px;
    background: #10b981;
    border-radius: 50%;
    border: 2px solid white;
}

.verified-badge {
    position: absolute;
    bottom: 0;
    right: -5px;
    background: #6366f1;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid white;
}

.verified-badge i {
    font-size: 10px;
    color: white;
}

.student-info {
    flex: 1;
}

.student-name {
    font-size: 16px;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 4px;
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}

.student-major {
    font-size: 12px;
    color: #6b7280;
    margin-bottom: 2px;
}

.student-university {
    font-size: 11px;
    color: #9ca3af;
    margin-bottom: 8px;
}

.student-bio {
    font-size: 12px;
    color: #6b7280;
    line-height: 1.4;
    margin-bottom: 8px;
}

.stats-row {
    display: flex;
    gap: 16px;
    margin-bottom: 12px;
    padding: 8px 0;
    border-top: 1px solid #f3f4f6;
    border-bottom: 1px solid #f3f4f6;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 11px;
    color: #9ca3af;
}

.stat-item i {
    font-size: 12px;
}

.stat-value {
    font-weight: 600;
    color: #4b5563;
}

.interests-section {
    margin-bottom: 12px;
}

.interests-tag {
    display: inline-block;
    padding: 4px 10px;
    background: #f3f4f6;
    border-radius: 20px;
    font-size: 10px;
    color: #6366f1;
    margin-right: 6px;
    margin-bottom: 6px;
}

.action-buttons {
    display: flex;
    gap: 10px;
}

.btn-connect {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 10px 16px;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    border: none;
    border-radius: 30px;
    color: white;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-connect:active {
    transform: scale(0.96);
}

.btn-connect.pending {
    background: #f3f4f6;
    color: #6b7280;
    cursor: default;
}

.btn-connect.connected {
    background: #10b981;
}

.btn-message {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 10px 20px;
    background: #f3f4f6;
    border: none;
    border-radius: 30px;
    color: #4b5563;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-message:active {
    transform: scale(0.96);
    background: #e5e7eb;
}

/* Loading indicator for infinite scroll */
.loading-indicator {
    text-align: center;
    padding: 20px;
    color: #9ca3af;
}

.loading-indicator .spinner {
    width: 30px;
    height: 30px;
    border: 3px solid #f3f4f6;
    border-top-color: #6366f1;
    border-radius: 50%;
    animation: spin 0.6s linear infinite;
    margin: 0 auto 10px;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* No more results */
.no-more {
    text-align: center;
    padding: 20px;
    color: #9ca3af;
    font-size: 13px;
}

/* Responsive */
@media (max-width: 768px) {
    .student-avatar {
        width: 60px;
        height: 60px;
    }
    
    .btn-connect, .btn-message {
        padding: 8px 12px;
        font-size: 12px;
    }
    
    .stats-row {
        gap: 12px;
    }
    
    .student-name {
        font-size: 14px;
    }
}
</style>
<div class="discover-container">
    <!-- Header -->
    <div class="discover-header">
        <div class="search-wrapper">
            <i class="bi bi-search search-icon"></i>
            <input type="text" class="search-input" id="searchInput" placeholder="Search students by name, major, or university..." value="<?php echo htmlspecialchars($search); ?>">
        </div>
        
        <div class="filter-chips">
            <button class="filter-chip <?php echo $filter == 'all' ? 'active' : ''; ?>" onclick="applyFilter('all')">
                <i class="bi bi-grid-3x3-gap-fill"></i> All
            </button>
            <button class="filter-chip <?php echo $filter == 'suggested' ? 'active' : ''; ?>" onclick="applyFilter('suggested')">
                <i class="bi bi-stars"></i> Suggested
            </button>
            <button class="filter-chip <?php echo $filter == 'same_major' ? 'active' : ''; ?>" onclick="applyFilter('same_major')">
                <i class="bi bi-mortarboard"></i> Same Major
            </button>
            <button class="filter-chip <?php echo $filter == 'same_university' ? 'active' : ''; ?>" onclick="applyFilter('same_university')">
                <i class="bi bi-building"></i> Same Uni
            </button>
            <button class="filter-chip <?php echo $filter == 'new' ? 'active' : ''; ?>" onclick="applyFilter('new')">
                <i class="bi bi-star-fill"></i> New
            </button>
        </div>
    </div>
    
    <!-- Results Count -->
    <div class="px-4 py-2">
        <small class="text-muted">
            <i class="bi bi-people-fill"></i> 
            <?php echo $students->num_rows; ?> student<?php echo $students->num_rows != 1 ? 's' : ''; ?> found
        </small>
    </div>
    
    <!-- Students List -->
    <div id="studentsList">
        <?php if($students->num_rows > 0): ?>
            <?php while($student = $students->fetch_assoc()): ?>
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
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="bi bi-search"></i>
                </div>
                <div class="empty-title">No students found</div>
                <div class="empty-text">
                    <?php if(!empty($search)): ?>
                        No results for "<?php echo htmlspecialchars($search); ?>"
                    <?php else: ?>
                        Try adjusting your filters or check back later
                    <?php endif; ?>
                </div>
                <?php if(!empty($search)): ?>
                <button class="btn btn-primary rounded-pill" onclick="clearSearch()">
                    <i class="bi bi-x-lg"></i> Clear Search
                </button>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Loading indicator -->
        <div id="loadingIndicator" class="loading-indicator" style="display: none;">
            <div class="spinner"></div>
            <div>Loading more students...</div>
        </div>

        <div id="noMoreResults" class="no-more" style="display: none;">
            <i class="bi bi-check-circle"></i> No more students to load
        </div>
    </div>
</div>

<?php include_once '../../includes/footer.php'; ?>

<script>
let isLoading = false;
let currentPage = 1;
let hasMore = true;

// Apply filter
function applyFilter(filter) {
    const url = new URL(window.location.href);
    url.searchParams.set('filter', filter);
    window.location.href = url;
}

// Search with debounce
let searchTimeout;
document.getElementById('searchInput')?.addEventListener('input', function(e) {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        const search = e.target.value;
        const url = new URL(window.location.href);
        if (search) {
            url.searchParams.set('search', search);
        } else {
            url.searchParams.delete('search');
        }
        window.location.href = url;
    }, 500);
});

// Clear search
function clearSearch() {
    const url = new URL(window.location.href);
    url.searchParams.delete('search');
    window.location.href = url;
}

// Send connection request
async function sendConnectionRequest(userId, button) {
    const originalHTML = button.innerHTML;
    button.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Sending...';
    button.disabled = true;
    
    try {
        const response = await fetch('<?php echo SITE_URL; ?>modules/connections/send.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `user_id=${userId}`
        });
        
        const result = await response.text();
        
        if (result === 'success') {
            button.innerHTML = '<i class="bi bi-clock"></i> Request Sent';
            button.classList.add('pending');
            showToast('Connection request sent!', 'success');
        } else {
            button.innerHTML = originalHTML;
            button.disabled = false;
            showToast('Failed to send request', 'error');
        }
    } catch (error) {
        button.innerHTML = originalHTML;
        button.disabled = false;
        showToast('An error occurred', 'error');
    }
}

// Accept request from discover page
async function acceptRequestFromDiscover(userId, button) {
    button.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Accepting...';
    button.disabled = true;
    
    try {
        // First get the request ID
        const getRequestResponse = await fetch(`<?php echo SITE_URL; ?>modules/connections/get_request_id.php?user_id=${userId}`);
        const requestData = await getRequestResponse.json();
        
        if (requestData.request_id) {
            const response = await fetch('<?php echo SITE_URL; ?>modules/connections/accept.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `request_id=${requestData.request_id}`
            });
            
            const result = await response.text();
            
            if (result === 'success') {
                button.innerHTML = '<i class="bi bi-check-lg"></i> Connected';
                button.classList.add('connected');
                button.disabled = true;
                showToast('Connection accepted!', 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                button.innerHTML = '<i class="bi bi-check-lg"></i> Accept Request';
                button.disabled = false;
                showToast('Failed to accept request', 'error');
            }
        }
    } catch (error) {
        button.innerHTML = '<i class="bi bi-check-lg"></i> Accept Request';
        button.disabled = false;
        showToast('An error occurred', 'error');
    }
}

// Send message
function sendMessage(userId) {
    window.location.href = '<?php echo SITE_URL; ?>modules/messages/?user=' + userId;
}

// View profile
function viewProfile(userId) {
    window.location.href = '<?php echo SITE_URL; ?>modules/profile/view.php?id=' + userId;
}

// Toast notification
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = 'toast-notification';
    const colors = {
        success: '#10b981',
        error: '#ef4444',
        info: '#6366f1',
        warning: '#f59e0b'
    };
    toast.style.background = colors[type] || colors.info;
    toast.innerHTML = `<i class="bi bi-${type === 'success' ? 'check-circle' : type === 'error' ? 'x-circle' : 'info-circle'} me-2"></i>${message}`;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}

// Throttled scroll event
let scrollTimeout;
window.addEventListener('scroll', function() {
    if (scrollTimeout) clearTimeout(scrollTimeout);
    
    scrollTimeout = setTimeout(() => {
        if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 500) {
            loadMore();
        }
    }, 100);
});

// Load more students
async function loadMore() {
    isLoading = true;
    currentPage++;
    
    const filter = '<?php echo $filter; ?>';
    const search = '<?php echo addslashes($search); ?>';
    
    try {
        const response = await fetch(`<?php echo SITE_URL; ?>modules/discover/load_more.php?page=${currentPage}&filter=${filter}&search=${search}`);
        const html = await response.text();
        
        if (html.trim()) {
            document.getElementById('studentsList').insertAdjacentHTML('beforeend', html);
        } else {
            hasMore = false;
        }
    } catch (error) {
        console.error('Error loading more:', error);
    } finally {
        isLoading = false;
    }
}

// Add CSS for toast if not exists
if (!document.querySelector('#toast-styles')) {
    const style = document.createElement('style');
    style.id = 'toast-styles';
    style.textContent = `
        .toast-notification {
            position: fixed;
            bottom: 80px;
            left: 16px;
            right: 16px;
            color: white;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 14px;
            text-align: center;
            z-index: 9999;
            animation: slideUp 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    `;
    document.head.appendChild(style);
}


// Intersection Observer for better performance (optional)
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting && !isLoading && hasMore) {
            loadMore();
        }
    });
}, { threshold: 0.1 });

// Observe the loading indicator
const loadingElement = document.getElementById('loadingIndicator');
if (loadingElement) observer.observe(loadingElement);
</script>

