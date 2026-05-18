<?php
session_start();

// 1. Check if running live on Railway, otherwise fall back to local XAMPP
define('DB_HOST', getenv('MYSQLHOST') ?: '127.0.0.1');
define('DB_USER', getenv('MYSQLUSER') ?: 'root');
define('DB_PASS', getenv('MYSQLPASSWORD') ?: '');
define('DB_NAME', getenv('MYSQLDATABASE') ?: 'student_network');
define('DB_PORT', getenv('MYSQLPORT') ?: '3306');

// 2. Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

// 3. Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset
$conn->set_charset("utf8mb4");

// Site configuration
define('SITE_NAME', 'Students Networking');
define('SITE_URL', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST']);
define('UPLOAD_PATH', SITE_URL . '/assets/uploads/');
define('MAX_FILE_SIZE', 5242880); // 5MB

// Timezone
date_default_timezone_set('Africa/Lagos');

// Error reporting (disable in production if needed)
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset
$conn->set_charset("utf8mb4");

// Site configuration
define('SITE_NAME', 'Students Networking');
define('SITE_URL', 'http://localhost/STUDENTNETWORKING/');
define('UPLOAD_PATH', SITE_URL . 'assets/uploads/');
define('MAX_FILE_SIZE', 5242880); // 5MB

// Timezone
date_default_timezone_set('Africa/Lagos');

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>