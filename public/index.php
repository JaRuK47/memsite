<?php

require_once '../vendor/autoload.php';
require_once "../framework/autoload.php";
require_once "../controllers/MainController.php";
require_once "../controllers/ObjectController.php";
require_once "../controllers/Controller404.php";
require_once "../controllers/SearchController.php";
require_once "../controllers/SuperheroCreateController.php";
require_once "../controllers/TypeAddController.php";
require_once "../controllers/SuperheroDeleteController.php";
require_once "../controllers/SuperheroUpdateController.php";
require_once "../controllers/SetWelcomeController.php";
require_once "../controllers/LoginController.php";
require_once "../controllers/LogoutController.php";
require_once "../middleware/LoginRequiredMiddleware.php";
require_once "../middleware/SetHistoryMiddleware.php";
require_once "../framework/BaseMiddleware.php";

session_set_cookie_params(60*60*10);
session_start();

$passwordFile = __DIR__ . '/../password.txt';

if (file_exists($passwordFile)) {
    $dbPassword = trim(file_get_contents($passwordFile));
} else {
    $dbPassword = '';
}

$pdo = new PDO("mysql:host=localhost;dbname=character;charset=utf8", "root", $dbPassword);


$loader = new \Twig\Loader\FilesystemLoader('../views');


$twig = new \Twig\Environment($loader, ["debug" => true]);
$twig->addExtension(new \Twig\Extension\DebugExtension());



$router = new Router($twig, $pdo);

$router->add("/", MainController::class)
    ->middleware(new SetHistoryMiddleware());

$router->add("/superhero/(?<id>\d+)", ObjectController::class)
    ->middleware(new SetHistoryMiddleware());

$router->add("/search", SearchController::class)
    ->middleware(new SetHistoryMiddleware());

$router->add("/superhero/create", SuperheroCreateController::class)
    ->middleware(new LoginRequiredMiddleware())
    ->middleware(new SetHistoryMiddleware());

$router->add("/type/add", TypeAddController::class)
    ->middleware(new LoginRequiredMiddleware())
    ->middleware(new SetHistoryMiddleware());

$router->add("/superhero/delete", SuperheroDeleteController::class)
    ->middleware(new LoginRequiredMiddleware())
    ->middleware(new SetHistoryMiddleware());

$router->add("/superhero/(?P<id>\d+)/edit", SuperheroUpdateController::class)
    ->middleware(new LoginRequiredMiddleware())
    ->middleware(new SetHistoryMiddleware());

$router->add("/set-welcome/", SetWelcomeController::class)
    ->middleware(new SetHistoryMiddleware());

$router->add("/login", LoginController::class)
    ->middleware(new SetHistoryMiddleware());

$router->add("/logout", LogoutController::class)
    ->middleware(new SetHistoryMiddleware());

$router->get_or_default(Controller404::class);
