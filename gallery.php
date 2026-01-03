<?php
require_once 'includes/functions.php';

$pageTitle = 'Gallery';

// Get images
$images = getGalleryImages();

include 'includes/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <div class="breadcrumb">
            <a href="index.php">Home</a>
            <span>/</span>
            <span>Gallery</span>
        </div>
        <h1>Photo <span style="color: #F59E0B;">Gallery</span></h1>
        <p>Capturing memorable moments from our school journey</p>
    </div>
    <div class="wave-bottom">
        <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 64C240 96 480 96 720 64C960 32 1200 32 1440 64V80H0V64Z" fill="#F5F7FB" />
        </svg>
    </div>
</section>

<!-- Gallery Grid -->
<section class="section bg-light">
    <div class="container">
        <?php if (empty($images)): ?>
            <div class="text-center" style="padding: 4rem 0;">
                <div
                    style="width: 5rem; height: 5rem; background: var(--border-light); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                    <i class="fas fa-images" style="font-size: 2rem; color: var(--text-muted);"></i>
                </div>
                <h3 style="margin-bottom: 1rem;">No Images Found</h3>
                <p style="color: var(--text-muted);">Check back later for new photos</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <?php foreach ($images as $image): ?>
                    <div class="card" style="cursor: pointer; overflow: hidden;"
                        onclick="openLightbox('<?php echo clean($image['image']); ?>')">
                        <div style="aspect-ratio: 1; overflow: hidden; position: relative;">
                            <img src="uploads/gallery/<?php echo clean($image['image']); ?>" alt="Gallery Image"
                                style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;">

                            <!-- Hover Overlay -->
                            <div
                                style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(37, 99, 235, 0.9), transparent); opacity: 0; transition: opacity 0.3s ease; display: flex; align-items: center; justify-content: center;">
                                <div
                                    style="width: 4rem; height: 4rem; background: rgba(255,255,255,0.2); backdrop-filter: blur(4px); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-search-plus" style="color: white; font-size: 1.5rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Lightbox Modal -->
<div id="lightboxModal"
    style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.95); z-index: 1000; align-items: center; justify-content: center; padding: 1rem;"
    onclick="closeLightbox()">
    <button onclick="closeLightbox()"
        style="position: absolute; top: 2rem; right: 2rem; background: rgba(255,255,255,0.1); border: none; color: white; width: 3rem; height: 3rem; border-radius: 50%; cursor: pointer; font-size: 1.25rem; display: flex; align-items: center; justify-content: center;">
        <i class="fas fa-times"></i>
    </button>

    <div style="max-width: 90vw; max-height: 90vh;" onclick="event.stopPropagation()">
        <img id="lightboxImage" src="" alt=""
            style="max-width: 100%; max-height: 90vh; border-radius: 0.5rem; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);">
    </div>
</div>

<style>
    .card:hover img {
        transform: scale(1.1);
    }

    .card:hover div[style*="opacity: 0"] {
        opacity: 1 !important;
    }
</style>

<script>
    function openLightbox(image) {
        const modal = document.getElementById('lightboxModal');
        modal.style.display = 'flex';
        document.getElementById('lightboxImage').src = 'uploads/gallery/' + image;
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        document.getElementById('lightboxModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    // Close lightbox on Escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeLightbox();
        }
    });
</script>

<?php include 'includes/footer.php'; ?>