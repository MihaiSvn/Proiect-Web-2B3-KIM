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
require_once 'services/ReportsService.php';
require_once 'services/RoomsEquipmentService.php';

require_once 'controllers/MemberDashboardController.php';
require_once 'controllers/TrainerDashboardController.php';
require_once 'controllers/UserSubscriptionController.php';
require_once 'controllers/NewsletterController.php';
require_once 'controllers/AdminDashboardController.php';
require_once 'controllers/SessionsPageController.php';
require_once 'controllers/profile_page_tabs_controllers/ProfileInfoController.php';
require_once 'controllers/profile_page_tabs_controllers/ProfileNotificationsController.php';
require_once 'controllers/profile_page_tabs_controllers/ProfileMembershipHistoryController.php';
require_once 'controllers/profile_page_tabs_controllers/ProfileActivityController.php';
require_once 'controllers/profile_page_tabs_controllers/ProfileSettingsController.php';
require_once 'controllers/MembershipPageController.php';
require_once 'controllers/HomePageController.php';
require_once 'controllers/ReportsPageController.php';
require_once 'controllers/RoomsEquipmentController.php';

require_once 'api/profile/ProfileActivityApiController.php';
require_once 'api/profile/ProfileNotificationsApiController.php';
require_once 'api/user/UserApiController.php';
require_once 'api/membership/UserSubscriptionApiController.php';
require_once 'api/auth/AuthApiController.php';
require_once 'api/dashboard/MemberDashboardApiController.php';
require_once 'api/dashboard/AdminDashboardApiController.php';
require_once 'api/dashboard/TrainerDashboardApiController.php';
require_once 'api/sessions/SessionsApiController.php';
require_once 'api/booking/BookingApiController.php';
require_once 'api/notification/NotificationsApiController.php';
require_once 'api/membership/MembershipApiController.php';
require_once 'api/trainer/TrainerApiController.php';
require_once 'api/admin_reports/ReportsApiController.php';
require_once 'api/admin_rooms/RoomsApiController.php';

require_once 'middleware/ApiAuthMiddleware.php';
require_once 'middleware/ApiAdminMiddleware.php';
require_once 'middleware/ApiTrainerMiddleware.php';
require_once 'middleware/ApiTrainerAdminMiddleware.php';
require_once 'middleware/ApiMemberMiddleware.php';

use api\profile\ProfileActivityApiController;
use api\profile\ProfileNotificationsApiController;
use api\user\UserApiController;
use api\membership\UserSubscriptionApiController;
use api\membership\MembershipApiController;
use api\auth\AuthApiController;
use api\dashboard\MemberDashboardApiController;
use api\dashboard\AdminDashboardApiController;
use api\dashboard\TrainerDashboardApiController;
use api\sessions\SessionsApiController;
use api\booking\BookingApiController;
use api\notification\NotificationsApiController;
use api\trainer\TrainerApiController;

use controllers\AdminDashboardController;
use controllers\MemberDashboardController;
use controllers\MembershipPageController;
use controllers\NewsletterController;
use controllers\profile_page_tabs_controllers\ProfileActivityController;
use controllers\profile_page_tabs_controllers\ProfileInfoController;
use controllers\profile_page_tabs_controllers\ProfileMembershipHistoryController;
use controllers\profile_page_tabs_controllers\ProfileNotificationsController;
use controllers\profile_page_tabs_controllers\ProfileSettingsController;
use controllers\SessionsPageController;
use controllers\TrainerDashboardController;
use controllers\UserSubscriptionController;
use controllers\HomePageController;
use controllers\RoomsEquipmentController;


use services\BookingService;
use services\NotificationService;
use services\RoomService;
use services\SessionService;
use services\SubscriptionService;
use services\TrainerService;
use services\UserService;
use services\UserSubscriptionsService;

use middleware\ApiAuthMiddleware;
use middleware\ApiAdminMiddleware;
use middleware\ApiTrainerMiddleware;
use middleware\ApiTrainerAdminMiddleware;
use middleware\ApiMemberMiddleware;


$router = new Router();

$router->get('/login', 'views/login.php');

$router->get('/home', function () {
    $controller = new HomePageController();
    $controller->index();

});

$router->get('/api/trainers', function () {

    $controller = new TrainerApiController();
    $controller->getAll();

});

$router->get('/membership', function () {

    $controller = new MembershipPageController();
    $controller->index();

});

$router->get('/register', 'views/register.php');

$router->get('/dashboard', function () {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /kim/login?error=' . urlencode('You need to be logged in!'));
        exit;
    }

    $role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'member';

    if ($role === 'admin') {

        $dashboardController = new AdminDashboardController();
        $dashboardController->index();

    } elseif ($role === 'trainer') {
        $dashboardController = new TrainerDashboardController();
        $dashboardController->index();

    } else {
        $dashboardController = new MemberDashboardController();
        $dashboardController->index();
    }
});

$router->get('/test', 'config/test_db.php');
$router->get('/sessions', function (){


    $sessionsPageController = new SessionsPageController();

    $sessionsPageController->index();
});

$router->get('/profile', function (){
    $profileInfoController = new ProfileInfoController();
    $profileInfoController->index();
});

$router->get('/profile/personal-info', function (){
    $profileInfoController = new ProfileInfoController();
    $profileInfoController->index();
});

$router->get('/profile/notifications', function (){
    $profileNotificationsController = new ProfileNotificationsController();
    $profileNotificationsController->index();
});

$router->get('/profile/membership-history', function (){
    $profileMembershipHistoryController = new ProfileMembershipHistoryController();
    $profileMembershipHistoryController->index();
});

