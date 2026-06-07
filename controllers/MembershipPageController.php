<?php

namespace controllers;
use core\ApiClient;

class MembershipPageController
{
    public function index(){

        $apiData = ApiClient::get('http://localhost/kim/api/memberships');

        if (!$apiData) {
            header(
                'Location: /kim/?error=' .
                urlencode('Service temporarily unavailable')
            );
            exit;
        }

        if (isset($apiData->error)) {
            header('Location: /kim/membership?error='.urlencode($apiData->error));
            exit;
        }

        $fitnessSubscriptions = (array)$apiData->fitness;
        $strengthSubscriptions = (array)$apiData->strength;
        $physiotherapySubscriptions = (array)$apiData->physiotherapy;
        $allSubscriptions = (array)$apiData->fullAccess;

        require 'views/membership.php';
    }
}