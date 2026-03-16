<?php
$host = '127.0.0.1';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS oncf");
    echo "Database 'oncf' created successfully or already exists.\n";
} catch (PDOException $e) {
    die("DB ERROR: " . $e->getMessage());
}
