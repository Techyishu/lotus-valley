<?php
$pageTitle = 'Manage Disclosures';
require_once 'includes/admin_header.php';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];

    try {
        // Get file path before deleting
        $stmt = $pdo->prepare("SELECT file_path FROM disclosures WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        if ($row) {
            $filePath = '../uploads/disclosures/' . $row['file_path'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $deleteStmt = $pdo->prepare("DELETE FROM disclosures WHERE id = ?");
        $deleteStmt->execute([$id]);
        $_SESSION['success'] = 'Disclosure deleted successfully!';
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Error deleting disclosure: ' . $e->getMessage();
    }

    header('Location: disclosures.php');
    exit;
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category = trim($_POST['category']);
    $display_order = (int) $_POST['display_order'];

    $uploadError = '';
    $fileName = '';

    // Handle file upload
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/disclosures/';

        // Create directory if it doesn't exist
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileExtension = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
        $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx'];

        if (!in_array($fileExtension, $allowedExtensions)) {
            $uploadError = 'Invalid file type. Allowed: PDF, JPG, PNG, DOC, DOCX';
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
                    $oldFileStmt = $pdo->prepare("SELECT file_path FROM disclosures WHERE id = ?");
                    $oldFileStmt->execute([$id]);
                    $oldRow = $oldFileStmt->fetch();

                    if ($oldRow) {
                        $oldFilePath = '../uploads/disclosures/' . $oldRow['file_path'];
                        if (file_exists($oldFilePath)) {
                            unlink($oldFilePath);
                        }
                    }

                    $stmt = $pdo->prepare("UPDATE disclosures SET title = ?, description = ?, category = ?, file_path = ?, display_order = ? WHERE id = ?");
                    $stmt->execute([$title, $description, $category, $fileName, $display_order, $id]);
                } else {
                    $stmt = $pdo->prepare("UPDATE disclosures SET title = ?, description = ?, category = ?, display_order = ? WHERE id = ?");
                    $stmt->execute([$title, $description, $category, $display_order, $id]);
                }
                $_SESSION['success'] = 'Disclosure updated successfully!';
            } else {
                // Insert new
                if (!$fileName) {
                    $_SESSION['error'] = 'Please upload a file';
                    header('Location: disclosures.php');
                    exit;
                }

                $stmt = $pdo->prepare("INSERT INTO disclosures (title, description, category, file_path, display_order) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$title, $description, $category, $fileName, $display_order]);
                $_SESSION['success'] = 'Disclosure added successfully!';
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Database error: ' . $e->getMessage();
        }
    } else {
        $_SESSION['error'] = $uploadError;
    }

    header('Location: disclosures.php');
    exit;
}

// Fetch all disclosures
try {
    $disclosuresStmt = $pdo->query("SELECT * FROM disclosures ORDER BY category, display_order, title");
    $disclosuresResult = $disclosuresStmt->fetchAll();
} catch (PDOException $e) {
    $disclosuresResult = [];
}

