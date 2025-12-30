<?php
/**
 * Create Admin User Script
 * 
 * Run this script to create a new admin user.
 * SECURITY WARNING: Delete this file after you have created your admin user!
 */

// Include database connection
require_once 'includes/config.php';

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Basic validation
    if (empty($username) || empty($email) || empty($password)) {
        $message = 'All fields are required';
        $messageType = 'error';
    } elseif (strlen($password) < 8) {
        $message = 'Password must be at least 8 characters long';
        $messageType = 'error';
    } else {
        try {
            // Check if username or email already exists
            $stmt = $pdo->prepare("SELECT id FROM admin_users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);

            if ($stmt->fetch()) {
                $message = 'Username or Email already exists';
                $messageType = 'error';
            } else {
                // Hash password
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Insert new admin
                $stmt = $pdo->prepare("INSERT INTO admin_users (username, email, password) VALUES (?, ?, ?)");
                $stmt->execute([$username, $email, $hashedPassword]);

                $message = 'Admin user created successfully! You can now <a href="admin/login.php" class="underline">login here</a>.';
                $messageType = 'success';
            }
        } catch (PDOException $e) {
            $message = 'Database error: ' . $e->getMessage();
            $messageType = 'error';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Admin User</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-blue-600 p-6 text-center">
            <h1 class="text-2xl font-bold text-white mb-2">Create Admin User</h1>
            <p class="text-blue-100">Lotus Valley International School</p>
        </div>

        <div class="p-8">
            <?php if ($message): ?>
                <div
                    class="mb-6 p-4 rounded-lg <?php echo $messageType === 'success' ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-red-100 text-red-700 border border-red-200'; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                        <input type="text" name="username" required
                            class="pl-10 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm border p-2.5"
                            placeholder="johndoe">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input type="email" name="email" required
                            class="pl-10 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm border p-2.5"
                            placeholder="admin@school.com">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input type="password" name="password" required minlength="8"
                            class="pl-10 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm border p-2.5"
                            placeholder="********">
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Must be at least 8 characters.</p>
                </div>

                <button type="submit"
                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    Create Admin Account
                </button>
            </form>

            <div class="mt-6 text-center border-t pt-6">
                <a href="index.php" class="text-sm text-gray-600 hover:text-blue-600">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Website
                </a>
            </div>

            <div class="mt-4 p-3 bg-yellow-50 rounded-lg border border-yellow-100 text-xs text-yellow-800">
                <strong>Start-up Note:</strong> A default admin account is usually created during setup:<br>
                User: <code>admin</code><br> Pass: <code>admin123</code>
            </div>
        </div>
    </div>
</body>

</html>