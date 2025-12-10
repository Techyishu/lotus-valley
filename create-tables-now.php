<?php
// Direct table creation - DELETE AFTER USE!
// This will create all tables in your PostgreSQL database

$host = 'dpg-d4snl9ccjiac739ndr0g-a';
$dbname = 'anthem_school_db';
$username = 'anthem_school_db_user';
$password = 'FBtOLsLeGb1e375A13vuQfp0vbrMJthD';
$port = '5432';

// Try to get from environment variables first (for Render.com)
if (getenv('DB_HOST')) {
    $host = getenv('DB_HOST');
    $dbname = getenv('DB_NAME') ?: $dbname;
    $username = getenv('DB_USER') ?: $username;
    $password = getenv('DB_PASS') ?: $password;
    $port = getenv('DB_PORT') ?: $port;
}

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Creating Tables...</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; max-width: 800px; margin: 0 auto; }
        .success { color: #155724; background: #d4edda; padding: 12px; border-radius: 5px; margin: 10px 0; border-left: 4px solid #28a745; }
        .error { color: #721c24; background: #f8d7da; padding: 12px; border-radius: 5px; margin: 10px 0; border-left: 4px solid #dc3545; }
        .info { color: #004085; background: #d1ecf1; padding: 12px; border-radius: 5px; margin: 10px 0; border-left: 4px solid #17a2b8; }
        .warning { color: #856404; background: #fff3cd; padding: 12px; border-radius: 5px; margin: 10px 0; border-left: 4px solid #ffc107; }
        h1 { color: #333; }
        h2 { color: #555; margin-top: 30px; }
        a { color: #007bff; text-decoration: none; }
        a:hover { text-decoration: underline; }
        hr { margin: 30px 0; border: none; border-top: 2px solid #ddd; }
        ol { line-height: 1.8; }
    </style>
</head>
<body>
    <h1>🔧 Creating Database Tables...</h1>
    
<?php
try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<div class='info'>✅ Connected to database successfully</div>";
    echo "<div class='info'>Database: <strong>$dbname</strong> on <strong>$host</strong></div>";
    
    // Read SQL file
    $sqlFile = __DIR__ . '/database_pgsql.sql';
    if (!file_exists($sqlFile)) {
        throw new Exception("SQL file not found: $sqlFile<br>Make sure database_pgsql.sql exists in the root directory.");
    }
    
    echo "<div class='info'>✅ SQL file found: database_pgsql.sql</div>";
    
    $sql = file_get_contents($sqlFile);
    
    // Split SQL into statements (better handling for PostgreSQL)
    // Remove comments first
    $sql = preg_replace('/--.*$/m', '', $sql);
    $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);
    
    // Split by semicolon, but be careful with functions and triggers
    $statements = preg_split('/;\s*(?=\w)/', $sql);
    
    $created = 0;
    $errors = 0;
    $tablesCreated = [];
    $dataInserted = [];
    
    echo "<div class='info'>📝 Executing SQL statements...</div>";
    
    foreach ($statements as $statement) {
        $statement = trim($statement);
        
        // Skip empty statements
        if (empty($statement) || strlen($statement) < 10) {
            continue;
        }
        
        try {
            $pdo->exec($statement);
            $created++;
            
            // Extract table name if it's a CREATE TABLE
            if (preg_match('/CREATE TABLE (?:IF NOT EXISTS )?(\w+)/i', $statement, $matches)) {
                $tableName = $matches[1];
                $tablesCreated[] = $tableName;
                echo "<div class='success'>✅ Created table: <strong>$tableName</strong></div>";
            } elseif (preg_match('/INSERT INTO (\w+)/i', $statement, $matches)) {
                $tableName = $matches[1];
                if (!in_array($tableName, $dataInserted)) {
                    $dataInserted[] = $tableName;
                    echo "<div class='info'>📊 Inserted sample data into: <strong>$tableName</strong></div>";
                }
            }
            
        } catch (PDOException $e) {
            $errorMsg = $e->getMessage();
            
            // Ignore "already exists" and "duplicate" errors
            if (strpos($errorMsg, 'already exists') !== false || 
                strpos($errorMsg, 'duplicate') !== false ||
                strpos($errorMsg, 'ON CONFLICT') !== false ||
                strpos($errorMsg, 'violates unique constraint') !== false) {
                // These are expected, ignore them
                continue;
            }
            
            $errors++;
            echo "<div class='error'>❌ Error: " . htmlspecialchars($errorMsg) . "</div>";
            echo "<div class='info'>Statement: " . htmlspecialchars(substr($statement, 0, 100)) . "...</div>";
        }
    }
    
    echo "<hr>";
    echo "<div class='success'><h2>✅ Installation Complete!</h2></div>";
    echo "<div class='info'><strong>Summary:</strong></div>";
    echo "<ul>";
    echo "<li>Tables created: <strong>" . count($tablesCreated) . "</strong></li>";
    echo "<li>SQL statements executed: <strong>$created</strong></li>";
    if ($errors > 0) {
        echo "<li>Errors encountered: <strong>$errors</strong> (some may be expected)</li>";
    }
    echo "</ul>";
    
    // Verify admin_users table exists
    try {
        $stmt = $pdo->query("SELECT COUNT(*) FROM admin_users");
        $count = $stmt->fetchColumn();
        echo "<div class='success'>✅ Verified: <strong>admin_users</strong> table exists with <strong>$count</strong> record(s)</div>";
        
        // Check if admin user exists
        $stmt = $pdo->query("SELECT username, email FROM admin_users LIMIT 1");
        $admin = $stmt->fetch();
        if ($admin) {
            echo "<div class='info'>✅ Admin user found: <strong>" . htmlspecialchars($admin['username']) . "</strong> (" . htmlspecialchars($admin['email']) . ")</div>";
        }
    } catch (PDOException $e) {
        echo "<div class='error'>❌ Warning: Could not verify admin_users table: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
    
    // List all created tables
    if (!empty($tablesCreated)) {
        echo "<div class='info'><strong>Tables created:</strong> " . implode(', ', $tablesCreated) . "</div>";
    }
    
    echo "<hr>";
    echo "<div class='warning'><h2>⚠️ IMPORTANT SECURITY WARNING</h2>";
    echo "<p><strong>DELETE THIS FILE IMMEDIATELY</strong> after verifying everything works!</p>";
    echo "<p>This file contains database credentials and should not remain on your server.</p></div>";
    
    echo "<hr>";
    echo "<h2>🎉 Next Steps:</h2>";
    echo "<ol>";
    echo "<li><strong>DELETE THIS FILE</strong> (create-tables-now.php) for security</li>";
    echo "<li><a href='admin/login.php' style='display:inline-block;background:#007bff;color:white;padding:10px 20px;border-radius:5px;margin-top:10px;'>Go to Admin Login →</a></li>";
    echo "<li>Login credentials: <strong>admin</strong> / <strong>admin123</strong></li>";
    echo "<li><strong>Change the admin password immediately</strong> after first login!</li>";
    echo "<li>Visit your <a href='index.php'>homepage</a> to see the website</li>";
    echo "</ol>";
    
} catch (PDOException $e) {
    echo "<div class='error'><h2>❌ Database Connection Error</h2>";
    echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<div class='info'><strong>Troubleshooting:</strong></div>";
    echo "<ul>";
    echo "<li>Check your database credentials</li>";
    echo "<li>Verify the database is running on Render</li>";
    echo "<li>Check environment variables are set correctly</li>";
    echo "<li>Host: <strong>$host</strong></li>";
    echo "<li>Database: <strong>$dbname</strong></li>";
    echo "<li>User: <strong>$username</strong></li>";
    echo "<li>Port: <strong>$port</strong></li>";
    echo "</ul></div>";
} catch (Exception $e) {
    echo "<div class='error'><h2>❌ Error</h2>";
    echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p></div>";
}
?>
</body>
</html>

