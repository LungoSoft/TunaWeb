<?php 

use PHPUnit\Framework\TestCase;
use Phroute\Phroute\RouteCollector;
use Tuna\Loader\Loader;
use Tuna\Loader\Exceptions\ViewNotFoundException;
use Tuna\Route\Exceptions\RouteIsNotFoundException;

class ViewShowedTest extends TestCase
{
    public function testRouteFails()
    {
        $this->expectException(RouteIsNotFoundException::class);

        $dir = __DIR__.'/../../';
        \Tuna\Kernel\App::$dir = $dir;
        $router = new RouteCollector();
        $loader = new Loader($router);
        $loader->load('other', '/', 'principal.html');
    }

    public function testViewIsNotLoad()
    {
        $this->expectException(ViewNotFoundException::class);

        $dir = __DIR__.'/../../';
        \Tuna\Kernel\App::$dir = $dir;
        $router = new RouteCollector();
        $loader = new Loader($router);
        $result = $loader->load('get', '/', 'otra.html');
    }

    public function testViewIsLoad()
    {
        $dir = __DIR__.'/../../';
        \Tuna\Kernel\App::$dir = $dir;
        $router = new RouteCollector();
        $loader = new Loader($router);
        $result = $loader->load('get', '/', 'principal.html');

        $this->assertTrue($result);
    }
}