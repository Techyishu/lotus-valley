<?php
$pageTitle = 'Manage Gallery';
require_once 'includes/admin_header.php';
global $pdo;

// Handle upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload']) && verifyCSRFToken($_POST['csrf_token'])) {
    if (isset($_FILES['images'])) {
        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
                $upload = uploadFile([
                    'tmp_name' => $_FILES['images']['tmp_name'][$key],
                    'name' => $_FILES['images']['name'][$key],
                    'size' => $_FILES['images']['size'][$key],
                    'error' => $_FILES['images']['error'][$key]
                ], '../uploads/gallery');

                if ($upload['success']) {
                    $stmt = $pdo->prepare("INSERT INTO gallery (image) VALUES (?)");
                    $stmt->execute([$upload['filename']]);
                }
            }
        }
        echo "<script>showToast('Images uploaded successfully', 'success');</script>";
    }
}

// Handle delete
if (isset($_GET['delete']) && isset($_GET['token']) && verifyCSRFToken($_GET['token'])) {
    $id = (int) $_GET['delete'];
    $stmt = $pdo->prepare("SELECT image FROM gallery WHERE id = ?");
    $stmt->execute([$id]);
    $img = $stmt->fetch();
    if ($img) {
        deleteFile('../uploads/gallery/' . $img['image']);
        $stmt = $pdo->prepare("DELETE FROM gallery WHERE id = ?");
        $stmt->execute([$id]);
        echo "<script>showToast('Image deleted', 'success');</script>";
    }
}

$stmt = $pdo->query("SELECT * FROM gallery ORDER BY uploaded_at DESC");
$images = $stmt->fetchAll();
?>

<div class="card">

    <div class="card-header border-b border-gray-100 flex justify-between items-center">
        <h3><i class="fas fa-images"></i> Gallery Images</h3>
        <button onclick="document.getElementById('uploadModal').classList.remove('hidden')" class="btn btn-primary">
            <i class="fas fa-plus"></i> Upload Images
        </button>
    </div>

    <div class="card-body">
        <?php if (empty($images)): ?>
            <div class="text-center py-16">
                <div
                    style="width: 80px; height: 80px; background: #fce4ec; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                    <i class="fas fa-images text-pink-600 text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">No Images Found</h3>
                <p class="text-gray-500 mb-6">Start by uploading photos to your gallery.</p>
                <button onclick="document.getElementById('uploadModal').classList.remove('hidden')" class="btn btn-primary">
                    Upload First Image
                </button>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
                <?php foreach ($images as $image): ?>
                    <div class="relative group rounded-lg overflow-hidden shadow-sm border border-gray-200">
                        <img src="../uploads/gallery/<?php echo clean($image['image']); ?>"
                            class="w-full h-40 object-cover transition transform group-hover:scale-105 duration-300">
                        <div
                            class="absolute inset-0 bg-black bg-opacity-60 opacity-0 group-hover:opacity-100 transition duration-200 flex items-center justify-center">
                            <a href="?delete=<?php echo $image['id']; ?>&token=<?php echo generateCSRFToken(); ?>"
                                onclick="return confirmDelete()"
                                class="btn btn-sm btn-danger rounded-full w-10 h-10 flex items-center justify-center"
                                title="Delete Image">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Upload Modal -->
<div id="uploadModal"
    class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 backdrop-blur-sm">
    <div class="bg-white rounded-xl shadow-2xl p-0 max-w-md w-full overflow-hidden scale-100 transition-transform">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="text-lg font-bold text-gray-800 m-0">Upload Images</h3>
            <button onclick="document.getElementById('uploadModal').classList.add('hidden')"
                class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-6">
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                <input type="hidden" name="upload" value="1">

                <div class="form-group">
                    <label class="block font-semibold mb-2 text-gray-700">Select Images</label>
                    <div
                        class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:bg-gray-50 transition cursor-pointer relative">
                        <input type="file" name="images[]" multiple accept="image/*" required
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-300 mb-2"></i>
                        <p class="text-sm text-gray-500 font-medium">Click to upload or drag and drop</p>
                        <p class="text-xs text-gray-400 mt-1">SVG, PNG, JPG or GIF</p>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="document.getElementById('uploadModal').classList.add('hidden')"
                        class="btn btn-outline">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload"></i> Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'includes/admin_footer.php'; ?>