<?php

/**
 * Front controller - All requests go through this file
 */

// Composer autoload (if using composer)
// require_once dirname(__DIR__) . '/vendor/autoload.php';

// PSR-4 Autoloader
spl_autoload_register(function ($class) {
    $root = dirname(__DIR__);
    
    // Convert namespace to path: Core\App -> Core/App.php
    $file = $root . '/' . str_replace('\\', '/', $class) . '.php';
    
    // Convert to lowercase directory for "core" and "app"
    $file = preg_replace_callback('/\/(Core|App)\//', function($matches) {
        return '/' . strtolower($matches[1]) . '/';
    }, $file);
    
    if (file_exists($file)) {
        require_once $file;
    }
});

// Set timezone
date_default_timezone_set('UTC');

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get application instance
$app = \Core\App::getInstance();

// Load routes
require dirname(__DIR__) . '/config/routes.php';

// Run the application
$app->run();
