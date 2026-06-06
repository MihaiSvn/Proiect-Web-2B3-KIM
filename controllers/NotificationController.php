<?php

namespace controllers;

use services\NotificationService;
use services\UserService;

class NotificationController
{
    private $userService;
    private $notificationService;

    public function __construct(NotificationService $notificationService, UserService  $userService)
    {
        $this->notificationService = $notificationService;
        $this->userService = $userService;
    }

    public function markAllAsRead()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /kim/login?error=You have to be logged in to see this page');
            exit;
        }

        $user = $this->userService->getUserById($_SESSION['user_id']);
        if(!$user){
            session_destroy();
            header('Location: /kim/login?error=User not found');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $this->notificationService->dismissNotifications($userId);

        $this->redirectBack('success', 'All notifications have been marked as read');
    }

    public function markAsRead()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /kim/login?error=You have to be logged in to see this page');
            exit;
        }
        $user = $this->userService->getUserById($_SESSION['user_id']);
        if(!$user){
            session_destroy();
            header('Location: /kim/login?error=User not found');
            exit;
        }

        if (!isset($_POST['notification_id'])) {
            $this->redirectBack('error', 'Missing notification ID.');
            return;
        }

        if (isset($_POST['notification_id'])) {
            $notificationId = (int)$_POST['notification_id'];
            try {
                $this->notificationService->dismissNotificationById($notificationId);

                $this->redirectBack('success', 'Notification has been marked as read.');

            } catch (\InvalidArgumentException $e) {
                $this->redirectBack('error', $e->getMessage());
            }
        }


    }


    private function redirectBack($statusType, $message)
    {

        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/kim/dashboard';

        //daca in link avem deja un parametru sau nu
        $separator = (strpos($referer, '?') !== false) ? '&' : '?';

        // ?success=Mesaj+aici)
        $queryString = ($statusType !== '') ? $separator . $statusType . '=' . urlencode($message) : '';

        header('Location: ' . $referer . $queryString);
        exit;
    }
}