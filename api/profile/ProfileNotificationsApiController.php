<?php

namespace api\profile;

use services\NotificationService;
use services\UserService;

class ProfileNotificationsApiController
{
    public function getNotifications(){

        header('Content-type: application/json');

        try{
            $userId = isset($_GET['user_id']) ? intval($_GET['user_id']) : null;

            if(!$userId){
                http_response_code(400);
                echo json_encode(["error" => "User ID is required."]);
                exit;
            }

            $userService = new UserService();
            $notificationService = new NotificationService();

            $user = $userService->getUserById($userId);

            if(!$user){
                http_response_code(400);
                echo json_encode(["error" => "User not found."]);
                exit;
            }

            $allNotifications = $notificationService->getUserNotifications($userId);
            $unreadNotifications = $notificationService->getUnreadUserNotifications($userId);

            foreach ($allNotifications as $notification) {
                $notification->time_ago = $notificationService->getTimeAgo($notification->created_at);
            }

            foreach ($unreadNotifications as $notification) {
                $notification->time_ago = $notificationService->getTimeAgo($notification->created_at);
            }

            http_response_code(200);
            echo json_encode(
                [
             'user' => $user,
            'allNotifications' => $allNotifications,
            'unreadNotifications' => $unreadNotifications]
            );
        } catch (\Exception $e){
            http_response_code(400);
            echo json_encode(["error" => $e->getMessage()]);
            exit;
        }
    }
}