<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

// 1. Diagnostics d'urgence
ini_set('display_errors', 1);
error_reporting(E_ALL);

define('LARAVEL_START', microtime(true));

// 2. Vérification des variables Vitales
$envVars = ['APP_KEY', 'DB_HOST', 'DB_USERNAME', 'DB_PASSWORD', 'DB_DATABASE'];
$missing = [];
foreach ($envVars as $var) {
    if (!getenv($var) && !isset($_ENV[$var])) {
        $missing[] = $var;
    }
}

if (!empty($missing)) {
    echo "<h1>❌ Variables d'environnement manquantes</h1>";
    echo "Il manque ces variables dans votre Dashboard Vercel : <strong>" . implode(', ', $missing) . "</strong>";
    exit;
}

// 3. Préparation du stockage
$storagePath = '/tmp/storage';
foreach (['/framework/views', '/framework/cache', '/framework/sessions', '/logs'] as $dir) {
    if (!is_dir($storagePath . $dir)) {
        @mkdir($storagePath . $dir, 0755, true);
    }
}

try {
    require __DIR__ . '/../vendor/autoload.php';
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $app->useStoragePath($storagePath);
    
    // 4. Test de connexion DB rapide avant de lancer Laravel
    $dbHost = getenv('DB_HOST');
    $dbName = getenv('DB_DATABASE');
    $dbUser = getenv('DB_USERNAME');
    $dbPass = getenv('DB_PASSWORD');
    
    // On laisse Laravel gérer la requête
    $app->handleRequest(Request::capture());

} catch (\Throwable $e) {
    header('Content-Type: text/html; charset=utf-8');
    echo "<h1>Diagnostic de Panne</h1>";
    echo "<strong>Erreur :</strong> " . htmlspecialchars($e->getMessage()) . "<br>";
    echo "<strong>Localisation :</strong> " . $e->getFile() . ":" . $e->getLine();
    echo "<h3>Détails :</h3><pre>" . $e->getTraceAsString() . "</pre>";
}
