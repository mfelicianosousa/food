<?php

namespace App\Models;

use CodeIgniter\Model;

class ExtraModel extends Model
{
    protected $table = 'extras';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'App\Entities\Extra';

    protected $allowedFields = ['name', 'description', 'price', 'active'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'modified_at';

    protected $useSoftDeletes = true; // Não excluir o registro definitivo
    protected $deletedField = 'deleted_at'; // Nome da coluna no banco de dados

    // Validation -Regras de validações
    protected $validationRules = [
        'name' => 'required|min_length[4]|max_length[50]|is_unique[extras.name]',
    ];
    protected $validationMessages = [
        'name' => [
            'required' => 'O campo nome é obrigatório.',
            'is_unique' => 'Esse adicional já existe',
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
     * @uso Controller Extras no método search com o autocomplete
     *
     * author: Marcelino
     *
     * @param string $term
     *
     * @return array extras
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
      * @uso Desfazer a exclusão do Additional (Extra)
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
