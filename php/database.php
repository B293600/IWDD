<?php
# Connects to the database
$host = '127.0.0.1';
$db = 's2328610_web_project';
$user = 's2328610';
$pass = 'Pedrocardoso13!';
$charset = 'utf8mb4';
# String to connect to the database
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
#Set PDO options, code adapted from: https://www.php.net/manual/en/pdo.setattribute.php
$options = [
	PDO::ATTR_ERRMODE		=> PDO::ERRMODE_EXCEPTION,
	PDO::ATTR_DEFAULT_FETCH_MODE	=> PDO::FETCH_ASSOC,
	PDO::ATTR_EMULATE_PREPARES	=> false,
];
# Progress report for user
echo "Connecting to PDO.../n";
#
try {
	$pdo_conn = new PDO ($dsn, $user, $pass, $options);
	echo "Connection successful!";
} catch (PDOException $error) {
	echo "Connection failed: " . $error->getMessage() . "/n";
}

?>
