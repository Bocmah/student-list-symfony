<?php

use Symfony\Component\Routing\{RouteCollection, Route};

$routes = new RouteCollection();

$routes->add("homepage_index", new Route("/", array(
    "_controller" => "StudentList\Controllers\HomeController::index"
), array(), array(), "", array(), array("GET")));

$routes->add("register_page_index", new Route("/register", array(
    "_controller" => "StudentList\Controllers\RegisterController::index"
), array(), array(), "", array(), array("GET")));

$routes->add("profile_index", new Route("/profile", array(
    "_controller" => "StudentList\Controllers\ProfileController::index"
), array(), array(), "", array(), array("GET")));

$routes->add("profile_edit", new Route("/profile/edit", array(
    "_controller" => "StudentList\Controllers\ProfileController::edit"
), array(), array(), "", array(), array("GET")));

$routes->add("register_page_store", new Route("/register", array(
    "_controller" => "StudentList\Controllers\RegisterController::store"
), array(), array(), "", array(), array("POST")));

$routes->add("profile_update", new Route("/profile/edit", array(
    "_controller" => "StudentList\Controllers\ProfileController::update"
), array(), array(), "", array(), array("POST")));

return $routes;
