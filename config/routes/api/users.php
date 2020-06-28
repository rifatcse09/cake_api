<?php
/**
 * Projects Routes configuration
 *
 */
use Cake\Routing\Router;

Router::scope('/api', ['prefix' => 'api/users'], function($routes) {
  $routes->connect('/users', ['controller' => 'Users', 'action' => 'index']);
  $routes->connect('/add', ['controller' => 'Users', 'action' => 'add'])->setMethods(['POST']);
});
 
