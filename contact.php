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
        
        if (empty($name)) $errors[] = 'Name is required';
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required';
        if (empty($phone)) $errors[] = 'Phone is required';
        if (empty($subject)) $errors[] = 'Subject is required';
        if (empty($msg)) $errors[] = 'Message is required';
        
        if (empty($errors)) {
            // Send email to admin
            $adminEmail = getSiteSetting('school_email', 'anthemschool55@gmail.com');
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
<section class="bg-gradient-to-r from-green-600 to-teal-600 text-white py-8 md:py-16">
    <div class="container mx-auto px-4">
        <nav class="text-xs md:text-sm mb-3 md:mb-4">
            <a href="index.php" class="hover:text-green-200">Home</a>
            <span class="mx-2">/</span>
            <span>Contact</span>
        </nav>
        <h1 class="text-2xl md:text-4xl lg:text-5xl font-bold">Get in Touch</h1>
        <p class="text-sm md:text-xl text-green-100 mt-2 md:mt-4">We'd love to hear from you</p>
    </div>
</section>

<!-- Contact Section -->
<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-16">
            <!-- Contact Info Cards -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-2xl p-8 hover-lift">
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-map-marker-alt text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-3">Visit Us</h3>
                <p class="text-blue-100"><?php echo clean($settings['school_address'] ?? 'Education City, New Delhi'); ?></p>
            </div>
            
            <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-2xl p-8 hover-lift">
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-phone text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-3">Call Us</h3>
                <a href="tel:9896421785" class="text-green-100 hover:text-white">
                    9896421785 / 8950081785
                </a>
            </div>
            
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-2xl p-8 hover-lift">
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-envelope text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-3">Email Us</h3>
                <a href="mailto:anthemschool55@gmail.com" class="text-purple-100 hover:text-white break-all">
                    anthemschool55@gmail.com
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Contact Form -->
            <div>
                <h2 class="text-3xl font-bold text-gray-800 mb-6">Send Us a Message</h2>
                
                <?php if ($message): ?>
                    <div class="mb-6 p-4 rounded-lg <?php echo $messageType === 'success' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200'; ?>">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="" class="space-y-4">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Full Name *</label>
                        <input type="text" name="name" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                               placeholder="Enter your name">
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Email *</label>
                            <input type="email" name="email" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   placeholder="your@email.com">
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Phone *</label>
                            <input type="tel" name="phone" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   placeholder="9896421785">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Subject *</label>
                        <input type="text" name="subject" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                               placeholder="What is this regarding?">
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Message *</label>
                        <textarea name="message" rows="5" required
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                  placeholder="Write your message here..."></textarea>
                    </div>
                    
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-green-600 to-teal-600 text-white py-3 rounded-lg font-semibold hover:from-green-700 hover:to-teal-700 transition shadow-lg">
                        <i class="fas fa-paper-plane mr-2"></i>Send Message
                    </button>
                </form>
            </div>

            <!-- Map & Office Hours -->
            <div class="space-y-8">
                <div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Find Us on Map</h3>
                    <div class="bg-gray-200 rounded-2xl overflow-hidden h-64">
                        <!-- Replace with actual Google Maps embed -->
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3502.1339866799387!2d77.2090212!3d28.6139391!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjjCsDM2JzUwLjIiTiA3N8KwMTInMzIuNSJF!5e0!3m2!1sen!2sin!4v1234567890"
                                width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-8">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6">Office Hours</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center pb-4 border-b border-gray-300">
                            <span class="font-semibold text-gray-700">Monday - Friday</span>
                            <span class="text-blue-600 font-bold">8:00 AM - 4:00 PM</span>
                        </div>
                        <div class="flex justify-between items-center pb-4 border-b border-gray-300">
                            <span class="font-semibold text-gray-700">Saturday</span>
                            <span class="text-blue-600 font-bold">8:00 AM - 1:00 PM</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-semibold text-gray-700">Sunday</span>
                            <span class="text-red-600 font-bold">Closed</span>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Quick Links</h3>
                    <div class="space-y-3">
                        <a href="admission.php" class="flex items-center text-gray-700 hover:text-purple-600 transition">
                            <i class="fas fa-graduation-cap mr-3 text-purple-600"></i>
                            Admission Inquiry
                        </a>
                        <a href="about.php" class="flex items-center text-gray-700 hover:text-purple-600 transition">
                            <i class="fas fa-info-circle mr-3 text-purple-600"></i>
                            About Our School
                        </a>
                        <a href="gallery.php" class="flex items-center text-gray-700 hover:text-purple-600 transition">
                            <i class="fas fa-images mr-3 text-purple-600"></i>
                            Photo Gallery
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

