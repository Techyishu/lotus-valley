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
        $stmt->execute([$_POST['status'], $isFeatured, (int) $_POST['id']]);
    } else {
        // MySQL: use integer 1/0
        $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
        $stmt = $pdo->prepare("UPDATE testimonials SET status = ?, is_featured = ? WHERE id = ?");
        $stmt->execute([$_POST['status'], (int) $isFeatured, (int) $_POST['id']]);
    }
    echo "<script>showToast('Testimonial updated', 'success');</script>";
}

// Handle delete
if (isset($_GET['delete']) && verifyCSRFToken($_GET['token'])) {
    $stmt = $pdo->prepare("DELETE FROM testimonials WHERE id = ?");
    $stmt->execute([(int) $_GET['delete']]);
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

<div class="card mb-6">
    <div class="card-header flex flex-col md:flex-row justify-between items-center gap-4">
        <h3><i class="fas fa-star-half-alt"></i> Manage Testimonials</h3>
        <div class="flex flex-wrap gap-2">
            <a href="?filter=all"
                class="btn btn-sm <?php echo $filter === 'all' ? 'btn-primary' : 'btn-outline'; ?>">All</a>
            <a href="?filter=pending"
                class="btn btn-sm <?php echo $filter === 'pending' ? 'btn-warning text-white' : 'btn-outline'; ?>">Pending</a>
            <a href="?filter=approved"
                class="btn btn-sm <?php echo $filter === 'approved' ? 'btn-success text-white' : 'btn-outline'; ?>">Approved</a>
            <a href="?filter=rejected"
                class="btn btn-sm <?php echo $filter === 'rejected' ? 'btn-danger text-white' : 'btn-outline'; ?>">Rejected</a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <?php if (empty($testimonials)): ?>
        <div class="col-span-1 lg:col-span-2 card">
            <div class="card-body text-center py-12">
                <div class="w-20 h-20 bg-teal-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="far fa-comment-dots text-teal-600 text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">No Testimonials Found</h3>
                <p class="text-gray-500">Wait for user submissions to approve/reject them here.</p>
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($testimonials as $test): ?>
            <div class="card hover:shadow-lg transition flex flex-col h-full border border-gray-100">
                <div class="card-body flex-grow">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-full bg-primary-50 text-primary-600 flex items-center justify-center font-bold text-lg">
                                <?php echo strtoupper(substr($test['name'], 0, 1)); ?>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800"><?php echo clean($test['name']); ?></h4>
                                <p class="text-xs text-primary-600 font-medium uppercase tracking-wide">
                                    <?php echo clean($test['role']); ?>
                                </p>
                            </div>
                        </div>
                        <div class="flex text-yellow-400 text-xs">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star <?php echo $i <= $test['rating'] ? '' : 'text-gray-200'; ?>"></i>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <div class="relative pl-4 mb-4">
                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-gray-100 rounded-full"></div>
                        <p class="text-gray-600 italic text-sm leading-relaxed">"<?php echo clean($test['content']); ?>"</p>
                    </div>

                    <div class="flex items-center justify-between text-xs text-gray-400 mt-auto pt-4 border-t border-gray-50">
                        <span><i class="far fa-clock mr-1"></i> <?php echo timeAgo($test['created_at']); ?></span>
                        <span class="badge <?php
                        $badgeClass = 'badge-warning';
                        if ($test['status'] === 'approved')
                            $badgeClass = 'badge-success';
                        elseif ($test['status'] === 'rejected')
                            $badgeClass = 'badge-danger';
                        echo $badgeClass;
                        ?>">
                            <?php echo ucfirst($test['status']); ?>
                        </span>
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 rounded-b-lg">
                    <form method="POST" class="flex flex-col sm:flex-row gap-3 items-center justify-between">
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                        <input type="hidden" name="id" value="<?php echo $test['id']; ?>">
                        <input type="hidden" name="update_status" value="1">

                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <select name="status"
                                class="form-select form-select-sm text-sm border-gray-200 rounded-lg focus:ring-primary-500 focus:border-primary-500">
                                <option value="pending" <?php echo $test['status'] === 'pending' ? 'selected' : ''; ?>>Pending
                                </option>
                                <option value="approved" <?php echo $test['status'] === 'approved' ? 'selected' : ''; ?>>Approved
                                </option>
                                <option value="rejected" <?php echo $test['status'] === 'rejected' ? 'selected' : ''; ?>>Rejected
                                </option>
                            </select>

                            <label class="flex items-center text-sm text-gray-600 cursor-pointer select-none whitespace-nowrap">
                                <?php
                                $isChecked = ($test['is_featured'] === true || $test['is_featured'] === 't' || $test['is_featured'] == 1);
                                ?>
                                <input type="checkbox" name="is_featured" value="1" <?php echo $isChecked ? 'checked' : ''; ?>
                                    class="mr-2 text-primary-600 focus:ring-primary-500 rounded">
                                Featured
                            </label>
                        </div>

                        <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="fas fa-check mr-1"></i> Save
                            </button>
                            <a href="?delete=<?php echo $test['id']; ?>&token=<?php echo generateCSRFToken(); ?>"
                                onclick="return confirmDelete()"
                                class="btn btn-sm btn-outline text-red-500 hover:bg-red-50 border-gray-200">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once 'includes/admin_footer.php'; ?>