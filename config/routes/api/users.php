<?php
/**
 * Projects Routes configuration
 *
 */
use Cake\Routing\Router;

Router::scope('/api', ['prefix' => 'api/users'], function($routes) {
  $routes->connect('/users', ['controller' => 'Users', 'action' => 'index']);
  $routes->connect('/', ['controller' => 'Users', 'action' => 'login']);
  $routes->connect('/users/register', ['controller' => 'Users', 'action' => 'add'])->setMethods(['POST']);
  $routes->connect('/users/:id', ['controller' => 'Users', 'action' => 'edit'])->setMethods(['POST'])->setPatterns(['id' => '\d+'])->setPass(['id']);
  $routes->connect('/users/logout', ['controller' => 'Users', 'action' => 'logout'])->setMethods(['GET']);
});
 
