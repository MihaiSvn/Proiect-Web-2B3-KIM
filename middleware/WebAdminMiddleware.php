<?php

namespace middleware;

class WebAdminMiddleware
{
    public static function checkAccess()
    {

        $loggedInUserId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
        $loggedInUserRole = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;

        if (!$loggedInUserId) {
            header('Location: /kim/login?error=You are not logged in');
            exit;
        }

        if ($loggedInUserRole !== "admin") {
            header('Location: /kim/dashboard?error=Admin+access+only');
            exit;
        }
    }
}