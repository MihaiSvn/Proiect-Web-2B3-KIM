<?php

namespace api\equipment;

use services\EquipmentService;

class EquipmentApiController
{
    public function create()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $name = isset($data['name']) ? $data['name'] : '';
        $roomId = isset($data['room_id']) ? $data['room_id'] : null;
        $isFunctional = isset($data['is_functional']) ? $data['is_functional'] : 1;

        if (empty($name) || empty($roomId)) {
            http_response_code(400);
            echo json_encode([
                'status' => 'error',
                'message' => 'Please fill in all required fields.'
            ]);
            exit;
        }

        $equipmentService = new EquipmentService();

        try {
            $equipmentService->createEquipment($roomId, $name, $isFunctional);

            http_response_code(200);
            echo json_encode([
                'status' => 'success',
                'message' => 'Equipment successfully added.'
            ]);
            exit;

        } catch (\Exception $ex) {
            http_response_code(400);
            echo json_encode([
                'status' => 'error',
                'message' => $ex->getMessage()
            ]);
            exit;
        }
    }
}
