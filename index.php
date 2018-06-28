<?php
require __DIR__ . '/vendor/autoload.php';

use Tuna\Kernel\App;
use Tuna\Route\RouteDispatcher;
use Tuna\Email\SendEmail;

\Tuna\Kernel\App::$subdomain = $subdomain ? $subdomain : '';
App::create(__DIR__);
SendEmail::init(__DIR__);
$uri = explode('?', $_SERVER['REQUEST_URI'])[0];
RouteDispatcher::dispatch($uri);
