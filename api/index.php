<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// 1. Forcer le stockage temporaire
$storagePath = '/tmp/storage';
if (!is_dir($storagePath . '/framework/views')) {
    @mkdir($storagePath . '/framework/views', 0755, true);
    @mkdir($storagePath . '/framework/cache', 0755, true);
    @mkdir($storagePath . '/framework/sessions', 0755, true);
}

// 2. Variables de secours
putenv("APP_STORAGE={$storagePath}");
putenv("VIEW_COMPILED_PATH={$storagePath}/framework/views");
putenv("SESSION_DRIVER=cookie");

// 3. Charger Laravel
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

// 4. SOLUTION FORCE : Enregistrer manuellement les services de base si nécessaire
// Cela règle l'erreur "Target class [view] does not exist"
$app->register(Illuminate\View\ViewServiceProvider::class);
$app->register(Illuminate\Events\EventServiceProvider::class);
$app->register(Illuminate\Routing\RoutingServiceProvider::class);

$app->useStoragePath($storagePath);

try {
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $response = $kernel->handle($request = Request::capture());
    $response->send();
    $kernel->terminate($request, $response);
} catch (\Throwable $e) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    echo "<h1>Diagnostic Vercel</h1>";
    echo "Message : " . $e->getMessage() . "<br>";
    echo "Fichier : " . $e->getFile() . ":" . $e->getLine();
}
