<?php 

namespace Tuna\Loader;

use Tuna\Loader\Exceptions\ViewNotFoundException;
use Tuna\Loader\Exceptions\ControllerNotFoundException;
use Tuna\Route\Exceptions\RouteIsNotFoundException;
use Phroute\Phroute\RouteCollector;

class Loader
{
    protected $router;

    public function __construct(RouteCollector $router)
    {
        $this->router = $router;
    }

    public function load($method, $route, $resource)
    {
        $method = strtolower($method);
        if($method != 'get' && $method != 'post')
            throw new RouteIsNotFoundException("Method $method is not valid, just post and get");
        
        if(is_string($resource)) {
            $resource = $this->getView($resource);
            if( !file_exists($resource) )
                throw new ViewNotFoundException("View $resource not found");

            $this->router->$method($route, function() use ($resource){
                include $resource;
            });
        }
        
        if(is_array($resource)) {
            if( !file_exists(\Tuna\Kernel\App::$dir.'/app/Http/Controllers/'.$resource[0].'.php') )
                throw new ControllerNotFoundException("Controller {$resource[0]}::{$resource[1]} not found");

            $resource = $this->getController($resource);
            $this->router->$method($route, $resource);
        }

        return true;
    }

    private function getView($view)
    {
        return \Tuna\Kernel\App::$dir.'/views/'.$view;
    }

    private function getController($controller)
    {
        return ['Tuna\\Http\\Controllers\\'.$controller[0], $controller[1]];
    }
}