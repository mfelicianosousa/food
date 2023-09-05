<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductSpecificationModel extends Model
{
    protected $table = 'products_specifications';
    protected $returnType = 'object';

    protected $allowedFields = [
        'product_id',
        'measure_id',
        'price',
        'customizable',
    ];

    // Regras de validações
    protected $validationRules = [
      'measure_id' => 'required|integer',
      'price' => 'required|greater_than[0]',
      'customizable' => 'required|integer',
    ];
    protected $validationMessages = [
        'measure_id' => [
            'required' => 'O campo de medida é obrigatório.',
        ],
        'price' => [
            'required' => 'O campo preço é obrigatório.',
        ],
        'customizable' => [
            'required' => 'O campo produto customizado é obrigatório.',
        ],
    ];

    /**
     * @description Retorna as especificações do produto
     *
     * @uso Adm/Product/especification ($id = null)
     *
     * @param int $product_id
     *
     * @return array object
     */
    public function findSpecificationProduct(int $product_id = null, int $quantity_pagination = 5)
    {
        return $this->select('measurements.name as measure, products_specifications.*')
                    ->join('measurements', 'measurements.id = products_specifications.measure_id')
                    ->join('products', 'products.id = products_specifications.product_id')
                    ->where('products_specifications.product_id', $product_id)
                    ->paginate($quantity_pagination);
    }
}
