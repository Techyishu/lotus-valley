<?php
$pageTitle = 'Manage Sports';
require_once 'includes/admin_header.php';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];

    try {
        $stmt = $pdo->prepare("DELETE FROM sports WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION['success'] = 'Sports activity deleted successfully!';
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Error deleting sports activity: ' . $e->getMessage();
    }

    header('Location: sports.php');
    exit;
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $icon = trim($_POST['icon']);
    $display_order = (int) $_POST['display_order'];

    try {
        if ($id > 0) {
            $stmt = $pdo->prepare("UPDATE sports SET title = ?, description = ?, icon = ?, display_order = ? WHERE id = ?");
            $stmt->execute([$title, $description, $icon, $display_order, $id]);
            $_SESSION['success'] = 'Sports activity updated successfully!';
        } else {
            $stmt = $pdo->prepare("INSERT INTO sports (title, description, icon, display_order) VALUES (?, ?, ?, ?)");
            $stmt->execute([$title, $description, $icon, $display_order]);
            $_SESSION['success'] = 'Sports activity added successfully!';
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Database error: ' . $e->getMessage();
    }

    header('Location: sports.php');
    exit;
}

// Fetch all sports activities
try {
    $sportsStmt = $pdo->query("SELECT * FROM sports ORDER BY display_order, title");
    $sportsResult = $sportsStmt->fetchAll();
} catch (PDOException $e) {
    $sportsResult = [];
}

// Get edit data if editing
$editData = null;
if (isset($_GET['edit'])) {
    $editId = (int) $_GET['edit'];
    try {
        $editStmt = $pdo->prepare("SELECT * FROM sports WHERE id = ?");
        $editStmt->execute([$editId]);
        $editData = $editStmt->fetch();
    } catch (PDOException $e) {
        $editData = null;
    }
}
?>


<?php if (isset($_SESSION['success'])): ?>
    <div class="mb-6 p-4 rounded-lg bg-green-50 text-green-800 border border-green-200 flex items-center gap-3">
        <i class="fas fa-check-circle text-green-500"></i> <?php echo $_SESSION['success'];
        unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="mb-6 p-4 rounded-lg bg-red-50 text-red-800 border border-red-200 flex items-center gap-3">
        <i class="fas fa-exclamation-circle text-red-500"></i> <?php echo $_SESSION['error'];
        unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<div class="card mb-6">
    <div class="card-header">
        <h3><i class="fas fa-running"></i> Manage Sports</h3>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Form Section -->
    <div class="lg:col-span-1">
        <div class="card h-fit sticky top-6">
            <div class="card-header border-b border-gray-100">
                <h3><i class="fas fa-<?php echo $editData ? 'edit' : 'plus'; ?>"></i>
                    <?php echo $editData ? 'Edit Sports Activity' : 'Add New Activity'; ?>
                </h3>
            </div>
            <div class="card-body">
                <form method="POST" class="space-y-4">
                    <?php if ($editData): ?>
                        <input type="hidden" name="id" value="<?php echo $editData['id']; ?>">
                    <?php endif; ?>

                    <div class="form-group">
                        <label class="block text-gray-700 font-semibold mb-2">Title <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="title" class="form-control"
                            value="<?php echo $editData ? htmlspecialchars($editData['title']) : ''; ?>" required
                            placeholder="e.g. Cricket Team">
                    </div>

                    <div class="form-group">
                        <label class="block text-gray-700 font-semibold mb-2">Icon Class</label>
                        <div class="relative">
                            <span class="absolute left-3 top-3 text-gray-400"><i class="fas fa-icons"></i></span>
                            <input type="text" name="icon" class="form-control pl-10"
                                value="<?php echo $editData ? htmlspecialchars($editData['icon']) : 'futbol'; ?>"
                                placeholder="e.g. futbol, basketball-ball">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Font Awesome icon name (without 'fa-')</p>
                    </div>

                    <div class="form-group">
                        <label class="block text-gray-700 font-semibold mb-2">Display Order</label>
                        <input type="number" name="display_order" class="form-control"
                            value="<?php echo $editData ? $editData['display_order'] : '0'; ?>" min="0" placeholder="0">
                    </div>

                    <div class="form-group">
                        <label class="block text-gray-700 font-semibold mb-2">Description <span
                                class="text-red-500">*</span></label>
                        <textarea name="description" class="form-control" rows="4" required
                            placeholder="Detailed description..."><?php echo $editData ? htmlspecialchars($editData['description']) : ''; ?></textarea>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit" class="btn btn-primary w-full justify-center">
                            <i class="fas fa-save"></i> <?php echo $editData ? 'Update' : 'Save'; ?>
                        </button>
                        <?php if ($editData): ?>
                            <a href="sports.php" class="btn btn-outline w-full justify-center">
                                Cancel
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- List Section -->
    <div class="lg:col-span-2 space-y-6">
        <?php if (empty($sportsResult)): ?>
            <div class="card">
                <div class="card-body text-center py-12">
                    <div class="w-20 h-20 bg-teal-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-basketball-ball text-teal-600 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">No Sports Activities Found</h3>
                    <p class="text-gray-500">Add a new sports activity to showcase.</p>
                </div>
            </div>
        <?php else: ?>
            <div class="card overflow-hidden">
                <div class="card-header border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-gray-800"><i class="fas fa-list"></i> All Activities</h3>
                    <span class="badge badge-secondary shadow-sm"><?php echo count($sportsResult); ?> items</span>
                </div>
                <div class="table-responsive">
                    <table class="table w-full">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600 uppercase text-xs tracking-wider text-left">
                                <th class="px-6 py-3 font-semibold w-16 text-center">Icon</th>
                                <th class="px-6 py-3 font-semibold">Activity Details</th>
                                <th class="px-6 py-3 font-semibold w-20 text-center">Order</th>
                                <th class="px-6 py-3 font-semibold text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php foreach ($sportsResult as $sport): ?>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-center">
                                        <div
                                            class="w-10 h-10 rounded-full bg-primary-50 text-primary-600 flex items-center justify-center text-lg mx-auto">
                                            <i class="fas fa-<?php echo htmlspecialchars($sport['icon'] ?: 'trophy'); ?>"></i>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-800 text-base mb-1">
                                            <?php echo htmlspecialchars($sport['title']); ?>
                                        </div>
                                        <div class="text-sm text-gray-500 line-clamp-2 leading-relaxed">
                                            <?php echo htmlspecialchars($sport['description']); ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span
                                            class="inline-flex items-center justify-center w-6 h-6 rounded bg-gray-100 text-gray-600 text-xs font-bold">
                                            <?php echo $sport['display_order']; ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="?edit=<?php echo $sport['id']; ?>"
                                                class="w-8 h-8 rounded-full flex items-center justify-center text-blue-600 hover:bg-blue-50 transition"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="?delete=<?php echo $sport['id']; ?>"
                                                class="w-8 h-8 rounded-full flex items-center justify-center text-red-600 hover:bg-red-50 transition"
                                                onclick="return confirm('Are you sure you want to delete this activity?')"
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
</div>

<?php include 'includes/admin_footer.php'; ?>