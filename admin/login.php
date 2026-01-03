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
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-900: #064e3b;
            --primary-800: #065f46;
            --primary-600: #059669;
            --accent-500: #d97706;
            --bg-body: #f0fdf4;
            /* Very light green tinge */
            --text-main: #1e293b;
            --text-muted: #64748b;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 0;
            padding: 0;
            color: var(--text-main);
        }

        .login-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #064e3b 0%, #065f46 100%);
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        /* Decorative Background Elements */
        .login-bg-shapes {
            position: absolute;
            inset: 0;
            pointer-events: none;
            overflow: hidden;
        }

        .login-shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.4;
        }

        .login-shape-1 {
            width: 500px;
            height: 500px;
            background: #d97706;
            /* Gold glow */
            top: -100px;
            right: -100px;
        }

        .login-shape-2 {
            width: 400px;
            height: 400px;
            background: #10b981;
            /* Emerald light */
            bottom: -100px;
            left: -100px;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            width: 100%;
            max-width: 440px;
            position: relative;
            z-index: 10;
        }

        .login-header {
            background: white;
            padding: 3rem 2rem 1rem;
            text-align: center;
        }

        .login-logo {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            padding: 0.75rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            border: 1px solid #f1f5f9;
        }

        .login-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .login-header h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.75rem;
            font-weight: 700;
            margin: 0 0 0.5rem;
            color: var(--primary-900);
            letter-spacing: -0.02em;
        }

        .login-header p {
            color: var(--text-muted);
            font-size: 0.95rem;
            margin: 0;
        }

        .login-form {
            padding: 2rem;
        }

        .login-input-group {
            margin-bottom: 1.5rem;
        }

        .login-input-group label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 0.5rem;
        }

        .login-input-wrapper {
            position: relative;
        }

        .login-input-wrapper i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 1rem;
            transition: color 0.2s;
        }

        .login-input {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 2.75rem;
            /* Space for icon */
            font-size: 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            background: #f8fafc;
            color: var(--text-main);
            transition: all 0.2s ease;
            font-family: inherit;
        }

        .login-input:focus {
            outline: none;
            border-color: var(--primary-600);
            background: white;
            box-shadow: 0 0 0 4px rgba(5, 150, 105, 0.1);
        }

        .login-input:focus+i {
            color: var(--primary-600);
        }

        .password-toggle {
            position: absolute;
            right: 0;
            top: 0;
            height: 100%;
            padding: 0 1rem;
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s;
        }

        .password-toggle:hover {
            color: var(--text-main);
        }

        .login-btn {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, var(--primary-800) 0%, var(--primary-600) 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            transition: all 0.2s ease;
            box-shadow: 0 4px 6px -1px rgba(6, 78, 59, 0.3);
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(6, 78, 59, 0.4);
        }

        .alert {
            padding: 0.875rem 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        .alert-warning {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fcd34d;
        }

        .back-link {
            position: absolute;
            top: 2rem;
            left: 2rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.2s;
            z-index: 20;
            background: rgba(255, 255, 255, 0.1);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            backdrop-filter: blur(4px);
        }

        .back-link:hover {
            color: white;
            background: rgba(255, 255, 255, 0.2);
            transform: translateX(-2px);
        }

        .login-footer {
            text-align: center;
            padding-top: 1rem;
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        .login-footer a {
            color: var(--primary-800);
            font-weight: 600;
            text-decoration: none;
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