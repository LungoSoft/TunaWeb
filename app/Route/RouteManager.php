<?php 

namespace Tuna\Route;

use Tuna\Route\RouteFile;
use Tuna\Loader\Loader;

class RouteManager
{
    protected $route, $loader;

    public function __construct(RouteFile $route, Loader $loader)
    {
        $this->route = $route;
        $this->loader = $loader;
    }

    public function load()
    {
        foreach($this->route->getViews() as $key => $route)
        {
            $this->loader->load($route[0], $key, $route[1]);
        }

        foreach($this->route->getControllers() as $key => $controller)
        {
            $this->loader->load($controller[0], $key,[$controller[1], $controller[1]]);
        }

        return true;
    }
}
