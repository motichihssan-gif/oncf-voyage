<?php
$passwords = ['', 'root', '123456', '1234', 'mysql', 'admin'];
foreach ($passwords as $p) {
    try {
        $pdo = new PDO("mysql:host=127.0.0.1", "root", $p);
        echo "FOUND_PASSWORD: '$p'\n";
        exit;
    } catch (PDOException $e) {
        // Continue
    }
}
echo "PASSWORD_NOT_FOUND\n";
