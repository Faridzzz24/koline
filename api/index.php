<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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

// Prepare SQLite database path in /tmp
$dbSource = dirname(__DIR__) . '/database/database.sqlite';
$dbTarget = $tmpDir . '/database.sqlite';

if (!file_exists($dbTarget) || filesize($dbTarget) < 10000) {
    if (file_exists($dbSource) && filesize($dbSource) > 10000) {
        @copy($dbSource, $dbTarget);
    } else {
        @file_put_contents($dbTarget, '');
    }
}

// Override environment variables for Vercel Serverless runtime
putenv("DB_DATABASE={$dbTarget}");
$_ENV['DB_DATABASE'] = $dbTarget;
$_SERVER['DB_DATABASE'] = $dbTarget;

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

// Bootstrap Laravel Application
require dirname(__DIR__) . '/vendor/autoload.php';
$app = require_once dirname(__DIR__) . '/bootstrap/app.php';

// Explicitly set database path in Laravel config repository and purge connection cache
config(['database.connections.sqlite.database' => $dbTarget]);
DB::purge('sqlite');

// Check and auto-migrate if specializations or users table is missing
try {
    if (!Schema::hasTable('specializations') || !Schema::hasTable('users')) {
        Artisan::call('migrate:fresh', [
            '--seed' => true,
            '--force' => true,
        ]);
    }
} catch (\Throwable $e) {
    try {
        Artisan::call('migrate:fresh', [
            '--seed' => true,
            '--force' => true,
        ]);
    } catch (\Throwable $ex) {}
}

// Handle HTTP Request
$kernel = $app->make(Kernel::class);
$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
