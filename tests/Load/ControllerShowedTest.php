<?php 

use PHPUnit\Framework\TestCase;
use Phroute\Phroute\RouteCollector;
use Tuna\Loader\Loader;
use Tuna\Loader\Exceptions\ControllerNotFoundException;
use Tuna\Route\Exceptions\RouteIsNotFoundException;

class ControllerShowedTest extends TestCase
{
    public function testControllerIsNotLoad()
    {
        $this->expectException(ControllerNotFoundException::class);

        $dir = __DIR__.'/../../';
        \Tuna\Kernel\App::$dir = $dir;
        $router = new RouteCollector();
        $loader = new Loader($router);
        $result = $loader->load('get', '/sendemail', ['OtherController', 'index']);
    }

    public function testControllerIsLoad()
    {
        $dir = __DIR__.'/../../';
        \Tuna\Kernel\App::$dir = $dir;
        $router = new RouteCollector();
        $loader = new Loader($router);
        $result = $loader->load('get', '/sendemail', ['SendEmailController', 'postIndex']);

        $this->assertTrue($result);
    }
}