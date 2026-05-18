<?php if (isLoggedIn() && basename($_SERVER['PHP_SELF']) != 'login.php' && basename($_SERVER['PHP_SELF']) != 'register.php'): ?>

    <?php $current_path = $_SERVER['PHP_SELF']; ?>

    <!-- Bottom Navigation -->
    <div class="bottom-nav">
        <div class="row g-4 justify-content-center text-center">

            <div class="col">
                <a href="<?php echo SITE_URL; ?>modules/dashboard/" 
                   class="nav-item text-decoration-none <?php echo strpos($current_path,'modules/dashboard') !== false ? 'active' : ''; ?>">
                    <i class="bi bi-house-door-fill"></i>
                    <span>Home</span>
                </a>
            </div>

            <div class="col">
                <a href="<?php echo SITE_URL; ?>modules/connections/" 
                   class="nav-item text-decoration-none <?php echo strpos($current_path,'modules/connections') !== false ? 'active' : ''; ?>">
                    <i class="bi bi-people-fill"></i>
                    <span>Network</span>
                </a>
            </div>

            <div class="col">
                <a href="#" data-bs-toggle="modal" data-bs-target="#createPostModal" 
                   class="nav-item text-decoration-none">
                    <i class="bi bi-plus-circle-fill text-primary" style="font-size: 28px;"></i>
                    <span>Post</span>
                </a>
            </div>

            <div class="col">
                <a href="<?php echo SITE_URL; ?>modules/messages/" 
                   class="nav-item text-decoration-none <?php echo strpos($current_path,'modules/messages') !== false ? 'active' : ''; ?>">
                    <i class="bi bi-chat-fill"></i>
                    <span>Chat</span>
                </a>
            </div>

            <div class="col">
                <div class="profile-dropdown-bottom">
                    <a href="#" class="nav-item text-decoration-none <?php echo strpos($current_path,'modules/profile') !== false ? 'active' : ''; ?>" id="bottomProfileBtn">
                        <i class="bi bi-person-fill"></i>
                        <span>Profile</span>
                    </a>
                    <div class="dropdown-menu-bottom">
                        <a href="<?php echo SITE_URL; ?>modules/profile/view.php?id=<?php echo $_SESSION['user_id']; ?>" class="dropdown-item">
                            <i class="bi bi-person"></i> My Profile
                        </a>
                        <a href="<?php echo SITE_URL; ?>modules/profile/edit.php" class="dropdown-item">
                            <i class="bi bi-gear"></i> Settings
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" onclick="confirmLogout(event)" class="dropdown-item text-danger">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </div>


    <!-- Create Post Modal -->
    <div class="modal fade" id="createPostModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New Post</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo SITE_URL; ?>modules/posts/create.php" method="POST">
                    <div class="modal-body">
                        <textarea class="form-control mb-3" name="content" rows="4" placeholder="What's on your mind? Share your thoughts, questions, or opportunities..."></textarea>
                        <select class="form-select mb-2" name="visibility">
                            <option value="public">🌍 Public - Everyone can see</option>
                            <option value="connections">👥 Connections Only</option>
                            <option value="university">🏫 University Only</option>
                        </select>
                        <input type="text" class="form-control" name="tags" placeholder="Tags (comma separated)">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Post <i class="bi bi-send"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Search Modal -->
    <div class="modal fade" id="searchModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Search</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="text" id="searchInput" class="form-control mb-3" placeholder="Search students, study groups, events...">
                    <div id="searchResults"></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Notification Modal -->
    <div class="modal fade" id="notificationModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Notifications</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
                    <?php
                    $notifications = getNotifications($_SESSION['user_id']);
                    if($notifications->num_rows > 0):
                        while($notif = $notifications->fetch_assoc()):
                    ?>
                    <div class="d-flex gap-3 mb-3 p-2 <?php echo !$notif['is_read'] ? 'bg-light' : ''; ?> rounded">
                        <i class="bi bi-bell-fill text-primary fs-4"></i>
                        <div class="flex-grow-1">
                            <p class="mb-1"><?php echo htmlspecialchars($notif['content']); ?></p>
                            <small class="text-muted"><?php echo timeAgo($notif['created_at']); ?></small>
                        </div>
                    </div>
                    <?php 
                        endwhile;
                    else:
                    ?>
                    <p class="text-center text-muted py-5">No notifications yet</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>


    <style>
        /* Bottom profile dropdown */
        .profile-dropdown-bottom {
            position: relative;
        }

        .dropdown-menu-bottom {
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: white;
            border-radius: 12px;
            box-shadow: 0 -4px 20px rgba(0,0,0,0.15);
            min-width: 180px;
            margin-bottom: 8px;
            opacity: 0;
            visibility: hidden;
            transition: all 0.2s;
            z-index: 1000;
        }

        .profile-dropdown-bottom.active .dropdown-menu-bottom {
            opacity: 1;
            visibility: visible;
        }

        .dropdown-menu-bottom .dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            color: #374151;
            text-decoration: none;
            font-size: 13px;
            border-bottom: 1px solid #f3f4f6;
        }

        .dropdown-menu-bottom .dropdown-item:last-child {
            border-bottom: none;
        }

        .dropdown-menu-bottom .dropdown-item:hover {
            background: #f9fafb;
        }

        .dropdown-menu-bottom .dropdown-divider {
            height: 1px;
            background: #e5e7eb;
            margin: 4px 0;
        }
    </style>

