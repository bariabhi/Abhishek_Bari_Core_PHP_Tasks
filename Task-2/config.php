<?php
// Database Connectivity ****
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "manage_db";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Determine the protocol (HTTP or HTTPS)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

// Get the host
$host = $_SERVER['HTTP_HOST'];

// Get the directory of the current file
$directory = dirname($_SERVER['PHP_SELF']);

// Construct the base URL
$base_url = $protocol . $host . $directory;

// Ensure it ends with a slash
if (substr($base_url, -1) != '/') {
    $base_url .= '/';
}

// Make $base_url available globally
define('BASE_URL', $base_url);

?>
