<?php
/**
 * Common Functions for Anthem Public School Website
 */

require_once __DIR__ . '/config.php';

/**
 * Sanitize output to prevent XSS
 */
function clean($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Generate CSRF token
 */
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 */
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Get site settings
 */
function getSiteSetting($key, $default = '') {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT setting_value FROM site_settings WHERE setting_key = ?");
        $stmt->execute([$key]);
        $result = $stmt->fetch();
        return $result ? $result['setting_value'] : $default;
    } catch (PDOException $e) {
        return $default;
    }
}

/**
 * Get all site settings as array
 */
function getAllSettings() {
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT setting_key, setting_value FROM site_settings");
        $settings = [];
        while ($row = $stmt->fetch()) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        return $settings;
    } catch (PDOException $e) {
        return [];
    }
}

/**
 * Get toppers with optional filters
 */
function getToppers($year = null, $limit = null) {
    global $pdo;
    try {
        $sql = "SELECT * FROM toppers";
        $params = [];
        
        if ($year) {
            $sql .= " WHERE year = ?";
            $params[] = $year;
        }
        
        $sql .= " ORDER BY percentage DESC, year DESC";
        
        if ($limit) {
            // PostgreSQL requires explicit cast for LIMIT parameter
            $dbType = defined('DB_TYPE') ? DB_TYPE : 'mysql';
            if ($dbType === 'pgsql') {
                $sql .= " LIMIT ?::integer";
            } else {
                $sql .= " LIMIT ?";
            }
            $params[] = $limit;
        }
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

/**
 * Get staff members by department
 */
function getStaff($department = null, $limit = null) {
    global $pdo;
    try {
        $sql = "SELECT * FROM staff";
        $params = [];
        
        if ($department) {
            $sql .= " WHERE department = ?";
            $params[] = $department;
        }
        
        $sql .= " ORDER BY display_order ASC, name ASC";
        
        if ($limit) {
            // PostgreSQL requires explicit cast for LIMIT parameter
            $dbType = defined('DB_TYPE') ? DB_TYPE : 'mysql';
            if ($dbType === 'pgsql') {
                $sql .= " LIMIT ?::integer";
            } else {
                $sql .= " LIMIT ?";
            }
            $params[] = $limit;
        }
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

/**
 * Get gallery images by category
 */
function getGalleryImages($category = null, $limit = null) {
    global $pdo;
    try {
        $sql = "SELECT * FROM gallery";
        $params = [];
        
        if ($category) {
            $sql .= " WHERE category = ?";
            $params[] = $category;
        }
        
        $sql .= " ORDER BY uploaded_at DESC";
        
        if ($limit) {
            // PostgreSQL requires explicit cast for LIMIT parameter
            $dbType = defined('DB_TYPE') ? DB_TYPE : 'mysql';
            if ($dbType === 'pgsql') {
                $sql .= " LIMIT ?::integer";
            } else {
                $sql .= " LIMIT ?";
            }
            $params[] = $limit;
        }
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

/**
 * Get gallery categories
 */
function getGalleryCategories() {
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT DISTINCT category FROM gallery ORDER BY category");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    } catch (PDOException $e) {
        return [];
    }
}

/**
 * Get announcements
 */
function getAnnouncements($limit = null, $status = 'published') {
    global $pdo;
    try {
        $sql = "SELECT * FROM announcements WHERE status = ? ORDER BY date DESC, priority DESC";
        $params = [$status];
        
        if ($limit) {
            // PostgreSQL requires explicit cast for LIMIT parameter
            $dbType = defined('DB_TYPE') ? DB_TYPE : 'mysql';
            if ($dbType === 'pgsql') {
                $sql .= " LIMIT ?::integer";
            } else {
                $sql .= " LIMIT ?";
            }
            $params[] = $limit;
        }
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

/**
 * Get upcoming events
 */
function getEvents($limit = null, $status = 'upcoming') {
    global $pdo;
    try {
        // PostgreSQL uses CURRENT_DATE, MySQL uses CURDATE()
        $dbType = defined('DB_TYPE') ? DB_TYPE : 'mysql';
        $dateFunction = ($dbType === 'pgsql') ? 'CURRENT_DATE' : 'CURDATE()';
        
        $sql = "SELECT * FROM events WHERE status = ? AND event_date >= $dateFunction ORDER BY event_date ASC";
        $params = [$status];
        
        if ($limit) {
            // PostgreSQL requires explicit cast for LIMIT parameter
            if ($dbType === 'pgsql') {
                $sql .= " LIMIT ?::integer";
            } else {
                $sql .= " LIMIT ?";
            }
            $params[] = $limit;
        }
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

/**
 * Get testimonials
 */
function getTestimonials($featured = false, $limit = null) {
    global $pdo;
    try {
        // Handle boolean for PostgreSQL vs MySQL
        $dbType = defined('DB_TYPE') ? DB_TYPE : 'mysql';
        
        $sql = "SELECT * FROM testimonials WHERE status = 'approved'";
        $params = [];
        
        if ($featured) {
            // PostgreSQL needs boolean cast, MySQL uses integer
            if ($dbType === 'pgsql') {
                $sql .= " AND is_featured = true";
            } else {
                $sql .= " AND is_featured = 1";
            }
        }
        
        $sql .= " ORDER BY created_at DESC";
        
        if ($limit) {
            // PostgreSQL requires explicit cast for LIMIT parameter
            if ($dbType === 'pgsql') {
                $sql .= " LIMIT ?::integer";
            } else {
                $sql .= " LIMIT ?";
            }
            $params[] = $limit;
        }
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

/**
 * Handle file upload
 */
function uploadFile($file, $uploadDir, $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif']) {
    if (!isset($file['error']) || is_array($file['error'])) {
        return ['success' => false, 'message' => 'Invalid file upload'];
    }
    
    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'Upload error: ' . $file['error']];
    }
    
    // Validate file size (5MB max)
    if ($file['size'] > 5242880) {
        return ['success' => false, 'message' => 'File size exceeds 5MB'];
    }
    
    // Get file extension
    $fileInfo = pathinfo($file['name']);
    $extension = strtolower($fileInfo['extension']);
    
    // Validate extension
    if (!in_array($extension, $allowedExtensions)) {
        return ['success' => false, 'message' => 'Invalid file type. Allowed: ' . implode(', ', $allowedExtensions)];
    }
    
    // Validate MIME type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    $allowedMimes = [
        'image/jpeg' => ['jpg', 'jpeg'],
        'image/png' => ['png'],
        'image/gif' => ['gif']
    ];
    
    $validMime = false;
    foreach ($allowedMimes as $mime => $exts) {
        if ($mimeType === $mime && in_array($extension, $exts)) {
            $validMime = true;
            break;
        }
    }
    
    if (!$validMime) {
        return ['success' => false, 'message' => 'Invalid file MIME type'];
    }
    
    // Create upload directory if not exists
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    // Generate unique filename
    $filename = uniqid() . '_' . time() . '.' . $extension;
    $filepath = $uploadDir . '/' . $filename;
    
    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return ['success' => true, 'filename' => $filename, 'filepath' => $filepath];
    } else {
        return ['success' => false, 'message' => 'Failed to move uploaded file'];
    }
}

/**
 * Delete file
 */
function deleteFile($filepath) {
    if (file_exists($filepath) && is_file($filepath)) {
        return unlink($filepath);
    }
    return false;
}

/**
 * Format date
 */
function formatDate($date, $format = 'd M Y') {
    return date($format, strtotime($date));
}

/**
 * Get time ago
 */
function timeAgo($datetime) {
    $time = strtotime($datetime);
    $time_difference = time() - $time;
    
    if ($time_difference < 1) {
        return 'just now';
    }
    
    $condition = [
        12 * 30 * 24 * 60 * 60 => 'year',
        30 * 24 * 60 * 60 => 'month',
        24 * 60 * 60 => 'day',
        60 * 60 => 'hour',
        60 => 'minute',
        1 => 'second'
    ];
    
    foreach ($condition as $secs => $str) {
        $d = $time_difference / $secs;
        if ($d >= 1) {
            $t = round($d);
            return $t . ' ' . $str . ($t > 1 ? 's' : '') . ' ago';
        }
    }
}

/**
 * Send email (basic implementation)
 */
function sendEmail($to, $subject, $message, $from = null) {
    if (!$from) {
        $from = getSiteSetting('school_email', 'noreply@anthemschool.com');
    }
    
    $headers = "From: $from\r\n";
    $headers .= "Reply-To: $from\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    
    return mail($to, $subject, $message, $headers);
}

/**
 * Search website content
 */
function searchContent($query, $limit = 10) {
    global $pdo;
    
    if (empty($query) || strlen($query) < 2) {
        return [];
    }
    
    $searchTerm = '%' . $query . '%';
    $results = [];
    
    // Detect database type for compatibility
    $dbType = defined('DB_TYPE') ? DB_TYPE : 'mysql';
    
    try {
        // PostgreSQL uses || for concatenation, MySQL uses CONCAT()
        if ($dbType === 'pgsql') {
            // Search toppers - PostgreSQL
            $stmt = $pdo->prepare("SELECT 'topper' as type, id, name as title, (marks || ' - ' || class || ' (' || year || ')') as description FROM toppers WHERE name LIKE ? LIMIT 5");
            $stmt->execute([$searchTerm]);
            $results = array_merge($results, $stmt->fetchAll());
            
            // Search staff - PostgreSQL
            $stmt = $pdo->prepare("SELECT 'staff' as type, id, name as title, (designation || ' - ' || department) as description FROM staff WHERE name LIKE ? OR designation LIKE ? LIMIT 5");
            $stmt->execute([$searchTerm, $searchTerm]);
            $results = array_merge($results, $stmt->fetchAll());
            
            // Search announcements - PostgreSQL uses SUBSTRING() instead of LEFT()
            $stmt = $pdo->prepare("SELECT 'announcement' as type, id, title, SUBSTRING(content, 1, 100) as description FROM announcements WHERE status = 'published' AND (title LIKE ? OR content LIKE ?) LIMIT 5");
            $stmt->execute([$searchTerm, $searchTerm]);
            $results = array_merge($results, $stmt->fetchAll());
        } else {
            // Search toppers - MySQL
            $stmt = $pdo->prepare("SELECT 'topper' as type, id, name as title, CONCAT(marks, ' - ', class, ' (', year, ')') as description FROM toppers WHERE name LIKE ? LIMIT 5");
            $stmt->execute([$searchTerm]);
            $results = array_merge($results, $stmt->fetchAll());
            
            // Search staff - MySQL
            $stmt = $pdo->prepare("SELECT 'staff' as type, id, name as title, CONCAT(designation, ' - ', department) as description FROM staff WHERE name LIKE ? OR designation LIKE ? LIMIT 5");
            $stmt->execute([$searchTerm, $searchTerm]);
            $results = array_merge($results, $stmt->fetchAll());
            
            // Search announcements - MySQL
            $stmt = $pdo->prepare("SELECT 'announcement' as type, id, title, LEFT(content, 100) as description FROM announcements WHERE status = 'published' AND (title LIKE ? OR content LIKE ?) LIMIT 5");
            $stmt->execute([$searchTerm, $searchTerm]);
            $results = array_merge($results, $stmt->fetchAll());
        }
        
        return array_slice($results, 0, $limit);
    } catch (PDOException $e) {
        return [];
    }
}

/**
 * Get page URL
 */
function getPageUrl($page) {
    $baseUrl = rtrim(dirname($_SERVER['PHP_SELF']), '/');
    return $baseUrl . '/' . $page;
}

/**
 * Check if current page
 */
function isCurrentPage($page) {
    $currentPage = basename($_SERVER['PHP_SELF']);
    return $currentPage === $page;
}

