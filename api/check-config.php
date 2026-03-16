<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Vérification des Fichiers de Config</h1>";

$files = [
    '../config/app.php',
    '../config/database.php',
    '../config/view.php',
    '../.env',
    '../vendor/autoload.php',
    '../bootstrap/app.php'
];

foreach ($files as $file) {
    echo "Fichier : <strong>$file</strong> - ";
    if (file_exists(__DIR__ . '/' . $file)) {
        echo "✅ EXISTE - Permissions : " . substr(sprintf('%o', fileperms(__DIR__ . '/' . $file)), -4) . "<br>";
    } else {
        echo "❌ INTROUVABLE<br>";
    }
}

echo "<h2>Contenu de bootstrap/cache (DOIT ÊTRE VIDE) :</h2>";
$cacheDir = __DIR__ . '/../bootstrap/cache';
if (is_dir($cacheDir)) {
    $cacheFiles = scandir($cacheDir);
    foreach ($cacheFiles as $cf) {
        if ($cf !== '.' && $cf !== '..' && $cf !== '.gitignore') {
            echo "Alerte : Fichier parasite trouvé : <strong>$cf</strong> (À supprimer !)<br>";
        }
    }
}
