<?php

namespace App\Controllers;

use Core\Controller;

/**
 * Static controller - handles serving static files
 */
class StaticServe extends Controller
{
    /**
     * Serve static files
     */
    public function serveAction()
    {
        // Get the file path from route params
        $file = $this->route_params['file'] ?? '';
        
        // Determine the base directory from the request URI
        $requestUri = $_SERVER['REQUEST_URI'];
        $requestUri = parse_url($requestUri, PHP_URL_PATH);
        
        // Extract the full path (assets/*, css/*, or js/*)
        if (preg_match('#^/(assets|css|js)/(.+)$#', $requestUri, $matches)) {
            $directory = $matches[1];
            $filePath = $matches[2];
            $fullPath = $directory . '/' . $filePath;
        } else {
            $fullPath = $file;
        }
        
        // Use the Static class to serve the file
        \Core\StaticManager::serve($fullPath);
    }
}
