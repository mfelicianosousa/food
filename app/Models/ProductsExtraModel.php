<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductsExtraModel extends Model
{
    protected $table            = 'products_extras';
    protected $returnType       = 'object';
    protected $allowedFields    = ['product_id','extra_id'];

      // Regras de validações
      protected $validationRules = [
        'extra_id'           => 'required|integer',
    ];
    protected $validationMessages = [
        'extra_id' => [
            'required' => 'O campo extra é obrigatório.',
        ],
    ];


}
