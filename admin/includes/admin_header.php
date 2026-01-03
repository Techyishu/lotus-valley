<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';

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
    <title><?php echo clean($pageTitle); ?> - Admin | Lotus Valley School</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: #f5f7fa;
            color: #1a202c;
        }

        .admin-layout {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .admin-sidebar {
            width: 250px;
            background: #ffffff;
            border-right: 1px solid #e2e8f0;
            display: none;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 1000;
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
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            background: #ffffff;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .sidebar-logo-img {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-logo-img img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .sidebar-logo-text h2 {
            font-size: 1rem;
            font-weight: 700;
            color: #1a202c;
            margin: 0;
            line-height: 1.3;
        }

        .sidebar-logo-text span {
            font-size: 0.7rem;
            color: #718096;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* Navigation */
        .sidebar-nav {
            flex: 1;
            padding: 1rem 0;
            overflow-y: auto;
        }

        .nav-section {
            margin-bottom: 0.5rem;
        }

        .nav-section-title {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #a0aec0;
            padding: 0.75rem 1.5rem 0.5rem;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.625rem 1.5rem;
            color: #4a5568;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.15s ease;
            border-left: 3px solid transparent;
            margin: 0.125rem 0;
        }

        .sidebar-link:hover {
            background: #f7fafc;
            color: #2d3748;
        }

        .sidebar-link.active {
            background: #edf2f7;
            color: #0d9488;
            border-left-color: #0d9488;
        }

        .sidebar-link i {
            width: 1.125rem;
            text-align: center;
            font-size: 0.9375rem;
        }

        /* Sidebar Footer */
        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid #e2e8f0;
            background: #f7fafc;
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            margin-bottom: 0.75rem;
        }

        .admin-avatar {
            width: 2.25rem;
            height: 2.25rem;
            background: linear-gradient(135deg, #0d9488, #0f766e);
            border-radius: 0.375rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .admin-info {
            flex: 1;
            min-width: 0;
        }

        .admin-name {
            font-size: 0.8125rem;
            font-weight: 600;
            color: #1a202c;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .admin-role {
            font-size: 0.6875rem;
            color: #718096;
        }

        .logout-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            padding: 0.625rem;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            color: #e53e3e;
            border-radius: 0.375rem;
            font-size: 0.8125rem;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .logout-btn:hover {
            background: #fff5f5;
            border-color: #fc8181;
        }

        /* Main Content */
        .admin-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: #f5f7fa;
            min-width: 0;
        }

        @media (min-width: 1024px) {
            .admin-main {
                margin-left: 250px;
            }
        }

        /* Top Header */
        .admin-header {
            background: #ffffff;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 50;
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
            width: 2.25rem;
            height: 2.25rem;
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 0.375rem;
            color: #4a5568;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .mobile-menu-btn:hover {
            background: #edf2f7;
        }

        @media (min-width: 1024px) {
            .mobile-menu-btn {
                display: none;
            }
        }

        .page-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: #1a202c;
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
            width: 2.25rem;
            height: 2.25rem;
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 0.375rem;
            color: #4a5568;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .header-btn:hover {
            background: #edf2f7;
            color: #2d3748;
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

        /* Cards */
        .card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
        }

        .card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-header h3 {
            font-size: 1rem;
            font-weight: 600;
            color: #1a202c;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Tables */
        .table-responsive {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.875rem;
        }

        .table thead {
            background: #f7fafc;
        }

        .table th {
            padding: 0.75rem 1rem;
            text-align: left;
            font-weight: 600;
            color: #4a5568;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid #e2e8f0;
        }

        .table td {
            padding: 0.875rem 1rem;
            border-bottom: 1px solid #e2e8f0;
            color: #2d3748;
        }

        .table tbody tr:hover {
            background: #f7fafc;
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            font-size: 0.8125rem;
            font-weight: 500;
            border-radius: 0.375rem;
            border: 1px solid transparent;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .btn-primary {
            background: #0d9488;
            color: white;
        }

        .btn-primary:hover {
            background: #0f766e;
        }

        .btn-outline {
            background: white;
            border-color: #e2e8f0;
            color: #4a5568;
        }

        .btn-outline:hover {
            background: #f7fafc;
            border-color: #cbd5e0;
        }

        .btn-danger {
            background: #e53e3e;
            color: white;
        }

        .btn-danger:hover {
            background: #c53030;
        }

        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
        }

        /* Forms */
        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            font-size: 0.8125rem;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 0.375rem;
        }

        .form-control {
            width: 100%;
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            color: #2d3748;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 0.375rem;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #0d9488;
            box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.1);
        }

        .form-control::placeholder {
            color: #a0aec0;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 80px;
        }

        /* Alerts */
        .alert {
            padding: 0.875rem 1rem;
            border-radius: 0.375rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.875rem;
        }

        .alert-success {
            background: #f0fdf4;
            border: 1px solid #86efac;
            color: #166534;
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fca5a5;
            color: #991b1b;
        }

        /* Grid */
        .grid {
            display: grid;
        }

        .grid-cols-1 {
            grid-template-columns: repeat(1, 1fr);
        }

        @media (min-width: 768px) {
            .grid-cols-2 {
                grid-template-columns: repeat(2, 1fr);
            }

            .md\:grid-cols-2 {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .gap-6 {
            gap: 1.5rem;
        }

        /* Utilities */
        .text-center {
            text-align: center;
        }

        .badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 0.25rem;
            background: #edf2f7;
            color: #4a5568;
        }

        /* Utility classes for backward compatibility */
        .mb-6 {
            margin-bottom: 1.5rem;
        }

        .flex {
            display: flex;
        }

        .justify-between {
            justify-content: space-between;
        }

        .items-center {
            align-items: center;
        }

        .text-2xl {
            font-size: 1.5rem;
        }

        .font-bold {
            font-weight: 700;
        }

        .text-gray-800 {
            color: #1a202c;
        }

        .bg-blue-600 {
            background: #0d9488;
        }

        .text-white {
            color: white;
        }

        .px-6 {
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }

        .py-2 {
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }

        .py-3 {
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
        }

        .py-16 {
            padding-top: 4rem;
            padding-bottom: 4rem;
        }

        .rounded-lg {
            border-radius: 0.5rem;
        }

        .rounded-xl {
            border-radius: 0.75rem;
        }

        .shadow-lg {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .overflow-hidden {
            overflow: hidden;
        }

        .hover\:bg-blue-700:hover {
            background: #0f766e;
        }

        .transition {
            transition: all 0.15s ease;
        }

        .mr-2 {
            margin-right: 0.5rem;
        }

        .mb-4 {
            margin-bottom: 1rem;
        }

        .mb-3 {
            margin-bottom: 0.75rem;
        }

        .mt-4 {
            margin-top: 1rem;
        }

        .text-center {
            text-align: center;
        }

        .text-gray-600 {
            color: #4a5568;
        }

        .text-gray-500 {
            color: #718096;
        }

        .bg-gray-50 {
            background: #f7fafc;
        }

        .p-6 {
            padding: 1.5rem;
        }

        .border-b {
            border-bottom: 1px solid #e2e8f0;
        }

        .w-full {
            width: 100%;
        }

        .space-y-4 > * + * {
            margin-top: 1rem;
        }

        .rounded {
            border-radius: 0.25rem;
        }

        .border {
            border: 1px solid #e2e8f0;
        }

        .grid {
            display: grid;
        }

        .grid-cols-1 {
            grid-template-columns: repeat(1, 1fr);
        }

        .md\:grid-cols-2 {
            grid-template-columns: repeat(1, 1fr);
        }

        @media (min-width: 768px) {
            .md\:grid-cols-2 {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .lg\:grid-cols-3 {
            grid-template-columns: repeat(1, 1fr);
        }

        @media (min-width: 1024px) {
            .lg\:grid-cols-3 {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        .gap-6 {
            gap: 1.5rem;
        }

        .gap-4 {
            gap: 1rem;
        }

        .px-4 {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .bg-white {
            background: white;
        }

        .text-sm {
            font-size: 0.875rem;
        }

        .font-medium {
            font-weight: 500;
        }

        .text-green-600 {
            color: #059669;
        }

        .bg-green-100 {
            background: #d1fae5;
        }

        .bg-orange-100 {
            background: #fed7aa;
        }

        .bg-yellow-100 {
            background: #fef3c7;
        }

        .text-orange-700 {
            color: #c2410c;
        }

        .bg-yellow-500 {
            background: #eab308;
        }

        .bg-purple-100 {
            background: #e9d5ff;
        }

        .text-purple-800 {
            color: #6b21a8;
        }

        .rounded-full {
            border-radius: 9999px;
        }

        .space-x-2 > * + * {
            margin-left: 0.5rem;
        }

        .text-right {
            text-align: right;
        }

        .whitespace-nowrap {
            white-space: nowrap;
        }

        .text-blue-600 {
            color: #0d9488;
        }

        .hover\:text-blue-700:hover {
            color: #0f766e;
        }

        .text-xs {
            font-size: 0.75rem;
        }

        .uppercase {
            text-transform: uppercase;
        }

        .leading-relaxed {
            line-height: 1.625;
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
                    <div class="nav-section-title">Content</div>
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
                    <a href="disclosures.php"
                        class="sidebar-link <?php echo $currentPage === 'disclosures.php' ? 'active' : ''; ?>">
                        <i class="fas fa-file-alt"></i>
                        <span>Disclosures</span>
                    </a>
                    <a href="sports.php"
                        class="sidebar-link <?php echo $currentPage === 'sports.php' ? 'active' : ''; ?>">
                        <i class="fas fa-running"></i>
                        <span>Sports</span>
                    </a>
                    <a href="slc.php"
                        class="sidebar-link <?php echo $currentPage === 'slc.php' ? 'active' : ''; ?>">
                        <i class="fas fa-certificate"></i>
                        <span>School Leaving Certificate</span>
                    </a>
                    <a href="bus-routes.php"
                        class="sidebar-link <?php echo $currentPage === 'bus-routes.php' ? 'active' : ''; ?>">
                        <i class="fas fa-bus"></i>
                        <span>Bus Routes</span>
                    </a>
                    <a href="fee-structure.php"
                        class="sidebar-link <?php echo $currentPage === 'fee-structure.php' ? 'active' : ''; ?>">
                        <i class="fas fa-money-bill-wave"></i>
                        <span>Fee Structure</span>
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
