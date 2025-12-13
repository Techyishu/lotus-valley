<?php
// Prevent caching - send no-cache headers
header("Cache-Control: no-cache, no-store, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($pageTitle)) {
    $pageTitle = 'Welcome';
}
$schoolName = getSiteSetting('school_name', 'Anthem International School');
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
    <meta name="description" content="<?php echo clean(getSiteSetting('about_text', 'Anthem International School - Prosperity with Purity - Excellence in Education')); ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy-blue: #1e3a8a;
            --navy-dark: #172554;
            --maroon-red: #b91c1c;
            --gold: #d4af37;
            --gold-light: #f5d78e;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
        }
        
        .bg-navy { background-color: var(--navy-blue); }
        .bg-navy-dark { background-color: var(--navy-dark); }
        .bg-maroon { background-color: var(--maroon-red); }
        .bg-gold { background-color: var(--gold); }
        .text-navy { color: var(--navy-blue); }
        .text-maroon { color: var(--maroon-red); }
        .text-gold { color: var(--gold); }
        .border-gold { border-color: var(--gold); }
        
        .gradient-bg {
            background: linear-gradient(135deg, var(--navy-dark) 0%, var(--navy-blue) 50%, #2563eb 100%);
        }
        .gradient-text {
            background: linear-gradient(135deg, var(--navy-blue) 0%, var(--maroon-red) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .hover-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: var(--navy-blue);
            border-radius: 5px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: var(--navy-dark);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Top Bar -->
    <div class="bg-navy-dark text-white py-2 hidden md:block">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center text-sm">
                <div class="flex items-center space-x-6">
                    <span class="flex items-center text-gold font-medium">
                        <i class="fas fa-award mr-2"></i>CBSE Affiliated
                    </span>
                    <a href="tel:9896421785" class="hover:text-gold transition">
                        <i class="fas fa-phone mr-2"></i>9896421785 / 8950081785
                    </a>
                    <a href="mailto:anthemschool55@gmail.com" class="hover:text-gold transition">
                        <i class="fas fa-envelope mr-2"></i>anthemschool55@gmail.com
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gold italic text-xs">"Prosperity with Purity"</span>
                    <div class="w-px h-4 bg-white bg-opacity-30"></div>
                    <?php if (getSiteSetting('facebook_url')): ?>
                        <a href="<?php echo clean(getSiteSetting('facebook_url')); ?>" target="_blank" class="hover:text-gold transition" aria-label="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (getSiteSetting('twitter_url')): ?>
                        <a href="<?php echo clean(getSiteSetting('twitter_url')); ?>" target="_blank" class="hover:text-gold transition" aria-label="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (getSiteSetting('instagram_url')): ?>
                        <a href="<?php echo clean(getSiteSetting('instagram_url')); ?>" target="_blank" class="hover:text-gold transition" aria-label="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (getSiteSetting('youtube_url')): ?>
                        <a href="<?php echo clean(getSiteSetting('youtube_url')); ?>" target="_blank" class="hover:text-gold transition" aria-label="YouTube">
                            <i class="fab fa-youtube"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-50 border-b-2 border-gold border-opacity-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-3">
                <!-- Logo -->
                <a href="index.php" class="flex items-center" aria-label="Home">
                    <img src="assets/images/logo.png" alt="<?php echo clean($schoolName); ?>" class="h-14 md:h-16 w-auto object-contain">
                </a>

                <!-- Desktop Menu -->
                <div class="hidden lg:flex items-center space-x-8">
                    <a href="index.php" class="<?php echo $currentPage === 'index.php' ? 'text-maroon font-bold' : 'text-gray-700 hover:text-navy font-medium'; ?> transition">
                        Home
                    </a>
                    <a href="about.php" class="<?php echo $currentPage === 'about.php' ? 'text-maroon font-bold' : 'text-gray-700 hover:text-navy font-medium'; ?> transition">
                        About
                    </a>
                    <a href="toppers.php" class="<?php echo $currentPage === 'toppers.php' ? 'text-maroon font-bold' : 'text-gray-700 hover:text-navy font-medium'; ?> transition">
                        Toppers
                    </a>
                    <a href="staff.php" class="<?php echo $currentPage === 'staff.php' ? 'text-maroon font-bold' : 'text-gray-700 hover:text-navy font-medium'; ?> transition">
                        Staff
                    </a>
                    <a href="gallery.php" class="<?php echo $currentPage === 'gallery.php' ? 'text-maroon font-bold' : 'text-gray-700 hover:text-navy font-medium'; ?> transition">
                        Gallery
                    </a>
                    <a href="contact.php" class="<?php echo $currentPage === 'contact.php' ? 'text-maroon font-bold' : 'text-gray-700 hover:text-navy font-medium'; ?> transition">
                        Contact
                    </a>
                    
                    <!-- Search Icon -->
                    <button onclick="toggleSearch()" class="text-gray-700 hover:text-navy transition" aria-label="Search">
                        <i class="fas fa-search"></i>
                    </button>
                    
                    <!-- Admission Button -->
                    <a href="admission.php" class="bg-gradient-to-r from-maroon to-red-700 px-6 py-2.5 rounded-full hover:from-red-700 hover:to-maroon transition shadow-lg font-semibold" style="color: var(--maroon-red);">
                        <i class="fas fa-graduation-cap mr-2"></i>Admission
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <button onclick="toggleMobileMenu()" class="lg:hidden text-navy hover:text-maroon" aria-label="Toggle menu">
                    <i class="fas fa-bars text-2xl" id="menuIcon"></i>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div id="mobileMenu" class="hidden lg:hidden pb-4">
                <div class="flex flex-col space-y-1 border-t border-gray-200 pt-4">
                    <a href="index.php" class="<?php echo $currentPage === 'index.php' ? 'text-maroon font-bold bg-red-50' : 'text-gray-700'; ?> py-3 px-4 rounded-lg">
                        <i class="fas fa-home mr-3 w-5"></i>Home
                    </a>
                    <a href="about.php" class="<?php echo $currentPage === 'about.php' ? 'text-maroon font-bold bg-red-50' : 'text-gray-700'; ?> py-3 px-4 rounded-lg">
                        <i class="fas fa-info-circle mr-3 w-5"></i>About
                    </a>
                    <a href="toppers.php" class="<?php echo $currentPage === 'toppers.php' ? 'text-maroon font-bold bg-red-50' : 'text-gray-700'; ?> py-3 px-4 rounded-lg">
                        <i class="fas fa-trophy mr-3 w-5"></i>Toppers
                    </a>
                    <a href="staff.php" class="<?php echo $currentPage === 'staff.php' ? 'text-maroon font-bold bg-red-50' : 'text-gray-700'; ?> py-3 px-4 rounded-lg">
                        <i class="fas fa-users mr-3 w-5"></i>Staff
                    </a>
                    <a href="gallery.php" class="<?php echo $currentPage === 'gallery.php' ? 'text-maroon font-bold bg-red-50' : 'text-gray-700'; ?> py-3 px-4 rounded-lg">
                        <i class="fas fa-images mr-3 w-5"></i>Gallery
                    </a>
                    <a href="contact.php" class="<?php echo $currentPage === 'contact.php' ? 'text-maroon font-bold bg-red-50' : 'text-gray-700'; ?> py-3 px-4 rounded-lg">
                        <i class="fas fa-envelope mr-3 w-5"></i>Contact
                    </a>
                    <a href="admission.php" class="bg-gradient-to-r from-maroon to-red-700 text-white px-6 py-3 rounded-full text-center font-semibold mt-4">
                        <i class="fas fa-graduation-cap mr-2"></i>Apply for Admission
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Search Modal -->
    <div id="searchModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-start justify-center pt-20">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-4 overflow-hidden">
            <div class="bg-gradient-to-r from-navy to-blue-600 p-6 text-white">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold"><i class="fas fa-search mr-3"></i>Search</h3>
                    <button onclick="toggleSearch()" class="text-white hover:text-gold transition" aria-label="Close search">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <input type="text" 
                       id="searchInput" 
                       placeholder="Search for toppers, staff, announcements..."
                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-navy focus:border-transparent"
                       onkeyup="performSearch()"
                       aria-label="Search input">
                <div id="searchResults" class="mt-4 max-h-96 overflow-y-auto"></div>
            </div>
        </div>
    </div>

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            const icon = document.getElementById('menuIcon');
            menu.classList.toggle('hidden');
            
            if (menu.classList.contains('hidden')) {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            } else {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            }
        }

        function toggleSearch() {
            const modal = document.getElementById('searchModal');
            const input = document.getElementById('searchInput');
            modal.classList.toggle('hidden');
            if (!modal.classList.contains('hidden')) {
                input.focus();
            } else {
                input.value = '';
                document.getElementById('searchResults').innerHTML = '';
            }
        }

        let searchTimeout;
        function performSearch() {
            clearTimeout(searchTimeout);
            const query = document.getElementById('searchInput').value;
            
            if (query.length < 2) {
                document.getElementById('searchResults').innerHTML = '';
                return;
            }
            
            searchTimeout = setTimeout(() => {
                fetch('search.php?q=' + encodeURIComponent(query))
                    .then(response => response.json())
                    .then(data => {
                        displaySearchResults(data);
                    })
                    .catch(error => {
                        console.error('Search error:', error);
                    });
            }, 300);
        }

        function displaySearchResults(results) {
            const container = document.getElementById('searchResults');
            
            if (results.length === 0) {
                container.innerHTML = '<p class="text-gray-500 text-center py-8"><i class="fas fa-search text-4xl mb-4 block text-gray-300"></i>No results found</p>';
                return;
            }
            
            let html = '<div class="space-y-2">';
            results.forEach(result => {
                const icon = result.type === 'topper' ? 'fa-trophy text-gold' : 
                           result.type === 'staff' ? 'fa-user-tie text-navy' : 'fa-bullhorn text-maroon';
                const link = result.type === 'topper' ? 'toppers.php' :
                           result.type === 'staff' ? 'staff.php' : 'index.php#announcements';
                
                html += `
                    <a href="${link}" class="block p-4 hover:bg-gray-50 rounded-xl transition border border-gray-100">
                        <div class="flex items-start">
                            <i class="fas ${icon} mt-1 mr-4 text-lg"></i>
                            <div>
                                <h4 class="font-semibold text-gray-800">${result.title}</h4>
                                <p class="text-sm text-gray-600">${result.description}</p>
                            </div>
                        </div>
                    </a>
                `;
            });
            html += '</div>';
            
            container.innerHTML = html;
        }

        // Close search on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = document.getElementById('searchModal');
                if (!modal.classList.contains('hidden')) {
                    toggleSearch();
                }
            }
        });
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', function(e) {
            const menu = document.getElementById('mobileMenu');
            const menuButton = document.querySelector('[onclick="toggleMobileMenu()"]');
            
            if (!menu.contains(e.target) && !menuButton.contains(e.target) && !menu.classList.contains('hidden')) {
                toggleMobileMenu();
            }
        });
    </script>
