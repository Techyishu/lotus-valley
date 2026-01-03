<?php
$pageTitle = 'Admission Inquiries';
require_once 'includes/admin_header.php';

global $pdo;

// Handle status update
if (isset($_POST['update_status']) && verifyCSRFToken($_POST['csrf_token'])) {
    $id = (int) $_POST['id'];
    $status = $_POST['status'];

    try {
        $stmt = $pdo->prepare("UPDATE admission_inquiries SET status = ? WHERE id = ?");
        $stmt->execute([$status, $id]);
        echo "<script>showToast('Status updated successfully', 'success');</script>";
    } catch (PDOException $e) {
        echo "<script>showToast('Error updating status', 'error');</script>";
    }
}

// Handle delete
if (isset($_GET['delete']) && isset($_GET['token']) && verifyCSRFToken($_GET['token'])) {
    $id = (int) $_GET['delete'];

    try {
        $stmt = $pdo->prepare("DELETE FROM admission_inquiries WHERE id = ?");
        $stmt->execute([$id]);
        echo "<script>showToast('Inquiry deleted successfully', 'success');</script>";
    } catch (PDOException $e) {
        echo "<script>showToast('Error deleting inquiry', 'error');</script>";
    }
}

// Get filter
$filterStatus = $_GET['status'] ?? 'all';

try {
    if ($filterStatus === 'all') {
        $stmt = $pdo->query("SELECT * FROM admission_inquiries ORDER BY submitted_at DESC");
    } else {
        $stmt = $pdo->prepare("SELECT * FROM admission_inquiries WHERE status = ? ORDER BY submitted_at DESC");
        $stmt->execute([$filterStatus]);
    }
    $inquiries = $stmt->fetchAll();
} catch (PDOException $e) {
    $inquiries = [];
}
?>

<div class="card mb-6">
    <div class="card-header flex flex-col md:flex-row justify-between items-center gap-4">
        <h3><i class="fas fa-envelope-open-text"></i> Admission Inquiries</h3>
        <!-- Filters -->
        <div class="flex flex-wrap gap-2">
            <a href="?status=all"
                class="btn btn-sm <?php echo $filterStatus === 'all' ? 'btn-primary' : 'btn-outline'; ?>">
                All
            </a>
            <a href="?status=pending"
                class="btn btn-sm <?php echo $filterStatus === 'pending' ? 'btn-warning text-white' : 'btn-outline text-orange-600 border-orange-200 hover:bg-orange-50'; ?>">
                Pending
            </a>
            <a href="?status=reviewed"
                class="btn btn-sm <?php echo $filterStatus === 'reviewed' ? 'btn-info text-white' : 'btn-outline text-blue-600 border-blue-200 hover:bg-blue-50'; ?>">
                Reviewed
            </a>
            <a href="?status=contacted"
                class="btn btn-sm <?php echo $filterStatus === 'contacted' ? 'btn-success text-white' : 'btn-outline text-green-600 border-green-200 hover:bg-green-50'; ?>">
                Contacted
            </a>
        </div>
    </div>
</div>

