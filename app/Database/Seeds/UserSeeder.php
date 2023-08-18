<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $userModel = new \App\Models\UserModel;
        $user = [ 
            'name' => 'Marcelino Felicino de Sousa',
            'cpf' => '07756074874',
            'user' => 'marcelino.sousa@gmail.com',
            'email' => 'marcelino.feliciano@outlook.com',
            'celular_number' => '65981413390',
        ];
        $userModel->protect(false)->insert($user);

        $user = [ 
            'name' => 'Ana Paula dos Santos Feliciano',
            'cpf' => '61604166110',
            'user' => 'anap@gmail.com',
            'email' => 'anap@admin.com',
            'celular_number' => '65992034218',
        ];
        $userModel->protect(false)->insert($user);
        dd($userModel->errors());
    }
}
