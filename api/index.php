<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

// Debugging for deployment
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('LARAVEL_START', microtime(true));

// Configuration Vercel
$storagePath = '/tmp/storage';
foreach (['/framework/views', '/framework/cache', '/framework/sessions', '/logs'] as $dir) {
    if (!is_dir($storagePath . $dir)) {
        @mkdir($storagePath . $dir, 0755, true);
    }
}

try {
    require __DIR__ . '/../vendor/autoload.php';
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $app->useStoragePath($storagePath);
    $app->handleRequest(Request::capture());
} catch (\Throwable $e) {
    header('Content-Type: text/html; charset=utf-8');
    echo "<h1>Diagnostic Vercel</h1>";
    echo "Message : " . htmlspecialchars($e->getMessage()) . "<br>";
    echo "Fichier : " . $e->getFile() . ":" . $e->getLine();
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
