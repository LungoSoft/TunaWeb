<?php 

namespace Tuna\Route;

use Tuna\Route\Exceptions\DirIsNorDefinedException;

class RouteFile
{
    protected $configRoutes;

    public function __construct()
    {
        $this->configRoutes = $this->getConfigRouteFile();
    }

    public function getView($key)
    {
        $configViews = $this->getViews();

        if( !isset($configViews[$key]) ) {
            throw new RouteIsNotFoundException("Route for view $key not found");
        }
        
        return $configViews[$key];
    }

    public function getController($key)
    {
        $configControllers = $this->getControllers();

        if( !isset($configControllers[$key]) ) {
            throw new RouteIsNotFoundException("Route for view $key not found");
        }
        
        return $configControllers[$key];
    }

    public function getViews()
    {
        return $this->configRoutes['views'];
    }

    public function getControllers()
    {
        return $this->configRoutes['controllers'];
    }

    protected function getConfigRouteFile()
    {
        if( !\Tuna\Kernel\App::$dir )
            throw new DirIsNorDefinedException('Base directory where your application located is not defined, define \Tuna\Kernel\App::$dir');

        return include \Tuna\Kernel\App::$dir.'/'.\Tuna\Kernel\App::$subdomain.'/config/routes.php';
    }
}