<?php

namespace services;

use models\Trainer;
class TrainerService
{
    public function getTrainerByUserId($userId){
        return Trainer::findTrainerByUserId($userId);
    }
}