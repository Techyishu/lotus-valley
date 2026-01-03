<?php
$pageTitle = 'Settings';
require_once 'includes/admin_header.php';

global $pdo;

$message = '';
$messageType = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (verifyCSRFToken($_POST['csrf_token'])) {
        $updates = [
            'school_name' => trim($_POST['school_name']),
            'school_email' => trim($_POST['school_email']),
            'school_phone' => trim($_POST['school_phone']),
            'school_address' => trim($_POST['school_address']),
            'facebook_url' => trim($_POST['facebook_url']),
            'twitter_url' => trim($_POST['twitter_url']),
            'instagram_url' => trim($_POST['instagram_url']),
            'youtube_url' => trim($_POST['youtube_url']),
            'principal_message' => trim($_POST['principal_message']),
            'about_text' => trim($_POST['about_text']),
            'students_count' => trim($_POST['students_count']),
            'faculty_count' => trim($_POST['faculty_count']),
            'years_established' => trim($_POST['years_established']),
            'awards_count' => trim($_POST['awards_count'])
        ];

        try {
            // PostgreSQL doesn't support ON UPDATE CURRENT_TIMESTAMP, so update manually
            $dbType = defined('DB_TYPE') ? DB_TYPE : 'mysql';

            foreach ($updates as $key => $value) {
                if ($dbType === 'pgsql') {
                    // PostgreSQL: manually update timestamp
                    $stmt = $pdo->prepare("UPDATE site_settings SET setting_value = ?, updated_at = CURRENT_TIMESTAMP WHERE setting_key = ?");
                    $stmt->execute([$value, $key]);
                } else {
                    // MySQL: ON UPDATE CURRENT_TIMESTAMP handles it automatically
                    $stmt = $pdo->prepare("UPDATE site_settings SET setting_value = ? WHERE setting_key = ?");
                    $stmt->execute([$value, $key]);
                }
            }

            $message = 'Settings updated successfully';
            $messageType = 'success';
        } catch (PDOException $e) {
            $message = 'Error updating settings: ' . $e->getMessage();
            $messageType = 'error';
        }
    }
}

// Get all settings
$settings = getAllSettings();
?>

<div class="card mb-6">
    <div class="card-header">
        <h3><i class="fas fa-cog"></i> Website Settings</h3>
    </div>
</div>


<?php if ($message): ?>
    <div
        class="mb-6 p-4 rounded-lg border flex items-center gap-3 <?php echo $messageType === 'success' ? 'bg-green-50 text-green-800 border-green-200' : 'bg-red-50 text-red-800 border-red-200'; ?>">
        <i
            class="<?php echo $messageType === 'success' ? 'fas fa-check-circle text-green-500' : 'fas fa-exclamation-circle text-red-500'; ?>"></i>
        <?php echo $message; ?>
    </div>
<?php endif; ?>

