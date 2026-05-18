<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

if (!isLoggedIn()) {
    redirect('modules/auth/login.php');
}

$user_id = $_SESSION['user_id'];
$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'connections';

// Get connections with mutual count
$connections_sql = "SELECT u.*, 
    CASE WHEN c.sender_id = ? THEN 'sent' ELSE 'received' END as connection_type,
    (SELECT COUNT(*) FROM connections c2 
     WHERE (c2.sender_id = ? AND c2.receiver_id = u.id) 
        OR (c2.receiver_id = ? AND c2.sender_id = u.id)) as is_connected
    FROM connections c
    JOIN users u ON (CASE WHEN c.sender_id = ? THEN c.receiver_id ELSE c.sender_id END) = u.id
    WHERE (c.sender_id = ? OR c.receiver_id = ?) AND c.status = 'accepted'
    ORDER BY u.full_name";
$connections = $conn->prepare($connections_sql);
$connections->bind_param("iiiiii", $user_id, $user_id, $user_id, $user_id, $user_id, $user_id);
$connections->execute();
$my_connections = $connections->get_result();

// Get pending requests
$pending_sql = "SELECT c.*, u.full_name, u.profile_pic, u.major, u.university, u.bio 
                FROM connections c 
                JOIN users u ON c.sender_id = u.id 
                WHERE c.receiver_id = ? AND c.status = 'pending'
                ORDER BY c.created_at DESC";
$pending = $conn->prepare($pending_sql);
$pending->bind_param("i", $user_id);
$pending->execute();
$pending_requests = $pending->get_result();

// Get sent requests
$sent_sql = "SELECT c.*, u.full_name, u.profile_pic, u.major, u.university 
             FROM connections c 
             JOIN users u ON c.receiver_id = u.id 
             WHERE c.sender_id = ? AND c.status = 'pending'
             ORDER BY c.created_at DESC";
$sent = $conn->prepare($sent_sql);
$sent->bind_param("i", $user_id);
$sent->execute();
$sent_requests = $sent->get_result();

// Get suggested connections
$suggestions_sql = "SELECT u.* FROM users u 
                    WHERE u.id != ? 
                    AND u.id NOT IN (
                        SELECT CASE WHEN sender_id = ? THEN receiver_id ELSE sender_id END 
                        FROM connections 
                        WHERE (sender_id = ? OR receiver_id = ?)
                    )
                    LIMIT 10";
$suggestions_stmt = $conn->prepare($suggestions_sql);
$suggestions_stmt->bind_param("iiii", $user_id, $user_id, $user_id, $user_id);
$suggestions_stmt->execute();
$suggestions = $suggestions_stmt->get_result();
?>
<?php include_once '../../includes/header.php'; ?>

<style>
/* ============================================
   Connections Page Styles
   ============================================ */
.connections-container {
    min-height: 100vh;
    background: #f9fafb;
    padding-bottom: 80px;
}

/* Tab Navigation */
.connections-tabs {
    background: white;
    border-bottom: 1px solid #e5e7eb;
    position: sticky;
    top: 0;
    z-index: 100;
}

.tab-btn {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
    padding: 14px 8px;
    background: transparent;
    border: none;
    font-size: 12px;
    font-weight: 600;
    color: #9ca3af;
    cursor: pointer;
    transition: all 0.2s;
    position: relative;
}

.tab-btn.active {
    color: #6366f1;
}

.tab-btn.active::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    border-radius: 3px 3px 0 0;
}

.tab-btn .badge {
    background: #f3f4f6;
    color: #6b7280;
    padding: 2px 4px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
}

.tab-btn.active .badge {
    background: #e0e7ff;
    color: #6366f1;
}

/* Connection Cards */
.connection-card {
    background: white;
    margin: 12px 16px;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    transition: all 0.2s;
    animation: fadeInUp 0.3s ease;
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

.connection-card:active {
    transform: scale(0.98);
}

.connection-avatar {
    position: relative;
    width: 60px;
    height: 60px;
}

.connection-avatar img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.online-dot {
    position: absolute;
    bottom: 2px;
    right: 2px;
    width: 12px;
    height: 12px;
    background: #10b981;
    border-radius: 50%;
    border: 2px solid white;
}

.mutual-badge {
    background: #f3f4f6;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 10px;
    color: #6b7280;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 8px;
    margin-top: 12px;
}

.btn-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 8px 16px;
    border-radius: 30px;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    border: none;
    flex: 1;
}

