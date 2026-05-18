<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

// If user is already logged in, redirect to dashboard
if (isLoggedIn()) {
    redirect('modules/dashboard/');
}

$noAuth = true;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes, viewport-fit=cover">
    <meta name="description" content="<?php echo SITE_NAME; ?> - The ultimate student networking platform to connect, collaborate, and grow with fellow students">
    <meta name="keywords" content="student network, campus connect, study groups, student community">
    <meta name="author" content="<?php echo SITE_NAME; ?>">
    <meta property="og:title" content="<?php echo SITE_NAME; ?> - Student Networking Platform">
    <meta property="og:description" content="Connect with fellow students, join study groups, and grow your network">
    <meta property="og:type" content="website">
    <meta name="theme-color" content="#6366f1">
    <title><?php echo SITE_NAME; ?> - Connect, Collaborate, Grow</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="<?php echo SITE_URL; ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/bootstrap-icons.css">
    <script>
        const SITE_URL = '<?php echo SITE_URL; ?>';
    </script>
    <scrip src=".../assets/js/main.js"></script>
    <style>
        /* ============================================
           Landing Page Styles
           ============================================ */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            overflow-x: hidden;
        }

        /* Mobile Menu Button */
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 28px;
            cursor: pointer;
            padding: 8px;
        }

        /* Mobile Navigation */
        .mobile-nav {
            position: fixed;
            top: 0;
            left: -100%;
            width: 80%;
            max-width: 300px;
            height: 100%;
            background: white;
            z-index: 2000;
            transition: left 0.3s ease;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            overflow-y: auto;
        }

        .mobile-nav.active {
            left: 0;
        }

        .mobile-nav-header {
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .mobile-nav-links {
            padding: 20px;
        }

        .mobile-nav-links a {
            display: block;
            padding: 12px 0;
            color: #374151;
            text-decoration: none;
            font-weight: 500;
            border-bottom: 1px solid #e5e7eb;
        }

        .mobile-nav-links a:last-child {
            border-bottom: none;
        }

        .mobile-nav-links .btn-primary,
        .mobile-nav-links .btn-outline {
            display: inline-block;
            margin-top: 10px;
            text-align: center;
        }

        /* Overlay */
        .nav-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1999;
            display: none;
        }

        .nav-overlay.active {
            display: block;
        }

        /* Navigation */
        .landing-nav {
            background: rgba(0,0,0,0.3);
            backdrop-filter: blur(10px);
            padding: 15px 0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 18px;
            font-weight: 800;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .logo i {
            font-size: 28px;
        }

        .nav-links {
            display: flex;
            gap: 30px;
            align-items: center;
        }

        .nav-link {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: opacity 0.3s;
        }

        .nav-link:hover {
            opacity: 0.8;
        }

        /* Hero Carousel */
        .hero-carousel {
            height: 100vh;
        }

        .carousel-item {
            height: 100vh;
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .carousel-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(102,126,234,0.9) 0%, rgba(118,75,162,0.9) 100%);
        }

        .carousel-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: white;
            width: 100%;
            padding: 0 20px;
            z-index: 10;
        }

        .carousel-title {
            font-size: 56px;
            font-weight: 800;
            margin-bottom: 20px;
            animation: fadeInUp 0.8s ease;
        }

        .carousel-description {
            font-size: 18px;
            margin-bottom: 30px;
            opacity: 0.9;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            animation: fadeInUp 1s ease;
        }

        .carousel-buttons {
            animation: fadeInUp 1.2s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Features Section */
        .features-section {
            background: white;
            padding: 80px 20px;
        }

        .section-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-title {
            text-align: center;
            font-size: 36px;
            font-weight: 800;
            color: #1f2937;
            margin-bottom: 15px;
        }

        .section-subtitle {
            text-align: center;
            font-size: 18px;
            color: #6b7280;
            margin-bottom: 60px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .feature-card {
            text-align: center;
            padding: 40px 30px;
            border-radius: 20px;
            background: white;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            transition: all 0.3s;
            border: 1px solid #e5e7eb;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .feature-icon i {
            font-size: 40px;
            color: white;
        }

        .feature-title {
            font-size: 20px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 10px;
        }

        .feature-description {
            font-size: 14px;
            color: #6b7280;
            line-height: 1.6;
        }

        /* Stats Section */
        .stats-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 60px 20px;
            color: white;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
            text-align: center;
        }

        .stat-number {
            font-size: 48px;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .stat-label {
            font-size: 16px;
            opacity: 0.9;
        }

        /* CTA Section */
        .cta-section {
            background: white;
            padding: 80px 20px;
            text-align: center;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 14px 32px;
            border-radius: 40px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            border: none;
            cursor: pointer;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        .btn-outline {
            background: transparent;
            color: white;
            padding: 14px 32px;
            border-radius: 40px;
            font-weight: 600;
            text-decoration: none;
            border: 2px solid white;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-outline:hover {
            background: white;
            color: #6366f1;
            transform: translateY(-2px);
        }

        /* Footer */
        .footer {
            background: #1f2937;
            color: white;
            padding: 60px 20px 30px;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
        }

        .footer-logo {
            font-size: 24px;
            font-weight: 800;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .footer-text {
            font-size: 14px;
            color: #9ca3af;
            line-height: 1.6;
        }

        .footer-links h4 {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .footer-links a {
            display: block;
            color: #9ca3af;
            text-decoration: none;
            margin-bottom: 10px;
            font-size: 14px;
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: white;
        }

        .footer-bottom {
            text-align: center;
            padding-top: 40px;
            margin-top: 40px;
            border-top: 1px solid #374151;
            font-size: 14px;
            color: #9ca3af;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .mobile-menu-btn {
                display: block;
            }

            .carousel-title {
                font-size: 32px;
            }

            .carousel-description {
                font-size: 14px;
            }

            .section-title {
                font-size: 28px;
            }

            .stat-number {
                font-size: 36px;
            }

            .features-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .feature-card {
                padding: 30px 20px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .footer-container {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .footer-logo {
                justify-content: center;
            }
        }

        /* Animation */
        .fade-up {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease;
        }

        .fade-up.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .carousel-control-prev, .carousel-control-next {
       z-index: 99;
        }
    </style>
</head>
<body>
    <!-- Mobile Navigation -->
    <div class="mobile-nav" id="mobileNav">
        <div class="mobile-nav-header">
            <div class="logo">
                <i class="bi bi-people-fill"></i>
                <?php echo SITE_NAME; ?>
            </div>
        </div>
        <div class="mobile-nav-links">
            <a href="#features">Features</a>
            <a href="#howitworks">How It Works</a>
            <a href="#testimonials">Testimonials</a>
            <a href="<?php echo SITE_URL; ?>modules/auth/login.php">Sign In</a>
            <a href="<?php echo SITE_URL; ?>modules/auth/register.php" class="btn-primary" style="display: block; text-align: center; margin-top: 20px;">Get Started</a>
        </div>
    </div>
    <div class="nav-overlay" id="navOverlay"></div>

    <!-- Navigation -->
    <nav class="landing-nav">
        <div class="nav-container">
            <a href="#" class="logo">
                <i class="bi bi-people-fill"></i>
                <?php echo SITE_NAME; ?>
            </a>
            <div class="nav-links">
                <a href="#features" class="nav-link">Features</a>
                <a href="#howitworks" class="nav-link">How It Works</a>
                <a href="#testimonials" class="nav-link">Testimonials</a>
                <a href="<?php echo SITE_URL; ?>modules/auth/login.php" class="btn-outline" style="padding: 8px 20px;">Sign In</a>
                <a href="<?php echo SITE_URL; ?>modules/auth/register.php" class="btn-primary" style="padding: 8px 20px;">Get Started</a>
            </div>
            <button class="mobile-menu-btn" id="mobileMenuBtn">
                <i class="bi bi-list"></i>
            </button>
        </div>
    </nav>

    <!-- Hero Carousel -->
    <div id="heroCarousel" class="carousel slide hero-carousel" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
        </div>
        
        <div class="carousel-inner">
            <div class="carousel-item active" style="background-image: url('assets/images/photo-1.jpg');">
                <div class="carousel-content">
                    <h1 class="carousel-title">Connect with Fellow Students</h1>
                    <p class="carousel-description">Build meaningful connections with students from around the world. Share ideas, collaborate on projects, and grow together.</p>
                    <div class="carousel-buttons">
                        <a href="<?php echo SITE_URL; ?>modules/auth/register.php" class="btn-primary">Get Started <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="carousel-item" style="background-image: url('assets/images/photo-2.jpg');">
                <div class="carousel-content">
                    <h1 class="carousel-title">Join Study Groups</h1>
                    <p class="carousel-description">Find or create study groups for your courses. Collaborate with peers, share resources, and ace your exams together.</p>
                    <div class="carousel-buttons">
                        <a href="<?php echo SITE_URL; ?>modules/auth/register.php" class="btn-primary">Join Now <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="carousel-item" style="background-image: url('assets/images/photo-3.jpg');">
                <div class="carousel-content">
                    <h1 class="carousel-title">Discover Events & Opportunities</h1>
                    <p class="carousel-description">Stay updated with campus events, workshops, and networking opportunities. Never miss out on important activities.</p>
                    <div class="carousel-buttons">
                        <a href="<?php echo SITE_URL; ?>modules/auth/register.php" class="btn-primary">Explore Events <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
        
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <!-- Features Section -->
    <section id="features" class="features-section">
        <div class="section-container">
            <h2 class="section-title fade-up">Powerful Features</h2>
            <p class="section-subtitle fade-up">Everything you need to succeed in your academic journey</p>
            
            <div class="features-grid">
                <div class="feature-card fade-up">
                    <div class="feature-icon">
                        <i class="bi bi-chat-dots"></i>
                    </div>
                    <h3 class="feature-title">Real-time Messaging</h3>
                    <p class="feature-description">Connect instantly with peers through our real-time messaging system. Share ideas, ask questions, and collaborate seamlessly.</p>
                </div>

                <div class="feature-card fade-up">
                    <div class="feature-icon">
                        <i class="bi bi-people"></i>
                    </div>
                    <h3 class="feature-title">Study Groups</h3>
                    <p class="feature-description">Create or join study groups based on your courses. Work together, share resources, and ace your exams as a team.</p>
                </div>

                <div class="feature-card fade-up">
                    <div class="feature-icon">
                        <i class="bi bi-calendar-event"></i>
                    </div>
                    <h3 class="feature-title">Events & Workshops</h3>
                    <p class="feature-description">Discover and participate in academic events, workshops, and social gatherings happening on and off campus.</p>
                </div>

                <div class="feature-card fade-up">
                    <div class="feature-icon">
                        <i class="bi bi-file-post"></i>
                    </div>
                    <h3 class="feature-title">Share & Discover</h3>
                    <p class="feature-description">Share your thoughts, achievements, and resources. Discover content from fellow students and stay updated.</p>
                </div>

                <div class="feature-card fade-up">
                    <div class="feature-icon">
                        <i class="bi bi-trophy"></i>
                    </div>
                    <h3 class="feature-title">Achievements</h3>
                    <p class="feature-description">Earn badges and recognition for your participation and contributions to the community.</p>
                </div>

                <div class="feature-card fade-up">
                    <div class="feature-icon">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h3 class="feature-title">Safe & Secure</h3>
                    <p class="feature-description">Verified student profiles ensure a safe and authentic networking environment for everyone.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="section-container">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number" data-target="10000">0</div>
                    <div class="stat-label">Active Students</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" data-target="500">0</div>
                    <div class="stat-label">Study Groups</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" data-target="1000">0</div>
                    <div class="stat-label">Events Monthly</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" data-target="50">0</div>
                    <div class="stat-label">Universities</div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section id="howitworks" class="features-section">
        <div class="section-container">
            <h2 class="section-title fade-up">How It Works</h2>
            <p class="section-subtitle fade-up">Get started in just 3 simple steps</p>
            
            <div class="features-grid">
                <div class="feature-card fade-up">
                    <div class="feature-icon">
                        <i class="bi bi-person-plus fs-1"></i>
                    </div>
                    <h3 class="feature-title">1. Create Account</h3>
                    <p class="feature-description">Sign up with your university email and complete your student profile in minutes.</p>
                </div>

                <div class="feature-card fade-up">
                    <div class="feature-icon">
                        <i class="bi bi-people fs-1"></i>
                    </div>
                    <h3 class="feature-title">2. Build Network</h3>
                    <p class="feature-description">Connect with classmates, join study groups, and follow topics you're interested in.</p>
                </div>

                <div class="feature-card fade-up">
                    <div class="feature-icon">
                        <i class="bi bi-graph-up fs-1"></i>
                    </div>
                    <h3 class="feature-title">3. Start Collaborating</h3>
                    <p class="feature-description">Share knowledge, attend events, and grow together with your network.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="section-container">
            <h2 class="section-title fade-up">Ready to Get Started?</h2>
            <p class="section-subtitle fade-up">Join thousands of students already using <?php echo SITE_NAME; ?> to enhance their academic journey</p>
            <a href="<?php echo SITE_URL; ?>modules/auth/register.php" class="btn-primary">
                <i class="bi bi-person-plus"></i> Create Free Account
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div>
                <div class="footer-logo">
                    <i class="bi bi-people-fill"></i>
                    <?php echo SITE_NAME; ?>
                </div>
                <p class="footer-text">Connecting students worldwide for better collaboration and growth.</p>
            </div>
            
            <div class="footer-links">
                <h4>Platform</h4>
                <a href="#features">Features</a>
                <a href="#howitworks">How It Works</a>
                <a href="#testimonials">Testimonials</a>
            </div>
            
            <div class="footer-links">
                <h4>Support</h4>
                <a href="#">Help Center</a>
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
                <a href="#">Contact Us</a>
            </div>
            
            <div class="footer-links">
                <h4>Connect</h4>
                <a href="#"><i class="bi bi-twitter"></i> Twitter</a>
                <a href="#"><i class="bi bi-instagram"></i> Instagram</a>
                <a href="#"><i class="bi bi-linkedin"></i> LinkedIn</a>
                <a href="#"><i class="bi bi-facebook"></i> Facebook</a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 <?php echo SITE_NAME; ?>. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileNav = document.getElementById('mobileNav');
        const navOverlay = document.getElementById('navOverlay');

        function openMobileMenu() {
            mobileNav.classList.add('active');
            navOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeMobileMenu() {
            mobileNav.classList.remove('active');
            navOverlay.classList.remove('active');
            document.body.style.overflow = '';
        }

        mobileMenuBtn.addEventListener('click', openMobileMenu);
        navOverlay.addEventListener('click', closeMobileMenu);

        // Close mobile menu when clicking on a link
        document.querySelectorAll('.mobile-nav-links a').forEach(link => {
            link.addEventListener('click', closeMobileMenu);
        });

        // Scroll reveal animation
        const fadeElements = document.querySelectorAll('.fade-up');
        
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);
        
        fadeElements.forEach(element => {
            observer.observe(element);
        });
        
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                    closeMobileMenu();
                }
            });
        });
        
        // Animate numbers on scroll
        function animateNumbers() {
            const statNumbers = document.querySelectorAll('.stat-number');
            
            statNumbers.forEach(stat => {
                const target = parseInt(stat.getAttribute('data-target'));
                let current = 0;
                const increment = target / 50;
                
                const updateNumber = () => {
                    if (current < target) {
                        current += increment;
                        stat.textContent = Math.floor(current).toLocaleString();
                        requestAnimationFrame(updateNumber);
                    } else {
                        stat.textContent = target.toLocaleString();
                    }
                };
                
                updateNumber();
            });
        }
        
        // Trigger number animation when stats come into view
        const statsObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateNumbers();
                    statsObserver.unobserve(entry.target);
                }
            });
        });
        
        const statsSection = document.querySelector('.stats-section');
        if (statsSection) {
            statsObserver.observe(statsSection);
        }
        
        // Add active class to nav links on scroll
        window.addEventListener('scroll', () => {
            const sections = document.querySelectorAll('section[id]');
            const scrollPosition = window.scrollY + 100;
            
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionBottom = sectionTop + section.offsetHeight;
                
                if (scrollPosition >= sectionTop && scrollPosition < sectionBottom) {
                    const id = section.getAttribute('id');
                    document.querySelectorAll('.nav-link').forEach(link => {
                        link.classList.remove('active');
                        if (link.getAttribute('href') === `#${id}`) {
                            link.classList.add('active');
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>