<?php

use Symfony\Component\DependencyInjection\{ContainerBuilder, Reference};
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\HttpKernel\Controller\{ControllerResolver, ArgumentResolver};
use StudentList\Database\{Connection, StudentDataGateway};
use StudentList\AuthManager;
use StudentList\Validators\StudentValidator;
use StudentList\Helpers\{UrlManager, Util, Pager};
use StudentList\Controllers\{HomeController, ProfileController, RegisterController};

$containerBuilder = new ContainerBuilder();

// Symfony components
$containerBuilder->register("context", RequestContext::class);
$containerBuilder->register("matcher", UrlMatcher::class)
    ->setArguments(array($routes, new Reference("context")));
$containerBuilder->register("controller_resolver", ControllerResolver::class);
$containerBuilder->register("argument_resolver", ArgumentResolver::class);

// Models
$containerBuilder->register("connection", Connection::class);
$containerBuilder->register("auth_manager", AuthManager::class);
$containerBuilder->register("student_data_gateway", StudentDataGateway::class)
    ->setArguments(array(new Reference("connection")));
$containerBuilder->register("student_validator", StudentValidator::class)
    ->setArguments(array(new Reference("student_data_gateway"), new Reference("auth_manager")));
$containerBuilder->register("url_manager", UrlManager::class);
$containerBuilder->register("util", Util::class);
$containerBuilder->register("pager", Pager::class);

// Controllers
$containerBuilder->register("home_controller", HomeController::class)
    ->setArguments(array());

