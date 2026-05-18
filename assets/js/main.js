// Real-time notifications using AJAX polling
$(document).ready(function() {
    // Check for new notifications every 30 seconds
    setInterval(function() {
        $.ajax({
            url: SITE_URL + 'includes/check_notifications.php',
            method: 'GET',
            success: function(response) {
                let data = JSON.parse(response);
                if(data.count > 0) {
                    $('.bi-bell').parent().find('.badge').remove();
                    $('.bi-bell').parent().append(`
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 10px;">
                            ${data.count}
                        </span>
                    `);
                }
            }
        });
    }, 30000);
    
    // Auto-resize textarea
    $('textarea').each(function() {
        this.style.height = this.scrollHeight + 'px';
        $(this).on('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });
    });
    
    // Smooth scroll to top
    $(window).scroll(function() {
        if ($(this).scrollTop() > 200) {
            $('.scroll-top').fadeIn();
        } else {
            $('.scroll-top').fadeOut();
        }
    });
    
    $('.scroll-top').click(function() {
        $('html, body').animate({scrollTop: 0}, 300);
    });
});

// Send connection request
function sendRequest(userId, element) {
    $.ajax({
        url: SITE_URL + 'modules/connections/send.php',
        method: 'POST',
        data: {user_id: userId},
        success: function(response) {
            if(response == 'success') {
                $(element).text('Requested').prop('disabled', true);
                showToast('Connection request sent!', 'success');
            }
        }
    });
}

// Toast notification
function showToast(message, type = 'info') {
    let bgColor = type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#6366f1';
    let toast = $(`
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div class="toast show" role="alert">
                <div class="toast-header" style="background: ${bgColor}; color: white;">
                    <strong class="me-auto">${SITE_NAME}</strong>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">
                    ${message}
                </div>
            </div>
        </div>
    `);
    $('body').append(toast);
    setTimeout(() => toast.remove(), 3000);
}

// Image upload preview
function previewImage(input, previewId) {
    if(input.files && input.files[0]) {
        let reader = new FileReader();
        reader.onload = function(e) {
            $(`#${previewId}`).attr('src', e.target.result).show();
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Infinite scroll with loading indicator
let isLoading = false;
$(window).scroll(function() {
    if($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
        if(!isLoading) {
            isLoading = true;
            $('#loading-indicator').show();
            
            let offset = $('.post-card').length;
            $.ajax({
                url: SITE_URL + 'includes/load_more.php',
                method: 'GET',
                data: {offset: offset},
                success: function(data) {
                    if(data.trim()) {
                        $('#feed').append(data);
                    }
                    $('#loading-indicator').hide();
                    isLoading = false;
                }
            });
        }
    }
});

// Search with debounce
let searchTimeout;
$('#searchInput').on('keyup', function() {
    clearTimeout(searchTimeout);
    let query = $(this).val();
    
    searchTimeout = setTimeout(function() {
        if(query.length > 2) {
            $.ajax({
                url: SITE_URL + 'includes/search.php',
                method: 'POST',
                data: {query: query},
                success: function(data) {
                    $('#searchResults').html(data);
                }
            });
        } else {
            $('#searchResults').html('');
        }
    }, 500);
});

// Mark notification as read
function markAsRead(notifId) {
    $.ajax({
        url: SITE_URL + 'includes/mark_read.php',
        method: 'POST',
        data: {id: notifId},
        success: function() {
            location.reload();
        }
    });
}




// Wait for DOM to load
document.addEventListener('DOMContentLoaded', function() {
    initializeButtons();
    initializeTouchEvents();
});

// Initialize all buttons with proper event handling
function initializeButtons() {
    // Add active state feedback for all buttons
    const buttons = document.querySelectorAll('.btn, .action-chip, .action-btn, .nav-item');
    
    buttons.forEach(button => {
        button.addEventListener('touchstart', function() {
            this.classList.add('button-active');
        });
        
        button.addEventListener('touchend', function() {
            this.classList.remove('button-active');
        });
        
        button.addEventListener('click', function(e) {
            // Prevent double clicks on submit buttons
            if (this.type === 'submit') {
                if (this.classList.contains('submitting')) {
                    e.preventDefault();
                    return;
                }
                this.classList.add('submitting');
                setTimeout(() => {
                    this.classList.remove('submitting');
                }, 3000);
            }
        });
    });
}

// Add touch feedback styles
function initializeTouchEvents() {
    const style = document.createElement('style');
    style.textContent = `
        .button-active {
            transform: scale(0.97);
            opacity: 0.8;
            transition: transform 0.05s ease;
        }
        
        .submitting {
            opacity: 0.6;
            pointer-events: none;
        }
        
        @media (hover: hover) {
            .btn:hover, .action-chip:hover, .action-btn:hover {
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            }
        }
    `;
    document.head.appendChild(style);
}

// Toast notification function
function showToast(message, type = 'info') {
    const existingToast = document.querySelector('.toast-notification');
    if (existingToast) existingToast.remove();
    
    const toast = document.createElement('div');
    toast.className = 'toast-notification';
    
    const colors = {
        success: '#10b981',
        error: '#ef4444',
        warning: '#f59e0b',
        info: '#6366f1'
    };
    
    toast.style.background = colors[type] || colors.info;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => toast.remove(), 3000);
}

// Like post function
async function likePost(postId, element) {
    try {
        const response = await fetch('modules/posts/like.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `post_id=${postId}`
        });
        
        const result = await response.json();
        
        if (result) {
            const likeCount = element.querySelector('.like-count');
            if (likeCount) likeCount.textContent = result.likes;
            
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
    
    const submitBtn = form.querySelector('.comment-submit');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<div class="spinner"></div>';
    
    try {
        const response = await fetch('modules/posts/comment.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `post_id=${postId}&comment=${encodeURIComponent(comment)}`
        });
        
        if (response.ok) {
            input.value = '';
            showToast('Comment added!', 'success');
            setTimeout(() => location.reload(), 500);
        } else {
            showToast('Failed to add comment', 'error');
        }
    } catch (error) {
        showToast('An error occurred', 'error');
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
}

// Share post
function sharePost(postId) {
    const url = window.location.href;
    
    if (navigator.share) {
        navigator.share({
            title: 'Check out this post',
            text: 'Check out this post on CampusConnect',
            url: url
        }).catch(console.error);
    } else {
        navigator.clipboard.writeText(url);
        showToast('Link copied to clipboard!', 'success');
    }
}

// View story
function viewStory(mediaUrl) {
    const modalHtml = `
        <div class="modal fade" id="storyModal" tabindex="-1">
            <div class="modal-dialog modal-fullscreen">
                <div class="modal-content bg-black">
                    <div class="modal-body d-flex align-items-center justify-content-center p-0">
                        <button type="button" class="btn btn-light position-absolute top-0 end-0 m-3 rounded-circle" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg"></i>
                        </button>
                        <img src="${mediaUrl}" style="max-width: 100%; max-height: 100vh;">
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Remove existing modal if present
    const existingModal = document.getElementById('storyModal');
    if (existingModal) existingModal.remove();
    
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    const modal = new bootstrap.Modal(document.getElementById('storyModal'));
    modal.show();
    
    // Auto close after 10 seconds
    setTimeout(() => modal.hide(), 10000);
}

// Open media viewer
function openMediaViewer(url, type) {
    const modalHtml = `
        <div class="modal fade" id="mediaViewerModal" tabindex="-1">
            <div class="modal-dialog modal-fullscreen">
                <div class="modal-content bg-black">
                    <div class="modal-body d-flex align-items-center justify-content-center p-0">
                        <button type="button" class="btn btn-light position-absolute top-0 end-0 m-3 rounded-circle" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg"></i>
                        </button>
                        ${type === 'video' 
                            ? `<video src="${url}" controls style="max-width: 100%; max-height: 100vh;"></video>`
                            : `<img src="${url}" style="max-width: 100%; max-height: 100vh;">`
                        }
                    </div>
                </div>
            </div>
        </div>
    `;
    
    const existingModal = document.getElementById('mediaViewerModal');
    if (existingModal) existingModal.remove();
    
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    const modal = new bootstrap.Modal(document.getElementById('mediaViewerModal'));
    modal.show();
    
    // Auto-play video if video
    if (type === 'video') {
        const video = document.querySelector('#mediaViewerModal video');
        if (video) video.play();
    }
}

// Send connection request
async function sendRequest(userId, element) {
    try {
        const response = await fetch('modules/connections/send.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `user_id=${userId}`
        });
        
        const result = await response.text();
        
        if (result === 'success') {
            element.textContent = 'Requested';
            element.disabled = true;
            showToast('Connection request sent!', 'success');
        }
    } catch (error) {
        showToast('Failed to send request', 'error');
    }
}

// RSVP to event
async function rsvpEvent(eventId, element) {
    try {
        const response = await fetch('modules/events/rsvp.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `event_id=${eventId}`
        });
        
        const result = await response.text();
        
        if (result === 'success') {
            element.textContent = 'Going';
            element.disabled = true;
            element.classList.add('btn-success');
            element.classList.remove('btn-primary');
            showToast('You are now going to this event!', 'success');
        }
    } catch (error) {
        showToast('Failed to RSVP', 'error');
    }
}

// Preview media before upload
function previewMedia(input) {
    const preview = document.getElementById('mediaPreview');
    if (!preview) return;
    
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
                } else if (file.type.startsWith('video/')) {
                    const video = document.createElement('video');
                    video.src = e.target.result;
                    video.preload = 'metadata';
                    previewItem.appendChild(video);
                }
                
                const removeBtn = document.createElement('div');
                removeBtn.className = 'remove-media';
                removeBtn.innerHTML = '×';
                removeBtn.onclick = () => previewItem.remove();
                previewItem.appendChild(removeBtn);
            };
            
            reader.readAsDataURL(file);
            preview.appendChild(previewItem);
        });
    }
}

// Auto-refresh feed (optional)
let refreshInterval;
function startAutoRefresh(interval = 60000) {
    if (refreshInterval) clearInterval(refreshInterval);
    
    refreshInterval = setInterval(() => {
        // Only refresh if page is visible
        if (!document.hidden) {
            fetch(window.location.href)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newFeed = doc.querySelector('#feedContainer');
                    const currentFeed = document.querySelector('#feedContainer');
                    
                    if (newFeed && currentFeed && newFeed.innerHTML !== currentFeed.innerHTML) {
                        currentFeed.innerHTML = newFeed.innerHTML;
                        initializeButtons(); // Re-initialize buttons
                        showToast('New posts available!', 'info');
                    }
                })
                .catch(console.error);
        }
    }, interval);
}

// Stop auto-refresh
function stopAutoRefresh() {
    if (refreshInterval) {
        clearInterval(refreshInterval);
        refreshInterval = null;
    }
}

// Export functions for global use
window.showToast = showToast;
window.likePost = likePost;
window.submitComment = submitComment;
window.sharePost = sharePost;
window.viewStory = viewStory;
window.openMediaViewer = openMediaViewer;
window.sendRequest = sendRequest;
window.rsvpEvent = rsvpEvent;
window.previewMedia = previewMedia;