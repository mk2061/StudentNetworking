<?php
require_once '../config/database.php';
require_once 'functions.php';

if (!isLoggedIn()) {
    exit();
}

$offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
$user_id = $_SESSION['user_id'];
$posts = getFeedPosts($user_id, 5, $offset);

while($post = $posts->fetch_assoc()):
?>
<div class="post-card">
    <div class="d-flex justify-content-between mb-3">
        <div class="d-flex gap-3">
            <img src="<?php echo SITE_URL; ?>assets/uploads/<?php echo $post['profile_pic']; ?>" class="avatar-sm" alt="Avatar">
            <div>
                <h6 class="mb-0 fw-bold"><?php echo htmlspecialchars($post['full_name']); ?></h6>
                <small class="text-muted"><?php echo $post['major']; ?> • <?php echo timeAgo($post['created_at']); ?></small>
            </div>
        </div>
    </div>
    
    <p class="mb-3"><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
    
    <div class="d-flex justify-content-between pt-2 border-top">
        <button class="btn btn-link text-decoration-none text-dark" onclick="likePost(<?php echo $post['id']; ?>, this)">
            <i class="bi <?php echo $post['user_liked'] > 0 ? 'bi-heart-fill text-danger' : 'bi-heart'; ?>"></i>
            <span class="like-count"><?php echo $post['like_count']; ?></span>
        </button>
        <button class="btn btn-link text-decoration-none text-dark">
            <i class="bi bi-chat"></i> <?php echo $post['comment_count']; ?>
        </button>
        <button class="btn btn-link text-decoration-none text-dark">
            <i class="bi bi-share"></i> Share
        </button>
    </div>
</div>
<?php endwhile; ?>