<?php

namespace App\Controllers\Adm;

use App\Controllers\BaseController;

class Districts extends BaseController
{
    private $districtModel;
    private $pagination = 10;

    public function __construct()
    {
        $this->districtModel = new \App\Models\DistrictModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Listando os Bairros Atendidos',
            'districts' => $this->districtModel->withDeleted(true)->paginate($this->pagination),
            'pager' => $this->districtModel->pager,
        ];

        return view('Adm/Districts/index', $data);
    }
}
