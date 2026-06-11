<?php

namespace services;

use models\Trainer;
class TrainerService
{
    public function getTrainerByUserId($userId){
        return Trainer::findTrainerByUserId($userId);
    }

    public function getAllTrainersCount(){
        return Trainer::getAllTrainersCount();
    }

    public function getAllTrainers(){
        return Trainer::findAllTrainers();
    }

    public function getTrainerById($trainerId){
        return Trainer::findTrainerById($trainerId);
    }

    public function getExportData(){
        return Trainer::getAllTrainersForExport();
    }

    public function exportCsv()
    {
        $data = $this->getExportData();
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="trainers.csv"');
        $output = fopen('php://output', 'w');

        if(empty($data)){
            fclose($output);
            exit;
        }

        fputcsv($output, array_keys((array)$data[0]), ';');

        foreach($data as $row){
            $rowArray = array_map(fn($value) => '="' . $value . '"', (array)$row);
            fputcsv($output, $rowArray, ';');
        }

        fclose($output);

        exit;
    }

    public function exportXml()
    {
        $data = $this->getExportData();
        $xml = new \SimpleXMLElement('<trainers/>');

        foreach($data as $row){
            $trainer = $xml->addChild('trainer');

            foreach((array)$row as $key => $value){

                $trainer->addChild($key, htmlspecialchars((string)$value));
            }
        }

        header('Content-Type: application/xml');
        header('Content-Disposition: attachment; filename="trainers.xml"');
        echo $xml->asXML();

        exit;
    }

    //functie de creat traineri in bd pt cand vreau sa fac import xml/csv
    private function importTrainer(
        $firstName,
        $lastName,
        $email,
        $password,
        $specialization
    )
    {
        $allowedSpecializations = [

            'fitness',
            'strength',
            'physiotherapy'
        ];

        if(
            !in_array(
                $specialization,
                $allowedSpecializations
            )
        ){
            throw new \Exception(
                'Invalid specialization'
            );
        }

        $userService =
            new UserService();

        $userService->createUser(

            $firstName,
            $lastName,
            $email,

            $password,
            $password,

            'trainer',

            $specialization
        );
    }
    public function importCsv($file)
    {
        $handle =
            fopen(
                $file['tmp_name'],
                'r'
            );

        fgetcsv(
            $handle,
            1000,
            ';'
        );

        while(
            ($data =
                fgetcsv(
                    $handle,
                    1000,
                    ';'
                )) !== false
        ){

            $this->importTrainer(

                trim($data[0]),
                trim($data[1]),
                trim($data[2]),
                trim($data[3]),
                trim($data[4])
            );
        }

        fclose($handle);
    }

    public function importXml($file)
    {
        $xml =
            simplexml_load_file(
                $file['tmp_name']
            );

        foreach(
            $xml->trainer
            as $trainer
        ){

            $this->importTrainer(

                (string)$trainer->first_name,
                (string)$trainer->last_name,
                (string)$trainer->email,
                (string)$trainer->password,
                (string)$trainer->specialization
            );
        }
    }
}
