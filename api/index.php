<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Configuration Vercel
putenv('VIEW_COMPILED_PATH=/tmp');
putenv('SESSION_DRIVER=cookie');
putenv('LOG_CHANNEL=stderr');
putenv('APP_STORAGE=/tmp');
$_ENV['VIEW_COMPILED_PATH'] = '/tmp';

// Register the Composer autoloader...
require __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel...
/** @var Application $app */
$app = require_once __DIR__ . '/../bootstrap/app.php';

// AUTO-MIGRATION : Utilisation de l'instance $app directement (évite l'erreur Facade)
try {
    $db = $app->make('db');
    $schema = $db->connection()->getSchemaBuilder();
    
    if (!$schema->hasTable('voyages')) {
        \Illuminate\Support\Facades\Artisan::call('migrate:fresh', ['--force' => true, '--seed' => true]);
    }
} catch (\Exception $e) {
    // On ignore silencieusement les erreurs ici pour laisser Laravel les afficher normalement plus bas
}

$app->handleRequest(Request::capture());
