<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// 1. Préparer le stockage temporaire
$storagePath = '/tmp/storage';
foreach (['/framework/views', '/framework/cache', '/framework/sessions', '/logs'] as $dir) {
    if (!is_dir($storagePath . $dir)) {
        @mkdir($storagePath . $dir, 0755, true);
    }
}

// 2. Charger Laravel
require __DIR__ . '/../vendor/autoload.php';

/** @var Application $app */
$app = require_once __DIR__ . '/../bootstrap/app.php';

// 3. Gérer la requête normalement
try {
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $response = $kernel->handle($request = Request::capture());
    $response->send();
    $kernel->terminate($request, $response);
} catch (\Throwable $e) {
    header('Content-Type: text/html; charset=utf-8');
    echo "<h1>Diagnostic Vercel</h1>";
    echo "Message : " . htmlspecialchars($e->getMessage()) . "<br>";
    echo "Fichier : " . $e->getFile() . ":" . $e->getLine();
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
