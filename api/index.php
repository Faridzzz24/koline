<?php

// Ensure /tmp directory has storage subfolders and sqlite database for Vercel Serverless environment
$tmpDir = '/tmp';

// Setup storage directories inside /tmp
$storageDirs = [
    $tmpDir . '/storage/framework/views',
    $tmpDir . '/storage/framework/sessions',
    $tmpDir . '/storage/framework/cache',
    $tmpDir . '/storage/logs',
];

foreach ($storageDirs as $dir) {
    if (!file_exists($dir)) {
        @mkdir($dir, 0777, true);
    }
}

// Copy seeded SQLite database to /tmp if not already present
$dbSource = dirname(__DIR__) . '/database/database.sqlite';
$dbTarget = $tmpDir . '/database.sqlite';

if (!file_exists($dbTarget) || filesize($dbTarget) === 0) {
    if (file_exists($dbSource) && filesize($dbSource) > 0) {
        @copy($dbSource, $dbTarget);
    } else {
        @touch($dbTarget);
    }
}

// Override environment variables for Vercel Serverless runtime
$activeDb = file_exists($dbTarget) ? $dbTarget : $dbSource;
putenv("DB_DATABASE={$activeDb}");
$_ENV['DB_DATABASE'] = $activeDb;
$_SERVER['DB_DATABASE'] = $activeDb;

$_ENV['APP_CONFIG_CACHE'] = $tmpDir . '/config.php';
$_ENV['APP_EVENTS_CACHE'] = $tmpDir . '/events.php';
$_ENV['APP_PACKAGES_CACHE'] = $tmpDir . '/packages.php';
$_ENV['APP_ROUTES_CACHE'] = $tmpDir . '/routes.php';
$_ENV['APP_SERVICES_CACHE'] = $tmpDir . '/services.php';
$_ENV['VIEW_COMPILED_PATH'] = $tmpDir . '/storage/framework/views';

// Fix Vercel Serverless Script Name & URI mapping for Laravel routing
$_SERVER['SCRIPT_FILENAME'] = dirname(__DIR__) . '/public/index.php';
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['PHP_SELF'] = '/index.php';

// Forward request to Laravel entrypoint
require dirname(__DIR__) . '/public/index.php';
