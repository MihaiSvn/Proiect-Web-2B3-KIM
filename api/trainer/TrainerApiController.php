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
        header('Content-Type: application/json');

        try {
            $trainerService = new TrainerService();

            if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
                http_response_code(400);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'A valid file is required for import.'
                ]);
                exit;
            }

            $format = isset($_POST['format']) ? $_POST['format'] : 'csv';

            if ($format === 'csv') {
                $trainerService->importCsv($_FILES['file']);
            } else {
                $trainerService->importXml($_FILES['file']);
            }

            http_response_code(200);
            echo json_encode([
                'status' => 'success',
                'message' => 'Import completed successfully!'
            ]);
            exit;

        } catch (\Exception $e) {
            http_response_code(500);

            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
            exit;
        }
    }
}