<form method="POST" class="space-y-6">
    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">

    <!-- Basic Information -->
    <div class="card">
        <div class="card-header border-b border-gray-100">
            <h4 class="text-lg font-bold text-gray-800"><i class="fas fa-info-circle mr-2 text-primary-500"></i> Basic
                Information</h4>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group">
                    <label class="block text-gray-700 font-semibold mb-2">School Name</label>
                    <input type="text" name="school_name" value="<?php echo clean($settings['school_name'] ?? ''); ?>"
                        class="form-control" placeholder="e.g. Lotus Valley School">
                </div>

                <div class="form-group">
                    <label class="block text-gray-700 font-semibold mb-2">Email Address</label>
                    <input type="email" name="school_email"
                        value="<?php echo clean($settings['school_email'] ?? ''); ?>" class="form-control"
                        placeholder="admin@example.com">
                </div>

                <div class="form-group">
                    <label class="block text-gray-700 font-semibold mb-2">Phone Number</label>
                    <input type="text" name="school_phone" value="<?php echo clean($settings['school_phone'] ?? ''); ?>"
                        class="form-control" placeholder="+91 1234567890">
                </div>

                <div class="form-group">
                    <label class="block text-gray-700 font-semibold mb-2">Address</label>
                    <input type="text" name="school_address"
                        value="<?php echo clean($settings['school_address'] ?? ''); ?>" class="form-control"
                        placeholder="Street, City, State">
                </div>
            </div>
        </div>
    </div>

    <!-- Social Media -->
    <div class="card">
        <div class="card-header border-b border-gray-100">
            <h4 class="text-lg font-bold text-gray-800"><i class="fas fa-share-alt mr-2 text-primary-500"></i> Social
                Media Links</h4>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group">
                    <label class="block text-gray-700 font-semibold mb-2"><i
                            class="fab fa-facebook text-blue-600 mr-1"></i> Facebook URL</label>
                    <input type="url" name="facebook_url" value="<?php echo clean($settings['facebook_url'] ?? ''); ?>"
                        class="form-control" placeholder="https://facebook.com/...">
                </div>

                <div class="form-group">
                    <label class="block text-gray-700 font-semibold mb-2"><i
                            class="fab fa-twitter text-blue-400 mr-1"></i> Twitter URL</label>
                    <input type="url" name="twitter_url" value="<?php echo clean($settings['twitter_url'] ?? ''); ?>"
                        class="form-control" placeholder="https://twitter.com/...">
                </div>

                <div class="form-group">
                    <label class="block text-gray-700 font-semibold mb-2"><i
                            class="fab fa-instagram text-pink-600 mr-1"></i> Instagram URL</label>
                    <input type="url" name="instagram_url"
                        value="<?php echo clean($settings['instagram_url'] ?? ''); ?>" class="form-control"
                        placeholder="https://instagram.com/...">
                </div>

                <div class="form-group">
                    <label class="block text-gray-700 font-semibold mb-2"><i
                            class="fab fa-youtube text-red-600 mr-1"></i> YouTube URL</label>
                    <input type="url" name="youtube_url" value="<?php echo clean($settings['youtube_url'] ?? ''); ?>"
                        class="form-control" placeholder="https://youtube.com/...">
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="card">
        <div class="card-header border-b border-gray-100">
            <h4 class="text-lg font-bold text-gray-800"><i class="fas fa-pen-nib mr-2 text-primary-500"></i> Website
                Content</h4>
        </div>
        <div class="card-body">
            <div class="space-y-6">
                <div class="form-group">
                    <label class="block text-gray-700 font-semibold mb-2">Principal's Message</label>
                    <textarea name="principal_message" rows="4" class="form-control"
                        placeholder="Message from the principal..."><?php echo clean($settings['principal_message'] ?? ''); ?></textarea>
                </div>

                <div class="form-group">
                    <label class="block text-gray-700 font-semibold mb-2">About Text</label>
                    <textarea name="about_text" rows="5" class="form-control"
                        placeholder="About the school..."><?php echo clean($settings['about_text'] ?? ''); ?></textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="card">
        <div class="card-header border-b border-gray-100">
            <h4 class="text-lg font-bold text-gray-800"><i class="fas fa-chart-pie mr-2 text-primary-500"></i>
                Statistics</h4>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="form-group">
                    <label class="block text-gray-700 font-semibold mb-2">Students Count</label>
                    <input type="number" name="students_count"
                        value="<?php echo clean($settings['students_count'] ?? ''); ?>" class="form-control">
                </div>

                <div class="form-group">
                    <label class="block text-gray-700 font-semibold mb-2">Faculty Count</label>
                    <input type="number" name="faculty_count"
                        value="<?php echo clean($settings['faculty_count'] ?? ''); ?>" class="form-control">
                </div>

                <div class="form-group">
                    <label class="block text-gray-700 font-semibold mb-2">Years Established</label>
                    <input type="number" name="years_established"
                        value="<?php echo clean($settings['years_established'] ?? ''); ?>" class="form-control">
                </div>

                <div class="form-group">
                    <label class="block text-gray-700 font-semibold mb-2">Awards Count</label>
                    <input type="number" name="awards_count"
                        value="<?php echo clean($settings['awards_count'] ?? ''); ?>" class="form-control">
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-end pt-4 pb-8">
        <button type="submit"
            class="btn btn-primary px-8 py-3 text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition">
            <i class="fas fa-save mr-2"></i> Save All Settings
        </button>
    </div>
</form>

<?php require_once 'includes/admin_footer.php'; ?>