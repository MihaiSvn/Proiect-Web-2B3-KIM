<?php

namespace api\admin_rooms;

use services\RoomsEquipmentService;

class RoomsApiController
{
    public function getData()
    {
        $service =
            new RoomsEquipmentService();

        http_response_code(200);

        echo json_encode([

            'rooms' =>
                $service->getRooms()
        ]);
    }

    public function update()
    {
        $service =
            new RoomsEquipmentService();

        $service->updateRoom(
            json_decode(
                file_get_contents(
                    'php://input'
                ),
                true
            )
        );

        echo json_encode([

            'status' =>
                'success',

            'message' =>
                'Room updated successfully'
        ]);
    }

    public function create()
    {
        $service =
            new RoomsEquipmentService();

        $service->createRoom(
            json_decode(
                file_get_contents(
                    'php://input'
                ),
                true
            )
        );

        echo json_encode([

            'status' =>
                'success',

            'message' =>
                'Room created successfully'
        ]);
    }
}