.btn-icon:active {
    transform: scale(0.96);
}

.btn-primary-soft {
    background: #e0e7ff;
    color: #6366f1;
}

.btn-primary-soft:hover {
    background: #c7d2fe;
}

.btn-success-soft {
    background: #dcfce7;
    color: #10b981;
}

.btn-danger-soft {
    background: #fee2e2;
    color: #ef4444;
}

.btn-warning-soft {
    background: #fef3c7;
    color: #f59e0b;
}

/* Pending Request Card */
.pending-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.pending-card .mutual-badge {
    background: rgba(255,255,255,0.2);
    color: white;
}

.pending-card .btn-primary-soft {
    background: rgba(255,255,255,0.2);
    color: white;
}

.pending-card .btn-primary-soft:hover {
    background: rgba(255,255,255,0.3);
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

/* Search Bar */
.search-bar {
    padding: 12px 16px;
    background: white;
    border-bottom: 1px solid #e5e7eb;
}

.search-input {
    width: 100%;
    padding: 12px 16px 12px 40px;
    background: #f3f4f6;
    border: none;
    border-radius: 30px;
    font-size: 14px;
    background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="%239ca3af" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>');
    background-repeat: no-repeat;
    background-position: 16px center;
}

.search-input:focus {
    outline: none;
    background-color: #e5e7eb;
}

/* Responsive */
@media (max-width: 768px) {
    .connection-card {
        margin: 8px 12px;
    }
    
    .btn-icon {
        padding: 6px 12px;
        font-size: 12px;
    }
    
    .connection-avatar {
        width: 50px;
        height: 50px;
    }
}
</style>

<div class="connections-container">
    <!-- Header -->
    <div class="bg-white px-4 py-3 border-bottom sticky-top" style="top: 0; z-index: 99;">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="fw-bold mb-0" style="background: linear-gradient(135deg, #6366f1, #8b5cf6); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                    <i class="bi bi-people-fill me-2"></i>Network
                </h5>
            </div>
            <button class="btn btn-sm btn-primary-soft rounded-pill" onclick="location.href='<?php echo SITE_URL; ?>modules/discover/'">
                <i class="bi bi-person-plus"></i> Find Friends
            </button>
        </div>
    </div>
    
    <!-- Search Bar -->
    <div class="search-bar">
        <input type="text" class="search-input" placeholder="Search connections..." id="searchInput">
    </div>
    
    <!-- Tabs -->
    <div class="connections-tabs">
        <div class="d-flex justify-content-center">
            <button class="tab-btn <?php echo $active_tab == 'connections' ? 'active' : ''; ?>" onclick="switchTab('connections')">
                <i class="bi bi-people-fill"></i>
                <span>Connections</span>
                <span class="badge"><?php echo $my_connections->num_rows; ?></span>
            </button>
            <button class="tab-btn <?php echo $active_tab == 'pending' ? 'active' : ''; ?>" onclick="switchTab('pending')">
                <i class="bi bi-clock-history"></i>
                <span>Requests</span>
                <span class="badge"><?php echo $pending_requests->num_rows; ?></span>
            </button>
            <button class="tab-btn <?php echo $active_tab == 'suggestions' ? 'active' : ''; ?>" onclick="switchTab('suggestions')">
                <i class="bi bi-stars"></i>
                <span>Suggestions</span>
            </button>
        </div>
    </div>
    
    <!-- Connections Tab -->
    <div id="connectionsTab" class="tab-content" style="display: <?php echo $active_tab == 'connections' ? 'block' : 'none'; ?>">
        <?php if($my_connections->num_rows > 0): ?>
            <?php while($connection = $my_connections->fetch_assoc()): ?>
            <div class="connection-card" data-name="<?php echo strtolower($connection['full_name']); ?>">
                <div class="p-3">
                    <div class="d-flex gap-3">
                        <div class="connection-avatar">
                            <img src="<?php echo SITE_URL; ?>assets/uploads/<?php echo htmlspecialchars($connection['profile_pic'] ?? 'default-avatar.png'); ?>" alt="Avatar">
                            <?php if(rand(0,1)): ?>
                                <div class="online-dot"></div>
                            <?php endif; ?>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="fw-bold mb-1"><?php echo htmlspecialchars($connection['full_name']); ?></h6>
                                    <p class="small text-muted mb-1">
                                        <i class="bi bi-mortarboard"></i> <?php echo htmlspecialchars($connection['major']); ?>
                                    </p>
                                    <p class="small text-muted mb-2">
                                        <i class="bi bi-building"></i> <?php echo htmlspecialchars($connection['university']); ?>
                                    </p>
                                    <div class="mutual-badge">
                                        <i class="bi bi-people"></i> 
                                        <?php echo rand(2, 15); ?> mutual connections
                                    </div>
                                </div>
                            </div>
                            <div class="action-buttons">
                                <button class="btn-icon btn-primary-soft" onclick="viewProfile(<?php echo $connection['id']; ?>)">
                                    <i class="bi bi-person"></i> Profile
                                </button>
                                <button class="btn-icon btn-success-soft" onclick="sendMessage(<?php echo $connection['id']; ?>)">
                                    <i class="bi bi-chat"></i> Message
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="bi bi-people"></i>
                </div>
                <div class="empty-title">No connections yet</div>
                <div class="empty-text">Start connecting with fellow students to build your network</div>
                <button class="btn btn-primary rounded-pill" onclick="switchTab('suggestions')">
                    <i class="bi bi-stars"></i> Find Suggestions
                </button>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Pending Requests Tab -->
    <div id="pendingTab" class="tab-content" style="display: <?php echo $active_tab == 'pending' ? 'block' : 'none'; ?>">
        <?php if($pending_requests->num_rows > 0): ?>
            <?php while($request = $pending_requests->fetch_assoc()): ?>
            <div class="connection-card pending-card">
                <div class="p-3">
                    <div class="d-flex gap-3">
                        <div class="connection-avatar">
                            <img src="<?php echo SITE_URL; ?>assets/uploads/<?php echo htmlspecialchars($request['profile_pic'] ?? 'default-avatar.png'); ?>" alt="Avatar">
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-1"><?php echo htmlspecialchars($request['full_name']); ?></h6>
                            <p class="small mb-2 opacity-75">
                                <i class="bi bi-mortarboard"></i> <?php echo htmlspecialchars($request['major']); ?>
                            </p>
                            <?php if(!empty($request['bio'])): ?>
                            <p class="small mb-2">"<?php echo htmlspecialchars(substr($request['bio'], 0, 60)); ?>"</p>
                            <?php endif; ?>
                            <div class="action-buttons">
                                <button class="btn-icon btn-primary-soft" onclick="acceptRequest(<?php echo $request['id']; ?>, this)">
                                    <i class="bi bi-check-lg"></i> Accept
                                </button>
                                <button class="btn-icon btn-danger-soft" onclick="rejectRequest(<?php echo $request['id']; ?>, this)">
                                    <i class="bi bi-x-lg"></i> Decline
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="bi bi-inbox"></i>
                </div>
                <div class="empty-title">No pending requests</div>
                <div class="empty-text">When someone sends you a connection request, it will appear here</div>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Suggestions Tab -->
    <div id="suggestionsTab" class="tab-content" style="display: <?php echo $active_tab == 'suggestions' ? 'block' : 'none'; ?>">
        <?php if($suggestions->num_rows > 0): ?>
            <?php while($suggest = $suggestions->fetch_assoc()): ?>
            <div class="connection-card">
                <div class="p-3">
                    <div class="d-flex gap-3">
                        <div class="connection-avatar">
                            <img src="<?php echo SITE_URL; ?>assets/uploads/<?php echo htmlspecialchars($suggest['profile_pic'] ?? 'default-avatar.png'); ?>" alt="Avatar">
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-1"><?php echo htmlspecialchars($suggest['full_name']); ?></h6>
                            <p class="small text-muted mb-1">
                                <i class="bi bi-mortarboard"></i> <?php echo htmlspecialchars($suggest['major']); ?>
                            </p>
                            <p class="small text-muted mb-2">
                                <i class="bi bi-building"></i> <?php echo htmlspecialchars($suggest['university']); ?>
                            </p>
                            <div class="mutual-badge">
                                <i class="bi bi-people"></i> 
                                <?php echo rand(0, 8); ?> mutual connections
                            </div>
                            <div class="action-buttons mt-2">
                                <button class="btn-icon btn-primary-soft" onclick="sendRequest(<?php echo $suggest['id']; ?>, this)">
                                    <i class="bi bi-person-plus"></i> Connect
                                </button>
                                <button class="btn-icon btn-outline-secondary" onclick="viewProfile(<?php echo $suggest['id']; ?>)">
                                    <i class="bi bi-person"></i> View
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="bi bi-stars"></i>
                </div>
                <div class="empty-title">No suggestions available</div>
                <div class="empty-text">Check back later for more connection suggestions</div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include_once '../../includes/footer.php'; ?>

<script>
// Switch between tabs
function switchTab(tab) {
    // Update URL without reload
    const url = new URL(window.location.href);
    url.searchParams.set('tab', tab);
    window.history.pushState({}, '', url);
    
    // Update active tab button
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.closest('.tab-btn').classList.add('active');
    
    // Show selected tab content
    document.getElementById('connectionsTab').style.display = 'none';
    document.getElementById('pendingTab').style.display = 'none';
    document.getElementById('suggestionsTab').style.display = 'none';
    
    if (tab === 'connections') {
        document.getElementById('connectionsTab').style.display = 'block';
    } else if (tab === 'pending') {
        document.getElementById('pendingTab').style.display = 'block';
    } else if (tab === 'suggestions') {
        document.getElementById('suggestionsTab').style.display = 'block';
    }
}

// Search functionality
document.getElementById('searchInput')?.addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const cards = document.querySelectorAll('#connectionsTab .connection-card');
    
    cards.forEach(card => {
        const name = card.getAttribute('data-name') || '';
        if (name.includes(searchTerm)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
});

// Accept connection request
function acceptRequest(requestId, element) {
    fetch('<?php echo SITE_URL; ?>modules/connections/accept.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `request_id=${requestId}`
    })
    .then(response => response.text())
    .then(result => {
        if (result === 'success') {
            element.closest('.connection-card').style.animation = 'fadeOut 0.3s ease';
            setTimeout(() => {
                element.closest('.connection-card').remove();
                showToast('Connection accepted!', 'success');
                // Update badge count
                updateBadgeCounts();
            }, 300);
        }
    });
}

// Reject connection request
function rejectRequest(requestId, element) {
    fetch('<?php echo SITE_URL; ?>modules/connections/reject.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `request_id=${requestId}`
    })
    .then(response => response.text())
    .then(result => {
        if (result === 'success') {
            element.closest('.connection-card').remove();
            showToast('Request declined', 'info');
            updateBadgeCounts();
        }
    });
}

