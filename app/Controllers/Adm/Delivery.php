<?php

namespace App\Controllers\Adm;

use App\Controllers\BaseController;

class Delivery extends BaseController
{
    private $deliveryMenModel;
    private $page = 10;

    public function __construct()
    {
        $this->deliveryMenModel = new \App\Models\DeliveryMenModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Listando os Entregadores',
            'deliveryMens' => $this->deliveryMenModel->withDeleted(true)->paginate($this->page),
            'pager' => $this->deliveryMenModel->page,
        ];

        return view('Adm/Deliverymens/index', $data);
    }
}
