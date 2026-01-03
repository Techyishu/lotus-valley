<?php
// Prevent caching - send no-cache headers
header("Cache-Control: no-cache, no-store, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($pageTitle)) {
    $pageTitle = 'Welcome';
}
$schoolName = getSiteSetting('school_name', 'Lotus Valley');
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title><?php echo clean($pageTitle); ?> - <?php echo clean($schoolName); ?></title>
    <meta name="description"
        content="<?php echo clean(getSiteSetting('about_text', 'Lotus Valley - Nurturing Future-Ready Students')); ?>">

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <!-- Top Bar -->
    <div class="top-bar hidden md:block">
        <div class="container">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-6">
                    <span class="flex items-center gap-2">
                        <i class="fas fa-award"></i>
                        <span>CBSE Affiliated</span>
                    </span>
                    <a href="tel:9896421785" class="flex items-center gap-2">
                        <i class="fas fa-phone-alt"></i>
                        <span>9896421785 / 8950081785</span>
                    </a>
                    <a href="mailto:info@lotusvalley.edu" class="flex items-center gap-2">
                        <i class="fas fa-envelope"></i>
                        <span>info@lotusvalley.edu</span>
                    </a>
                </div>
                <div class="flex items-center gap-4">
                    <?php if (getSiteSetting('facebook_url')): ?>
                        <a href="<?php echo clean(getSiteSetting('facebook_url')); ?>" target="_blank"
                            aria-label="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (getSiteSetting('instagram_url')): ?>
                        <a href="<?php echo clean(getSiteSetting('instagram_url')); ?>" target="_blank"
                            aria-label="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (getSiteSetting('youtube_url')): ?>
                        <a href="<?php echo clean(getSiteSetting('youtube_url')); ?>" target="_blank" aria-label="YouTube">
                            <i class="fab fa-youtube"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation -->
    <nav class="navbar">
        <div class="container">
            <div class="navbar-inner">
                <!-- Logo -->
                <a href="index.php" class="navbar-brand" aria-label="Home">
                    <img src="assets/images/logo.png" alt="<?php echo clean($schoolName); ?>">
                    <div class="navbar-brand-text">
                        <span class="navbar-brand-name">Lotus Valley</span>
                        <span class="navbar-brand-tagline">International School</span>
                    </div>
                </a>

                <!-- Desktop Menu -->
                <ul class="nav-menu">
                    <li>
                        <a href="index.php"
                            class="nav-link <?php echo $currentPage === 'index.php' ? 'active' : ''; ?>">
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="about.php"
                            class="nav-link <?php echo $currentPage === 'about.php' ? 'active' : ''; ?>">
                            About
                        </a>
                    </li>
                    <li>
                        <a href="toppers.php"
                            class="nav-link <?php echo $currentPage === 'toppers.php' ? 'active' : ''; ?>">
                            Academics
                        </a>
                    </li>
                    <li>
                        <a href="admission.php"
                            class="nav-link <?php echo $currentPage === 'admission.php' ? 'active' : ''; ?>">
                            Admissions
                        </a>
                    </li>
                    <li>
                        <a href="gallery.php"
                            class="nav-link <?php echo $currentPage === 'gallery.php' ? 'active' : ''; ?>">
                            Gallery
                        </a>
                    </li>
                    <li class="nav-item-dropdown">
                        <a href="javascript:void(0)"
                            class="nav-link dropdown-toggle <?php echo in_array($currentPage, ['disclosure.php', 'sports.php', 'slc.php', 'bus-routes.php', 'fee-structure.php']) ? 'active' : ''; ?>">
                            More Info <i class="fas fa-chevron-down" style="font-size: 0.7em; margin-left: 0.25rem;"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="disclosure.php"
                                    class="<?php echo $currentPage === 'disclosure.php' ? 'active' : ''; ?>">
                                    <i class="fas fa-file-alt"></i> Disclosure
                                </a>
                            </li>
                            <li>
                                <a href="sports.php"
                                    class="<?php echo $currentPage === 'sports.php' ? 'active' : ''; ?>">
                                    <i class="fas fa-running"></i> Sports
                                </a>
                            </li>
                            <li>
                                <a href="slc.php"
                                    class="<?php echo $currentPage === 'slc.php' ? 'active' : ''; ?>">
                                    <i class="fas fa-certificate"></i> School Leaving Certificate
                                </a>
                            </li>
                            <li>
                                <a href="bus-routes.php"
                                    class="<?php echo $currentPage === 'bus-routes.php' ? 'active' : ''; ?>">
                                    <i class="fas fa-bus"></i> Bus Routes
                                </a>
                            </li>
                            <li>
                                <a href="fee-structure.php"
                                    class="<?php echo $currentPage === 'fee-structure.php' ? 'active' : ''; ?>">
                                    <i class="fas fa-money-bill-wave"></i> Fee Structure
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="contact.php"
                            class="nav-link <?php echo $currentPage === 'contact.php' ? 'active' : ''; ?>">
                            Contact
                        </a>
                    </li>
                    <li>
                        <a href="admission.php" class="btn btn-primary btn-pill">
                            Apply Now
                        </a>
                    </li>
                </ul>

                <!-- Mobile Menu Button -->
                <button class="nav-toggle" onclick="toggleMobileMenu()" aria-label="Toggle menu">
                    <i class="fas fa-bars" id="menuIcon"></i>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div id="mobileMenu" class="mobile-menu">
                <a href="index.php" class="<?php echo $currentPage === 'index.php' ? 'active' : ''; ?>">
                    <i class="fas fa-home"></i> Home
                </a>
                <a href="about.php" class="<?php echo $currentPage === 'about.php' ? 'active' : ''; ?>">
                    <i class="fas fa-info-circle"></i> About
                </a>
                <a href="toppers.php" class="<?php echo $currentPage === 'toppers.php' ? 'active' : ''; ?>">
                    <i class="fas fa-trophy"></i> Academics
                </a>
                <a href="admission.php" class="<?php echo $currentPage === 'admission.php' ? 'active' : ''; ?>">
                    <i class="fas fa-file-alt"></i> Admissions
                </a>
                <a href="gallery.php" class="<?php echo $currentPage === 'gallery.php' ? 'active' : ''; ?>">
                    <i class="fas fa-images"></i> Gallery
                </a>
                <a href="disclosure.php" class="<?php echo $currentPage === 'disclosure.php' ? 'active' : ''; ?>">
                    <i class="fas fa-file-alt"></i> Disclosure
                </a>
                <a href="sports.php" class="<?php echo $currentPage === 'sports.php' ? 'active' : ''; ?>">
                    <i class="fas fa-running"></i> Sports
                </a>
                <a href="slc.php" class="<?php echo $currentPage === 'slc.php' ? 'active' : ''; ?>">
                    <i class="fas fa-certificate"></i> School Leaving Certificate
                </a>
                <a href="bus-routes.php" class="<?php echo $currentPage === 'bus-routes.php' ? 'active' : ''; ?>">
                    <i class="fas fa-bus"></i> Bus Routes
                </a>
                <a href="fee-structure.php" class="<?php echo $currentPage === 'fee-structure.php' ? 'active' : ''; ?>">
                    <i class="fas fa-money-bill-wave"></i> Fee Structure
                </a>
                <a href="contact.php" class="<?php echo $currentPage === 'contact.php' ? 'active' : ''; ?>">
                    <i class="fas fa-envelope"></i> Contact
                </a>
                <a href="admission.php" class="btn btn-primary w-full mt-4" style="justify-content: center;">
                    <i class="fas fa-paper-plane"></i> Apply Now
                </a>
            </div>
        </div>
    </nav>

    <main>

        <script>
            function toggleMobileMenu() {
                const menu = document.getElementById('mobileMenu');
                const icon = document.getElementById('menuIcon');
                menu.classList.toggle('active');

                if (menu.classList.contains('active')) {
                    icon.classList.remove('fa-bars');
                    icon.classList.add('fa-times');
                } else {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            }

            // Mobile dropdown toggle
            document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    if (window.innerWidth < 1024) {
                        e.preventDefault();
                        e.stopPropagation();

                        const dropdown = this.nextElementSibling;
                        const isOpen = dropdown.style.display === 'block';

                        // Close all other dropdowns
                        document.querySelectorAll('.dropdown-menu').forEach(menu => {
                            menu.style.display = 'none';
                        });

                        // Toggle current dropdown
                        dropdown.style.display = isOpen ? 'none' : 'block';
                    }
                });
            });

            // Close mobile menu when clicking outside
            document.addEventListener('click', function (e) {
                const menu = document.getElementById('mobileMenu');
                const toggle = document.querySelector('.nav-toggle');
                if (!menu.contains(e.target) && !toggle.contains(e.target) && menu.classList.contains('active')) {
                    toggleMobileMenu();
                }
            });
        </script>