<?php
/**
 * Database Configuration
 * Supports both MySQL and PostgreSQL for cloud deployment (Render.com, etc.)
 * 
 * SECURITY: Database credentials are set via environment variables in .htaccess
 * This file reads from environment variables only - no hardcoded credentials.
 */

// Read from environment variables (set in .htaccess or hosting control panel)
$dbHost = getenv('DB_HOST') ?: 'localhost';
$dbName = getenv('DB_NAME') ?: 'lotus_valley';
$dbUser = getenv('DB_USER') ?: 'root';
$dbPass = getenv('DB_PASS') ?: '';
$dbPort = getenv('DB_PORT') ?: '3306';
$dbType = getenv('DB_TYPE') ?: 'mysql'; // 'mysql' or 'pgsql'

// For local development with PHP built-in server, you can set these defaults:
// Or create a .env file and modify this section to read from it
// For production, always use environment variables in .htaccess

// Define constants for backward compatibility
define('DB_HOST', $dbHost);
define('DB_NAME', $dbName);
define('DB_USER', $dbUser);
define('DB_PASS', $dbPass);
define('DB_TYPE', $dbType);

// Create PDO connection
try {
    if ($dbType === 'pgsql') {
        // PostgreSQL connection
        $dsn = "pgsql:host=$dbHost;port=$dbPort;dbname=$dbName";
    } else {
        // MySQL connection
        $dsn = "mysql:host=$dbHost;port=$dbPort;dbname=$dbName;charset=utf8mb4";
    }

    $pdo = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
} catch (PDOException $e) {
    // Show helpful error message for local development
    $errorMsg = '<div style="font-family: Arial; padding: 20px; background: #fee; border: 2px solid #c00; margin: 20px; border-radius: 8px;">';
    $errorMsg .= '<h2 style="color: #c00;">Database Connection Failed</h2>';
    $errorMsg .= '<p><strong>Error:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
    $errorMsg .= '<p><strong>Host:</strong> ' . htmlspecialchars($dbHost) . '</p>';
    $errorMsg .= '<p><strong>Database:</strong> ' . htmlspecialchars($dbName) . '</p>';
    $errorMsg .= '<p><strong>Type:</strong> ' . htmlspecialchars($dbType) . '</p>';
    $errorMsg .= '<hr><h3>Quick Fix for Local Development:</h3>';
    $errorMsg .= '<ol>';
    $errorMsg .= '<li>Create database: <code>CREATE DATABASE lotus_valley;</code></li>';
    $errorMsg .= '<li>Import schema: <code>mysql lotus_valley < database.sql</code></li>';
    $errorMsg .= '<li>Or edit <code>includes/config.php</code> with your database credentials</li>';
    $errorMsg .= '</ol></div>';
    die($errorMsg);
}

// Session configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
// Only use secure cookies on HTTPS (production), not on localhost
ini_set('session.cookie_secure', (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 1 : 0);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Site URL - detect HTTPS
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
define('SITE_URL', $protocol . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']));
