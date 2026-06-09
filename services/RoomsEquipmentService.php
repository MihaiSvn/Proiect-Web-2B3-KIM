<?php

namespace services;

use models\Room;

class RoomsEquipmentService
{
    public function getRooms()
    {
        return Room::getAllRooms();
    }

    public function updateRoom($data)
    {
        Room::updateRoom(
            $data
        );
    }

    public function createRoom($data)
    {
        Room::createRoom(
            $data
        );
    }
}