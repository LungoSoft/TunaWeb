<?php
require __DIR__ . '/vendor/autoload.php';

use Phroute\Phroute\RouteCollector;

$router = new RouteCollector();

$router->get('/', function(){
    echo 'principal';
});

//$resolver = new RouterResolver($_SERVER['REQUEST_URI']);