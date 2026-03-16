<?php

// Diagnostics ultra-simplifiés pour Vercel PHP
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Diagnostic Vercel PHP</h1>";
echo "Version PHP : " . phpversion() . "<br>";
echo "Interface : " . php_sapi_name() . "<br>";
echo "Script : " . __FILE__ . "<br>";

$vendor = __DIR__ . '/../vendor/autoload.php';
if (file_exists($vendor)) {
    echo "✅ Vendor trouvé<br>";
} else {
    echo "❌ Vendor MANQUANT (Problème d'installation sur Vercel)<br>";
}

$bootstrap = __DIR__ . '/../bootstrap/app.php';
if (file_exists($bootstrap)) {
    echo "✅ Bootstrap trouvé<br>";
} else {
    echo "❌ Bootstrap MANQUANT<br>";
}

echo "<h2>Variables d'environnement détectées :</h2>";
echo "APP_KEY : " . (getenv('APP_KEY') ? 'DÉFINIE' : 'VIDE (Erreur probable)') . "<br>";
echo "DB_HOST : " . (getenv('DB_HOST') ? getenv('DB_HOST') : 'VIDE') . "<br>";
echo "VIEW_COMPILED_PATH : " . getenv('VIEW_COMPILED_PATH') . "<br>";
