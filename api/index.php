<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

// Affichage force des erreurs pour comprendre le 500
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

$app->handleRequest(Request::capture());
