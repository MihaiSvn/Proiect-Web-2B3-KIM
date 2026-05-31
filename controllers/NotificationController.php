<?php

namespace controllers;

class NotificationController
{
    private $notificationService;

    public function __construct($notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function markAllAsRead()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /kim/login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $this->notificationService->dismissNotifications($userId);

        $this->redirectBack();
    }

    public function markAsRead()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /kim/login');
            exit;
        }

        if (isset($_POST['notification_id'])) {
            $notificationId = (int)$_POST['notification_id'];
            $this->notificationService->dismissNotificationById($notificationId);
        }

        $this->redirectBack();
    }

    /**
     * Helper privat: Redirecționează userul înapoi de unde a venit
     */
    private function redirectBack()
    {
        // Dacă știm de pe ce pagină a venit cererea, îl trimitem fix acolo
        if (isset($_SERVER['HTTP_REFERER'])) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            // Fallback în caz că browserul nu trimite HTTP_REFERER
            header('Location: /kim/dashboard');
        }
        exit;
    }
}