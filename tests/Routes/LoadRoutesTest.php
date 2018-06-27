<?php 

use PHPUnit\Framework\TestCase;
use Tuna\Route\RouteFile;
use Tuna\Route\RouteManager;
use Tuna\Loader\Loader;
use Phroute\Phroute\RouteCollector;

class LoadRoutesTest extends TestCase
{
    public function testRoutesAreNotLoadedBecauseDirIsNotDefined()
    {
        $this->expectException(Tuna\Route\Exceptions\DirIsNorDefinedException::class);
        $route = new RouteFile();
    }

    public function testRoutesAreLoadedCorrectly()
    {
        $dir = __DIR__.'/../../';
        \Tuna\Kernel\App::$dir = $dir;
        $route = new RouteFile();

        $this->assertArrayHasKey('/', $route->getViews());
        $this->assertArrayHasKey('/sendemail', $route->getControllers());

        $this->assertArraySubset(['get', 'principal.html'], $route->getView('/'));
        $this->assertArraySubset(['post', 'SendEmailController', 'postIndex'], $route->getController('/sendemail'));
    }

    public function testRouteManagerLoadRoutes()
    {
        $dir = __DIR__.'/../../';
        \Tuna\Kernel\App::$dir = $dir;
        $routeFile = new RouteFile();
        $routes = new RouteCollector();
        $loader = new Loader($routes);
        $routeManager = new RouteManager($routeFile, $loader);

        $result = $routeManager->load();
        $this->assertTrue($result);
    }
}
