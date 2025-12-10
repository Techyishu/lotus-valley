<?php
/**
 * Database Configuration
 * Supports environment variables for cloud deployment (Render.com, etc.)
 */

// Read from environment variables if available (for Render.com, Docker, etc.)
// Otherwise use default values
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'anthem_school_db');
define('DB_USER', getenv('DB_USER') ?: 'anthem');
define('DB_PASS', getenv('DB_PASS') ?: 'anthem123');

// Create PDO connection
try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

// Session configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Set to 1 if using HTTPS

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Site URL
define('SITE_URL', 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']));
