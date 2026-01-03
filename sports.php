<?php
require_once 'includes/functions.php';

$pageTitle = 'Sports Activities';

// Fetch all sports activities
try {
    $sportsStmt = $pdo->query("SELECT * FROM sports ORDER BY display_order, title");
    $sportsResult = $sportsStmt->fetchAll();
} catch (PDOException $e) {
    $sportsResult = [];
}

include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="page-hero"
    style="background: linear-gradient(135deg, #EF4444, #DC2626); padding: 4rem 0; color: white; position: relative; overflow: hidden;">
    <div
        style="position: absolute; inset: 0; background: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.05\'%3E%3Cpath d=\'M30 30c0-5.52 4.48-10 10-10V10C30 10 22.18 14.48 16 20.66V30h14z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');">
    </div>
    <div class="container" style="position: relative; z-index: 1;">
        <div style="text-align: center; max-width: 48rem; margin: 0 auto;">
            <div
                style="display: inline-block; background: rgba(255,255,255,0.2); padding: 0.5rem 1.5rem; border-radius: 2rem; margin-bottom: 1.5rem;">
                <span style="font-size: 0.875rem; font-weight: 600;"><i class="fas fa-running"></i> Excellence in
                    Sports</span>
            </div>
            <h1 style="font-size: 3rem; font-weight: 700; margin-bottom: 1rem; color: white;">SPORTS ACTIVITIES</h1>
            <nav style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; font-size: 0.875rem;">
                <a href="index.php" style="color: rgba(255,255,255,0.9); text-decoration: none;"><i
                        class="fas fa-home"></i> Home</a>
                <span style="color: rgba(255,255,255,0.7);">/</span>
                <span style="color: white;">Sports Activities</span>
            </nav>
        </div>
    </div>
</section>

<!-- Main Content Section -->
<section class="section bg-light">
    <div class="container">
        <div class="section-header">
            <span class="badge badge-primary"><i class="fas fa-running"></i> Sports</span>
            <h2>Our Sports Facilities</h2>
            <p>Nurturing physical fitness and sportsmanship through diverse athletic programs</p>
        </div>

        <?php if (empty($sportsResult)): ?>
            <div class="card text-center" style="padding: 4rem;">
                <i class="fas fa-running" style="font-size: 4rem; color: var(--border-light); margin-bottom: 1rem;"></i>
                <h3 style="color: var(--text-muted); font-weight: 500;">No sports activities available</h3>
                <p style="color: var(--text-muted);">Please check back later</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($sportsResult as $sport): ?>
                    <div class="card sport-card" style="border-top: 4px solid #EF4444;">
                        <div class="card-body">
                            <div class="icon-box"
                                style="background: linear-gradient(135deg, #EF4444, #DC2626); margin-bottom: 1.5rem;">
                                <i class="fas fa-<?php echo $sport['icon'] ?: 'trophy'; ?>"></i>
                            </div>
                            <h3 style="font-size: 1.5rem; margin-bottom: 1rem; color: var(--text-heading);">
                                <?php echo clean($sport['title']); ?>
                            </h3>
                            <p style="color: var(--text-body); line-height: 1.7;">
                                <?php echo clean($sport['description']); ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Note Section -->
        <div class="card"
            style="margin-top: 3rem; background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(220, 38, 38, 0.05)); border-left: 4px solid #EF4444;">
            <div class="card-body">
                <div style="display: flex; gap: 1rem; align-items: start;">
                    <i class="fas fa-info-circle" style="color: #EF4444; font-size: 1.5rem; margin-top: 0.25rem;"></i>
                    <div>
                        <h4 style="color: #EF4444; margin-bottom: 0.5rem; font-weight: 600;">Sports Philosophy</h4>
                        <p style="color: var(--text-body); line-height: 1.7; margin: 0;">
                            We believe in the holistic development of our students through active participation in sports.
                            Our state-of-the-art facilities and experienced coaches help students discover their sporting
                            talent and build essential life skills like teamwork, discipline, and leadership.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .sport-card {
        transition: all 0.3s ease;
    }

    .sport-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgba(239, 68, 68, 0.2);
    }

    .sport-card:hover .icon-box {
        transform: scale(1.1) rotate(5deg);
    }

    .icon-box {
        transition: all 0.3s ease;
    }
</style>

<?php include 'includes/footer.php'; ?>
