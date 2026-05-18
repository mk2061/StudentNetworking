<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

if (!isLoggedIn()) {
    redirect('modules/auth/login.php');
}

$profile_id = isset($_GET['id']) ? intval($_GET['id']) : $_SESSION['user_id'];
$profile = getUserById($profile_id);

// Check if profile exists
if (!$profile) {
    redirect('modules/dashboard/');
}

$current_user_id = $_SESSION['user_id'];
$is_own_profile = ($profile_id == $current_user_id);

// Check connection status
$connection_status = 'none';
if (!$is_own_profile) {
    $check = $conn->prepare("SELECT status FROM connections WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?)");
    $check->execute([$current_user_id, $profile_id, $profile_id, $current_user_id]);
    $result = $check->get_result();
    if ($conn_result = $result->fetch_assoc()) {
        $connection_status = $conn_result['status'];
    }
}

// Get user's posts with media
$posts = $conn->prepare("SELECT p.*, 
    (SELECT COUNT(*) FROM likes WHERE post_id = p.id) as like_count,
    (SELECT COUNT(*) FROM comments WHERE post_id = p.id) as comment_count,
    (SELECT COUNT(*) FROM likes WHERE post_id = p.id AND user_id = ?) as user_liked
    FROM posts p WHERE p.user_id = ? ORDER BY p.created_at DESC LIMIT 20");
$posts->execute([$current_user_id, $profile_id]);
$user_posts = $posts->get_result();

// Get connections count
$connections_count = getConnectionsCount($profile_id);

// Get mutual connections
$mutual_sql = "SELECT u.* FROM users u WHERE u.id IN (
    SELECT c1.user_id FROM (
        SELECT CASE WHEN sender_id = ? THEN receiver_id ELSE sender_id END as user_id 
        FROM connections WHERE status = 'accepted' AND (sender_id = ? OR receiver_id = ?)
    ) as c1
    WHERE c1.user_id IN (
        SELECT CASE WHEN sender_id = ? THEN receiver_id ELSE sender_id END 
        FROM connections WHERE status = 'accepted' AND (sender_id = ? OR receiver_id = ?)
    )
) LIMIT 6";
$mutual = $conn->prepare($mutual_sql);
$mutual->execute([$profile_id, $profile_id, $profile_id, $current_user_id, $current_user_id, $current_user_id]);
$mutual_connections = $mutual->get_result();

// Get user's interests
$interests = !empty($profile['interests']) ? explode(',', $profile['interests']) : [];

?>
<?php include_once '../../includes/header.php'; ?>

