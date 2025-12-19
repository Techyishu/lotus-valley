<?php
require_once 'includes/functions.php';

$pageTitle = 'Gallery';

// Get images
$images = getGalleryImages();

include 'includes/header.php';
?>

<!-- Page Header -->
<section class="bg-gradient-to-r from-pink-600 to-purple-600 text-white py-16">
    <div class="container mx-auto px-4">
        <nav class="text-sm mb-4">
            <a href="index.php" class="hover:text-pink-200">Home</a>
            <span class="mx-2">/</span>
            <span>Gallery</span>
        </nav>
        <h1 class="text-4xl md:text-5xl font-bold">Photo Gallery</h1>
        <p class="text-xl text-pink-100 mt-4">Capturing memorable moments from our school life</p>
    </div>
</section>



<!-- Gallery Grid -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <?php if (empty($images)): ?>
            <div class="text-center py-16">
                <i class="fas fa-images text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-2xl font-bold text-gray-600 mb-2">No Images Found</h3>
                <p class="text-gray-500">Check back later for new photos</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <?php foreach ($images as $image): ?>
                    <div class="group relative overflow-hidden rounded-xl aspect-square bg-gray-200 cursor-pointer hover-lift"
                        onclick="openLightbox('<?php echo clean($image['image']); ?>')">
                        <img src="uploads/gallery/<?php echo clean($image['image']); ?>" alt="Gallery Image"
                            class="w-full h-full object-cover transition duration-300 group-hover:scale-110">

                        <!-- Overlay -->
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent opacity-0 group-hover:opacity-100 transition duration-300">
                            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                                <div
                                    class="w-16 h-16 bg-white bg-opacity-30 backdrop-blur rounded-full flex items-center justify-center">
                                    <i class="fas fa-search-plus text-white text-2xl"></i>
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
<div id="lightboxModal" class="hidden fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center p-4"
    onclick="closeLightbox()">
    <button onclick="closeLightbox()" class="absolute top-4 right-4 text-white text-4xl hover:text-gray-300 z-10">
        <i class="fas fa-times"></i>
    </button>

    <div class="max-w-6xl w-full" onclick="event.stopPropagation()">
        <img id="lightboxImage" src="" alt="" class="w-full h-auto rounded-lg shadow-2xl">
    </div>
</div>

<script>
    function openLightbox(image) {
        document.getElementById('lightboxModal').classList.remove('hidden');
        document.getElementById('lightboxImage').src = 'uploads/gallery/' + image;
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        document.getElementById('lightboxModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Close on Escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeLightbox();
        }
    });
</script>

<?php include 'includes/footer.php'; ?>