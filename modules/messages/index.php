<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

if (!isLoggedIn()) {
    redirect('modules/auth/login.php');
}

$user_id = $_SESSION['user_id'];
$selected_user = isset($_GET['user']) ? intval($_GET['user']) : 0;

// Get all conversations with proper data
$conversations_sql = "SELECT 
    CASE 
        WHEN m.sender_id = ? THEN m.receiver_id 
        ELSE m.sender_id 
    END as other_user_id,
    u.full_name, 
    u.profile_pic, 
    u.major, 
    u.is_verified,
    u.last_active,
    MAX(m.created_at) as last_time,
    (SELECT message FROM messages 
     WHERE ((sender_id = ? AND receiver_id = other_user_id) OR (sender_id = other_user_id AND receiver_id = ?)) 
     ORDER BY created_at DESC LIMIT 1) as last_message,
    (SELECT COUNT(*) FROM messages 
     WHERE receiver_id = ? AND sender_id = other_user_id AND is_read = 0) as unread_count
    FROM messages m
    JOIN users u ON (CASE WHEN m.sender_id = ? THEN m.receiver_id ELSE m.sender_id END) = u.id
    WHERE m.sender_id = ? OR m.receiver_id = ?
    GROUP BY other_user_id, u.full_name, u.profile_pic, u.major, u.is_verified, u.last_active
    ORDER BY last_time DESC";

$conversations = $conn->prepare($conversations_sql);
$conversations->execute([$user_id, $user_id, $user_id, $user_id, $user_id, $user_id, $user_id]);
$conversation_list = $conversations->get_result();

// Get messages for selected conversation
$messages = [];
if ($selected_user) {
    // Mark messages as read
    $update_read = $conn->prepare("UPDATE messages SET is_read = 1 WHERE sender_id = ? AND receiver_id = ?");
    $update_read->execute([$selected_user, $user_id]);
    
    $messages_sql = "SELECT m.*, u.full_name, u.profile_pic 
                     FROM messages m 
                     JOIN users u ON m.sender_id = u.id 
                     WHERE (m.sender_id = ? AND m.receiver_id = ?) OR (m.sender_id = ? AND m.receiver_id = ?)
                     ORDER BY m.created_at ASC";
    $msg_stmt = $conn->prepare($messages_sql);
    $msg_stmt->execute([$user_id, $selected_user, $selected_user, $user_id]);
    $messages = $msg_stmt->get_result();
    
    $other_user = getUserById($selected_user);
}
?>
<?php include_once '../../includes/header.php'; ?>

<style>
/* ============================================
   Messages Page Styles
   ============================================ */
.messages-container {
    height: calc(100vh - 60px);
    background: #f8f9fa;
    display: flex;
    flex-direction: column;
}

/* Chat Header */
.chat-header {
    background: white;
    border-bottom: 1px solid #e5e7eb;
    padding: 12px 16px;
    display: flex;
    align-items: center;
    gap: 12px;
    position: sticky;
    top: 0;
    z-index: 10;
}

.back-btn {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: #f3f4f6;
    color: #374151;
    text-decoration: none;
    transition: all 0.2s;
}

.back-btn:active {
    transform: scale(0.95);
    background: #e5e7eb;
}

.chat-avatar {
    position: relative;
}

