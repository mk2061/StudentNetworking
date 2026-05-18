<?php
session_start();

<?php
// 1. Check if running live on Railway, otherwise fall back to local XAMPP
$host     = getenv('MYSQLHOST')     ?: '127.0.0.1';
$user     = getenv('MYSQLUSER')     ?: 'root';
$password = getenv('MYSQLPASSWORD') ?: '';
$database = getenv('MYSQLDATABASE') ?: 'student_network'; 
$port     = getenv('MYSQLPORT')     ?: '3306';

// 2. Establish the connection
$conn = new mysqli($host, $user, $password, $database, $port);

// 3. Catch connection errors safely
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
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