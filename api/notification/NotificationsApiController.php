<?php

namespace api\notification;

use services\NotificationService;
use services\UserService;

class NotificationsApiController
{
    public function markAllAsRead(){

        $data = json_decode(file_get_contents("php://input"),true);
        $userService = new UserService();

        $user = $userService->getUserById($_SESSION['user_id']);
        if(!$user){
            http_response_code(400);
            echo json_encode(['status'=>'error', 'message'=>'User not found']);
            exit;
        }

        $notificationService = new NotificationService();
        try{
            $notificationService->dismissNotifications($_SESSION['user_id']);
            http_response_code(200);
            echo json_encode(['status'=>'success', 'message'=>'Notifications marked as read']);
            exit;

        } catch(\Exception $e){
            http_response_code(400);
            echo json_encode(['status'=>'error', 'message'=>$e->getMessage()]);
            exit;
        }
    }

    public function markAsRead(){
        $data = json_decode(file_get_contents("php://input"),true);
        $userService = new UserService();
        $user = $userService->getUserById($_SESSION['user_id']);
        if(!$user){
            http_response_code(400);
            echo json_encode(['status'=>'error', 'message'=>'User not found']);
            exit;
        }

        $notificationService = new NotificationService();
        $notificationId = isset($data['notification_id']) ? $data['notification_id'] : null;
        if(!isset($notificationId)){
            http_response_code(400);
            echo json_encode(['status'=>'error', 'message'=>'Notification id is required']);
            exit;
        }

        try{
            $notificationService->dismissNotificationById($notificationId);
            http_response_code(200);
            echo json_encode(['status'=>'success', 'message'=>'Notification marked as read']);
            exit;
        } catch(\Exception $e){
            http_response_code(400);
            echo json_encode(['status'=>'error', 'message'=>$e->getMessage()]);
            exit;
        }

    }
}