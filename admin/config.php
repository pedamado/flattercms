<?php
// Prevent direct access
if (!defined('IN_ADMIN')) {
    die('Direct access denied');
}

// /your-site-root/
// └── admin/
//     ├── config.php
//     ├── index.php
//     └── .htaccess
// In config.php:
//     Replace 'editor' with your desired username
//     Replace 'your_secure_password_here' with your strong password

// root/admin/.htaccess

// # Block direct access to sensitive files
// <FilesMatch "^(config\.php|\.htaccess)$">
//     Order allow,deny
//     Deny from all
// </FilesMatch>

// # Prevent directory listing
// Options -Indexes

// # Protect session cookies
// php_value session.cookie_httponly 1
// php_value session.cookie_secure 1
// php_value session.use_strict_mode 1


// chmod 644 admin/config.php
// chmod 700 admin/.htaccess



// ========================
// Admin Credentials
// ========================
define('ADMIN_USERNAME', 'admin');          // Change this username
define('ADMIN_PASSWORD_HASH', password_hash('admin', PASSWORD_DEFAULT)); // Change this password

// ========================
// Security Settings
// ========================
define('LOGIN_ATTEMPTS_LIMIT', 3);          // Max failed attempts
define('SESSION_TIMEOUT', 1800);            // 30 minutes in seconds

// ========================
// Website Configuration
// ========================
define('BASE_URL', 'http://localhost:8888/flatter/'); // Include trailing slash
define('MEDIA_URL', BASE_URL . 'media/');      // Media files URL

// ========================
// WARNING: Do not edit below this line
// ========================
session_start();
