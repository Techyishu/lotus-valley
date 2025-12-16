<?php
/**
 * Fix Admin Password
 * DELETE THIS FILE AFTER USE!
 */
require_once 'includes/config.php';

$email = 'anthemschool55@gmail.com';
$password = 'Anthemschool@2024';

// Generate proper password hash
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

echo "<h2>Updating Admin Password</h2>";
echo "<hr>";

try {
    // Check if user exists
    $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = ? OR email = ?");
    $stmt->execute([$email, $email]);
    $user = $stmt->fetch();
    
    if ($user) {
        echo "<p>✅ User found: " . htmlspecialchars($user['username']) . " (ID: {$user['id']})</p>";
        echo "<p>Current password hash length: " . strlen($user['password']) . "</p>";
        
        // Update password
        $updateStmt = $pdo->prepare("UPDATE admin_users SET username = ?, email = ?, password = ? WHERE id = ?");
        $updateStmt->execute([$email, $email, $passwordHash, $user['id']]);
        
        echo "<p style='color: green;'>✅ Password updated successfully!</p>";
        
        // Verify the update
        $verifyStmt = $pdo->prepare("SELECT password FROM admin_users WHERE id = ?");
        $verifyStmt->execute([$user['id']]);
        $updated = $verifyStmt->fetch();
        
        if ($updated && password_verify($password, $updated['password'])) {
            echo "<p style='color: green;'>✅ Password verification: SUCCESS</p>";
        } else {
            echo "<p style='color: red;'>❌ Password verification: FAILED</p>";
        }
        
    } else {
        // Create new user
        echo "<p>User not found. Creating new admin user...</p>";
        $insertStmt = $pdo->prepare("INSERT INTO admin_users (username, password, email) VALUES (?, ?, ?)");
        $insertStmt->execute([$email, $passwordHash, $email]);
        
        echo "<p style='color: green;'>✅ New admin user created!</p>";
    }
    
    echo "<hr>";
    echo "<h3>Login Credentials:</h3>";
    echo "<p><strong>Username/Email:</strong> $email</p>";
    echo "<p><strong>Password:</strong> $password</p>";
    echo "<p><strong>Password Hash:</strong> <code style='word-break: break-all;'>$passwordHash</code></p>";
    
    echo "<hr>";
    echo "<p><a href='admin/login.php' style='background: blue; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Test Login Now</a></p>";
    echo "<p style='color: red;'><strong>⚠️ DELETE THIS FILE AFTER USE!</strong></p>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>

