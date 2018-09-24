<?php

use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\HttpKernel\Controller\{ControllerResolver, ArgumentResolver};

require_once __DIR__."/../vendor/autoload.php";

$routes = require_once __DIR__."/../routes.php";
$container = require_once __DIR__."/../container.php";
$config = require_once __DIR__."/../config.php";

$request = Request::createFromGlobals();

$context = $container->get("context");
$context->fromRequest($request);
$matcher = new UrlMatcher($routes, $context);

$controllerResolver = new ControllerResolver();
$argumentResolver = new ArgumentResolver();

try {
    $request->attributes->add($matcher->match($request->getPathInfo()));

    $controller = $controllerResolver->getController($request);
    $arguments = $argumentResolver->getArguments($request, $controller);

    $response = call_user_func_array($controller, $arguments);
} catch (ResourceNotFoundException $exception) {
    $response = new Response('Not Found', 404);
} catch (Exception $exception) {
    $response = new Response('An error occurred', 500);
}

$response->send();