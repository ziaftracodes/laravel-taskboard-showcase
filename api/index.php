<?php

// Enable error reporting for debugging serverless deployment exceptions
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Detect Vercel serverless environment
if (getenv('VERCEL') || isset($_SERVER['VERCEL'])) {
    // 1. Force Vercel env variables
    putenv('SESSION_DRIVER=cookie'); // Store sessions in cookies to prevent DB write lockouts
    putenv('LOG_CHANNEL=stderr');    // Send logs to Vercel output log instead of files
    putenv('CACHE_STORE=array');     // Use array cache

    // 2. Create required temp directories
    $directories = [
        '/tmp/storage/app',
        '/tmp/storage/framework/cache/data',
        '/tmp/storage/framework/sessions',
        '/tmp/storage/framework/views',
        '/tmp/storage/logs',
    ];
    foreach ($directories as $directory) {
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
    }

    // 3. Move SQLite database to writable /tmp directory
    $sourceDb = __DIR__ . '/../database/database.sqlite';
    $targetDb = '/tmp/database.sqlite';
    
    if (file_exists($sourceDb) && !file_exists($targetDb)) {
        copy($sourceDb, $targetDb);
        chmod($targetDb, 0666);
    }
    
    putenv("DB_DATABASE={$targetDb}");
}

// Forward all serverless requests to the Laravel public entrypoint
require __DIR__ . '/../public/index.php';
