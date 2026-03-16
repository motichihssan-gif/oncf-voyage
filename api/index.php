<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// 1. Forcer le stockage temporaire
$storagePath = '/tmp/storage';
foreach (['/framework/views', '/framework/cache', '/framework/sessions', '/logs'] as $dir) {
    if (!is_dir($storagePath . $dir)) {
        @mkdir($storagePath . $dir, 0755, true);
    }
}

// 2. Tuer tout cache parasite
putenv("APP_STORAGE={$storagePath}");
putenv("VIEW_COMPILED_PATH={$storagePath}/framework/views");
putenv("SESSION_DRIVER=cookie");

try {
    require __DIR__ . '/../vendor/autoload.php';
    
    /** @var Application $app */
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    
    // FORCER L'ENREGISTREMENT DU MOTEUR DE VUES (Solution pour Target class view not found)
    $app->register(\Illuminate\View\ViewServiceProvider::class);
    $app->register(\Illuminate\Events\EventServiceProvider::class);

    $app->useStoragePath($storagePath);

    // 3. Exécution avec interception manuelle pour voir l'erreur ORIGINALE
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    
    // On ne laisse PAS Laravel gérer l'exception avec Blade (qui plante)
    try {
        $response = $kernel->handle($request = Request::capture());
        $response->send();
        $kernel->terminate($request, $response);
    } catch (\Throwable $err) {
        // AFFICHAGE DE L'ERREUR QUI DÉCLENCHE TOUT
        header('Content-Type: text/plain; charset=utf-8');
        echo "VÉRITABLE ERREUR DÉTECTÉE :\n";
        echo $err->getMessage() . "\n";
        echo "Dans : " . $err->getFile() . ":" . $err->getLine();
        exit;
    }

} catch (\Throwable $e) {
    header('Content-Type: text/plain; charset=utf-8');
    echo "ERREUR BOOTSTRAP :\n" . $e->getMessage();
    exit;
}
