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
<div
    class="mb-8 bg-gradient-to-r from-primary-900 to-primary-700 rounded-2xl p-8 text-white shadow-lg relative overflow-hidden">
    <div class="absolute right-0 top-0 h-full w-1/3 bg-white opacity-5 transform skew-x-12 translate-x-12"></div>
    <div class="relative z-10">
        <h2 class="text-3xl font-bold mb-2">Welcome back, <?php echo clean($admin['username'] ?? 'Admin'); ?>!</h2>
        <p class="text-primary-100 text-lg">Here's what's happening at Lotus Valley School today.</p>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Toppers -->
    <div class="card flex flex-col h-full hover:shadow-lg transition-shadow duration-300">
        <div class="card-body flex-1">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-trophy text-amber-600 text-xl"></i>
                </div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-800 mb-1"><?php echo $stats['toppers']; ?></h3>
            <p class="text-gray-500 text-sm">Toppers</p>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 rounded-b-xl">
            <a href="toppers.php"
                class="text-primary-600 font-semibold text-sm flex items-center gap-2 hover:text-primary-700 group">
                Manage Toppers <i
                    class="fas fa-arrow-right text-xs transform group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
    </div>

    <!-- Staff -->
    <div class="card flex flex-col h-full hover:shadow-lg transition-shadow duration-300">
        <div class="card-body flex-1">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-user-tie text-blue-600 text-xl"></i>
                </div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-800 mb-1"><?php echo $stats['staff']; ?></h3>
            <p class="text-gray-500 text-sm">Staff Members</p>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 rounded-b-xl">
            <a href="staff.php"
                class="text-primary-600 font-semibold text-sm flex items-center gap-2 hover:text-primary-700 group">
                Manage Staff <i
                    class="fas fa-arrow-right text-xs transform group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
    </div>

    <!-- Gallery -->
    <div class="card flex flex-col h-full hover:shadow-lg transition-shadow duration-300">
        <div class="card-body flex-1">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-pink-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-images text-pink-600 text-xl"></i>
                </div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-800 mb-1"><?php echo $stats['gallery']; ?></h3>
            <p class="text-gray-500 text-sm">Gallery Images</p>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 rounded-b-xl">
            <a href="gallery.php"
                class="text-primary-600 font-semibold text-sm flex items-center gap-2 hover:text-primary-700 group">
                Manage Gallery <i
                    class="fas fa-arrow-right text-xs transform group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
    </div>

    <!-- Inquiries -->
    <div class="card flex flex-col h-full hover:shadow-lg transition-shadow duration-300">
        <div class="card-body flex-1">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-envelope text-orange-600 text-xl"></i>
                </div>
                <span class="badge badge-warning text-xs">
                    <?php echo $stats['pending_admissions']; ?> Pending
                </span>
            </div>
            <h3 class="text-3xl font-bold text-gray-800 mb-1"><?php echo $stats['pending_admissions']; ?></h3>
            <p class="text-gray-500 text-sm">New Inquiries</p>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 rounded-b-xl">
            <a href="admissions.php"
                class="text-primary-600 font-semibold text-sm flex items-center gap-2 hover:text-primary-700 group">
                View Inquiries <i
                    class="fas fa-arrow-right text-xs transform group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Recent Admission Inquiries -->
    <div class="card">
        <div class="card-header flex justify-between items-center border-b border-gray-100 pb-4">
            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-envelope text-primary-500"></i> Recent Inquiries
            </h3>
            <a href="admissions.php"
                class="text-sm text-primary-600 hover:text-primary-700 font-medium hover:underline">View All</a>
        </div>
        <div class="card-body p-0">
            <?php if (empty($recentAdmissions)): ?>
                <div class="p-8 text-center text-gray-500">
                    <i class="far fa-envelope-open text-3xl mb-2 text-gray-300"></i>
                    <p>No admission inquiries yet</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table w-full">
                        <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-semibold">
                            <tr>
                                <th class="px-6 py-3 text-left">Student</th>
                                <th class="px-6 py-3 text-left">Class</th>
                                <th class="px-6 py-3 text-left">Status</th>
                                <th class="px-6 py-3 text-right">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php foreach ($recentAdmissions as $admission): ?>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-800"><?php echo clean($admission['student_name']); ?>
                                        </div>
                                        <div class="text-xs text-gray-500"><?php echo clean($admission['phone']); ?></div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600 text-sm">
                                        <?php echo clean($admission['class_applying']); ?></td>
                                    <td class="px-6 py-4">
                                        <?php if ($admission['status'] === 'pending'): ?>
                                            <span class="badge badge-warning">Pending</span>
                                        <?php else: ?>
                                            <span class="badge badge-success">Approved</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-right text-xs text-gray-500">
                                        <?php echo timeAgo($admission['submitted_at']); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Upcoming Events -->
    <div class="card">
        <div class="card-header flex justify-between items-center border-b border-gray-100 pb-4">
            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-calendar-alt text-primary-500"></i> Upcoming Events
            </h3>
            <a href="events.php"
                class="text-sm text-primary-600 hover:text-primary-700 font-medium hover:underline">View All</a>
        </div>
        <div class="card-body p-6">
            <?php if (empty($upcomingEvents)): ?>
                <div class="p-8 text-center text-gray-500">
                    <i class="far fa-calendar text-3xl mb-2 text-gray-300"></i>
                    <p>No upcoming events</p>
                </div>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($upcomingEvents as $event): ?>
                        <div class="flex gap-4 items-start pb-4 border-b border-gray-50 last:border-0 last:pb-0 group">
                            <div
                                class="bg-white border border-gray-200 rounded-lg w-16 text-center overflow-hidden shadow-sm flex-shrink-0">
                                <div class="bg-primary-600 text-white text-[10px] uppercase font-bold py-1">
                                    <?php echo date('M', strtotime($event['event_date'])); ?>
                                </div>
                                <div class="text-xl font-bold text-gray-800 py-2 bg-gray-50">
                                    <?php echo date('d', strtotime($event['event_date'])); ?>
                                </div>
                            </div>
                            <div class="flex-grow">
                                <h4
                                    class="text-base font-bold text-gray-800 mb-1 group-hover:text-primary-600 transition-colors">
                                    <?php echo clean($event['title']); ?>
                                </h4>
                                <div class="flex items-center gap-4 text-xs text-gray-500">
                                    <?php if ($event['event_time']): ?>
                                        <span class="flex items-center gap-1"><i class="far fa-clock"></i>
                                            <?php echo date('g:i A', strtotime($event['event_time'])); ?></span>
                                    <?php endif; ?>
                                    <?php if ($event['location']): ?>
                                        <span class="flex items-center gap-1"><i class="fas fa-map-marker-alt"></i>
                                            <?php echo clean($event['location']); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Recent Announcements -->