// Get edit data if editing
$editData = null;
if (isset($_GET['edit'])) {
    $editId = (int) $_GET['edit'];
    try {
        $editStmt = $pdo->prepare("SELECT * FROM disclosures WHERE id = ?");
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
        <h3><i class="fas fa-file-alt"></i> Manage Disclosures</h3>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Form Section -->
    <div class="lg:col-span-1">
        <div class="card h-fit sticky top-6">
            <div class="card-header border-b border-gray-100">
                <h3><i class="fas fa-<?php echo $editData ? 'edit' : 'plus'; ?>"></i>
                    <?php echo $editData ? 'Edit Disclosure' : 'Add New Disclosure'; ?>
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
                            placeholder="e.g. Affiliation Letter">
                    </div>

                    <div class="form-group">
                        <label class="block text-gray-700 font-semibold mb-2">Category <span
                                class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-3 text-gray-400"><i class="fas fa-tag"></i></span>
                            <input type="text" name="category" class="form-control pl-10"
                                value="<?php echo $editData ? htmlspecialchars($editData['category']) : ''; ?>" required
                                placeholder="e.g. General Info">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Disclosures are grouped by category.</p>
                    </div>

                    <div class="form-group">
                        <label class="block text-gray-700 font-semibold mb-2">
                            Upload File
                            <?php echo $editData ? '<span class="text-gray-400 font-normal text-xs">(Optional to replace)</span>' : '<span class="text-red-500">*</span>'; ?>
                        </label>
                        <div
                            class="border-2 border-dashed border-gray-200 rounded-lg p-4 text-center hover:bg-gray-50 transition cursor-pointer relative">
                            <input type="file" name="file"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" <?php echo $editData ? '' : 'required'; ?>
                                onchange="this.nextElementSibling.innerText = this.files[0].name">
                            <div class="text-gray-500 text-sm pointer-events-none">
                                <i class="fas fa-cloud-upload-alt text-2xl mb-2 text-primary-400"></i><br>
                                <span class="font-medium">Click to upload</span><br>
                                <span class="text-xs text-gray-400">PDF, JPG, PNG, DOC</span>
                            </div>
                        </div>
                        <?php if ($editData && $editData['file_path']): ?>
                            <div
                                class="mt-2 flex items-center gap-2 text-sm text-blue-600 bg-blue-50 p-2 rounded border border-blue-100">
                                <i class="fas fa-paperclip"></i>
                                <a href="../uploads/disclosures/<?php echo htmlspecialchars($editData['file_path']); ?>"
                                    target="_blank" class="hover:underline truncate flex-1">
                                    View Current File
                                </a>
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
                            placeholder="Brief description..."><?php echo $editData ? htmlspecialchars($editData['description']) : ''; ?></textarea>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit" class="btn btn-primary w-full justify-center">
                            <i class="fas fa-save"></i> <?php echo $editData ? 'Update' : 'Save'; ?>
                        </button>
                        <?php if ($editData): ?>
                            <a href="disclosures.php" class="btn btn-outline w-full justify-center">
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
        <?php if (empty($disclosuresResult)): ?>
            <div class="card">
                <div class="card-body text-center py-16">
                    <i class="fas fa-folder-open text-gray-300 text-5xl mb-4"></i>
                    <p class="text-gray-500">No disclosures found.</p>
                </div>
            </div>
        <?php else: ?>
            <div class="card overflow-hidden">
                <div class="card-header border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-gray-800"><i class="fas fa-list"></i> All Disclosures</h3>
                    <span class="badge badge-secondary"><?php echo count($disclosuresResult); ?> files</span>
                </div>
                <div class="table-responsive">
                    <table class="table w-full">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600 uppercase text-xs tracking-wider text-left">
                                <th class="px-6 py-3 font-semibold">Title</th>
                                <th class="px-6 py-3 font-semibold">File</th>
                                <th class="px-6 py-3 font-semibold w-20 text-center">Order</th>
                                <th class="px-6 py-3 font-semibold text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php
                            $currentCategory = '';
                            foreach ($disclosuresResult as $disclosure):
                                if ($disclosure['category'] !== $currentCategory):
                                    $currentCategory = $disclosure['category'];
                                    ?>
                                    <tr class="bg-primary-50">
                                        <td colspan="4" class="px-6 py-2">
                                            <div
                                                class="flex items-center gap-2 text-primary-700 font-bold text-sm uppercase tracking-wide">
                                                <i class="fas fa-folder"></i> <?php echo htmlspecialchars($currentCategory); ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-gray-800">
                                            <?php echo htmlspecialchars($disclosure['title']); ?></div>
                                        <?php if ($disclosure['description']): ?>
                                            <div class="text-xs text-gray-500 mt-1">
                                                <?php echo htmlspecialchars($disclosure['description']); ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="../uploads/disclosures/<?php echo htmlspecialchars($disclosure['file_path']); ?>"
                                            target="_blank"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-blue-50 text-blue-700 hover:bg-blue-100 transition">
                                            <i class="fas fa-file-pdf"></i> View File
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span
                                            class="inline-flex items-center justify-center w-6 h-6 rounded bg-gray-100 text-gray-600 text-xs font-bold">
                                            <?php echo $disclosure['display_order']; ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="?edit=<?php echo $disclosure['id']; ?>"
                                                class="w-8 h-8 rounded-full flex items-center justify-center text-blue-600 hover:bg-blue-50 transition"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="?delete=<?php echo $disclosure['id']; ?>"
                                                class="w-8 h-8 rounded-full flex items-center justify-center text-red-600 hover:bg-red-50 transition"
                                                onclick="return confirm('Are you sure you want to delete this disclosure?')"
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

<?php require_once 'includes/admin_footer.php'; ?>