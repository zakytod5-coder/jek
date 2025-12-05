<?php
$host = 'localhost';
$dbname = 'sewa_gedung';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // Jika database tidak ada, redirect ke setup
    if (strpos($e->getMessage(), 'Unknown database') !== false) {
        header('Location: setup_database.php');
        exit;
    }
    die("Connection failed: " . $e->getMessage());
}
?>