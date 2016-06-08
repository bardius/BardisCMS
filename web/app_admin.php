<?php

use Symfony\Component\HttpFoundation\Request;

$loader = require __DIR__.'/../app/autoload.php';
require_once __DIR__.'/../app/bootstrap.php.cache';
//require_once __DIR__.'/../app/AppCache.php';

$kernel = new AppKernel('prod', false);
$kernel->loadClassCache();
// wrap the default AppKernel with the AppCache one
//$kernel = new AppCache($kernel);

// When using the HttpCache, you need to call the _method in your front controller instead
// of relying on the configuration parameter
//http://symfony.com/doc/2.8/reference/configuration/framework.html#configuration-framework-http-method-override
Request::enableHttpMethodParameterOverride();

$request = Request::createFromGlobals();

$response = $kernel->handle($request);
$response->send();

$kernel->terminate($request, $response);
