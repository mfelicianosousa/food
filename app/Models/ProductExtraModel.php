<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductExtraModel extends Model
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

    /**
     * @descriction Recupere os extras do produto
     * @uso controller Adm/Products/extra( $id = null)
     * @param int $product_id
     * @return Type 
     */
    public function findExtrasProduct(int $product_id = null){

        return $this->select('extras.name AS extra, products_extras.*')
                    ->join('extras','extras.id = products_extras.extra_id')
                    ->join('products','products.id = products_extras.product_id')
                    ->where('products_extras.product_id',$product_id)
                    ->findAll();
    }

}
