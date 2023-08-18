<?php

namespace App\Controllers\Adm;

use App\Controllers\BaseController;

class Home extends BaseController
{
    public function index() { 

        $data = [ 
        'title'=> 'Home da Área Restrita',
        ];

        return view('Adm/Home/index', $data);
   
    }
    
}
