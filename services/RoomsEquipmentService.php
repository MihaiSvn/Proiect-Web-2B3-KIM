<?php

namespace services;

use models\Room;

class RoomsEquipmentService
{
    public function getRooms()
    {
        return Room::getAllRooms();
    }

    public function updateRoom(
        $data
    )
    {
        $room =
            Room::findById(
                $data['room_id']
            );

        if(!$room){

            throw new \Exception(
                'Room not found'
            );
        }

        if(
            empty(
            trim(
                $data['name']
            )
            )
        ){

            throw new \Exception(
                'Room name is required'
            );
        }

        if(
            (int)$data['capacity']
            <= 0
        ){

            throw new \Exception(
                'Capacity must be greater than 0'
            );
        }

        if(
            empty(
            $data['type']
            )
        ){

            throw new \Exception(
                'Please select a room type'
            );
        }

        $allowedTypes = [

            'fitness',
            'strength',
            'physiotherapy'
        ];

        if(
            !in_array(
                $data['type'],
                $allowedTypes
            )
        ){

            throw new \Exception(
                'Invalid room type'
            );
        }

        Room::updateRoom(
            $data
        );
    }

    public function createRoom(
        $data
    )
    {
        if(!$data){

            throw new \Exception(
                'Invalid request body'
            );
        }

        if(
            empty(
            trim(
                $data['name']
            )
            )
        ){

            throw new \Exception(
                'Room name is required'
            );
        }

        if(
            empty(
            $data['capacity']
            )
        ){

            throw new \Exception(
                'Capacity is required'
            );
        }

        if(
            (int)$data['capacity']
            <= 0
        ){

            throw new \Exception(
                'Capacity must be greater than 0'
            );
        }

        if(
            empty(
            $data['type']
            )
        ){

            throw new \Exception(
                'Please select a room type'
            );
        }

        $allowedTypes = [

            'fitness',
            'strength',
            'physiotherapy'
        ];

        if(
            !in_array(
                $data['type'],
                $allowedTypes
            )
        ){

            throw new \Exception(
                'Invalid room type'
            );
        }

        Room::createRoom(
            $data
        );
    }
}