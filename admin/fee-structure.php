<?php
$pageTitle = 'Manage Fee Structure';
require_once 'includes/admin_header.php';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];

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
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $class_name = trim($_POST['class_name']);
    $admission_fee = !empty($_POST['admission_fee']) ? (float)$_POST['admission_fee'] : 0.00;
    $tuition_fee = !empty($_POST['tuition_fee']) ? (float)$_POST['tuition_fee'] : 0.00;
    $annual_charges = !empty($_POST['annual_charges']) ? (float)$_POST['annual_charges'] : 0.00;
    $total_fee = !empty($_POST['total_fee']) ? (float)$_POST['total_fee'] : 0.00;
    $notes = trim($_POST['notes']);
    $display_order = (int)$_POST['display_order'];

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
    $editId = (int)$_GET['edit'];
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
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <!-- Add/Edit Form -->
    <div class="card" style="margin-bottom: 2rem;">
        <div class="card-header">
            <h3><i class="fas fa-<?php echo $editData ? 'edit' : 'plus'; ?>"></i>
                <?php echo $editData ? 'Edit Fee Structure' : 'Add Fee Structure'; ?>
            </h3>
        </div>
        <div class="card-body">
            <form method="POST" id="feeForm">
                <?php if ($editData): ?>
                    <input type="hidden" name="id" value="<?php echo $editData['id']; ?>">
                <?php endif; ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label for="class_name">Class Name *</label>
                        <input type="text" id="class_name" name="class_name" class="form-control"
                               value="<?php echo $editData ? htmlspecialchars($editData['class_name']) : ''; ?>"
                               required placeholder="e.g., Nursery, Class 1, Class 10">
                    </div>

                    <div class="form-group">
                        <label for="admission_fee">Admission Fee (₹)</label>
                        <input type="number" id="admission_fee" name="admission_fee" class="form-control"
                               step="0.01" value="<?php echo $editData ? $editData['admission_fee'] : '0.00'; ?>"
                               onchange="calculateTotal()" placeholder="0.00">
                    </div>

                    <div class="form-group">
                        <label for="tuition_fee">Tuition Fee (₹)</label>
                        <input type="number" id="tuition_fee" name="tuition_fee" class="form-control"
                               step="0.01" value="<?php echo $editData ? $editData['tuition_fee'] : '0.00'; ?>"
                               onchange="calculateTotal()" placeholder="0.00">
                    </div>

                    <div class="form-group">
                        <label for="annual_charges">Annual Charges (₹)</label>
                        <input type="number" id="annual_charges" name="annual_charges" class="form-control"
                               step="0.01" value="<?php echo $editData ? $editData['annual_charges'] : '0.00'; ?>"
                               onchange="calculateTotal()" placeholder="0.00">
                    </div>

                    <div class="form-group">
                        <label for="total_fee">Total Fee (₹) *</label>
                        <input type="number" id="total_fee" name="total_fee" class="form-control"
                               step="0.01" value="<?php echo $editData ? $editData['total_fee'] : '0.00'; ?>"
                               required placeholder="0.00">
                        <small style="color: var(--text-muted); font-size: 0.875rem; display: block; margin-top: 0.5rem;">
                            Auto-calculated or can be entered manually
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="display_order">Display Order</label>
                        <input type="number" id="display_order" name="display_order" class="form-control"
                               value="<?php echo $editData ? $editData['display_order'] : '0'; ?>"
                               min="0" placeholder="0">
                    </div>

                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label for="notes">Notes (Optional)</label>
                        <textarea id="notes" name="notes" class="form-control" rows="3"
                                  placeholder="Any additional information about fees"><?php echo $editData ? htmlspecialchars($editData['notes']) : ''; ?></textarea>
                    </div>
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-<?php echo $editData ? 'save' : 'plus'; ?>"></i>
                        <?php echo $editData ? 'Update Structure' : 'Add Structure'; ?>
                    </button>
                    <?php if ($editData): ?>
                        <a href="fee-structure.php" class="btn btn-outline">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Fee Structure List -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-list"></i> All Fee Structures</h3>
        </div>
        <div class="card-body">
            <?php if (count($feeResult) > 0): ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Class</th>
                                <th>Admission Fee</th>
                                <th>Tuition Fee</th>
                                <th>Annual Charges</th>
                                <th>Total Fee</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($feeResult as $fee): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($fee['class_name']); ?></strong></td>
                                <td>₹<?php echo number_format($fee['admission_fee'], 2); ?></td>
                                <td>₹<?php echo number_format($fee['tuition_fee'], 2); ?></td>
                                <td>₹<?php echo number_format($fee['annual_charges'], 2); ?></td>
                                <td><strong>₹<?php echo number_format($fee['total_fee'], 2); ?></strong></td>
                                <td>
                                    <div style="display: flex; gap: 0.5rem;">
                                        <a href="?edit=<?php echo $fee['id']; ?>"
                                           class="btn btn-sm btn-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="?delete=<?php echo $fee['id']; ?>"
                                           class="btn btn-sm btn-danger"
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
            <?php else: ?>
                <div class="text-center" style="padding: 3rem;">
                    <i class="fas fa-money-bill-wave" style="font-size: 4rem; color: var(--border-light); margin-bottom: 1rem;"></i>
                    <p style="color: var(--text-muted);">No fee structures added yet. Add your first structure above.</p>
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
