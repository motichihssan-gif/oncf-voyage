<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

// FORCE DEBUG
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

// Tentative de migration avec logs explicites si ça échoue
try {
    $db = $app->make('db');
    $schema = $db->connection()->getSchemaBuilder();
    
    if (!$schema->hasTable('voyages')) {
        echo "Initialisation de la base de données AlwaysData...<br>";
        \Illuminate\Support\Facades\Artisan::call('migrate:fresh', ['--force' => true, '--seed' => true]);
        echo "Base de données prête ! Redirection...<br>";
        header("Refresh:2");
        exit();
    }
} catch (\Exception $e) {
    echo "<h1>Erreur critique de démarrage</h1>";
    echo "Message : " . $e->getMessage() . "<br>";
    echo "Fichier : " . $e->getFile() . " à la ligne " . $e->getLine() . "<br>";
    exit();
}

$app->handleRequest(Request::capture());
