<?php

/**
 * Define application routes
 */

$router = \Core\App::getInstance()->getRouter();

// Static file routes (must be first to handle assets)
$router->add('assets/{file:.+}', ['controller' => 'StaticManager', 'action' => 'serve']);
$router->add('css/{file:.+}', ['controller' => 'StaticManager', 'action' => 'serve']);
$router->add('js/{file:.+}', ['controller' => 'StaticManager', 'action' => 'serve']);

// Installation routes
$router->add('install', ['controller' => 'Install', 'action' => 'index']);
$router->add('install/requirements', ['controller' => 'Install', 'action' => 'requirements']);
$router->add('install/database', ['controller' => 'Install', 'action' => 'database']);
$router->add('install/test-connection', ['controller' => 'Install', 'action' => 'testConnection']);
$router->add('install/migrate', ['controller' => 'Install', 'action' => 'migrate']);
$router->add('install/complete', ['controller' => 'Install', 'action' => 'complete']);

// Authentication routes
$router->add('login', ['controller' => 'Auth', 'action' => 'login']);
$router->add('auth/authenticate', ['controller' => 'Auth', 'action' => 'authenticate']);
$router->add('logout', ['controller' => 'Auth', 'action' => 'logout']);

// User profile route
$router->add('profile', ['controller' => 'Profile', 'action' => 'index']);



// Dashboard routes
$router->add('dashboard', ['controller' => 'Dashboard', 'action' => 'index']);
$router->add('dashboard/create-thread', ['controller' => 'Dashboard', 'action' => 'createThread']);
$router->add('dashboard/store-thread', ['controller' => 'Dashboard', 'action' => 'storeThread']);
$router->add('dashboard/threads', ['controller' => 'Dashboard', 'action' => 'threads']);
$router->add('dashboard/thread/{id:\d+}', ['controller' => 'Dashboard', 'action' => 'thread']);

// Ajax routes
$router->add('ajax/get-threads', ['controller' => 'Ajax', 'action' => 'getThreads']);
$router->add('ajax/get-thread', ['controller' => 'Ajax', 'action' => 'getThread']);
$router->add('ajax/get-groups', ['controller' => 'Ajax', 'action' => 'getGroups']);
$router->add('ajax/increment-view', ['controller' => 'Ajax', 'action' => 'incrementView']);
$router->add('ajax/search', ['controller' => 'Ajax', 'action' => 'search']);

// Default route - Home page
$router->add('', ['controller' => 'Home', 'action' => 'index']);

// About page
$router->add('about', ['controller' => 'Home', 'action' => 'about']);

// Standard routes with controller and action
$router->add('{controller}/{action}');