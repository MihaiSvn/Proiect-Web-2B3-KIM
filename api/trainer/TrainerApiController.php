<?php

namespace api\trainer;

use services\TrainerService;

class TrainerApiController
{
    public function getAll()
    {
        header('Content-Type: application/json');

        $trainerService = new TrainerService();

        echo json_encode([
            'trainers' => $trainerService->getAllTrainers()
        ]);
    }
}