<?php
require_once 'includes/functions.php';

$pageTitle = 'Contact Us';
$settings = getAllSettings();

$message = '';
$messageType = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['csrf_token']) && verifyCSRFToken($_POST['csrf_token'])) {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $msg = trim($_POST['message'] ?? '');

        $errors = [];

        if (empty($name))
            $errors[] = 'Name is required';
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL))
            $errors[] = 'Valid email is required';
        if (empty($phone))
            $errors[] = 'Phone is required';
        if (empty($subject))
            $errors[] = 'Subject is required';
        if (empty($msg))
            $errors[] = 'Message is required';

        if (empty($errors)) {
            // Send email to admin
            $adminEmail = getSiteSetting('school_email', 'info@lotusvalley.edu');
            $emailSubject = "Contact Form: " . $subject;
            $emailBody = "
                <h2>New Contact Form Submission</h2>
                <p><strong>Name:</strong> $name</p>
                <p><strong>Email:</strong> $email</p>
                <p><strong>Phone:</strong> $phone</p>
                <p><strong>Subject:</strong> $subject</p>
                <p><strong>Message:</strong></p>
                <p>$msg</p>
            ";

            if (sendEmail($adminEmail, $emailSubject, $emailBody, $email)) {
                $message = 'Thank you for contacting us! We will get back to you soon.';
                $messageType = 'success';
            } else {
                $message = 'Sorry, there was an error sending your message. Please try again or contact us directly.';
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
            <span>Contact Us</span>
        </div>
        <h1>Get in <span style="color: #F59E0B;">Touch</span></h1>
        <p>We'd love to hear from you. Reach out to us for any queries!</p>
    </div>
    <div class="wave-bottom">
        <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 64C240 96 480 96 720 64C960 32 1200 32 1440 64V80H0V64Z" fill="#F5F7FB" />
        </svg>
    </div>
</section>

<!-- Contact Section -->
<section class="section bg-light">
    <div class="container">
        <!-- Contact Info Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <div class="card"
                style="background: linear-gradient(135deg, #0D9488, #0F766E); color: white; text-align: center;">
                <div class="card-body" style="padding: 1.5rem;">
                    <div
                        style="width: 3rem; height: 3rem; background: rgba(255,255,255,0.2); border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 1.25rem;">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h4 style="color: white; margin-bottom: 0.5rem; font-size: 1.1rem;">Visit Us</h4>
                    <p style="opacity: 0.9; font-size: 0.85rem;">
                        <?php echo clean($settings['school_address'] ?? 'Education City, New Delhi'); ?>
                    </p>
                    <a href="https://maps.google.com/?q=Lotus+Valley+School" target="_blank"
                        style="display: inline-flex; align-items: center; gap: 0.5rem; color: white; opacity: 0.8; font-size: 0.8rem; margin-top: 0.75rem;">
                        <i class="fas fa-external-link-alt"></i> Get Directions
                    </a>
                </div>
            </div>

            <div class="card"
                style="background: linear-gradient(135deg, #F59E0B, #D97706); color: white; text-align: center;">
                <div class="card-body" style="padding: 1.5rem;">
                    <div
                        style="width: 3rem; height: 3rem; background: rgba(255,255,255,0.2); border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 1.25rem;">
                        <i class="fas fa-phone"></i>
                    </div>
                    <h4 style="color: white; margin-bottom: 0.5rem; font-size: 1.1rem;">Call Us</h4>
                    <a href="tel:9896421785"
                        style="display: block; color: white; font-size: 1.1rem; font-weight: 700;">9896421785</a>
                    <a href="tel:8950081785"
                        style="display: block; color: white; font-size: 1.1rem; font-weight: 700; margin-top: 0.1rem;">8950081785</a>
                    <p style="opacity: 0.8; font-size: 0.8rem; margin-top: 0.75rem;">
                        <i class="fas fa-clock" style="margin-right: 0.5rem;"></i> Mon - Sat: 8:00 AM - 3:00 PM
                    </p>
                </div>
            </div>

            <div class="card"
                style="background: linear-gradient(135deg, #EC4899, #DB2777); color: white; text-align: center;">
                <div class="card-body" style="padding: 1.5rem;">
                    <div
                        style="width: 3rem; height: 3rem; background: rgba(255,255,255,0.2); border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 1.25rem;">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h4 style="color: white; margin-bottom: 0.5rem; font-size: 1.1rem;">Email Us</h4>
                    <a href="mailto:info@lotusvalley.edu"
                        style="display: block; color: white; font-size: 0.85rem;">info@lotusvalley.edu</a>
                    <a href="mailto:admissions@lotusvalley.edu"
                        style="display: block; color: white; font-size: 0.85rem; margin-top: 0.1rem;">admissions@lotusvalley.edu</a>
                    <p style="opacity: 0.8; font-size: 0.8rem; margin-top: 0.75rem;">
                        <i class="fas fa-info-circle" style="margin-right: 0.5rem;"></i> Reply within 24 hours
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Contact Form -->
            <div class="card">
                <div class="card-body" style="padding: 2rem;">
                    <h3 class="mb-6">Send Us a <span style="color: var(--color-primary);">Message</span></h3>
                    <p style="color: var(--text-muted); margin-bottom: 1.5rem;">We'd love to hear from you. Fill out the
                        form below.</p>

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

                        <div class="form-group">
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="name" required class="form-input"
                                placeholder="Enter your full name">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label class="form-label">Email *</label>
                                <input type="email" name="email" required class="form-input"
                                    placeholder="your@email.com">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Phone *</label>
                                <input type="tel" name="phone" required class="form-input" placeholder="9896421785">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Subject *</label>
                            <input type="text" name="subject" required class="form-input"
                                placeholder="What is this regarding?">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Message *</label>
                            <textarea name="message" rows="5" required class="form-input"
                                placeholder="Write your message here..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-full" style="justify-content: center;">
                            <i class="fas fa-paper-plane"></i> Send Message
                        </button>
                    </form>
                </div>
            </div>

            <!-- Map & Office Hours -->
            <div>
                <!-- Map -->
                <div class="card mb-6">
                    <div class="card-body" style="padding: 1.5rem;">
                        <h4 class="flex items-center gap-3 mb-4">
                            <div class="icon-box icon-box-sm"><i class="fas fa-map-marked-alt"></i></div>
                            Find Us on Map
                        </h4>
                        <div style="height: 180px; border-radius: 0.5rem; overflow: hidden;">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3502.1339866799387!2d77.2090212!3d28.6139391!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjjCsDM2JzUwLjIiTiA3N8KwMTInMjUuNSJF!5e0!3m2!1sen!2sin!4v1234567890"
                                width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                        </div>
                    </div>
                </div>

                <!-- Office Hours -->
                <div class="card mb-6"
                    style="border: 2px solid rgba(37, 99, 235, 0.1); background: linear-gradient(135deg, rgba(37, 99, 235, 0.02), rgba(37, 99, 235, 0.05));">
                    <div class="card-body" style="padding: 1.5rem;">
                        <h4 class="flex items-center gap-3 mb-4">
                            <div class="icon-box icon-box-sm" style="background: #F59E0B;"><i class="fas fa-clock"></i>
                            </div>
                            Office Hours
                        </h4>
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            <div class="flex justify-between items-center"
                                style="padding-bottom: 1rem; border-bottom: 1px solid var(--border-light);">
                                <span style="font-weight: 500;">Monday - Friday</span>
                                <span class="badge badge-primary">8:00 AM - 4:00 PM</span>
                            </div>
                            <div class="flex justify-between items-center"
                                style="padding-bottom: 1rem; border-bottom: 1px solid var(--border-light);">
                                <span style="font-weight: 500;">Saturday</span>
                                <span class="badge badge-secondary">8:00 AM - 1:00 PM</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span style="font-weight: 500;">Sunday</span>
                                <span
                                    style="background: rgba(239, 68, 68, 0.1); color: #DC2626; padding: 0.375rem 1rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 600;">Closed</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="card"
                    style="border: 2px solid rgba(245, 158, 11, 0.1); background: linear-gradient(135deg, rgba(245, 158, 11, 0.02), rgba(245, 158, 11, 0.05));">
                    <div class="card-body" style="padding: 1.5rem;">
                        <h4 class="mb-4">Quick Links</h4>
                        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                            <a href="admission.php" class="flex items-center gap-3"
                                style="padding: 1rem; background: white; border-radius: 0.75rem; color: var(--text-body); transition: all 0.3s ease;">
                                <div class="icon-box icon-box-sm"><i class="fas fa-graduation-cap"></i></div>
                                <div style="flex: 1;">
                                    <strong style="display: block; color: var(--text-heading);">Admission
                                        Inquiry</strong>
                                    <small style="color: var(--text-muted);">Apply for admission</small>
                                </div>
                                <i class="fas fa-chevron-right" style="color: var(--text-muted);"></i>
                            </a>
                            <a href="about.php" class="flex items-center gap-3"
                                style="padding: 1rem; background: white; border-radius: 0.75rem; color: var(--text-body); transition: all 0.3s ease;">
                                <div class="icon-box icon-box-sm" style="background: #F59E0B;"><i
                                        class="fas fa-info-circle"></i></div>
                                <div style="flex: 1;">
                                    <strong style="display: block; color: var(--text-heading);">About Our
                                        School</strong>
                                    <small style="color: var(--text-muted);">Learn more about us</small>
                                </div>
                                <i class="fas fa-chevron-right" style="color: var(--text-muted);"></i>
                            </a>
                            <a href="gallery.php" class="flex items-center gap-3"
                                style="padding: 1rem; background: white; border-radius: 0.75rem; color: var(--text-body); transition: all 0.3s ease;">
                                <div class="icon-box icon-box-sm" style="background: #EC4899;"><i
                                        class="fas fa-images"></i></div>
                                <div style="flex: 1;">
                                    <strong style="display: block; color: var(--text-heading);">Photo Gallery</strong>
                                    <small style="color: var(--text-muted);">View our gallery</small>
                                </div>
                                <i class="fas fa-chevron-right" style="color: var(--text-muted);"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>