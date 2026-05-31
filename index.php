<?php

session_start();

require_once 'config/database.php';
require_once 'core/Router.php';

require_once 'models/User.php';
require_once 'models/UserSubscription.php';
require_once 'models/Session.php';

require_once 'services/UserService.php';
require_once 'services/UserSubscriptionsService.php';
require_once 'services/SessionService.php';

require_once 'controllers/AuthController.php';
require_once 'controllers/DashboardController.php';
require_once 'controllers/UserSubscriptionController.php';

use services\UserService;
use services\UserSubscriptionsService;
use services\SessionService;

use controllers\DashboardController;
use controllers\AuthController;
use controllers\UserSubscriptionController;


$router = new Router();

$router->get('/login','views/login.php');
$router->get('/home','views/home.php');
$router->get('/register','views/register.php');
$router->get('/dashboard',function(){
    $userService = new UserService();
    $userSubscriptionsService = new UserSubscriptionsService();
    $sessionService = new SessionService();
    $dashboardController = new DashboardController($userService, $userSubscriptionsService, $sessionService);
    $dashboardController->index();
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

$router->post('/subscription/suspend', function(){
    $userSubscriptionService = new UserSubscriptionsService();
    $userSubscriptionController = new UserSubscriptionController($userSubscriptionService);
    $userSubscriptionController->suspend();
});


$router->get('/hash','hash.php');

$router->resolve();
