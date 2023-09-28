<?php

namespace App\Models;

use CodeIgniter\Model;

class DeliverymenModel extends Model
{
    protected $table = 'delivery_mens';
    protected $returnType = 'App\Entities\Deliverymen';
    protected $useSoftDeletes = true;
    protected $allowedFields = [
        'name',
        'cpf',
        'cnh',
        'email',
        'phone_celular',
        'image',
        'active',
        'vehicle',
        'vehicle_plate',
        'address',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'modified_at';
    protected $deletedField = 'deleted_at';

    // Regras de validações
    protected $validationRules = [
        'name' => 'required|alpha_numeric_space|min_length[4]|max_length[50]',
        'email' => 'required|valid_email|is_unique[delivery_mens.email]',
        'cpf' => 'required|is_unique[delivery_mens.cpf]|exact_length[14]|validateCpf',
        'cnh' => 'required|is_unique[delivery_mens.cnh]|exact_length[11]',
        'phone_celular' => 'required|exact_length[15]|is_unique[delivery_mens.phone_celular]',
        'address' => 'required|max_length[190]',
        'vehicle' => 'required|min_length[10]',
        'vehicle_plate' => 'required|min_length[7]|min_length[8]|is_unique[delivery_mens.vehicle_plate]',
    ];
    protected $validationMessages = [
        'name' => [
            'required' => 'O campo nome é obrigatório.',
        ],
        'email' => [
            'required' => 'O campo e-mail é obrigatório.',
            'is_unique' => 'Desculpe. Esse email já existe.',
        ],
        'cpf' => [
            'required' => 'O campo cpf é obrigatório.',
            'is_unique' => 'Desculpe. Esse número de cpf já existe.',
        ],
        'phone_celular' => [
            'required' => 'O campo celular é obrigatório.',
        ],
    ];

    /**
     * @uso Controller DeliveryMens, no método search com o autocomplete
     *
     * author: Marcelino
     *
     * @param string $term
     *
     * @return array DeliveryMen
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
     * @uso Desfazer a exclusão do DeliveryMen
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
