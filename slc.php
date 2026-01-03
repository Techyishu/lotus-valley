<?php
require_once 'includes/functions.php';

$pageTitle = 'School Leaving Certificates';

// Fetch all SLC items
try {
    $slcStmt = $pdo->query("SELECT * FROM slc ORDER BY display_order, title");
    $slcResult = $slcStmt->fetchAll();
} catch (PDOException $e) {
    $slcResult = [];
}

include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="page-hero"
    style="background: linear-gradient(135deg, #8B5CF6, #7C3AED); padding: 4rem 0; color: white; position: relative; overflow: hidden;">
    <div
        style="position: absolute; inset: 0; background: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.05\'%3E%3Cpath d=\'M30 30c0-5.52 4.48-10 10-10V10C30 10 22.18 14.48 16 20.66V30h14z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');">
    </div>
    <div class="container" style="position: relative; z-index: 1;">
        <div style="text-align: center; max-width: 48rem; margin: 0 auto;">
            <div
                style="display: inline-block; background: rgba(255,255,255,0.2); padding: 0.5rem 1.5rem; border-radius: 2rem; margin-bottom: 1.5rem;">
                <span style="font-size: 0.875rem; font-weight: 600;"><i class="fas fa-certificate"></i> Official Documents</span>
            </div>
            <h1 style="font-size: 3rem; font-weight: 700; margin-bottom: 1rem; color: white;">SCHOOL LEAVING CERTIFICATES</h1>
            <nav style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; font-size: 0.875rem;">
                <a href="index.php" style="color: rgba(255,255,255,0.9); text-decoration: none;"><i
                        class="fas fa-home"></i> Home</a>
                <span style="color: rgba(255,255,255,0.7);">/</span>
                <span style="color: white;">School Leaving Certificates</span>
            </nav>
        </div>
    </div>
</section>

<!-- Main Content Section -->
<section class="section bg-light">
    <div class="container">
        <div class="section-header">
            <span class="badge badge-primary"><i class="fas fa-certificate"></i> Certificates</span>
            <h2>School Leaving Certificates</h2>
            <p>Download official school leaving certificates and transfer certificates</p>
        </div>

        <?php if (empty($slcResult)): ?>
            <div class="card text-center" style="padding: 4rem;">
                <i class="fas fa-certificate" style="font-size: 4rem; color: var(--border-light); margin-bottom: 1rem;"></i>
                <h3 style="color: var(--text-muted); font-weight: 500;">No certificates available</h3>
                <p style="color: var(--text-muted);">Please check back later or contact school administration</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($slcResult as $item): ?>
                    <div class="card slc-card" style="border-top: 4px solid #8B5CF6;">
                        <?php if ($item['file_type'] === 'pdf'): ?>
                            <a href="uploads/slc/<?php echo clean($item['file_path']); ?>" target="_blank"
                                style="text-decoration: none; display: block;">
                                <div class="card-body">
                                    <div class="icon-box"
                                        style="background: linear-gradient(135deg, #8B5CF6, #7C3AED); margin-bottom: 1.5rem;">
                                        <i class="fas fa-file-pdf"></i>
                                    </div>
                                    <h3 style="font-size: 1.25rem; margin-bottom: 0.75rem; color: var(--text-heading);">
                                        <?php echo clean($item['title']); ?>
                                    </h3>
                                    <?php if ($item['description']): ?>
                                        <p style="color: var(--text-muted); font-size: 0.875rem; line-height: 1.6;">
                                            <?php echo clean(substr($item['description'], 0, 100)); ?>
                                            <?php echo strlen($item['description']) > 100 ? '...' : ''; ?>
                                        </p>
                                    <?php endif; ?>
                                    <div style="margin-top: 1rem;">
                                        <span style="color: #8B5CF6; font-size: 0.875rem; font-weight: 600;">
                                            <i class="fas fa-download"></i> Download Certificate
                                        </span>
                                    </div>
                                </div>
                            </a>
                        <?php else: ?>
                            <a href="uploads/slc/<?php echo clean($item['file_path']); ?>" target="_blank"
                                style="text-decoration: none; display: block;">
                                <div style="aspect-ratio: 16/10; overflow: hidden; border-top-left-radius: 0.75rem; border-top-right-radius: 0.75rem;">
                                    <img src="uploads/slc/<?php echo clean($item['file_path']); ?>"
                                        alt="<?php echo clean($item['title']); ?>"
                                        style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                                <div class="card-body">
                                    <h3 style="font-size: 1.25rem; margin-bottom: 0.75rem; color: var(--text-heading);">
                                        <?php echo clean($item['title']); ?>
                                    </h3>
                                    <?php if ($item['description']): ?>
                                        <p style="color: var(--text-muted); font-size: 0.875rem; line-height: 1.6;">
                                            <?php echo clean(substr($item['description'], 0, 100)); ?>
                                            <?php echo strlen($item['description']) > 100 ? '...' : ''; ?>
                                        </p>
                                    <?php endif; ?>
                                    <div style="margin-top: 1rem;">
                                        <span style="color: #8B5CF6; font-size: 0.875rem; font-weight: 600;">
                                            <i class="fas fa-expand"></i> View Certificate
                                        </span>
                                    </div>
                                </div>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Note Section -->
        <div class="card"
            style="margin-top: 3rem; background: linear-gradient(135deg, rgba(139, 92, 246, 0.1), rgba(124, 58, 237, 0.05)); border-left: 4px solid #8B5CF6;">
            <div class="card-body">
                <div style="display: flex; gap: 1rem; align-items: start;">
                    <i class="fas fa-info-circle" style="color: #8B5CF6; font-size: 1.5rem; margin-top: 0.25rem;"></i>
                    <div>
                        <h4 style="color: #8B5CF6; margin-bottom: 0.5rem; font-weight: 600;">Important Information</h4>
                        <p style="color: var(--text-body); line-height: 1.7; margin: 0;">
                            School Leaving Certificates are issued upon completion of education or upon transfer to another
                            institution. For obtaining a new certificate, please contact the school administration with
                            necessary documents including student ID, previous academic records, and parents' identity
                            proof. Processing time is typically 3-5 working days.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Section -->
        <div class="card"
            style="margin-top: 2rem; background: linear-gradient(135deg, rgba(139, 92, 246, 0.05), rgba(124, 58, 237, 0.02)); border: 2px solid rgba(139, 92, 246, 0.2);">
            <div class="card-body" style="text-align: center;">
                <i class="fas fa-phone-alt" style="color: #8B5CF6; font-size: 2rem; margin-bottom: 1rem;"></i>
                <h3 style="color: #8B5CF6; margin-bottom: 0.5rem; font-weight: 600;">Need a Certificate?</h3>
                <p style="color: var(--text-body); line-height: 1.7; margin: 0 0 1rem 0;">
                    Contact the school office to request a School Leaving Certificate or Transfer Certificate.
                </p>
                <a href="contact.php"
                    style="display: inline-block; background: linear-gradient(135deg, #8B5CF6, #7C3AED); color: white; padding: 0.75rem 2rem; border-radius: 2rem; text-decoration: none; font-weight: 600; transition: all 0.3s ease;">
                    <i class="fas fa-envelope"></i> Contact Office
                </a>
            </div>
        </div>
    </div>
</section>

<style>
    .slc-card {
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .slc-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgba(139, 92, 246, 0.2);
    }

    .slc-card:hover .icon-box {
        transform: scale(1.1) rotate(5deg);
    }

    .icon-box {
        transition: all 0.3s ease;
    }
</style>

<?php include 'includes/footer.php'; ?>