$router->get('/profile/activity-history', function (){

    $profileActivityController = new ProfileActivityController();
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

$router->get('/api/memberships', function () {

    $controller = new MembershipApiController();
    $controller->getMemberships();

});

$router->get('/reports', function () {

    $controller = new \controllers\ReportsPageController();
    $controller->index();

});

$router->get('/api/reports', function () {

    ApiAdminMiddleware::checkAccess();

    $controller = new \api\admin_reports\ReportsApiController();

    $controller->getData();

});

$router->get('/admin/rooms', function () {

        $controller = new RoomsEquipmentController();
        $controller->index();
    }
);

$router->get('/api/rooms', function () {

    ApiAdminMiddleware::checkAccess();

    $controller = new \api\admin_rooms\RoomsApiController();

    $controller->getData();
});

$router->post('/api/rooms/update', function () {

        ApiAdminMiddleware::checkAccess();

        $controller = new \api\admin_rooms\RoomsApiController();

        $controller->update();
    }
);

$router->post('/api/reports/export', function () {

    ApiAdminMiddleware::checkAccess();

    $controller =
        new \api\admin_reports\ReportsApiController();

    $controller->export();
});

$router->post(
    '/api/rooms/create',
    function () {

        ApiAdminMiddleware::checkAccess();

        $controller =
            new \api\admin_rooms\RoomsApiController();

        $controller->create();
    }
);

$router->post('/api/subscription/purchase', function () {

    ApiAuthMiddleware::checkAccess();

    $controller = new UserSubscriptionApiController();
    $controller->purchase();

});

//$router->post('/subscription/purchase', function () {
//
//    $userSubscriptionService = new UserSubscriptionsService();
//    $userSubscriptionController = new UserSubscriptionController($userSubscriptionService);
//    $userSubscriptionController->purchase();
//
//});


$router->post('/newsletter', function(){

    $newsletterController =
        new NewsletterController();

    $newsletterController->subscribe();

});

$router->post('/api/user/update', function () {
    \middleware\ApiAuthMiddleware::checkAccess();
    $profileInfoController = new UserApiController();

    $profileInfoController->updateProfile();
});

$router->post('/api/user/change-password', function () {
    \middleware\ApiAuthMiddleware::checkAccess();
    $userController = new UserApiController();
    $userController->changePassword();
});

$router->post('/api/user/delete', function () {
    \middleware\ApiAuthMiddleware::checkAccess();
    $userController = new UserApiController();
    $userController->deleteUser();
});

$router->get('/hash', 'hash.php');




$router->get('/api/profile_activity', function (){
    ApiAuthMiddleware::checkAccess();
    $apiController = new ProfileActivityApiController();
    $apiController->getActivities();
});

$router->get('/api/profile_notifications', function (){
    ApiAuthMiddleware::checkAccess();
    $apiController = new ProfileNotificationsApiController();
    $apiController->getNotifications();
});

// ia toate datele despre user
$router->get('/api/user-data', function (){
    ApiAuthMiddleware::checkAccess();
    $apiController = new UserApiController();
    $apiController->getUser();
});

$router->get('/api/membership-history', function (){
    ApiAuthMiddleware::checkAccess();
    $apiController = new UserSubscriptionApiController();
    $apiController->getHistory();
});

$router->post('/api/auth/login', function (){
    $authApiController = new AuthApiController();
    $authApiController->login();
});

$router->post('/api/auth/register', function (){
    $authApiController = new AuthApiController();
    $authApiController->register();
});

$router->post('/api/auth/logout', function (){
    $authApiController = new AuthApiController();
    $authApiController->logout();
});

$router->get('/api/member-dashboard', function (){
    ApiAuthMiddleware::checkAccess();
    $apiController = new MemberDashboardApiController();
    $apiController->getData();
});
$router->get('/api/admin-dashboard', function (){
    ApiAdminMiddleware::checkAccess();
    $apiController = new AdminDashboardApiController();
    $apiController->getData();
});
$router->get('/api/trainer-dashboard', function (){
    ApiTrainerMiddleware::checkAccess();
    $apiController = new TrainerDashboardApiController();
    $apiController->getData();
});

$router->get('/api/sessions-data', function (){
    ApiAuthMiddleware::checkAccess();
    $apiController = new SessionsApiController();
    $apiController->getSessionsData();
});

$router->post('/api/sessions/cancel', function (){
    ApiTrainerAdminMiddleware::checkAccess();
    $apiController = new SessionsApiController();
    $apiController->cancel();
});

$router->post('/api/sessions/create', function (){
    ApiTrainerAdminMiddleware::checkAccess();
    $apiController = new SessionsApiController();
    $apiController->create();
});

$router->post('/api/sessions/edit', function (){
    ApiTrainerAdminMiddleware::checkAccess();
    $apiController = new SessionsApiController();
    $apiController->edit();
});

$router->post('/api/bookings/book', function (){
    ApiMemberMiddleware::checkAccess();
    $apiController = new BookingApiController();
    $apiController->book();
});

$router->post('/api/bookings/cancel', function (){
    ApiMemberMiddleware::checkAccess();
    $apiController = new BookingApiController();
    $apiController->cancel();
});

$router->post('/api/notifications/dismiss', function (){
    ApiAuthMiddleware::checkAccess();
    $apiController = new NotificationsApiController();
    $apiController->markAsRead();
});

$router->post('/api/notifications/dismiss-all', function (){
   ApiAuthMiddleware::checkAccess();
   $apiController = new NotificationsApiController();
   $apiController->markAllAsRead();
});

$router->post('/api/membership/suspend', function (){
    ApiMemberMiddleware::checkAccess();
    $apiController = new UserSubscriptionApiController();
    $apiController->suspend();
});
$router->resolve();
