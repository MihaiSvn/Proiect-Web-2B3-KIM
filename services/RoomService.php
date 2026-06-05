<?php

namespace services;

use models\Room;
class RoomService
{
    public function getAllActiveRoomsByType($type){
        return Room::getAllActiveRoomsByType($type);
    }
}