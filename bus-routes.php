<?php
require_once 'includes/functions.php';

$pageTitle = 'Bus Routes';

// Fetch all bus routes
try {
    $routesStmt = $pdo->query("SELECT * FROM bus_routes ORDER BY display_order, route_number");
    $routesResult = $routesStmt->fetchAll();
} catch (PDOException $e) {
    $routesResult = [];
}

include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="page-hero"
    style="background: linear-gradient(135deg, #F59E0B, #D97706); padding: 4rem 0; color: white; position: relative; overflow: hidden;">
    <div
        style="position: absolute; inset: 0; background: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.05\'%3E%3Cpath d=\'M30 30c0-5.52 4.48-10 10-10V10C30 10 22.18 14.48 16 20.66V30h14z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');">
    </div>
    <div class="container" style="position: relative; z-index: 1;">
        <div style="text-align: center; max-width: 48rem; margin: 0 auto;">
            <div
                style="display: inline-block; background: rgba(255,255,255,0.2); padding: 0.5rem 1.5rem; border-radius: 2rem; margin-bottom: 1.5rem;">
                <span style="font-size: 0.875rem; font-weight: 600;"><i class="fas fa-bus"></i> Safe & Convenient
                    Transport</span>
            </div>
            <h1 style="font-size: 3rem; font-weight: 700; margin-bottom: 1rem; color: white;">BUS ROUTES</h1>
            <nav style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; font-size: 0.875rem;">
                <a href="index.php" style="color: rgba(255,255,255,0.9); text-decoration: none;"><i
                        class="fas fa-home"></i> Home</a>
                <span style="color: rgba(255,255,255,0.7);">/</span>
                <span style="color: white;">Bus Routes</span>
            </nav>
        </div>
    </div>
</section>

<!-- Main Content Section -->
<section class="section bg-light">
    <div class="container">
        <div class="section-header">
            <span class="badge badge-primary"><i class="fas fa-bus"></i> Transport</span>
            <h2>School Bus Routes</h2>
            <p>Safe and reliable transportation service covering major areas of the city</p>
        </div>

        <?php if (empty($routesResult)): ?>
            <div class="card text-center" style="padding: 4rem;">
                <i class="fas fa-bus" style="font-size: 4rem; color: var(--border-light); margin-bottom: 1rem;"></i>
                <h3 style="color: var(--text-muted); font-weight: 500;">No bus routes available</h3>
                <p style="color: var(--text-muted);">Please check back later</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <?php foreach ($routesResult as $route): ?>
                    <div class="card route-card" style="border-left: 4px solid #F59E0B;">
                        <div class="card-body">
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">
                                <div>
                                    <span class="badge"
                                        style="background: linear-gradient(135deg, #F59E0B, #D97706); color: white; padding: 0.5rem 1rem; border-radius: 2rem; font-weight: 600;">
                                        <?php echo clean($route['route_number']); ?>
                                    </span>
                                </div>
                                <?php if ($route['fare']): ?>
                                    <div style="text-align: right;">
                                        <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 600;">Monthly</div>
                                        <div style="font-size: 1.25rem; font-weight: 700; color: #F59E0B;">₹<?php echo number_format($route['fare'], 0); ?></div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <h3 style="font-size: 1.5rem; margin-bottom: 1rem; color: var(--text-heading);">
                                <?php echo clean($route['route_name']); ?>
                            </h3>

                            <div style="margin-bottom: 1.5rem;">
                                <div style="font-size: 0.875rem; color: var(--text-muted); font-weight: 600; margin-bottom: 0.5rem;">
                                    <i class="fas fa-map-marker-alt" style="color: #F59E0B;"></i> Bus Stops
                                </div>
                                <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                                    <?php
                                    $stops = array_map('trim', explode(',', $route['stops']));
                                    foreach ($stops as $stop):
                                        if (!empty($stop)):
                                    ?>
                                        <span style="background: #FEF3C7; color: #92400E; padding: 0.375rem 0.75rem; border-radius: 1rem; font-size: 0.875rem;">
                                            <?php echo clean($stop); ?>
                                        </span>
                                    <?php
                                        endif;
                                    endforeach;
                                    ?>
                                </div>
                            </div>

                            <?php if ($route['bus_number'] || $route['driver_name']): ?>
                                <div style="padding-top: 1rem; border-top: 1px solid var(--border-light);">
                                    <?php if ($route['bus_number']): ?>
                                        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                                            <i class="fas fa-bus" style="color: #F59E0B; width: 1.25rem;"></i>
                                            <span style="color: var(--text-body);"><strong>Bus:</strong> <?php echo clean($route['bus_number']); ?></span>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($route['driver_name']): ?>
                                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                                            <i class="fas fa-user" style="color: #F59E0B; width: 1.25rem;"></i>
                                            <span style="color: var(--text-body);">
                                                <strong>Driver:</strong> <?php echo clean($route['driver_name']); ?>
                                                <?php if ($route['driver_phone']): ?>
                                                    <span style="color: var(--text-muted);">(<?php echo clean($route['driver_phone']); ?>)</span>
                                                <?php endif; ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Note Section -->
        <div class="card"
            style="margin-top: 3rem; background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(217, 119, 6, 0.05)); border-left: 4px solid #F59E0B;">
            <div class="card-body">
                <div style="display: flex; gap: 1rem; align-items: start;">
                    <i class="fas fa-info-circle" style="color: #F59E0B; font-size: 1.5rem; margin-top: 0.25rem;"></i>
                    <div>
                        <h4 style="color: #F59E0B; margin-bottom: 0.5rem; font-weight: 600;">Transport Guidelines</h4>
                        <p style="color: var(--text-body); line-height: 1.7; margin: 0;">
                            Our school transport service ensures safe and comfortable commute for students. All buses are
                            equipped with GPS tracking, first aid kits, and trained attendants. For new admissions or
                            route changes, please contact the school transport office at least 3 working days in advance.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .route-card {
        transition: all 0.3s ease;
    }

    .route-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(245, 158, 11, 0.15);
    }
</style>

<?php include 'includes/footer.php'; ?>
