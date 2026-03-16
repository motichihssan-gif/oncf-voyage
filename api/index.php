<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Foundation\PackageManifest;
use Illuminate\Filesystem\Filesystem;

define('LARAVEL_START', microtime(true));

// 1. Préparer les dossiers temporaires (Indispensables)
$storagePath = '/tmp/storage';
$cachePath = '/tmp/storage/bootstrap/cache'; // C'est un DOSSIER

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
    $app = require_once __DIR__ . '/../bootstrap/app.php';

    // --- CORRECTION DU CHEMIN DE MANIFESTE ---
    // On donne le chemin vers le FICHIER packages.php dans le dossier /tmp
    $app->instance(PackageManifest::class, new PackageManifest(
        new Filesystem,
        $app->basePath(),
        $cachePath . '/packages.php' // <-- Le correctif est ICI
    ));

    $app->useStoragePath($storagePath);

    // On branche les moteurs vitaux
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
    echo "Fichier : " . $e->getFile() . ":" . $e->getLine();
    exit;
}
