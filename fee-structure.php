<?php
require_once 'includes/functions.php';

$pageTitle = 'Fee Structure';

// Fetch all fee structures
try {
    $feeStmt = $pdo->query("SELECT * FROM fee_structure ORDER BY display_order, class_name");
    $feeResult = $feeStmt->fetchAll();
} catch (PDOException $e) {
    $feeResult = [];
}

include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="page-hero"
    style="background: linear-gradient(135deg, #3B82F6, #2563EB); padding: 4rem 0; color: white; position: relative; overflow: hidden;">
    <div
        style="position: absolute; inset: 0; background: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.05\'%3E%3Cpath d=\'M30 30c0-5.52 4.48-10 10-10V10C30 10 22.18 14.48 16 20.66V30h14z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');">
    </div>
    <div class="container" style="position: relative; z-index: 1;">
        <div style="text-align: center; max-width: 48rem; margin: 0 auto;">
            <div
                style="display: inline-block; background: rgba(255,255,255,0.2); padding: 0.5rem 1.5rem; border-radius: 2rem; margin-bottom: 1.5rem;">
                <span style="font-size: 0.875rem; font-weight: 600;"><i class="fas fa-money-bill-wave"></i> Transparent
                    Pricing</span>
            </div>
            <h1 style="font-size: 3rem; font-weight: 700; margin-bottom: 1rem; color: white;">FEE STRUCTURE</h1>
            <nav style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; font-size: 0.875rem;">
                <a href="index.php" style="color: rgba(255,255,255,0.9); text-decoration: none;"><i
                        class="fas fa-home"></i> Home</a>
                <span style="color: rgba(255,255,255,0.7);">/</span>
                <span style="color: white;">Fee Structure</span>
            </nav>
        </div>
    </div>
</section>

