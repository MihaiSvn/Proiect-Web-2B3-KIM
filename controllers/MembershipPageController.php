<?php

namespace controllers;

class MembershipPageController
{
    public function index()
    {
        $apiUrl = 'http://localhost/kim/api/memberships';
        session_write_close();
        $context = stream_context_create(['http' => ['ignore_errors' => true]]);
        $jsonResponse = file_get_contents($apiUrl, false, $context);

        if (!$jsonResponse) {
            header('Location: /kim/?error='.urlencode('Service temporarily unavailable'));
            exit;
        }

        $apiData = json_decode($jsonResponse);

        if (isset($apiData->error)) {
            header('Location: /kim/?error='.urlencode($apiData->error));
            exit;
        }

        $fitnessSubscriptions = (array)$apiData->fitness;
        $strengthSubscriptions = (array)$apiData->strength;
        $physiotherapySubscriptions = (array)$apiData->physiotherapy;
        $allSubscriptions = (array)$apiData->fullAccess;

        require 'views/membership.php';
    }
}