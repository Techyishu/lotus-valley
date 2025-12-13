    <!-- Footer -->
    <footer class="bg-navy-dark text-gray-300 mt-0">
        <!-- Top Wave -->
        <div class="bg-white">
            <svg viewBox="0 0 1440 60" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full">
                <path d="M0 60L60 52.5C120 45 240 30 360 22.5C480 15 600 15 720 20C840 25 960 35 1080 37.5C1200 40 1320 35 1380 32.5L1440 30V60H0Z" fill="#172554"/>
            </svg>
        </div>
        
        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- About Section -->
                <div>
                    <div class="mb-6">
                        <img src="assets/images/logo.png" alt="<?php echo clean(getSiteSetting('school_name', 'Anthem International School')); ?>" class="h-20 w-auto object-contain mb-4 bg-white p-2 rounded-lg">
                        <p class="text-gold italic text-sm mb-2">"Prosperity with Purity"</p>
                    </div>
                    <p class="text-sm leading-relaxed text-gray-400">
                        <?php echo clean(getSiteSetting('about_text', 'Committed to excellence in education and holistic development of students.')); ?>
                    </p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-white text-lg font-bold mb-6 flex items-center">
                        <span class="w-8 h-1 bg-gold mr-3"></span>Quick Links
                    </h3>
                    <ul class="space-y-3 text-sm">
                        <li>
                            <a href="index.php" class="hover:text-gold transition flex items-center group">
                                <i class="fas fa-chevron-right text-gold mr-2 text-xs group-hover:translate-x-1 transition-transform"></i>Home
                            </a>
                        </li>
                        <li>
                            <a href="about.php" class="hover:text-gold transition flex items-center group">
                                <i class="fas fa-chevron-right text-gold mr-2 text-xs group-hover:translate-x-1 transition-transform"></i>About Us
                            </a>
                        </li>
                        <li>
                            <a href="toppers.php" class="hover:text-gold transition flex items-center group">
                                <i class="fas fa-chevron-right text-gold mr-2 text-xs group-hover:translate-x-1 transition-transform"></i>Our Toppers
                            </a>
                        </li>
                        <li>
                            <a href="staff.php" class="hover:text-gold transition flex items-center group">
                                <i class="fas fa-chevron-right text-gold mr-2 text-xs group-hover:translate-x-1 transition-transform"></i>Faculty
                            </a>
                        </li>
                        <li>
                            <a href="gallery.php" class="hover:text-gold transition flex items-center group">
                                <i class="fas fa-chevron-right text-gold mr-2 text-xs group-hover:translate-x-1 transition-transform"></i>Gallery
                            </a>
                        </li>
                        <li>
                            <a href="admission.php" class="hover:text-gold transition flex items-center group">
                                <i class="fas fa-chevron-right text-gold mr-2 text-xs group-hover:translate-x-1 transition-transform"></i>Admission
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h3 class="text-white text-lg font-bold mb-6 flex items-center">
                        <span class="w-8 h-1 bg-gold mr-3"></span>Contact Us
                    </h3>
                    <ul class="space-y-4 text-sm">
                        <li class="flex items-center group">
                            <div class="w-10 h-10 bg-maroon bg-opacity-20 rounded-lg flex items-center justify-center mr-3 flex-shrink-0 group-hover:bg-maroon transition">
                                <i class="fas fa-phone text-gold"></i>
                            </div>
                            <a href="tel:9896421785" class="text-gray-400 hover:text-gold transition">
                                9896421785 / 8950081785
                            </a>
                        </li>
                        <li class="flex items-center group">
                            <div class="w-10 h-10 bg-maroon bg-opacity-20 rounded-lg flex items-center justify-center mr-3 flex-shrink-0 group-hover:bg-maroon transition">
                                <i class="fas fa-envelope text-gold"></i>
                            </div>
                            <a href="mailto:anthemschool55@gmail.com" class="text-gray-400 hover:text-gold transition">
                                anthemschool55@gmail.com
                            </a>
                        </li>
                        <li class="flex items-center group">
                            <div class="w-10 h-10 bg-maroon bg-opacity-20 rounded-lg flex items-center justify-center mr-3 flex-shrink-0 group-hover:bg-maroon transition">
                                <i class="fas fa-clock text-gold"></i>
                            </div>
                            <span class="text-gray-400">Mon - Sat: 8:00 AM - 3:00 PM</span>
                        </li>
                    </ul>
                </div>

                <!-- Social & Newsletter -->
                <div>
                    <h3 class="text-white text-lg font-bold mb-6 flex items-center">
                        <span class="w-8 h-1 bg-gold mr-3"></span>Connect With Us
                    </h3>
                    <div class="flex space-x-3 mb-6">
                        <?php if (getSiteSetting('facebook_url')): ?>
                            <a href="<?php echo clean(getSiteSetting('facebook_url')); ?>" target="_blank" 
                               class="w-11 h-11 bg-navy-dark border border-gold border-opacity-30 hover:bg-gold hover:text-navy-dark rounded-lg flex items-center justify-center transition" aria-label="Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                        <?php endif; ?>
                        <?php if (getSiteSetting('twitter_url')): ?>
                            <a href="<?php echo clean(getSiteSetting('twitter_url')); ?>" target="_blank" 
                               class="w-11 h-11 bg-navy-dark border border-gold border-opacity-30 hover:bg-gold hover:text-navy-dark rounded-lg flex items-center justify-center transition" aria-label="Twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                        <?php endif; ?>
                        <?php if (getSiteSetting('instagram_url')): ?>
                            <a href="<?php echo clean(getSiteSetting('instagram_url')); ?>" target="_blank" 
                               class="w-11 h-11 bg-navy-dark border border-gold border-opacity-30 hover:bg-gold hover:text-navy-dark rounded-lg flex items-center justify-center transition" aria-label="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                        <?php endif; ?>
                        <?php if (getSiteSetting('youtube_url')): ?>
                            <a href="<?php echo clean(getSiteSetting('youtube_url')); ?>" target="_blank" 
                               class="w-11 h-11 bg-navy-dark border border-gold border-opacity-30 hover:bg-gold hover:text-navy-dark rounded-lg flex items-center justify-center transition" aria-label="YouTube">
                                <i class="fab fa-youtube"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Quick Contact CTA -->
                    <div class="bg-maroon bg-opacity-20 rounded-xl p-4 border border-maroon border-opacity-30">
                        <h4 class="text-white font-semibold mb-2">Have Questions?</h4>
                        <p class="text-gray-400 text-sm mb-3">We're here to help you with admissions and queries.</p>
                        <a href="contact.php" class="inline-flex items-center text-gold font-semibold text-sm hover:text-white transition">
                            Contact Us <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                    
                    <div class="mt-6">
                        <a href="admin/login.php" class="text-xs text-gray-500 hover:text-gold transition">
                            <i class="fas fa-lock mr-1"></i>Admin Login
                        </a>
                    </div>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="border-t border-gray-800 mt-12 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center text-sm">
                    <p class="text-gray-400">&copy; <?php echo date('Y'); ?> <?php echo clean(getSiteSetting('school_name', 'Anthem International School')); ?>. All rights reserved.</p>
                    <p class="mt-2 md:mt-0 text-gray-500">
                        Designed with <i class="fas fa-heart text-maroon"></i> for excellence in education
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scroll to Top Button -->
    <button onclick="scrollToTop()" id="scrollTopBtn" 
            class="hidden fixed bottom-8 right-8 bg-gradient-to-r from-maroon to-red-700 text-white w-12 h-12 rounded-full shadow-2xl hover:from-red-700 hover:to-maroon transition z-40" aria-label="Scroll to top">
        <i class="fas fa-arrow-up"></i>
    </button>

    <script>
        // Scroll to top functionality
        window.onscroll = function() {
            const btn = document.getElementById('scrollTopBtn');
            if (document.body.scrollTop > 300 || document.documentElement.scrollTop > 300) {
                btn.classList.remove('hidden');
            } else {
                btn.classList.add('hidden');
            }
        };

        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }
    </script>
</body>
</html>
