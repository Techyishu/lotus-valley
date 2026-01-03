<?php
/**
 * Password Hash Generator
 * Generate a bcrypt hash for the admin password
 */

$password = 'Admin@lotus12';
$hash = password_hash($password, PASSWORD_BCRYPT);

echo "Password: " . $password . "\n";
echo "Bcrypt Hash: " . $hash . "\n\n";
echo "SQL UPDATE Statement:\n";
echo "UPDATE `admin_users` SET `password` = '" . $hash . "' WHERE `id` = 1;\n";
?>