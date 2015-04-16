<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;

defined('SYMFONY_ENV') || define('SYMFONY_ENV', getenv('SYMFONY_ENV') ?: 'prod');
defined('SYMFONY_DEBUG') || define('SYMFONY_DEBUG', filter_var(getenv('SYMFONY_DEBUG') ?: SYMFONY_ENV === 'dev', FILTER_VALIDATE_BOOLEAN));

if (SYMFONY_DEBUG) {
    require_once __DIR__.'/../app/autoload.php';
} else {
    require_once __DIR__.'/../var/bootstrap.php.cache';
}

require_once __DIR__.'/../app/AppKernel.php';
//require_once __DIR__.'/../app/AppCache.php';

if (SYMFONY_DEBUG) {
    Debug::enable();
}

$kernel = new AppKernel(SYMFONY_ENV, SYMFONY_DEBUG);
if (!SYMFONY_DEBUG
    || !extension_loaded('xdebug')
    || (!isset($_REQUEST['XDEBUG_SESSION_START']) && !isset($_COOKIE['XDEBUG_SESSION']) && ini_get('xdebug.remote_autostart') == false))
{
    $kernel->loadClassCache();
}

$request = Request::createFromGlobals();

$response = $kernel->handle($request);
$response->send();

$kernel->terminate($request, $response);
