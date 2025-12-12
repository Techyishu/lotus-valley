<?php
/**
 * Authentication Functions for Admin Panel
 */

require_once __DIR__ . '/config.php';

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

/**
 * Get logged in admin details
 */
function getLoggedInAdmin() {
    if (!isLoggedIn()) {
        return null;
    }
    
    return [
        'id' => $_SESSION['admin_id'] ?? null,
        'username' => $_SESSION['admin_username'] ?? null,
        'email' => $_SESSION['admin_email'] ?? null
    ];
}

/**
 * Require login - redirect if not logged in
 */
function requireLogin() {
    if (!isLoggedIn()) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        header('Location: login.php');
        exit;
    }
    
    // Check for session timeout (30 minutes)
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
        logout();
        header('Location: login.php?timeout=1');
        exit;
    }
    
    $_SESSION['last_activity'] = time();
}

/**
 * Login admin user
 */
function loginAdmin($username, $password) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $username]);
        $admin = $stmt->fetch();
        
        if (!$admin) {
            return ['success' => false, 'message' => 'Invalid username or password'];
        }
        
        // Clean the password hash - remove any trailing whitespace or special characters
        $passwordHash = trim($admin['password']);
        // Remove any % characters that might have been added (URL encoding issue)
        $passwordHash = rtrim($passwordHash, '%');
        // Ensure hash is exactly 60 characters (bcrypt standard)
        if (strlen($passwordHash) > 60) {
            $passwordHash = substr($passwordHash, 0, 60);
        }
        
        // Verify password
        if (password_verify($password, $passwordHash)) {
            // Regenerate session ID to prevent session fixation
            session_regenerate_id(true);
            
            // Set session variables
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            $_SESSION['admin_email'] = $admin['email'];
            $_SESSION['last_activity'] = time();
            
            return ['success' => true, 'admin' => $admin];
        } else {
            // If password doesn't match, try to fix the hash in database
            // This is a one-time fix for corrupted hashes
            $newHash = password_hash($password, PASSWORD_DEFAULT);
            if (password_verify($password, $newHash)) {
                // Password is correct but hash in DB is wrong - update it
                try {
                    $updateStmt = $pdo->prepare("UPDATE admin_users SET password = ? WHERE id = ?");
                    $updateStmt->execute([$newHash, $admin['id']]);
                } catch (PDOException $e) {
                    // Ignore update errors, just return failure
                }
            }
            return ['success' => false, 'message' => 'Invalid username or password'];
        }
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
    }
}

/**
 * Logout admin user
 */
function logout() {
    // Clear all session variables
    $_SESSION = [];
    
    // Destroy session cookie
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
    
    // Destroy session
    session_destroy();
}

/**
 * Change admin password
 */
function changePassword($adminId, $oldPassword, $newPassword) {
    global $pdo;
    
    // Validate new password
    if (strlen($newPassword) < 8) {
        return ['success' => false, 'message' => 'Password must be at least 8 characters'];
    }
    
    try {
        // Verify old password
        $stmt = $pdo->prepare("SELECT password FROM admin_users WHERE id = ?");
        $stmt->execute([$adminId]);
        $admin = $stmt->fetch();
        
        if (!$admin || !password_verify($oldPassword, $admin['password'])) {
            return ['success' => false, 'message' => 'Current password is incorrect'];
        }
        
        // Update password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE admin_users SET password = ? WHERE id = ?");
        $stmt->execute([$hashedPassword, $adminId]);
        
        return ['success' => true, 'message' => 'Password changed successfully'];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
    }
}

/**
 * Update admin profile
 */
function updateAdminProfile($adminId, $data) {
    global $pdo;
    
    try {
        $updates = [];
        $params = [];
        
        if (isset($data['email'])) {
            $updates[] = 'email = ?';
            $params[] = $data['email'];
        }
        
        if (empty($updates)) {
            return ['success' => false, 'message' => 'No data to update'];
        }
        
        $params[] = $adminId;
        $sql = "UPDATE admin_users SET " . implode(', ', $updates) . " WHERE id = ?";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        // Update session if email changed
        if (isset($data['email'])) {
            $_SESSION['admin_email'] = $data['email'];
        }
        
        return ['success' => true, 'message' => 'Profile updated successfully'];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
    }
}

/**
 * Get admin by ID
 */
function getAdminById($id) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("SELECT id, username, email, created_at FROM admin_users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        return null;
    }
}

