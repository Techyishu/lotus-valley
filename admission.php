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
        
        if (empty($studentName)) $errors[] = 'Student name is required';
        if (empty($parentName)) $errors[] = 'Parent name is required';
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required';
        if (empty($phone)) $errors[] = 'Phone is required';
        if (empty($classApplying)) $errors[] = 'Class is required';
        
        if (empty($errors)) {
            global $pdo;
            
            try {
                $stmt = $pdo->prepare("
                    INSERT INTO admission_inquiries 
                    (student_name, parent_name, email, phone, class_applying, previous_school, message) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)
                ");
                
                $stmt->execute([
                    $studentName, $parentName, $email, $phone, 
                    $classApplying, $previousSchool, $msg
                ]);
                
                // Send email to admin
                $adminEmail = getSiteSetting('school_email', 'anthemschool55@gmail.com');
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
<section class="bg-gradient-to-r from-orange-600 to-red-600 text-white py-8 md:py-16">
    <div class="container mx-auto px-4">
        <nav class="text-xs md:text-sm mb-3 md:mb-4">
            <a href="index.php" class="hover:text-orange-200">Home</a>
            <span class="mx-2">/</span>
            <span>Admission</span>
        </nav>
        <h1 class="text-2xl md:text-4xl lg:text-5xl font-bold">Admission Inquiry</h1>
        <p class="text-sm md:text-xl text-orange-100 mt-2 md:mt-4">Take the first step towards a bright future</p>
    </div>
</section>

<!-- Admission Process -->
<section class="py-8 md:py-16 bg-gradient-to-br from-blue-50 to-indigo-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-8 md:mb-12">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-3 md:mb-4">Admission Process</h2>
            <p class="text-sm md:text-base text-gray-600 max-w-2xl mx-auto px-4">Follow these simple steps to join our school family</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
            <div class="text-center">
                <div class="w-20 h-20 bg-blue-600 text-white rounded-full flex items-center justify-center text-3xl font-bold mx-auto mb-4">
                    1
                </div>
                <h3 class="font-bold text-gray-800 mb-2">Inquiry Form</h3>
                <p class="text-sm text-gray-600">Fill out the admission inquiry form below</p>
            </div>

            <div class="text-center">
                <div class="w-20 h-20 bg-indigo-600 text-white rounded-full flex items-center justify-center text-3xl font-bold mx-auto mb-4">
                    2
                </div>
                <h3 class="font-bold text-gray-800 mb-2">Verification</h3>
                <p class="text-sm text-gray-600">Our team will review and contact you</p>
            </div>

            <div class="text-center">
                <div class="w-20 h-20 bg-purple-600 text-white rounded-full flex items-center justify-center text-3xl font-bold mx-auto mb-4">
                    3
                </div>
                <h3 class="font-bold text-gray-800 mb-2">Interview</h3>
                <p class="text-sm text-gray-600">Meet with the principal and faculty</p>
            </div>

            <div class="text-center">
                <div class="w-20 h-20 bg-green-600 text-white rounded-full flex items-center justify-center text-3xl font-bold mx-auto mb-4">
                    4
                </div>
                <h3 class="font-bold text-gray-800 mb-2">Enrollment</h3>
                <p class="text-sm text-gray-600">Complete documentation and join us</p>
            </div>
        </div>
    </div>
</section>

<!-- Admission Form -->
<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12">
                <div class="text-center mb-8">
                    <div class="w-20 h-20 bg-gradient-to-br from-orange-600 to-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-graduation-cap text-white text-3xl"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">Admission Inquiry Form</h2>
                    <p class="text-gray-600">Please fill in the details below</p>
                </div>

                <?php if ($message): ?>
                    <div class="mb-6 p-4 rounded-lg <?php echo $messageType === 'success' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200'; ?>">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="" class="space-y-6">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">

                    <!-- Student Information -->
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-user text-blue-600"></i>
                            </div>
                            Student Information
                        </h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Student's Full Name *</label>
                                <input type="text" name="student_name" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                       placeholder="Enter student's full name">
                            </div>

                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Class Applying For *</label>
                                <select name="class_applying" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
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

                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Previous School (if applicable)</label>
                                <input type="text" name="previous_school"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                       placeholder="Name of previous school">
                            </div>
                        </div>
                    </div>

                    <!-- Parent Information -->
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-users text-green-600"></i>
                            </div>
                            Parent/Guardian Information
                        </h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Parent's Full Name *</label>
                                <input type="text" name="parent_name" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                       placeholder="Enter parent's full name">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">Email Address *</label>
                                    <input type="email" name="email" required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                           placeholder="your@email.com">
                                </div>

                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">Phone Number *</label>
                                    <input type="tel" name="phone" required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                           placeholder="9896421785">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">
                            Additional Information / Message
                        </label>
                        <textarea name="message" rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                  placeholder="Any additional information you'd like to share..."></textarea>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-sm text-blue-800">
                            <i class="fas fa-info-circle mr-2"></i>
                            <strong>Note:</strong> This is an inquiry form. Our admission team will contact you within 2-3 business days to guide you through the complete admission process.
                        </p>
                    </div>

                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-orange-600 to-red-600 text-white py-4 rounded-lg font-bold text-lg hover:from-orange-700 hover:to-red-700 transition shadow-xl">
                        <i class="fas fa-paper-plane mr-2"></i>Submit Inquiry
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-gray-800 text-center mb-12">Why Choose Anthem Public School?</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="bg-white rounded-xl shadow-lg p-6 hover-lift">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-award text-blue-600 text-2xl"></i>
                </div>
                <h4 class="font-bold text-gray-800 mb-2 text-lg">Academic Excellence</h4>
                <p class="text-gray-600 text-sm">Proven track record with 99%+ pass rates and numerous toppers</p>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 hover-lift">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-chalkboard-teacher text-green-600 text-2xl"></i>
                </div>
                <h4 class="font-bold text-gray-800 mb-2 text-lg">Experienced Faculty</h4>
                <p class="text-gray-600 text-sm">Highly qualified and dedicated teachers with years of experience</p>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 hover-lift">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-building text-purple-600 text-2xl"></i>
                </div>
                <h4 class="font-bold text-gray-800 mb-2 text-lg">Modern Infrastructure</h4>
                <p class="text-gray-600 text-sm">State-of-the-art facilities including smart classrooms and labs</p>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 hover-lift">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-futbol text-red-600 text-2xl"></i>
                </div>
                <h4 class="font-bold text-gray-800 mb-2 text-lg">Sports & Activities</h4>
                <p class="text-gray-600 text-sm">Comprehensive sports program and extracurricular activities</p>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 hover-lift">
                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-shield-alt text-yellow-600 text-2xl"></i>
                </div>
                <h4 class="font-bold text-gray-800 mb-2 text-lg">Safe Environment</h4>
                <p class="text-gray-600 text-sm">24/7 security with CCTV surveillance and safe transportation</p>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 hover-lift">
                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-users text-indigo-600 text-2xl"></i>
                </div>
                <h4 class="font-bold text-gray-800 mb-2 text-lg">Holistic Development</h4>
                <p class="text-gray-600 text-sm">Focus on overall personality and character development</p>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

