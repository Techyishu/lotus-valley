<?php
$pageTitle = 'Manage Toppers';
require_once 'includes/admin_header.php';

global $pdo;

// Handle delete
if (isset($_GET['delete']) && isset($_GET['token']) && verifyCSRFToken($_GET['token'])) {
    $id = (int) $_GET['delete'];

    try {
        // Get topper photo
        $stmt = $pdo->prepare("SELECT photo FROM toppers WHERE id = ?");
        $stmt->execute([$id]);
        $topper = $stmt->fetch();

        // Delete photo file
        if ($topper && $topper['photo']) {
            deleteFile('../uploads/toppers/' . $topper['photo']);
        }

        // Delete from database
        $stmt = $pdo->prepare("DELETE FROM toppers WHERE id = ?");
        $stmt->execute([$id]);

        echo "<script>showToast('Topper deleted successfully', 'success');</script>";
    } catch (PDOException $e) {
        echo "<script>showToast('Error deleting topper', 'error');</script>";
    }
}

// Get all toppers
try {
    $stmt = $pdo->query("SELECT * FROM toppers ORDER BY year DESC, percentage DESC");
    $toppers = $stmt->fetchAll();
} catch (PDOException $e) {
    $toppers = [];
}
?>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-trophy"></i> All Toppers</h3>
        <a href="add_topper.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Topper
        </a>
    </div>

    <?php if (empty($toppers)): ?>
        <div class="card-body text-center py-16">
            <div
                style="width: 80px; height: 80px; background: #fef3c7; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                <i class="fas fa-trophy text-orange-400 text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">No Toppers Found</h3>
            <p class="text-gray-500 mb-6">Start by adding your first academic topper.</p>
            <a href="add_topper.php" class="btn btn-primary">
                Add Topper
            </a>
        </div>
    <?php else: ?>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive" style="border: none; border-radius: 0;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Class</th>
                            <th>Marks</th>
                            <th>Percentage</th>
                            <th>Year</th>
                            <th>Board</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($toppers as $topper): ?>
                            <tr>
                                <td>
                                    <div class="flex items-center gap-4">
                                        <?php if ($topper['photo']): ?>
                                            <img src="../uploads/toppers/<?php echo clean($topper['photo']); ?>"
                                                alt="<?php echo clean($topper['name']); ?>"
                                                class="w-10 h-10 rounded-full object-cover border border-gray-200">
                                        <?php else: ?>
                                            <div
                                                class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center border border-gray-200">
                                                <i class="fas fa-user text-gray-400"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div class="font-medium text-gray-900"><?php echo clean($topper['name']); ?></div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-info"><?php echo clean($topper['class']); ?></span>
                                </td>
                                <td>
                                    <span class="font-medium text-gray-700"><?php echo clean($topper['marks']); ?></span>
                                </td>
                                <td>
                                    <span class="badge badge-warning">
                                        <?php echo clean($topper['percentage']); ?>%
                                    </span>
                                </td>
                                <td class="text-gray-600">
                                    <?php echo clean($topper['year']); ?>
                                </td>
                                <td class="text-gray-600">
                                    <?php echo clean($topper['board']); ?>
                                </td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="edit_topper.php?id=<?php echo $topper['id']; ?>"
                                            class="btn btn-sm btn-outline text-blue-600" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="?delete=<?php echo $topper['id']; ?>&token=<?php echo generateCSRFToken(); ?>"
                                            class="btn btn-sm btn-outline text-red-600"
                                            onclick="return confirmDelete('Are you sure you want to delete this topper?')"
                                            title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/admin_footer.php'; ?>