<?php

namespace Tuna\Kernel;

use Tuna\Email\SendEmail;
use Tuna\Route\RouteFile;
use Tuna\Route\RouteManager;
use Tuna\Loader\Loader;
use Phroute\Phroute\RouteCollector;

class App
{
    public static $dir;
    public static $routes;

    public static function create($dir)
    {
        //change dir project
        \Tuna\Kernel\App::$dir = $dir;

        //load enviroment variables
        $dotenv = new \Dotenv\Dotenv($dir);
        $dotenv->load();

        //load dependencies
        $routeFile = new RouteFile();
        static::$routes = new RouteCollector();
        $loader = new Loader(static::$routes);
        $routeManager = new RouteManager($routeFile, $loader);

        $result = $routeManager->load();
    }
}