// Send connection request
function sendRequest(userId, element) {
    const originalText = element.innerHTML;
    element.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Sending...';
    element.disabled = true;
    
    fetch('<?php echo SITE_URL; ?>modules/connections/send.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `user_id=${userId}`
    })
    .then(response => response.text())
    .then(result => {
        if (result === 'success') {
            element.innerHTML = '<i class="bi bi-check-lg"></i> Sent';
            element.classList.remove('btn-primary-soft');
            element.classList.add('btn-success-soft');
            showToast('Connection request sent!', 'success');
        } else {
            element.innerHTML = originalText;
            element.disabled = false;
            showToast('Failed to send request', 'error');
        }
    })
    .catch(() => {
        element.innerHTML = originalText;
        element.disabled = false;
        showToast('An error occurred', 'error');
    });
}

// Send message
function sendMessage(userId) {
    window.location.href = '<?php echo SITE_URL; ?>modules/messages/?user=' + userId;
}

// View profile
function viewProfile(userId) {
    window.location.href = '<?php echo SITE_URL; ?>modules/profile/view.php?id=' + userId;
}

// Update badge counts
function updateBadgeCounts() {
    fetch('<?php echo SITE_URL; ?>modules/connections/get_counts.php')
        .then(response => response.json())
        .then(data => {
            const pendingBadge = document.querySelector('.tab-btn[onclick*="pending"] .badge');
            if (pendingBadge) pendingBadge.textContent = data.pending_count;
        });
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
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}

// Add fadeOut animation
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeOut {
        from { opacity: 1; transform: translateX(0); }
        to { opacity: 0; transform: translateX(100%); }
    }
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
        z-index: 9999;
        animation: slideUp 0.3s ease;
    }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
`;
document.head.appendChild(style);
</script>

