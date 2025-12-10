<?php
// Fix Admin Password Script - DELETE AFTER USE!
// This will reset the admin password

require_once 'includes/config.php';

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Fix Admin Password</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; max-width: 600px; margin: 0 auto; }
        .success { color: #155724; background: #d4edda; padding: 12px; border-radius: 5px; margin: 10px 0; border-left: 4px solid #28a745; }
        .error { color: #721c24; background: #f8d7da; padding: 12px; border-radius: 5px; margin: 10px 0; border-left: 4px solid #dc3545; }
        .info { color: #004085; background: #d1ecf1; padding: 12px; border-radius: 5px; margin: 10px 0; border-left: 4px solid #17a2b8; }
        .warning { color: #856404; background: #fff3cd; padding: 12px; border-radius: 5px; margin: 10px 0; border-left: 4px solid #ffc107; }
        input[type="text"], input[type="password"] { width: 100%; padding: 10px; margin: 5px 0; border: 1px solid #ddd; border-radius: 4px; }
        button { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>
    <h1>🔐 Fix Admin Password</h1>
    
<?php
global $pdo;

// Check if admin user exists
try {
    $stmt = $pdo->query("SELECT id, username, email FROM admin_users LIMIT 1");
    $admin = $stmt->fetch();
    
    if (!$admin) {
        echo "<div class='error'>❌ No admin user found. Creating one...</div>";
        
        // Create admin user
        $password = 'admin123';
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $pdo->prepare("INSERT INTO admin_users (username, password, email) VALUES (?, ?, ?)");
        $stmt->execute(['admin', $hashedPassword, 'admin@anthemschool.com']);
        
        echo "<div class='success'>✅ Admin user created!</div>";
        echo "<div class='info'>Username: <strong>admin</strong><br>Password: <strong>admin123</strong></div>";
    } else {
        echo "<div class='info'>Found admin user: <strong>" . htmlspecialchars($admin['username']) . "</strong></div>";
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset'])) {
            $newPassword = $_POST['password'] ?? 'admin123';
            
            if (strlen($newPassword) < 8) {
                echo "<div class='error'>❌ Password must be at least 8 characters</div>";
            } else {
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                
                $stmt = $pdo->prepare("UPDATE admin_users SET password = ? WHERE id = ?");
                $stmt->execute([$hashedPassword, $admin['id']]);
                
                echo "<div class='success'><h2>✅ Password Updated Successfully!</h2></div>";
                echo "<div class='info'>";
                echo "<strong>Login Credentials:</strong><br>";
                echo "Username: <strong>admin</strong><br>";
                echo "Password: <strong>" . htmlspecialchars($newPassword) . "</strong>";
                echo "</div>";
                echo "<div class='warning'><strong>⚠️ DELETE THIS FILE NOW!</strong></div>";
                echo "<p><a href='admin/login.php' style='display:inline-block;background:#28a745;color:white;padding:10px 20px;border-radius:5px;margin-top:10px;text-decoration:none;'>Go to Admin Login →</a></p>";
            }
        } else {
            // Show form to reset password
            ?>
            <form method="POST">
                <div class="info">
                    <strong>Current Admin User:</strong><br>
                    ID: <?php echo $admin['id']; ?><br>
                    Username: <?php echo htmlspecialchars($admin['username']); ?><br>
                    Email: <?php echo htmlspecialchars($admin['email']); ?>
                </div>
                
                <div style="margin: 20px 0;">
                    <label><strong>New Password:</strong></label>
                    <input type="password" name="password" value="admin123" required minlength="8" placeholder="Enter new password">
                    <small style="color: #666;">Minimum 8 characters</small>
                </div>
                
                <input type="hidden" name="reset" value="1">
                <button type="submit">Reset Admin Password</button>
            </form>
            <?php
        }
    }
    
    // Also check and fix password hash format
    if (isset($_GET['check'])) {
        echo "<hr>";
        echo "<h2>Password Hash Check</h2>";
        
        $stmt = $pdo->query("SELECT id, username, password FROM admin_users LIMIT 1");
        $admin = $stmt->fetch();
        
        if ($admin) {
            echo "<div class='info'>Current password hash: " . htmlspecialchars(substr($admin['password'], 0, 30)) . "...</div>";
            
            // Test if password works
            $testPassword = 'admin123';
            if (password_verify($testPassword, $admin['password'])) {
                echo "<div class='success'>✅ Password hash is valid! 'admin123' should work.</div>";
            } else {
                echo "<div class='error'>❌ Password hash is invalid. Need to reset.</div>";
            }
        }
    }
    
} catch (PDOException $e) {
    echo "<div class='error'><h2>❌ Database Error</h2>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p></div>";
}
?>

<hr>
<div class="info">
    <strong>Quick Check:</strong> 
    <a href="?check=1">Verify Password Hash</a>
</div>

<div class="warning" style="margin-top: 20px;">
    <strong>⚠️ SECURITY WARNING:</strong><br>
    Delete this file immediately after fixing the password!
</div>

</body>
</html>

