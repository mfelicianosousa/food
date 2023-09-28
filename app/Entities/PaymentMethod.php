<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class PaymentMethod extends Entity
{
    protected $datamap = [];
    protected $dates = ['created_at', 'modified_at', 'deleted_at'];
    protected $casts = [];
}
