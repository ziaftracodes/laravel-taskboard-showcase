<?php

// Enable error reporting for debugging serverless deployment exceptions
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Create required storage directories in /tmp for write access on Vercel
if (isset($_SERVER['VERCEL']) || isset($_ENV['VERCEL'])) {
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
}

// Forward all serverless requests to the Laravel public entrypoint
require __DIR__ . '/../public/index.php';
