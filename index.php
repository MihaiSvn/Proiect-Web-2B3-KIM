<?php

session_start();

require_once 'config/database.php';
require_once 'core/Router.php';

require_once 'models/User.php';
require_once 'models/UserSubscription.php';
require_once 'models/Session.php';
require_once 'models/Notification.php';
require_once 'models/Booking.php';
require_once 'models/Trainer.php';
require_once 'models/Room.php';

require_once 'services/UserService.php';
require_once 'services/UserSubscriptionsService.php';
require_once 'services/SessionService.php';
require_once 'services/NotificationService.php';
require_once 'services/BookingService.php';
require_once 'services/TrainerService.php';
require_once 'services/RoomService.php';

require_once 'controllers/AuthController.php';
require_once 'controllers/MemberDashboardController.php';
require_once 'controllers/TrainerDashboardController.php';
require_once 'controllers/UserSubscriptionController.php';
require_once 'controllers/NotificationController.php';
require_once 'controllers/BookingController.php';
require_once 'controllers/NewsletterController.php';
require_once 'controllers/SessionController.php';
require_once 'controllers/AdminDashboardController.php';
require_once 'controllers/SessionsPageController.php';

use services\UserService;
use services\UserSubscriptionsService;
use services\SessionService;
use services\NotificationService;
use services\BookingService;
use services\TrainerService;
use services\RoomService;

use controllers\MemberDashboardController;
use controllers\TrainerDashboardController;
use controllers\AuthController;
use controllers\UserSubscriptionController;
use controllers\NotificationController;
use controllers\BookingController;
use controllers\NewsletterController;

$router = new Router();

$router->get('/login','views/login.php');
$router->get('/home','views/home.php');
$router->get('/membership','views/membership.php');
$router->get('/register','views/register.php');
$router->get('/dashboard',function(){
    $userService = new UserService();
    $userSubscriptionsService = new UserSubscriptionsService();
    $sessionService = new SessionService();
    $notificationService = new NotificationService();
    $dashboardController = new DashboardController($userService, $userSubscriptionsService, $sessionService, $notificationService);
    $dashboardController->index();
use controllers\SessionController;
use controllers\AdminDashboardController;
use controllers\SessionsPageController;

$router = new Router();

$router->get('/login', 'views/login.php');
$router->get('/home', 'views/home.php');
$router->get('/register', 'views/register.php');
$router->get('/dashboard', function () {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /kim/login?error=' . urlencode('You need to be logged in!'));
        exit;
    }

    $role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'member';

    if ($role === 'admin') {

        $userService = new UserService();
        $sessionService = new SessionService();
        $trainingService = new TrainerService();
        $userSubscriptionsService = new UserSubscriptionsService();
        $notificationService = new NotificationService();

        $dashboardController = new AdminDashboardController($userService, $sessionService, $trainingService, $userSubscriptionsService, $notificationService);
        $dashboardController->index();

    } elseif ($role === 'trainer') {

        $userService = new UserService();
        $sessionService = new SessionService();
        $notificationService = new NotificationService();
        $trainerService = new TrainerService();

        $dashboardController = new TrainerDashboardController($userService, $sessionService, $notificationService, $trainerService);
        $dashboardController->index();

    } else {
        $userService = new UserService();
        $userSubscriptionsService = new UserSubscriptionsService();
        $sessionService = new SessionService();
        $notificationService = new NotificationService();

        $dashboardController = new MemberDashboardController(
            $userService,
            $userSubscriptionsService,
            $sessionService,
            $notificationService
        );
        $dashboardController->index();
    }
});

$router->get('/test', 'config/test_db.php');
$router->get('/sessions', function (){
    $sessionService = new SessionService();
    $userService = new UserService();
    $trainerService = new TrainerService();
    $roomService = new RoomService();

    $sessionsPageController = new SessionsPageController($sessionService, $userService, $trainerService, $roomService);

    $sessionsPageController->index();
});

$router->post('/login', function () {
    $userService = new UserService();
    $trainerService = new TrainerService();
    $authController = new AuthController($userService, $trainerService);
    $authController->login();
});
$router->post('/register', function () {
    $userService = new UserService();
    $trainerService = new TrainerService();
    $authController = new AuthController($userService, $trainerService);
    $authController->register();
});

$router->post('/subscription/suspend', function () {
    $userSubscriptionService = new UserSubscriptionsService();
    $userSubscriptionController = new UserSubscriptionController($userSubscriptionService);
    $userSubscriptionController->suspend();
});

$router->post('/notifications/mark-all-read', function () {
    $notificationService = new NotificationService();
    $notificationController = new NotificationController($notificationService);

    $notificationController->markAllAsRead();
});

$router->post('/notifications/mark-read', function () {
    $notificationService = new NotificationService();
    $notificationController = new NotificationController($notificationService);

    $notificationController->markAsRead();
});

$router->post('/sessions/cancel-booking', function () {
    $bookingService = new BookingService();
    $bookingController = new BookingController($bookingService);

    $bookingController->cancel();
});

$router->post('/newsletter', function(){

    $newsletterController =
        new NewsletterController();

    $newsletterController->subscribe();

});

$router->post('/sessions/book', function () {
    $bookingService = new BookingService();
    $bookingController = new BookingController($bookingService);

    $bookingController->book();
});

$router->post('/sessions/cancel-session', function () {
    $sessionService = new SessionService();
    $trainerService = new TrainerService();
    $sessionController = new SessionController($sessionService, $trainerService);

    $sessionController->cancel();
});

$router->post('/sessions/create', function () {
    $sessionService = new SessionService();
    $trainerService = new TrainerService();
    $sessionController = new SessionController($sessionService, $trainerService);
    $sessionController->create();
});

$router->post('/sessions/edit', function () {
    $sessionService = new SessionService();
    $trainerService = new TrainerService();
    $sessionController = new SessionController($sessionService, $trainerService);
    $sessionController->edit();
});

$router->get('/hash', 'hash.php');

$router->resolve();
