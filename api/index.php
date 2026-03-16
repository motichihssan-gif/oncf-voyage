<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// 1. Préparer les dossiers temporaires dans /tmp
$storagePath = '/tmp/storage';
$cachePath = '/tmp/storage/bootstrap/cache';

foreach ([$storagePath . '/framework/views', $storagePath . '/framework/cache', $storagePath . '/framework/sessions', $storagePath . '/logs', $cachePath] as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// 2. REDIRECTION OFFICIELLE DES CACHES VIA VARIABLES D'ENVIRONNEMENT
// Laravel 11/12 utilise ces variables pour définir ses chemins de cache
putenv("APP_STORAGE={$storagePath}");
putenv("APP_SERVICES_CACHE={$cachePath}/services.php");
putenv("APP_PACKAGES_CACHE={$cachePath}/packages.php");
putenv("APP_CONFIG_CACHE={$cachePath}/config.php");
putenv("APP_ROUTES_CACHE={$cachePath}/routes.php");
putenv("APP_EVENTS_CACHE={$cachePath}/events.php");
putenv("VIEW_COMPILED_PATH={$storagePath}/framework/views");

try {
    // 3. Charger Laravel
    require __DIR__ . '/../vendor/autoload.php';
    $app = require_once __DIR__ . '/../bootstrap/app.php';

    // On force l'utilisation du stockage
    $app->useStoragePath($storagePath);

    // 4. Lancer le site
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $response = $kernel->handle($request = Request::capture());
    $response->send();
    $kernel->terminate($request, $response);

} catch (\Throwable $e) {
    header('Content-Type: text/plain; charset=utf-8');
    echo "DIAGNOSTIC VERCEL :\n";
    echo $e->getMessage() . "\n";
    echo "Fichier : " . $e->getFile() . ":" . $e->getLine() . "\n";
    exit;
}
