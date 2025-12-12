<?php
/**
 * Database Configuration
 * Supports both MySQL and PostgreSQL for cloud deployment (Render.com, etc.)
 */

// Read from environment variables if available (for Render.com, Docker, etc.)
// Otherwise use default values
$dbHost = getenv('DB_HOST') ?: 'localhost';
$dbName = getenv('DB_NAME') ?: 'anthem_school_db';
$dbUser = getenv('DB_USER') ?: 'anthem';
$dbPass = getenv('DB_PASS') ?: 'anthem123';
$dbPort = getenv('DB_PORT') ?: '3306';
$dbType = getenv('DB_TYPE') ?: 'mysql'; // 'mysql' or 'pgsql'

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
ini_set('session.cookie_secure', 1); // Use 1 for HTTPS on Render

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Site URL - detect HTTPS
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
define('SITE_URL', $protocol . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']));
