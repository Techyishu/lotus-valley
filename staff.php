<?php
require_once 'includes/functions.php';

$pageTitle = 'Our Faculty';

// Get filter parameter
$filterDept = $_GET['dept'] ?? null;

// Get all staff
$allStaff = getStaff();
$departments = array_unique(array_column($allStaff, 'department'));
sort($departments);

// Get filtered staff
$staff = getStaff($filterDept);

include 'includes/header.php';
?>

<!-- Page Header -->
<section class="page-header" style="background: linear-gradient(135deg, #F59E0B, #D97706);">
    <div class="container">
        <div class="breadcrumb">
            <a href="index.php">Home</a>
            <span>/</span>
            <span>Faculty</span>
        </div>
        <h1>Meet Our <span style="color: white;">Dedicated Faculty</span></h1>
        <p>Passionate educators committed to shaping future leaders</p>
    </div>
    <div class="wave-bottom">
        <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 64C240 96 480 96 720 64C960 32 1200 32 1440 64V80H0V64Z" fill="#F5F7FB" />
        </svg>
    </div>
</section>

<!-- Filter Section -->
<section
    style="padding: 1rem 0; background: white; box-shadow: var(--shadow-sm); border-bottom: 1px solid var(--border-light); position: sticky; top: 60px; z-index: 40;">
    <div class="container">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h3 class="flex items-center gap-3">
                <div class="icon-box icon-box-sm" style="background: #F59E0B;"><i class="fas fa-filter"></i></div>
                Filter by Department
            </h3>
            <div class="flex flex-wrap gap-2">
                <a href="staff.php" class="btn btn-pill <?php echo !$filterDept ? 'btn-secondary' : 'btn-outline'; ?>"
                    style="padding: 0.5rem 1.25rem; font-size: 0.875rem;">
                    All Departments
                </a>
                <?php foreach ($departments as $dept): ?>
                    <a href="staff.php?dept=<?php echo urlencode($dept); ?>"
                        class="btn btn-pill <?php echo $filterDept == $dept ? 'btn-secondary' : 'btn-outline'; ?>"
                        style="padding: 0.5rem 1.25rem; font-size: 0.875rem;">
                        <?php echo clean($dept); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- Staff Grid -->
<section class="section bg-light">
    <div class="container">
        <?php if (empty($staff)): ?>
            <div class="text-center" style="padding: 4rem 0;">
                <div
                    style="width: 8rem; height: 8rem; background: var(--border-light); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 2rem;">
                    <i class="fas fa-user-slash" style="font-size: 3rem; color: var(--text-muted);"></i>
                </div>
                <h3 style="margin-bottom: 1rem;">No Staff Members Found</h3>
                <p style="color: var(--text-muted);">Try selecting a different department</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"
                style="grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));">
                <?php foreach ($staff as $member): ?>
                    <div class="card">
                        <!-- Photo Section -->
                        <div
                            style="height: 220px; background: linear-gradient(135deg, #F59E0B, #D97706); position: relative; overflow: hidden;">
                            <?php if ($member['photo']): ?>
                                <img src="uploads/staff/<?php echo clean($member['photo']); ?>"
                                    alt="<?php echo clean($member['name']); ?>"
                                    style="width: 100%; height: 100%; object-fit: cover;">
                            <?php else: ?>
                                <div
                                    style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-user-tie" style="font-size: 4rem; color: rgba(255,255,255,0.5);"></i>
                                </div>
                            <?php endif; ?>
                            <!-- Overlay with name -->
                            <div
                                style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(to top, rgba(0,0,0,0.8), transparent); padding: 2rem 1.5rem 1rem;">
                                <h4 style="color: white; margin: 0;"><?php echo clean($member['name']); ?></h4>
                                <p style="color: rgba(255,255,255,0.8); font-size: 0.875rem; margin: 0.25rem 0 0;">
                                    <?php echo clean($member['department']); ?></p>
                            </div>
                        </div>

                        <!-- Details Section -->
                        <div class="card-body">
                            <span class="badge badge-secondary mb-4"><?php echo clean($member['department']); ?></span>

                            <h4 style="font-size: 1rem; margin-bottom: 0.5rem;">
                                <?php echo clean($member['position'] ?? 'Teacher'); ?></h4>

                            <p style="color: var(--text-muted); font-size: 0.875rem; margin-bottom: 1rem; line-height: 1.6;">
                                <?php echo clean($member['bio'] ?? 'Dedicated educator committed to student success and excellence in teaching.'); ?>
                            </p>

                            <?php if (!empty($member['qualifications'])): ?>
                                <div class="flex items-center gap-2"
                                    style="font-size: 0.875rem; color: var(--text-muted); margin-bottom: 0.5rem;">
                                    <i class="fas fa-graduation-cap" style="color: #F59E0B;"></i>
                                    <span><?php echo clean($member['qualifications']); ?></span>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($member['experience'])): ?>
                                <div class="flex items-center gap-2" style="font-size: 0.875rem; color: var(--text-muted);">
                                    <i class="fas fa-clock" style="color: var(--color-primary);"></i>
                                    <span><?php echo clean($member['experience']); ?> Experience</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>