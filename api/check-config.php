<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🔍 Scan Complet ONCF Voyage</h1>";

$configFiles = [
    'app.php', 'auth.php', 'cache.php', 'database.php', 
    'filesystems.php', 'logging.php', 'mail.php', 
    'queue.php', 'services.php', 'session.php', 'view.php'
];

echo "<h3>Vérification du dossier config/</h3>";
foreach ($configFiles as $file) {
    $path = __DIR__ . '/../config/' . $file;
    if (file_exists($path)) {
        echo "✅ config/$file : TROUVÉ (" . substr(sprintf('%o', fileperms($path)), -4) . ")<br>";
    } else {
        echo "❌ config/$file : <strong style='color:red;'>MANQUANT</strong><br>";
    }
}

echo "<h3>Vérification Système</h3>";
$systemFiles = [
    '../vendor/autoload.php',
    '../bootstrap/app.php',
    '../routes/web.php'
];

foreach ($systemFiles as $file) {
    if (file_exists(__DIR__ . '/' . $file)) {
        echo "✅ $file : TROUVÉ<br>";
    } else {
        echo "❌ $file : <strong style='color:red;'>MANQUANT</strong><br>";
    }
}
