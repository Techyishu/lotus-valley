<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';
requireLogin();

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];

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
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
    $route_number = trim($_POST['route_number']);
    $route_name = trim($_POST['route_name']);
    $stops = trim($_POST['stops']);
    $bus_number = trim($_POST['bus_number']);
    $driver_name = trim($_POST['driver_name']);
    $driver_phone = trim($_POST['driver_phone']);
    $fare = !empty($_POST['fare']) ? (float) $_POST['fare'] : null;
    $display_order = (int) $_POST['display_order'];

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

$pageTitle = 'Manage Bus Routes';
require_once 'includes/admin_header.php';

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
    $editId = (int) $_GET['edit'];
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
        <h3><i class="fas fa-bus"></i> Manage Bus Routes</h3>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Form Section -->
    <div class="lg:col-span-1">
        <div class="card h-fit sticky top-6">
            <div class="card-header border-b border-gray-100">
                <h3><i class="fas fa-<?php echo $editData ? 'edit' : 'plus'; ?>"></i>
                    <?php echo $editData ? 'Edit Bus Route' : 'Add New Route'; ?>
                </h3>
            </div>
            <div class="card-body">
                <form method="POST" class="space-y-4">
                    <?php if ($editData): ?>
                        <input type="hidden" name="id" value="<?php echo $editData['id']; ?>">
                    <?php endif; ?>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="block text-gray-700 font-semibold mb-2">Route No. <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="route_number" class="form-control"
                                value="<?php echo $editData ? htmlspecialchars($editData['route_number']) : ''; ?>"
                                required placeholder="R-01">
                        </div>

                        <div class="form-group">
                            <label class="block text-gray-700 font-semibold mb-2">Bus No.</label>
                            <input type="text" name="bus_number" class="form-control"
                                value="<?php echo $editData ? htmlspecialchars($editData['bus_number']) : ''; ?>"
                                placeholder="UP-16-...">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="block text-gray-700 font-semibold mb-2">Route Name <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="route_name" class="form-control"
                            value="<?php echo $editData ? htmlspecialchars($editData['route_name']) : ''; ?>" required
                            placeholder="e.g. Sector 15 Route">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="block text-gray-700 font-semibold mb-2">Driver Name</label>
                            <input type="text" name="driver_name" class="form-control"
                                value="<?php echo $editData ? htmlspecialchars($editData['driver_name']) : ''; ?>"
                                placeholder="Driver Name">
                        </div>

                        <div class="form-group">
                            <label class="block text-gray-700 font-semibold mb-2">Driver Phone</label>
                            <input type="text" name="driver_phone" class="form-control"
                                value="<?php echo $editData ? htmlspecialchars($editData['driver_phone']) : ''; ?>"
                                placeholder="Contact No.">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="block text-gray-700 font-semibold mb-2">Monthly Fare (₹)</label>
                        <input type="number" name="fare" class="form-control" step="0.01"
                            value="<?php echo $editData ? $editData['fare'] : ''; ?>" placeholder="e.g. 1500">
                    </div>

                    <div class="form-group">
                        <label class="block text-gray-700 font-semibold mb-2">Bus Stops <span
                                class="text-red-500">*</span></label>
                        <textarea name="stops" class="form-control" rows="5" required
                            placeholder="List stops separated by commas..."><?php echo $editData ? htmlspecialchars($editData['stops']) : ''; ?></textarea>
                        <p class="text-xs text-gray-500 mt-1">Separate stops with commas</p>
                    </div>

                    <div class="form-group">
                        <label class="block text-gray-700 font-semibold mb-2">Display Order</label>
                        <input type="number" name="display_order" class="form-control"
                            value="<?php echo $editData ? $editData['display_order'] : '0'; ?>" min="0" placeholder="0">
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit" class="btn btn-primary w-full justify-center">
                            <i class="fas fa-save"></i> <?php echo $editData ? 'Update' : 'Save'; ?>
                        </button>
                        <?php if ($editData): ?>
                            <a href="bus-routes.php" class="btn btn-outline w-full justify-center">
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
        <?php if (empty($routesResult)): ?>
            <div class="card">
                <div class="card-body text-center py-12">
                    <div class="w-20 h-20 bg-teal-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-bus-alt text-teal-600 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">No Bus Routes Found</h3>
                    <p class="text-gray-500">Add a new route to manage transportation details.</p>
                </div>
            </div>
        <?php else: ?>
            <div class="card overflow-hidden">
                <div class="card-header border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-gray-800"><i class="fas fa-list"></i> All Routes</h3>
                    <span class="badge badge-secondary shadow-sm"><?php echo count($routesResult); ?> routes</span>
                </div>
                <div class="table-responsive">
                    <table class="table w-full">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600 uppercase text-xs tracking-wider text-left">
                                <th class="px-6 py-3 font-semibold">Route Info</th>
                                <th class="px-6 py-3 font-semibold">Driver Details</th>
                                <th class="px-6 py-3 font-semibold">Fare</th>
                                <th class="px-6 py-3 font-semibold text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php foreach ($routesResult as $route): ?>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 rounded-lg bg-primary-50 text-primary-700 flex items-center justify-center font-bold border border-primary-100">
                                                <?php echo htmlspecialchars($route['route_number']); ?>
                                            </div>
                                            <div>
                                                <div class="font-bold text-gray-800">
                                                    <?php echo htmlspecialchars($route['route_name']); ?>
                                                </div>
                                                <div class="text-xs text-gray-500 mt-0.5">
                                                    <i class="fas fa-bus text-gray-400 mr-1"></i>
                                                    <?php echo $route['bus_number'] ? htmlspecialchars($route['bus_number']) : 'No Bus Assigned'; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php if ($route['driver_name']): ?>
                                            <div class="font-medium text-gray-800">
                                                <?php echo htmlspecialchars($route['driver_name']); ?>
                                            </div>
                                            <?php if ($route['driver_phone']): ?>
                                                <div class="text-xs text-gray-500 mt-0.5">
                                                    <i class="fas fa-phone-alt mr-1"></i>
                                                    <?php echo htmlspecialchars($route['driver_phone']); ?>
                                                </div>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-gray-400 text-sm">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 font-medium text-gray-700">
                                        <?php echo $route['fare'] ? '₹' . number_format($route['fare'], 2) : '-'; ?>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="?edit=<?php echo $route['id']; ?>"
                                                class="w-8 h-8 rounded-full flex items-center justify-center text-blue-600 hover:bg-blue-50 transition"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="?delete=<?php echo $route['id']; ?>"
                                                class="w-8 h-8 rounded-full flex items-center justify-center text-red-600 hover:bg-red-50 transition"
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
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/admin_footer.php'; ?>