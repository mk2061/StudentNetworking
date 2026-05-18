    <style>
        :root {
            --primary: #1a4f8c;
            --secondary: #2e7d32;
            --accent: #ff6f00;
            --light: #f8f9fa;
            --dark: #212529;
            --success: #28a745;
            --warning: #ffc107;
            --danger: #dc3545;
            --info: #17a2b8;
            --bg-primary: #ffffff;
            --bg-secondary: #f8f9fa;
            --bg-card: #ffffff;
            --text-primary: #212529;
            --text-secondary: #6c757d;
            --border-color: #dee2e6;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        [data-bs-theme="dark"] {
            --bg-primary: #121212;
            --bg-secondary: #1e1e1e;
            --bg-card: #2d2d2d;
            --text-primary: #ffffff;
            --text-secondary: #b0b0b0;
            --border-color: #404040;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            --primary: #2c8fff;
            --secondary: #4caf50;
            --accent: #ff9800;
        }

        [data-bs-theme="blue"] {
            --bg-primary: #0d1b2a;
            --bg-secondary: #1b263b;
            --bg-card: #415a77;
            --text-primary: #e0e1dd;
            --text-secondary: #b0b0b0;
            --border-color: #415a77;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.4);
            --primary: #48cae4;
            --secondary: #90e0ef;
            --accent: #ff9e00;
        }

        [data-bs-theme="green"] {
            --bg-primary: #1b4332;
            --bg-secondary: #2d6a4f;
            --bg-card: #40916c;
            --text-primary: #d8f3dc;
            --text-secondary: #b7e4c7;
            --border-color: #40916c;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            --primary: #52b788;
            --secondary: #74c69d;
            --accent: #ff9e00;
        }

        [data-bs-theme="purple"] {
            --bg-primary: #240046;
            --bg-secondary: #3c096c;
            --bg-card: #5a189a;
            --text-primary: #e0aaff;
            --text-secondary: #c77dff;
            --border-color: #5a189a;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.4);
            --primary: #9d4edd;
            --secondary: #c77dff;
            --accent: #ff6d00;
        }

        /* ==============================================
           Universal Text Color Utilities (Theme-Aware)
           ============================================== */

        /* Base Text Colors (Light / Default Theme) */
        /*.text-primary { color: var(--primary) !important; }
        .text-secondary { color: var(--secondary) !important; }
        .text-accent { color: var(--accent) !important; }
        .text-success { color: var(--success) !important; }
        .text-warning { color: var(--warning) !important; }
        .text-danger { color: var(--danger) !important; }
        .text-info { color: var(--info) !important; }
        .text-light { color: var(--light) !important; }
        .text-dark { color: var(--dark) !important; }
        .text-muted { color: var(--text-secondary) !important; }
        .text-body { color: var(--text-primary) !important; }
        .text-white { color: #fff !important; }*/

        /* Ensure link colors adapt too */
        a.text-primary:hover,
        a.text-secondary:hover,
        a.text-accent:hover {
            opacity: 0.8;
            text-decoration: underline;
        }

        /* ==============================================
           Theme-Specific Adjustments (Optional Overrides)
           ============================================== */
/* ==============================================
   THEME-AWARE TEXT COLORS (Improved Readability)
   ============================================== */

