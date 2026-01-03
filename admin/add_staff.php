<?php
$pageTitle = 'Add Staff Member';
require_once 'includes/admin_header.php';
global $pdo;

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && verifyCSRFToken($_POST['csrf_token'])) {
    $photoFilename = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $upload = uploadFile($_FILES['photo'], '../uploads/staff');
        if ($upload['success'])
            $photoFilename = $upload['filename'];
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO staff (name, photo, designation, department, qualification, experience, email, phone, specialization, display_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            trim($_POST['name']),
            $photoFilename,
            trim($_POST['designation']),
            trim($_POST['department']),
            trim($_POST['qualification']),
            trim($_POST['experience']),
            trim($_POST['email']),
            trim($_POST['phone']),
            trim($_POST['specialization']),
            (int) $_POST['display_order']
        ]);
        echo "<script>showToast('Staff member added', 'success'); setTimeout(() => window.location.href = 'staff.php', 1500);</script>";
    } catch (PDOException $e) {
        $message = 'Error: ' . $e->getMessage();
    }
}
?>

<div class="mb-6">
    <a href="staff.php" class="text-primary-600 hover:text-primary-700 flex items-center gap-2 transition-colors">
        <i class="fas fa-arrow-left"></i> Back to Staff
    </a>
</div>

<div class="card max-w-3xl mx-auto">
    <div class="card-header">
        <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
            <i class="fas fa-user-plus text-primary-600"></i> Add Staff Member
        </h3>
    </div>
    <div class="card-body">
        <?php if ($message): ?>
            <div class="alert alert-error mb-6">
                <i class="fas fa-exclamation-circle"></i>
                <span><?php echo $message; ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="space-y-6">
            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">

            <div class="form-group">
                <label>Name *</label>
                <input type="text" name="name" required class="form-control" placeholder="Enter staff name">
            </div>

            <div class="form-group">
                <label>Photo</label>
                <input type="file" name="photo" accept="image/*" class="form-control">
                <p class="text-xs text-gray-500 mt-1">Recommended size: 300x300px. Max: 2MB.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group">
                    <label>Designation *</label>
                    <input type="text" name="designation" required class="form-control"
                        placeholder="e.g. Senior Teacher">
                </div>
                <div class="form-group">
                    <label>Department *</label>
                    <select name="department" required class="form-control">
                        <option value="">Select Department</option>
                        <option value="Administration">Administration</option>
                        <option value="Science">Science</option>
                        <option value="Mathematics">Mathematics</option>
                        <option value="English">English</option>
                        <option value="Social Science">Social Science</option>
                        <option value="Sports">Sports</option>
                        <option value="Others">Others</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group">
                    <label>Qualification</label>
                    <input type="text" name="qualification" class="form-control" placeholder="e.g. M.Sc, B.Ed">
                </div>
                <div class="form-group">
                    <label>Experience</label>
                    <input type="text" name="experience" class="form-control" placeholder="e.g. 10 Years">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" placeholder="email@example.com">
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="tel" name="phone" class="form-control" placeholder="Mobile Number">
                </div>
            </div>

            <div class="form-group">
                <label>Specialization</label>
                <textarea name="specialization" rows="3" class="form-control"
                    placeholder="Brief description of specialization..."></textarea>
            </div>

            <div class="form-group">
                <label>Display Order</label>
                <input type="number" name="display_order" value="0" class="form-control">
            </div>

            <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Staff Member
                </button>
                <a href="staff.php" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php require_once 'includes/admin_footer.php'; ?>