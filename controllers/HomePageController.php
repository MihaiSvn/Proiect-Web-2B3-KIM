<?php

namespace controllers;

use core\ApiClient;

class HomePageController
{
    public function index()
    {
        $apiUrl =
            'http://localhost/kim/api/trainers';

        $apiData =
            ApiClient::get($apiUrl);

        if(!$apiData){
            header(
                'Location: /kim/?error=' .
                urlencode(
                    'Unable to load trainers'
                )
            );
            exit;
        }

        $trainers =
            (array)$apiData->trainers;

        require 'views/home.php';
    }
}