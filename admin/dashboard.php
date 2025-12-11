<?php
$pageTitle = 'Dashboard';
require_once 'includes/admin_header.php';

// Get statistics
global $pdo;

try {
    $stats = [];
    
    // Count toppers
    $stmt = $pdo->query("SELECT COUNT(*) FROM toppers");
    $stats['toppers'] = $stmt->fetchColumn();
    
    // Count staff
    $stmt = $pdo->query("SELECT COUNT(*) FROM staff");
    $stats['staff'] = $stmt->fetchColumn();
    
    // Count gallery images
    $stmt = $pdo->query("SELECT COUNT(*) FROM gallery");
    $stats['gallery'] = $stmt->fetchColumn();
    
    // Count pending admission inquiries
    $stmt = $pdo->query("SELECT COUNT(*) FROM admission_inquiries WHERE status = 'pending'");
    $stats['pending_admissions'] = $stmt->fetchColumn();
    
    // Get recent admission inquiries
    $stmt = $pdo->query("SELECT * FROM admission_inquiries ORDER BY submitted_at DESC LIMIT 5");
    $recentAdmissions = $stmt->fetchAll();
    
    // Get recent announcements
    $stmt = $pdo->query("SELECT * FROM announcements ORDER BY created_at DESC LIMIT 5");
    $recentAnnouncements = $stmt->fetchAll();
    
    // Get upcoming events
    // PostgreSQL uses CURRENT_DATE, MySQL uses CURDATE()
    $dbType = defined('DB_TYPE') ? DB_TYPE : 'mysql';
    $dateFunction = ($dbType === 'pgsql') ? 'CURRENT_DATE' : 'CURDATE()';
    $stmt = $pdo->query("SELECT * FROM events WHERE event_date >= $dateFunction ORDER BY event_date ASC LIMIT 5");
    $upcomingEvents = $stmt->fetchAll();
    
} catch (PDOException $e) {
    $stats = ['toppers' => 0, 'staff' => 0, 'gallery' => 0, 'pending_admissions' => 0];
    $recentAdmissions = [];
    $recentAnnouncements = [];
    $upcomingEvents = [];
}
?>

<!-- Welcome Message -->
<div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl text-white p-8 mb-6">
    <h2 class="text-3xl font-bold mb-2">Welcome back, <?php echo clean($admin['username']); ?>!</h2>
    <p class="text-blue-100">Here's what's happening with your school website today.</p>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Total Toppers</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-2"><?php echo $stats['toppers']; ?></h3>
            </div>
            <div class="w-14 h-14 bg-yellow-100 rounded-full flex items-center justify-center">
                <i class="fas fa-trophy text-yellow-600 text-2xl"></i>
            </div>
        </div>
        <a href="toppers.php" class="text-blue-600 text-sm font-medium hover:text-blue-700 mt-4 inline-block">
            Manage Toppers →
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Staff Members</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-2"><?php echo $stats['staff']; ?></h3>
            </div>
            <div class="w-14 h-14 bg-purple-100 rounded-full flex items-center justify-center">
                <i class="fas fa-user-tie text-purple-600 text-2xl"></i>
            </div>
        </div>
        <a href="staff.php" class="text-blue-600 text-sm font-medium hover:text-blue-700 mt-4 inline-block">
            Manage Staff →
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Gallery Images</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-2"><?php echo $stats['gallery']; ?></h3>
            </div>
            <div class="w-14 h-14 bg-pink-100 rounded-full flex items-center justify-center">
                <i class="fas fa-images text-pink-600 text-2xl"></i>
            </div>
        </div>
        <a href="gallery.php" class="text-blue-600 text-sm font-medium hover:text-blue-700 mt-4 inline-block">
            Manage Gallery →
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Pending Inquiries</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-2"><?php echo $stats['pending_admissions']; ?></h3>
            </div>
            <div class="w-14 h-14 bg-orange-100 rounded-full flex items-center justify-center">
                <i class="fas fa-envelope text-orange-600 text-2xl"></i>
            </div>
        </div>
        <a href="admissions.php" class="text-blue-600 text-sm font-medium hover:text-blue-700 mt-4 inline-block">
            View Inquiries →
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Admission Inquiries -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-800">
                <i class="fas fa-envelope text-orange-600 mr-2"></i>
                Recent Inquiries
            </h3>
            <a href="admissions.php" class="text-blue-600 text-sm font-medium hover:text-blue-700">
                View All
            </a>
        </div>

        <?php if (empty($recentAdmissions)): ?>
            <p class="text-gray-500 text-center py-8">No admission inquiries yet</p>
        <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($recentAdmissions as $admission): ?>
                    <div class="border-l-4 <?php echo $admission['status'] === 'pending' ? 'border-orange-500' : 'border-green-500'; ?> bg-gray-50 p-4 rounded-r-lg">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="font-semibold text-gray-800"><?php echo clean($admission['student_name']); ?></h4>
                            <span class="text-xs px-2 py-1 rounded-full <?php echo $admission['status'] === 'pending' ? 'bg-orange-100 text-orange-700' : 'bg-green-100 text-green-700'; ?>">
                                <?php echo ucfirst($admission['status']); ?>
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 mb-1">
                            <i class="fas fa-graduation-cap mr-1"></i>
                            <?php echo clean($admission['class_applying']); ?>
                        </p>
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-user mr-1"></i>
                            <?php echo clean($admission['parent_name']); ?> • 
                            <i class="fas fa-phone ml-2 mr-1"></i>
                            <?php echo clean($admission['phone']); ?>
                        </p>
                        <p class="text-xs text-gray-500 mt-2">
                            <?php echo timeAgo($admission['submitted_at']); ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Upcoming Events -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-800">
                <i class="fas fa-calendar-alt text-indigo-600 mr-2"></i>
                Upcoming Events
            </h3>
            <a href="events.php" class="text-blue-600 text-sm font-medium hover:text-blue-700">
                View All
            </a>
        </div>

        <?php if (empty($upcomingEvents)): ?>
            <p class="text-gray-500 text-center py-8">No upcoming events</p>
        <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($upcomingEvents as $event): ?>
                    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-gray-800 mb-2"><?php echo clean($event['title']); ?></h4>
                        <div class="flex items-center text-sm text-gray-600 space-x-4">
                            <span>
                                <i class="fas fa-calendar text-indigo-600 mr-1"></i>
                                <?php echo formatDate($event['event_date']); ?>
                            </span>
                            <?php if ($event['event_time']): ?>
                                <span>
                                    <i class="fas fa-clock text-indigo-600 mr-1"></i>
                                    <?php echo date('g:i A', strtotime($event['event_time'])); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        <?php if ($event['location']): ?>
                            <p class="text-sm text-gray-600 mt-1">
                                <i class="fas fa-map-marker-alt text-indigo-600 mr-1"></i>
                                <?php echo clean($event['location']); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Recent Announcements -->