<?php endif; ?>

    </div>
</div>

<!-- Scripts -->
<script src="<?php echo SITE_URL; ?>assets/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo SITE_URL; ?>assets/js/jquery-3.6.0.min.js"></script>
<script src="<?php echo SITE_URL; ?>assets/js/main.js"></script>
<script src="<?php echo SITE_URL; ?>assets/js/sw.js"></script>

<script>
    // Auto-hide alerts after 3 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 3000);

    // Search functionality
    $('#searchInput').on('keyup', function() {
        let query = $(this).val();
        if(query.length > 2) {
            $.ajax({
                url: '<?php echo SITE_URL; ?>includes/search.php',
                method: 'POST',
                data: {query: query},
                success: function(data) {
                    $('#searchResults').html(data);
                }
            });
        }
    });

    // Like post AJAX
    function likePost(postId, element) {
        $.ajax({
            url: '<?php echo SITE_URL; ?>modules/posts/like.php',
            method: 'POST',
            data: {post_id: postId},
            success: function(res) { 
                $(element).find('.like-count').text(res.likes);
                if(res.liked) {
                    $(element).find('i').removeClass('bi-heart').addClass('bi-heart-fill text-danger');
                } else {
                    $(element).find('i').removeClass('bi-heart-fill text-danger').addClass('bi-heart');
                }
            }
        });
    }

    // Infinite scroll
    let loading = false;
    $(window).scroll(function() {
        if($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
            if(!loading) {
                loading = true;
                let offset = $('.post-card').length;
                $.ajax({
                    url: '<?php echo SITE_URL; ?>includes/load_more.php',
                    method: 'GET',
                    data: {offset: offset},
                    success: function(data) {
                        if(data) {
                            $('#feed').append(data);
                            loading = false;
                        }
                    }
                });
            }
        }
    });

    // Bottom profile dropdown
    const bottomProfileBtn = document.getElementById('bottomProfileBtn');
    const bottomDropdown = document.querySelector('.profile-dropdown-bottom');

    if (bottomProfileBtn && bottomDropdown) {
        bottomProfileBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            bottomDropdown.classList.toggle('active');
        });
        
        document.addEventListener('click', function(e) {
            if (!bottomDropdown.contains(e.target)) {
                bottomDropdown.classList.remove('active');
            }
        });
    }


        // Profile dropdown toggle
    document.addEventListener('DOMContentLoaded', function() {
        const dropdownBtn = document.getElementById('profileDropdownBtn');
        const dropdown = document.querySelector('.profile-dropdown');
        
        if (dropdownBtn && dropdown) {
            dropdownBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropdown.classList.toggle('active');
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!dropdown.contains(e.target)) {
                    dropdown.classList.remove('active');
                }
            });
        }
    });

    // Confirm logout function
    function confirmLogout(event) {
        event.preventDefault();
        
        // Create custom confirmation dialog
        const modalHtml = `
            <div class="modal fade" id="logoutModal" tabindex="-1" data-bs-backdrop="static">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header border-0">
                            <h5 class="modal-title">
                                <i class="bi bi-box-arrow-right text-danger"></i> Confirm Logout
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-center py-4">
                            <i class="bi bi-question-circle fs-1 text-warning mb-3 d-block"></i>
                            <p class="mb-0">Are you sure you want to logout?</p>
                            <small class="text-muted">You will need to login again to access your account</small>
                        </div>
                        <div class="modal-footer border-0 justify-content-center gap-3">
                            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">
                                <i class="bi bi-x-lg me-1"></i> Cancel
                            </button>
                            <a href="<?php echo SITE_URL; ?>modules/auth/logout.php" class="btn btn-danger rounded-pill px-4">
                                <i class="bi bi-box-arrow-right me-1"></i> Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Remove existing modal if any
        const existingModal = document.getElementById('logoutModal');
        if (existingModal) existingModal.remove();
        
        // Add modal to body
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        
        // Show modal
        const logoutModal = new bootstrap.Modal(document.getElementById('logoutModal'));
        logoutModal.show();
        
        // Clean up modal when hidden
        document.getElementById('logoutModal').addEventListener('hidden.bs.modal', function() {
            this.remove();
        });
    }

    // Alternative simple confirm (if you don't want the modal)
    function simpleConfirmLogout(event) {
        event.preventDefault();
        if (confirm('Are you sure you want to logout?')) {
            window.location.href = '<?php echo SITE_URL; ?>modules/auth/logout.php';
        }
    }
</script>
</body>
</html>