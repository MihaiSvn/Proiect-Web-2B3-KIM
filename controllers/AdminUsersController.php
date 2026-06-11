<?php

namespace controllers;

use core\ApiClient;

class AdminUsersController
{
    public function index()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /kim/login?error=' . urlencode('You need to be logged in to access this page!'));
            exit;
        }


        $apiUrl = "http://localhost/kim/api/user/all";
        $apiData = ApiClient::get($apiUrl);

        $admins = [];
        $trainers = [];
        $members = [];

        if ($apiData && isset($apiData->status) && $apiData->status === 'success') {

            $admins   = (array)$apiData->admin;
            $trainers = (array)$apiData->trainer;
            $members  = (array)$apiData->member;

        } else {
            header('Location: /kim/dashboard?error=There was a problem loading users');
            exit;
        }

        require 'views/users.php';

    }
}
