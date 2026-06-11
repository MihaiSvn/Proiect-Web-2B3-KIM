<?php

namespace controllers;

use core\ApiClient;

class RoomsEquipmentController
{
    public function index()
    {
        $apiUrl =
            "http://localhost/kim/api/rooms";

        $apiData =
            ApiClient::get($apiUrl);

        if(isset($apiData->error)){

            header(
                'Location: /kim/dashboard'
            );

            exit;
        }

        $rooms =
            (array)$apiData->rooms;

        require
        'views/rooms_equipment.php';
    }
}