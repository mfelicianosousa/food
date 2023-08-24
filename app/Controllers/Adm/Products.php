<?php

namespace App\Controllers\Adm;

use App\Controllers\BaseController;
use App\Entities\Product ;

class Products extends BaseController
{
    private $productModel;

    public function __construct(){

        $this->productModel = new \App\Models\ProductModel();

    }

    public function index()
    {
        $data = [
            'title' => 'Lista de produtos',
            'products' => $this->productModel->select('products.*, categories.name as category')
                                            ->join('categories','categories.id = products.category_id')
                                            ->withDeleted(true)
                                            ->paginate(10),
            'pager'=> $this->productModel->pager,
        ];

        return view('adm/products/index',$data);
    }
}
