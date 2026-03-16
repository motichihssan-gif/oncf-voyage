<?php

// Affichage force des erreurs pour le debug sur Vercel
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Rediriger les dossiers de cache vers /tmp (obligatoire sur Vercel)
putenv('VIEW_COMPILED_PATH=/tmp');
putenv('SESSION_DRIVER=cookie');
putenv('LOG_CHANNEL=stderr');

// Forward Vercel requests to normal index.php
require __DIR__ . '/../public/index.php';
