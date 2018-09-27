<?php

use Symfony\Component\HttpFoundation\Request;

require_once __DIR__."/../vendor/autoload.php";

$container = require_once __DIR__."/../container.php";

$container->setParameter("routes", require_once __DIR__."/../routes.php");
$container->setParameter("config", require_once __DIR__."/../config.php");
$container->setParameter("container", $container);

$request = Request::createFromGlobals();

$response = $container->get("app")->handle($request);

$response->send();
