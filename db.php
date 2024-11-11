<?php
// db.php

$host = 'localhost'; // Database host
$db = 'user_auth'; // Database name
$user = 'root'; // Database username
$pass = ''; // Database password (default is empty for XAMPP)

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Handle connection error
    die("Database connection failed: " . $e->getMessage());
}
?>

