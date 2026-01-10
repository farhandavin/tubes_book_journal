<?php

// Set storage path to /tmp for Vercel serverless
$_ENV['APP_STORAGE'] = '/tmp';
putenv('APP_STORAGE=/tmp');

// Ensure storage directories exist in /tmp
$storageDirs = [
    '/tmp/framework',
    '/tmp/framework/views',
    '/tmp/framework/cache',
    '/tmp/framework/sessions',
    '/tmp/logs',
];

foreach ($storageDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

require __DIR__ . '/../public/index.php';