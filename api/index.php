<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

define('LARAVEL_START', microtime(true));

// Rediriger le cache vers /tmp pour Vercel
putenv('VIEW_COMPILED_PATH=/tmp');
putenv('SESSION_DRIVER=cookie');
putenv('LOG_CHANNEL=stderr');
putenv('APP_STORAGE=/tmp');

// Register the Composer autoloader...
require __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__ . '/../bootstrap/app.php';

// AUTO-MIGRATION : Crée les tables sur AlwaysData au premier chargement
try {
    // On ne migre que si la table "voyages" n'existe pas pour ne pas ralentir le site
    if (!Schema::hasTable('voyages')) {
        Artisan::call('migrate:fresh', ['--force' => true, '--seed' => true]);
    }
} catch (\Exception $e) {
    // Si la DB n'est pas encore prete, on continue pour voir l'erreur Laravel
}

$app->handleRequest(Request::capture());
