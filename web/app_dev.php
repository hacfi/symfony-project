<?php

//require_once __DIR__.'/../var/bootstrap.php.cache';
require_once __DIR__.'/../app/autoload.php';
require_once __DIR__.'/../app/AppKernel.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;

Debug::enable();

$kernel = new AppKernel('dev', true);
if (!extension_loaded('xdebug') || (!isset($_REQUEST['XDEBUG_SESSION_START']) && !isset($_COOKIE['XDEBUG_SESSION']) && ini_get('xdebug.remote_autostart') == false)) {
    $kernel->loadClassCache();
}

$request = Request::createFromGlobals();

$response = $kernel->handle($request);
$response->send();

$kernel->terminate($request, $response);
