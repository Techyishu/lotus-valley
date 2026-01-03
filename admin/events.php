<?php
$pageTitle = 'Manage Events';
require_once 'includes/admin_header.php';
global $pdo;

// Handle add/edit/delete - similar to announcements
if ($_SERVER['REQUEST_METHOD'] === 'POST' && verifyCSRFToken($_POST['csrf_token'])) {
    if (isset($_POST['add'])) {
        $stmt = $pdo->prepare("INSERT INTO events (title, description, event_date, event_time, location, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([trim($_POST['title']), trim($_POST['description']), $_POST['event_date'], $_POST['event_time'], trim($_POST['location']), $_POST['status']]);
        echo "<script>showToast('Event added', 'success');</script>";
    } elseif (isset($_POST['edit'])) {
        $stmt = $pdo->prepare("UPDATE events SET title=?, description=?, event_date=?, event_time=?, location=?, status=? WHERE id=?");
        $stmt->execute([trim($_POST['title']), trim($_POST['description']), $_POST['event_date'], $_POST['event_time'], trim($_POST['location']), $_POST['status'], (int)$_POST['id']]);
        echo "<script>showToast('Event updated', 'success');</script>";
    }
}

if (isset($_GET['delete']) && verifyCSRFToken($_GET['token'])) {
    $stmt = $pdo->prepare("DELETE FROM events WHERE id = ?");
    $stmt->execute([(int)$_GET['delete']]);
    echo "<script>showToast('Event deleted', 'success');</script>";
}

$editing = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->execute([(int)$_GET['edit']]);
    $editing = $stmt->fetch();
}

$stmt = $pdo->query("SELECT * FROM events ORDER BY event_date DESC");
$events = $stmt->fetchAll();
?>

