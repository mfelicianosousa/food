<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Category extends Entity
{

    protected $dates   = [
        'created_at', 
        'modified_at', 
        'deleted_at'];

}
