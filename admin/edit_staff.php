<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';
requireLogin();

if (!isset($_GET['id'])) {
    header('Location: staff.php');
    exit;
}

$pageTitle = 'Edit Staff Member';
require_once 'includes/admin_header.php';
global $pdo;

$id = (int) ($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: staff.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM staff WHERE id = ?");
$stmt->execute([$id]);
$staff = $stmt->fetch();
if (!$staff) {
    header('Location: staff.php');
    exit;
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && verifyCSRFToken($_POST['csrf_token'])) {
    $photoFilename = $staff['photo'];
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        if ($photoFilename)
            deleteFile('../uploads/staff/' . $photoFilename);
        $upload = uploadFile($_FILES['photo'], '../uploads/staff');
        if ($upload['success'])
            $photoFilename = $upload['filename'];
    }

    try {
        $stmt = $pdo->prepare("UPDATE staff SET name=?, photo=?, designation=?, department=?, qualification=?, experience=?, email=?, phone=?, specialization=?, display_order=? WHERE id=?");
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
            (int) $_POST['display_order'],
            $id
        ]);
        $message = 'Staff member updated';
        $stmt = $pdo->prepare("SELECT * FROM staff WHERE id = ?");
        $stmt->execute([$id]);
        $staff = $stmt->fetch();
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
            <i class="fas fa-user-edit text-primary-600"></i> Edit Staff Member
        </h3>
    </div>
    <div class="card-body">
        <?php if ($message): ?>
            <div class="alert alert-success mb-6">
                <i class="fas fa-check-circle"></i>
                <span><?php echo $message; ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="space-y-6">
            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">

            <?php if ($staff['photo']): ?>
                <div class="form-group">
                    <label>Current Photo</label>
                    <div class="mt-2">
                        <img src="../uploads/staff/<?php echo clean($staff['photo']); ?>"
                            class="w-24 h-24 rounded-lg object-cover border border-gray-200 shadow-sm">
                    </div>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <label>Name *</label>
                <input type="text" name="name" required value="<?php echo clean($staff['name']); ?>"
                    class="form-control" placeholder="Enter staff name">
            </div>

            <div class="form-group">
                <label>Photo (Leave empty to keep current)</label>
                <input type="file" name="photo" accept="image/*" class="form-control">
                <p class="text-xs text-gray-500 mt-1">Recommended size: 300x300px. Max: 2MB.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group">
                    <label>Designation *</label>
                    <input type="text" name="designation" required value="<?php echo clean($staff['designation']); ?>"
                        class="form-control">
                </div>
                <div class="form-group">
                    <label>Department *</label>
                    <select name="department" required class="form-control">
                        <option value="Administration" <?php echo $staff['department'] === 'Administration' ? 'selected' : ''; ?>>Administration</option>
                        <option value="Science" <?php echo $staff['department'] === 'Science' ? 'selected' : ''; ?>>
                            Science</option>
                        <option value="Mathematics" <?php echo $staff['department'] === 'Mathematics' ? 'selected' : ''; ?>>Mathematics</option>
                        <option value="English" <?php echo $staff['department'] === 'English' ? 'selected' : ''; ?>>
                            English</option>
                        <option value="Social Science" <?php echo $staff['department'] === 'Social Science' ? 'selected' : ''; ?>>Social Science</option>
                        <option value="Sports" <?php echo $staff['department'] === 'Sports' ? 'selected' : ''; ?>>Sports
                        </option>
                        <option value="Others" <?php echo $staff['department'] === 'Others' ? 'selected' : ''; ?>>Others
                        </option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group">
                    <label>Qualification</label>
                    <input type="text" name="qualification" value="<?php echo clean($staff['qualification']); ?>"
                        class="form-control">
                </div>
                <div class="form-group">
                    <label>Experience</label>
                    <input type="text" name="experience" value="<?php echo clean($staff['experience']); ?>"
                        class="form-control">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="<?php echo clean($staff['email']); ?>" class="form-control">
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="tel" name="phone" value="<?php echo clean($staff['phone']); ?>" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label>Specialization</label>
                <textarea name="specialization" rows="3"
                    class="form-control"><?php echo clean($staff['specialization']); ?></textarea>
            </div>

            <div class="form-group">
                <label>Display Order</label>
                <input type="number" name="display_order" value="<?php echo clean($staff['display_order']); ?>"
                    class="form-control">
            </div>

            <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Staff Member
                </button>
                <a href="staff.php" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php require_once 'includes/admin_footer.php'; ?>