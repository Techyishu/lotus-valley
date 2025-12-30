<?php
require_once '../includes/auth.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password';
    } else {
        $result = loginAdmin($username, $password);

        if ($result['success']) {
            // Redirect to intended page or dashboard
            $redirect = $_SESSION['redirect_after_login'] ?? 'dashboard.php';
            unset($_SESSION['redirect_after_login']);
            header('Location: ' . $redirect);
            exit;
        } else {
            $error = $result['message'];
        }
    }
}

$timeout = isset($_GET['timeout']) ? true : false;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Lotus Valley School</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .login-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #F5F7FB 0%, #EBF4FF 50%, #F0F7FF 100%);
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .login-bg-shapes {
            position: absolute;
            inset: 0;
            pointer-events: none;
            overflow: hidden;
        }

        .login-shape {
            position: absolute;
            border-radius: 50%;
        }

        .login-shape-1 {
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.08) 0%, transparent 70%);
            top: -150px;
            right: -150px;
        }

        .login-shape-2 {
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(245, 158, 11, 0.08) 0%, transparent 70%);
            bottom: -100px;
            left: -100px;
        }

        .login-shape-3 {
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.06) 0%, transparent 70%);
            top: 40%;
            left: 20%;
        }

        .login-card {
            background: white;
            border-radius: 1.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1),
                0 12px 24px -8px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 1;
        }

        .login-header {
            background: linear-gradient(135deg, #0D9488 0%, #0F766E 100%);
            color: white;
            padding: 2.5rem 2rem;
            text-align: center;
            position: relative;
        }

        .login-header::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M30 30c0-5.52 4.48-10 10-10V10C30 10 22.18 14.48 16 20.66V30h14z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .login-header>* {
            position: relative;
            z-index: 1;
        }

        .login-logo {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
            padding: 0.5rem;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15);
        }

        .login-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .login-header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
            color: white;
        }

        .login-header p {
            opacity: 0.9;
            font-size: 0.9375rem;
        }

        .login-form {
            padding: 2rem;
        }

        .login-input-group {
            margin-bottom: 1.25rem;
        }

        .login-input-group label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            color: var(--text-heading);
            margin-bottom: 0.5rem;
            font-size: 0.9375rem;
        }

        .login-input-group label i {
            color: var(--color-primary);
        }

        .login-input-wrapper {
            position: relative;
        }

        .login-input {
            width: 100%;
            padding: 0.875rem 1rem;
            font-size: 1rem;
            border: 2px solid var(--border-light);
            border-radius: 0.75rem;
            background: var(--bg-light);
            color: var(--text-heading);
            transition: all 0.3s ease;
        }

        .login-input:focus {
            outline: none;
            border-color: var(--color-primary);
            background: white;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .login-input::placeholder {
            color: var(--text-muted);
        }

        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            padding: 0.25rem;
            transition: color 0.2s;
        }

        .password-toggle:hover {
            color: var(--color-primary);
        }

        .login-btn {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
            color: white;
            border: none;
            border-radius: 0.75rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35);
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.45);
        }

        .alert {
            padding: 0.875rem 1rem;
            border-radius: 0.75rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.9375rem;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            color: #DC2626;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .alert-warning {
            background: rgba(245, 158, 11, 0.1);
            color: #D97706;
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-muted);
            margin-bottom: 1.5rem;
            font-size: 0.9375rem;
            transition: color 0.2s;
        }

        .back-link:hover {
            color: var(--color-primary);
        }

        .login-footer {
            text-align: center;
            padding-top: 1rem;
            color: var(--text-muted);
            font-size: 0.875rem;
        }

        .login-footer a {
            color: var(--color-primary);
        }
    </style>
</head>

<body>
    <div class="login-page">
        <!-- Background shapes -->
        <div class="login-bg-shapes">
            <div class="login-shape login-shape-1"></div>
            <div class="login-shape login-shape-2"></div>
            <div class="login-shape login-shape-3"></div>
        </div>

        <div style="width: 100%; max-width: 420px;">
            <!-- Back to Website -->
            <a href="../index.php" class="back-link">
                <i class="fas fa-arrow-left"></i>
                <span>Back to Website</span>
            </a>

            <!-- Login Card -->
            <div class="login-card">
                <!-- Header -->
                <div class="login-header">
                    <div class="login-logo">
                        <img src="../assets/images/logo.png" alt="Lotus Valley School">
                    </div>
                    <h1>Admin Panel</h1>
                    <p>Lotus Valley School</p>
                </div>

                <!-- Form -->
                <div class="login-form">
                    <?php if ($timeout): ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-clock"></i>
                            <span>Your session has expired. Please login again.</span>
                        </div>
                    <?php endif; ?>

                    <?php if ($error): ?>
                        <div class="alert alert-error">
                            <i class="fas fa-exclamation-circle"></i>
                            <span><?php echo htmlspecialchars($error); ?></span>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="login-input-group">
                            <label>
                                <i class="fas fa-user"></i>
                                <span>Username or Email</span>
                            </label>
                            <input type="text" name="username" required autofocus class="login-input"
                                placeholder="Enter your username">
                        </div>

                        <div class="login-input-group">
                            <label>
                                <i class="fas fa-lock"></i>
                                <span>Password</span>
                            </label>
                            <div class="login-input-wrapper">
                                <input type="password" name="password" id="password" required class="login-input"
                                    placeholder="Enter your password">
                                <button type="button" onclick="togglePassword()" class="password-toggle">
                                    <i class="fas fa-eye" id="toggleIcon"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="login-btn">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>Login to Dashboard</span>
                        </button>
                    </form>

                    <div class="login-footer">
                        <p>&copy; <?php echo date('Y'); ?> <a href="../index.php">Lotus Valley School</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>

</html>