<!-- Main Content Section -->
<section class="section bg-light">
    <div class="container">
        <div class="section-header">
            <span class="badge badge-primary"><i class="fas fa-money-bill-wave"></i> Fees</span>
            <h2>Fee Structure</h2>
            <p>Transparent and affordable fee structure for the academic year</p>
        </div>

        <?php if (empty($feeResult)): ?>
            <div class="card text-center" style="padding: 4rem;">
                <i class="fas fa-money-bill-wave" style="font-size: 4rem; color: var(--border-light); margin-bottom: 1rem;"></i>
                <h3 style="color: var(--text-muted); font-weight: 500;">No fee structure available</h3>
                <p style="color: var(--text-muted);">Please check back later</p>
            </div>
        <?php else: ?>
            <div style="overflow-x: auto;">
                <table class="table" style="background: white; border-radius: 1rem; overflow: hidden; box-shadow: var(--shadow-md);">
                    <thead>
                        <tr style="background: linear-gradient(135deg, #3B82F6, #2563EB); color: white;">
                            <th style="padding: 1.25rem 1.5rem; text-align: left; font-weight: 600;">Class</th>
                            <th style="padding: 1.25rem 1.5rem; text-align: right; font-weight: 600;">Admission Fee</th>
                            <th style="padding: 1.25rem 1.5rem; text-align: right; font-weight: 600;">Tuition Fee</th>
                            <th style="padding: 1.25rem 1.5rem; text-align: right; font-weight: 600;">Annual Charges</th>
                            <th style="padding: 1.25rem 1.5rem; text-align: right; font-weight: 600;">Total Fee</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $totalAdmission = 0;
                        $totalTuition = 0;
                        $totalAnnual = 0;
                        $totalAll = 0;
                        ?>

                        <?php foreach ($feeResult as $index => $fee): ?>
                            <?php
                            $totalAdmission += $fee['admission_fee'];
                            $totalTuition += $fee['tuition_fee'];
                            $totalAnnual += $fee['annual_charges'];
                            $totalAll += $fee['total_fee'];
                            ?>
                            <tr style="<?php echo $index % 2 === 0 ? 'background: #F8FAFC;' : ''; ?>">
                                <td style="padding: 1rem 1.5rem;">
                                    <strong style="color: var(--text-heading); font-size: 1.0625rem;">
                                        <?php echo clean($fee['class_name']); ?>
                                    </strong>
                                    <?php if ($fee['notes']): ?>
                                        <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">
                                            <?php echo clean($fee['notes']); ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td style="padding: 1rem 1.5rem; text-align: right; color: var(--text-body);">
                                    ₹<?php echo number_format($fee['admission_fee'], 2); ?>
                                </td>
                                <td style="padding: 1rem 1.5rem; text-align: right; color: var(--text-body);">
                                    ₹<?php echo number_format($fee['tuition_fee'], 2); ?>
                                </td>
                                <td style="padding: 1rem 1.5rem; text-align: right; color: var(--text-body);">
                                    ₹<?php echo number_format($fee['annual_charges'], 2); ?>
                                </td>
                                <td style="padding: 1rem 1.5rem; text-align: right;">
                                    <strong style="color: #3B82F6; font-size: 1.0625rem;">
                                        ₹<?php echo number_format($fee['total_fee'], 2); ?>
                                    </strong>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <!-- Total Row -->
                        <tr style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(37, 99, 235, 0.05)); font-weight: 700;">
                            <td style="padding: 1rem 1.5rem; color: #3B82F6;">
                                <strong>Total</strong>
                            </td>
                            <td style="padding: 1rem 1.5rem; text-align: right; color: #3B82F6;">
                                ₹<?php echo number_format($totalAdmission, 2); ?>
                            </td>
                            <td style="padding: 1rem 1.5rem; text-align: right; color: #3B82F6;">
                                ₹<?php echo number_format($totalTuition, 2); ?>
                            </td>
                            <td style="padding: 1rem 1.5rem; text-align: right; color: #3B82F6;">
                                ₹<?php echo number_format($totalAnnual, 2); ?>
                            </td>
                            <td style="padding: 1rem 1.5rem; text-align: right; color: #3B82F6;">
                                <strong style="font-size: 1.125rem;">₹<?php echo number_format($totalAll, 2); ?></strong>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <!-- Important Notes Section -->
        <div style="display: grid; grid-cols: 1 md:grid-cols-2 gap-6; margin-top: 3rem;">
            <div class="card"
                style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(37, 99, 235, 0.05)); border-left: 4px solid #3B82F6;">
                <div class="card-body">
                    <div style="display: flex; gap: 1rem; align-items: start;">
                        <i class="fas fa-info-circle" style="color: #3B82F6; font-size: 1.5rem; margin-top: 0.25rem;"></i>
                        <div>
                            <h4 style="color: #3B82F6; margin-bottom: 0.5rem; font-weight: 600;">Fee Payment Schedule</h4>
                            <p style="color: var(--text-body); line-height: 1.7; margin: 0; font-size: 0.9375rem;">
                                Fees can be paid quarterly or annually. A 5% discount is available for full annual fee
                                payment at the time of admission.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card"
                style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(37, 99, 235, 0.05)); border-left: 4px solid #3B82F6;">
                <div class="card-body">
                    <div style="display: flex; gap: 1rem; align-items: start;">
                        <i class="fas fa-credit-card" style="color: #3B82F6; font-size: 1.5rem; margin-top: 0.25rem;"></i>
                        <div>
                            <h4 style="color: #3B82F6; margin-bottom: 0.5rem; font-weight: 600;">Payment Methods</h4>
                            <p style="color: var(--text-body); line-height: 1.7; margin: 0; font-size: 0.9375rem;">
                                We accept cash, cheque, demand draft, and online payments. All payments should be made
                                payable to "Lotus Valley School".
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Section -->
        <div class="card"
            style="margin-top: 2rem; background: linear-gradient(135deg, rgba(59, 130, 246, 0.05), rgba(37, 99, 235, 0.02)); border: 2px solid rgba(59, 130, 246, 0.2);">
            <div class="card-body" style="text-align: center;">
                <i class="fas fa-phone-alt" style="color: #3B82F6; font-size: 2rem; margin-bottom: 1rem;"></i>
                <h3 style="color: #3B82F6; margin-bottom: 0.5rem; font-weight: 600;">Need More Information?</h3>
                <p style="color: var(--text-body); line-height: 1.7; margin: 0 0 1rem 0;">
                    For fee-related queries, payment schedules, or scholarship information, please contact our accounts
                    department.
                </p>
                <a href="contact.php"
                    style="display: inline-block; background: linear-gradient(135deg, #3B82F6, #2563EB); color: white; padding: 0.75rem 2rem; border-radius: 2rem; text-decoration: none; font-weight: 600; transition: all 0.3s ease;">
                    <i class="fas fa-envelope"></i> Contact Us
                </a>
            </div>
        </div>
    </div>
</section>

<style>
    .table tbody tr {
        transition: all 0.2s ease;
    }

    .table tbody tr:hover {
        background: #EFF6FF !important;
        transform: scale(1.01);
    }
</style>

<?php include 'includes/footer.php'; ?>
