<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentMethodsModel extends Model
{
    protected $table = 'payment_methods';
    protected $returnType = 'App\Entities\PaymentMethod';

    protected $allowedFields = ['name', 'active'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'modified_at';

    protected $useSoftDeletes = true; // Não excluir o registro definitivo
    protected $deletedField = 'deleted_at'; // Nome da coluna no banco de dados

    // Validation -Regras de validações
    protected $validationRules = [
        'name' => 'required|min_length[4]|max_length[50]|is_unique[payment_methods.name]',
    ];
    protected $validationMessages = [
        'name' => [
            'required' => 'O campo nome é obrigatório.',
            'is_unique' => 'Esse nome já existe',
        ],
    ];

    /**
     * @uso Controller PaymentMethods no método search com o autocomplete
     *
     * author: Marcelino
     *
     * @param string $term
     *
     * @return array objects
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
      * @uso Desfazer a exclusão
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
