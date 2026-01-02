<?php

namespace Core;

class App
{
    /**
     * Application instance
     */
    private static $instance = null;

    /**
     * Router instance
     */
    private $router;

    /**
     * Configuration array
     */
    private $config = [];

    /**
     * Private constructor to prevent direct instantiation
     */
    private function __construct()
    {
        $this->router = new Router();
        $this->loadConfig();
        $this->setupErrorHandling();
    }

    /**
     * Get singleton instance
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Load configuration files
     */
    private function loadConfig()
    {
        $this->config = require dirname(__DIR__) . '/config/app.php';
    }

    /**
     * Setup error and exception handling
     */
    private function setupErrorHandling()
    {
        error_reporting(E_ALL);
        set_error_handler('Core\Error::errorHandler');
        set_exception_handler('Core\Error::exceptionHandler');
    }

    /**
     * Get router instance
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * Get config value
     */
    public function getConfig($key, $default = null)
    {
        return $this->config[$key] ?? $default;
    }

    /**
     * Run the application
     */
    public function run()
    {
        $url = $_GET['url'] ?? '';
        $this->router->dispatch($url);
    }
}
