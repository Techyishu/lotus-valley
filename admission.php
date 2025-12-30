<?php
require_once 'includes/functions.php';

$pageTitle = 'Admission Inquiry';
$settings = getAllSettings();

$message = '';
$messageType = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['csrf_token']) && verifyCSRFToken($_POST['csrf_token'])) {
        $studentName = trim($_POST['student_name'] ?? '');
        $parentName = trim($_POST['parent_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $classApplying = trim($_POST['class_applying'] ?? '');
        $previousSchool = trim($_POST['previous_school'] ?? '');
        $msg = trim($_POST['message'] ?? '');

        $errors = [];

        if (empty($studentName))
            $errors[] = 'Student name is required';
        if (empty($parentName))
            $errors[] = 'Parent name is required';
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL))
            $errors[] = 'Valid email is required';
        if (empty($phone))
            $errors[] = 'Phone is required';
        if (empty($classApplying))
            $errors[] = 'Class is required';

        if (empty($errors)) {
            global $pdo;

            try {
                $stmt = $pdo->prepare("
                    INSERT INTO admission_inquiries 
                    (student_name, parent_name, email, phone, class_applying, previous_school, message) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)
                ");

                $stmt->execute([
                    $studentName,
                    $parentName,
                    $email,
                    $phone,
                    $classApplying,
                    $previousSchool,
                    $msg
                ]);

                // Send email to admin
                $adminEmail = getSiteSetting('school_email', 'info@lotusvalley.edu');
                $emailSubject = "New Admission Inquiry: " . $studentName;
                $emailBody = "
                    <h2>New Admission Inquiry Received</h2>
                    <p><strong>Student Name:</strong> $studentName</p>
                    <p><strong>Parent Name:</strong> $parentName</p>
                    <p><strong>Email:</strong> $email</p>
                    <p><strong>Phone:</strong> $phone</p>
                    <p><strong>Class Applying For:</strong> $classApplying</p>
                    <p><strong>Previous School:</strong> $previousSchool</p>
                    <p><strong>Message:</strong></p>
                    <p>$msg</p>
                ";

                sendEmail($adminEmail, $emailSubject, $emailBody, $email);

                $message = 'Thank you for your interest! Your admission inquiry has been submitted successfully. We will contact you soon.';
                $messageType = 'success';
            } catch (PDOException $e) {
                $message = 'Sorry, there was an error processing your request. Please try again.';
                $messageType = 'error';
            }
        } else {
            $message = implode('<br>', $errors);
            $messageType = 'error';
        }
    } else {
        $message = 'Invalid security token. Please try again.';
        $messageType = 'error';
    }
}

include 'includes/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <div class="breadcrumb">
            <a href="index.php">Home</a>
            <span>/</span>
            <span>Admission</span>
        </div>
        <h1>Begin Your <span style="color: #F59E0B;">Journey</span></h1>
        <p>Take the first step towards a bright and successful future at Lotus Valley</p>
    </div>
    <div class="wave-bottom">
        <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 64C240 96 480 96 720 64C960 32 1200 32 1440 64V80H0V64Z" fill="#F5F7FB" />
        </svg>
    </div>
</section>

<!-- Admission Process -->
<section class="section bg-light">
    <div class="container">
        <div class="section-header">
            <h2>Admission <span style="color: var(--color-primary);">Process</span></h2>
            <p>Follow these simple steps to join the Lotus Valley family</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="feature-card">
                <div
                    style="width: 4rem; height: 4rem; background: var(--color-primary); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: 700; margin: 0 auto 1.5rem;">
                    1</div>
                <h4>Inquiry Form</h4>
                <p>Fill out the admission inquiry form below with all required details</p>
            </div>

            <div class="feature-card">
                <div
                    style="width: 4rem; height: 4rem; background: #F59E0B; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: 700; margin: 0 auto 1.5rem;">
                    2</div>
                <h4>Verification</h4>
                <p>Our team will review and contact you within 2-3 business days</p>
            </div>

            <div class="feature-card">
                <div
                    style="width: 4rem; height: 4rem; background: #EC4899; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: 700; margin: 0 auto 1.5rem;">
                    3</div>
                <h4>Interview</h4>
                <p>Meet with our principal and faculty for assessment and counseling</p>
            </div>

            <div class="feature-card">
                <div
                    style="width: 4rem; height: 4rem; background: #10B981; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: 700; margin: 0 auto 1.5rem;">
                    4</div>
                <h4>Enrollment</h4>
                <p>Complete documentation and payment to secure admission</p>
            </div>
        </div>
    </div>
