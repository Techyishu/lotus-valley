<?php
/**
 * SET CUSTOM ADMIN PASSWORD
 * Use this to set your own password for admin users
 * Run this once, then DELETE this file!
 */

require_once 'includes/config.php';

header('Content-Type: text/html; charset=utf-8');

$message = '';
$messageType = '';
$success = false;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['set_password'])) {
    $username = trim($_POST['username'] ?? '');
    $newPassword = trim($_POST['password'] ?? '');
    $confirmPassword = trim($_POST['confirm_password'] ?? '');
    
    // Validation
    if (empty($username)) {
        $message = 'Please enter a username';
        $messageType = 'error';
    } elseif (empty($newPassword)) {
        $message = 'Please enter a password';
        $messageType = 'error';
    } elseif (strlen($newPassword) < 8) {
        $message = 'Password must be at least 8 characters long';
        $messageType = 'error';
    } elseif ($newPassword !== $confirmPassword) {
        $message = 'Passwords do not match';
        $messageType = 'error';
    } else {
        try {
            // Check if user exists
            $stmt = $pdo->prepare("SELECT id, username, email FROM admin_users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $username]);
            $user = $stmt->fetch();
            
            if (!$user) {
                // User doesn't exist - create it
                $email = filter_var($username, FILTER_VALIDATE_EMAIL) ? $username : 'admin@anthemschool.com';
                $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
                
                $insertStmt = $pdo->prepare("INSERT INTO admin_users (username, password, email) VALUES (?, ?, ?)");
                $insertStmt->execute([$username, $passwordHash, $email]);
                
                $message = "✅ Created new user '{$username}' with your custom password";
                $messageType = 'success';
                $success = true;
            } else {
                // User exists - update password
                $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
                
                $updateStmt = $pdo->prepare("UPDATE admin_users SET password = ? WHERE id = ?");
                $updateStmt->execute([$passwordHash, $user['id']]);
                
                $message = "✅ Password updated successfully for user '{$user['username']}'";
                $messageType = 'success';
                $success = true;
            }
        } catch (PDOException $e) {
            $message = '❌ Error: ' . $e->getMessage();
            $messageType = 'error';
        }
    }
}

// Get all admin users
try {
    $stmt = $pdo->query("SELECT id, username, email, LENGTH(password) as pwd_len FROM admin_users ORDER BY id");
    $allUsers = $stmt->fetchAll();
} catch (PDOException $e) {
    $allUsers = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Custom Admin Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl p-8 max-w-md w-full">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-key text-blue-600 text-2xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Set Admin Password</h1>
            <p class="text-gray-600 text-sm mt-2">Set your own custom password</p>
        </div>
        
        <?php if ($message): ?>
            <div class="mb-6 p-4 rounded-lg <?php echo $messageType === 'success' ? 'bg-green-100 text-green-800 border border-green-300' : 'bg-red-100 text-red-800 border border-red-300'; ?>">
                <div class="flex items-center">
                    <i class="fas <?php echo $messageType === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?> mr-2"></i>
                    <?php echo htmlspecialchars($message); ?>
                </div>
            </div>
        <?php endif; ?>
        
        <form method="POST" class="space-y-4">
            <input type="hidden" name="set_password" value="1">
            
            <div>
                <label class="block text-gray-700 font-medium mb-2">
                    <i class="fas fa-user mr-2 text-blue-600"></i>Username or Email
                </label>
                <input type="text" 
                       name="username" 
                       required
                       value="<?php echo htmlspecialchars($_POST['username'] ?? 'admin'); ?>"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="Enter username (e.g., admin)">
                <p class="text-xs text-gray-500 mt-1">Enter existing username or create a new one</p>
            </div>
            
            <div>
                <label class="block text-gray-700 font-medium mb-2">
                    <i class="fas fa-lock mr-2 text-blue-600"></i>New Password
                </label>
                <input type="password" 
                       name="password" 
                       id="password"
                       required
                       minlength="8"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="Enter new password (min 8 characters)">
                <p class="text-xs text-gray-500 mt-1">Must be at least 8 characters long</p>
            </div>
            
            <div>
                <label class="block text-gray-700 font-medium mb-2">
                    <i class="fas fa-lock mr-2 text-blue-600"></i>Confirm Password
                </label>
                <input type="password" 
                       name="confirm_password" 
                       id="confirm_password"
                       required
                       minlength="8"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="Confirm new password">
            </div>
            
            <button type="submit" 
                    class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 rounded-lg font-semibold hover:from-blue-700 hover:to-indigo-700 transition shadow-lg">
                <i class="fas fa-save mr-2"></i>Set Password
            </button>
        </form>
        
        <?php if (!empty($allUsers)): ?>
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Existing Admin Users:</h3>
                <div class="space-y-2">
                    <?php foreach ($allUsers as $user): ?>
                        <div class="p-3 bg-gray-50 rounded-lg text-sm">
                            <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                            <p class="text-xs text-gray-500">Hash Length: <?php echo $user['pwd_len']; ?> chars</p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <p class="text-sm text-yellow-800">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <strong>Security Warning:</strong> Delete this file (<code>set-password.php</code>) after setting your password!
            </p>
        </div>
        
        <div class="mt-4 text-center">
            <a href="admin/login.php" class="text-blue-600 hover:text-blue-800 text-sm">
                <i class="fas fa-arrow-left mr-1"></i>Back to Login
            </a>
        </div>
    </div>
    
    <script>
        // Password strength indicator
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirm_password');
        
        confirmPassword.addEventListener('input', function() {
            if (password.value !== confirmPassword.value) {
                confirmPassword.setCustomValidity('Passwords do not match');
            } else {
                confirmPassword.setCustomValidity('');
            }
        });
    </script>
</body>
</html>

