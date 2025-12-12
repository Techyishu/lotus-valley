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

<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Website Settings</h2>
</div>

<?php if ($message): ?>
    <div class="mb-6 p-4 rounded-lg <?php echo $messageType === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
        <?php echo $message; ?>
    </div>
<?php endif; ?>

<div class="bg-white rounded-xl shadow-lg p-8">
    <form method="POST" class="space-y-8">
        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">

        <!-- Basic Information -->
        <div>
            <h3 class="text-xl font-bold text-gray-800 mb-4 pb-2 border-b">Basic Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">School Name</label>
                    <input type="text" name="school_name" value="<?php echo clean($settings['school_name'] ?? ''); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Email Address</label>
                    <input type="email" name="school_email" value="<?php echo clean($settings['school_email'] ?? ''); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Phone Number</label>
                    <input type="text" name="school_phone" value="<?php echo clean($settings['school_phone'] ?? ''); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Address</label>
                    <input type="text" name="school_address" value="<?php echo clean($settings['school_address'] ?? ''); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>

        <!-- Social Media -->
        <div>
            <h3 class="text-xl font-bold text-gray-800 mb-4 pb-2 border-b">Social Media Links</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Facebook URL</label>
                    <input type="url" name="facebook_url" value="<?php echo clean($settings['facebook_url'] ?? ''); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Twitter URL</label>
                    <input type="url" name="twitter_url" value="<?php echo clean($settings['twitter_url'] ?? ''); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Instagram URL</label>
                    <input type="url" name="instagram_url" value="<?php echo clean($settings['instagram_url'] ?? ''); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-gray-700 font-medium mb-2">YouTube URL</label>
                    <input type="url" name="youtube_url" value="<?php echo clean($settings['youtube_url'] ?? ''); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>

        <!-- Content -->
        <div>
            <h3 class="text-xl font-bold text-gray-800 mb-4 pb-2 border-b">Website Content</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Principal's Message</label>
                    <textarea name="principal_message" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"><?php echo clean($settings['principal_message'] ?? ''); ?></textarea>
                </div>
                
                <div>
                    <label class="block text-gray-700 font-medium mb-2">About Text</label>
                    <textarea name="about_text" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"><?php echo clean($settings['about_text'] ?? ''); ?></textarea>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div>
            <h3 class="text-xl font-bold text-gray-800 mb-4 pb-2 border-b">Statistics</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Students Count</label>
                    <input type="number" name="students_count" value="<?php echo clean($settings['students_count'] ?? ''); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Faculty Count</label>
                    <input type="number" name="faculty_count" value="<?php echo clean($settings['faculty_count'] ?? ''); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Years Established</label>
                    <input type="number" name="years_established" value="<?php echo clean($settings['years_established'] ?? ''); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Awards Count</label>
                    <input type="number" name="awards_count" value="<?php echo clean($settings['awards_count'] ?? ''); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>

        <div class="flex space-x-4 pt-4">
            <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-save mr-2"></i>Save Settings
            </button>
        </div>
    </form>
</div>

<?php require_once 'includes/admin_footer.php'; ?>

