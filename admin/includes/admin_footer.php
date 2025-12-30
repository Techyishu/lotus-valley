</main>
</div>
</div>

<script>
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function (e) {
        const sidebar = document.getElementById('adminSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const menuBtn = document.querySelector('.mobile-menu-btn');

        if (sidebar && overlay && window.innerWidth < 1024) {
            if (!sidebar.contains(e.target) && !menuBtn.contains(e.target) && sidebar.classList.contains('mobile-open')) {
                toggleMobileSidebar();
            }
        }
    });

    // Handle window resize
    window.addEventListener('resize', function () {
        const sidebar = document.getElementById('adminSidebar');
        const overlay = document.getElementById('sidebarOverlay');

        if (window.innerWidth >= 1024) {
            sidebar.classList.remove('mobile-open');
            overlay.classList.remove('active');
        }
    });
</script>
</body>

</html>