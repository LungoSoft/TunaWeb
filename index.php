<?php
require __DIR__ . '/vendor/autoload.php';

use Phroute\Phroute\RouteCollector;
use Phroute\Phroute\Dispatcher;
use Tuna\Email\SendEmail;

$router = new RouteCollector();

$routes = include 'config/routes.php';

foreach($routes as $key => $route)
{
    $router->get($key, function() use ($route){
        include 'views/'.$route;
    });
}

try
{
    SendEmail::$config = include 'config/mail.php';
}
catch(Exception $e)
{
    echo 'Mailer Error: ' . $e->getMessage();
}

try
{
    $response = (new Dispatcher($router->getData()))->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
}
catch (Phroute\Phroute\Exception\HttpRouteNotFoundException $ex)
{
    echo 'Not Found Route';
}
