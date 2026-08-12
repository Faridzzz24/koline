<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Fail-safe static asset handler for Vercel Serverless
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH));
$publicFile = __DIR__ . '/../public' . $uri;

if ($uri !== '/' && !empty($uri) && file_exists($publicFile) && !is_dir($publicFile)) {
    $ext = strtolower(pathinfo($publicFile, PATHINFO_EXTENSION));
    $mimeTypes = [
        'css'   => 'text/css; charset=utf-8',
        'js'    => 'application/javascript; charset=utf-8',
        'svg'   => 'image/svg+xml',
        'ico'   => 'image/x-icon',
        'png'   => 'image/png',
        'jpg'   => 'image/jpeg',
        'jpeg'  => 'image/jpeg',
        'webp'  => 'image/webp',
        'woff'  => 'font/woff',
        'woff2' => 'font/woff2',
    ];
    if (isset($mimeTypes[$ext])) {
        header('Content-Type: ' . $mimeTypes[$ext]);
    }
    readfile($publicFile);
    exit;
}

$tmpDir = '/tmp';
$tmpDb = $tmpDir . '/database.sqlite';

// 1. Setup storage directories inside /tmp
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

// 2. Prepare SQLite database in /tmp (ALWAYS target /tmp/database.sqlite)
$dbSource = __DIR__ . '/../database/database.sqlite';

if (!file_exists($tmpDb) || filesize($tmpDb) < 1000) {
    if (file_exists($dbSource) && filesize($dbSource) > 1000) {
        @copy($dbSource, $tmpDb);
    } else {
        @file_put_contents($tmpDb, '');
    }
}

// 3. Force environment variables to ALWAYS point to /tmp/database.sqlite
putenv("DB_DATABASE={$tmpDb}");
$_ENV['DB_DATABASE'] = $tmpDb;
$_SERVER['DB_DATABASE'] = $tmpDb;

putenv("SESSION_DRIVER=cookie");
$_ENV['SESSION_DRIVER'] = 'cookie';
$_SERVER['SESSION_DRIVER'] = 'cookie';

$_ENV['APP_CONFIG_CACHE'] = $tmpDir . '/config.php';
$_ENV['APP_EVENTS_CACHE'] = $tmpDir . '/events.php';
$_ENV['APP_PACKAGES_CACHE'] = $tmpDir . '/packages.php';
$_ENV['APP_ROUTES_CACHE'] = $tmpDir . '/routes.php';
$_ENV['APP_SERVICES_CACHE'] = $tmpDir . '/services.php';
$_ENV['VIEW_COMPILED_PATH'] = $tmpDir . '/storage/framework/views';

// Fix Vercel Serverless Script Name & URI mapping for Laravel routing
$_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/../public/index.php';
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['PHP_SELF'] = '/index.php';

// Force HTTPS protocol behind Vercel reverse proxy
$_SERVER['HTTPS'] = 'on';
$_SERVER['SERVER_PORT'] = 443;
$_SERVER['REQUEST_SCHEME'] = 'https';

// 4. Bootstrap Laravel Application
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

// 5. Register booted hook to override database config in memory before any query executes
$app->booted(function ($app) use ($tmpDb) {
    config(['database.connections.sqlite.database' => $tmpDb]);
    DB::purge('sqlite');

    // Auto-migrate if tables are missing
    try {
        if (!Schema::hasTable('specializations') || !Schema::hasTable('users')) {
            Artisan::call('migrate', [
                '--force' => true,
            ]);
        }
    } catch (\Throwable $e) {}
});

// 6. Handle HTTP Request
$kernel = $app->make(Kernel::class);
$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);

// 7. Sync database changes back to database/database.sqlite if writable
if (file_exists($tmpDb) && (is_writable($dbSource) || (!file_exists($dbSource) && is_writable(dirname($dbSource))))) {
    @copy($tmpDb, $dbSource);
}
