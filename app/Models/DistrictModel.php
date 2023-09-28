<?php

namespace App\Models;

use CodeIgniter\Model;

class DistrictModel extends Model
{
    protected $table = 'districts';
    protected $returnType = 'App\Entities\District';
    protected $useSoftDeletes = true;
    protected $allowedFields = [
        'name',
        'slug',
        'city',
        'delivery_value',
        'active',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'modified_at';
    protected $deletedField = 'deleted_at';

    // Validation
    // Validation
    // Regras de validações
    protected $validationRules = [
        'name' => 'required|min_length[4]|max_length[50]|is_unique[districts.name]',
        'cep' => 'required|exact_length[9]',
        'delivery_value' => 'required|exact_length[9]',
    ];
    protected $validationMessages = [
        'name' => [
            'required' => 'O campo nome é obrigatório.',
            'is_unique' => 'Esse bairro já existe',
        ],
        'delivery_value' => [
            'required' => 'O valor de entrega é obrigatório.',
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
     * @uso Controller District no método search com o autocomplete
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
      * @uso Desfazer a exclusão da category
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
