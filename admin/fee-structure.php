<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';
requireLogin();

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];

    try {
        $stmt = $pdo->prepare("DELETE FROM fee_structure WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION['success'] = 'Fee structure deleted successfully!';
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Error deleting fee structure: ' . $e->getMessage();
    }

    header('Location: fee-structure.php');
    exit;
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
    $class_name = trim($_POST['class_name']);
    $admission_fee = !empty($_POST['admission_fee']) ? (float) $_POST['admission_fee'] : 0.00;
    $tuition_fee = !empty($_POST['tuition_fee']) ? (float) $_POST['tuition_fee'] : 0.00;
    $annual_charges = !empty($_POST['annual_charges']) ? (float) $_POST['annual_charges'] : 0.00;
    $total_fee = !empty($_POST['total_fee']) ? (float) $_POST['total_fee'] : 0.00;
    $notes = trim($_POST['notes']);
    $display_order = (int) $_POST['display_order'];

    try {
        if ($id > 0) {
            $stmt = $pdo->prepare("UPDATE fee_structure SET class_name = ?, admission_fee = ?, tuition_fee = ?, annual_charges = ?, total_fee = ?, notes = ?, display_order = ? WHERE id = ?");
            $stmt->execute([$class_name, $admission_fee, $tuition_fee, $annual_charges, $total_fee, $notes, $display_order, $id]);
            $_SESSION['success'] = 'Fee structure updated successfully!';
        } else {
            $stmt = $pdo->prepare("INSERT INTO fee_structure (class_name, admission_fee, tuition_fee, annual_charges, total_fee, notes, display_order) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$class_name, $admission_fee, $tuition_fee, $annual_charges, $total_fee, $notes, $display_order]);
            $_SESSION['success'] = 'Fee structure added successfully!';
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Database error: ' . $e->getMessage();
    }

    header('Location: fee-structure.php');
    exit;
}

$pageTitle = 'Manage Fee Structure';
require_once 'includes/admin_header.php';

// Fetch all fee structures
try {
    $feeStmt = $pdo->query("SELECT * FROM fee_structure ORDER BY display_order, class_name");
    $feeResult = $feeStmt->fetchAll();
} catch (PDOException $e) {
    $feeResult = [];
}

// Get edit data if editing
$editData = null;
if (isset($_GET['edit'])) {
    $editId = (int) $_GET['edit'];
    try {
        $editStmt = $pdo->prepare("SELECT * FROM fee_structure WHERE id = ?");
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
        <h3><i class="fas fa-money-bill-wave"></i> Manage Fee Structure</h3>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Form Section -->
    <div class="lg:col-span-1">
        <div class="card h-fit sticky top-6">
            <div class="card-header border-b border-gray-100">
                <h3><i class="fas fa-<?php echo $editData ? 'edit' : 'plus'; ?>"></i>
                    <?php echo $editData ? 'Edit Fee Structure' : 'Add New Fee Structure'; ?>
                </h3>
            </div>
            <div class="card-body">
                <form method="POST" id="feeForm" class="space-y-4">
                    <?php if ($editData): ?>
                        <input type="hidden" name="id" value="<?php echo $editData['id']; ?>">
                    <?php endif; ?>

                    <div class="form-group">
                        <label class="block text-gray-700 font-semibold mb-2">Class Name <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="class_name" class="form-control"
                            value="<?php echo $editData ? htmlspecialchars($editData['class_name']) : ''; ?>" required
                            placeholder="e.g. Nursery, Class 1">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="block text-gray-700 font-semibold mb-2">Admission (₹)</label>
                            <input type="number" id="admission_fee" name="admission_fee" class="form-control"
                                step="0.01" value="<?php echo $editData ? $editData['admission_fee'] : '0.00'; ?>"
                                onchange="calculateTotal()" placeholder="0.00">
                        </div>

                        <div class="form-group">
                            <label class="block text-gray-700 font-semibold mb-2">Tuition (₹)</label>
                            <input type="number" id="tuition_fee" name="tuition_fee" class="form-control" step="0.01"
                                value="<?php echo $editData ? $editData['tuition_fee'] : '0.00'; ?>"
                                onchange="calculateTotal()" placeholder="0.00">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="block text-gray-700 font-semibold mb-2">Annual Charges (₹)</label>
                        <input type="number" id="annual_charges" name="annual_charges" class="form-control" step="0.01"
                            value="<?php echo $editData ? $editData['annual_charges'] : '0.00'; ?>"
                            onchange="calculateTotal()" placeholder="0.00">
                    </div>

                    <div class="form-group">
                        <label class="block text-gray-700 font-semibold mb-2">Total Fee (₹) <span
                                class="text-red-500">*</span></label>
                        <input type="number" id="total_fee" name="total_fee"
                            class="form-control bg-gray-50 mb-2 font-bold" step="0.01"
                            value="<?php echo $editData ? $editData['total_fee'] : '0.00'; ?>" required readonly>
                        <p class="text-xs text-gray-500">Auto-calculated from above fields</p>
                    </div>

                    <div class="form-group">
                        <label class="block text-gray-700 font-semibold mb-2">Display Order</label>
                        <input type="number" name="display_order" class="form-control"
                            value="<?php echo $editData ? $editData['display_order'] : '0'; ?>" min="0" placeholder="0">
                    </div>

                    <div class="form-group">
                        <label class="block text-gray-700 font-semibold mb-2">Notes</label>
                        <textarea name="notes" class="form-control" rows="3"
                            placeholder="Additional notes..."><?php echo $editData ? htmlspecialchars($editData['notes']) : ''; ?></textarea>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit" class="btn btn-primary w-full justify-center">
                            <i class="fas fa-save"></i> <?php echo $editData ? 'Update' : 'Save'; ?>
                        </button>
                        <?php if ($editData): ?>
                            <a href="fee-structure.php" class="btn btn-outline w-full justify-center">
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
        <?php if (empty($feeResult)): ?>
            <div class="card">
                <div class="card-body text-center py-16">
                    <i class="fas fa-rupee-sign text-gray-300 text-5xl mb-4"></i>
                    <p class="text-gray-500">No fee structures found.</p>
                </div>
            </div>
        <?php else: ?>
            <div class="card overflow-hidden">
                <div class="card-header border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-gray-800"><i class="fas fa-list"></i> Structure List</h3>
                    <span class="badge badge-secondary shadow-sm"><?php echo count($feeResult); ?> classes</span>
                </div>
                <div class="table-responsive">
                    <table class="table w-full">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600 uppercase text-xs tracking-wider text-left">
                                <th class="px-6 py-3 font-semibold">Class</th>
                                <th class="px-6 py-3 font-semibold text-right">Admission</th>
                                <th class="px-6 py-3 font-semibold text-right">Tuition</th>
                                <th class="px-6 py-3 font-semibold text-right">Annual</th>
                                <th class="px-6 py-3 font-semibold text-right">Total</th>
                                <th class="px-6 py-3 font-semibold text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php foreach ($feeResult as $fee): ?>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-800"><?php echo htmlspecialchars($fee['class_name']); ?>
                                        </div>
                                        <?php if ($fee['notes']): ?>
                                            <div class="text-xs text-gray-500 mt-0.5">
                                                <?php echo htmlspecialchars(substr($fee['notes'], 0, 30)); ?>...
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-right text-gray-600">
                                        ₹<?php echo number_format($fee['admission_fee'], 0); ?></td>
                                    <td class="px-6 py-4 text-right text-gray-600">
                                        ₹<?php echo number_format($fee['tuition_fee'], 0); ?></td>
                                    <td class="px-6 py-4 text-right text-gray-600">
                                        ₹<?php echo number_format($fee['annual_charges'], 0); ?></td>
                                    <td class="px-6 py-4 text-right">
                                        <span class="font-bold text-primary-700 bg-primary-50 px-2 py-1 rounded">
                                            ₹<?php echo number_format($fee['total_fee'], 0); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="?edit=<?php echo $fee['id']; ?>"
                                                class="w-8 h-8 rounded-full flex items-center justify-center text-blue-600 hover:bg-blue-50 transition"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="?delete=<?php echo $fee['id']; ?>"
                                                class="w-8 h-8 rounded-full flex items-center justify-center text-red-600 hover:bg-red-50 transition"
                                                onclick="return confirm('Are you sure you want to delete this fee structure?')"
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

<script>
    function calculateTotal() {
        const admission = parseFloat(document.getElementById('admission_fee').value) || 0;
        const tuition = parseFloat(document.getElementById('tuition_fee').value) || 0;
        const annual = parseFloat(document.getElementById('annual_charges').value) || 0;

        const total = admission + tuition + annual;
        document.getElementById('total_fee').value = total.toFixed(2);
    }
</script>

<?php include 'includes/admin_footer.php'; ?>