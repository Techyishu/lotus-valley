<?php
/**
 * RESET ADMIN PASSWORD
 * This script will reset the password for ALL admin users to: admin123
 * Run this once, then DELETE this file!
 */

require_once 'includes/config.php';

header('Content-Type: text/html; charset=utf-8');

$newPassword = 'admin123';
$newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);

$results = [];
$errors = [];

try {
    // Get all admin users
    $stmt = $pdo->query("SELECT id, username, email FROM admin_users");
    $users = $stmt->fetchAll();
    
    if (empty($users)) {
        $errors[] = "No admin users found in database!";
    } else {
        // Update password for each user
        foreach ($users as $user) {
            try {
                $updateStmt = $pdo->prepare("UPDATE admin_users SET password = ? WHERE id = ?");
                $updateStmt->execute([$newPasswordHash, $user['id']]);
                
                $results[] = sprintf(
                    "✅ Updated user: %s (ID: %d, Email: %s)",
                    htmlspecialchars($user['username']),
                    $user['id'],
                    htmlspecialchars($user['email'])
                );
            } catch (PDOException $e) {
                $errors[] = sprintf(
                    "❌ Failed to update user %s: %s",
                    htmlspecialchars($user['username']),
                    $e->getMessage()
                );
            }
        }
        
        // Also ensure 'admin' user exists
        $checkStmt = $pdo->prepare("SELECT id FROM admin_users WHERE username = 'admin'");
        $checkStmt->execute();
        $adminExists = $checkStmt->fetch();
        
        if (!$adminExists) {
            try {
                $insertStmt = $pdo->prepare("INSERT INTO admin_users (username, password, email) VALUES (?, ?, ?)");
                $insertStmt->execute(['admin', $newPasswordHash, 'admin@anthemschool.com']);
                $results[] = "✅ Created new 'admin' user";
            } catch (PDOException $e) {
                $errors[] = "❌ Failed to create admin user: " . $e->getMessage();
            }
        }
    }
    
    // Verify the update worked
    $verifyStmt = $pdo->query("SELECT id, username, email, LENGTH(password) as pwd_len, SUBSTRING(password, 1, 20) as pwd_start FROM admin_users");
    $allUsers = $verifyStmt->fetchAll();
    
} catch (PDOException $e) {
    $errors[] = "Database error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Admin Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-lg p-8 max-w-2xl w-full">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">Reset Admin Password</h1>
        
        <?php if (!empty($results)): ?>
            <div class="mb-6 p-4 bg-green-100 text-green-800 rounded-lg">
                <h2 class="font-bold mb-2">✅ Success:</h2>
                <ul class="list-disc list-inside space-y-1">
                    <?php foreach ($results as $result): ?>
                        <li><?php echo $result; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($errors)): ?>
            <div class="mb-6 p-4 bg-red-100 text-red-800 rounded-lg">
                <h2 class="font-bold mb-2">❌ Errors:</h2>
                <ul class="list-disc list-inside space-y-1">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <?php if (isset($allUsers)): ?>
            <div class="mb-6">
                <h2 class="text-lg font-semibold mb-3">All Admin Users (After Reset):</h2>
                <div class="space-y-2">
                    <?php foreach ($allUsers as $user): ?>
                        <div class="p-3 bg-gray-50 rounded-lg border">
                            <p><strong>ID:</strong> <?php echo htmlspecialchars($user['id']); ?></p>
                            <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                            <p><strong>Password Hash Length:</strong> <?php echo htmlspecialchars($user['pwd_len']); ?> 
                                <?php if ($user['pwd_len'] == 60): ?>
                                    <span class="text-green-600">✅ Correct</span>
                                <?php else: ?>
                                    <span class="text-red-600">❌ Should be 60</span>
                                <?php endif; ?>
                            </p>
                            <p><strong>Hash Start:</strong> <code class="text-xs"><?php echo htmlspecialchars($user['pwd_start']); ?>...</code></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="bg-blue-100 border border-blue-300 text-blue-800 p-4 rounded-lg mb-6">
            <h3 class="font-bold mb-2">🔑 New Login Credentials:</h3>
            <p class="mb-1"><strong>Username:</strong> <code>admin</code> OR <code>schooladmin</code></p>
            <p><strong>Password:</strong> <code><?php echo htmlspecialchars($newPassword); ?></code></p>
        </div>
        
        <div class="bg-yellow-100 border border-yellow-300 text-yellow-800 p-4 rounded-lg mb-6">
            <p class="font-semibold mb-2">⚠️ Security Warning:</p>
            <p class="text-sm">After verifying login works, DELETE this file (<code>reset-password.php</code>) immediately!</p>
        </div>
        
        <div class="flex space-x-4">
            <a href="admin/login.php" class="flex-1 text-center bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                Test Login Now
            </a>
            <a href="debug-login.php" class="flex-1 text-center bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition">
                Debug Login
            </a>
        </div>
        
        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
            <h3 class="font-semibold mb-2">What this script did:</h3>
            <ol class="list-decimal list-inside space-y-1 text-sm">
                <li>Generated a new password hash for password: <strong><?php echo htmlspecialchars($newPassword); ?></strong></li>
                <li>Updated password for all existing admin users</li>
                <li>Created 'admin' user if it didn't exist</li>
                <li>Verified all password hashes are 60 characters (correct length)</li>
            </ol>
        </div>
    </div>
</body>
</html>

