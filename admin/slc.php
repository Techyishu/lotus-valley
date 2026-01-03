<?php
$pageTitle = 'Manage School Leaving Certificates';
require_once 'includes/admin_header.php';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];

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
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $display_order = (int)$_POST['display_order'];

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
    $editId = (int)$_GET['edit'];
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
                <?php echo $editData ? 'Edit Certificate' : 'Add School Leaving Certificate'; ?>
            </h3>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <?php if ($editData): ?>
                    <input type="hidden" name="id" value="<?php echo $editData['id']; ?>">
                <?php endif; ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label for="title">Certificate Title *</label>
                        <input type="text" id="title" name="title" class="form-control"
                               value="<?php echo $editData ? htmlspecialchars($editData['title']) : ''; ?>"
                               required placeholder="e.g., Class 10 - 2024, Class 12 - 2023">
                    </div>

                    <div class="form-group">
                        <label for="file">Upload Certificate *</label>
                        <input type="file" id="file" name="file" class="form-control"
                               accept=".pdf,.jpg,.jpeg,.png,.gif"
                               <?php echo $editData ? '' : 'required'; ?>>
                        <small style="color: var(--text-muted); font-size: 0.875rem; display: block; margin-top: 0.5rem;">
                            Accepted formats: PDF, JPG, PNG, GIF
                        </small>
                        <?php if ($editData && $editData['file_path']): ?>
                            <div style="margin-top: 0.5rem;">
                                <?php if ($editData['file_type'] === 'pdf'): ?>
                                    <a href="../uploads/slc/<?php echo htmlspecialchars($editData['file_path']); ?>"
                                       target="_blank" class="btn btn-sm btn-outline">
                                        <i class="fas fa-file-pdf"></i> View Current PDF
                                    </a>
                                <?php else: ?>
                                    <a href="../uploads/slc/<?php echo htmlspecialchars($editData['file_path']); ?>"
                                       target="_blank" class="btn btn-sm btn-outline">
                                        <i class="fas fa-image"></i> View Current Image
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label for="description">Description (Optional)</label>
                        <textarea id="description" name="description" class="form-control" rows="3"
                                  placeholder="Additional information about the certificate"><?php echo $editData ? htmlspecialchars($editData['description']) : ''; ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="display_order">Display Order</label>
                        <input type="number" id="display_order" name="display_order" class="form-control"
                               value="<?php echo $editData ? $editData['display_order'] : '0'; ?>"
                               min="0" placeholder="0">
                        <small style="color: var(--text-muted); font-size: 0.875rem; display: block; margin-top: 0.5rem;">
                            Lower numbers appear first
                        </small>
                    </div>
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-<?php echo $editData ? 'save' : 'plus'; ?>"></i>
                        <?php echo $editData ? 'Update Certificate' : 'Add Certificate'; ?>
                    </button>
                    <?php if ($editData): ?>
                        <a href="slc.php" class="btn btn-outline">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Certificates List -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-list"></i> All School Leaving Certificates</h3>
        </div>
        <div class="card-body">
            <?php if (count($slcResult) > 0): ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Description</th>
                                <th>File</th>
                                <th>Order</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($slcResult as $item): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($item['title']); ?></strong></td>
                                <td>
                                    <span class="badge" style="background: <?php echo $item['file_type'] === 'pdf' ? '#EF4444' : '#10B981'; ?>; color: white; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.75rem;">
                                        <?php echo strtoupper($item['file_type']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($item['description']): ?>
                                        <small><?php echo htmlspecialchars(substr($item['description'], 0, 80)); ?>...</small>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="../uploads/slc/<?php echo htmlspecialchars($item['file_path']); ?>"
                                       target="_blank" class="btn btn-sm btn-outline">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                                <td><?php echo $item['display_order']; ?></td>
                                <td>
                                    <div style="display: flex; gap: 0.5rem;">
                                        <a href="?edit=<?php echo $item['id']; ?>"
                                           class="btn btn-sm btn-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="?delete=<?php echo $item['id']; ?>"
                                           class="btn btn-sm btn-danger"
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
            <?php else: ?>
                <div class="text-center" style="padding: 3rem;">
                    <i class="fas fa-certificate" style="font-size: 4rem; color: var(--border-light); margin-bottom: 1rem;"></i>
                    <p style="color: var(--text-muted);">No certificates added yet. Add your first certificate above.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

<?php include 'includes/admin_footer.php'; ?>
