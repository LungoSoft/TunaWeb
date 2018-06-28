<?php
require __DIR__ . '/vendor/autoload.php';

use Tuna\Kernel\App;
use Tuna\Route\RouteDispatcher;
use Tuna\Email\SendEmail;

App::create(__DIR__);
$uri = explode('?', $_SERVER['REQUEST_URI'])[0];
RouteDispatcher::dispatch($uri);
SendEmail::init(__DIR__);
