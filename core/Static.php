<?php

namespace Core;

/**
 * Static file server
 * Handles serving static assets (images, CSS, JS, etc.)
 */
class StaticManager
{
    /**
     * Serve a static file
     * 
     * @param string $filePath Path to the file relative to public directory
     * @return void
     */
    public static function serve($filePath)
    {
        // Get the public directory path
        $publicDir = dirname(__DIR__) . '/public';
        
        // Construct full file path
        $fullPath = $publicDir . '/' . ltrim($filePath, '/');
        
        // Security check: Ensure the file is within public directory
        $realPath = realpath($fullPath);
        $realPublicDir = realpath($publicDir);
        
        if ($realPath === false || strpos($realPath, $realPublicDir) !== 0) {
            self::sendNotFound();
            return;
        }
        
        // Check if file exists
        if (!file_exists($realPath) || !is_file($realPath)) {
            self::sendNotFound();
            return;
        }
        
        // Get MIME type
        $mimeType = self::getMimeType($realPath);
        
        // Set headers
        header('Content-Type: ' . $mimeType);
        header('Content-Length: ' . filesize($realPath));
        header('Cache-Control: public, max-age=31536000'); // Cache for 1 year
        
        // Output the file
        readfile($realPath);
        exit;
    }
    
    /**
     * Get MIME type for a file
     * 
     * @param string $filePath Full path to the file
     * @return string MIME type
     */
    protected static function getMimeType($filePath)
    {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        
        $mimeTypes = [
            // Images
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png'  => 'image/png',
            'gif'  => 'image/gif',
            'webp' => 'image/webp',
            'svg'  => 'image/svg+xml',
            'ico'  => 'image/x-icon',
            
            // CSS & JS
            'css'  => 'text/css',
            'js'   => 'application/javascript',
            'json' => 'application/json',
            
            // Fonts
            'woff'  => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf'   => 'font/ttf',
            'otf'   => 'font/otf',
            'eot'   => 'application/vnd.ms-fontobject',
            
            // Other
            'pdf'  => 'application/pdf',
            'txt'  => 'text/plain',
            'xml'  => 'application/xml',
        ];
        
        return $mimeTypes[$extension] ?? 'application/octet-stream';
    }
    
    /**
     * Send 404 Not Found response
     * 
     * @return void
     */
    protected static function sendNotFound()
    {
        header('HTTP/1.0 404 Not Found');
        echo '404 - File Not Found';
        exit;
    }
}