<div class="space-y-6">

    <?php if (empty($inquiries)): ?>
        <div class="card">
            <div class="card-body text-center py-16">
                <div
                    style="width: 80px; height: 80px; background: #e0f2f1; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                    <i class="fas fa-envelope-open text-teal-600 text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">No Inquiries Found</h3>
                <p class="text-gray-500">Check back later for new admission inquiries.</p>
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($inquiries as $inquiry): ?>
            <div class="card hover:shadow-lg transition duration-200 border border-gray-100">
                <div class="card-body p-6">
                    <div class="flex flex-col md:flex-row justify-between items-start gap-4 mb-4 pb-4 border-b border-gray-50">
                        <div>
                            <div class="flex items-center gap-3 mb-1">
                                <h3 class="text-xl font-bold text-primary-900"><?php echo clean($inquiry['student_name']); ?>
                                </h3>
                                <span class="badge <?php echo $inquiry['status'] === 'pending' ? 'badge-warning' :
                                    ($inquiry['status'] === 'reviewed' ? 'badge-info' : 'badge-success'); ?>">
                                    <?php echo ucfirst($inquiry['status']); ?>
                                </span>
                            </div>
                            <p class="text-sm text-gray-500 flex items-center gap-2">
                                <span
                                    class="bg-gray-100 px-2 py-0.5 rounded text-xs font-semibold uppercase tracking-wide text-gray-600">Applied
                                    For</span>
                                <span class="font-medium text-gray-800"><?php echo clean($inquiry['class_applying']); ?></span>
                            </p>
                        </div>
                        <div class="text-right text-xs text-gray-400 font-medium">
                            <i class="far fa-clock mr-1"></i> Received
                            <?php echo formatDate($inquiry['submitted_at'], 'd M Y, g:i A'); ?>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-user text-blue-500 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 uppercase font-bold tracking-wider mb-0.5">Parent Name</p>
                                <p class="text-gray-800 font-medium"><?php echo clean($inquiry['parent_name']); ?></p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-green-50 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-envelope text-green-500 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 uppercase font-bold tracking-wider mb-0.5">Contact Email</p>
                                <p class="text-gray-800 font-medium"><?php echo clean($inquiry['email']); ?></p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-purple-50 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-phone text-purple-500 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 uppercase font-bold tracking-wider mb-0.5">Phone Number</p>
                                <p class="text-gray-800 font-medium"><?php echo clean($inquiry['phone']); ?></p>
                            </div>
                        </div>

                        <?php if ($inquiry['previous_school']): ?>
                            <div class="flex items-start gap-3 md:col-span-2 lg:col-span-3 border-t border-gray-50 pt-4 mt-2">
                                <div class="w-8 h-8 rounded-full bg-orange-50 flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-school text-orange-500 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 uppercase font-bold tracking-wider mb-0.5">Previous School</p>
                                    <p class="text-gray-800"><?php echo clean($inquiry['previous_school']); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if ($inquiry['message']): ?>
                        <div class="bg-gray-50 rounded-xl p-5 mb-6 border border-gray-100 relative">
                            <i class="fas fa-quote-left absolute top-4 left-4 text-gray-200 text-2xl"></i>
                            <p class="text-gray-600 text-sm leading-relaxed relative z-10 pl-8 italic">
                                "<?php echo clean($inquiry['message']); ?>"
                            </p>
                        </div>
                    <?php endif; ?>

                    <div class="flex flex-wrap items-center gap-3 pt-2">
                        <form method="POST" class="inline-flex">
                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                            <input type="hidden" name="id" value="<?php echo $inquiry['id']; ?>">
                            <input type="hidden" name="update_status" value="1">

                            <select name="status" onchange="this.form.submit()"
                                class="form-select form-select-sm py-1.5 pl-3 pr-8 border border-gray-200 rounded-lg text-sm bg-gray-50 hover:bg-white focus:ring-2 focus:ring-primary-100 cursor-pointer">
                                <option value="pending" <?php echo $inquiry['status'] === 'pending' ? 'selected' : ''; ?>>Mark as
                                    Pending</option>
                                <option value="reviewed" <?php echo $inquiry['status'] === 'reviewed' ? 'selected' : ''; ?>>Mark
                                    as Reviewed</option>
                                <option value="contacted" <?php echo $inquiry['status'] === 'contacted' ? 'selected' : ''; ?>>Mark
                                    as Contacted</option>
                            </select>
                        </form>

                        <div class="h-6 w-px bg-gray-200 mx-1 hidden sm:block"></div>

                        <a href="mailto:<?php echo clean($inquiry['email']); ?>"
                            class="btn btn-sm btn-outline text-gray-600 hover:text-blue-600 hover:border-blue-200">
                            <i class="fas fa-envelope mr-1.5"></i> Email
                        </a>
                        <a href="tel:<?php echo clean($inquiry['phone']); ?>"
                            class="btn btn-sm btn-outline text-gray-600 hover:text-green-600 hover:border-green-200">
                            <i class="fas fa-phone mr-1.5"></i> Call
                        </a>

                        <div class="ml-auto">
                            <a href="?delete=<?php echo $inquiry['id']; ?>&token=<?php echo generateCSRFToken(); ?>"
                                class="btn btn-sm btn-outline text-red-500 border-red-100 hover:bg-red-50"
                                onclick="return confirmDelete('Delete this inquiry?')">
                                <i class="fas fa-trash mr-1.5"></i> Delete
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once 'includes/admin_footer.php'; ?>