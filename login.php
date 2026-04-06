<?php //this file contains all the login details required
$host = '127.0.0.1';
$db = 's2328610_website'; // edit this
$user = 's2328610'; // edit this
$pass = 'Pedrocardoso13!'; // edit this


$port = 3306;

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("DB Connection failed: " . $e->getMessage());
}


?>

