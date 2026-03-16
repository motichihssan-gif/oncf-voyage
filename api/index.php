<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

// 1. Diagnostics d'urgence absolus
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('LARAVEL_START', microtime(true));

// 2. Préparer le stockage temporaire (Crucial pour Vercel)
$storagePath = '/tmp/storage';
foreach (['/framework/views', '/framework/cache', '/framework/sessions', '/logs'] as $dir) {
    if (!is_dir($storagePath . $dir)) {
        @mkdir($storagePath . $dir, 0755, true);
    }
}

try {
    // 3. Charger l'application
    require __DIR__ . '/../vendor/autoload.php';
    
    /** @var Application $app */
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    
    // 4. Forcer le stockage AVANT de démarrer quoi que ce soit
    $app->useStoragePath($storagePath);
    putenv("VIEW_COMPILED_PATH={$storagePath}/framework/views");

    // 5. Capturer l'erreur réelle avant le crash du moteur de "Vues"
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $response = $kernel->handle(
        $request = Request::capture()
    );
    $response->send();
    $kernel->terminate($request, $response);

} catch (\Throwable $e) {
    // VOICI L'ERREUR RÉELLE
    header('Content-Type: text/html; charset=utf-8');
    echo "<h1>🔍 Diagnostic de l'Erreur Racine</h1>";
    echo "<p style='color:red; font-size:1.2rem;'><strong>Erreur :</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>Fichier :</strong> " . $e->getFile() . " (Ligne " . $e->getLine() . ")</p>";
    echo "<h3>Stack Trace :</h3>";
    echo "<pre style='background:#f4f4f4; padding:10px; border:1px solid #ccc;'>" . $e->getTraceAsString() . "</pre>";
    exit;
}
