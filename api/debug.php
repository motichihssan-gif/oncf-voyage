<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Diagnostic Approfondi</h1>";

// 1. Vérification PDO
echo "<h2>Extensions PHP :</h2>";
if (extension_loaded('pdo_mysql')) {
    echo "✅ pdo_mysql est CHARGÉ<br>";
} else {
    echo "❌ pdo_mysql est MANQUANT (C'est le problème !)<br>";
}

// 2. Test Connexion AlwaysData
echo "<h2>Test Connexion AlwaysData :</h2>";
$host = getenv('DB_HOST');
$user = getenv('DB_USERNAME');
$pass = getenv('DB_PASSWORD');
$db   = getenv('DB_DATABASE');

echo "Tentative sur : $host | Utilisateur : $user | Base : $db<br>";

try {
    $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
    $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_TIMEOUT => 5];
    $pdo = new PDO($dsn, $user, $pass, $options);
    echo "✅ CONNEXION RÉUSSIE À LA BASE DE DONNÉES !<br>";
} catch (\PDOException $e) {
    echo "❌ ÉCHEC DE CONNEXION : " . $e->getMessage() . "<br>";
}

// 3. Permissions temporaires
echo "<h2>Tests Système :</h2>";
if (is_writable('/tmp')) {
    echo "✅ /tmp est accessible en écriture<br>";
} else {
    echo "❌ /tmp n'est pas accessible<br>";
}
