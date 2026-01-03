<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';
requireLogin();

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];

    try {
        // Get file path before deleting
        $stmt = $pdo->prepare("SELECT file_path FROM slc WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        if ($row) {
            $filePath = '../uploads/slc/' . $row['file_path'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $deleteStmt = $pdo->prepare("DELETE FROM slc WHERE id = ?");
        $deleteStmt->execute([$id]);
        $_SESSION['success'] = 'School Leaving Certificate deleted successfully!';
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Error deleting certificate: ' . $e->getMessage();
    }

    header('Location: slc.php');
    exit;
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $display_order = (int) $_POST['display_order'];

    $uploadError = '';
    $fileName = '';
    $fileType = 'image';

    // Handle file upload
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/slc/';

        // Create directory if it doesn't exist
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileExtension = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));

        // Determine file type
        if ($fileExtension === 'pdf') {
            $fileType = 'pdf';
            $allowedExtensions = ['pdf'];
        } else {
            $fileType = 'image';
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        }

        if (!in_array($fileExtension, $allowedExtensions)) {
            if ($fileType === 'pdf') {
                $uploadError = 'Invalid file type for PDF. Only PDF files allowed.';
            } else {
                $uploadError = 'Invalid file type for image. Allowed: JPG, PNG, GIF';
            }
        } else {
            $fileName = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $_FILES['file']['name']);
            $targetPath = $uploadDir . $fileName;

            if (!move_uploaded_file($_FILES['file']['tmp_name'], $targetPath)) {
                $uploadError = 'Error uploading file';
            }
        }
    }

    if (!$uploadError) {
        try {
            if ($id > 0) {
                // Update existing
                if ($fileName) {
                    // Delete old file
                    $oldFileStmt = $pdo->prepare("SELECT file_path FROM slc WHERE id = ?");
                    $oldFileStmt->execute([$id]);
                    $oldRow = $oldFileStmt->fetch();

                    if ($oldRow) {
                        $oldFilePath = '../uploads/slc/' . $oldRow['file_path'];
                        if (file_exists($oldFilePath)) {
                            unlink($oldFilePath);
                        }
                    }

                    $stmt = $pdo->prepare("UPDATE slc SET title = ?, description = ?, file_path = ?, file_type = ?, display_order = ? WHERE id = ?");
                    $stmt->execute([$title, $description, $fileName, $fileType, $display_order, $id]);
                } else {
                    $stmt = $pdo->prepare("UPDATE slc SET title = ?, description = ?, display_order = ? WHERE id = ?");
                    $stmt->execute([$title, $description, $display_order, $id]);
                }
                $_SESSION['success'] = 'School Leaving Certificate updated successfully!';
            } else {
                // Insert new
                if (!$fileName) {
                    $_SESSION['error'] = 'Please upload a file';
                    header('Location: slc.php');
                    exit;
                }

                $stmt = $pdo->prepare("INSERT INTO slc (title, description, file_path, file_type, display_order) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$title, $description, $fileName, $fileType, $display_order]);
                $_SESSION['success'] = 'School Leaving Certificate added successfully!';
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Database error: ' . $e->getMessage();
        }
    } else {
        $_SESSION['error'] = $uploadError;
    }

    header('Location: slc.php');
    exit;
}

$pageTitle = 'Manage School Leaving Certificates';
require_once 'includes/admin_header.php';

// Fetch all SLC items
try {
    $slcStmt = $pdo->query("SELECT * FROM slc ORDER BY display_order, title");
    $slcResult = $slcStmt->fetchAll();
} catch (PDOException $e) {
    $slcResult = [];
}

// Get edit data if editing
$editData = null;
if (isset($_GET['edit'])) {
    $editId = (int) $_GET['edit'];
    try {
        $editStmt = $pdo->prepare("SELECT * FROM slc WHERE id = ?");
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
        <h3><i class="fas fa-certificate"></i> Manage School Leaving Certificates</h3>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Form Section -->
    <div class="lg:col-span-1">
        <div class="card h-fit sticky top-6">
            <div class="card-header border-b border-gray-100">
                <h3><i class="fas fa-<?php echo $editData ? 'edit' : 'plus'; ?>"></i>
                    <?php echo $editData ? 'Edit Certificate' : 'Add New Certificate'; ?>
                </h3>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data" class="space-y-4">
                    <?php if ($editData): ?>
                        <input type="hidden" name="id" value="<?php echo $editData['id']; ?>">
                    <?php endif; ?>

                    <div class="form-group">
                        <label class="block text-gray-700 font-semibold mb-2">Title <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="title" class="form-control"
                            value="<?php echo $editData ? htmlspecialchars($editData['title']) : ''; ?>" required
                            placeholder="e.g. Class 10 - 2024">
                    </div>

                    <div class="form-group">
                        <label class="block text-gray-700 font-semibold mb-2">
                            Upload Certificate
                            <?php echo $editData ? '<span class="text-gray-400 font-normal text-xs">(Optional to replace)</span>' : '<span class="text-red-500">*</span>'; ?>
                        </label>
                        <div
                            class="border-2 border-dashed border-gray-200 rounded-lg p-4 text-center hover:bg-gray-50 transition cursor-pointer relative">
                            <input type="file" name="file"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                accept=".pdf,.jpg,.jpeg,.png,.gif" <?php echo $editData ? '' : 'required'; ?>
                                onchange="this.nextElementSibling.innerText = this.files[0].name">
                            <div class="text-gray-500 text-sm pointer-events-none">
                                <i class="fas fa-cloud-upload-alt text-2xl mb-2 text-primary-400"></i><br>
                                <span class="font-medium">Click to upload</span><br>
                                <span class="text-xs text-gray-400">PDF, JPG, PNG, GIF</span>
                            </div>
                        </div>
                        <?php if ($editData && $editData['file_path']): ?>
                            <div class="mt-2 flex items-center gap-2 text-sm">
                                <?php if ($editData['file_type'] === 'pdf'): ?>
                                    <a href="../uploads/slc/<?php echo htmlspecialchars($editData['file_path']); ?>"
                                        target="_blank"
                                        class="flex items-center gap-2 px-3 py-2 rounded-lg bg-red-50 text-red-600 border border-red-100 hover:bg-red-100 transition w-full">
                                        <i class="fas fa-file-pdf"></i> View Current PDF
                                    </a>
                                <?php else: ?>
                                    <a href="../uploads/slc/<?php echo htmlspecialchars($editData['file_path']); ?>"
                                        target="_blank"
                                        class="flex items-center gap-2 px-3 py-2 rounded-lg bg-blue-50 text-blue-600 border border-blue-100 hover:bg-blue-100 transition w-full">
                                        <i class="fas fa-image"></i> View Current Image
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label class="block text-gray-700 font-semibold mb-2">Display Order</label>
                        <input type="number" name="display_order" class="form-control"
                            value="<?php echo $editData ? $editData['display_order'] : '0'; ?>" min="0" placeholder="0">
                    </div>

                    <div class="form-group">
                        <label class="block text-gray-700 font-semibold mb-2">Description</label>
                        <textarea name="description" class="form-control" rows="3"
                            placeholder="Additional info..."><?php echo $editData ? htmlspecialchars($editData['description']) : ''; ?></textarea>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit" class="btn btn-primary w-full justify-center">
                            <i class="fas fa-save"></i> <?php echo $editData ? 'Update' : 'Save'; ?>
                        </button>
                        <?php if ($editData): ?>
                            <a href="slc.php" class="btn btn-outline w-full justify-center">
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
        <?php if (empty($slcResult)): ?>
            <div class="card">
                <div class="card-body text-center py-12">
                    <div class="w-20 h-20 bg-teal-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-certificate text-teal-600 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">No SLC Certificates Found</h3>
                    <p class="text-gray-500">Upload a new School Leaving Certificate to display.</p>
                </div>
            </div>
        <?php else: ?>
            <div class="card overflow-hidden">
                <div class="card-header border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-gray-800"><i class="fas fa-list"></i> All Certificates</h3>
                    <span class="badge badge-secondary shadow-sm"><?php echo count($slcResult); ?> files</span>
                </div>
                <div class="table-responsive">
                    <table class="table w-full">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600 uppercase text-xs tracking-wider text-left">
                                <th class="px-6 py-3 font-semibold">Details</th>
                                <th class="px-6 py-3 font-semibold w-24 text-center">Type</th>
                                <th class="px-6 py-3 font-semibold w-20 text-center">Order</th>
                                <th class="px-6 py-3 font-semibold text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php foreach ($slcResult as $item): ?>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-800 text-base mb-1">
                                            <?php echo htmlspecialchars($item['title']); ?>
                                        </div>
                                        <?php if ($item['description']): ?>
                                            <div class="text-xs text-gray-500 line-clamp-1">
                                                <?php echo htmlspecialchars($item['description']); ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span
                                            class="px-2 py-1 rounded-md text-xs font-bold uppercase tracking-wide <?php echo $item['file_type'] === 'pdf' ? 'bg-red-50 text-red-600' : 'bg-green-50 text-green-600'; ?>">
                                            <?php echo strtoupper($item['file_type']); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span
                                            class="inline-flex items-center justify-center w-6 h-6 rounded bg-gray-100 text-gray-600 text-xs font-bold">
                                            <?php echo $item['display_order']; ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="../uploads/slc/<?php echo htmlspecialchars($item['file_path']); ?>"
                                                target="_blank"
                                                class="w-8 h-8 rounded-full flex items-center justify-center text-gray-500 hover:bg-gray-100 transition"
                                                title="View File">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="?edit=<?php echo $item['id']; ?>"
                                                class="w-8 h-8 rounded-full flex items-center justify-center text-blue-600 hover:bg-blue-50 transition"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="?delete=<?php echo $item['id']; ?>"
                                                class="w-8 h-8 rounded-full flex items-center justify-center text-red-600 hover:bg-red-50 transition"
                                                onclick="return confirm('Are you sure you want to delete this certificate?')"
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