<div class="card mb-6">
    <div class="card-header">
        <h3><i class="fas fa-calendar-alt"></i> Manage Events</h3>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Form Side -->
    <div class="card h-fit">
        <div class="card-header">
            <h3>
                <i class="fas <?php echo $editing ? 'fa-edit' : 'fa-plus'; ?>"></i> 
                <?php echo $editing ? 'Edit' : 'Add New'; ?> Event
            </h3>
        </div>
        <div class="card-body">
            <form method="POST" class="space-y-4">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                <?php if ($editing): ?>
                    <input type="hidden" name="id" value="<?php echo $editing['id']; ?>">
                    <input type="hidden" name="edit" value="1">
                <?php else: ?>
                    <input type="hidden" name="add" value="1">
                <?php endif; ?>

                <div class="form-group">
                    <label class="block font-semibold mb-2 text-gray-700">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" required value="<?php echo $editing ? clean($editing['title']) : ''; ?>" 
                           class="form-control" placeholder="Event title">
                </div>

                <div class="form-group">
                    <label class="block font-semibold mb-2 text-gray-700">Description</label>
                    <textarea name="description" rows="3" class="form-control" placeholder="Event details..."><?php echo $editing ? clean($editing['description']) : ''; ?></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="form-group">
                        <label class="block font-semibold mb-2 text-gray-700">Date <span class="text-red-500">*</span></label>
                        <input type="date" name="event_date" required value="<?php echo $editing ? $editing['event_date'] : ''; ?>" 
                               class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="block font-semibold mb-2 text-gray-700">Time</label>
                        <input type="time" name="event_time" value="<?php echo $editing ? $editing['event_time'] : ''; ?>" 
                               class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label class="block font-semibold mb-2 text-gray-700">Location</label>
                    <div class="relative">
                        <span class="absolute left-3 top-3 text-gray-400"><i class="fas fa-map-marker-alt"></i></span>
                        <input type="text" name="location" value="<?php echo $editing ? clean($editing['location']) : ''; ?>" 
                               class="form-control pl-10" placeholder="e.g. School Auditorium">
                    </div>
                </div>

                <div class="form-group">
                    <label class="block font-semibold mb-2 text-gray-700">Status</label>
                    <select name="status" class="form-control">
                        <option value="upcoming" <?php echo (!$editing || $editing['status'] === 'upcoming') ? 'selected' : ''; ?>>Upcoming</option>
                        <option value="completed" <?php echo ($editing && $editing['status'] === 'completed') ? 'selected' : ''; ?>>Completed</option>
                        <option value="cancelled" <?php echo ($editing && $editing['status'] === 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                    </select>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="btn btn-primary w-full justify-center">
                        <i class="fas fa-save"></i> Save Event
                    </button>
                    <?php if ($editing): ?>
                        <a href="events.php" class="btn btn-outline w-full justify-center">
                            Cancel
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- List Side -->
    <div class="space-y-4">
        <?php if (empty($events)): ?>
             <div class="card">
                <div class="card-body text-center py-12">
                    <i class="fas fa-calendar-day text-gray-300 text-5xl mb-4"></i>
                    <p class="text-gray-500">No events scheduled</p>
                </div>
             </div>
        <?php else: ?>
            <?php foreach ($events as $event): ?>
                <div class="card hover:shadow-lg transition group">
                    <div class="card-body p-0 flex flex-col md:flex-row">
                        <!-- Date Badge -->
                        <div class="bg-primary-50 p-6 flex flex-col items-center justify-center min-w-[100px] border-b md:border-b-0 md:border-r border-gray-100">
                            <span class="text-3xl font-bold text-primary-800 leading-none mb-1">
                                <?php echo date('d', strtotime($event['event_date'])); ?>
                            </span>
                            <span class="text-xs uppercase font-bold text-primary-600 tracking-wider">
                                <?php echo date('M', strtotime($event['event_date'])); ?>
                            </span>
                             <span class="text-xs text-gray-400 mt-1">
                                <?php echo date('Y', strtotime($event['event_date'])); ?>
                            </span>
                        </div>
                        
                        <!-- Content -->
                        <div class="p-5 flex-grow">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-bold text-lg text-gray-800"><?php echo clean($event['title']); ?></h4>
                                <span class="badge <?php 
                                    $statusClass = 'badge-secondary';
                                    if ($event['status'] === 'upcoming') $statusClass = 'badge-info';
                                    elseif ($event['status'] === 'completed') $statusClass = 'badge-success';
                                    elseif ($event['status'] === 'cancelled') $statusClass = 'badge-danger';
                                    echo $statusClass;
                                ?>">
                                    <?php echo ucfirst($event['status']); ?>
                                </span>
                            </div>
                            
                            <?php if ($event['description']): ?>
                                <p class="text-gray-600 text-sm mb-3 line-clamp-2"><?php echo clean($event['description']); ?></p>
                            <?php endif; ?>
                            
                            <div class="flex flex-wrap items-center gap-4 text-xs text-gray-500 mt-3 pt-3 border-t border-gray-100">
                                <?php if ($event['event_time']): ?>
                                    <span class="flex items-center"><i class="far fa-clock mr-1.5 text-primary-400"></i><?php echo date('g:i A', strtotime($event['event_time'])); ?></span>
                                <?php endif; ?>
                                <?php if ($event['location']): ?>
                                    <span class="flex items-center"><i class="fas fa-map-marker-alt mr-1.5 text-red-400"></i><?php echo clean($event['location']); ?></span>
                                <?php endif; ?>
                                
                                <div class="ml-auto flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="?edit=<?php echo $event['id']; ?>" class="text-blue-600 hover:text-blue-800 font-medium">Edit</a>
                                    <span class="text-gray-300">|</span>
                                    <a href="?delete=<?php echo $event['id']; ?>&token=<?php echo generateCSRFToken(); ?>" onclick="return confirmDelete()" class="text-red-600 hover:text-red-800 font-medium">Delete</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/admin_footer.php'; ?>