</section>

<!-- Admission Form -->
<section class="section bg-white" id="admission-form">
    <div class="container">
        <div style="max-width: 48rem; margin: 0 auto;">
            <div class="card" style="border: 1px solid var(--border-light);">
                <div class="card-body" style="padding: 2.5rem;">
                    <div class="text-center mb-8">
                        <div class="icon-box icon-box-lg"
                            style="margin: 0 auto 1.5rem; background: linear-gradient(135deg, #F59E0B, #D97706);">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h2>Admission <span style="color: var(--color-primary);">Inquiry Form</span></h2>
                        <p style="color: var(--text-muted);">Please provide the following information to begin the
                            admission process</p>
                    </div>

                    <?php if ($message): ?>
                        <div
                            style="padding: 1rem 1.5rem; border-radius: 0.75rem; margin-bottom: 1.5rem; <?php echo $messageType === 'success' ? 'background: rgba(16, 185, 129, 0.1); color: #059669; border: 1px solid rgba(16, 185, 129, 0.3);' : 'background: rgba(239, 68, 68, 0.1); color: #DC2626; border: 1px solid rgba(239, 68, 68, 0.3);'; ?>">
                            <div class="flex items-center gap-3">
                                <i
                                    class="fas <?php echo $messageType === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
                                <div><?php echo $message; ?></div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">

                        <!-- Student Information -->
                        <div style="margin-bottom: 2rem;">
                            <h3 class="flex items-center gap-3 mb-6">
                                <div class="icon-box icon-box-sm"><i class="fas fa-user"></i></div>
                                Student Information
                            </h3>

                            <div class="form-group">
                                <label class="form-label">Student's Full Name *</label>
                                <input type="text" name="student_name" required class="form-input"
                                    placeholder="Enter student's full name">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Class Applying For *</label>
                                <select name="class_applying" required class="form-input">
                                    <option value="">Select Class</option>
                                    <option value="Nursery">Nursery</option>
                                    <option value="LKG">LKG</option>
                                    <option value="UKG">UKG</option>
                                    <option value="Class 1">Class 1</option>
                                    <option value="Class 2">Class 2</option>
                                    <option value="Class 3">Class 3</option>
                                    <option value="Class 4">Class 4</option>
                                    <option value="Class 5">Class 5</option>
                                    <option value="Class 6">Class 6</option>
                                    <option value="Class 7">Class 7</option>
                                    <option value="Class 8">Class 8</option>
                                    <option value="Class 9">Class 9</option>
                                    <option value="Class 10">Class 10</option>
                                    <option value="Class 11">Class 11</option>
                                    <option value="Class 12">Class 12</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Previous School (if applicable)</label>
                                <input type="text" name="previous_school" class="form-input"
                                    placeholder="Name of previous school">
                            </div>
                        </div>

                        <!-- Parent Information -->
                        <div style="margin-bottom: 2rem;">
                            <h3 class="flex items-center gap-3 mb-6">
                                <div class="icon-box icon-box-sm" style="background: #F59E0B;"><i
                                        class="fas fa-users"></i></div>
                                Parent/Guardian Information
                            </h3>

                            <div class="form-group">
                                <label class="form-label">Parent's Full Name *</label>
                                <input type="text" name="parent_name" required class="form-input"
                                    placeholder="Enter parent's full name">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="form-group">
                                    <label class="form-label">Email Address *</label>
                                    <input type="email" name="email" required class="form-input"
                                        placeholder="your@email.com">
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Phone Number *</label>
                                    <input type="tel" name="phone" required class="form-input" placeholder="9896421785">
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="form-group">
                            <label class="form-label">Additional Information / Message</label>
                            <textarea name="message" rows="4" class="form-input"
                                placeholder="Any additional information you'd like to share..."></textarea>
                        </div>

                        <div
                            style="background: rgba(37, 99, 235, 0.1); padding: 1rem 1.5rem; border-radius: 0.75rem; margin-bottom: 1.5rem; border: 1px solid rgba(37, 99, 235, 0.2);">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-info-circle"
                                    style="color: var(--color-primary); margin-top: 0.125rem;"></i>
                                <p style="color: var(--text-body); font-size: 0.9375rem;">
                                    <strong>Note:</strong> This is an inquiry form. Our admission team will contact you
                                    within 2-3 business days to guide you through the complete admission process.
                                </p>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-full" style="justify-content: center;">
                            <i class="fas fa-paper-plane"></i> Submit Admission Inquiry
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Lotus Valley -->
<section class="section bg-light">
    <div class="container">
        <div class="section-header">
            <h2>Why Choose <span style="color: var(--color-primary);">Lotus Valley?</span></h2>
            <p>Discover what makes us the preferred choice for quality education</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="feature-card" style="text-align: left; display: flex; gap: 1rem; align-items: flex-start;">
                <div class="icon-box icon-box-sm" style="flex-shrink: 0;"><i class="fas fa-award"></i></div>
                <div>
                    <h4>Academic Excellence</h4>
                    <p>Proven track record with 99%+ pass rates and numerous toppers</p>
                </div>
            </div>

            <div class="feature-card" style="text-align: left; display: flex; gap: 1rem; align-items: flex-start;">
                <div class="icon-box icon-box-sm" style="flex-shrink: 0; background: #F59E0B;"><i
                        class="fas fa-chalkboard-teacher"></i></div>
                <div>
                    <h4>Experienced Faculty</h4>
                    <p>Highly qualified teachers dedicated to student success</p>
                </div>
            </div>

            <div class="feature-card" style="text-align: left; display: flex; gap: 1rem; align-items: flex-start;">
                <div class="icon-box icon-box-sm" style="flex-shrink: 0; background: #EC4899;"><i
                        class="fas fa-building"></i></div>
                <div>
                    <h4>Modern Infrastructure</h4>
                    <p>State-of-the-art facilities including smart classrooms and labs</p>
                </div>
            </div>

            <div class="feature-card" style="text-align: left; display: flex; gap: 1rem; align-items: flex-start;">
                <div class="icon-box icon-box-sm" style="flex-shrink: 0; background: #10B981;"><i
                        class="fas fa-futbol"></i></div>
                <div>
                    <h4>Sports & Activities</h4>
                    <p>Comprehensive sports program and extracurricular activities</p>
                </div>
            </div>

            <div class="feature-card" style="text-align: left; display: flex; gap: 1rem; align-items: flex-start;">
                <div class="icon-box icon-box-sm" style="flex-shrink: 0; background: #8B5CF6;"><i
                        class="fas fa-shield-alt"></i></div>
                <div>
                    <h4>Safe Environment</h4>
                    <p>24/7 security with CCTV surveillance and GPS tracking</p>
                </div>
            </div>

            <div class="feature-card" style="text-align: left; display: flex; gap: 1rem; align-items: flex-start;">
                <div class="icon-box icon-box-sm" style="flex-shrink: 0; background: #6366F1;"><i
                        class="fas fa-users"></i></div>
                <div>
                    <h4>Holistic Development</h4>
                    <p>Focus on overall personality and character development</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="section" style="background: linear-gradient(135deg, #0D9488, #0F766E); color: white;">
    <div class="container text-center">
        <div style="max-width: 48rem; margin: 0 auto;">
            <h2 style="color: white; margin-bottom: 1rem;">Ready to Join <span style="color: #F59E0B;">Lotus
                    Valley?</span></h2>
            <p style="font-size: 1.25rem; opacity: 0.9; margin-bottom: 2rem;">
                Take the first step towards giving your child the gift of quality education
            </p>
            <div class="flex flex-wrap gap-4 justify-center">
                <a href="#admission-form" class="btn btn-secondary btn-lg btn-pill">
                    <i class="fas fa-file-alt"></i> Fill Admission Form
                </a>
                <a href="contact.php" class="btn btn-lg btn-pill"
                    style="background: rgba(255,255,255,0.1); color: white; border: 2px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-phone"></i> Call Us Now
                </a>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>