<style>
    /* ============================================
       Profile Page Styles
       ============================================ */
    .profile-container {
        min-height: 100vh;
        background: #f9fafb;
        padding-bottom: 80px;
    }

    /* Cover Photo */
    .cover-container {
        position: relative;
        height: 200px;
        overflow: hidden;
    }

    .cover-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .cover-gradient {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    /* Profile Header */
    .profile-header {
        position: relative;
        margin-top: -60px;
        padding: 0 20px;
    }

    .avatar-container {
        position: relative;
        display: inline-block;
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        background: white;
    }

    .verified-badge-lg {
        position: absolute;
        bottom: 5px;
        right: 5px;
        background: #6366f1;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid white;
    }

    .verified-badge-lg i {
        font-size: 16px;
        color: white;
    }

    /* Edit Button */
    .edit-btn {
        position: absolute;
        top: 20px;
        right: 20px;
        background: rgba(255,255,255,0.9);
        backdrop-filter: blur(10px);
        border: none;
        padding: 8px 16px;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        display: flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }

    .edit-btn:active {
        transform: scale(0.95);
    }

    /* Profile Info */
    .profile-info {
        text-align: center;
        padding: 16px 20px;
    }

    .profile-name {
        font-size: 24px;
        font-weight: 700;
        margin: 0 0 4px 0;
        color: #1f2937;
    }

    .profile-major {
        font-size: 14px;
        color: #6b7280;
        margin-bottom: 8px;
    }

    .profile-major i {
        margin-right: 4px;
    }

    .profile-location {
        font-size: 13px;
        color: #9ca3af;
        margin-bottom: 12px;
    }

    .profile-bio {
        font-size: 14px;
        color: #4b5563;
        line-height: 1.6;
        max-width: 400px;
        margin: 12px auto;
    }

    /* Stats Row */
    .stats-row {
        display: flex;
        justify-content: center;
        gap: 32px;
        padding: 16px 20px;
        background: white;
        border-radius: 20px;
        margin: 0 16px 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .stat-item {
        text-align: center;
    }

    .stat-value {
        font-size: 20px;
        font-weight: 700;
        color: #1f2937;
    }

    .stat-label {
        font-size: 12px;
        color: #9ca3af;
        margin-top: 4px;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        justify-content: center;
        gap: 12px;
        padding: 0 20px 20px;
    }

    .btn-action {
        padding: 10px 24px;
        border-radius: 30px;
        font-size: 14px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-action:active {
        transform: scale(0.96);
    }

    .btn-primary {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        color: white;
    }

    .btn-secondary {
        background: #f3f4f6;
        color: #4b5563;
    }

    .btn-success {
        background: #10b981;
        color: white;
    }

    .btn-outline {
        background: transparent;
        border: 1px solid #e5e7eb;
        color: #6b7280;
    }

    /* Interests Section */
    .interests-section {
        padding: 20px;
        background: white;
        margin: 16px;
        border-radius: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .section-title {
        font-size: 16px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .interests-cloud {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .interest-tag {
        background: #f3f4f6;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        color: #6366f1;
        transition: all 0.2s;
    }

    .interest-tag:active {
        transform: scale(0.95);
    }

    /* Posts Section */
    .posts-section {
        padding: 0 16px;
    }

    .post-card {
        background: white;
        border-radius: 20px;
        margin-bottom: 12px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        transition: all 0.2s;
    }

    .post-card:active {
        transform: scale(0.98);
    }

    .post-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px;
    }

    .post-date {
        font-size: 11px;
        color: #9ca3af;
    }

    .post-content {
        padding: 0 12px 12px;
        font-size: 14px;
        line-height: 1.5;
        color: #374151;
    }

    .post-media {
        margin: 0 12px 12px;
        border-radius: 12px;
        overflow: hidden;
    }

    .post-media.single {
        max-height: 300px;
    }

    .post-media img,
    .post-media video {
        width: 100%;
        max-height: 300px;
        object-fit: cover;
    }

    .post-stats {
        display: flex;
        gap: 16px;
        padding: 8px 12px;
        border-top: 1px solid #f3f4f6;
        font-size: 12px;
        color: #6b7280;
    }

    /* Mutual Connections */
    .mutual-section {
        padding: 20px;
        background: white;
        margin: 16px;
        border-radius: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .mutual-scroll {
        display: flex;
        gap: 16px;
        overflow-x: auto;
        scrollbar-width: none;
    }

    .mutual-scroll::-webkit-scrollbar {
        display: none;
    }

    .mutual-item {
        text-align: center;
        min-width: 80px;
        cursor: pointer;
    }

    .mutual-avatar {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 8px;
        border: 2px solid white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .mutual-name {
        font-size: 12px;
        font-weight: 500;
        color: #1f2937;
    }

    .mutual-major {
        font-size: 10px;
        color: #9ca3af;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        background: white;
        border-radius: 20px;
        margin: 16px;
    }

    .empty-icon {
        width: 60px;
        height: 60px;
        background: #f3f4f6;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
    }

    .empty-icon i {
        font-size: 30px;
        color: #9ca3af;
    }

    .empty-title {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 8px;
        color: #1f2937;
    }

    .empty-text {
        font-size: 13px;
        color: #6b7280;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .profile-avatar {
            width: 100px;
            height: 100px;
        }
        
        .profile-name {
            font-size: 20px;
        }
        
        .stats-row {
            gap: 20px;
        }
        
        .stat-value {
            font-size: 18px;
        }
        
        .btn-action {
            padding: 8px 20px;
            font-size: 13px;
        }
        
        .mutual-avatar {
            width: 60px;
            height: 60px;
        }
    }
</style>

<div class="profile-container">
    <!-- Cover Photo -->
    <div class="cover-container">
        <?php if(!empty($profile['cover_photo'])): ?>
        <img src="<?php echo SITE_URL; ?>assets/uploads/<?php echo htmlspecialchars($profile['cover_photo']); ?>" class="cover-image" alt="Cover">
        <?php else: ?>
        <div class="cover-gradient"></div>
        <?php endif; ?>
        
        <!-- Edit Button (only for own profile) -->
        <?php if($is_own_profile): ?>
        <a href="edit.php" class="edit-btn">
            <i class="bi bi-pencil"></i> Edit Profile
        </a>
        <?php endif; ?>
    </div>
    
    <!-- Profile Header -->
    <div class="profile-header text-center">
        <div class="avatar-container">
            <img src="<?php echo SITE_URL; ?>assets/uploads/<?php echo htmlspecialchars($profile['profile_pic'] ?? 'default-avatar.png'); ?>" 
                 class="profile-avatar" alt="Profile Picture">
            <?php if($profile['is_verified'] ?? false): ?>
            <div class="verified-badge-lg">
                <i class="bi bi-check-lg"></i>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Profile Info -->
    <div class="profile-info">
        <h1 class="profile-name"><?php echo htmlspecialchars($profile['full_name'] ?? ''); ?></h1>
        <div class="profile-major">
            <i class="bi bi-mortarboard"></i> <?php echo htmlspecialchars($profile['major'] ?? ''); ?>
            <?php if($profile['year_of_study']): ?>
            • Year <?php echo $profile['year_of_study']; ?>
            <?php endif; ?>
        </div>
        <div class="profile-location">
            <i class="bi bi-building"></i> <?php echo htmlspecialchars($profile['university'] ?? ''); ?>
            <?php if(!empty($profile['location'])): ?>
            • <i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($profile['location']); ?>
            <?php endif; ?>
        </div>
        
        <?php if(!empty($profile['bio'])): ?>
        <div class="profile-bio">
            <?php echo nl2br(htmlspecialchars($profile['bio'])); ?>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Stats Row -->
    <div class="stats-row">
        <div class="stat-item">
            <div class="stat-value"><?php echo $connections_count; ?></div>
            <div class="stat-label">Connections</div>
        </div>
        <div class="stat-item">
            <div class="stat-value"><?php echo $user_posts->num_rows; ?></div>
            <div class="stat-label">Posts</div>
        </div>
        <div class="stat-item">
            <div class="stat-value"><?php echo $mutual_connections->num_rows; ?></div>
            <div class="stat-label">Mutual</div>
        </div>
    </div>
    
    <!-- Action Buttons -->
    <?php if(!$is_own_profile): ?>
        <div class="action-buttons">
            <?php if($connection_status == 'none'): ?>
                <button class="btn-action btn-primary" onclick="sendConnectionRequest(<?php echo $profile_id; ?>, this)">
                    <i class="bi bi-person-plus"></i> Connect
                </button>
            <?php elseif($connection_status == 'pending'): ?>
                <button class="btn-action btn-secondary" disabled>
                    <i class="bi bi-clock"></i> Request Pending
                </button>
            <?php elseif($connection_status == 'accepted'): ?>
                <button class="btn-action btn-success" onclick="sendMessage(<?php echo $profile_id; ?>)">
                    <i class="bi bi-chat-dots"></i> Message
                </button>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <!-- Interests Section -->
    <?php if(!empty($interests)): ?>
        <div class="interests-section">
            <div class="section-title">
                <i class="bi bi-tags"></i> Interests & Hobbies
            </div>
            <div class="interests-cloud">
                <?php foreach($interests as $interest): ?>
                    <span class="interest-tag">#<?php echo htmlspecialchars_decode(trim($interest)); ?></span>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Posts Section -->
    <div class="posts-section">
        <div class="section-title" style="padding: 0 4px 12px;">
            <i class="bi bi-file-post"></i> Posts
        </div>
        
        <?php if($user_posts->num_rows > 0): ?>
            <?php while($post = $user_posts->fetch_assoc()): ?>
            <div class="post-card" data-post-id="<?php echo $post['id']; ?>">
                <div class="post-header">
                    <span class="post-date">
                        <i class="bi bi-clock"></i> <?php echo timeAgo($post['created_at']); ?>
                    </span>
                    <?php if($is_own_profile): ?>
                    <i class="bi bi-three-dots" style="cursor: pointer;" onclick="showPostOptions(<?php echo $post['id']; ?>)"></i>
                    <?php endif; ?>
                </div>
                
                <div class="post-content">
                    <?php echo nl2br(htmlspecialchars($post['content'] ?? '')); ?>
                </div>
                
                <?php if(!empty($post['media_url'])): 
                    $media_files = explode(',', $post['media_url']);
                    $first_media = $media_files[0];
                    $ext = strtolower(pathinfo($first_media, PATHINFO_EXTENSION));
                    $is_video = in_array($ext, ['mp4', 'webm', 'ogg']);
                ?>
                <div class="post-media single">
                    <?php if($is_video): ?>
                    <video src="<?php echo SITE_URL; ?>assets/uploads/<?php echo $first_media; ?>" controls></video>
                    <?php else: ?>
                    <img src="<?php echo SITE_URL; ?>assets/uploads/<?php echo $first_media; ?>" alt="Post media" onclick="openMediaViewer('<?php echo SITE_URL; ?>assets/uploads/<?php echo $first_media; ?>', 'image')">
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <div class="post-stats">
                    <span><i class="bi bi-heart<?php echo ($post['user_liked'] ?? 0) > 0 ? '-fill text-danger' : ''; ?>"></i> <?php echo $post['like_count'] ?? 0; ?></span>
                    <span><i class="bi bi-chat"></i> <?php echo $post['comment_count'] ?? 0; ?></span>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
        <div class="empty-state">
            <div class="empty-icon">
                <i class="bi bi-file-post"></i>
            </div>
            <div class="empty-title">No posts yet</div>
            <div class="empty-text">
                <?php if($is_own_profile): ?>
                Share your first post to connect with others
                <?php else: ?>
                <?php echo htmlspecialchars($profile['full_name']); ?> hasn't posted anything yet
                <?php endif; ?>
            </div>
            <?php if($is_own_profile): ?>
            <button class="btn-action btn-primary" style="margin-top: 16px;" data-bs-toggle="modal" data-bs-target="#createPostModal">
                <i class="bi bi-plus-lg"></i> Create Post
            </button>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Mutual Connections -->
    <?php if($mutual_connections->num_rows > 0): ?>
    <div class="mutual-section">
        <div class="section-title">
            <i class="bi bi-people"></i> Mutual Connections
        </div>
        <div class="mutual-scroll">
            <?php while($mutual = $mutual_connections->fetch_assoc()): ?>
            <div class="mutual-item" onclick="viewProfile(<?php echo $mutual['id']; ?>)">
                <img src="<?php echo SITE_URL; ?>assets/uploads/<?php echo htmlspecialchars($mutual['profile_pic'] ?? 'default-avatar.png'); ?>" 
                     class="mutual-avatar" alt="Avatar">
                <div class="mutual-name"><?php echo htmlspecialchars(explode(' ', $mutual['full_name'])[0]); ?></div>
                <div class="mutual-major"><?php echo htmlspecialchars(substr($mutual['major'] ?? '', 0, 15)); ?></div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Media Viewer Modal -->
<div class="modal fade" id="mediaViewerModal" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content bg-black">
            <div class="modal-body d-flex align-items-center justify-content-center p-0">
                <button type="button" class="btn btn-light position-absolute top-0 end-0 m-3 rounded-circle" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i>
                </button>
                <div class="text-center">
                    <img id="viewerImage" src="" style="max-width: 100%; max-height: 100vh; display: none;">
                    <video id="viewerVideo" controls style="max-width: 100%; max-height: 100vh; display: none;"></video>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once '../../includes/footer.php'; ?>

<script>
    // Send connection request
    async function sendConnectionRequest(userId, button) {
        const originalText = button.innerHTML;
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
                button.classList.remove('btn-primary');
                button.classList.add('btn-secondary');
                button.disabled = true;
                showToast('Connection request sent!', 'success');
            } else {
                button.innerHTML = originalText;
                button.disabled = false;
                showToast('Failed to send request', 'error');
            }
        } catch (error) {
            button.innerHTML = originalText;
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

    // Open media viewer
    function openMediaViewer(url, type) {
        const modal = new bootstrap.Modal(document.getElementById('mediaViewerModal'));
        const viewerImage = document.getElementById('viewerImage');
        const viewerVideo = document.getElementById('viewerVideo');
        
        if (type === 'video') {
            viewerVideo.src = url;
            viewerVideo.style.display = 'block';
            viewerImage.style.display = 'none';
            viewerVideo.play();
        } else {
            viewerImage.src = url;
            viewerImage.style.display = 'block';
            viewerVideo.style.display = 'none';
        }
        modal.show();
    }

    // Show post options
    function showPostOptions(postId) {
        if (confirm('Delete this post?')) {
            fetch('<?php echo SITE_URL; ?>modules/posts/delete_post.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `post_id=${postId}`
            }).then(() => location.reload());
        }
    }

    // Toast notification
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = 'toast-notification';
        const colors = {
            success: '#10b981',
            error: '#ef4444',
            info: '#6366f1'
        };
        toast.style.cssText = `
            position: fixed;
            bottom: 80px;
            left: 16px;
            right: 16px;
            background: ${colors[type]};
            color: white;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 14px;
            text-align: center;
            z-index: 9999;
            animation: slideUp 0.3s ease;
        `;
        toast.innerHTML = `<i class="bi bi-${type === 'success' ? 'check-circle' : type === 'error' ? 'x-circle' : 'info-circle'} me-2"></i>${message}`;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }

    // Add styles if not exists
    if (!document.querySelector('#toast-styles')) {
        const style = document.createElement('style');
        style.id = 'toast-styles';
        style.textContent = `
            @keyframes slideUp {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .spinner-border {
                display: inline-block;
                width: 14px;
                height: 14px;
                border: 2px solid currentColor;
                border-right-color: transparent;
                border-radius: 50%;
                animation: spinner 0.6s linear infinite;
            }
            @keyframes spinner {
                to { transform: rotate(360deg); }
            }
        `;
        document.head.appendChild(style);
    }
</script>
