<?php
if (!isset($noAuth) && !isLoggedIn() && basename($_SERVER['PHP_SELF']) != 'login.php' && basename($_SERVER['PHP_SELF']) != 'register.php') {
    redirect('modules/auth/login.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes, viewport-fit=cover">
    <meta name="theme-color" content="#6366f1">
    <title><?php echo SITE_NAME; ?> - Student Networking Platform</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="<?php echo SITE_URL; ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/bootstrap-icons.css">
    <!-- Font Awesome 6 (free) -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/style.css">
    
    <!-- PWA Support -->
    <link rel="manifest" href="<?php echo SITE_URL; ?>manifest.json">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Students Networking">
    <link rel="apple-touch-icon" href="<?php echo SITE_URL; ?>assets/icons/icon-152x152.png">
    <meta name="theme-color" content="#6366f1">
    
    
    <style>
        /* Mobile-first responsive styles */
        :root {
            --primary-color: #6366f1;
            --secondary-color: #8b5cf6;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --dark-color: #1f2937;
            --light-color: #f3f4f6;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding-bottom: 70px;
        }
        
        /* Mobile container */
        .mobile-container {
            max-width: 500px;
            margin: 0 auto;
            background: white;
            min-height: 100vh;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            position: relative;
        }
        
        /* Bottom navigation */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            max-width: 500px;
            width: 100%;
            background: white;
            border-top: 1px solid #e5e7eb;
            padding: 8px 0;
            z-index: 1000;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
        }
        
        .nav-item {
            text-align: center;
            padding: 8px 0;
            color: #9ca3af;
            transition: all 0.3s;
            cursor: pointer;
        }
        
        .nav-item.active {
            color: var(--primary-color);
        }
        
        .nav-item i {
            font-size: 24px;
            display: block;
        }
        
        .nav-item span {
            font-size: 12px;
            margin-top: 4px;
            display: block;
        }
        
        /* Card styles */
        .post-card {
            background: white;
            border-radius: 15px;
            margin-bottom: 15px;
            padding: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: transform 0.2s;
        }
        
        .post-card:hover {
            transform: translateY(-2px);
        }
        
        /* Avatar */
        .avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .avatar-sm {
            width: 32px;
            height: 32px;
            border-radius: 50%;
        }
        
        /* Story circle */
        .story-circle {
            width: 80px;
            text-align: center;
            cursor: pointer;
        }
        
        .story-avatar {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            border: 3px solid var(--primary-color);
            padding: 3px;
            margin-bottom: 8px;
        }
        
        /* Loading animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(0,0,0,.1);
            border-radius: 50%;
            border-top-color: var(--primary-color);
            animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Dropdown menu */
        .profile-dropdown {
            position: relative;
            cursor: pointer;
        }
        
        .dropdown-menu-custom {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            min-width: 200px;
            z-index: 1000;
            margin-top: 8px;
            opacity: 0;
            visibility: hidden;
            transition: all 0.2s;
        }
        
        .profile-dropdown:hover .dropdown-menu-custom,
        .profile-dropdown.active .dropdown-menu-custom {
            opacity: 1;
            visibility: visible;
        }
        
        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            color: #374151;
            text-decoration: none;
            transition: background 0.2s;
            border-bottom: 1px solid #f3f4f6;
        }
        
        .dropdown-item:last-child {
            border-bottom: none;
        }
        
        .dropdown-item:hover {
            background: #f9fafb;
        }
        
        .dropdown-item i {
            width: 20px;
            font-size: 16px;
        }
        
        .dropdown-divider {
            height: 1px;
            background: #e5e7eb;
            margin: 4px 0;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .mobile-container {
                border-radius: 0;
            }
            
            h1, h2, h3 {
                font-size: 1.2rem;
            }
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 5px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 5px;
        }
    </style>


    <!-- Register Service Worker -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('<?php echo SITE_URL; ?>assets/js/sw.js').then(function(registration) {
                    console.log('ServiceWorker registration successful');
                }, function(err) {
                    console.log('ServiceWorker registration failed: ', err);
                });
            });
        }
    </script>

</head>
<body>
<div class="mobile-container">
    <?php if (isLoggedIn() && basename($_SERVER['PHP_SELF']) != 'login.php' && basename($_SERVER['PHP_SELF']) != 'register.php'): ?>
        <!-- Top Header with Dropdown -->
        <nav class="navbar navbar-light bg-white border-bottom px-3 py-2 sticky-top">
            <div class="container-fluid p-0">
                <a class="navbar-brand fw-bold fs-6" href="<?php echo SITE_URL; ?>modules/dashboard/">
                    <i class="bi bi-people-fill text-primary"></i> <?php echo SITE_NAME; ?>
                </a>
                <div class="d-flex gap-3">
                    <!-- Search Button -->
                    <a href="#" class="text-dark position-relative" data-bs-toggle="modal" data-bs-target="#searchModal">
                        <i class="bi bi-search fs-5"></i>
                    </a>
                    
                    <!-- Notifications -->
                    <a href="#" class="text-dark position-relative" data-bs-toggle="modal" data-bs-target="#notificationModal">
                        <i class="bi bi-bell fs-5"></i>
                        <?php
                        $notifications = getNotifications($_SESSION['user_id']);
                        $unread_count = 0;
                        while($notif = $notifications->fetch_assoc()) {
                            if(!$notif['is_read']) $unread_count++;
                        }
                        if($unread_count > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 10px;">
                                <?php echo $unread_count > 9 ? '9+' : $unread_count; ?>
                            </span>
                        <?php endif; ?>
                    </a>
                    
                    <!-- Profile Dropdown with Logout -->
                    <div class="profile-dropdown">
                        <a href="#" class="text-dark" id="profileDropdownBtn">
                            <i class="bi bi-person-circle fs-5"></i>
                        </a>
                        <div class="dropdown-menu-custom">
                            <a href="<?php echo SITE_URL; ?>modules/profile/view.php?id=<?php echo $_SESSION['user_id']; ?>" class="dropdown-item">
                                <i class="bi bi-person"></i>
                                <span>My Profile</span>
                            </a>
                            <a href="<?php echo SITE_URL; ?>modules/profile/edit.php" class="dropdown-item">
                                <i class="bi bi-gear"></i>
                                <span>Settings</span>
                            </a>
                            <a href="<?php echo SITE_URL; ?>modules/connections/" class="dropdown-item">
                                <i class="bi bi-people"></i>
                                <span>My Network</span>
                            </a>
                            <a href="<?php echo SITE_URL; ?>modules/messages/" class="dropdown-item">
                                <i class="bi bi-chat-dots"></i>
                                <span>Messages</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item" onclick="confirmLogout(event)">
                                <i class="bi bi-box-arrow-right text-danger"></i>
                                <span class="text-danger">Logout</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    <?php endif; ?>
    
    <div class="container-fluid p-0">