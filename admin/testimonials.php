<?php
$pageTitle = 'Manage Testimonials';
require_once 'includes/admin_header.php';
global $pdo;

// Handle status update
if (isset($_POST['update_status']) && verifyCSRFToken($_POST['csrf_token'])) {
    // Handle boolean for PostgreSQL vs MySQL
    $dbType = defined('DB_TYPE') ? DB_TYPE : 'mysql';
    
    if ($dbType === 'pgsql') {
        // PostgreSQL: use boolean true/false
        $isFeatured = isset($_POST['is_featured']) ? 'true' : 'false';
        $stmt = $pdo->prepare("UPDATE testimonials SET status = ?, is_featured = ?::boolean WHERE id = ?");
        $stmt->execute([$_POST['status'], $isFeatured, (int)$_POST['id']]);
    } else {
        // MySQL: use integer 1/0
        $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
        $stmt = $pdo->prepare("UPDATE testimonials SET status = ?, is_featured = ? WHERE id = ?");
        $stmt->execute([$_POST['status'], (int)$isFeatured, (int)$_POST['id']]);
    }
    echo "<script>showToast('Testimonial updated', 'success');</script>";
}

// Handle delete
if (isset($_GET['delete']) && verifyCSRFToken($_GET['token'])) {
    $stmt = $pdo->prepare("DELETE FROM testimonials WHERE id = ?");
    $stmt->execute([(int)$_GET['delete']]);
    echo "<script>showToast('Testimonial deleted', 'success');</script>";
}

$filter = $_GET['filter'] ?? 'all';
if ($filter === 'all') {
    $stmt = $pdo->query("SELECT * FROM testimonials ORDER BY created_at DESC");
} else {
    $stmt = $pdo->prepare("SELECT * FROM testimonials WHERE status = ? ORDER BY created_at DESC");
    $stmt->execute([$filter]);
}
$testimonials = $stmt->fetchAll();
?>

<div class="mb-6">
    <h2 class="text-2xl font-bold mb-4">Testimonials</h2>
    <div class="flex space-x-2">
        <a href="?filter=all" class="px-4 py-2 rounded-lg <?php echo $filter === 'all' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'; ?>">All</a>
        <a href="?filter=pending" class="px-4 py-2 rounded-lg <?php echo $filter === 'pending' ? 'bg-orange-600 text-white' : 'bg-gray-200 text-gray-700'; ?>">Pending</a>
        <a href="?filter=approved" class="px-4 py-2 rounded-lg <?php echo $filter === 'approved' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700'; ?>">Approved</a>
        <a href="?filter=rejected" class="px-4 py-2 rounded-lg <?php echo $filter === 'rejected' ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700'; ?>">Rejected</a>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <?php foreach ($testimonials as $test): ?>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h4 class="font-bold text-lg"><?php echo clean($test['name']); ?></h4>
                    <p class="text-sm text-gray-600"><?php echo clean($test['role']); ?></p>
                </div>
                <div class="flex">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <i class="fas fa-star <?php echo $i <= $test['rating'] ? 'text-yellow-400' : 'text-gray-300'; ?>"></i>
                    <?php endfor; ?>
                </div>
            </div>
            <p class="text-gray-700 mb-4"><?php echo clean($test['content']); ?></p>
            <div class="mb-4 text-xs text-gray-500">
                <span><?php echo timeAgo($test['created_at']); ?></span>
            </div>
            <form method="POST" class="space-y-3">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                <input type="hidden" name="id" value="<?php echo $test['id']; ?>">
                <input type="hidden" name="update_status" value="1">
                <div class="flex space-x-2">
                    <select name="status" class="flex-1 px-3 py-2 border rounded-lg text-sm">
                        <option value="pending" <?php echo $test['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="approved" <?php echo $test['status'] === 'approved' ? 'selected' : ''; ?>>Approved</option>
                        <option value="rejected" <?php echo $test['status'] === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                    </select>
                    <label class="flex items-center text-sm">
                        <?php 
                        // Handle boolean for PostgreSQL (returns true/false) vs MySQL (returns 1/0)
                        $isChecked = ($test['is_featured'] === true || $test['is_featured'] === 't' || $test['is_featured'] == 1);
                        ?>
                        <input type="checkbox" name="is_featured" value="1" <?php echo $isChecked ? 'checked' : ''; ?> class="mr-2">Featured
                    </label>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">Update</button>
                    <a href="?delete=<?php echo $test['id']; ?>&token=<?php echo generateCSRFToken(); ?>" 
                       onclick="return confirmDelete()" class="text-red-600 px-4 py-2">Delete</a>
                </div>
            </form>
        </div>
    <?php endforeach; ?>
</div>

<?php require_once 'includes/admin_footer.php'; ?>

