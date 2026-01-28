<?php
/**
 * Router script for PHP built-in server
 * Run with: php -S localhost:7000 -t public router.php
 */

// Get the requested URI
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Check if it's a request for static files (js, css, images, etc.)
if (preg_match('/^\/(js|css|img|images|fonts|assets)\//', $uri)) {
    $filePath = __DIR__ . '/public' . $uri;
    if (file_exists($filePath) && is_file($filePath)) {
        return false; // Let PHP serve the static file
    }
}

// Otherwise, route through the application
$_GET['url'] = trim($uri, '/');
require __DIR__ . '/public/index.php';
