<?php
$pageTitle = 'Manage Sports';
require_once 'includes/admin_header.php';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];

    try {
        $stmt = $pdo->prepare("DELETE FROM sports WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION['success'] = 'Sports activity deleted successfully!';
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Error deleting sports activity: ' . $e->getMessage();
    }

    header('Location: sports.php');
    exit;
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $icon = trim($_POST['icon']);
    $display_order = (int)$_POST['display_order'];

    try {
        if ($id > 0) {
            $stmt = $pdo->prepare("UPDATE sports SET title = ?, description = ?, icon = ?, display_order = ? WHERE id = ?");
            $stmt->execute([$title, $description, $icon, $display_order, $id]);
            $_SESSION['success'] = 'Sports activity updated successfully!';
        } else {
            $stmt = $pdo->prepare("INSERT INTO sports (title, description, icon, display_order) VALUES (?, ?, ?, ?)");
            $stmt->execute([$title, $description, $icon, $display_order]);
            $_SESSION['success'] = 'Sports activity added successfully!';
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Database error: ' . $e->getMessage();
    }

    header('Location: sports.php');
    exit;
}

// Fetch all sports activities
try {
    $sportsStmt = $pdo->query("SELECT * FROM sports ORDER BY display_order, title");
    $sportsResult = $sportsStmt->fetchAll();
} catch (PDOException $e) {
    $sportsResult = [];
}

// Get edit data if editing
$editData = null;
if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    try {
        $editStmt = $pdo->prepare("SELECT * FROM sports WHERE id = ?");
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
                <?php echo $editData ? 'Edit Sports Activity' : 'Add Sports Activity'; ?>
            </h3>
        </div>
        <div class="card-body">
            <form method="POST">
                <?php if ($editData): ?>
                    <input type="hidden" name="id" value="<?php echo $editData['id']; ?>">
                <?php endif; ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label for="title">Title *</label>
                        <input type="text" id="title" name="title" class="form-control"
                               value="<?php echo $editData ? htmlspecialchars($editData['title']) : ''; ?>"
                               required placeholder="e.g., Cricket, Football, Basketball">
                    </div>

                    <div class="form-group">
                        <label for="icon">Icon (Font Awesome class)</label>
                        <input type="text" id="icon" name="icon" class="form-control"
                               value="<?php echo $editData ? htmlspecialchars($editData['icon']) : 'fa-trophy'; ?>"
                               placeholder="e.g., fa-futbol, fa-basketball-ball, fa-trophy">
                        <small style="color: var(--text-muted); font-size: 0.875rem; display: block; margin-top: 0.5rem;">
                            Use Font Awesome icon class (without "fas fa-")
                        </small>
                    </div>

                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label for="description">Description *</label>
                        <textarea id="description" name="description" class="form-control" rows="5"
                                  required placeholder="Detailed description of the sports activity"><?php echo $editData ? htmlspecialchars($editData['description']) : ''; ?></textarea>
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
                        <?php echo $editData ? 'Update Activity' : 'Add Activity'; ?>
                    </button>
                    <?php if ($editData): ?>
                        <a href="sports.php" class="btn btn-outline">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Sports List -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-list"></i> All Sports Activities</h3>
        </div>
        <div class="card-body">
            <?php if (count($sportsResult) > 0): ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Icon</th>
                                <th>Description</th>
                                <th>Order</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sportsResult as $sport): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($sport['title']); ?></strong></td>
                                <td>
                                    <?php if ($sport['icon']): ?>
                                        <i class="fas fa-<?php echo htmlspecialchars($sport['icon']); ?>"></i>
                                        <?php echo htmlspecialchars($sport['icon']); ?>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small><?php echo htmlspecialchars(substr($sport['description'], 0, 100)); ?>...</small>
                                </td>
                                <td><?php echo $sport['display_order']; ?></td>
                                <td>
                                    <div style="display: flex; gap: 0.5rem;">
                                        <a href="?edit=<?php echo $sport['id']; ?>"
                                           class="btn btn-sm btn-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="?delete=<?php echo $sport['id']; ?>"
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Are you sure you want to delete this sports activity?')"
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
                    <i class="fas fa-running" style="font-size: 4rem; color: var(--border-light); margin-bottom: 1rem;"></i>
                    <p style="color: var(--text-muted);">No sports activities added yet. Add your first activity above.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

<?php include 'includes/admin_footer.php'; ?>
