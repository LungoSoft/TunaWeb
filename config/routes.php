<?php

use Phroute\Phroute\RouteCollector;

$router = new RouteCollector();

$router->get('/', function(){
    echo 'principal';
});
//$router->post($route, $handler);   # match only post requests
//$router->delete($route, $handler); # match only delete requests
//$router->any($route, $handler);    # match any request method