.chat-avatar img {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.online-status {
    position: absolute;
    bottom: 2px;
    right: 2px;
    width: 12px;
    height: 12px;
    background: #10b981;
    border-radius: 50%;
    border: 2px solid white;
}

.chat-info h6 {
    font-size: 16px;
    font-weight: 700;
    margin: 0 0 2px 0;
}

.chat-info small {
    font-size: 12px;
    color: #6b7280;
}

/* Messages Area */
.messages-area {
    flex: 1;
    overflow-y: auto;
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

/* Message Bubbles */
.message {
    display: flex;
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

.message.sent {
    justify-content: flex-end;
}

.message.received {
    justify-content: flex-start;
}

.message-bubble {
    max-width: 70%;
    padding: 10px 14px;
    border-radius: 20px;
    position: relative;
    word-wrap: break-word;
}

.message.sent .message-bubble {
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    color: white;
    border-bottom-right-radius: 4px;
}

.message.received .message-bubble {
    background: white;
    color: #1f2937;
    border-bottom-left-radius: 4px;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
}

.message-text {
    font-size: 14px;
    line-height: 1.5;
    margin: 0;
}

.message-time {
    font-size: 10px;
    margin-top: 4px;
    display: block;
    opacity: 0.7;
}

.message.sent .message-time {
    text-align: right;
}

/* Message Input Area */
.message-input-area {
    background: white;
    border-top: 1px solid #e5e7eb;
    padding: 12px 16px;
    position: sticky;
    bottom: 0;
}

.input-wrapper {
    display: flex;
    gap: 10px;
    align-items: center;
}

.message-input {
    flex: 1;
    padding: 12px 16px;
    border: 1px solid #e5e7eb;
    border-radius: 30px;
    font-size: 14px;
    background: #f9fafb;
    transition: all 0.2s;
}

.message-input:focus {
    outline: none;
    border-color: #6366f1;
    background: white;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.send-btn {
    width: 44px;
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    border: none;
    border-radius: 50%;
    color: white;
    cursor: pointer;
    transition: all 0.2s;
}

.send-btn:active {
    transform: scale(0.95);
}

.send-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* Conversation List */
.conversation-list {
    flex: 1;
    overflow-y: auto;
}

.conversation-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    background: white;
    border-bottom: 1px solid #f3f4f6;
    text-decoration: none;
    transition: all 0.2s;
    cursor: pointer;
}

.conversation-item:active {
    background: #f9fafb;
}

.conversation-avatar {
    position: relative;
}

.conversation-avatar img {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    object-fit: cover;
}

.unread-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #ef4444;
    color: white;
    font-size: 10px;
    font-weight: 700;
    padding: 2px 6px;
    border-radius: 20px;
    min-width: 20px;
    text-align: center;
}

.conversation-info {
    flex: 1;
    min-width: 0;
}

.conversation-header {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    margin-bottom: 4px;
}

.conversation-name {
    font-size: 15px;
    font-weight: 700;
    color: #1f2937;
    margin: 0;
}

.conversation-time {
    font-size: 10px;
    color: #9ca3af;
}

.conversation-last-message {
    font-size: 13px;
    color: #6b7280;
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.conversation-last-message.unread {
    color: #1f2937;
    font-weight: 500;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 24px;
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

/* Typing Indicator */
.typing-indicator {
    display: flex;
    gap: 4px;
    padding: 8px 12px;
    background: white;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
}

.typing-indicator span {
    width: 8px;
    height: 8px;
    background: #9ca3af;
    border-radius: 50%;
    animation: typing 1.4s infinite;
}

.typing-indicator span:nth-child(2) {
    animation-delay: 0.2s;
}

.typing-indicator span:nth-child(3) {
    animation-delay: 0.4s;
}

@keyframes typing {
    0%, 60%, 100% {
        transform: translateY(0);
        opacity: 0.4;
    }
    30% {
        transform: translateY(-10px);
        opacity: 1;
    }
}

/* Scrollbar */
.messages-area::-webkit-scrollbar,
.conversation-list::-webkit-scrollbar {
    width: 5px;
}

.messages-area::-webkit-scrollbar-track,
.conversation-list::-webkit-scrollbar-track {
    background: transparent;
}

.messages-area::-webkit-scrollbar-thumb,
.conversation-list::-webkit-scrollbar-thumb {
    background: #d1d5db;
    border-radius: 10px;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .message-bubble {
        max-width: 85%;
    }
    
    .conversation-avatar img {
        width: 48px;
        height: 48px;
    }
    
    .chat-avatar img {
        width: 40px;
        height: 40px;
    }
}
</style>

<div class="messages-container">
    <?php if($selected_user && $other_user): ?>
    <!-- Chat Header -->
    <div class="chat-header">
        <a href="index.php" class="back-btn">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <div class="chat-avatar">
            <img src="<?php echo SITE_URL; ?>assets/uploads/<?php echo htmlspecialchars($other_user['profile_pic'] ?? 'default-avatar.png'); ?>" alt="Avatar">
            <div class="online-status" style="display: <?php echo (time() - strtotime($other_user['last_active'])) < 300 ? 'block' : 'none'; ?>"></div>
        </div>
        <div class="chat-info">
            <h6><?php echo htmlspecialchars($other_user['full_name']); ?></h6>
            <small>
                <?php echo htmlspecialchars($other_user['major']); ?>
                <?php if((time() - strtotime($other_user['last_active'])) < 300): ?>
                • <span class="text-success">Online</span>
                <?php else: ?>
                • Offline
                <?php endif; ?>
            </small>
        </div>
    </div>
    
    <!-- Messages Area -->
    <div class="messages-area" id="messagesArea">
        <?php if($messages && $messages->num_rows > 0): ?>
            <?php while($msg = $messages->fetch_assoc()): ?>
            <div class="message <?php echo $msg['sender_id'] == $user_id ? 'sent' : 'received'; ?>">
                <div class="message-bubble">
                    <p class="message-text"><?php echo nl2br(htmlspecialchars($msg['message'])); ?></p>
                    <small class="message-time">
                        <?php echo date('h:i A', strtotime($msg['created_at'])); ?>
                        <?php if($msg['sender_id'] == $user_id && $msg['is_read']): ?>
                        <i class="bi bi-check-all"></i>
                        <?php elseif($msg['sender_id'] == $user_id): ?>
                        <i class="bi bi-check"></i>
                        <?php endif; ?>
                    </small>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
        <div class="empty-state" style="padding: 40px;">
            <div class="empty-icon">
                <i class="bi bi-chat-dots"></i>
            </div>
            <div class="empty-title">No messages yet</div>
            <div class="empty-text">Start a conversation with <?php echo htmlspecialchars($other_user['full_name']); ?></div>
        </div>
        <?php endif; ?>
        
        <!-- Typing Indicator -->
        <div id="typingIndicator" class="typing-indicator" style="display: none; margin-left: 16px;">
            <span></span><span></span><span></span>
            <span style="margin-left: 8px; font-size: 12px;">Typing...</span>
        </div>
    </div>
    
    <!-- Message Input -->
    <div class="message-input-area">
        <div class="input-wrapper">
            <input type="text" id="messageInput" class="message-input" placeholder="Type a message..." autocomplete="off">
            <button id="sendBtn" class="send-btn">
                <i class="bi bi-send"></i>
            </button>
        </div>
    </div>
    
    <?php else: ?>
        <!-- Conversation List -->
        <div class="chat-header" style="justify-content: space-between;">
            <h5 class="mb-0 fw-bold">Messages</h5>
            <a href="<?php echo SITE_URL; ?>modules/discover/" class="btn btn-sm btn-primary rounded-pill">
                <i class="bi bi-person-plus"></i> New Chat
            </a>
        </div>
        
        <div class="conversation-list" id="conversationList">
            <?php if($conversation_list && $conversation_list->num_rows > 0): ?>
                <?php while($conv = $conversation_list->fetch_assoc()): ?>
                <a href="?user=<?php echo $conv['other_user_id']; ?>" class="conversation-item">
                    <div class="conversation-avatar">
                        <img src="<?php echo SITE_URL; ?>assets/uploads/<?php echo htmlspecialchars($conv['profile_pic'] ?? 'default-avatar.png'); ?>" alt="Avatar">
                        <?php if($conv['unread_count'] > 0): ?>
                        <span class="unread-badge"><?php echo $conv['unread_count']; ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="conversation-info">
                        <div class="conversation-header">
                            <h6 class="conversation-name">
                                <?php echo htmlspecialchars($conv['full_name']); ?>
                                <?php if($conv['is_verified']): ?>
                                <i class="bi bi-patch-check-fill text-primary" style="font-size: 12px;"></i>
                                <?php endif; ?>
                            </h6>
                            <span class="conversation-time"><?php echo timeAgo($conv['last_time']); ?></span>
                        </div>
                        <p class="conversation-last-message <?php echo $conv['unread_count'] > 0 ? 'unread' : ''; ?>">
                            <?php echo htmlspecialchars(substr($conv['last_message'] ?? 'No messages yet', 0, 50)); ?>
                        </p>
                    </div>
                </a>
                <?php endwhile; ?>
            <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="bi bi-chat-dots"></i>
                </div>
                <div class="empty-title">No messages yet</div>
                <div class="empty-text">Start a conversation with your connections</div>
                <a href="<?php echo SITE_URL; ?>modules/discover/" class="btn btn-primary rounded-pill">
                    Find Friends
                </a>
            </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>


<?php if($selected_user): ?>
    <script>
        const user_id = <?php echo $user_id; ?>;
        const selected_user = <?php echo $selected_user; ?>;
        let lastMessageId = 0;
        let typingTimeout = null;
        let isTyping = false;
        let isLoading = false;

        // Scroll to bottom of messages
        const messagesArea = document.getElementById('messagesArea');
        if (messagesArea) {
            messagesArea.scrollTop = messagesArea.scrollHeight;
        }

        // Get the last message ID from existing messages
        function updateLastMessageId() {
            if (!messagesArea) return;
            
            const lastMessage = messagesArea.querySelector('.message:last-child');
            if (lastMessage && lastMessage.dataset.messageId) {
                lastMessageId = parseInt(lastMessage.dataset.messageId);
            }
        }

        // Initial load - get all messages
        async function loadInitialMessages() {
            try {
                const response = await fetch(`<?php echo SITE_URL; ?>modules/messages/get_messages.php?user_id=${selected_user}&all=1`);
                const html = await response.text();
                
                if (html.trim()) {
                    messagesArea.innerHTML = html;
                    messagesArea.scrollTop = messagesArea.scrollHeight;
                    updateLastMessageId();
                }
            } catch (error) {
                console.error('Error loading messages:', error);
            }
        }

        // Load ONLY NEW messages (not all)
        async function loadNewMessages() {
            if (isLoading) return;
            isLoading = true;
            
            try {
                // Only fetch messages newer than lastMessageId
                const url = `<?php echo SITE_URL; ?>modules/messages/get_messages.php?user_id=${selected_user}&last_id=${lastMessageId}`;
                const response = await fetch(url);
                const html = await response.text();
                
                if (html.trim()) {
                    // Check if we have new messages
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = html;
                    const newMessages = tempDiv.children;
                    
                    if (newMessages.length > 0) {
                        // Append only new messages
                        for (let i = 0; i < newMessages.length; i++) {
                            // Check if message already exists to prevent duplicates
                            const messageId = newMessages[i].dataset.messageId;
                            const existingMessage = messagesArea.querySelector(`.message[data-message-id="${messageId}"]`);
                            
                            if (!existingMessage) {
                                messagesArea.appendChild(newMessages[i]);
                            }
                        }
                        
                        messagesArea.scrollTop = messagesArea.scrollHeight;
                        updateLastMessageId();
                    }
                }
            } catch (error) {
                console.error('Error loading new messages:', error);
            } finally {
                isLoading = false;
            }
        }

        // Send message function
        async function sendMessage() {
            const input = document.getElementById('messageInput');
            const message = input.value.trim();
            
            if (!message) return;
            
            const sendBtn = document.getElementById('sendBtn');
            const originalText = sendBtn.innerHTML;
            sendBtn.disabled = true;
            sendBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
            
            try {
                const formData = new FormData();
                formData.append('receiver_id', selected_user);
                formData.append('message', message);
                
                const response = await fetch('<?php echo SITE_URL; ?>modules/messages/send.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.text();
                
                if (result === 'success') {
                    input.value = '';
                    // Clear typing indicator
                    isTyping = false;
                    sendTypingIndicator(false);
                    // Load new messages after sending
                    setTimeout(() => {
                        loadNewMessages();
                    }, 500);
                } else {
                    showToast('Failed to send message', 'error');
                }
            } catch (error) {
                showToast('An error occurred', 'error');
            } finally {
                sendBtn.disabled = false;
                sendBtn.innerHTML = originalText;
                input.focus();
            }
        }

        // Send typing indicator
        async function sendTypingIndicator(isTypingNow) {
            try {
                await fetch('<?php echo SITE_URL; ?>modules/messages/typing.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `receiver_id=${selected_user}&typing=${isTypingNow ? 1 : 0}`
                });
            } catch (error) {
                console.error('Error sending typing indicator:', error);
            }
        }

        // Check for typing status
        async function checkTypingStatus() {
            try {
                const response = await fetch(`<?php echo SITE_URL; ?>modules/messages/check_typing.php?user_id=${selected_user}`);
                const data = await response.json();
                
                const typingIndicator = document.getElementById('typingIndicator');
                if (typingIndicator) {
                    if (data.is_typing) {
                        typingIndicator.style.display = 'flex';
                        messagesArea.scrollTop = messagesArea.scrollHeight;
                    } else {
                        typingIndicator.style.display = 'none';
                    }
                }
            } catch (error) {
                console.error('Error checking typing status:', error);
            }
        }

        // Mark messages as read
        async function markMessagesAsRead() {
            try {
                await fetch('<?php echo SITE_URL; ?>modules/messages/mark_read.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `sender_id=${selected_user}`
                });
            } catch (error) {
                console.error('Error marking messages as read:', error);
            }
        }

        // Event listeners
        document.getElementById('sendBtn')?.addEventListener('click', sendMessage);
        document.getElementById('messageInput')?.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });

        // Typing indicator
        document.getElementById('messageInput')?.addEventListener('input', () => {
            if (!isTyping) {
                isTyping = true;
                sendTypingIndicator(true);
            }
            
            clearTimeout(typingTimeout);
            typingTimeout = setTimeout(() => {
                isTyping = false;
                sendTypingIndicator(false);
            }, 1000);
        });

        // Auto-refresh messages every 3 seconds (only new ones)
        let messageInterval = setInterval(() => {
            if (document.hasFocus()) {
                loadNewMessages();
                checkTypingStatus();
            }
        }, 3000);

        // Mark messages as read periodically
        let readInterval = setInterval(() => {
            if (document.hasFocus()) {
                markMessagesAsRead();
            }
        }, 5000);

        // Clean up intervals on page unload
        window.addEventListener('beforeunload', () => {
            if (messageInterval) clearInterval(messageInterval);
            if (readInterval) clearInterval(readInterval);
        });

        // Mark as read when page loads and when window gains focus
        markMessagesAsRead();
        window.addEventListener('focus', () => {
            markMessagesAsRead();
            loadNewMessages();
        });

        // Load initial messages
        loadInitialMessages();

        // Toast notification
        function showToast(message, type = 'info') {
            const existingToast = document.querySelector('.toast-notification');
            if (existingToast) existingToast.remove();
            
            const toast = document.createElement('div');
            toast.className = 'toast-notification';
            const colors = {
                success: '#10b981',
                error: '#ef4444',
                info: '#6366f1',
                warning: '#f59e0b'
            };
            toast.style.cssText = `
                position: fixed;
                bottom: 80px;
                left: 16px;
                right: 16px;
                background: ${colors[type] || colors.info};
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
            `;
            toast.innerHTML = `<i class="bi bi-${type === 'success' ? 'check-circle-fill' : type === 'error' ? 'x-circle-fill' : 'info-circle-fill'}"></i>${message}`;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }

        // Add animation style if not exists
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
                    width: 16px;
                    height: 16px;
                    border: 2px solid currentColor;
                    border-right-color: transparent;
                    border-radius: 50%;
                    animation: spinner 0.75s linear infinite;
                }
                @keyframes spinner {
                    to { transform: rotate(360deg); }
                }
            `;
            document.head.appendChild(style);
        }
    </script>
<?php endif; ?>

<?php include_once '../../includes/footer.php'; ?>
