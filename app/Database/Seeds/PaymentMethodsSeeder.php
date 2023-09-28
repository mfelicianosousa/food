<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PaymentMethodsSeeder extends Seeder
{
    public function run()
    {
        $paymentMethodsModel = new \App\Models\PaymentMethodsModel();
        $paymentMethods = [
           'name' => 'Dinheiro',
           'activo' => true,
        ];
        $paymentMethodsModel->skipValidation(true)->insert($paymentMethods);
        dd($paymentMethodsModel->errors());
    }
}
