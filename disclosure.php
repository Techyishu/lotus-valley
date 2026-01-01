<?php
require_once 'includes/functions.php';

$pageTitle = 'Mandatory Public Disclosure';

// Fetch all disclosures grouped by category
try {
    $disclosuresStmt = $pdo->query("SELECT * FROM disclosures ORDER BY category, display_order, title");
    $allDisclosures = $disclosuresStmt->fetchAll();
    
    $disclosures = [];
    foreach ($allDisclosures as $row) {
        $category = $row['category'] ?: 'General';
        if (!isset($disclosures[$category])) {
            $disclosures[$category] = [];
        }
        $disclosures[$category][] = $row;
    }
} catch (PDOException $e) {
    $disclosures = [];
}

include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="page-hero"
    style="background: linear-gradient(135deg, #10B981, #059669); padding: 4rem 0; color: white; position: relative; overflow: hidden;">
    <div
        style="position: absolute; inset: 0; background: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.05\'%3E%3Cpath d=\'M30 30c0-5.52 4.48-10 10-10V10C30 10 22.18 14.48 16 20.66V30h14z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');">
    </div>
    <div class="container" style="position: relative; z-index: 1;">
        <div style="text-align: center; max-width: 48rem; margin: 0 auto;">
            <div
                style="display: inline-block; background: rgba(255,255,255,0.2); padding: 0.5rem 1.5rem; border-radius: 2rem; margin-bottom: 1.5rem;">
                <span style="font-size: 0.875rem; font-weight: 600;"><i class="fas fa-file-alt"></i> Transparency &
                    Compliance</span>
            </div>
            <h1 style="font-size: 3rem; font-weight: 700; margin-bottom: 1rem; color: white;">MANDATORY PUBLIC
                DISCLOSURE</h1>
            <nav style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; font-size: 0.875rem;">
                <a href="index.php" style="color: rgba(255,255,255,0.9); text-decoration: none;"><i
                        class="fas fa-home"></i> Home</a>
                <span style="color: rgba(255,255,255,0.7);">/</span>
                <a href="about.php" style="color: rgba(255,255,255,0.9); text-decoration: none;">About Us</a>
                <span style="color: rgba(255,255,255,0.7);">/</span>
                <span style="color: white;">Mandatory Public Disclosure</span>
            </nav>
        </div>
    </div>
</section>

<!-- Main Content Section -->
<section class="section bg-light">
    <div class="container">
        <div class="section-header">
            <span class="badge badge-primary"><i class="fas fa-info-circle"></i> Information</span>
            <h2>Mandatory Public Disclosure</h2>
            <p>Essential documents and information as per regulatory requirements</p>
        </div>

        <?php if (empty($disclosures)): ?>
            <div class="card text-center" style="padding: 4rem;">
                <i class="fas fa-file-alt" style="font-size: 4rem; color: var(--border-light); margin-bottom: 1rem;"></i>
                <h3 style="color: var(--text-muted); font-weight: 500;">No disclosure documents available</h3>
                <p style="color: var(--text-muted);">Please check back later</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <?php foreach ($disclosures as $category => $items): ?>
                    <div>
                        <h3
                            style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem; color: var(--color-primary);">
                            <div class="icon-box icon-box-sm" style="background: linear-gradient(135deg, #10B981, #059669);">
                                <i class="fas fa-folder-open"></i>
                            </div>
                            <?php echo clean($category); ?>
                        </h3>

                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            <?php foreach ($items as $item): ?>
                                <a href="uploads/disclosures/<?php echo clean($item['file_path']); ?>" target="_blank"
                                    class="card disclosure-card"
                                    style="text-decoration: none; transition: all 0.3s ease; border-left: 4px solid #10B981;">
                                    <div class="card-body" style="display: flex; align-items: center; gap: 1rem;">
                                        <div class="icon-box"
                                            style="background: linear-gradient(135deg, #10B981, #059669); flex-shrink: 0;">
                                            <i class="fas fa-file-pdf"></i>
                                        </div>
                                        <div style="flex: 1;">
                                            <h4 style="font-size: 1.125rem; margin-bottom: 0.25rem; color: var(--text-heading);">
                                                <?php echo clean($item['title']); ?>
                                            </h4>
                                            <?php if ($item['description']): ?>
                                                <p style="font-size: 0.875rem; color: var(--text-muted); margin: 0;">
                                                    <?php echo clean($item['description']); ?>
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                        <i class="fas fa-external-link-alt"
                                            style="color: var(--color-primary); font-size: 1.25rem; flex-shrink: 0;"></i>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Note Section -->
        <div class="card"
            style="margin-top: 3rem; background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(5, 150, 105, 0.05)); border-left: 4px solid #10B981;">
            <div class="card-body">
                <div style="display: flex; gap: 1rem; align-items: start;">
                    <i class="fas fa-info-circle" style="color: #10B981; font-size: 1.5rem; margin-top: 0.25rem;"></i>
                    <div>
                        <h4 style="color: #10B981; margin-bottom: 0.5rem; font-weight: 600;">Important Information</h4>
                        <p style="color: var(--text-body); line-height: 1.7; margin: 0;">
                            All documents listed above are provided in compliance with applicable regulations and
                            guidelines.
                            These documents are available for public viewing and download. For any queries or
                            clarifications,
                            please contact the school administration.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .disclosure-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
    }

    .disclosure-card:hover .icon-box {
        transform: scale(1.1) rotate(5deg);
    }

    .icon-box {
        transition: all 0.3s ease;
    }
</style>

<?php include 'includes/footer.php'; ?>