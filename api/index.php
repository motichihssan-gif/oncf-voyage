<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Foundation\PackageManifest;
use Illuminate\Filesystem\Filesystem;

define('LARAVEL_START', microtime(true));

// 1. Préparer le stockage temporaire pour Vercel
$storagePath = '/tmp/storage';
$bootstrapCachePath = '/tmp/storage/bootstrap/cache';

foreach ([$storagePath . '/framework/views', $storagePath . '/framework/cache', $storagePath . '/framework/sessions', $storagePath . '/logs', $bootstrapCachePath] as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// 2. Configuration d'urgence
putenv("APP_STORAGE={$storagePath}");
putenv("VIEW_COMPILED_PATH={$storagePath}/framework/views");

try {
    // 3. Charger Laravel
    require __DIR__ . '/../vendor/autoload.php';
    
    /** @var Application $app */
    $app = require_once __DIR__ . '/../bootstrap/app.php';

    // --- SOLUTION TECHNIQUE AVANCÉE POUR VERCEL ---
    // On remplace le moteur de manifestation par une version qui pointe sur /tmp
    // car Laravel 12 fixe ce chemin dès sa création.
    $app->instance(PackageManifest::class, new PackageManifest(
        new Filesystem,
        $app->basePath(),
        $bootstrapCachePath
    ));

    $app->useStoragePath($storagePath);

    // On branche manuellement les moteurs vitaux
    $app->register(\Illuminate\Filesystem\FilesystemServiceProvider::class);
    $app->register(\Illuminate\View\ViewServiceProvider::class);
    $app->register(\Illuminate\Events\EventServiceProvider::class);
    $app->register(\Illuminate\Routing\RoutingServiceProvider::class);

    // 4. Lancer le Kernel
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $response = $kernel->handle($request = Request::capture());
    $response->send();
    $kernel->terminate($request, $response);

} catch (\Throwable $e) {
    header('Content-Type: text/plain; charset=utf-8');
    echo "DIAGNOSTIC FINAL VERCEL :\n";
    echo $e->getMessage() . "\n";
    echo "Fichier : " . $e->getFile() . ":" . $e->getLine();
    exit;
}
