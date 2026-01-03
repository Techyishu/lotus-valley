<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';
requireLogin();

$id = (int) ($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: toppers.php');
    exit;
}

$pageTitle = 'Edit Topper';
require_once 'includes/admin_header.php';

global $pdo;

// Get topper data
try {
    $stmt = $pdo->prepare("SELECT * FROM toppers WHERE id = ?");
    $stmt->execute([$id]);
    $topper = $stmt->fetch();

    if (!$topper) {
        header('Location: toppers.php');
        exit;
    }
} catch (PDOException $e) {
    header('Location: toppers.php');
    exit;
}

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (verifyCSRFToken($_POST['csrf_token'])) {
        $name = trim($_POST['name']);
        $marks = trim($_POST['marks']);
        $percentage = (float) $_POST['percentage'];
        $year = (int) $_POST['year'];
        $board = trim($_POST['board']);
        $class = trim($_POST['class']);
        $achievement = trim($_POST['achievement']);

        $errors = [];

        if (empty($name))
            $errors[] = 'Name is required';
        if (empty($marks))
            $errors[] = 'Marks are required';
        if ($percentage <= 0)
            $errors[] = 'Valid percentage is required';
        if ($year <= 0)
            $errors[] = 'Valid year is required';
        if (empty($board))
            $errors[] = 'Board is required';
        if (empty($class))
            $errors[] = 'Class is required';

        // Handle photo upload
        $photoFilename = $topper['photo'];
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            // Delete old photo
            if ($photoFilename) {
                deleteFile('../uploads/toppers/' . $photoFilename);
            }

            $upload = uploadFile($_FILES['photo'], '../uploads/toppers');
            if ($upload['success']) {
                $photoFilename = $upload['filename'];
            } else {
                $errors[] = $upload['message'];
            }
        }

        if (empty($errors)) {
            try {
                $stmt = $pdo->prepare("
                    UPDATE toppers 
                    SET name = ?, photo = ?, marks = ?, percentage = ?, year = ?, board = ?, class = ?, achievement = ? 
                    WHERE id = ?
                ");
                $stmt->execute([$name, $photoFilename, $marks, $percentage, $year, $board, $class, $achievement, $id]);

                $message = 'Topper updated successfully';
                $messageType = 'success';

                // Refresh topper data
                $stmt = $pdo->prepare("SELECT * FROM toppers WHERE id = ?");
                $stmt->execute([$id]);
                $topper = $stmt->fetch();
            } catch (PDOException $e) {
                $message = 'Database error: ' . $e->getMessage();
                $messageType = 'error';
            }
        } else {
            $message = implode('<br>', $errors);
            $messageType = 'error';
        }
    } else {
        $message = 'Invalid security token';
        $messageType = 'error';
    }
}
?>

<div class="mb-6">
    <a href="toppers.php" class="text-primary-600 hover:text-primary-700 flex items-center gap-2 transition-colors">
        <i class="fas fa-arrow-left"></i> Back to Toppers
    </a>
</div>

<div class="card max-w-3xl mx-auto">
    <div class="card-header">
        <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
            <i class="fas fa-trophy text-amber-500"></i> Edit Topper
        </h3>
    </div>
    <div class="card-body">
        <?php if ($message): ?>
            <div class="alert <?php echo $messageType === 'success' ? 'alert-success' : 'alert-error'; ?> mb-6">
                <i class="fas <?php echo $messageType === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
                <span><?php echo $message; ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="space-y-6">
            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">

            <?php if ($topper['photo']): ?>
                <div class="form-group">
                    <label>Current Photo</label>
                    <div class="mt-2">
                        <img src="../uploads/toppers/<?php echo clean($topper['photo']); ?>"
                            alt="<?php echo clean($topper['name']); ?>"
                            class="w-24 h-24 rounded-lg object-cover border border-gray-200 shadow-sm">
                    </div>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <label>Student Name *</label>
                <input type="text" name="name" required value="<?php echo clean($topper['name']); ?>"
                    class="form-control">
            </div>

            <div class="form-group">
                <label>Photo (Leave empty to keep current)</label>
                <input type="file" name="photo" accept="image/*" class="form-control">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group">
                    <label>Marks Obtained *</label>
                    <input type="text" name="marks" required value="<?php echo clean($topper['marks']); ?>"
                        class="form-control">
                </div>
                <div class="form-group">
                    <label>Percentage *</label>
                    <input type="number" name="percentage" step="0.01" required
                        value="<?php echo clean($topper['percentage']); ?>" class="form-control">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="form-group">
                    <label>Year *</label>
                    <input type="number" name="year" required value="<?php echo clean($topper['year']); ?>"
                        class="form-control">
                </div>
                <div class="form-group">
                    <label>Board *</label>
                    <select name="board" required class="form-control">
                        <option value="">Select Board</option>
                        <option value="CBSE" <?php echo $topper['board'] === 'CBSE' ? 'selected' : ''; ?>>CBSE</option>
                        <option value="ICSE" <?php echo $topper['board'] === 'ICSE' ? 'selected' : ''; ?>>ICSE</option>
                        <option value="State Board" <?php echo $topper['board'] === 'State Board' ? 'selected' : ''; ?>>
                            State Board</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Class *</label>
                    <select name="class" required class="form-control">
                        <option value="">Select Class</option>
                        <option value="Class 10" <?php echo $topper['class'] === 'Class 10' ? 'selected' : ''; ?>>Class 10
                        </option>
                        <option value="Class 12" <?php echo $topper['class'] === 'Class 12' ? 'selected' : ''; ?>>Class 12
                        </option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Achievement / Special Note</label>
                <textarea name="achievement" rows="3"
                    class="form-control"><?php echo clean($topper['achievement'] ?? ''); ?></textarea>
            </div>

            <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Topper
                </button>
                <a href="toppers.php" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php require_once 'includes/admin_footer.php'; ?>