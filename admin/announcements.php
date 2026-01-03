<?php
$pageTitle = 'Manage Announcements';
require_once 'includes/admin_header.php';
global $pdo;

// Handle add/edit/delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && verifyCSRFToken($_POST['csrf_token'])) {
    if (isset($_POST['add'])) {
        $stmt = $pdo->prepare("INSERT INTO announcements (title, content, date, priority, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([trim($_POST['title']), trim($_POST['content']), $_POST['date'], $_POST['priority'], $_POST['status']]);
        echo "<script>showToast('Announcement added', 'success');</script>";
    } elseif (isset($_POST['edit'])) {
        $stmt = $pdo->prepare("UPDATE announcements SET title=?, content=?, date=?, priority=?, status=? WHERE id=?");
        $stmt->execute([trim($_POST['title']), trim($_POST['content']), $_POST['date'], $_POST['priority'], $_POST['status'], (int) $_POST['id']]);
        echo "<script>showToast('Announcement updated', 'success');</script>";
    }
}

if (isset($_GET['delete']) && verifyCSRFToken($_GET['token'])) {
    $stmt = $pdo->prepare("DELETE FROM announcements WHERE id = ?");
    $stmt->execute([(int) $_GET['delete']]);
    echo "<script>showToast('Announcement deleted', 'success');</script>";
}

$editing = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM announcements WHERE id = ?");
    $stmt->execute([(int) $_GET['edit']]);
    $editing = $stmt->fetch();
}

$stmt = $pdo->query("SELECT * FROM announcements ORDER BY date DESC, priority DESC");
$announcements = $stmt->fetchAll();
?>

<div class="card mb-6">
    <div class="card-header">
        <h3><i class="fas fa-bullhorn"></i> Manage Announcements</h3>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Form Side -->
    <div class="card h-fit">
        <div class="card-header">
            <h3>
                <i class="fas <?php echo $editing ? 'fa-edit' : 'fa-plus'; ?>"></i>
                <?php echo $editing ? 'Edit' : 'Add New'; ?> Announcement
            </h3>
        </div>
        <div class="card-body">
            <form method="POST" class="space-y-4">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                <?php if ($editing): ?>
                    <input type="hidden" name="id" value="<?php echo $editing['id']; ?>">
                    <input type="hidden" name="edit" value="1">
                <?php else: ?>
                    <input type="hidden" name="add" value="1">
                <?php endif; ?>

                <div class="form-group">
                    <label class="block font-semibold mb-2 text-gray-700">Title <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="title" required
                        value="<?php echo $editing ? clean($editing['title']) : ''; ?>" class="form-control"
                        placeholder="Enter announcement title">
                </div>

                <div class="form-group">
                    <label class="block font-semibold mb-2 text-gray-700">Content <span
                            class="text-red-500">*</span></label>
                    <textarea name="content" rows="4" required class="form-control"
                        placeholder="Enter full details..."><?php echo $editing ? clean($editing['content']) : ''; ?></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="form-group">
                        <label class="block font-semibold mb-2 text-gray-700">Date <span
                                class="text-red-500">*</span></label>
                        <input type="date" name="date" required
                            value="<?php echo $editing ? $editing['date'] : date('Y-m-d'); ?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="block font-semibold mb-2 text-gray-700">Priority</label>
                        <select name="priority" class="form-control">
                            <option value="low" <?php echo ($editing && $editing['priority'] === 'low') ? 'selected' : ''; ?>>Low Priority</option>
                            <option value="medium" <?php echo (!$editing || $editing['priority'] === 'medium') ? 'selected' : ''; ?>>Medium Priority</option>
                            <option value="high" <?php echo ($editing && $editing['priority'] === 'high') ? 'selected' : ''; ?>>High Priority</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="block font-semibold mb-2 text-gray-700">Status</label>
                    <select name="status" class="form-control">
                        <option value="published" <?php echo (!$editing || $editing['status'] === 'published') ? 'selected' : ''; ?>>Published</option>
                        <option value="draft" <?php echo ($editing && $editing['status'] === 'draft') ? 'selected' : ''; ?>>Draft</option>
                    </select>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="btn btn-primary w-full justify-center">
                        <i class="fas fa-save"></i> Save Announcement
                    </button>
                    <?php if ($editing): ?>
                        <a href="announcements.php" class="btn btn-outline w-full justify-center">
                            Cancel
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- List Side -->
    <div class="space-y-4">
        <?php if (empty($announcements)): ?>

            <div class="card">
                <div class="card-body text-center py-12">
                    <div
                        style="width: 80px; height: 80px; background: #e0f2f1; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                        <i class="fas fa-bullhorn text-teal-600 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">No Announcements</h3>
                    <p class="text-gray-500">Create your first announcement to notify users.</p>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($announcements as $ann): ?>
                <div
                    class="card border-l-4 <?php echo $ann['priority'] === 'high' ? 'border-red-500' : ($ann['priority'] === 'medium' ? 'border-yellow-500' : 'border-blue-500'); ?> hover:shadow-lg transition">
                    <div class="card-body p-5">
                        <div class="flex justify-between items-start mb-3">
                            <h4 class="font-bold text-lg text-gray-800 leading-tight"><?php echo clean($ann['title']); ?></h4>
                            <span
                                class="badge <?php echo $ann['status'] === 'published' ? 'badge-success' : 'badge-secondary'; ?>">
                                <?php echo ucfirst($ann['status']); ?>
                            </span>
                        </div>

                        <p class="text-gray-600 mb-4 leading-relaxed text-sm">
                            <?php echo nl2br(clean($ann['content'])); ?>
                        </p>

                        <div class="flex justify-between items-center text-sm pt-4 border-t border-gray-100 mt-2">
                            <span class="text-gray-500 font-medium">
                                <i class="far fa-calendar-alt mr-2"></i><?php echo formatDate($ann['date']); ?>
                            </span>
                            <div class="flex gap-2">
                                <a href="?edit=<?php echo $ann['id']; ?>"
                                    class="btn btn-sm btn-outline text-blue-600 border-none hover:bg-blue-50">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="?delete=<?php echo $ann['id']; ?>&token=<?php echo generateCSRFToken(); ?>"
                                    onclick="return confirmDelete('Delete this announcement?')"
                                    class="btn btn-sm btn-outline text-red-600 border-none hover:bg-red-50">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/admin_footer.php'; ?>