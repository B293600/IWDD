<?php //this file contains all the login details required
$host = '127.0.0.1';
$db = 's2328610_website'; 
$user = 's2328610'; 
$pass = 'Pedrocardoso13!';

// Specify port
$port = 3306;
// Defines how to connect to the database
$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
// Created a PDO connection
try {
    $pdo = new PDO($dsn, $user, $pass, [
// For error handling
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
// Stop execution and display error message if connection fails
    die("DB Connection failed: " . $e->getMessage());
}


?>

