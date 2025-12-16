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
$dbName = getenv('DB_NAME');
$dbUser = getenv('DB_USER');
$dbPass = getenv('DB_PASS');
$dbPort = getenv('DB_PORT') ?: '3306';
$dbType = getenv('DB_TYPE') ?: 'mysql'; // 'mysql' or 'pgsql'

// Validate that required environment variables are set
if (empty($dbName) || empty($dbUser) || empty($dbPass)) {
    die('Database configuration error: Required environment variables (DB_NAME, DB_USER, DB_PASS) are not set. Please configure them in .htaccess or your hosting control panel.');
}

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
    die('Database connection failed: ' . $e->getMessage() . '<br>Host: ' . $dbHost . '<br>Type: ' . $dbType);
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
