<?php

session_start();

require_once 'config/database.php';
require_once 'core/Router.php';
require_once 'core/ApiClient.php';

require_once 'models/User.php';
require_once 'models/UserSubscription.php';
require_once 'models/Session.php';
require_once 'models/Notification.php';
require_once 'models/Booking.php';
require_once 'models/Trainer.php';
require_once 'models/Room.php';
require_once 'models/Subscription.php';
require_once 'models/SubscriptionFeatures.php';

require_once 'services/UserService.php';
require_once 'services/UserSubscriptionsService.php';
require_once 'services/SessionService.php';
require_once 'services/NotificationService.php';
require_once 'services/BookingService.php';
require_once 'services/TrainerService.php';
require_once 'services/RoomService.php';
require_once 'services/SubscriptionService.php';


require_once 'api/auth/AuthApiController.php';
require_once 'controllers/MemberDashboardController.php';
require_once 'controllers/TrainerDashboardController.php';
require_once 'controllers/UserSubscriptionController.php';
require_once 'controllers/NotificationController.php';
require_once 'controllers/BookingController.php';
require_once 'controllers/NewsletterController.php';
require_once 'controllers/SessionController.php';
require_once 'controllers/AdminDashboardController.php';
require_once 'controllers/SessionsPageController.php';
require_once 'api/user/UserApiController.php';
require_once 'controllers/profile_page_tabs_controllers/ProfileInfoController.php';
require_once 'controllers/profile_page_tabs_controllers/ProfileNotificationsController.php';
require_once 'controllers/profile_page_tabs_controllers/ProfileMembershipHistoryController.php';
require_once 'controllers/profile_page_tabs_controllers/ProfileActivityController.php';
require_once 'controllers/profile_page_tabs_controllers/ProfileSettingsController.php';
require_once 'controllers/MembershipPageController.php';
require_once 'api/membership/MembershipApiController.php';
require_once 'api/membership/UserSubscriptionApiController.php';
require_once 'api/dashboard/AdminDashboardApiController.php';
require_once 'api/dashboard/MemberDashboardApiController.php';
require_once 'api/dashboard/TrainerDashboardApiController.php';
require_once 'api/profile/ProfileActivityApiController.php';
require_once 'api/profile/ProfileNotificationsApiController.php';

use services\UserService;
use services\UserSubscriptionsService;
use services\SessionService;
use services\NotificationService;
use services\BookingService;
use services\TrainerService;
use services\RoomService;
use services\SubscriptionService;

use controllers\MemberDashboardController;
use controllers\TrainerDashboardController;
use api\auth\AuthApiController;
use api\membership\MembershipApiController;
use api\membership\UserSubscriptionApiController;
use controllers\UserSubscriptionController;
use controllers\NotificationController;
use controllers\profile_page_tabs_controllers\ProfileInfoController;
use controllers\profile_page_tabs_controllers\ProfileNotificationsController;
use controllers\profile_page_tabs_controllers\ProfileMembershipHistoryController;
use controllers\profile_page_tabs_controllers\ProfileActivityController;
use controllers\profile_page_tabs_controllers\ProfileSettingsController;
use controllers\BookingController;
use controllers\NewsletterController;
use controllers\SessionController;
use controllers\AdminDashboardController;
use controllers\SessionsPageController;
use api\user\UserApiController;
use controllers\MembershipPageController;
use api\dashboard\AdminDashboardApiController;
use api\dashboard\MemberDashboardApiController;
use api\dashboard\TrainerDashboardApiController;

use api\profile\ProfileActivityApiController;
use api\profile\ProfileNotificationsApiController;



$router = new Router();

$router->get('/login', 'views/login.php');
$router->get('/home', 'views/home.php');

$router->get('/membership', function () {

    $controller = new MembershipPageController();
    $controller->index();

});

$router->get('/api/memberships', function () {

    $controller = new MembershipApiController();
    $controller->getMemberships();

});

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

$router->get('/profile', function (){
    $userService = new UserService();
    $profileInfoController = new ProfileInfoController($userService);
    $profileInfoController->index();
});

$router->get('/profile/personal-info', function (){
    $userService = new UserService();
    $profileInfoController = new ProfileInfoController($userService);
    $profileInfoController->index();
});

$router->get('/profile/notifications', function (){
    $userService = new UserService();
    $notificationService = new NotificationService();

    $profileNotificationsController = new ProfileNotificationsController($notificationService, $userService);
    $profileNotificationsController->index();
});

$router->get('/profile/membership-history', function (){
    $userService = new UserService();
    $userSubscriptionService = new UserSubscriptionsService();

    $profileMembershipHistoryController = new ProfileMembershipHistoryController($userSubscriptionService,$userService);
    $profileMembershipHistoryController->index();
});

$router->get('/profile/activity-history', function (){
    $userService = new UserService();
    $trainerService = new TrainerService();
    $sessionService = new SessionService();

    $profileActivityController = new ProfileActivityController($sessionService, $userService, $trainerService);
    $profileActivityController->index();
});

$router->get('/profile/settings', function (){
    $userService = new UserService();
    $profileSettingsController = new ProfileSettingsController($userService);
    $profileSettingsController->index();
});

$router->get('/admin/users', function () {
    $userService = new UserService();
    $controller = new AdminUsersController($userService);
    $controller->index();
});

$router->get('/api/member-dashboard', function () {
    (new MemberDashboardApiController())->getData();
});

$router->get('/api/trainer-dashboard', function () {
    (new TrainerDashboardApiController())->getData();
});

$router->get('/api/admin-dashboard', function () {
    (new AdminDashboardApiController())->getData();
});

$router->get('/api/profile/activity', function () {
    (new ProfileActivityApiController())->getActivities();
});

$router->get('/api/profile/notifications', function () {
    (new ProfileNotificationsApiController())->getNotifications();
});

$router->post('/subscription/suspend', function () {
    $userSubscriptionService = new UserSubscriptionsService();
    $userSubscriptionController = new UserSubscriptionController($userSubscriptionService);
    $userSubscriptionController->suspend();
});

$router->post('/subscription/purchase', function () {

    $userSubscriptionService = new UserSubscriptionsService();
    $userSubscriptionController = new UserSubscriptionController($userSubscriptionService);
    $userSubscriptionController->purchase();

});

$router->post('/api/subscription/purchase', function () {

    $controller = new UserSubscriptionApiController();
    $controller->purchase();

});

$router->post('/notifications/mark-all-read', function () {
    $notificationService = new NotificationService();
    $userService = new UserService();
    $notificationController = new NotificationController($notificationService, $userService);

    $notificationController->markAllAsRead();
});

$router->post('/notifications/mark-read', function () {
    $notificationService = new NotificationService();
    $userService = new UserService();
    $notificationController = new NotificationController($notificationService, $userService);

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

$router->post('/api/user/update', function () {
    (new UserApiController())->updateProfile();
});

$router->post('/api/user/change-password', function () {
    (new UserApiController())->changePassword();
});

$router->post('/api/user/delete', function () {
    (new UserApiController())->deleteUser();
});

$router->get('/api/user', function () {
    (new UserApiController())->getUser();
});

$router->post('/api/login', function () {
    (new AuthApiController())->login();
});

$router->post('/api/register', function () {
    (new AuthApiController())->register();
});

$router->post('/api/logout', function () {
    (new AuthApiController())->logout();
});

$router->get('/hash', 'hash.php');

$router->resolve();
