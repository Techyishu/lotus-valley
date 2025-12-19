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

<div class="mb-6 flex justify-between items-center">
    <h2 class="text-2xl font-bold text-gray-800">Gallery Images</h2>
    <button onclick="document.getElementById('uploadModal').classList.remove('hidden')"
        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
        <i class="fas fa-plus mr-2"></i>Upload Images
    </button>
</div>

<div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
    <?php foreach ($images as $image): ?>
        <div class="relative group">
            <img src="../uploads/gallery/<?php echo clean($image['image']); ?>" class="w-full h-32 object-cover rounded-lg">
            <div
                class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                <a href="?delete=<?php echo $image['id']; ?>&token=<?php echo generateCSRFToken(); ?>"
                    onclick="return confirmDelete()" class="text-white hover:text-red-300">
                    <i class="fas fa-trash text-xl"></i>
                </a>
            </div>

        </div>
    <?php endforeach; ?>
</div>

<!-- Upload Modal -->
<div id="uploadModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl p-8 max-w-md w-full">
        <h3 class="text-xl font-bold mb-4">Upload Images</h3>
        <form method="POST" enctype="multipart/form-data" class="space-y-4">
            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
            <input type="hidden" name="upload" value="1">

            <div><label class="block font-medium mb-2">Images</label>
                <input type="file" name="images[]" multiple accept="image/*" required
                    class="w-full px-4 py-2 border rounded-lg">
            </div>
            <div class="flex space-x-4">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg">Upload</button>
                <button type="button" onclick="document.getElementById('uploadModal').classList.add('hidden')"
                    class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg">Cancel</button>
            </div>
        </form>
    </div>
</div>

<?php require_once 'includes/admin_footer.php'; ?>