<div class="bg-white rounded-xl shadow-lg p-6 mt-6">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-xl font-bold text-gray-800">
            <i class="fas fa-bullhorn text-blue-600 mr-2"></i>
            Recent Announcements
        </h3>
        <a href="announcements.php" class="text-blue-600 text-sm font-medium hover:text-blue-700">
            View All
        </a>
    </div>

    <?php if (empty($recentAnnouncements)): ?>
        <p class="text-gray-500 text-center py-8">No announcements yet</p>
    <?php else: ?>
        <div class="space-y-3">
            <?php foreach ($recentAnnouncements as $announcement): ?>
                <div class="border-l-4 <?php echo $announcement['priority'] === 'high' ? 'border-red-500' : ($announcement['priority'] === 'medium' ? 'border-yellow-500' : 'border-blue-500'); ?> bg-gray-50 p-4 rounded-r-lg">
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="font-semibold text-gray-800"><?php echo clean($announcement['title']); ?></h4>
                            <p class="text-sm text-gray-600 mt-1"><?php echo clean(substr($announcement['content'], 0, 100)); ?>...</p>
                            <p class="text-xs text-gray-500 mt-2">
                                <?php echo formatDate($announcement['date']); ?> • 
                                <span class="<?php echo $announcement['status'] === 'published' ? 'text-green-600' : 'text-gray-600'; ?>">
                                    <?php echo ucfirst($announcement['status']); ?>
                                </span>
                            </p>
                        </div>
                        <?php if ($announcement['priority'] === 'high'): ?>
                            <span class="bg-red-100 text-red-600 text-xs px-2 py-1 rounded-full">High Priority</span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Quick Actions -->
<div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl text-white p-6 mt-6">
    <h3 class="text-xl font-bold mb-4">Quick Actions</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="add_topper.php" class="bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg p-4 text-center transition">
            <i class="fas fa-plus-circle text-3xl mb-2"></i>
            <p class="text-sm font-medium">Add Topper</p>
        </a>
        <a href="add_staff.php" class="bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg p-4 text-center transition">
            <i class="fas fa-user-plus text-3xl mb-2"></i>
            <p class="text-sm font-medium">Add Staff</p>
        </a>
        <a href="add_announcement.php" class="bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg p-4 text-center transition">
            <i class="fas fa-bullhorn text-3xl mb-2"></i>
            <p class="text-sm font-medium">Add Announcement</p>
        </a>
        <a href="add_event.php" class="bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg p-4 text-center transition">
            <i class="fas fa-calendar-plus text-3xl mb-2"></i>
            <p class="text-sm font-medium">Add Event</p>
        </a>
    </div>
</div>

<?php require_once 'includes/admin_footer.php'; ?>

