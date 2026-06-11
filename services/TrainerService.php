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
    public static function createTrainer(
        $firstName,
        $lastName,
        $email,
        $specialization
    )
    {
        global $pdo;
        $pdo->beginTransaction();

        try{

            $passwordHash = password_hash('hash_12345', PASSWORD_DEFAULT);

            $query = "
            INSERT INTO USERS
            (
                first_name,
                last_name,
                email,
                password_hash,
                role
            )
            VALUES
            (
                :first_name,
                :last_name,
                :email,
                :password_hash,
                'trainer'
            )
        ";

            $stmt = $pdo->prepare($query);

            $stmt->execute([

                ':first_name' =>
                    $firstName,

                ':last_name' =>
                    $lastName,

                ':email' =>
                    $email,

                ':password_hash' =>
                    $passwordHash
            ]);

            $userId =
                $pdo->lastInsertId();

            $query = "
            INSERT INTO TRAINERS
            (
                user_id,
                specialization
            )
            VALUES
            (
                :user_id,
                :specialization
            )
        ";

            $stmt = $pdo->prepare($query);

            $stmt->execute([

                ':user_id' =>
                    $userId,

                ':specialization' =>
                    $specialization
            ]);

            $pdo->commit();
            return true;

        }catch(\Exception $ex){

            $pdo->rollBack();
            throw $ex;
        }
    }

    public function importCsv($file)
    {
        if(!$file || !file_exists($file)){

            throw new \Exception(
                'Invalid CSV file'
            );
        }

        $handle =
            fopen(
                $file,
                'r'
            );

        if(!$handle){

            throw new \Exception(
                'Could not open CSV file'
            );
        }

        fgetcsv($handle);

        while(
            ($row = fgetcsv($handle))
            !== false
        ){

            $firstName =
                trim($row[0]);

            $lastName =
                trim($row[1]);

            $email =
                trim($row[2]);

            $specialization =
                trim($row[3]);

            if(
                empty($firstName) ||
                empty($lastName) ||
                empty($email) ||
                empty($specialization)
            ){

                continue;
            }

            if(
                Trainer::existsByEmail(
                    $email
                )
            ){

                continue;
            }

            if(
                !in_array(
                    $specialization,
                    [
                        'fitness',
                        'strength',
                        'physiotherapy'
                    ]
                )
            ){

                throw new \Exception(
                    'Invalid specialization'
                );
            }

            Trainer::createTrainer(

                $firstName,

                $lastName,

                $email,

                $specialization
            );
        }

        fclose($handle);
    }

    public function importXml($file)
    {
        if(!$file || !file_exists($file)){

            throw new \Exception(
                'Invalid XML file'
            );
        }

        $xml =
            simplexml_load_file(
                $file
            );

        if(!$xml){

            throw new \Exception(
                'Invalid XML structure'
            );
        }

        foreach($xml->trainer as $trainer){

            $firstName =
                trim(
                    (string)$trainer->first_name
                );

            $lastName =
                trim(
                    (string)$trainer->last_name
                );

            $email =
                trim(
                    (string)$trainer->email
                );

            $specialization =
                trim(
                    (string)$trainer->specialization
                );

            if(
                empty($firstName) ||
                empty($lastName) ||
                empty($email) ||
                empty($specialization)
            ){

                continue;
            }

            if(
                Trainer::existsByEmail(
                    $email
                )
            ){

                continue;
            }

            if(
                !in_array(
                    $specialization,
                    [
                        'fitness',
                        'strength',
                        'physiotherapy'
                    ]
                )
            ){

                throw new \Exception(
                    'Invalid specialization'
                );
            }

            Trainer::createTrainer(

                $firstName,
                $lastName,
                $email,
                $specialization
            );
        }
    }
}
