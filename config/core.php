<?php
session_start();

// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

$host = 'localhost';
$dbname = 'most_db'; // Make sure this database exists in your MySQL server
$username = 'root';
$password = ''; // Default password for XAMPP is empty

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Database connection successful."; // This should be visible if connection is successful
} catch (PDOException $e) {
    die("Error connecting to the database: " . $e->getMessage());
}

define('BASE_URL', 'http://localhost/most/');
?>