<?php

use Symfony\Component\DependencyInjection\{ContainerBuilder, Reference};
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\HttpKernel\Controller\{ArgumentResolver, ContainerControllerResolver};
use StudentList\App;
use StudentList\Database\StudentDataGateway;
use StudentList\AuthManager;
use StudentList\Validators\StudentValidator;
use StudentList\Helpers\{UrlManager, Util, Pager, BinToHexHash};
use StudentList\Controllers\{HomeController, RegisterController, ProfileController};

$containerBuilder = new ContainerBuilder();

// Symfony components
$containerBuilder->register("context", RequestContext::class);
$containerBuilder->register("matcher", UrlMatcher::class)
    ->setArguments(array("%routes%", new Reference("context")));
$containerBuilder->register("controller_resolver", ContainerControllerResolver::class)
    ->setArguments(array("%container%"));
$containerBuilder->register("argument_resolver", ArgumentResolver::class);
$containerBuilder->register("app", App::class)->setArguments(
    array(
        new Reference("matcher"),
        new Reference("controller_resolver"),
        new Reference("argument_resolver")
    )
);

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

