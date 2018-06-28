<?php

namespace Tuna\Route;
use Phroute\Phroute\Dispatcher;

class RouteDispatcher
{
    public static function dispatch($uri)
    {
        try
        {
            $dispatcher = new Dispatcher(\Tuna\Kernel\App::$routes->getData());
            $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], $uri);
        }
        catch (\Phroute\Phroute\Exception\HttpRouteNotFoundException $ex)
        {
            http_response_code(404);
            if( file_exists(\Tuna\Kernel\App::$dir.'/views/404.html') )
                include \Tuna\Kernel\App::$dir.'/views/404.html';
        }
    }
}