<div class="card mb-8">
    <div class="card-header flex justify-between items-center border-b border-gray-100 pb-4">
        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
            <i class="fas fa-bullhorn text-primary-500"></i> Recent Announcements
        </h3>
        <a href="announcements.php"
            class="text-sm text-primary-600 hover:text-primary-700 font-medium hover:underline">View All</a>
    </div>
    <div class="card-body p-6">
        <?php if (empty($recentAnnouncements)): ?>
            <div class="p-8 text-center text-gray-500">
                <i class="fas fa-bullhorn text-3xl mb-2 text-gray-300"></i>
                <p>No announcements yet</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($recentAnnouncements as $announcement): ?>
                    <div
                        class="p-5 border border-gray-100 rounded-xl bg-gray-50 hover:bg-white hover:shadow-md transition-all duration-300 border-l-4 <?php echo $announcement['priority'] === 'high' ? 'border-l-red-500' : ($announcement['priority'] === 'medium' ? 'border-l-amber-500' : 'border-l-blue-500'); ?>">
                        <div class="flex justify-between items-start mb-2">
                            <span
                                class="text-xs font-semibold text-gray-400 uppercase tracking-wider"><?php echo formatDate($announcement['date']); ?></span>
                            <?php if ($announcement['priority'] === 'high'): ?>
                                <span class="badge badge-danger text-[10px] px-2 py-0.5">High Priority</span>
                            <?php endif; ?>
                        </div>
                        <h4 class="text-base font-bold text-gray-800 mb-2 line-clamp-1">
                            <?php echo clean($announcement['title']); ?>
                        </h4>
                        <p class="text-sm text-gray-600 line-clamp-2 leading-relaxed">
                            <?php echo clean(strip_tags($announcement['content'])); ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
        <i class="fas fa-bolt text-amber-500"></i> Quick Actions
    </h3>
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <a href="announcements.php"
            class="flex flex-col items-center justify-center p-4 border border-dashed border-gray-300 rounded-xl hover:bg-green-50 hover:border-green-300 transition-colors group text-center bg-gray-50">
            <div
                class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                <i class="fas fa-bullhorn text-green-600"></i>
            </div>
            <span class="text-sm font-semibold text-gray-700 group-hover:text-green-700">New Announcement</span>
        </a>
        <a href="events.php"
            class="flex flex-col items-center justify-center p-4 border border-dashed border-gray-300 rounded-xl hover:bg-blue-50 hover:border-blue-300 transition-colors group text-center bg-gray-50">
            <div
                class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                <i class="fas fa-calendar-plus text-blue-600"></i>
            </div>
            <span class="text-sm font-semibold text-gray-700 group-hover:text-blue-700">New Event</span>
        </a>
        <a href="toppers.php"
            class="flex flex-col items-center justify-center p-4 border border-dashed border-gray-300 rounded-xl hover:bg-amber-50 hover:border-amber-300 transition-colors group text-center bg-gray-50">
            <div
                class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                <i class="fas fa-trophy text-amber-600"></i>
            </div>
            <span class="text-sm font-semibold text-gray-700 group-hover:text-amber-700">New Topper</span>
        </a>
        <a href="staff.php"
            class="flex flex-col items-center justify-center p-4 border border-dashed border-gray-300 rounded-xl hover:bg-purple-50 hover:border-purple-300 transition-colors group text-center bg-gray-50">
            <div
                class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                <i class="fas fa-user-plus text-purple-600"></i>
            </div>
            <span class="text-sm font-semibold text-gray-700 group-hover:text-purple-700">New Staff</span>
        </a>
    </div>
</div>

<?php require_once 'includes/admin_footer.php'; ?>