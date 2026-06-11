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

    public function exportCsv()
    {
        $trainerService = new TrainerService();
        $trainerService->exportCsv();
    }

    public function exportXml()
    {
        $trainerService = new TrainerService();
        $trainerService->exportXml();
    }

    public function import()
    {
        $trainerService = new TrainerService();

        if(!isset($_FILES['file'])){
            throw new \Exception(
                'File required'
            );
        }

        if($_POST['format'] === 'csv'){

            $trainerService->importCsv(
                $_FILES['file']
            );

        }else{

            $trainerService->importXml(
                $_FILES['file']
            );
        }

        header(
            'Location: /kim/users'
        );

        exit;
    }
}