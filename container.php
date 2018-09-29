<?php

use Symfony\Component\DependencyInjection\{ContainerBuilder, Reference};
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\HttpKernel\Controller\{ArgumentResolver, ContainerControllerResolver};
use Symfony\Component\HttpFoundation\RequestStack;
use StudentList\App;
use StudentList\Database\StudentDataGateway;
use StudentList\AuthManager;
use StudentList\Validators\StudentValidator;
use StudentList\Helpers\{UrlManager, Util, Pager, BinToHexHash};
use StudentList\EventListeners\{LoggedInUserListener, AnonymousUserListener};
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\EventDispatcher\EventDispatcher;
use StudentList\Controllers\{HomeController, RegisterController, ProfileController};

$containerBuilder = new ContainerBuilder();

// Symfony components
$containerBuilder->register("context", RequestContext::class);
$containerBuilder->register("matcher", UrlMatcher::class)
    ->setArguments(array("%routes%", new Reference("context")));
$containerBuilder->register("request_stack", RequestStack::class);
$containerBuilder->register("controller_resolver", ContainerControllerResolver::class)
    ->setArguments(array("%container%"));
$containerBuilder->register("argument_resolver", ArgumentResolver::class);

// Models
$containerBuilder->register("auth_manager", AuthManager::class);
$containerBuilder->register("student_data_gateway", StudentDataGateway::class)
    ->setArguments(array("%connection%"));
$containerBuilder->register("student_validator", StudentValidator::class)
    ->setArguments(array(new Reference("student_data_gateway"), new Reference("auth_manager")));
$containerBuilder->register("url_manager", UrlManager::class);
$containerBuilder->register("hash", BinToHexHash::class);
$containerBuilder->register("util", Util::class);
$containerBuilder->register("pager", Pager::class);

// Events listeners
$containerBuilder->register("listener.logged_in_user", LoggedInUserListener::class)
    ->setArguments(array(new Reference("auth_manager")));
$containerBuilder->register("listener.anonymous_user", AnonymousUserListener::class)
    ->setArguments(array(new Reference("auth_manager")));
$containerBuilder->register("listener.router", RouterListener::class)
    ->setArguments(array(new Reference("matcher"), new Reference("request_stack")));

// Event dispatcher
$containerBuilder->register("dispatcher", EventDispatcher::class)
    ->addMethodCall("addSubscriber", array(new Reference("listener.router")))
    ->addMethodCall("addSubscriber", array(new Reference("listener.logged_in_user")))
    ->addMethodCall("addSubscriber", array(new Reference("listener.anonymous_user")));

// App
$containerBuilder->register("app", App::class)->setArguments(
    array(
        new Reference("dispatcher"),
        new Reference("controller_resolver"),
        new Reference("request_stack"),
        new Reference("argument_resolver")
    )
);

// Controllers
$containerBuilder->register("StudentList\Controllers\HomeController", HomeController::class)
    ->setArguments(
        array(
            new Reference("pager"),
            new Reference("student_data_gateway"),
            new Reference("auth_manager")
        )
    );

$containerBuilder->register("StudentList\Controllers\RegisterController", RegisterController::class)
    ->setArguments(
        array(
            new Reference("student_validator"),
            new Reference("hash"),
            new Reference("student_data_gateway"),
            new Reference("auth_manager")
        )
    );

$containerBuilder->register("StudentList\Controllers\ProfileController", ProfileController::class)
    ->setArguments(
        array(
            new Reference("student_data_gateway"),
            new Reference("student_validator")
        )
    );

return $containerBuilder;

