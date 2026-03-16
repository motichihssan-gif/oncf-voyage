<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Foundation\PackageManifest;
use Illuminate\Filesystem\Filesystem;

define('LARAVEL_START', microtime(true));

// 1. Dossiers temporaires
$storagePath = '/tmp/storage';
$cachePath = '/tmp/storage/bootstrap/cache';

foreach ([$storagePath . '/framework/views', $storagePath . '/framework/cache', $storagePath . '/framework/sessions', $storagePath . '/logs', $cachePath] as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// 2. Configuration d'urgence
putenv("APP_STORAGE={$storagePath}");
putenv("VIEW_COMPILED_PATH={$storagePath}/framework/views");

try {
    require __DIR__ . '/../vendor/autoload.php';
    
    /** @var Application $app */
    $app = require_once __DIR__ . '/../bootstrap/app.php';

    // --- REDIRECTION ABSOLUE DES CACHES (POUR VERCEL) ---
    // On force Laravel à utiliser /tmp pour TOUS ses fichiers de démarrage
    $app->useStoragePath($storagePath);
    
    // On définit les chemins de fichiers de cache individuellement
    $app->setCachedServicesPath($cachePath . '/services.php');
    $app->setCachedPackagesPath($cachePath . '/packages.php');
    $app->setCachedConfigPath($cachePath . '/config.php');
    $app->setCachedRoutesPath($cachePath . '/routes.php');
    $app->setCachedEventsPath($cachePath . '/events.php');

    // On s'assure que le PackageManifest utilise aussi ce chemin
    $app->instance(PackageManifest::class, new PackageManifest(
        new Filesystem,
        $app->basePath(),
        $cachePath . '/packages.php'
    ));

    // 4. Lancer le site
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $response = $kernel->handle($request = Request::capture());
    $response->send();
    $kernel->terminate($request, $response);

} catch (\Throwable $e) {
    header('Content-Type: text/plain; charset=utf-8');
    echo "DIAGNOSTIC FINAL VERCEL :\n";
    echo $e->getMessage() . "\n";
    echo "Fichier : " . $e->getFile() . ":" . $e->getLine();
    echo "\n\nTRACE :\n" . $e->getTraceAsString();
    exit;
}
