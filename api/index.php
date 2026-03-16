<?php

// 1. Diagnostics d'urgence absolus
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('LARAVEL_START', microtime(true));

// 2. Préparer le dossier temporaire pour Vercel (indispensable)
$storagePath = '/tmp/storage';
foreach (['/framework/views', '/framework/cache', '/framework/sessions', '/logs'] as $dir) {
    if (!is_dir($storagePath . $dir)) {
        @mkdir($storagePath . $dir, 0755, true);
    }
}

// 3. Forcer les chemins
putenv("APP_STORAGE={$storagePath}");
putenv("VIEW_COMPILED_PATH={$storagePath}/framework/views");

try {
    // 4. Charger l'autoloader
    if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
        throw new Exception("Dossier vendor introuvable. Problème d'installation sur Vercel.");
    }
    require __DIR__ . '/../vendor/autoload.php';

    // 5. Charger l'application
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $app->useStoragePath($storagePath);

    // 6. Gérer la requête
    $app->handleRequest(\Illuminate\Http\Request::capture());

} catch (\Throwable $e) {
    // CAPTURE DE L'ERREUR CRITIQUE
    http_response_code(500);
    echo "<h1>❌ Erreur Détectée sur Vercel</h1>";
    echo "<p><strong>Message :</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>Fichier :</strong> " . $e->getFile() . " à la ligne " . $e->getLine() . "</p>";
    echo "<h3>Détails techniques (Stack Trace) :</h3>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
    exit;
}
