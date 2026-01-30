<?php

namespace Core;

use Core\App; 

class Router
{
    protected $routes = [];
    protected $params = [];

    protected $currentLanguage = "en";


    protected $supportedLanguages = ['en', 'it'];

    /**
     * Add a route to the routing table
     */
    public function add($route, $params = [])
    {
        // Convert the route to a regular expression
        $route = preg_replace('/\//', '\\/', $route);
        $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $route);
        $route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);
        $route = '/^' . $route . '$/i';

        $this->routes[$route] = $params;
    }

    /**
     * Get all routes
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    public function setLanguage($lang) {
        if (in_array($lang, $this->supportedLanguages)) {
            $this->currentLanguage = $lang;
            $_SESSION['lang'] = $lang;
        }
    }

    public function getLanguage() {
        if (isset($_SESSION['lang']) && in_array($_SESSION['lang'], $this->supportedLanguages)) {
            $this->currentLanguage = $_SESSION['lang'];
        }
        return $this->currentLanguage;
    }
    private function removeLangFromRoute($url) {
        $parts = explode('/', $url);
        //App::beautyPrint($parts);
        if (count($parts) > 1 && in_array($parts[0], $this->supportedLanguages)) {
            $lang = $parts[0];
            $this->setLanguage($lang);
            array_splice($parts, 0, 1); // Remove the language part
            $url = implode('/', $parts);
        }
        else if (in_array($url, $this->supportedLanguages)) {
            $lang = $url;
            $this->setLanguage($lang);
            $url = '';
        }
        return $url;
    }


    private function applyLangToRoute($url) {
        $lang = $this->getLanguage();
        //App::beautyPrint($_SESSION);
        //App::beautyPrint($lang);
        //exit(0);

        if ($lang) {
            $url = "/{$lang}/{$url}";
            // Redirect if the URL does not start with the language
            if (strpos($_SERVER['REQUEST_URI'], "/{$lang}") !== 0) {
            header('HTTP/1.1 301 Moved Permanently');
            header("Location: {$url}");
            exit;
            }
        }
        return $url;
    }

    /**
     * Match the route to the routes in the routing table
     */
    public function match($url)
    {

        $url = $this->removeLangFromRoute($url);

        // App::beautyPrint($url);
        // exit(0);

        foreach ($this->routes as $route => $params) {            
            if (preg_match($route, $url, $matches)) {
                foreach ($matches as $key => $match) {
                    if (\is_string($key)) {
                        $params[$key] = $match;
                    }
                }
                $this->params = $params;
        
                $this->applyLangToRoute($url);
                return true;
            }
        }
        return false;
    }

    /**
     * Get the matched parameters
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Dispatch the route
     */
    public function dispatch($url)
    {
        $url = $this->removeQueryStringVariables($url);

        if ($this->match($url)) {
            $controller = $this->params['controller'];

            $controller = $this->convertToStudlyCaps($controller);
            $controller = $this->getNamespace() . $controller;

            if (class_exists($controller)) {
                $controller_object = new $controller($this->params);
                
                $action = $this->params['action'];
                $action = $this->convertToCamelCase($action);

                if (is_callable([$controller_object, $action])) {
                    $controller_object->$action();
                } else {
                    throw new \Exception("Method $action not found in controller $controller");
                }
            } else {
                throw new \Exception("Controller class $controller not found");
            }
        } else {
            http_response_code(404);
            require dirname(__DIR__) . '/app/views/404.html';
            exit;
        }
    }

    /**
     * Convert string to StudlyCaps
     */
    protected function convertToStudlyCaps($string)
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }

    /**
     * Convert string to camelCase
     */
    protected function convertToCamelCase($string)
    {
        return lcfirst($this->convertToStudlyCaps($string));
    }

    /**
     * Remove query string variables from URL
     */
    protected function removeQueryStringVariables($url)
    {
        if ($url != '') {
            $parts = explode('&', $url, 2);
            if (strpos($parts[0], '=') === false) {
                $url = $parts[0];
            } else {
                $url = '';
            }
        }
        return $url;
    }

    /**
     * Get the namespace for the controller class
     */
    protected function getNamespace()
    {
        $namespace = 'App\Controllers\\';
        if (array_key_exists('namespace', $this->params)) {
            $namespace .= $this->params['namespace'] . '\\';
        }
        return $namespace;
    }
}
