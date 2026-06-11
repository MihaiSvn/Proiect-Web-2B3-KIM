<?php

namespace api\admin_rooms;

use services\RoomsEquipmentService;

class RoomsApiController
{
    public function getData()
    {
        $service = new RoomsEquipmentService();

        http_response_code(200);

        echo json_encode([

            'rooms' => $service->getRooms()
        ]);
    }

    public function update()
    {
        $data = json_decode(
            file_get_contents('php://input'),
            true
        );

        if(
            !isset($data['room_id'])
        ){
            http_response_code(400);

            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid room'
            ]);

            return;
        }

        try{

            $service =
                new RoomsEquipmentService();

            $service->updateRoom(
                $data
            );

            echo json_encode([

                'status' => 'success',
                'message' => 'Room updated successfully'
            ]);

        }catch(\Exception $ex){

            http_response_code(400);

            echo json_encode([

                'status' => 'error',
                'message' => $ex->getMessage()
            ]);
        }
    }

    public function create()
    {
        try{

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

                'status' => 'success',
                'message' => 'Room created successfully'
            ]);

        }catch(\Exception $ex){

            http_response_code(400);

            echo json_encode([

                'status' => 'error',
                'message' => $ex->getMessage()
            ]);
        }
    }
}