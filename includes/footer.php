</main>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <!-- About Section -->
            <div class="footer-about">
                <div class="footer-brand">
                    <img src="assets/images/logo.png"
                        alt="<?php echo clean(getSiteSetting('school_name', 'Lotus Valley')); ?>"
                        style="background: white; padding: 0.5rem; border-radius: 0.5rem;">
                    <h4 style="margin-top: 1rem;">Lotus Valley International School</h4>
                </div>
                <p>An educational institution with a fresh vision, modern outlook, and a strong academic foundation.
                    (Under the aegis of Lotus Valley Social & Educational Trust)
                </p>
                <div class="flex items-center gap-4 mt-4" style="margin-top: 1rem;">
                    <span class="badge badge-secondary">
                        <i class="fas fa-award"></i> CBSE Affiliated
                    </span>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h4>Quick Links</h4>
                <ul class="footer-links">
                    <li><a href="index.php"><i class="fas fa-chevron-right"></i> Home</a></li>
                    <li><a href="about.php"><i class="fas fa-chevron-right"></i> About Us</a></li>
                    <li><a href="toppers.php"><i class="fas fa-chevron-right"></i> Our Achievers</a></li>
                    <li><a href="staff.php"><i class="fas fa-chevron-right"></i> Faculty</a></li>
                    <li><a href="gallery.php"><i class="fas fa-chevron-right"></i> Gallery</a></li>
                    <li><a href="admission.php"><i class="fas fa-chevron-right"></i> Admissions</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div>
                <h4>Contact Us</h4>
                <ul class="footer-contact">
                    <li>
                        <div class="icon"><i class="fas fa-phone"></i></div>
                        <div class="text">
                            <a href="tel:9896421785">9896421785</a><br>
                            <a href="tel:8950081785">8950081785</a>
                        </div>
                    </li>
                    <li>
                        <div class="icon"><i class="fas fa-envelope"></i></div>
                        <div class="text">
                            <a href="mailto:info@lotusvalley.edu">info@lotusvalley.edu</a>
                        </div>
                    </li>
                    <li>
                        <div class="icon"><i class="fas fa-clock"></i></div>
                        <div class="text">
                            Mon - Sat: 8:00 AM - 3:00 PM
                        </div>
                    </li>
                    <li>
                        <div class="icon"><i class="fas fa-map-marker-alt"></i></div>
                        <div class="text">
                            <?php echo clean($settings['school_address'] ?? 'Choura Campus'); ?>
                        </div>
                    </li>
                    <!-- Additional Address Info from Content -->
                    <li>
                        <div class="icon"><i class="fas fa-map"></i></div>
                        <div class="text">
                            Under the aegis of Lotus Valley Social & Educational Trust
                        </div>
                    </li>
                </ul>
            </div>

            <!-- Connect -->
            <div>
                <h4>Connect With Us</h4>
                <div class="social-links" style="margin-bottom: 1.5rem;">
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

                <!-- Quick Contact CTA -->
                <div
                    style="background: linear-gradient(135deg, #0D9488, #0F766E); padding: 1.5rem; border-radius: 1rem;">
                    <h5 style="color: white; font-size: 1.125rem; margin-bottom: 0.75rem;">
                        <i class="fas fa-lightbulb" style="margin-right: 0.5rem;"></i>Have Questions?
                    </h5>
                    <p style="color: rgba(255,255,255,0.8); font-size: 0.875rem; margin-bottom: 1rem;">
                        Our team is ready to help with all your queries.
                    </p>
                    <a href="contact.php" class="btn btn-secondary btn-pill"
                        style="font-size: 0.875rem; padding: 0.5rem 1rem;">
                        Get in Touch <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.1);">
                    <a href="admin/login.php" style="font-size: 0.75rem; color: rgba(255,255,255,0.5);">
                        <i class="fas fa-lock" style="margin-right: 0.375rem;"></i>Admin Access
                    </a>
                </div>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?>
                <strong><?php echo clean(getSiteSetting('school_name', 'Lotus Valley')); ?></strong>. All rights
                reserved.
            </p>
            <div class="footer-bottom-links">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>

<!-- Scroll to Top Button -->
<button class="scroll-top" id="scrollTopBtn" onclick="scrollToTop()" aria-label="Scroll to top">
    <i class="fas fa-arrow-up"></i>
</button>

<script>
    // Scroll to top functionality
    window.onscroll = function () {
        const btn = document.getElementById('scrollTopBtn');
        if (document.body.scrollTop > 400 || document.documentElement.scrollTop > 400) {
            btn.classList.add('visible');
        } else {
            btn.classList.remove('visible');
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