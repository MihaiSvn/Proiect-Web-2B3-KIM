<?php

session_start();

require_once 'config/database.php';
require_once 'core/Router.php';

require_once 'models/User.php';
require_once 'models/UserSubscription.php';
require_once 'models/Session.php';
require_once 'models/Notification.php';
require_once 'models/Booking.php';

require_once 'services/UserService.php';
require_once 'services/UserSubscriptionsService.php';
require_once 'services/SessionService.php';
require_once 'services/NotificationService.php';
require_once 'services/BookingService.php';

require_once 'controllers/AuthController.php';
require_once 'controllers/DashboardController.php';
require_once 'controllers/UserSubscriptionController.php';
require_once 'controllers/NotificationController.php';
require_once 'controllers/BookingController.php';

use services\UserService;
use services\UserSubscriptionsService;
use services\SessionService;
use services\NotificationService;
use services\BookingService;

use controllers\DashboardController;
use controllers\AuthController;
use controllers\UserSubscriptionController;
use controllers\NotificationController;
use controllers\BookingController;

$router = new Router();

$router->get('/login','views/login.php');
$router->get('/home','views/home.php');
$router->get('/register','views/register.php');
$router->get('/dashboard',function(){
    $userService = new UserService();
    $userSubscriptionsService = new UserSubscriptionsService();
    $sessionService = new SessionService();
    $notificationService = new NotificationService();
    $dashboardController = new DashboardController($userService, $userSubscriptionsService, $sessionService, $notificationService);
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

$router->post('/notifications/mark-all-read', function(){
    $notificationService = new NotificationService();
    $notificationController = new NotificationController($notificationService);

    $notificationController->markAllAsRead();
});

$router->post('/notifications/mark-read', function(){
    $notificationService = new NotificationService();
    $notificationController = new NotificationController($notificationService);

    $notificationController->markAsRead();
});

$router->post('/sessions/cancel-booking', function(){
    $bookingService = new BookingService();
    $bookingController = new BookingController($bookingService);

    $bookingController->cancel();
});



$router->get('/hash','hash.php');

$router->resolve();
