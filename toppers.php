<?php
require_once 'includes/functions.php';

$pageTitle = 'Our Toppers';

// Get filter parameters
$filterYear = $_GET['year'] ?? null;

// Get all available years
$allToppers = getToppers();
$years = array_unique(array_column($allToppers, 'year'));
rsort($years);

// Get filtered toppers
$toppers = getToppers($filterYear);

include 'includes/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <div class="breadcrumb">
            <a href="index.php">Home</a>
            <span>/</span>
            <span>Academics</span>
        </div>
        <h1>Our <span style="color: #F59E0B;">Pride</span> - Achievers</h1>
        <p>Celebrating academic excellence and outstanding achievements</p>
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
                <div class="icon-box icon-box-sm"><i class="fas fa-trophy"></i></div>
                Filter by Year
            </h3>
            <div class="flex flex-wrap gap-2">
                <a href="toppers.php" class="btn btn-pill <?php echo !$filterYear ? 'btn-primary' : 'btn-outline'; ?>"
                    style="padding: 0.5rem 1.25rem; font-size: 0.875rem;">
                    All Years
                </a>
                <?php foreach ($years as $year): ?>
                    <a href="toppers.php?year=<?php echo $year; ?>"
                        class="btn btn-pill <?php echo $filterYear == $year ? 'btn-primary' : 'btn-outline'; ?>"
                        style="padding: 0.5rem 1.25rem; font-size: 0.875rem;">
                        <?php echo $year; ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- Toppers Grid -->
<section class="section bg-light">
    <div class="container">
        <?php if (empty($toppers)): ?>
            <div class="text-center" style="padding: 4rem 0;">
                <div
                    style="width: 8rem; height: 8rem; background: var(--border-light); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 2rem;">
                    <i class="fas fa-search" style="font-size: 3rem; color: var(--text-muted);"></i>
                </div>
                <h3 style="margin-bottom: 1rem;">No Toppers Found</h3>
                <p style="color: var(--text-muted);">Try selecting a different year</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($toppers as $topper): ?>
                    <div class="card">
                        <!-- Photo Section -->
                        <div
                            style="height: 220px; background: linear-gradient(135deg, #F59E0B, #D97706); position: relative; overflow: hidden;">
                            <?php if ($topper['photo']): ?>
                                <img src="uploads/toppers/<?php echo clean($topper['photo']); ?>"
                                    alt="<?php echo clean($topper['name']); ?>"
                                    style="width: 100%; height: 100%; object-fit: cover;">
                            <?php else: ?>
                                <div
                                    style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-user-graduate" style="font-size: 4rem; color: rgba(255,255,255,0.5);"></i>
                                </div>
                            <?php endif; ?>
                            <div
                                style="position: absolute; top: 1rem; right: 1rem; background: var(--color-primary); color: white; padding: 0.5rem 1rem; border-radius: 0.5rem; font-weight: 700; font-size: 1.25rem;">
                                <?php echo clean($topper['percentage']); ?>%
                            </div>
                        </div>

                        <!-- Details Section -->
                        <div class="card-body">
                            <h4 style="margin-bottom: 1rem;"><?php echo clean($topper['name']); ?></h4>

                            <div style="display: flex; flex-direction: column; gap: 0.75rem; margin-bottom: 1rem;">
                                <div class="flex items-center gap-3">
                                    <div
                                        style="width: 2.5rem; height: 2.5rem; background: rgba(245, 158, 11, 0.1); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                        <i class="fas fa-star" style="color: #F59E0B;"></i>
                                    </div>
                                    <div>
                                        <div style="font-size: 0.75rem; color: var(--text-muted);">Marks</div>
                                        <div style="font-weight: 600;"><?php echo clean($topper['marks']); ?></div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <div
                                        style="width: 2.5rem; height: 2.5rem; background: rgba(37, 99, 235, 0.1); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                        <i class="fas fa-graduation-cap" style="color: var(--color-primary);"></i>
                                    </div>
                                    <div>
                                        <div style="font-size: 0.75rem; color: var(--text-muted);">Class & Board</div>
                                        <div style="font-weight: 600;"><?php echo clean($topper['class']); ?> •
                                            <?php echo clean($topper['board']); ?></div>
                                    </div>
                                </div>

                                <?php if (!empty($topper['year'])): ?>
                                    <div class="flex items-center gap-3">
                                        <div
                                            style="width: 2.5rem; height: 2.5rem; background: rgba(16, 185, 129, 0.1); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                            <i class="fas fa-calendar" style="color: #10B981;"></i>
                                        </div>
                                        <div>
                                            <div style="font-size: 0.75rem; color: var(--text-muted);">Year</div>
                                            <div style="font-weight: 600;"><?php echo clean($topper['year']); ?></div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php if (!empty($topper['achievement'])): ?>
                                <div
                                    style="background: linear-gradient(135deg, rgba(37, 99, 235, 0.05), rgba(245, 158, 11, 0.05)); border: 1px solid rgba(37, 99, 235, 0.1); border-radius: 0.75rem; padding: 1rem;">
                                    <div class="flex items-start gap-2">
                                        <i class="fas fa-award" style="color: #F59E0B; margin-top: 0.125rem;"></i>
                                        <div>
                                            <div
                                                style="font-size: 0.625rem; color: var(--text-muted); text-transform: uppercase; font-weight: 600;">
                                                Achievement</div>
                                            <div style="font-weight: 600; color: var(--text-heading);">
                                                <?php echo clean($topper['achievement']); ?></div>
                                        </div>
                                    </div>
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