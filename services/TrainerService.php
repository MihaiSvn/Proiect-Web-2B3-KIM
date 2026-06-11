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
}
