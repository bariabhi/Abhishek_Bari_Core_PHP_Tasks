<?php
// Database connection settings
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'manage_db';

// Create a database connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


?>