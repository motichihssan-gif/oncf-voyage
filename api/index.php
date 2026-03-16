<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

// Affichage force des erreurs pour le premier chargement
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('LARAVEL_START', microtime(true));

// Configuration CRUCIALE pour Vercel (Dossiers temporaires)
putenv('VIEW_COMPILED_PATH=/tmp');
putenv('SESSION_DRIVER=cookie');
putenv('LOG_CHANNEL=stderr');
putenv('APP_STORAGE=/tmp');
$_ENV['VIEW_COMPILED_PATH'] = '/tmp';

// Register the Composer autoloader...
require __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__ . '/../bootstrap/app.php';

// AUTO-MIGRATION : Crée les tables sur AlwaysData au premier chargement
try {
    // On ne migre que si la table "voyages" n'existe pas
    if (!Schema::hasTable('voyages')) {
        Artisan::call('migrate:fresh', ['--force' => true, '--seed' => true]);
    }
} catch (\Exception $e) {
    // En cas d'erreur de migration, on affiche l'erreur pour débugger
    echo "Info Migration : " . $e->getMessage();
}

$app->handleRequest(Request::capture());
