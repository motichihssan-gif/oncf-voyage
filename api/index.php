<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// 1. FORCER LE STOCKAGE DANS /TMP (Seul endroit autorisé sur Vercel)
// On définit cela AVANT de charger quoi que ce soit
$storagePath = '/tmp/storage';
if (!is_dir($storagePath . '/framework/views')) {
    @mkdir($storagePath . '/framework/views', 0755, true);
    @mkdir($storagePath . '/framework/cache', 0755, true);
    @mkdir($storagePath . '/framework/sessions', 0755, true);
    @mkdir($storagePath . '/logs', 0755, true);
}

// 2. Variables d'environnement critiques pour Vercel
putenv("APP_STORAGE={$storagePath}");
putenv("VIEW_COMPILED_PATH={$storagePath}/framework/views");
putenv("SESSION_DRIVER=cookie");
putenv("LOG_CHANNEL=stderr");
putenv("CACHE_STORE=array"); // Évite d'écrire du cache sur le disque

// 3. Charger l'autoloader
require __DIR__ . '/../vendor/autoload.php';

// 4. Démarre l'application
/** @var Application $app */
$app = require_once __DIR__ . '/../bootstrap/app.php';

// 5. Forcer les chemins AU COEUR du système
$app->useStoragePath($storagePath);

// 6. Gérer la requête
try {
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $response = $kernel->handle(
        $request = Request::capture()
    );
    $response->send();
    $kernel->terminate($request, $response);
} catch (\Throwable $e) {
    // Si ça plante encore, on affiche l'erreur SANS utiliser Laravel (pour éviter le crash de "view")
    echo "<h1>❌ Erreur Critique de Boot</h1>";
    echo "<p>Ceci est l'erreur réelle qui bloque le site :</p>";
    echo "<pre style='background:#eee;padding:10px;'>" . $e->getMessage() . "</pre>";
    echo "<p>Fichier : " . $e->getFile() . " à la ligne " . $e->getLine() . "</p>";
}
