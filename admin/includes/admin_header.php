<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';

// Require login for all admin pages
requireLogin();

$admin = getLoggedInAdmin();
$currentPage = basename($_SERVER['PHP_SELF']);

if (!isset($pageTitle)) {
    $pageTitle = 'Dashboard';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo clean($pageTitle); ?> - Admin Panel | Lotus Valley School</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Admin Panel Specific Styles */
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .admin-sidebar {
            width: 260px;
            background: linear-gradient(180deg, #0F766E 0%, #064E4A 100%);
            color: white;
            flex-shrink: 0;
            display: none;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 50;
            overflow-y: auto;
        }

        @media (min-width: 1024px) {
            .admin-sidebar {
                display: flex;
            }
        }

        .admin-sidebar.mobile-open {
            display: flex;
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .sidebar-logo-img {
            width: 50px;
            height: 50px;
            background: white;
            border-radius: 0.75rem;
            padding: 0.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-logo-img img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .sidebar-logo-text {
            flex: 1;
        }

        .sidebar-logo-text h2 {
            font-size: 1.125rem;
            font-weight: 700;
            color: white;
            margin: 0;
            line-height: 1.2;
        }

        .sidebar-logo-text span {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.6);
        }

        /* Navigation */
        .sidebar-nav {
            flex: 1;
            padding: 1rem 0.75rem;
        }

        .nav-section {
            margin-bottom: 1.5rem;
        }

        .nav-section-title {
            font-size: 0.6875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: rgba(255, 255, 255, 0.4);
            padding: 0 0.75rem;
            margin-bottom: 0.5rem;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            border-radius: 0.5rem;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9375rem;
            font-weight: 500;
            transition: all 0.2s ease;
            margin-bottom: 0.25rem;
        }

        .sidebar-link:hover {
            background: rgba(255, 255, 255, 0.08);
            color: white;
        }

        .sidebar-link.active {
            background: var(--color-primary);
            color: white;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
        }

        .sidebar-link i {
            width: 1.25rem;
            text-align: center;
            font-size: 1rem;
        }

        .sidebar-link .badge {
            margin-left: auto;
            background: var(--color-secondary);
            color: #1a1a1a;
            font-size: 0.6875rem;
            padding: 0.125rem 0.5rem;
            border-radius: 9999px;
            font-weight: 600;
        }

        /* Sidebar Footer */
        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 0.75rem;
            margin-bottom: 0.75rem;
        }

        .admin-avatar {
            width: 2.5rem;
            height: 2.5rem;
            background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        .admin-info {
            flex: 1;
            min-width: 0;
        }

        .admin-name {
            font-size: 0.875rem;
            font-weight: 600;
            color: white;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .admin-role {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.5);
        }

        .logout-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            padding: 0.625rem;
            background: rgba(239, 68, 68, 0.2);
            color: #FCA5A5;
            border: none;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .logout-btn:hover {
            background: rgba(239, 68, 68, 0.3);
            color: white;
        }

        /* Main Content */
        .admin-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: var(--bg-light);
            min-width: 0;
        }

        @media (min-width: 1024px) {
            .admin-main {
                margin-left: 260px;
            }
        }

        /* Top Header */
        .admin-header {
            background: white;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border-light);
            position: sticky;
            top: 0;
            z-index: 40;
        }

        .admin-header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .mobile-menu-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 2.5rem;
            height: 2.5rem;
            background: var(--bg-light);
            border: none;
            border-radius: 0.5rem;
            color: var(--text-heading);
            cursor: pointer;
        }

        @media (min-width: 1024px) {
            .mobile-menu-btn {
                display: none;
            }
        }

        .page-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-heading);
        }

        .admin-header-right {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .header-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 2.5rem;
            height: 2.5rem;
            background: var(--bg-light);
            border: none;
            border-radius: 0.5rem;
            color: var(--text-body);
            cursor: pointer;
            transition: all 0.2s;
        }

        .header-btn:hover {
            background: var(--color-primary);
            color: white;
        }

        .header-btn.primary {
            background: var(--color-primary);
            color: white;
        }

        /* Content Area */
        .admin-content {
            flex: 1;
            padding: 1.5rem;
            overflow-y: auto;
        }

        /* Mobile Overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 45;
        }

        .sidebar-overlay.active {
            display: block;
        }

        /* Admin Cards */
        .admin-card {
            background: white;
            border-radius: 1rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-light);
        }

        .admin-card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border-light);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .admin-card-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--text-heading);
        }

        .admin-card-body {
            padding: 1.5rem;
        }
    </style>
