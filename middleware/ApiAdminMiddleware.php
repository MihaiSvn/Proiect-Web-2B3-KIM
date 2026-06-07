<?php

namespace middleware;

class ApiAdminMiddleware
{
    public static function checkAccess()
    {
        header('Content-Type: application/json');

        $loggedInUserId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
        $loggedInUserRole = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;

        if (!$loggedInUserId) {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'You must be logged in.']);
            exit;
        }

        if ($loggedInUserRole !== 'admin') {
            http_response_code(403);
            echo json_encode(['status' => 'error', 'message' => 'Access denied. Admin privileges required.']);
            exit;
        }
    }
}