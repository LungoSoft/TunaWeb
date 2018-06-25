<?php
require __DIR__ . '/vendor/autoload.php';

use Phroute\Phroute\RouteCollector;
use Phroute\Phroute\Dispatcher;
use Tuna\Email\SendEmail;

$router = new RouteCollector();

$routes = include getcwd().'/config/routes.php';

foreach($routes['views'] as $key => $route)
{
    switch ($route[0]) {
        case 'get':
            $router->get($key, function() use ($route){
                include getcwd().'/views/'.$route[1];
            });
            break;
        case 'post':
            $router->post($key, function() use ($route){
                include getcwd().'/views/'.$route[1];
            });
            break;
        default:
            echo 'Error no existe '.$key.' como metodo de ruta!!';
            break;
    }
}
foreach($routes['controllers'] as $key => $controller)
{
    switch ($controller[0]) {
        case 'get':
            $router->get($key, ['Tuna\\Http\\Controllers\\'.$controller[1], $controller[2]]);
            break;
        case 'post':
            $router->post($key, ['Tuna\\Http\\Controllers\\'.$controller[1], $controller[2]]);
            break;
        default:
            echo 'Error no existe '.$key.' como metodo de ruta!!';
            break;
    }
}

try
{
    if(file_exists(getcwd().'/config/mail.php'))
        SendEmail::$config = include getcwd().'/config/mail.php';
    else
        SendEmail::$config = include 'config/mail.php';
}
catch(Exception $e)
{
    echo 'Mailer Error: ' . $e->getMessage();
}

try
{
    $uri = explode('?', $_SERVER['REQUEST_URI'])[0];
    $response = (new Dispatcher($router->getData()))->dispatch($_SERVER['REQUEST_METHOD'], $uri);
}
catch (Phroute\Phroute\Exception\HttpRouteNotFoundException $ex)
{
    echo 'Not Found Route';
}
