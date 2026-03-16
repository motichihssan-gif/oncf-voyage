<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

// 1. Diagnostics d'urgence (On ne laisse RIEN passer)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('LARAVEL_START', microtime(true));

// 2. Préparation du stockage temporaire
$storagePath = '/tmp/storage';
foreach (['/framework/views', '/framework/cache', '/framework/sessions', '/logs'] as $dir) {
    if (!is_dir($storagePath . $dir)) {
        @mkdir($storagePath . $dir, 0755, true);
    }
}

try {
    // 3. Charger Laravel
    require __DIR__ . '/../vendor/autoload.php';
    
    /** @var Application $app */
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    
    // Forcer le stockage
    $app->useStoragePath($storagePath);

    // 4. Exécution MANUELLE du Kernel pour capturer l'erreur "brute"
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    
    // On essaie de capturer l'erreur AVANT que Laravel ne tente de l'afficher avec Blade
    try {
        $response = $kernel->handle($request = Request::capture());
        $response->send();
        $kernel->terminate($request, $response);
    } catch (\Throwable $err) {
        // C'EST ICI QUE NOUS AURONS LA VÉRITÉ
        throw $err; 
    }

} catch (\Throwable $e) {
    // AFFICHAGE DE L'ERREUR RACINE EN TEXTE BRUT
    header('Content-Type: text/plain; charset=utf-8');
    echo "--- ERREUR RACINE DÉTECTÉE ---\n";
    echo "MESSAGE : " . $e->getMessage() . "\n";
    echo "FICHIER : " . $e->getFile() . "\n";
    echo "LIGNE : " . $e->getLine() . "\n";
    echo "\n--- STACK TRACE ---\n";
    echo $e->getTraceAsString();
    exit;
}
