<?php

namespace middleware;

class ApiAuthMiddleware
{
    public static function checkAccess(){


        header('content-type: application/json');
        $requestedUserId = isset($_GET['user_id']) ? (int)$_GET['user_id'] : null;
        $loggedInUserId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
        $loggedInUserRole = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;

        if(!$loggedInUserId){
            http_response_code(401);
            echo json_encode(['error' => 'You are not authorized to access this page.']);
            exit;
        }

        if($requestedUserId !== null && $loggedInUserId !== $requestedUserId && $loggedInUserRole !== "admin"){
            http_response_code(401);
            echo json_encode(['error' => 'You are not authorized to access this page.']);
            exit;
        }
    }
}