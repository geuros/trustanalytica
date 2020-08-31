<?php

$router = $di->getRouter();

// Define your routes here
// Main page
$router->add('/', ['controller' => 'index', 'action' => 'index']);

// User routes
$router->add('/user/login', ['controller' => 'user', 'action' => 'login']);
$router->add('/user/login/submit', ['controller' => 'user', 'action' => 'loginSubmit']);
$router->add('/user/register', ['controller' => 'user', 'action' => 'register']);
$router->add('/user/register/submit', ['controller' => 'user', 'action' => 'registerSubmit']);

// Route 404
$router->notFound(['controller' => 'index', 'action' => 'route404']);

$router->handle($_SERVER['REQUEST_URI']);
