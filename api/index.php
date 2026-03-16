<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Configuration indispensable pour Vercel
putenv('VIEW_COMPILED_PATH=/tmp');
putenv('SESSION_DRIVER=cookie');
putenv('LOG_CHANNEL=stderr');
putenv('APP_STORAGE=/tmp');
$_ENV['VIEW_COMPILED_PATH'] = '/tmp';

// Charge l'autoloader
require __DIR__ . '/../vendor/autoload.php';

// Démarre l'application
/** @var Application $app */
$app = require_once __DIR__ . '/../bootstrap/app.php';

// On laisse Laravel gérer la requête normalement
$app->handleRequest(Request::capture());
