<?php

namespace services;

use models\Notification;

class NotificationService
{

    public function getUserNotifications($userId)
    {
        return Notification::findByUserId($userId);
    }

    public function getUnreadUserNotifications($userId)
    {
        return Notification::findUnreadByUserId($userId);
    }

    public function sendNotification($userId, $title, $message)
    {
        return Notification::create($userId, $title, $message);
    }

    public function dismissNotifications($userId)
    {
        return Notification::markAllAsRead($userId);
    }

    public function dismissNotificationById($notificationId){
        return Notification::markAsReadById($notificationId);
    }

    public function getTimeAgo($datetime)
    {
        $timestamp = strtotime($datetime);
        $difference = time() - $timestamp;

        if ($difference < 60) {
            return 'Just now';
        } elseif ($difference < 3600) {
            return floor($difference / 60) . 'm ago';
        } elseif ($difference < 86400) {
            return floor($difference / 3600) . 'h ago';
        } elseif ($difference < 2592000) { //  30 de zile
            return floor($difference / 86400) . 'd ago';
        } elseif ($difference < 31536000) { // 365 de zile
            return floor($difference / 2592000) . 'mo ago';
        } else {
            return floor($difference / 31536000) . 'y ago';
        }
    }

}