/* DARK THEME */
[data-bs-theme="dark"] .text-primary { color: var(--primary) !important; }
[data-bs-theme="dark"] .text-secondary { color: var(--secondary) !important; }
[data-bs-theme="dark"] .text-accent { color: color-mix(in srgb, var(--accent) 90%, white) !important; }
[data-bs-theme="dark"] .text-muted { color: color-mix(in srgb, var(--text-secondary) 90%, white) !important; }
[data-bs-theme="dark"] .text-body { color: color-mix(in srgb, var(--text-primary) 95%, white) !important; }
[data-bs-theme="dark"] .text-dark { color: #f0f0f0 !important; }

/* BLUE THEME */
[data-bs-theme="blue"] .text-primary { color: var(--primary) !important; }
[data-bs-theme="blue"] .text-secondary { color: var(--secondary) !important; }
[data-bs-theme="blue"] .text-accent { color: color-mix(in srgb, var(--accent) 90%, white) !important; }
[data-bs-theme="blue"] .text-muted { color: color-mix(in srgb, var(--text-secondary) 85%, white) !important; }
[data-bs-theme="blue"] .text-body { color: color-mix(in srgb, var(--text-primary) 90%, white) !important; }
[data-bs-theme="blue"] .text-dark { color: #f5f7fa !important; }

/* GREEN THEME */
[data-bs-theme="green"] .text-primary { color: var(--primary) !important; }
[data-bs-theme="green"] .text-secondary { color: var(--secondary) !important; }
[data-bs-theme="green"] .text-accent { color: color-mix(in srgb, var(--accent) 85%, white) !important; }
[data-bs-theme="green"] .text-muted { color: color-mix(in srgb, var(--text-secondary) 85%, white) !important; }
[data-bs-theme="green"] .text-body { color: color-mix(in srgb, var(--text-primary) 90%, white) !important; }
[data-bs-theme="green"] .text-dark { color: #edf6f9 !important; }

/* PURPLE THEME */
[data-bs-theme="purple"] .text-primary { color: var(--primary) !important; }
[data-bs-theme="purple"] .text-secondary { color: var(--secondary) !important; }
[data-bs-theme="purple"] .text-accent { color: color-mix(in srgb, var(--accent) 85%, white) !important; }
[data-bs-theme="purple"] .text-muted { color: color-mix(in srgb, var(--text-secondary) 85%, white) !important; }
[data-bs-theme="purple"] .text-body { color: color-mix(in srgb, var(--text-primary) 90%, white) !important; }
[data-bs-theme="purple"] .text-dark { color: #f3d9fa !important; }

/* LIGHT THEME */
[data-bs-theme="light"] .text-primary { color: var(--primary) !important; }
[data-bs-theme="light"] .text-secondary { color: var(--secondary) !important; }
[data-bs-theme="light"] .text-accent { color: color-mix(in srgb, var(--accent) 90%, black) !important; }
[data-bs-theme="light"] .text-muted { color: color-mix(in srgb, var(--text-secondary) 80%, black) !important; }
[data-bs-theme="light"] .text-body { color: var(--text-primary) !important; }
[data-bs-theme="light"] .text-dark { color: #212529 !important; }

/* ==============================================
   SIDEBAR LINKS (Improved Hover Readability)
   ============================================== */
[data-bs-theme="dark"] .sidebar-nav .nav-link:hover,
[data-bs-theme="dark"] .sidebar-nav .nav-link.active {
    background: rgba(255, 255, 255, 0.08);
}

[data-bs-theme="blue"] .sidebar-nav .nav-link:hover,
[data-bs-theme="green"] .sidebar-nav .nav-link:hover,
[data-bs-theme="purple"] .sidebar-nav .nav-link:hover,
[data-bs-theme="blue"] .sidebar-nav .nav-link.active,
[data-bs-theme="green"] .sidebar-nav .nav-link.active,
[data-bs-theme="purple"] .sidebar-nav .nav-link.active {
    background: rgba(255, 255, 255, 0.1);
    color: #fff !important;
}

/* ==============================================
   UNIVERSAL BACKGROUND UTILITIES (Including .bg-purple)
   ============================================== */

/* LIGHT THEME */
[data-bs-theme="light"] .bg-purple {
    background-color: #6f42c1 !important; /* Bootstrap purple base */
    color: #ffffff !important;
}
[data-bs-theme="light"] .bg-purple .text-muted,
[data-bs-theme="light"] .bg-purple p,
[data-bs-theme="light"] .bg-purple span {
    color: rgba(255, 255, 255, 0.9) !important;
}

/* DARK THEME */
[data-bs-theme="dark"] .bg-purple {
    background-color: #9d4edd !important; /* Softer violet for visibility */
    color: #ffffff !important;
}
[data-bs-theme="dark"] .bg-purple .text-muted,
[data-bs-theme="dark"] .bg-purple p,
[data-bs-theme="dark"] .bg-purple span {
    color: rgba(255, 255, 255, 0.85) !important;
}

/* BLUE THEME */
[data-bs-theme="blue"] .bg-purple {
    background-color: #7209b7 !important;
    color: #f8f9fa !important;
}
[data-bs-theme="blue"] .bg-purple .text-muted,
[data-bs-theme="blue"] .bg-purple p,
[data-bs-theme="blue"] .bg-purple span {
    color: rgba(255, 255, 255, 0.85) !important;
}

/* GREEN THEME */
[data-bs-theme="green"] .bg-purple {
    background-color: #7b2cbf !important;
    color: #e9ecef !important;
}
[data-bs-theme="green"] .bg-purple .text-muted,
[data-bs-theme="green"] .bg-purple p,
[data-bs-theme="green"] .bg-purple span {
    color: rgba(255, 255, 255, 0.85) !important;
}

/* PURPLE THEME */
[data-bs-theme="purple"] .bg-purple {
    background-color: #5a189a !important; /* Deep violet */
    color: #f3d9fa !important;
}
[data-bs-theme="purple"] .bg-purple .text-muted,
[data-bs-theme="purple"] .bg-purple p,
[data-bs-theme="purple"] .bg-purple span {
    color: rgba(255, 255, 255, 0.85) !important;
}

/* ==============================================
   GLOBAL STYLE FOR .bg-purple ACROSS ALL THEMES
   ============================================== */
.bg-purple {
    border-radius: 0.5rem;
    box-shadow: var(--shadow);
    transition: background-color 0.3s ease, color 0.3s ease;
}

.bg-purple a {
    color: #fff !important;
    text-decoration: underline;
}

.bg-purple a:hover {
    color: #ffd6ff !important;
}

/* ==============================================
   GENERAL TEXT ADJUSTMENTS
   ============================================== */
/*.text-muted {
    opacity: 0.92;
    font-weight: 400;
}

.text-accent {
    font-weight: 500;
    letter-spacing: 0.02em;
}

.text-body {
    line-height: 1.65;
    font-weight: 400;
}

.text-dark {
    font-weight: 500;
}*/

/* ==============================================
   CARD COLOR UTILITIES (Theme-Aware)
   ============================================== */

/* Base (Light Theme / Default) */
.card {
    background-color: var(--bg-card) !important;
    color: var(--text-primary) !important;
    border: 1px solid var(--border-color) !important;
    box-shadow: var(--shadow);
    transition: background-color 0.3s, color 0.3s, border-color 0.3s;
    border-radius: 0.75rem;
}

.card-header {
    background-color: var(--bg-secondary) !important;
    color: var(--text-primary) !important;
    border-bottom: 1px solid var(--border-color) !important;
}

.card-body {
    background-color: var(--bg-card) !important;
    color: var(--text-primary) !important;
}

.card-footer {
    background-color: var(--bg-secondary) !important;
    border-top: 1px solid var(--border-color) !important;
    color: var(--text-secondary) !important;
}

/* Muted / Subtle Text inside Cards */
/*.card .text-muted {
    color: var(--text-secondary) !important;
}*/

/* ==============================================
   DARK THEME
   ============================================== */
[data-bs-theme="dark"] .card {
    background-color: var(--bg-card) !important;
    color: var(--text-primary) !important;
    border-color: var(--border-color) !important;
    box-shadow: var(--shadow);
}

[data-bs-theme="dark"] .card-header,
[data-bs-theme="dark"] .card-footer {
    background-color: var(--bg-secondary) !important;
    color: var(--text-secondary) !important;
}

/* ==============================================
   BLUE THEME
   ============================================== */
[data-bs-theme="blue"] .card {
    background-color: var(--bg-card) !important;
    color: var(--text-primary) !important;
    border-color: var(--border-color) !important;
    box-shadow: var(--shadow);
}

[data-bs-theme="blue"] .card-header,
[data-bs-theme="blue"] .card-footer {
    background-color: var(--bg-secondary) !important;
    color: var(--text-secondary) !important;
}

/* ==============================================
   GREEN THEME
   ============================================== */
[data-bs-theme="green"] .card {
    background-color: var(--bg-card) !important;
    color: var(--text-primary) !important;
    border-color: var(--border-color) !important;
    box-shadow: var(--shadow);
}

[data-bs-theme="green"] .card-header,
[data-bs-theme="green"] .card-footer {
    background-color: var(--bg-secondary) !important;
    color: var(--text-secondary) !important;
}

/* ==============================================
   PURPLE THEME
   ============================================== */
[data-bs-theme="purple"] .card {
    background-color: var(--bg-card) !important;
    color: var(--text-primary) !important;
    border-color: var(--border-color) !important;
    box-shadow: var(--shadow);
}

[data-bs-theme="purple"] .card-header,
[data-bs-theme="purple"] .card-footer {
    background-color: var(--bg-secondary) !important;
    color: var(--text-secondary) !important;
}

/* ==============================================
   CARD ACCENTS / VARIANTS
   ============================================== */
.card-primary { border-left: 4px solid var(--primary) !important; }
.card-secondary { border-left: 4px solid var(--secondary) !important; }
.card-accent { border-left: 4px solid var(--accent) !important; }
.card-success { border-left: 4px solid var(--success) !important; }
.card-warning { border-left: 4px solid var(--warning) !important; }
.card-danger { border-left: 4px solid var(--danger) !important; }
.card-info { border-left: 4px solid var(--info) !important; }


/* ==============================================
   TABLE COLOR UTILITIES (Theme-Aware)
   ============================================== */

/* Base (Light Theme / Default) */
.table {
    color: var(--text-primary) !important;
    background-color: var(--bg-card) !important;
    border-color: var(--border-color) !important;
    transition: background-color 0.3s, color 0.3s;
}

.table th,
.table td {
    border-color: var(--border-color) !important;
    vertical-align: middle;
}

.table thead th {
    background-color: var(--bg-secondary) !important;
    color: var(--text-primary) !important;
    font-weight: 600;
}

.table tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.05) !important;
}

/* Muted Text Inside Tables */
/*.table .text-muted {
    color: var(--text-secondary) !important;
}*/

/* ==============================================
   DARK THEME
   ============================================== */
[data-bs-theme="dark"] .table {
    color: var(--text-primary) !important;
    background-color: var(--bg-card) !important;
    border-color: var(--border-color) !important;
}

[data-bs-theme="dark"] .table thead th {
    background-color: var(--bg-secondary) !important;
    color: var(--text-primary) !important;
}

[data-bs-theme="dark"] .table tbody tr:hover {
    background-color: rgba(255, 255, 255, 0.05) !important;
}

/* ==============================================
   BLUE THEME
   ============================================== */
[data-bs-theme="blue"] .table {
    color: var(--text-primary) !important;
    background-color: var(--bg-card) !important;
    border-color: var(--border-color) !important;
}

[data-bs-theme="blue"] .table thead th {
    background-color: var(--bg-secondary) !important;
    color: var(--text-primary) !important;
}

[data-bs-theme="blue"] .table tbody tr:hover {
    background-color: rgba(72, 202, 228, 0.15) !important; /* soft blue hover */
}

/* ==============================================
   GREEN THEME
   ============================================== */
[data-bs-theme="green"] .table {
    color: var(--text-primary) !important;
    background-color: var(--bg-card) !important;
    border-color: var(--border-color) !important;
}

[data-bs-theme="green"] .table thead th {
    background-color: var(--bg-secondary) !important;
    color: var(--text-primary) !important;
}

[data-bs-theme="green"] .table tbody tr:hover {
    background-color: rgba(82, 183, 136, 0.15) !important; /* soft green hover */
}

/* ==============================================
   PURPLE THEME
   ============================================== */
[data-bs-theme="purple"] .table {
    color: var(--text-primary) !important;
    background-color: var(--bg-card) !important;
    border-color: var(--border-color) !important;
}

[data-bs-theme="purple"] .table thead th {
    background-color: var(--bg-secondary) !important;
    color: var(--text-primary) !important;
}

[data-bs-theme="purple"] .table tbody tr:hover {
    background-color: rgba(157, 78, 221, 0.15) !important; /* soft purple hover */
}

/* ==============================================
   TABLE VARIANTS
   ============================================== */
.table-primary { border-left: 4px solid var(--primary) !important; }
.table-secondary { border-left: 4px solid var(--secondary) !important; }
.table-accent { border-left: 4px solid var(--accent) !important; }
.table-success { border-left: 4px solid var(--success) !important; }
.table-warning { border-left: 4px solid var(--warning) !important; }
.table-danger { border-left: 4px solid var(--danger) !important; }
.table-info { border-left: 4px solid var(--info) !important; }

/* Striped Rows (Bootstrap Compatible) */
.table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(0, 0, 0, 0.03) !important;
}
[data-bs-theme="dark"] .table-striped tbody tr:nth-of-type(odd),
[data-bs-theme="blue"] .table-striped tbody tr:nth-of-type(odd),
[data-bs-theme="green"] .table-striped tbody tr:nth-of-type(odd),
[data-bs-theme="purple"] .table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(255, 255, 255, 0.03) !important;
}


        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-primary);
            color: var(--text-primary);
            transition: all 0.3s ease;
            margin: 0;
            overflow-x: hidden;
        }

        .navbar {
            background-color: var(--bg-card) !important;
            border-bottom: 1px solid var(--border-color);
        }

        .sidebar {
            width: 20%;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
            overflow-y: auto;
            background: var(--bg-secondary);
            color: var(--text-primary);
            border-right: 1px solid var(--border-color);
        }

        .sidebar.collapsed {
            width: 70px;
        }

        .sidebar.collapsed .sidebar-title,
        .sidebar.collapsed .nav-link span,
        .sidebar.collapsed .badge,
        .sidebar.collapsed .user-info,
        .sidebar.collapsed .nav-section-header {
            display: none !important;
        }

        .sidebar.collapsed .nav-link {
            text-align: center;
            padding: 0.75rem 0.5rem;
            position: relative;
            
            align-items: center !important;
            justify-content: center !important;
        }

        .sidebar.collapsed .nav-link i {
            margin: 0 !important;
            font-size: 1.2rem !important;
        }

        .sidebar-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border-color);
            background: var(--bg-card);
        }

        .sidebar-logo {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
        }

        .sidebar-title h6 {
            color: var(--text-primary);
        }

        .sidebar-title small {
            color: var(--text-secondary) !important;
        }

        .avatar-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            background: var(--primary);
            color: white;
        }

        .status-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            background: var(--success);
        }

        .sidebar-user {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border-color);
            background: var(--bg-card);
        }

        .nav-section-header {
            padding: 1rem 1.25rem 0.5rem 1.25rem;
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--text-secondary) !important;
        }

        .sidebar-nav .nav-link {
            color: var(--text-primary);
            padding: 0.75rem 1.25rem;
            border-left: 3px solid transparent;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            background: transparent;
            border: none;
            text-decoration: none;
        }

        .sidebar-nav .nav-link:hover {
            color: var(--primary);
            background: var(--bg-primary);
            border-left-color: var(--primary);
        }

        .sidebar-nav .nav-link.active {
            color: var(--primary);
            background: var(--bg-primary);
            border-left-color: var(--primary);
            font-weight: 600;
        }

        .sidebar-nav .nav-link i {
            width: 20px;
            text-align: center;
            color: inherit;
        }

        .sidebar-nav .badge {
            font-size: 0.65rem;
            padding: 0.25rem 0.4rem;
            background: var(--primary) !important;
            color: white;
        }

        .sidebar-footer {
            padding: 1rem 1.25rem;
            border-top: 1px solid var(--border-color);
            background: var(--bg-card);
        }

        .progress {
            background: var(--border-color);
            height: 4px;
        }

        .progress-bar {
            background: var(--success);
        }

        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: var(--border-color);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: var(--text-secondary);
            border-radius: 2px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: var(--primary);
        }

        .sidebar-toggle {
            border-color: var(--border-color) !important;
            color: var(--text-secondary) !important;
        }

        .sidebar-toggle:hover {
            border-color: var(--primary) !important;
            color: var(--primary) !important;
            background: var(--bg-primary) !important;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 250px !important;
                transform: translateX(-100%);
            }
            
            .sidebar.mobile-open {
                transform: translateX(0);
            }
        }

        @media (max-width: 576px) {
            .sidebar {
                width: 300px !important;
            }
        }

        @media (min-width: 769px) and (max-width: 1024px) {
            .sidebar {
                width: 25%;
            }
        }

        .content-wrapper {
            margin-left: 20%;
            padding: 20px;
            min-height: 100vh;
            background: var(--bg-primary);
            transition: margin-left 0.3s ease, width 0.3s ease, padding 0.3s ease;
            width: calc(100% - 20%);
            box-sizing: border-box;
        }

        .sidebar.collapsed ~ .content-wrapper {
            margin-left: 70px;
            width: calc(100% - 70px);
        }

        @media (max-width: 768px) {
            .sidebar.collapsed {
                width: 170px !important;
            }

            .content-wrapper {
                margin-left: 0;
                width: 100%;
                padding: 15px;
            }
            
            .sidebar.mobile-open ~ .content-wrapper {
                margin-left: 0;
                width: 100%;
            }
            
            .sidebar.mobile-open ~ .content-wrapper::before {
                content: '';
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 999;
                pointer-events: all;
            }
        }

        @media (max-width: 576px) {
            .content-wrapper {
                padding: 10px;
            }
        }

        @media (min-width: 769px) and (max-width: 1024px) {
            .content-wrapper {
                margin-left: 25%;
                width: calc(100% - 25%);
                padding: 15px;
            }
            
            .sidebar.collapsed ~ .content-wrapper {
                margin-left: 70px;
                width: calc(100% - 70px);
            }
        }

        .container-fluid {
            padding: 0;
            width: 100%;
            max-width: 100%;
        }

        .tab-content {
            background: var(--bg-primary);
            border-radius: 8px;
            padding: 20px;
            box-shadow: var(--shadow);
        }

        .admin-main-card,
        .data-table-card {
            background: var(--bg-card);
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow);
        }

        /*.quick-action-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }*/

        @media (max-width: 768px) {
            /*.quick-action-grid {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)) !important;
            }*/
        }

        @media (max-width: 576px) {
            .quick-action-grid {
                /*grid-template-columns: 1fr !important;
                display: inline-flex !important;
                flex-wrap: wrap !important;
                align-items: center !important;
                justify-content: center !important;*/
            }
        }

        .system-health-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 768px) {
            .system-health-grid {
                grid-template-columns: 1fr;
            }
        }

        @media print {
            .content-wrapper {
                margin-left: 0 !important;
                width: 100% !important;
            }
            .sidebar {
                display: none;
            }
        }

        @media (prefers-contrast: high) {
            .content-wrapper {
                border-left: 2px solid var(--primary);
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .content-wrapper,
            .sidebar {
                transition: none;
            }
        }

        /* Additional styles for navbar toggle button */
        .navbar-toggler {
            border: none;
            padding: 0.5rem;
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(0, 0, 0, 0.55)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        /* Navbar toggler icons for different Bootstrap theme modes */

        /* Light Theme */
        [data-bs-theme="light"] .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(0, 0, 0, 0.55)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        /* Dark Theme */
        [data-bs-theme="dark"] .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(255, 255, 255, 0.75)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        /* Blue Theme */
        [data-bs-theme="blue"] .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(255, 255, 255, 0.75)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        /* Green Theme */
        [data-bs-theme="green"] .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(255, 255, 255, 0.75)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        /* Purple Theme */
        [data-bs-theme="purple"] .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(255, 255, 255, 0.75)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        .progress {
            background: var(--border-color);
            height: 4px;
        }

        .progress-bar {
            background: var(--success);
        }

        /* Scrollbar styling */
        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: var(--border-color);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: var(--text-secondary);
            border-radius: 2px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: var(--primary);
        }

        /* Button styling */
        .navbar-toggler {
            float: left;
            display: inline-flex !important;
            margin-left: 0;
        }

        .sidebar-toggle {
            border-color: var(--border-color) !important;
            color: var(--text-secondary) !important;
        }

        .sidebar-toggle:hover {
            border-color: var(--primary) !important;
            color: var(--primary) !important;
            background: var(--bg-primary) !important;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar-toggler {
                position: absolute !important;
                right: 5px !important;
                z-index: 9999;
            }
        
            .sidebar {
                transform: translateX(-100%);
                box-shadow: var(--shadow);
            }
            
            .sidebar.mobile-open {
                transform: translateX(0);
            }
        }

        .dashboard-card {
            background-color: var(--bg-card);
            border-radius: 12px;
            box-shadow: var(--shadow);
            transition: transform 0.3s, box-shadow 0.3s;
            margin-bottom: 15px;
            border: 1px solid var(--border-color);
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }

        .account-balance {
            background: linear-gradient(135deg, var(--primary), #2c5aa0);
            color: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .quick-action {
            text-align: center;
            padding: 15px 5px;
            border-radius: 10px;
            background: var(--bg-card);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            transition: all 0.3s;
            border: 1px solid var(--border-color);
        }

        .quick-action:hover {
            background: var(--bg-secondary);
            cursor: pointer;
            transform: translateY(-2px);
        }

        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: var(--bg-card);
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            border-top: 1px solid var(--border-color);
        }

        .theme-selector {
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 1050;
            background: var(--bg-card);
            border-radius: 12px;
            padding: 15px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border-color);
            min-width: 250px;
        }

        .theme-option {
            display: flex;
            align-items: center;
            padding: 10px;
            margin: 5px 0;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .theme-option:hover {
            background-color: var(--bg-secondary);
        }

        .theme-option.active {
            background-color: var(--primary);
            color: white;
        }

        .theme-preview {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            margin-right: 10px;
            border: 2px solid var(--border-color);
        }

        .theme-light .theme-preview { background: linear-gradient(135deg, #ffffff, #f8f9fa); }
        .theme-dark .theme-preview { background: linear-gradient(135deg, #121212, #2d2d2d); }
        .theme-blue .theme-preview { background: linear-gradient(135deg, #0d1b2a, #415a77); }
        .theme-green .theme-preview { background: linear-gradient(135deg, #1b4332, #40916c); }
        .theme-purple .theme-preview { background: linear-gradient(135deg, #240046, #5a189a); }

        .transaction-item {
            border-bottom: 1px solid var(--border-color);
            padding: 12px 0;
        }

        .transaction-item:last-child {
            border-bottom: none;
        }

        .form-control, .form-select {
            background-color: var(--bg-card);
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        .form-control:focus, .form-select:focus {
            background-color: var(--bg-card);
            border-color: var(--primary);
            color: var(--text-primary);
            box-shadow: 0 0 0 0.25rem rgba(26, 79, 140, 0.25);
        }

        .modal-content {
            background-color: var(--bg-card);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
        }

        .modal-header {
            border-bottom-color: var(--border-color);
        }

        .modal-footer {
            border-top-color: var(--border-color);
        }

        .table {
            color: var(--text-primary);
        }

        .table-hover tbody tr:hover {
            background-color: var(--bg-secondary);
        }

        .alert {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
        }

        .theme-toggle-btn {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            border-radius: 20px;
            padding: 8px 15px;
            transition: all 0.3s;
        }

        .theme-toggle-btn:hover {
            background: var(--primary);
            color: white;
        }

        .theme-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
        }

        .investment-card {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
        }

        .ai-insight-card {
            background: linear-gradient(135deg, var(--primary), var(--accent));
            color: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
        }

        /* Custom scrollbar for different themes */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-secondary);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--accent);
        }

        /* Chart.js theme adaptation */
        .chart-container {
            background: var(--bg-card);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
        }

        /* DataTables theme adaptation */
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_processing,
        .dataTables_wrapper .dataTables_paginate {
            color: var(--text-primary) !important;
        }

        .dataTables_wrapper .dataTables_filter input {
            background-color: var(--bg-card);
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        .page-item .page-link {
            background-color: var(--bg-card);
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        .page-item.active .page-link {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        /* Theme transition smoothness */
        * {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }

        .card-icon {
            font-size: 1.8rem;
            color: var(--primary);
            margin-bottom: 10px;
        }
        
        .quick-action {
            text-align: center;
            padding: 15px 5px;
            border-radius: 10px;
            background: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            transition: all 0.3s;
        }
        
        .quick-action:hover {
            background: var(--light);
            cursor: pointer;
        }
        
        .quick-action i {
            font-size: 1.5rem;
            color: var(--primary);
            margin-bottom: 8px;
        }
        
        .account-balance {
            background: linear-gradient(135deg, var(--primary), #2c5aa0);
            color: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .transaction-item {
            border-bottom: 1px solid #eee;
            padding: 12px 0;
        }
        
        .transaction-item:last-child {
            border-bottom: none;
        }
        
        .nav-tabs .nav-link.active {
            color: var(--primary);
            font-weight: 600;
            border-bottom: 3px solid var(--primary);
        }
        
        .feature-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: rgba(26, 79, 140, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }
        
        .feature-icon i {
            color: var(--primary);
            font-size: 1.3rem;
        }
        
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }
        
        .bottom-nav-item {
            text-align: center;
            padding: 10px 5px;
            color: #6c757d;
            font-size: 0.8rem;
        }
        
        .bottom-nav-item.active {
            color: var(--primary);
        }
        
        .bottom-nav-item i {
            display: block;
            font-size: 1.3rem;
            margin-bottom: 5px;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(26, 79, 140, 0.25);
        }
        
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        
        .btn-primary:hover {
            background-color: #153a6b;
            border-color: #153a6b;
        }
        
        .btn-success {
            background-color: var(--secondary);
            border-color: var(--secondary);
        }
        
        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--dark);
        }
        
        .badge-success {
            background-color: var(--success);
        }
        
        .badge-danger {
            background-color: var(--danger);
        }
        
        .badge-warning {
            background-color: var(--warning);
            color: var(--dark);
        }
        
        .tab-content {
            padding-bottom: 70px;
        }

        
        .investment-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
        }
        
        .progress {
            height: 8px;
            margin: 10px 0;
        }
        
        .support-ticket {
            border-left: 4px solid var(--primary);
            padding-left: 15px;
            margin-bottom: 15px;
        }
        
        .ticket-status-open { border-left-color: var(--success); }
        .ticket-status-pending { border-left-color: var(--warning); }
        .ticket-status-closed { border-left-color: var(--secondary); }
        
        .statement-period {
            background: white;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 10px;
            border-left: 4px solid var(--primary);
        }
        
        .gift-card {
            background: linear-gradient(135deg, #ff6b6b, #ffa36b);
            color: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            position: relative;
            overflow: hidden;
        }
        
        .gift-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.1);
            transform: rotate(30deg);
        }
        
        .international-transfer-info {
            background: #e3f2fd;
            border-radius: 8px;
            padding: 15px;
            margin: 10px 0;
            border-left: 4px solid var(--info);
        }
        
        .rate-alert {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 10px;
            margin: 10px 0;
            font-size: 0.9rem;
        }
        
        .trending-feature {
            background: white;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 10px;
            border: 1px solid #e0e0e0;
        }
        
        .feature-badge {
            background: var(--primary);
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.7rem;
            margin-left: 8px;
        }

        
        .security-badge {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border-radius: 20px;
            padding: 8px 15px;
            font-size: 0.8rem;
            margin: 2px;
        }
        
        .biometric-option {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 15px;
            margin: 10px 0;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .biometric-option.active {
            border-color: var(--primary);
            background: rgba(26, 79, 140, 0.05);
        }
        
        .biometric-option:hover {
            border-color: var(--primary);
        }
        
        .limit-setting {
            background: white;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 10px;
            border-left: 4px solid var(--primary);
        }
        
        .admin-card {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
        }
        
        .real-time-alert {
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 1050;
            min-width: 300px;
            animation: slideInRight 0.3s ease-out;
        }
        
        @keyframes slideInRight {
            from { transform: translateX(100%); }
            to { transform: translateX(0); }
        }
        
        .two-factor-setup {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin: 15px 0;
        }
        
        .qr-code {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            margin: 15px 0;
        }
        
        .session-list {
            max-height: 300px;
            overflow-y: auto;
        }
        
        .session-item {
            border-bottom: 1px solid #eee;
            padding: 10px 0;
        }
        
        .session-item:last-child {
            border-bottom: none;
        }
        
        .risk-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
        }
        
        .risk-low { background: var(--success); }
        .risk-medium { background: var(--warning); }
        .risk-high { background: var(--danger); }
        
        .analytics-chart {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .api-status {
            padding: 8px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .status-active { background: #d4edda; color: #155724; }
        .status-inactive { background: #f8d7da; color: #721c24; }
        .status-pending { background: #fff3cd; color: #856404; }


        
        .limit-progress {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            border-left: 4px solid var(--primary);
        }
        
        .charge-breakdown {
            background: #e8f4fd;
            border-radius: 8px;
            padding: 15px;
            margin: 10px 0;
            border: 1px solid #b3d9ff;
        }
        
        .charge-item {
            display: flex;
            justify-content: between;
            align-items: center;
            padding: 5px 0;
            border-bottom: 1px solid #dee2e6;
        }
        
        .charge-item:last-child {
            border-bottom: none;
        }
        
        .face-verification-container {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            margin: 20px 0;
            border: 2px dashed #dee2e6;
        }
        
        .video-preview {
            width: 100%;
            max-width: 300px;
            height: 300px;
            background: #000;
            border-radius: 10px;
            margin: 0 auto 20px;
            overflow: hidden;
        }
        
        .verification-steps {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
            position: relative;
        }
        
        .verification-step {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            position: relative;
            z-index: 2;
        }
        
        .verification-step.active {
            background: var(--primary);
            color: white;
        }
        
        .verification-step.completed {
            background: var(--success);
            color: white;
        }
        
        .verification-steps::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 20px;
            right: 20px;
            height: 2px;
            background: #e9ecef;
            z-index: 1;
        }
        
        .limit-warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 12px;
            margin: 10px 0;
        }
        
        .limit-danger {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 8px;
            padding: 12px;
            margin: 10px 0;
        }
        
        .transfer-tier {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 20px;
            margin: 10px 0;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .transfer-tier.selected {
            border-color: var(--primary);
            background: rgba(26, 79, 140, 0.05);
        }
        
        .transfer-tier.recommended {
            border-color: var(--success);
            position: relative;
            overflow: hidden;
        }
        
        .transfer-tier.recommended::before {
            content: 'RECOMMENDED';
            position: absolute;
            top: 10px;
            right: -30px;
            background: var(--success);
            color: white;
            padding: 5px 30px;
            transform: rotate(45deg);
            font-size: 0.7rem;
            font-weight: bold;
        }
        
        .face-motion-instruction {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin: 15px 0;
            text-align: center;
        }
        
        .motion-indicator {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 2rem;
            transition: all 0.3s;
        }
        
        .motion-indicator.active {
            background: var(--success);
            color: white;
            animation: pulse 1s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .security-badge {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border-radius: 20px;
            padding: 8px 15px;
            font-size: 0.8rem;
            margin: 2px;
        }


        
        .ai-insight-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
        }
        
        .ai-insight-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.1);
            transform: rotate(30deg);
        }
        
        .fraud-alert {
            background: linear-gradient(135deg, #ff6b6b, #ffa36b);
            color: white;
            border-radius: 10px;
            padding: 15px;
            margin: 10px 0;
            border-left: 4px solid #dc3545;
        }
        
        .blockchain-verification {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin: 15px 0;
            border: 2px solid #e9ecef;
        }
        
        .transaction-risk-indicator {
            width: 100%;
            height: 8px;
            background: #e9ecef;
            border-radius: 4px;
            margin: 10px 0;
            overflow: hidden;
        }
        
        .risk-level {
            height: 100%;
            border-radius: 4px;
            transition: width 0.5s ease;
        }
        
        .risk-low { background: #28a745; }
        .risk-medium { background: #ffc107; }
        .risk-high { background: #dc3545; }
        
        .ai-recommendation {
            background: #e8f5e8;
            border: 1px solid #28a745;
            border-radius: 10px;
            padding: 15px;
            margin: 10px 0;
        }
        
        .smart-contract-status {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 15px;
            background: #f8f9fa;
            border-radius: 8px;
            margin: 5px 0;
        }
        
        .predictive-analytics {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .neural-network-visual {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 10px;
            margin: 15px 0;
            position: relative;
            overflow: hidden;
        }
        
        .ai-chatbot {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 350px;
            height: 500px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            z-index: 1050;
            display: none;
        }
        
        .chatbot-header {
            background: var(--primary);
            color: white;
            padding: 15px;
            border-radius: 15px 15px 0 0;
        }
        
        .chatbot-messages {
            height: 350px;
            overflow-y: auto;
            padding: 15px;
        }
        
        .chatbot-input {
            padding: 15px;
            border-top: 1px solid #e9ecef;
        }
        
        .quantum-safe {
            background: linear-gradient(135deg, #00b4db, #0083b0);
            color: white;
            border-radius: 10px;
            padding: 15px;
            margin: 10px 0;
        }
        
        .behavioral-analytics {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 10px;
            padding: 15px;
            margin: 10px 0;
        }
        
        .ai-training-progress {
            background: #e9ecef;
            border-radius: 10px;
            padding: 15px;
            margin: 10px 0;
        }
        
        .data-table-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .quick-stats {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            border-left: 4px solid var(--primary);
        }
        
        .record-actions {
            display: flex;
            gap: 5px;
        }
        
        .btn-action {
            width: 32px;
            height: 32px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .bulk-actions {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            border: 1px solid #dee2e6;
        }
        
        .filter-bar {
            background: white;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            border: 1px solid #e9ecef;
        }
        
        .export-options {
            background: #e8f5e8;
            border: 1px solid #28a745;
            border-radius: 8px;
            padding: 15px;
            margin: 10px 0;
        }
        
        .audit-trail {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 12px;
            margin: 5px 0;
            font-size: 0.85rem;
        }
        
        .data-import {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            border-radius: 8px;
            padding: 20px;
            margin: 15px 0;
        }
        
        .system-health {
            background: white;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            border-left: 4px solid #28a745;
        }
        
        .health-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
        }
        
        .health-good { background: #28a745; }
        .health-warning { background: #ffc107; }
        .health-critical { background: #dc3545; }
        
        .user-role-badge {
            font-size: 0.7rem;
            padding: 4px 8px;
        }
        
        .pagination-info {
            font-size: 0.9rem;
            color: #6c757d;
        }


        
        .real-time-monitor {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .monitor-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        
        .stat-card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            backdrop-filter: blur(10px);
        }
        
        .alert-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .automation-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            border-left: 4px solid #28a745;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .role-management {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .permission-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 10px;
            margin-top: 15px;
        }
        
        .permission-item {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 10px;
            text-align: center;
        }
        
        .system-health-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .health-metric {
            background: white;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .metric-value {
            font-size: 2rem;
            font-weight: bold;
            margin: 10px 0;
        }
        
        .trend-indicator {
            display: inline-flex;
            align-items: center;
            font-size: 0.9rem;
            padding: 4px 8px;
            border-radius: 12px;
        }
        
        .trend-up { background: #d4edda; color: #155724; }
        .trend-down { background: #f8d7da; color: #721c24; }
        .trend-neutral { background: #e2e3e5; color: #383d41; }
        
        /*.quick-action-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }
        
        .quick-action-btn {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 20px 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .quick-action-btn:hover {
            border-color: #007bff;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.2);
        }*/
        
        .audit-trail-container {
            max-height: 400px;
            overflow-y: auto;
        }
        
        .live-feed {
            background: #1a1a1a;
            color: #00ff00;
            font-family: 'Courier New', monospace;
            padding: 15px;
            border-radius: 8px;
            max-height: 300px;
            overflow-y: auto;
            margin-bottom: 20px;
        }
        
        .log-entry {
            margin-bottom: 5px;
            font-size: 0.9rem;
        }
        
        .log-timestamp {
            color: #888;
        }
        
        .risk-matrix {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin: 15px 0;
        }
        
        .risk-cell {
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            color: white;
            font-weight: bold;
        }
        
        .risk-low { background: #28a745; }
        .risk-medium { background: #ffc107; color: #000; }
        .risk-high { background: #fd7e14; }
        .risk-critical { background: #dc3545; }
        
        .automation-workflow {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin: 15px 0;
        }
        
        .workflow-step {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding: 10px;
            background: white;
            border-radius: 8px;
            border-left: 4px solid #007bff;
        }
        
        .step-number {
            width: 30px;
            height: 30px;
            background: #007bff;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-weight: bold;
        }

        
        /* Responsive Admin Styles */
        .admin-main-card {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
        }
        
        .data-table-card {
            background: var(--bg-card);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border-color);
            overflow-x: auto;
        }
        
        .quick-stats {
            background: var(--bg-card);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            border-left: 4px solid var(--primary);
            box-shadow: var(--shadow);
        }
        
        .record-actions {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }
        
        .btn-action {
            width: 32px;
            height: 32px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .bulk-actions {
            background: var(--bg-secondary);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            border: 1px solid var(--border-color);
        }
        
        .filter-bar {
            background: var(--bg-card);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            border: 1px solid var(--border-color);
        }
        
        .real-time-monitor {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .monitor-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        
        .stat-card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            backdrop-filter: blur(10px);
        }
        
        .alert-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--danger);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /*.quick-action-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }*/
        
        .quick-action-btn {
            background: var(--bg-card);
            border: 2px solid var(--border-color);
            border-radius: 10px;
            padding: 20px 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .quick-action-btn:hover {
            border-color: var(--primary);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.2);
        }
        
        .system-health-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .health-metric {
            background: var(--bg-card);
            border-radius: 10px;
            padding: 15px;
            box-shadow: var(--shadow);
        }
        
        .metric-value {
            font-size: 2rem;
            font-weight: bold;
            margin: 10px 0;
        }
        
        .risk-matrix {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 10px;
            margin: 15px 0;
        }
        
        .risk-cell {
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            color: white;
            font-weight: bold;
        }
        
        .risk-low { background: var(--success); }
        .risk-medium { background: var(--warning); color: var(--dark); }
        .risk-high { background: var(--accent); }
        .risk-critical { background: var(--danger); }
        
        .automation-workflow {
            background: var(--bg-secondary);
            border-radius: 10px;
            padding: 20px;
            margin: 15px 0;
        }
        
        .workflow-step {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding: 10px;
            background: var(--bg-card);
            border-radius: 8px;
            border-left: 4px solid var(--primary);
        }
        
        .step-number {
            width: 30px;
            height: 30px;
            background: var(--primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-weight: bold;
        }
        
        .avatar-placeholder {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.8rem;
        }
        
        /* Mobile Responsive Styles */
        @media (max-width: 768px) {
            .hidden-xs {
                display: none !important;
            }
            
            .container-fluid {
                padding-left: 10px;
                padding-right: 10px;
            }
            
            .navbar-brand {
                font-size: 1rem;
            }
            
            .admin-main-card {
                padding: 15px;
            }
            
            .data-table-card {
                padding: 10px;
                overflow-x: auto;
            }
            
            .bulk-actions .btn-group {
                display: flex;
                flex-wrap: wrap;
                gap: 5px;
                margin-bottom: 10px;
            }
            
            .filter-bar .row {
                margin-bottom: 10px;
            }
            
            .filter-bar .col-md-3,
            .filter-bar .col-md-2 {
                margin-bottom: 10px;
            }
            
            .monitor-stats {
                grid-template-columns: repeat(2, 1fr);
            }
            
            /*.quick-action-grid {
                grid-template-columns: repeat(2, 1fr);
            }*/
            
            .system-health-grid {
                grid-template-columns: 1fr;
            }
            
            .risk-matrix {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .table-responsive {
                font-size: 0.85rem;
            }
            
            .record-actions {
                justify-content: center;
            }
            
            .theme-selector {
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 90%;
                max-width: 300px;
            }
            
            .real-time-indicators {
                display: none;
            }
            
            .admin-quick-actions {
                display: none;
            }
        }
        
        @media (max-width: 576px) {
            .hidden-xs {
                display: none !important;
            }

            .monitor-stats {
                grid-template-columns: 1fr;
            }
            
            /*.quick-action-grid {
                grid-template-columns: 1fr !important;
            }*/
            
            .table th, .table td {
                padding: 0.5rem;
            }
            
            .btn-sm {
                padding: 0.25rem 0.5rem;
                font-size: 0.775rem;
            }
            
            .modal-dialog {
                margin: 0.5rem;
            }
        }
        
        /* Dark theme adjustments */
        [data-bs-theme="dark"] .quick-stats,
        [data-bs-theme="dark"] .data-table-card,
        [data-bs-theme="dark"] .health-metric,
        [data-bs-theme="dark"] .quick-action-btn {
            border-color: var(--border-color);
        }


		/* Professional User Details Styles */
		.user-header {
		    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
		    backdrop-filter: blur(10px);
		}

		.avatar-xl {
		    width: 80px;
		    height: 80px;
		    font-weight: 600;
		}

		.stat-card {
		    transition: all 0.3s ease;
		    border-left: 4px solid transparent;
		}

		.stat-card:hover {
		    transform: translateY(-2px);
		    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
		}

		.stat-card:nth-child(1) { border-left-color: #007bff; }
		.stat-card:nth-child(2) { border-left-color: #28a745; }
		.stat-card:nth-child(3) { border-left-color: #17a2b8; }
		.stat-card:nth-child(4) { border-left-color: #ffc107; }

		.stat-icon {
		    transition: all 0.3s ease;
		}

		.stat-card:hover .stat-icon {
		    transform: scale(1.1);
		}

		.info-item, .security-item {
		    transition: all 0.2s ease;
		}

		.info-item:hover, .security-item:hover {
		    background-color: #f8f9fa;
		    margin-left: -8px;
		    margin-right: -8px;
		    padding-left: 8px;
		    padding-right: 8px;
		    border-radius: 8px;
		}

		.user-id-badge {
		    backdrop-filter: blur(10px);
		    border: 1px solid rgba(255, 255, 255, 0.2);
		}

		.card {
		    transition: all 0.3s ease;
		}

		.card:hover {
		    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
		}

		.btn-outline-primary, .btn-outline-warning, .btn-outline-info, .btn-outline-secondary {
		    transition: all 0.3s ease;
		    border-width: 2px;
		    font-weight: 500;
		}

		.btn-outline-primary:hover { transform: translateY(-1px); }
		.btn-outline-warning:hover { transform: translateY(-1px); }
		.btn-outline-info:hover { transform: translateY(-1px); }
		.btn-outline-secondary:hover { transform: translateY(-1px); }

		/* Responsive adjustments */
		@media (max-width: 768px) {
		    .user-header {
		        text-align: center;
		        padding: 2rem 1rem !important;
		    }
		    
		    .avatar-xl {
		        margin: 0 auto 1rem auto;
		    }
		    
		    .stat-card .card-body {
		        padding: 1rem !important;
		    }
		}



/* Edit User Form Styles */
.required::after {
    content: " *";
    color: #dc3545;
}

.transaction-description {
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.sticky-top {
    position: sticky;
    top: 0;
    z-index: 10;
}

.bg-opacity-10 {
    background-color: rgba(var(--bs-primary-rgb), 0.1) !important;
}

/* Print Styles */
@media print {
    .modal-header,
    .modal-footer {
        display: none !important;
    }
    
    .modal-body {
        padding: 0 !important;
    }
    
    .modal-content {
        border: none !important;
        box-shadow: none !important;
    }
    
    .btn {
        display: none !important;
    }
}

/* Risk score colors */
.progress-bar.bg-success { background-color: #28a745 !important; }
.progress-bar.bg-warning { background-color: #ffc107 !important; }
.progress-bar.bg-danger { background-color: #dc3545 !important; }

/* Badge variations */
.badge.bg-success { background-color: #28a745 !important; }
.badge.bg-warning { background-color: #ffc107 !important; }
.badge.bg-danger { background-color: #dc3545 !important; }
.badge.bg-dark { background-color: #343a40 !important; }

/* Rejection Modal Styles */
.btn-group-vertical .btn {
    text-align: left;
    white-space: normal;
    padding: 0.5rem 0.75rem;
    margin-bottom: 0.25rem;
}

.btn-group-vertical .btn:last-child {
    margin-bottom: 0;
}

/* Loading states */
.spinner-border-sm {
    width: 1rem;
    height: 1rem;
}

/* Refund Modal Styles */
#refundModal .alert-info {
    border-left: 4px solid #0dcaf0;
}

#refundModal .form-check-inline {
    margin-right: 1rem;
}

#refundModal .btn-group-vertical .btn {
    text-align: left;
    white-space: normal;
    padding: 0.5rem 0.75rem;
    margin-bottom: 0.25rem;
    border: 1px solid #dee2e6;
}

#refundModal .btn-group-vertical .btn:hover {
    background-color: #f8f9fa;
    border-color: #0d6efd;
}

#refundModal .input-group-text {
    background-color: #f8f9fa;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    #refundModal .modal-dialog {
        margin: 0.5rem;
    }
    
    #refundModal .row {
        flex-direction: column;
    }
}

/* Banking Professional Styles */
.bg-banking-gradient {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
}

.banking-icon-container {
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.banking-header-section {
    border-bottom: 1px solid #e9ecef;
    padding-bottom: 1.5rem;
}

.banking-amount {
    font-weight: 700;
    color: #2e59d9;
    font-size: 2.25rem;
}

.transaction-type-badge {
    background: #e3f2fd;
    /*color: #1565c0;*/
    font-weight: 600;
    padding: 0.375rem 0.75rem;
}

.transaction-status-badge {
    background: #e8f5e8;
    /*color: #2e7d32;*/
    font-weight: 600;
    padding: 0.375rem 0.75rem;
}

.reference-code {
    font-family: 'Courier New', monospace;
    font-weight: 600;
    color: #495057;
    font-size: 1.1rem;
}

/* Banking Tabs */
.banking-tabs .nav-link {
    border: none;
    color: #6c757d;
    font-weight: 500;
    padding: 0.75rem 1.5rem;
    border-bottom: 3px solid transparent;
}

.banking-tabs .nav-link.active {
    color: #2e59d9;
    background: none;
    border-bottom: 3px solid #2e59d9;
}

.banking-tabs .nav-link:hover {
    border-bottom: 3px solid #dee2e6;
}

/* Banking Cards */
.banking-card {
    /*background: #fff;
    border: 1px solid #e3e6f0;*/
    border-radius: 0.5rem;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
}

.banking-card .card-header {
    /*background: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;*/
    padding: 1rem 1.25rem;
    font-weight: 600;
    /*color: #5a5c69;*/
}

.banking-card .card-body {
    padding: 1.25rem;
}

/* Info Grid */
.info-grid {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}


/*.info-label {
    color: #6c757d;
    font-size: 0.875rem;
    font-weight: 500;
}

.info-value {
    color: #5a5c69;
    font-weight: 600;
}*/

/* Timeline */
.timeline {
    position: relative;
    padding-left: 2rem;
}

.timeline-item {
    position: relative;
    padding-bottom: 1.5rem;
}

.timeline-item:last-child {
    padding-bottom: 0;
}

.timeline-marker {
    position: absolute;
    left: -2rem;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #e3e6f0;
    border: 3px solid #fff;
    box-shadow: 0 0 0 2px #e3e6f0;
}

.timeline-item.completed .timeline-marker {
    background: #1cc88a;
    box-shadow: 0 0 0 2px #1cc88a;
}

.timeline-item.active .timeline-marker {
    background: #f6c23e;
    box-shadow: 0 0 0 2px #f6c23e;
}

.timeline-content {
    display: flex;
    flex-direction: column;
}

.timeline-title {
    font-weight: 600;
    color: #5a5c69;
}

.timeline-time {
    font-size: 0.875rem;
    color: #6c757d;
}

/* Party Information */
.party-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.party-avatar {
    width: 50px;
    height: 50px;
    background: #e3f2fd;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: #1565c0;
}

.party-name {
    color: #5a5c69;
    font-weight: 600;
}

/* Amount Breakdown */
.amount-breakdown {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.breakdown-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
}

.breakdown-item.total {
    border-top: 2px solid #e3e6f0;
    padding-top: 1rem;
    margin-top: 0.5rem;
}

.breakdown-label {
    color: #5a5c69;
    font-weight: 500;
}

.breakdown-value {
    font-weight: 600;
    color: #5a5c69;
}

/* Security Styles */
.security-status {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.security-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    border-radius: 0.375rem;
    background: #f8f9fc;
}

.security-item.verified {
    color: #1cc88a;
}

.security-item.pending {
    color: #f6c23e;
}

/* Risk Score */
.risk-score {
    display: flex;
    align-items: center;
    gap: 2rem;
}

.score-circle {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: conic-gradient(#1cc88a 0% 25%, #f6c23e 25% 75%, #e74a3b 75% 100%);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
}

.score-value {
    font-size: 1.25rem;
    line-height: 1;
}

.score-label {
    font-size: 0.75rem;
    opacity: 0.9;
}

.risk-badge {
    padding: 0.5rem 1rem;
    font-weight: 600;
}

/* Footer */
.banking-footer {
    /*background: #f8f9fc;
    border-top: 1px solid #e3e6f0;*/
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.footer-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
}

.security-notice {
    text-align: center;
    width: 100%;
}

/* Responsive */
@media (max-width: 768px) {
    .banking-amount {
        font-size: 1.75rem;
    }
    
    .risk-score {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .footer-actions {
        flex-direction: column;
        gap: 1rem;
    }
    
    .action-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        justify-content: center;
    }
}

.warning-icon-container {
    width: 40px;
    height: 40px;
    background: rgba(255, 193, 7, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.impact-stat {
    padding: 0.5rem;
    border-radius: 0.375rem;
    background: #f8f9fa;
}


.loading-spinner {
    display: inline-block;
    width: 1rem;
    height: 1rem;
    border: 2px solid #ffffff;
    border-radius: 50%;
    border-top-color: transparent;
    animation: spin 1s ease-in-out infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

        /* Style all pagination page number buttons as Bootstrap buttons */
        .dataTables_wrapper .dataTables_paginate .page-link {
            border: 1px solid #0d6efd;        /* outline border */
            color: #0d6efd;                   /* text color */
            border-radius: 0.25rem;           /* rounded corners */
            padding: 0.25rem 0.6rem;          /* small padding (btn-sm) */
            margin: 4px 2px;                    /* spacing between buttons */
            font-size: 0.875rem;              /* smaller text (btn-sm) */
            background-color: #fff;           /* white background */
            transition: all 0.2s ease-in-out; /* smooth hover effect */
        }

        /* Hover effect */
        #customPagination .page-link:hover {
            background-color: #0d6efd;
            color: #fff !important;
        }

        /* Active button */
        #customPagination .page-item.active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: #fff !important;
        }


        /* Assign Tickets Modal Styling */
        #assignTicketsModal .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        #assignTicketsModal .modal-header .btn-close {
            filter: invert(1);
        }

        #assignTicketsModal .alert-info {
            border-left: 4px solid #17a2b8;
        }

        #assignTicketsModal .form-check-label {
            font-weight: 500;
        }

        /* Loading state for assign button */
        #assignTicketsModal .btn-primary:disabled {
            opacity: 0.7;
        }

        /* Ticket Details Modal Styling */
        #ticketDetailsModal .modal-xl {
            max-width: 95%;
        }

        #ticketDetailsModal .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        #ticketDetailsModal .modal-header .btn-close {
            filter: invert(1);
        }

        #ticketDetailsModal .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        #ticketDetailsModal .ticket-description {
            line-height: 1.6;
            font-size: 0.95rem;
        }

        #ticketDetailsModal .avatar-placeholder {
            font-weight: 600;
        }

        #ticketDetailsModal .activity-timeline .activity-item {
            border-left: 2px solid #e9ecef;
            padding-left: 1rem;
            margin-left: 0.5rem;
        }

        #ticketDetailsModal .activity-timeline .activity-item:last-child {
            border-left: 2px solid transparent;
        }

        #ticketDetailsModal .messages-preview .message-preview {
            transition: all 0.2s ease;
        }

        #ticketDetailsModal .messages-preview .message-preview:hover {
            transform: translateX(5px);
        }

        #ticketDetailsModal .card-header {
            font-weight: 600;
        }

        /* Print Styles */
        @media print {
            #ticketDetailsModal .modal-dialog {
                max-width: none !important;
                margin: 0 !important;
            }
            
            #ticketDetailsModal .modal-content {
                border: none !important;
                box-shadow: none !important;
            }
        }














/* ==============================================
   THEME-AWARE TABLES
   ============================================== */

table {
    width: 100%;
    border-collapse: collapse;
    color: var(--text-primary);
    background-color: var(--bg-card);
    border: 1px solid var(--border-color);
    box-shadow: var(--shadow);
    transition: background-color 0.3s, color 0.3s, border-color 0.3s;
}

thead {
    background-color: var(--bg-secondary);
    color: var(--text-primary);
    border-bottom: 2px solid var(--border-color);
}

th, td {
    padding: 0.75rem;
    border: 1px solid var(--border-color);
    vertical-align: middle;
}

tbody tr:nth-child(even) {
    background-color: var(--bg-secondary);
}

tbody tr:hover {
    background-color: var(--accent);
    color: #fff;
    transition: background-color 0.2s;
}

.table-striped > tbody > tr:nth-of-type(odd) {
    background-color: var(--bg-secondary);
}

.table-bordered {
    border: 1px solid var(--border-color);
}

.table-hover > tbody > tr:hover {
    background-color: var(--accent);
    color: #fff;
}

/* ==============================================
   THEME-AWARE BADGES
   ============================================== */

.badge {
    display: inline-block;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.4em 0.65em;
    border-radius: 0.35rem;
    text-transform: capitalize;
    transition: background-color 0.3s, color 0.3s;
}

/* Primary Badges */
.badge-primary {
    background-color: var(--primary);
    color: #fff;
}

/* Secondary Badges */
.badge-secondary {
    background-color: var(--secondary);
    color: #fff;
}

/* Accent Badges */
.badge-accent {
    background-color: var(--accent);
    color: #fff;
}

/* Status / Semantic Badges */
.badge-success {
    background-color: var(--success);
    color: #fff;
}

.badge-warning {
    background-color: var(--warning);
    color: #212529;
}

.badge-danger {
    background-color: var(--danger);
    color: #fff;
}

.badge-info {
    background-color: var(--info);
    color: #fff;
}

/* Muted / Outline Badges */
.badge-muted {
    background-color: var(--bg-secondary);
    color: var(--text-secondary);
    border: 1px solid var(--border-color);
}

/* Outline Variants */
.badge-outline-primary {
    background-color: transparent;
    color: var(--primary);
    border: 1px solid var(--primary);
}

.badge-outline-secondary {
    background-color: transparent;
    color: var(--secondary);
    border: 1px solid var(--secondary);
}

.badge-outline-success {
    background-color: transparent;
    color: var(--success);
    border: 1px solid var(--success);
}

.badge-outline-danger {
    background-color: transparent;
    color: var(--danger);
    border: 1px solid var(--danger);
}

.badge-outline-warning {
    background-color: transparent;
    color: var(--warning);
    border: 1px solid var(--warning);
}

/* ==============================================
   THEME OVERRIDES (All inherit via variables)
   ============================================== */
[data-bs-theme="dark"] table,
[data-bs-theme="blue"] table,
[data-bs-theme="green"] table,
[data-bs-theme="purple"] table {
    background-color: var(--bg-card);
    color: var(--text-primary);
    border-color: var(--border-color);
}

[data-bs-theme="dark"] th,
[data-bs-theme="blue"] th,
[data-bs-theme="green"] th,
[data-bs-theme="purple"] th {
    background-color: var(--bg-secondary);
    color: var(--text-primary);
}

/* ==============================================
   THEME-AWARE BUTTONS & BACKGROUND UTILITIES
   ============================================== */

/* Base Button */
.btn {
    font-weight: 500;
    border-radius: 0.45rem;
    padding: 0.45rem 0.9rem;
    border: 1px solid transparent;
    transition: background-color 0.25s, color 0.25s, border-color 0.25s, box-shadow 0.25s;
    box-shadow: var(--shadow);
}

.btn:focus,
.btn:hover {
    opacity: 0.9;
}

/* Primary */
.btn-primary, .bg-primary {
    background-color: var(--primary) !important;
    border-color: var(--primary) !important;
    color: #fff !important;
}

.btn-primary:hover {
    background-color: color-mix(in srgb, var(--primary) 85%, black);
}

/* Secondary */
.btn-secondary, .bg-secondary {
    background-color: var(--secondary) !important;
    border-color: var(--secondary) !important;
    color: #fff !important;
}

.btn-secondary:hover {
    background-color: color-mix(in srgb, var(--secondary) 85%, black);
}

/* Accent */
.btn-accent, .bg-accent {
    background-color: var(--accent) !important;
    border-color: var(--accent) !important;
    color: #fff !important;
}

.btn-accent:hover {
    background-color: color-mix(in srgb, var(--accent) 85%, black);
}

/* Success */
.btn-success, .bg-success {
    background-color: var(--success) !important;
    border-color: var(--success) !important;
    color: #fff !important;
}

.btn-success:hover {
    background-color: color-mix(in srgb, var(--success) 85%, black);
}

/* Danger */
.btn-danger, .bg-danger {
    background-color: var(--danger) !important;
    border-color: var(--danger) !important;
    color: #fff !important;
}

.btn-danger:hover {
    background-color: color-mix(in srgb, var(--danger) 85%, black);
}

/* Warning */
.btn-warning, .bg-warning {
    background-color: var(--warning) !important;
    border-color: var(--warning) !important;
    color: #212529 !important;
}

.btn-warning:hover {
    background-color: color-mix(in srgb, var(--warning) 85%, black);
}

/* Info */
.btn-info, .bg-info {
    background-color: var(--info) !important;
    border-color: var(--info) !important;
    color: #fff !important;
}

.btn-info:hover {
    background-color: color-mix(in srgb, var(--info) 85%, black);
}

/* Light */
.btn-light, .bg-light {
    background-color: var(--light) !important;
    border-color: var(--border-color) !important;
    color: var(--text-primary) !important;
}

.btn-light:hover {
    background-color: color-mix(in srgb, var(--light) 85%, black);
}

/* Dark */
.btn-dark, .bg-dark {
    background-color: var(--dark) !important;
    border-color: var(--dark) !important;
    color: #fff !important;
}

.btn-dark:hover {
    background-color: color-mix(in srgb, var(--dark) 85%, white);
}

/* Outline Variants */
.btn-outline-primary {
    background-color: transparent !important;
    border-color: var(--primary) !important;
    color: var(--primary) !important;
}
.btn-outline-primary:hover {
    background-color: var(--primary) !important;
    color: #fff !important;
}

.btn-outline-secondary {
    background-color: transparent !important;
    border-color: var(--secondary) !important;
    color: var(--secondary) !important;
}
.btn-outline-secondary:hover {
    background-color: var(--secondary) !important;
    color: #fff !important;
}

.btn-outline-success {
    background-color: transparent !important;
    border-color: var(--success) !important;
    color: var(--success) !important;
}
.btn-outline-success:hover {
    background-color: var(--success) !important;
    color: #fff !important;
}

.btn-outline-danger {
    background-color: transparent !important;
    border-color: var(--danger) !important;
    color: var(--danger) !important;
}
.btn-outline-danger:hover {
    background-color: var(--danger) !important;
    color: #fff !important;
}

.btn-outline-warning {
    background-color: transparent !important;
    border-color: var(--warning) !important;
    color: var(--warning) !important;
}
.btn-outline-warning:hover {
    background-color: var(--warning) !important;
    color: #212529 !important;
}

/* Disabled */
.btn:disabled,
.btn.disabled {
    opacity: 0.65;
    cursor: not-allowed;
    box-shadow: none;
}

/* Link buttons */
.btn-link {
    background-color: transparent !important;
    border: none;
    color: var(--primary);
    text-decoration: underline;
}

.btn-link:hover {
    color: color-mix(in srgb, var(--primary) 85%, black);
}

/* ==============================================
   BACKGROUND UTILITIES (for spans, divs, etc.)
   ============================================== */

.bg-primary-subtle { background-color: color-mix(in srgb, var(--primary) 20%, white) !important; }
.bg-secondary-subtle { background-color: color-mix(in srgb, var(--secondary) 20%, white) !important; }
.bg-success-subtle { background-color: color-mix(in srgb, var(--success) 20%, white) !important; }
.bg-danger-subtle { background-color: color-mix(in srgb, var(--danger) 20%, white) !important; }
.bg-warning-subtle { background-color: color-mix(in srgb, var(--warning) 20%, white) !important; }
.bg-info-subtle { background-color: color-mix(in srgb, var(--info) 20%, white) !important; }
.bg-light-subtle { background-color: var(--bg-secondary) !important; }
.bg-dark-subtle { background-color: color-mix(in srgb, var(--dark) 20%, white) !important; }


/* ==============================================
   THEME-AWARE LISTS
   ============================================== */

/* Generic list text and background */
ul, ol {
    color: var(--text-primary);
}

/* List items default */
li {
    color: var(--text-secondary);
}

/* Hover and active states for interactive lists */
li:hover {
    color: var(--text-primary);
}

/* ==============================================
   Bootstrap-like List Group
   ============================================== */

.list-group {
    background-color: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 0.5rem;
    box-shadow: var(--shadow);
}

.list-group-item {
    background-color: var(--bg-card);
    color: var(--text-primary);
    border-color: var(--border-color);
    transition: background-color 0.2s ease, color 0.2s ease;
}

.list-group-item:hover {
    background-color: color-mix(in srgb, var(--bg-card) 85%, var(--primary));
    color: var(--text-primary);
}

.list-group-item.active {
    background-color: var(--primary);
    border-color: var(--primary);
    color: #fff;
    font-weight: 600;
}

.list-group-item.disabled,
.list-group-item:disabled {
    color: var(--text-secondary);
    background-color: var(--bg-secondary);
    opacity: 0.6;
    cursor: not-allowed;
}

/* Contextual variants */
.list-group-item-primary {
    background-color: color-mix(in srgb, var(--primary) 20%, var(--bg-card));
    color: var(--primary);
    border-color: var(--primary);
}

.list-group-item-secondary {
    background-color: color-mix(in srgb, var(--secondary) 20%, var(--bg-card));
    color: var(--secondary);
    border-color: var(--secondary);
}

.list-group-item-success {
    background-color: color-mix(in srgb, var(--success) 20%, var(--bg-card));
    color: var(--success);
    border-color: var(--success);
}

.list-group-item-danger {
    background-color: color-mix(in srgb, var(--danger) 20%, var(--bg-card));
    color: var(--danger);
    border-color: var(--danger);
}

.list-group-item-warning {
    background-color: color-mix(in srgb, var(--warning) 20%, var(--bg-card));
    color: var(--warning);
    border-color: var(--warning);
}

.list-group-item-info {
    background-color: color-mix(in srgb, var(--info) 20%, var(--bg-card));
    color: var(--info);
    border-color: var(--info);
}

/* Flush variant (no border-radius or spacing) */
.list-group-flush .list-group-item {
    border-left: none;
    border-right: none;
    border-radius: 0;
}

/* Horizontal variant */
.list-group-horizontal .list-group-item {
    border-right: 1px solid var(--border-color);
}
.list-group-horizontal .list-group-item:last-child {
    border-right: none;
}

/* ==============================================
   LIST ICON STYLES (for menus, dashboards, etc.)
   ============================================== */

.list-icon {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.list-icon i {
    color: var(--primary);
    font-size: 1rem;
}

.list-icon:hover i {
    color: color-mix(in srgb, var(--primary) 85%, black);
}




    </style>
