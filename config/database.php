<?php

$envPath = __DIR__ . '/../.env';
if (file_exists($envPath)) {
    $env = parse_ini_file($envPath);
} else {
    $env = [];
}
$host     = $env['DB_HOST'] ?? 'localhost';
$db       = $env['DB_NAME'] ?? 'kim_db';
$user     = $env['DB_USER'] ?? 'root';
$password = $env['DB_PASS'] ?? '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,       // randurile sunt returnate din bd sub forma unui obiect
];

try{
    $pdo = new PDO($dsn, $user, $password, $options);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

define('AVATAR_PATH', '/kim/public/images/avatars/');
?>