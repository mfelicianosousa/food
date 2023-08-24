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

    public function search()
    {
        if (!$this->request->isAJAX()) {
            exit('Página não encontrada!');
        }
        $products = $this->productModel->search($this->request->getGet('term'));

        $return = []; // variavel de retorno

        foreach ($products as $product) {
            $data['id'] = $product->id;
            $data['value'] = $product->name;
            $return[] = $data;
        }

        return $this->response->setJSON($return);
    }

/**
     * Apresenta na tela o product selecionado.
     *
     * @param int $id
     *
     * @return object $product
     */
    public function show($id = null)
    {
        $product = $this->findProductOr404($id);

        // dd($extra);

        $data = [
         'title' => "Detalhe do Produto: $product->name",
         'product' => $product,
        ];

        return view('adm/Products/show', $data);
    }



    /**
      * Pesquisa Produtos no database.
      *
      * @param int $id
      * @return object $product
      */
      private function findProductOr404(int $id = null)
      {
          // ->withDeleted(true) Pesquisar os extras deletados
          if (!$id || !$product = $this->productModel->select('products.*, categories.name as category')
                                                     ->join('categories','categories.id = products.category_id')
                                                     ->withDeleted(true)
                                                     ->first()) {
              throw \CodeIgniter\Exceptions\PageNotFoundException::ForPageNotFound("Não encontramos o produto $id");
          }
 
          return $product;
      }
}
