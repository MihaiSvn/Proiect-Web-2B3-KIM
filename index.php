<?php

session_start();

require_once 'config/database.php';
require_once 'core/Router.php';

require_once 'models/User.php';
require_once 'models/UserSubscription.php';

require_once 'services/UserService.php';
require_once 'services/UserSubscriptionsService.php';

require_once 'controllers/AuthController.php';
require_once 'controllers/ProfileController.php';

use controllers\AuthController;
use models\UserSubscription;
use services\UserService;
use services\UserSubscriptionsService;
use controllers\ProfileController;

$router = new Router();

$router->get('/login','views/login.php');
$router->get('/home','views/home.php');
$router->get('/register','views/register.php');
$router->get('/profile',function(){
    $userService = new UserService();
    $userSubscriptionsService = new UserSubscriptionsService();
    $profileController = new ProfileController($userService, $userSubscriptionsService);
    $profileController->index();
});

$router->get('/test','config/test_db.php');

$router->post('/login', function(){
    $userService = new UserService();
    $authController = new AuthController($userService);
    $authController->login();
});
$router->post('/register', function(){
    $userService = new UserService();
    $authController = new AuthController($userService);
    $authController->register();
});


$router->get('/hash','hash.php');

$router->resolve();
