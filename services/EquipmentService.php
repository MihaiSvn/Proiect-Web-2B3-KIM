<?php

namespace services;

use models\Equipment;

class EquipmentService
{
    public function createEquipment($roomId, $name, $isFunctional)
    {
        $name = trim($name);

        if (empty($name)) {
            throw new \Exception("Equipment name cannot be empty.");
        }

        if (empty($roomId)) {
            throw new \Exception("You must assign the equipment to a valid room.");
        }

        if ($isFunctional !== '0' && $isFunctional !== '1' && $isFunctional !== 0 && $isFunctional !== 1) {
            throw new \Exception("Invalid functional status.");
        }

        $success = Equipment::create($roomId, $name, (int)$isFunctional);

        if (!$success) {
            throw new \Exception("Failed to add equipment. Please ensure the selected room exists.");
        }

        return true;
    }
}
