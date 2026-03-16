<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

// 1. Diagnostics d'urgence (Afficher TOUT)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('LARAVEL_START', microtime(true));

// 2. Préparer le dossier temporaire pour Vercel
$storagePath = '/tmp/storage';
foreach (['/framework/views', '/framework/cache', '/framework/sessions', '/logs'] as $dir) {
    if (!is_dir($storagePath . $dir)) {
        @mkdir($storagePath . $dir, 0755, true);
    }
}

// 3. Configuration forcée
putenv("APP_STORAGE={$storagePath}");
putenv("VIEW_COMPILED_PATH={$storagePath}/framework/views");
putenv("SESSION_DRIVER=cookie");
putenv("LOG_CHANNEL=stderr");

try {
    // 4. Charger l'application
    require __DIR__ . '/../vendor/autoload.php';

    /** @var Application $app */
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    
    // Configurer le stockage avant de gérer la requête
    $app->useStoragePath($storagePath);

    // 5. Gérer la requête
    $app->handleRequest(Request::capture());

} catch (\Throwable $e) {
    echo "<h1>Debug Laravel Vercel</h1>";
    echo "<p><strong>Message :</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Fichier :</strong> " . $e->getFile() . " (" . $e->getLine() . ")</p>";
    echo "<h3>Stack Trace :</h3>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
