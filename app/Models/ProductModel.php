<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'products';

    protected $returnType = 'App\Entities\Product';

    protected $allowedFields = [
        'category_id',
        'name',
        'slug',
        'ingredients',
        'image',
        'active',
    ];

    // Dates

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'modified_at';
    protected $useSoftDeletes = true;
    protected $deletedField = 'deleted_at';

    // Validation
    // Regras de validações
    protected $validationRules = [
        'name' => 'required|min_length[4]|max_length[120]|is_unique[product.name]',
        'category_id' => 'required|integer',
        'ingredients' => 'required|min_length[10]|max_length[1000]',
    ];
    protected $validationMessages = [
        'name' => [
            'required' => 'O campo nome é obrigatório.',
            'is_unique' => 'Esse produto já existe',
        ],
        'category_id' => [
            'required' => 'O campo Categoria é obrigatório.',
        ],
    ];

    // Eventos de callback
    protected $beforeInsert = ['createSlug'];
    protected $beforeUpdate = ['createSlug'];

    /**
     * Cria o Slug do nome.
     *
     * @return $data
     */
    protected function createSlug(array $data)
    {
        if (isset($data['data']['name'])) {
            $data['data']['slug'] = mb_url_title($data['data']['name'], '-', true);
        }

        return $data;
    }

    /**
     * @uso Controller Produto no método search com o autocomplete
     *
     * author: Marcelino
     *
     * @param string $term
     *
     * @return array categories
     */
    public function search($term)
    {
        if ($term === null) {
            return [];
        }

        return $this->select('id, name')
                    ->like('name', $term)
                    ->withDeleted(true)
                    ->get()
                    ->getResult();
    }

     /**
      * @uso Desfazer a exclusão do Produto
      *
      * author: Marcelino
      *
      * @return void
      */
     public function undoDelete(int $id)
     {
         return $this->protect(false)
                         ->where('id', $id)
                         ->set('deleted_at', null)
                         ->update();
     }
}
