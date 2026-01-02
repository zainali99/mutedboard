<?php

/**
 * Define application routes
 */

$router = \Core\App::getInstance()->getRouter();

// Default route - Home page
$router->add('', ['controller' => 'Home', 'action' => 'index']);

// About page
$router->add('about', ['controller' => 'Home', 'action' => 'about']);

// Standard routes with controller and action
$router->add('{controller}/{action}');

// Route with parameters
$router->add('{controller}/{action}/{id:\d+}');
