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
<div class="welcome-banner mb-6"
    style="background: linear-gradient(135deg, var(--primary-900) 0%, var(--primary-700) 100%); padding: 2rem; border-radius: 12px; color: white; box-shadow: var(--shadow-md);">
    <h2 style="font-size: 1.75rem; margin-bottom: 0.5rem; color: white;">Welcome back,
        <?php echo clean($admin['username'] ?? 'Admin'); ?>!</h2>
    <p style="color: rgba(255,255,255,0.8);">Here's what's happening at Lotus Valley School today.</p>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Toppers -->
    <div class="card" style="margin-bottom: 0; display: flex; flex-direction: column; height: 100%;">
        <div class="card-body" style="flex: 1;">
            <div class="flex items-center justify-between mb-4">
                <div
                    style="width: 48px; height: 48px; background: #fef3c7; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-trophy" style="color: #d97706; font-size: 1.25rem;"></i>
                </div>
                <span
                    style="font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase;">Total</span>
            </div>
            <h3 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.25rem;"><?php echo $stats['toppers']; ?></h3>
            <p style="color: var(--text-secondary); font-size: 0.9rem;">Toppers</p>
        </div>
        <div style="padding: 1rem 1.5rem; background: #f8fafc; border-top: 1px solid var(--border-color);">
            <a href="toppers.php"
                style="color: var(--primary-600); font-weight: 600; font-size: 0.85rem; text-decoration: none; display: flex; align-items: center; gap: 0.5rem;">
                Manage Toppers <i class="fas fa-arrow-right" style="font-size: 0.7rem;"></i>
            </a>
        </div>
    </div>

    <!-- Staff -->
    <div class="card" style="margin-bottom: 0; display: flex; flex-direction: column; height: 100%;">
        <div class="card-body" style="flex: 1;">
            <div class="flex items-center justify-between mb-4">
                <div
                    style="width: 48px; height: 48px; background: #e0e7ff; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-user-tie" style="color: #4f46e5; font-size: 1.25rem;"></i>
                </div>
                <span
                    style="font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase;">Total</span>
            </div>
            <h3 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.25rem;"><?php echo $stats['staff']; ?></h3>
            <p style="color: var(--text-secondary); font-size: 0.9rem;">Staff Members</p>
        </div>
        <div style="padding: 1rem 1.5rem; background: #f8fafc; border-top: 1px solid var(--border-color);">
            <a href="staff.php"
                style="color: var(--primary-600); font-weight: 600; font-size: 0.85rem; text-decoration: none; display: flex; align-items: center; gap: 0.5rem;">
                Manage Staff <i class="fas fa-arrow-right" style="font-size: 0.7rem;"></i>
            </a>
        </div>
    </div>

    <!-- Gallery -->
    <div class="card" style="margin-bottom: 0; display: flex; flex-direction: column; height: 100%;">
        <div class="card-body" style="flex: 1;">
            <div class="flex items-center justify-between mb-4">
                <div
                    style="width: 48px; height: 48px; background: #fce7f3; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-images" style="color: #db2777; font-size: 1.25rem;"></i>
                </div>
                <span
                    style="font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase;">Total</span>
            </div>
            <h3 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.25rem;"><?php echo $stats['gallery']; ?></h3>
            <p style="color: var(--text-secondary); font-size: 0.9rem;">Gallery Images</p>
        </div>
        <div style="padding: 1rem 1.5rem; background: #f8fafc; border-top: 1px solid var(--border-color);">
            <a href="gallery.php"
                style="color: var(--primary-600); font-weight: 600; font-size: 0.85rem; text-decoration: none; display: flex; align-items: center; gap: 0.5rem;">
                Manage Gallery <i class="fas fa-arrow-right" style="font-size: 0.7rem;"></i>
            </a>
        </div>
    </div>

    <!-- Inquiries -->
    <div class="card" style="margin-bottom: 0; display: flex; flex-direction: column; height: 100%;">
        <div class="card-body" style="flex: 1;">
            <div class="flex items-center justify-between mb-4">
                <div
                    style="width: 48px; height: 48px; background: #ffedd5; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-envelope" style="color: #ea580c; font-size: 1.25rem;"></i>
                </div>
                <span class="badge badge-danger">
                    <?php echo $stats['pending_admissions']; ?> Pending
                </span>
            </div>
            <h3 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.25rem;">
                <?php echo $stats['pending_admissions']; ?></h3>
            <p style="color: var(--text-secondary); font-size: 0.9rem;">New Inquiries</p>
        </div>
        <div style="padding: 1rem 1.5rem; background: #f8fafc; border-top: 1px solid var(--border-color);">
            <a href="admissions.php"
                style="color: var(--primary-600); font-weight: 600; font-size: 0.85rem; text-decoration: none; display: flex; align-items: center; gap: 0.5rem;">
                View Inquiries <i class="fas fa-arrow-right" style="font-size: 0.7rem;"></i>
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Admission Inquiries -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-envelope"></i> Recent Inquiries</h3>
            <a href="admissions.php" class="btn btn-sm btn-outline">View All</a>
        </div>
        <div class="card-body" style="padding: 0;">
            <?php if (empty($recentAdmissions)): ?>
                <div style="padding: 2rem; text-align: center; color: var(--text-secondary);">No admission inquiries yet
                </div>
            <?php else: ?>
                <div class="table-responsive" style="border: none; border-radius: 0;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Class</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentAdmissions as $admission): ?>
                                <tr>
                                    <td>
                                        <div style="font-weight: 600;"><?php echo clean($admission['student_name']); ?></div>
                                        <div style="font-size: 0.75rem; color: var(--text-secondary);">
                                            <?php echo clean($admission['phone']); ?></div>
                                    </td>
                                    <td><?php echo clean($admission['class_applying']); ?></td>
                                    <td>
                                        <?php if ($admission['status'] === 'pending'): ?>
                                            <span class="badge badge-warning">Pending</span>
                                        <?php else: ?>
                                            <span class="badge badge-success">Approved</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="font-size: 0.8rem; color: var(--text-secondary);">
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
        <div class="card-header">
            <h3><i class="fas fa-calendar-alt"></i> Upcoming Events</h3>
            <a href="events.php" class="btn btn-sm btn-outline">View All</a>
        </div>
        <div class="card-body">
            <?php if (empty($upcomingEvents)): ?>
                <div style="padding: 2rem; text-align: center; color: var(--text-secondary);">No upcoming events</div>
            <?php else: ?>
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <?php foreach ($upcomingEvents as $event): ?>
                        <div
                            style="display: flex; gap: 1rem; align-items: start; padding-bottom: 1rem; border-bottom: 1px solid var(--border-color);">
                            <div
                                style="background: var(--bg-body); border-radius: 8px; width: 60px; text-align: center; overflow: hidden; border: 1px solid var(--border-color);">
                                <div
                                    style="background: var(--primary-600); color: white; font-size: 0.7rem; padding: 2px; text-transform: uppercase; font-weight: 700;">
                                    <?php echo date('M', strtotime($event['event_date'])); ?>
                                </div>
                                <div style="font-size: 1.25rem; font-weight: 700; padding: 4px; color: var(--text-main);">
                                    <?php echo date('d', strtotime($event['event_date'])); ?>
                                </div>
                            </div>
                            <div>
                                <h4 style="font-size: 0.95rem; font-weight: 600; margin-bottom: 0.25rem;">
                                    <?php echo clean($event['title']); ?></h4>
                                <div style="font-size: 0.85rem; color: var(--text-secondary); display: flex; gap: 0.75rem;">
                                    <?php if ($event['event_time']): ?>
                                        <span><i class="far fa-clock"></i>
                                            <?php echo date('g:i A', strtotime($event['event_time'])); ?></span>
                                    <?php endif; ?>
                                    <?php if ($event['location']): ?>
                                        <span><i class="fas fa-map-marker-alt"></i> <?php echo clean($event['location']); ?></span>
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
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-bullhorn"></i> Recent Announcements</h3>
        <a href="announcements.php" class="btn btn-sm btn-outline">View All</a>
    </div>
    <div class="card-body">
        <?php if (empty($recentAnnouncements)): ?>
            <div style="padding: 2rem; text-align: center; color: var(--text-secondary);">No announcements yet</div>
        <?php else: ?>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
                <?php foreach ($recentAnnouncements as $announcement): ?>
                    <div
                        style="padding: 1.25rem; border: 1px solid var(--border-color); border-radius: 8px; background: #fafafa; border-left: 4px solid <?php echo $announcement['priority'] === 'high' ? '#ef4444' : ($announcement['priority'] === 'medium' ? '#f59e0b' : '#3b82f6'); ?>;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span
                                style="font-size: 0.75rem; color: var(--text-secondary); font-weight: 500;"><?php echo formatDate($announcement['date']); ?></span>
                            <?php if ($announcement['priority'] === 'high'): ?>
                                <span class="badge badge-danger" style="font-size: 0.65rem;">High Priority</span>
                            <?php endif; ?>
                        </div>
                        <h4 style="font-size: 1rem; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-main);">
                            <?php echo clean($announcement['title']); ?></h4>
                        <p style="font-size: 0.875rem; color: var(--text-secondary); line-height: 1.5; margin-bottom: 0;">
                            <?php echo clean(substr($announcement['content'], 0, 100)); ?>...</p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Quick Actions -->
<div class="welcome-banner"
    style="background: white; border-radius: 12px; padding: 1.5rem; border: 1px solid var(--border-color); box-shadow: var(--shadow-sm);">
    <h3 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 1rem; color: var(--text-main);">Quick Actions</h3>
    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
        <a href="add_announcement.php" class="btn btn-outline" style="flex: 1; border-style: dashed;">
            <i class="fas fa-plus text-green-600"></i> New Announcement
        </a>
        <a href="add_event.php" class="btn btn-outline" style="flex: 1; border-style: dashed;">
            <i class="fas fa-plus text-blue-600"></i> New Event
        </a>
        <a href="add_topper.php" class="btn btn-outline" style="flex: 1; border-style: dashed;">
            <i class="fas fa-plus text-yellow-600"></i> New Topper
        </a>
        <a href="add_staff.php" class="btn btn-outline" style="flex: 1; border-style: dashed;">
            <i class="fas fa-plus text-purple-600"></i> New Staff
        </a>
    </div>
</div>

<?php require_once 'includes/admin_footer.php'; ?>