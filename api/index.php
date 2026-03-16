<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// 1. Dossiers temporaires
$storagePath = '/tmp/storage';
$cachePath = '/tmp/storage/bootstrap/cache';

foreach ([$storagePath . '/framework/views', $storagePath . '/framework/cache', $storagePath . '/framework/sessions', $storagePath . '/logs', $cachePath] as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// 2. Redirection officielle des caches
putenv("APP_STORAGE={$storagePath}");
putenv("APP_SERVICES_CACHE={$cachePath}/services.php");
putenv("APP_PACKAGES_CACHE={$cachePath}/packages.php");
putenv("APP_CONFIG_CACHE={$cachePath}/config.php");
putenv("APP_ROUTES_CACHE={$cachePath}/routes.php");
putenv("APP_EVENTS_CACHE={$cachePath}/events.php");
putenv("VIEW_COMPILED_PATH={$storagePath}/framework/views");

// 3. Charger Laravel
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->useStoragePath($storagePath);

// 4. Lancer le site proprement
$app->handleRequest(Request::capture());