</head>

<body>
    <div class="admin-layout">
        <!-- Mobile Overlay -->
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleMobileSidebar()"></div>

        <!-- Sidebar -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <div class="sidebar-logo-img">
                        <img src="../assets/images/logo.png" alt="Lotus Valley">
                    </div>
                    <div class="sidebar-logo-text">
                        <h2>Lotus Valley</h2>
                        <span>Admin Panel</span>
                    </div>
                </div>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-section">
                    <div class="nav-section-title">Main</div>
                    <a href="dashboard.php"
                        class="sidebar-link <?php echo $currentPage === 'dashboard.php' ? 'active' : ''; ?>">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Content Management</div>
                    <a href="toppers.php"
                        class="sidebar-link <?php echo in_array($currentPage, ['toppers.php', 'add_topper.php', 'edit_topper.php']) ? 'active' : ''; ?>">
                        <i class="fas fa-trophy"></i>
                        <span>Toppers</span>
                    </a>
                    <a href="staff.php"
                        class="sidebar-link <?php echo in_array($currentPage, ['staff.php', 'add_staff.php', 'edit_staff.php']) ? 'active' : ''; ?>">
                        <i class="fas fa-user-tie"></i>
                        <span>Staff</span>
                    </a>
                    <a href="gallery.php"
                        class="sidebar-link <?php echo in_array($currentPage, ['gallery.php', 'add_gallery.php']) ? 'active' : ''; ?>">
                        <i class="fas fa-images"></i>
                        <span>Gallery</span>
                    </a>
                    <a href="testimonials.php"
                        class="sidebar-link <?php echo $currentPage === 'testimonials.php' ? 'active' : ''; ?>">
                        <i class="fas fa-star"></i>
                        <span>Testimonials</span>
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Updates</div>
                    <a href="announcements.php"
                        class="sidebar-link <?php echo in_array($currentPage, ['announcements.php', 'add_announcement.php', 'edit_announcement.php']) ? 'active' : ''; ?>">
                        <i class="fas fa-bullhorn"></i>
                        <span>Announcements</span>
                    </a>
                    <a href="events.php"
                        class="sidebar-link <?php echo in_array($currentPage, ['events.php', 'add_event.php', 'edit_event.php']) ? 'active' : ''; ?>">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Events</span>
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Inquiries</div>
                    <a href="admissions.php"
                        class="sidebar-link <?php echo $currentPage === 'admissions.php' ? 'active' : ''; ?>">
                        <i class="fas fa-envelope"></i>
                        <span>Admissions</span>
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Settings</div>
                    <a href="settings.php"
                        class="sidebar-link <?php echo $currentPage === 'settings.php' ? 'active' : ''; ?>">
                        <i class="fas fa-cog"></i>
                        <span>Site Settings</span>
                    </a>
                </div>
            </nav>

            <div class="sidebar-footer">
                <div class="admin-profile">
                    <div class="admin-avatar">
                        <?php echo strtoupper(substr($admin['username'] ?? 'A', 0, 1)); ?>
                    </div>
                    <div class="admin-info">
                        <div class="admin-name"><?php echo clean($admin['username'] ?? 'Admin'); ?></div>
                        <div class="admin-role">Administrator</div>
                    </div>
                </div>
                <a href="logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="admin-main">
            <!-- Header -->
            <header class="admin-header">
                <div class="admin-header-left">
                    <button class="mobile-menu-btn" onclick="toggleMobileSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="page-title"><?php echo clean($pageTitle); ?></h1>
                </div>

                <div class="admin-header-right">
                    <a href="../" target="_blank" class="header-btn" title="View Website">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                    <button class="header-btn primary" title="Notifications">
                        <i class="fas fa-bell"></i>
                    </button>
                </div>
            </header>

            <!-- Content Area -->
            <main class="admin-content">

                <script>
                    function toggleMobileSidebar() {
                        const sidebar = document.getElementById('adminSidebar');
                        const overlay = document.getElementById('sidebarOverlay');
                        sidebar.classList.toggle('mobile-open');
                        overlay.classList.toggle('active');
                    }
                </script>