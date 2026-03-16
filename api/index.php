<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// 1. Forcer le système de fichiers temporaire
$storagePath = '/tmp/storage';
if (!is_dir($storagePath . '/framework/views')) {
    @mkdir($storagePath . '/framework/views', 0755, true);
    @mkdir($storagePath . '/framework/cache', 0755, true);
    @mkdir($storagePath . '/framework/sessions', 0755, true);
}

// 2. Variables critiques
putenv("APP_STORAGE={$storagePath}");
putenv("VIEW_COMPILED_PATH={$storagePath}/framework/views");
putenv("SESSION_DRIVER=cookie");

// 3. Amorçage de Laravel
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

// 4. Forcer la configuration avant exécution
$app->useStoragePath($storagePath);

// On capture tout pour le debug
try {
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $response = $kernel->handle($request = Request::capture());
    $response->send();
    $kernel->terminate($request, $response);
} catch (\Throwable $e) {
    echo "<h1>Debug Laravel Vercel</h1>";
    echo "L'erreur view [does not exist] est souvent causée par un problème de config.<br>";
    echo "Message : " . $e->getMessage() . "<br>";
    echo "Fichier : " . $e->getFile() . ":" . $e->getLine();
}
