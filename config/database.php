<?php
session_start();

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'student_network');

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