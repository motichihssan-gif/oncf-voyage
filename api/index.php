<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

// Affichage TOTAL des erreurs pour débloquer le site
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('LARAVEL_START', microtime(true));

// Configuration Vercel (Redirection des dossiers d'écriture)
putenv('VIEW_COMPILED_PATH=/tmp');
putenv('SESSION_DRIVER=cookie');
putenv('LOG_CHANNEL=stderr');
putenv('APP_STORAGE=/tmp');
$_ENV['VIEW_COMPILED_PATH'] = '/tmp';

try {
    // 1. Chargement de l'autoloader
    require __DIR__ . '/../vendor/autoload.php';

    // 2. Démarrage de l'application
    /** @var Application $app */
    $app = require_once __DIR__ . '/../bootstrap/app.php';

    // 3. Gestion de la requête
    $app->handleRequest(Request::capture());

} catch (\Throwable $e) {
    // Capture de TOUTES les erreurs (même les plus graves)
    echo "<h1>Erreur Laravel sur Vercel : Diagnostic</h1>";
    echo "<p><strong>Message :</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Fichier :</strong> " . $e->getFile() . " (Ligne " . $e->getLine() . ")</p>";
    echo "<h3>Détails techniques :</h3>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
