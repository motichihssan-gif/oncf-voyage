<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// 1. Diagnostics (Affichage forcé)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. Préparer le stockage temporaire pour Vercel
$storagePath = '/tmp/storage';
foreach (['/framework/views', '/framework/cache', '/framework/sessions', '/logs'] as $dir) {
    if (!is_dir($storagePath . $dir)) {
        @mkdir($storagePath . $dir, 0755, true);
    }
}

// 3. Forcer Laravel à ignorer tout cache de configuration Windows
// On définit les variables système pour écraser les fichiers de cache
putenv("APP_STORAGE={$storagePath}");
putenv("VIEW_COMPILED_PATH={$storagePath}/framework/views");
putenv("SESSION_DRIVER=cookie");

try {
    require __DIR__ . '/../vendor/autoload.php';

    /** @var Application $app */
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    
    // Forcer le dossier de stockage
    $app->useStoragePath($storagePath);

    // DÉSACTIVER LES CACHES DE CONFIGURATION (Crucial pour Vercel)
    $app->forgetBufferedState();

    $app->handleRequest(Request::capture());

} catch (\Throwable $e) {
    // Si Laravel plante, on affiche l'ERREUR RÉELLE sans passer par le Handler de Laravel
    header('Content-Type: text/html; charset=utf-8');
    echo "<h1>❌ Erreur Détectée (Bypass Laravel)</h1>";
    echo "<p><strong>Message :</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>Fichier :</strong> " . $e->getFile() . " à la ligne " . $e->getLine() . "</p>";
    echo "<h3>Détails :</h3><pre>" . $e->getTraceAsString() . "</pre>";
}
