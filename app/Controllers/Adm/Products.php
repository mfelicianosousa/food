<?php

namespace App\Controllers\Adm;

use App\Controllers\BaseController;
use App\Entities\Product;

class Products extends BaseController
{
    private $productModel;
    private $categoryModel;

    public function __construct()
    {
        $this->productModel = new \App\Models\ProductModel();
        $this->categoryModel = new \App\Models\CategoryModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Lista de produtos',
            'products' => $this->productModel->select('products.*, categories.name as category')
                                            ->join('categories', 'categories.id = products.category_id')
                                            ->withDeleted(true)
                                            ->paginate(10),
            'pager' => $this->productModel->pager,
        ];

        return view('adm/products/index', $data);
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
     * Cria um novo produto.
     *
     * @param int $id
     *
     * @return object $product
     */
    public function create()
    {
        $product = new Product();

        // dd($extra);

        $data = [
         'title' => 'Criando novo produto',
         'product' => $product,
         'categories' => $this->categoryModel->where('active', true)->findAll(),
        ];

        return view('adm/Products/create', $data);
    }

    /**
     * Apresenta o produto selecionado na tela.
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
     * Editar o produto selecionado.
     *
     * @param int $id
     *
     * @return object $product
     */
    public function edit($id = null)
    {
        $product = $this->findProductOr404($id);

        // dd($extra);

        $data = [
         'title' => "Edição do Produto: $product->name",
         'product' => $product,
         'categories' => $this->categoryModel->where('active', true)->findAll(),
        ];

        return view('adm/Products/edit', $data);
    }

    public function editImage(int $id = null)
    {
        $product = $this->findProductOr404($id);

        $data = [
         'title' => "Editando imagem do Produto: $product->name",
         'product' => $product,
        ];

        return view('adm/Products/edit_image', $data);
    }

    public function upload(int $id = null)
    {
        $product = $this->findProductOr404($id);

        $image = $this->request->getFile('image_product');

        if (!$image->isValid()) {
            $codeError = $image->getError();

            if ($codeError == UPLOAD_ERR_NO_FILE) {
                return redirect()->back()->with('info', 'Nenhum arquivo foi selecionado');
            }
        }
        $sizeImage = $image->getSizeByUnit('mb');

        if ($sizeImage > 2) {
            return redirect()->back()->with('info', 'O arquivo selecionado é muito grande. Máximo permitido é: 2MB');
        }

        // dd($image);
        $typeImage = explode('/', $image->getMimeType());
        $allowedTypes = ['jpeg', 'jpg', 'png', 'webp'];

        if (!in_array($typeImage[1], $allowedTypes)) {
            return redirect()->back()->with('info', 'O arquivo não tem o formato permitido. Apenas '.implode(', ', $allowedTypes));
        }

        list($width, $height) = getimagesize($image->getPathname());

        if ($width < '400' || $height < '400') {
            return redirect()->back()->with('info', 'A imagem não pode ser menor que 400 x 400 pixels');
        }

        dd($image);
    }

    public function update($id = null)
    {
        if ($this->request->getMethod() === 'post') {
            $product = $this->findProductOr404($id);

            $product->fill($this->request->getPost());

            // echo "<pre>";
            // print_r( $_POST ) ;
            // print_r( $product ) ;
            // echo "</pre>";
            // exit;

            if (!$product->hasChanged()) {
                return redirect()->back()->with('info', 'Não há dados para atualizar');
            }

            // echo "<pre>";
            // print_r( $product) ;
            // echo "</pre>";
            // exit;

            if ($this->productModel->save($product)) {
                return redirect()->to(site_url("adm/products/show/$id"))->with('success', 'Produto atualizado com sucesso');
            } else {
                // erros de validação

                return redirect()->back()
                                 ->with('errors_model', $this->productModel->errors())
                                 ->with('info', 'Por favor verifique os erros abaixo')
                                 ->withInput();
            }
        } else {
            return redirect()->back();
        }
    }

    public function register()
    {
        if ($this->request->getMethod() === 'post') {
            $product = new Product($this->request->getPost());

            // echo "<pre>";
            // print_r( $_POST ) ;
            // print_r( $product ) ;
            // echo "</pre>";
            // exit;

            if ($this->productModel->save($product)) {
                return redirect()->to(site_url('adm/products/show/').$this->productModel->getInsertID())
                        ->with('success', "Produto $product->name, cadastrado com sucesso");
            } else {
                // erros de validação

                return redirect()->back()
                                 ->with('errors_model', $this->productModel->errors())
                                 ->with('info', 'Por favor verifique os erros abaixo')
                                 ->withInput();
            }
        } else {
            return redirect()->back();
        }
    }

      /**
       * Pesquisa Produtos no database.
       *
       * @param int $id
       *
       * @return object $product
       */
      private function findProductOr404(int $id = null)
      {
          // ->withDeleted(true) Pesquisar os extras deletados

          if (!$id || !$product = $this->productModel->select('products.*, categories.name as category')
                                                     ->join('categories', 'categories.id = products.category_id')
                                                     ->where('products.id', $id)
                                                     ->withDeleted(true)
                                                     ->first()) {
              throw \CodeIgniter\Exceptions\PageNotFoundException::ForPageNotFound("Não encontramos o produto $id");
          }

          return $product;
      }
}
