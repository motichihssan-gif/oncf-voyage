<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// 1. Forcer le stockage temporaire (Vital pour Vercel)
$storagePath = '/tmp/storage';
$bootstrapCachePath = '/tmp/storage/bootstrap/cache';

foreach ([$storagePath . '/framework/views', $storagePath . '/framework/cache', $storagePath . '/framework/sessions', $storagePath . '/logs', $bootstrapCachePath] as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// 2. Variables de secours
putenv("APP_STORAGE={$storagePath}");
putenv("VIEW_COMPILED_PATH={$storagePath}/framework/views");

try {
    // 3. Charger Laravel
    require __DIR__ . '/../vendor/autoload.php';
    
    /** @var Application $app */
    $app = require_once __DIR__ . '/../bootstrap/app.php';

    // --- SOLUTION MIRACLE POUR VERCEL ---
    // On redirige TOUS les caches (Storage ET Bootstrap) vers /tmp
    $app->useStoragePath($storagePath);
    
    // Cette ligne dit à Laravel d'écrire ses manifestes de packages dans /tmp
    if (method_exists($app, 'useBootstrapCachePath')) {
        $app->useBootstrapCachePath($bootstrapCachePath);
    } elseif (method_exists($app, 'setBootstrapCachePath')) {
        $app->setBootstrapCachePath($bootstrapCachePath);
    }

    // On branche les moteurs de base
    $app->register(\Illuminate\Filesystem\FilesystemServiceProvider::class);
    $app->register(\Illuminate\View\ViewServiceProvider::class);
    $app->register(\Illuminate\Events\EventServiceProvider::class);
    $app->register(\Illuminate\Routing\RoutingServiceProvider::class);

    // 4. Lancer le site
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $response = $kernel->handle($request = Request::capture());
    $response->send();
    $kernel->terminate($request, $response);

} catch (\Throwable $e) {
    header('Content-Type: text/plain; charset=utf-8');
    echo "DIAGNOSTIC FINAL :\n";
    echo $e->getMessage() . "\n";
    echo "Dans : " . $e->getFile() . ":" . $e->getLine();
    exit;
}
