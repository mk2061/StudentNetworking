<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

if (!isLoggedIn()) {
    redirect('modules/auth/login.php');
}

$user_id = $_SESSION['user_id'];
$user = getUserById($user_id);

// Get feed posts
$posts = getFeedPosts($user_id, 20, 0);
$suggestions = getSuggestedConnections($user_id, 5);
$pending_requests = getPendingRequests($user_id);

// Get upcoming events
$upcoming_events_sql = "SELECT e.*, u.full_name as organizer_name,
                        (SELECT COUNT(*) FROM event_attendees WHERE event_id = e.id AND status = 'going') as going_count
                        FROM events e
                        JOIN users u ON e.created_by = u.id
                        WHERE e.event_date >= NOW() 
                        ORDER BY e.event_date ASC
                        LIMIT 5";
$upcoming_events = $conn->query($upcoming_events_sql);

// Get user's stories
$stories_sql = "SELECT s.*, u.full_name, u.profile_pic 
                FROM stories s
                JOIN users u ON s.user_id = u.id
                WHERE s.expires_at > NOW()
                ORDER BY s.created_at DESC
                LIMIT 20";
$stories = $conn->query($stories_sql);
?>
<?php include_once '../../includes/header.php'; ?>

<style>
    /* ============================================
       Mobile-First Responsive Design
       ============================================ */
       
    /* CSS Variables */
    :root {
        --mobile-padding: 12px;
        --card-radius: 16px;
        --transition-speed: 0.2s;
    }

    /* Base Mobile Layout */
    .dashboard-container {
        max-width: 100%;
        margin: 0 auto;
        padding: 0;
    }

    /* Simplified Header */
    .simple-header {
        background: white;
        padding: 12px 16px;
        border-bottom: 1px solid #e5e7eb;
        position: sticky;
        top: 0;
        z-index: 100;
        backdrop-filter: blur(10px);
        background: rgba(255,255,255,0.95);
    }

    /* Quick Action Bar */
    .quick-actions {
        display: flex;
        gap: 8px;
        padding: 8px 16px;
        background: white;
        border-bottom: 1px solid #e5e7eb;
        overflow-x: auto;
        scrollbar-width: none;
    }

    .quick-actions::-webkit-scrollbar {
        display: none;
    }

    .action-chip.primary {
        background: #6366f1;
        color: white;
    }

    /* Stories Row */
    .stories-row {
        padding: 16px;
        background: white;
        border-bottom: 1px solid #e5e7eb;
    }

    .stories-scroll {
        display: flex;
        gap: 16px;
        overflow-x: auto;
        scrollbar-width: none;
    }

    .stories-scroll::-webkit-scrollbar {
        display: none;
    }

    .story-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        min-width: 70px;
        cursor: pointer;
    }

    .story-avatar-wrapper {
        position: relative;
        width: 64px;
        height: 64px;
    }

    .story-avatar {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #6366f1;
        padding: 2px;
    }

    .story-add {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
    }

    .story-name {
        font-size: 11px;
        color: #6b7280;
        text-align: center;
        max-width: 70px;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Create Post Card */
    .create-card {
        background: white;
        margin: 12px 16px;
        border-radius: var(--card-radius);
        padding: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .create-input {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .create-avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        object-fit: cover;
    }

    .create-placeholder {
        flex: 1;
        background: #f3f4f6;
        border: none;
        padding: 12px 16px;
        border-radius: 30px;
        font-size: 14px;
        color: #6b7280;
        text-align: left;
        cursor: pointer;
    }

    /* Feed Container */
    .feed-container {
        padding: 0 16px 80px 16px;
    }

    /* Post Card - Simplified */
    .feed-post {
        background: white;
        border-radius: var(--card-radius);
        margin-bottom: 12px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .post-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px;
    }

    .post-user {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .post-avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        object-fit: cover;
    }

    .post-user-info h4 {
        font-size: 14px;
        font-weight: 600;
        margin: 0 0 2px 0;
    }

    .post-user-info p {
        font-size: 11px;
        color: #9ca3af;
        margin: 0;
    }

    .post-content {
        padding: 0 12px 12px 12px;
        font-size: 14px;
        line-height: 1.5;
        color: #374151;
    }

    /* Media Grid */
    .post-media {
        margin: 0 12px 12px 12px;
        display: grid;
        gap: 4px;
        background: #f3f4f6;
        border-radius: 12px;
        overflow: hidden;
    }

    .post-media.single {
        grid-template-columns: 1fr;
    }

    .post-media.double {
        grid-template-columns: 1fr 1fr;
    }

    .post-media.triple {
        grid-template-columns: repeat(3, 1fr);
    }

    .media-item {
        aspect-ratio: 1.5;
        object-fit: cover;
        width: 100%;
        cursor: pointer;
    }

    /* Post Tags */
    .post-tags {
        padding: 0 12px 8px 12px;
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }

    .tag {
        background: #f3f4f6;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        color: #6366f1;
    }

    /* Post Stats */
    .post-stats {
        display: flex;
        justify-content: space-between;
        padding: 8px 12px;
        border-top: 1px solid #e5e7eb;
        font-size: 12px;
        color: #6b7280;
    }

    /* Post Actions */
    .post-actions {
        display: flex;
        justify-content: space-around;
        padding: 8px 12px;
        border-top: 1px solid #e5e7eb;
    }

    .action-btn {
        display: flex;
        align-items: center;
        gap: 6px;
        background: none;
        border: none;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 14px;
        color: #6b7280;
        cursor: pointer;
        transition: background 0.2s;
    }

    .action-btn:active {
        background: #f3f4f6;
    }

    .action-btn.liked {
        color: #ef4444;
    }

    .action-btn.liked i {
        animation: heartPop 0.3s ease;
    }

    @keyframes heartPop {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.2); }
    }

    /* Comments Section */
    .comments-section {
        padding: 12px;
        background: #f9fafb;
        border-top: 1px solid #e5e7eb;
    }

    .comment {
        display: flex;
        gap: 10px;
        margin-bottom: 12px;
    }

    .comment-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        object-fit: cover;
    }

    .comment-bubble {
        flex: 1;
        background: white;
        padding: 8px 12px;
        border-radius: 16px;
        font-size: 13px;
    }

    .comment-name {
        font-weight: 600;
        font-size: 12px;
        margin-bottom: 2px;
    }

    .comment-text {
        color: #374151;
        line-height: 1.4;
    }

    .comment-time {
        font-size: 10px;
        color: #9ca3af;
        margin-top: 4px;
    }

    .comment-form {
        display: flex;
        gap: 8px;
        margin-top: 12px;
    }

    .comment-input {
        flex: 1;
        border: 1px solid #e5e7eb;
        border-radius: 24px;
        padding: 10px 16px;
        font-size: 13px;
        background: white;
    }

    .comment-submit {
        background: #6366f1;
        color: white;
        border: none;
        border-radius: 24px;
        padding: 0 16px;
        font-size: 13px;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 48px 24px;
        background: white;
        border-radius: var(--card-radius);
    }

    .empty-icon {
        font-size: 64px;
        color: #d1d5db;
        margin-bottom: 16px;
    }

    .empty-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .empty-text {
        color: #6b7280;
        font-size: 14px;
        margin-bottom: 20px;
    }

    /* Bottom Navigation */
    /*.bottom-nav {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: white;
        display: flex;
        justify-content: space-around;
        padding: 8px 16px 12px;
        border-top: 1px solid #e5e7eb;
        z-index: 100;
    }*/

    /*.nav-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
        background: none;
        border: none;
        color: #9ca3af;
        font-size: 12px;
        cursor: pointer;
        transition: color 0.2s;
    }

    .nav-item i {
        font-size: 22px;
    }

    .nav-item.active {
        color: #6366f1;
    }*/

    /* Modal Styles */
    .modal-simple .modal-content {
        border-radius: 20px;
        border: none;
        margin: 16px;
    }

    .modal-header {
        padding: 16px;
        border-bottom: 1px solid #e5e7eb;
    }

    .modal-body {
        padding: 16px;
    }

    .modal-footer {
        padding: 16px;
        border-top: 1px solid #e5e7eb;
    }

    /* Tablet Styles */
    @media (min-width: 768px) {
        .dashboard-container {
            max-width: 600px;
            margin: 0 auto;
            background: #f9fafb;
            min-height: 100vh;
        }
        
        .feed-container {
            padding: 0 20px 80px 20px;
        }
        
        .create-card {
            margin: 16px 20px;
        }
        
        .stories-row {
            padding: 16px 20px;
        }
        
        .story-avatar-wrapper {
            width: 72px;
            height: 72px;
        }
        
        .post-avatar {
            width: 48px;
            height: 48px;
        }
        
        .post-user-info h4 {
            font-size: 15px;
        }
    }

    /* Desktop Styles */
    @media (min-width: 1024px) {
        .dashboard-container {
            max-width: 700px;
        }
    }

    /* Loading Spinner */
    .spinner {
        width: 20px;
        height: 20px;
        border: 2px solid #e5e7eb;
        border-top-color: #6366f1;
        border-radius: 50%;
        animation: spin 0.6s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Toast Notification */
    .toast-notification {
        position: fixed;
        bottom: 80px;
        left: 16px;
        right: 16px;
        background: #1f2937;
        color: white;
        padding: 12px 16px;
        border-radius: 12px;
        font-size: 14px;
        text-align: center;
        z-index: 1000;
        animation: slideUp 0.3s ease;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<div class="dashboard-container">
    
    <!-- Quick Action Bar -->
    <div class="quick-actions justify-content-center">
        <button class="action-chip primary" data-bs-toggle="modal" data-bs-target="#createPostModal">
            <i class="bi bi-pencil-square"></i> Post
        </button>
        <button class="action-chip" data-bs-toggle="modal" data-bs-target="#mediaUploadModal">
            <i class="bi bi-image"></i> Photo
        </button>
        <button class="action-chip" data-bs-toggle="modal" data-bs-target="#createEventModal">
            <i class="bi bi-calendar"></i> Event
        </button>
        <button class="action-chip" data-bs-toggle="modal" data-bs-target="#createGroupModal">
            <i class="bi bi-people"></i> Group
        </button>
    </div>
    
    <!-- Stories Row -->
    <div class="stories-row">
        <div class="stories-scroll">
            <!-- Add Story -->
            <div class="story-item" data-bs-toggle="modal" data-bs-target="#addStoryModal">
                <div class="story-avatar-wrapper">
                    <div class="story-avatar story-add">
                        <i class="bi bi-plus"></i>
                    </div>
                </div>
                <span class="story-name">Your Story</span>
            </div>
            
            <!-- Display Stories -->
            <?php if($stories && $stories->num_rows > 0): ?>
                <?php while($story = $stories->fetch_assoc()): ?>
                <div class="story-item" onclick="viewStory('<?php echo htmlspecialchars($story['media_url']); ?>')">
                    <div class="story-avatar-wrapper">
                        <img src="<?php echo SITE_URL; ?>assets/uploads/<?php echo htmlspecialchars($story['media_url'] ?? 'default-avatar.png'); ?>" class="story-avatar" alt="Story">
                    </div>
                    <span class="story-name"><?php echo htmlspecialchars(explode(' ', $story['full_name'])[0]); ?></span>
                </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Create Post Card -->
    <div class="create-card">
        <div class="create-input">
            <img src="<?php echo SITE_URL; ?>assets/uploads/<?php echo htmlspecialchars($user['profile_pic'] ?? 'default-avatar.png'); ?>" class="create-avatar" alt="Avatar">
            <button class="create-placeholder" data-bs-toggle="modal" data-bs-target="#createPostModal">
                What's on your mind?
            </button>
        </div>
    </div>
    
    <!-- Feed Container -->
    <div class="feed-container" id="feedContainer">
        <?php if($posts && $posts->num_rows > 0): ?>
            <?php while($post = $posts->fetch_assoc()): ?>
            <div class="feed-post" data-post-id="<?php echo $post['id']; ?>">
                <!-- Post Header -->
                <div class="post-header">
                    <div class="post-user">
                        <img src="<?php echo SITE_URL; ?>assets/uploads/<?php echo htmlspecialchars($post['profile_pic'] ?? 'default-avatar.png'); ?>" class="post-avatar" alt="Avatar">
                        <div class="post-user-info">
                            <h4><?php echo htmlspecialchars($post['full_name']); ?></h4>
                            <p><?php echo htmlspecialchars($post['major']); ?> • <?php echo timeAgo($post['created_at']); ?></p>
                        </div>
                    </div>
                    <i class="bi bi-three-dots" style="cursor: pointer;" onclick="showPostOptions(<?php echo $post['id']; ?>)"></i>
                </div>
                
                <!-- Post Content -->
                <div class="post-content">
                    <?php echo nl2br(htmlspecialchars($post['content'])); ?>
                </div>
                
                <!-- Post Media -->
                <?php if(!empty($post['media_url'])): 
                    $media_files = explode(',', $post['media_url']);
                    $media_count = count($media_files);
                    $grid_class = $media_count == 1 ? 'single' : ($media_count == 2 ? 'double' : 'triple');
                ?>
                <div class="post-media <?php echo $grid_class; ?>">
                    <?php foreach(array_slice($media_files, 0, 3) as $media): 
                        $is_video = in_array(strtolower(pathinfo($media, PATHINFO_EXTENSION)), ['mp4', 'webm', 'ogg']);
                        ?>
                        <?php if($is_video): ?>
                        <video class="media-item" src="<?php echo SITE_URL; ?>assets/uploads/<?php echo $media; ?>" preload="metadata" onclick="openMediaViewer('<?php echo SITE_URL; ?>assets/uploads/<?php echo $media; ?>', 'video')"></video>
                        <?php else: ?>
                        <img class="media-item" src="<?php echo SITE_URL; ?>assets/uploads/<?php echo $media; ?>" alt="Post media" onclick="openMediaViewer('<?php echo SITE_URL; ?>assets/uploads/<?php echo $media; ?>', 'image')">
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <?php if($media_count > 3): ?>
                        <div class="media-item" style="background: #1f2937; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                            +<?php echo ($media_count - 3); ?> more
                        </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <!-- Post Tags -->
                <?php if(!empty($post['tags'])): ?>
                <div class="post-tags">
                    <?php foreach(explode(',', $post['tags']) as $tag): ?>
                        <span class="tag">#<?php echo htmlspecialchars(trim($tag)); ?></span>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                
                <!-- Post Stats -->
                <div class="post-stats">
                    <span><i class="bi bi-heart"></i> <?php echo $post['like_count'] ?? 0; ?></span>
                    <span><i class="bi bi-chat"></i> <?php echo $post['comment_count'] ?? 0; ?></span>
                    <span><i class="bi bi-share"></i> <?php echo $post['shares_count'] ?? 0; ?></span>
                </div>
                
                <!-- Post Actions -->
                <div class="post-actions">
                    <button class="action-btn <?php echo ($post['user_liked'] ?? 0) > 0 ? 'liked' : ''; ?>" onclick="likePost(<?php echo $post['id']; ?>, this)">
                        <i class="bi <?php echo ($post['user_liked'] ?? 0) > 0 ? 'bi-heart-fill' : 'bi-heart'; ?>"></i>
                        <span>Like</span>
                    </button>
                    <button class="action-btn" data-bs-toggle="collapse" data-bs-target="#comments-<?php echo $post['id']; ?>">
                        <i class="bi bi-chat"></i>
                        <span>Comment</span>
                    </button>
                    <button class="action-btn" onclick="sharePost(<?php echo $post['id']; ?>)">
                        <i class="bi bi-share"></i>
                        <span>Share</span>
                    </button>
                </div>
                
                <!-- Comments Section -->
                <div class="collapse" id="comments-<?php echo $post['id']; ?>">
                    <div class="comments-section">
                        <div class="comments-list" id="comments-list-<?php echo $post['id']; ?>">
                            <?php
                            $comments = getComments($post['id']);
                            if($comments && $comments->num_rows > 0):
                                while($comment = $comments->fetch_assoc()):
                            ?>
                            <div class="comment">
                                <img src="<?php echo SITE_URL; ?>assets/uploads/<?php echo htmlspecialchars($comment['profile_pic'] ?? 'default-avatar.png'); ?>" class="comment-avatar" alt="Avatar">
                                <div class="comment-bubble">
                                    <div class="comment-name"><?php echo htmlspecialchars($comment['full_name']); ?></div>
                                    <div class="comment-text"><?php echo htmlspecialchars($comment['content']); ?></div>
                                    <div class="comment-time"><?php echo timeAgo($comment['created_at']); ?></div>
                                </div>
                            </div>
                            <?php 
                                endwhile;
                            else:
                            ?>
                            <div style="text-align: center; color: #9ca3af; padding: 16px;">No comments yet. Be the first to comment!</div>
                            <?php endif; ?>
                        </div>
                        
                        <form class="comment-form d-flex gap-2 mt-2" onsubmit="submitComment(event, <?php echo $post['id']; ?>)">
    <input type="text" name="comment_text" class="form-control rounded-pill comment-input" placeholder="Write a comment..." style="pointer-events: auto !important; position: relative; z-index: 10; color: #333333 !important; background-color: #000000 !important;" required>
    <button type="submit" class="btn btn-primary rounded-pill comment-submit">Post</button>
</form>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <!-- Empty State -->
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="bi bi-newspaper"></i>
                </div>
                <div class="empty-title">No posts yet</div>
                <div class="empty-text">Be the first to share something with your community!</div>
                <button class="action-chip primary" data-bs-toggle="modal" data-bs-target="#createPostModal" style="padding: 12px 24px;">
                    <i class="bi bi-plus-lg"></i> Create Your First Post
                </button>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modals (Simplified) -->
<div class="modal fade modal-simple" id="createPostModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createPostForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <textarea class="form-input form-textarea" name="content" placeholder="What's on your mind?"></textarea>
                    <input type="file" name="media_files[]" class="form-input" accept="image/*,video/*" multiple>
                    <select class="form-input" name="visibility">
                        <option value="public">🌍 Public</option>
                        <option value="connections">👥 Connections</option>
                        <option value="university">🏫 University</option>
                    </select>
                    <input type="text" class="form-input" name="tags" placeholder="Tags (comma separated)">
                </div>
                <div class="modal-footer">
                    <button type="button" class="action-chip" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="action-chip primary">Post</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Other Modals (similar simplified structure) -->
<div class="modal fade modal-simple" id="mediaUploadModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Media</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo SITE_URL; ?>modules/posts/upload_media.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="file" name="media" class="form-input" accept="image/*,video/*" required>
                    <textarea name="caption" class="form-input form-textarea" rows="3" placeholder="Say something..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="action-chip" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="action-chip primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade modal-simple" id="createEventModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo SITE_URL; ?>modules/events/create.php" method="POST">
                <div class="modal-body">
                    <input type="text" name="title" class="form-input" placeholder="Event Title" required>
                    <textarea name="description" class="form-input form-textarea" rows="3" placeholder="Description" required></textarea>
                    <input type="datetime-local" name="event_date" class="form-input" required>
                    <input type="text" name="location" class="form-input" placeholder="Location" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="action-chip" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="action-chip primary">Create Event</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade modal-simple" id="createGroupModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Study Group</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo SITE_URL; ?>modules/groups/create.php" method="POST">
                <div class="modal-body">
                    <input type="text" name="name" class="form-input" placeholder="Group Name" required>
                    <input type="text" name="subject" class="form-input" placeholder="Subject/Course" required>
                    <textarea name="description" class="form-input form-textarea" rows="3" placeholder="Description" required></textarea>
                    <input type="number" name="max_members" class="form-input" placeholder="Max Members" value="20">
                </div>
                <div class="modal-footer">
                    <button type="button" class="action-chip" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="action-chip primary">Create Group</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade modal-simple" id="addStoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Story</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo SITE_URL; ?>modules/stories/add.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="file" name="story_media" class="form-input" accept="image/*,video/*" required>
                    <textarea name="caption" class="form-input form-textarea" rows="3" placeholder="Add a caption..."></textarea>
                    <div class="alert alert-info" style="background: #f3f4f6; padding: 12px; border-radius: 12px; font-size: 12px;">
                        <i class="bi bi-info-circle"></i> Your story will disappear after 24 hours
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="action-chip" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="action-chip primary">Share Story</button>
                </div>
            </form>
        </div>
    </div>
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
// Preview media before upload
function previewMedia(input) {
    const preview = document.getElementById('mediaPreview');
    if (preview) {
        preview.innerHTML = '';
        if (input.files) {
            Array.from(input.files).forEach(file => {
                const reader = new FileReader();
                const previewItem = document.createElement('div');
                previewItem.className = 'preview-item';
                reader.onload = function(e) {
                    if (file.type.startsWith('image/')) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        previewItem.appendChild(img);
                    }
                };
                reader.readAsDataURL(file);
                preview.appendChild(previewItem);
            });
        }
    }
}

// Submit post
document.getElementById('createPostForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<div class="spinner"></div>';
    
    try {
        const response = await fetch('<?php echo SITE_URL; ?>modules/posts/create_post.php', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        if (result.success) {
            showToast('Post created!', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(result.error || 'Failed to create post', 'error');
        }
    } catch (error) {
        showToast('An error occurred', 'error');
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
});

// Like post
async function likePost(postId, element) {
    try {
        const response = await fetch('<?php echo SITE_URL; ?>modules/posts/like.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `post_id=${postId}`
        });
        const result = await response.json();
        if (result) {
            const stats = element.closest('.feed-post').querySelector('.post-stats span:first-child');
            if (stats) stats.innerHTML = `<i class="bi bi-heart"></i> ${result.likes}`;
            if (result.liked) {
                element.classList.add('liked');
                element.querySelector('i').classList.remove('bi-heart');
                element.querySelector('i').classList.add('bi-heart-fill');
            } else {
                element.classList.remove('liked');
                element.querySelector('i').classList.remove('bi-heart-fill');
                element.querySelector('i').classList.add('bi-heart');
            }
        }
    } catch (error) {
        showToast('Failed to like post', 'error');
    }
}

// Submit comment
async function submitComment(event, postId) {
    event.preventDefault();
    const form = event.target;
    const input = form.querySelector('.comment-input');
    const comment = input.value.trim();
    if (!comment) return;
    
    try {
        const response = await fetch('<?php echo SITE_URL; ?>modules/posts/comment.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `post_id=${postId}&comment=${encodeURIComponent(comment)}`
        });
        if (response.ok) {
            input.value = '';
            location.reload();
        }
    } catch (error) {
        showToast('Failed to add comment', 'error');
    }
}

// Share post
function sharePost(postId) {
    const url = `${window.location.origin}${window.location.pathname}`;
    if (navigator.share) {
        navigator.share({ title: 'Check out this post', url: url });
    } else {
        navigator.clipboard.writeText(url);
        showToast('Link copied!', 'success');
    }
}

// View story
function viewStory(mediaUrl) {
    const modal = new bootstrap.Modal(document.getElementById('mediaViewerModal'));
    const viewerImage = document.getElementById('viewerImage');
    const viewerVideo = document.getElementById('viewerVideo');
    const ext = mediaUrl.split('.').pop().toLowerCase();
    const isVideo = ['mp4', 'webm', 'ogg'].includes(ext);
    
    if (isVideo) {
        viewerVideo.src = '<?php echo SITE_URL; ?>assets/uploads/' + mediaUrl;
        viewerVideo.style.display = 'block';
        viewerImage.style.display = 'none';
        viewerVideo.play();
    } else {
        viewerImage.src = '<?php echo SITE_URL; ?>assets/uploads/' + mediaUrl;
        viewerImage.style.display = 'block';
        viewerVideo.style.display = 'none';
    }
    modal.show();
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

// Show toast notification
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = 'toast-notification';
    toast.style.background = type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#6366f1';
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
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
</script>

