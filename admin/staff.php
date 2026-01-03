<?php
$pageTitle = 'Manage Staff';
require_once 'includes/admin_header.php';

global $pdo;

// Handle delete
if (isset($_GET['delete']) && isset($_GET['token']) && verifyCSRFToken($_GET['token'])) {
    $id = (int) $_GET['delete'];

    try {
        $stmt = $pdo->prepare("SELECT photo FROM staff WHERE id = ?");
        $stmt->execute([$id]);
        $staff = $stmt->fetch();

        if ($staff && $staff['photo']) {
            deleteFile('../uploads/staff/' . $staff['photo']);
        }

        $stmt = $pdo->prepare("DELETE FROM staff WHERE id = ?");
        $stmt->execute([$id]);

        echo "<script>showToast('Staff member deleted successfully', 'success');</script>";
    } catch (PDOException $e) {
        echo "<script>showToast('Error deleting staff member', 'error');</script>";
    }
}

try {
    $stmt = $pdo->query("SELECT * FROM staff ORDER BY display_order ASC, name ASC");
    $staffMembers = $stmt->fetchAll();
} catch (PDOException $e) {
    $staffMembers = [];
}
?>

<div class="card">

    <div class="card-header border-b border-gray-100 flex justify-between items-center">
        <h3><i class="fas fa-user-tie"></i> Manage Staff</h3>
        <a href="add_staff.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Staff
        </a>
    </div>

    <?php if (empty($staffMembers)): ?>
            <div class=" card-body text-center py-16">
                    <div
                        style="width: 80px; height: 80px; background: #e0f2f1; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                        <i class="fas fa-user-tie text-teal-600 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">No Staff Members Found</h3>
                    <p class="text-gray-500 mb-6">Start by building your team directory.</p>
                    <a href="add_staff.php" class="btn btn-primary">
                        Add Staff Member
                    </a>
        </div>
    <?php else: ?>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table w-full">
                    <thead>
                        <tr>
                            <th>Staff Member</th>
                            <th>Designation</th>
                            <th>Department</th>
                            <th>Experience</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($staffMembers as $staff): ?>
                            <tr>
                                <td>
                                    <div class="flex items-center gap-4">
                                        <?php if ($staff['photo']): ?>
                                            <img src="../uploads/staff/<?php echo clean($staff['photo']); ?>"
                                                alt="<?php echo clean($staff['name']); ?>"
                                                class="w-10 h-10 rounded-full object-cover border border-gray-200">
                                        <?php else: ?>
                                            <div
                                                class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center border border-gray-200">
                                                <i class="fas fa-user text-gray-400"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <div class="font-medium text-gray-900"><?php echo clean($staff['name']); ?></div>
                                            <div class="text-xs text-gray-500"><?php echo clean($staff['email'] ?? ''); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-gray-700 font-medium"><?php echo clean($staff['designation']); ?></span>
                                </td>
                                <td>
                                    <span class="badge badge-info">
                                        <?php echo clean($staff['department']); ?>
                                    </span>
                                </td>
                                <td class="text-gray-600">
                                    <?php echo clean($staff['experience'] ?? 'N/A'); ?>
                                </td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="edit_staff.php?id=<?php echo $staff['id']; ?>"
                                            class="btn btn-sm btn-outline text-blue-600" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="?delete=<?php echo $staff['id']; ?>&token=<?php echo generateCSRFToken(); ?>"
                                            class="btn btn-sm btn-outline text-red-600"
                                            onclick="return confirmDelete('Are you sure you want to delete this staff member?')"
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