<?php
$pageTitle = 'Manage Bus Routes';
require_once 'includes/admin_header.php';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];

    try {
        $stmt = $pdo->prepare("DELETE FROM bus_routes WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION['success'] = 'Bus route deleted successfully!';
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Error deleting bus route: ' . $e->getMessage();
    }

    header('Location: bus-routes.php');
    exit;
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $route_number = trim($_POST['route_number']);
    $route_name = trim($_POST['route_name']);
    $stops = trim($_POST['stops']);
    $bus_number = trim($_POST['bus_number']);
    $driver_name = trim($_POST['driver_name']);
    $driver_phone = trim($_POST['driver_phone']);
    $fare = !empty($_POST['fare']) ? (float)$_POST['fare'] : null;
    $display_order = (int)$_POST['display_order'];

    try {
        if ($id > 0) {
            $stmt = $pdo->prepare("UPDATE bus_routes SET route_number = ?, route_name = ?, stops = ?, bus_number = ?, driver_name = ?, driver_phone = ?, fare = ?, display_order = ? WHERE id = ?");
            $stmt->execute([$route_number, $route_name, $stops, $bus_number, $driver_name, $driver_phone, $fare, $display_order, $id]);
            $_SESSION['success'] = 'Bus route updated successfully!';
        } else {
            $stmt = $pdo->prepare("INSERT INTO bus_routes (route_number, route_name, stops, bus_number, driver_name, driver_phone, fare, display_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$route_number, $route_name, $stops, $bus_number, $driver_name, $driver_phone, $fare, $display_order]);
            $_SESSION['success'] = 'Bus route added successfully!';
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Database error: ' . $e->getMessage();
    }

    header('Location: bus-routes.php');
    exit;
}

// Fetch all bus routes
try {
    $routesStmt = $pdo->query("SELECT * FROM bus_routes ORDER BY display_order, route_number");
    $routesResult = $routesStmt->fetchAll();
} catch (PDOException $e) {
    $routesResult = [];
}

// Get edit data if editing
$editData = null;
if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    try {
        $editStmt = $pdo->prepare("SELECT * FROM bus_routes WHERE id = ?");
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
                <?php echo $editData ? 'Edit Bus Route' : 'Add Bus Route'; ?>
            </h3>
        </div>
        <div class="card-body">
            <form method="POST">
                <?php if ($editData): ?>
                    <input type="hidden" name="id" value="<?php echo $editData['id']; ?>">
                <?php endif; ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label for="route_number">Route Number *</label>
                        <input type="text" id="route_number" name="route_number" class="form-control"
                               value="<?php echo $editData ? htmlspecialchars($editData['route_number']) : ''; ?>"
                               required placeholder="e.g., R-01, R-02">
                    </div>

                    <div class="form-group">
                        <label for="route_name">Route Name *</label>
                        <input type="text" id="route_name" name="route_name" class="form-control"
                               value="<?php echo $editData ? htmlspecialchars($editData['route_name']) : ''; ?>"
                               required placeholder="e.g., Sector 15 Route, Civil Lines Route">
                    </div>

                    <div class="form-group">
                        <label for="bus_number">Bus Number</label>
                        <input type="text" id="bus_number" name="bus_number" class="form-control"
                               value="<?php echo $editData ? htmlspecialchars($editData['bus_number']) : ''; ?>"
                               placeholder="e.g., UP-14-AB-1234">
                    </div>

                    <div class="form-group">
                        <label for="driver_name">Driver Name</label>
                        <input type="text" id="driver_name" name="driver_name" class="form-control"
                               value="<?php echo $editData ? htmlspecialchars($editData['driver_name']) : ''; ?>"
                               placeholder="e.g., Ram Singh">
                    </div>

                    <div class="form-group">
                        <label for="driver_phone">Driver Phone</label>
                        <input type="text" id="driver_phone" name="driver_phone" class="form-control"
                               value="<?php echo $editData ? htmlspecialchars($editData['driver_phone']) : ''; ?>"
                               placeholder="e.g., 9876543210">
                    </div>

                    <div class="form-group">
                        <label for="fare">Monthly Fare (₹)</label>
                        <input type="number" id="fare" name="fare" class="form-control" step="0.01"
                               value="<?php echo $editData ? $editData['fare'] : ''; ?>"
                               placeholder="e.g., 1500">
                    </div>

                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label for="stops">Bus Stops *</label>
                        <textarea id="stops" name="stops" class="form-control" rows="5"
                                  required placeholder="Enter bus stops separated by commas or new lines"><?php echo $editData ? htmlspecialchars($editData['stops']) : ''; ?></textarea>
                        <small style="color: var(--text-muted); font-size: 0.875rem; display: block; margin-top: 0.5rem;">
                            Enter all bus stops on this route, separated by commas or new lines
                        </small>
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
                        <?php echo $editData ? 'Update Route' : 'Add Route'; ?>
                    </button>
                    <?php if ($editData): ?>
                        <a href="bus-routes.php" class="btn btn-outline">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Bus Routes List -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-list"></i> All Bus Routes</h3>
        </div>
        <div class="card-body">
            <?php if (count($routesResult) > 0): ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Route</th>
                                <th>Route Name</th>
                                <th>Bus Number</th>
                                <th>Driver</th>
                                <th>Fare</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($routesResult as $route): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($route['route_number']); ?></strong></td>
                                <td><?php echo htmlspecialchars($route['route_name']); ?></td>
                                <td><?php echo $route['bus_number'] ? htmlspecialchars($route['bus_number']) : '-'; ?></td>
                                <td>
                                    <?php if ($route['driver_name']): ?>
                                        <?php echo htmlspecialchars($route['driver_name']); ?>
                                        <?php if ($route['driver_phone']): ?>
                                            <br><small><?php echo htmlspecialchars($route['driver_phone']); ?></small>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $route['fare'] ? '₹' . number_format($route['fare'], 2) : '-'; ?></td>
                                <td>
                                    <div style="display: flex; gap: 0.5rem;">
                                        <a href="?edit=<?php echo $route['id']; ?>"
                                           class="btn btn-sm btn-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="?delete=<?php echo $route['id']; ?>"
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Are you sure you want to delete this bus route?')"
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
                    <i class="fas fa-bus" style="font-size: 4rem; color: var(--border-light); margin-bottom: 1rem;"></i>
                    <p style="color: var(--text-muted);">No bus routes added yet. Add your first route above.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

<?php include 'includes/admin_footer.php'; ?>
