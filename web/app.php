<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;

$env = getenv('APP_ENVIRONMENT') ?: 'prod';
$debug = getenv('APP_DEBUG') ? (bool) getenv('APP_DEBUG') : $env === 'dev';
$xdebug = extension_loaded('xdebug') && (isset($_REQUEST['XDEBUG_SESSION_START']) || isset($_COOKIE['XDEBUG_SESSION']));

if ($xdebug) {
    require_once __DIR__.'/../app/autoload.php';
} else {
    require_once __DIR__.'/../var/bootstrap.php.cache';
}

require_once __DIR__.'/../app/AppKernel.php';

if ($debug) {
    Debug::enable();
}

$kernel = new AppKernel($env, $debug);
if (!$xdebug) {
    $kernel->loadClassCache();
}

//require_once __DIR__.'/../app/AppCache.php';
//$kernel = new AppCache($kernel);

Request::enableHttpMethodParameterOverride();
$request = Request::createFromGlobals();

$response = $kernel->handle($request);
$response->send();

$kernel->terminate($request, $response);
