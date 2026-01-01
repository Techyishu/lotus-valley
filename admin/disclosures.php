<?php
require_once 'includes/admin_auth.php';
require_once '../includes/functions.php';

$pageTitle = 'Manage Disclosures';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
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
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category = trim($_POST['category']);
    $display_order = (int)$_POST['display_order'];
    
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
    $editId = (int)$_GET['edit'];
    try {
        $editStmt = $pdo->prepare("SELECT * FROM disclosures WHERE id = ?");
        $editStmt->execute([$editId]);
        $editData = $editStmt->fetch();
    } catch (PDOException $e) {
        $editData = null;
    }
}

include 'includes/admin_header.php';
?>

<div class="admin-content">
    <div class="admin-header">
        <h1><i class="fas fa-file-alt"></i> Manage Mandatory Disclosures</h1>
        <p>Upload and manage mandatory disclosure documents</p>
    </div>

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
                <?php echo $editData ? 'Edit Disclosure' : 'Add New Disclosure'; ?>
            </h3>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <?php if ($editData): ?>
                    <input type="hidden" name="id" value="<?php echo $editData['id']; ?>">
                <?php endif; ?>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label for="title">Disclosure Title *</label>
                        <input type="text" id="title" name="title" class="form-control" 
                               value="<?php echo $editData ? htmlspecialchars($editData['title']) : ''; ?>" 
                               required placeholder="e.g., Affiliation Letter">
                    </div>

                    <div class="form-group">
                        <label for="category">Category *</label>
                        <input type="text" id="category" name="category" class="form-control" 
                               value="<?php echo $editData ? htmlspecialchars($editData['category']) : ''; ?>" 
                               required placeholder="e.g., Certificates, Documents, Reports">
                        <small style="color: var(--text-muted); font-size: 0.875rem; display: block; margin-top: 0.5rem;">
                            Disclosures will be grouped by category on the frontend
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="file">Upload File <?php echo $editData ? '(Upload only to replace existing)' : '*'; ?></label>
                        <input type="file" id="file" name="file" class="form-control" 
                               accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                               <?php echo $editData ? '' : 'required'; ?>>
                        <small style="color: var(--text-muted); font-size: 0.875rem; display: block; margin-top: 0.5rem;">
                            Accepted formats: PDF, JPG, PNG, DOC, DOCX
                        </small>
                        <?php if ($editData && $editData['file_path']): ?>
                            <div style="margin-top: 0.5rem;">
                                <a href="../uploads/disclosures/<?php echo htmlspecialchars($editData['file_path']); ?>" 
                                   target="_blank" class="btn btn-sm btn-outline">
                                    <i class="fas fa-eye"></i> View Current File
                                </a>
                            </div>
                        <?php endif; ?>
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

                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label for="description">Description (Optional)</label>
                        <textarea id="description" name="description" class="form-control" rows="3" 
                                  placeholder="Brief description of the document"><?php echo $editData ? htmlspecialchars($editData['description']) : ''; ?></textarea>
                    </div>
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-<?php echo $editData ? 'save' : 'plus'; ?>"></i> 
                        <?php echo $editData ? 'Update Disclosure' : 'Add Disclosure'; ?>
                    </button>
                    <?php if ($editData): ?>
                        <a href="disclosures.php" class="btn btn-outline">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Disclosures List -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-list"></i> All Disclosures</h3>
        </div>
        <div class="card-body">
            <?php if (count($disclosuresResult) > 0): ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Category</th>
                                <th>File</th>
                                <th>Order</th>
                                <th>Uploaded</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $currentCategory = '';
                            foreach ($disclosuresResult as $disclosure): 
                                // Show category header
                                if ($disclosure['category'] !== $currentCategory):
                                    $currentCategory = $disclosure['category'];
                            ?>
                                <tr style="background: rgba(16, 185, 129, 0.1);">
                                    <td colspan="6" style="font-weight: 600; color: #10B981;">
                                        <i class="fas fa-folder"></i> <?php echo htmlspecialchars($currentCategory); ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <td>
                                    <strong><?php echo htmlspecialchars($disclosure['title']); ?></strong>
                                    <?php if ($disclosure['description']): ?>
                                        <br><small style="color: var(--text-muted);"><?php echo htmlspecialchars($disclosure['description']); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($disclosure['category']); ?></td>
                                <td>
                                    <a href="../uploads/disclosures/<?php echo htmlspecialchars($disclosure['file_path']); ?>" 
                                       target="_blank" class="btn btn-sm btn-outline">
                                        <i class="fas fa-external-link-alt"></i> View
                                    </a>
                                </td>
                                <td><?php echo $disclosure['display_order']; ?></td>
                                <td><?php echo date('M d, Y', strtotime($disclosure['created_at'])); ?></td>
                                <td>
                                    <div style="display: flex; gap: 0.5rem;">
                                        <a href="?edit=<?php echo $disclosure['id']; ?>" 
                                           class="btn btn-sm btn-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="?delete=<?php echo $disclosure['id']; ?>" 
                                           class="btn btn-sm btn-danger" 
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
            <?php else: ?>
                <div class="text-center" style="padding: 3rem;">
                    <i class="fas fa-file-alt" style="font-size: 4rem; color: var(--border-light); margin-bottom: 1rem;"></i>
                    <p style="color: var(--text-muted);">No disclosures added yet. Add your first disclosure above.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/admin_footer.php'; ?>