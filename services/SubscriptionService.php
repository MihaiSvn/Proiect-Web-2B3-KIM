<?php

namespace services;

use models\Subscription;

class SubscriptionService
{
    public function getByType($type)
    {
        return Subscription::findByType($type);
    }

    public function getById($id)
    {
        return Subscription::findById($id);
    }

    public function getAll()
    {
        return Subscription::findAll();
    }
}