<?php

namespace App\Controllers\Adm;

use App\Controllers\BaseController;
use App\Entities\Product;


class Products extends BaseController
{
    private $productModel;
    private $categoryModel;
    private $extraModel;
    private $productExtraModel;

    public function __construct()
    {
        $this->productModel = new \App\Models\ProductModel();
        $this->categoryModel = new \App\Models\CategoryModel();
        $this->extraModel = new \App\Models\ExtraModel();
        $this->productExtraModel = new \App\Models\productExtraModel();
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
        // -- A partir desse ponto fazemos o store da image --//
        /* fazendo o store da imagem */
        $pathImage = $image->store('products');
        $pathImage = WRITEPATH.'uploads/'.$pathImage;
        /* fazendo o fit( recsize)da imagem */
        service('image')->withFile($pathImage)
                        ->fit(400, 400, 'center')
                        ->save($pathImage);

        /** Recuperando a imagem antiga, para exclui-la */
        $oldImage = $product->image;

        /* Atribuindo a nova imagem */
        $product->image = $image->getname();
        /* Atualizando a image do product */
        $this->productModel->save($product);

        /** Definindo o caminho da imagem antiga, para ser excluida */
        $pathImage = WRITEPATH.'uploads/products'.$oldImage;

        if (is_file($pathImage)) {
            unlink($pathImage);
        }

        return redirect()->to(site_url("adm/products/show/$product->id"))
                         ->with('success', 'Imagem alterada com sucesso');
    }
   
 
    // Apresenta a imagem na tela de produto

    public function image(string $image = null)
    {
       
        if ($image) {

            $imagePath = WRITEPATH."uploads\products".DIRECTORY_SEPARATOR.$image;

            $imageInfo = new \finfo( FILEINFO_MIME );

            echo "<pre>";
            print_r($imageInfo) ;
            echo "</pre>";
            exit;
            //$imageInfo = new Finfo(FILEINFO_MIME_TYPE);
            //$imageInfo = new finfo();
            //$imageInfo->set_flags(FILEINFO_MIME_TYPE);
            $imageType = $imageInfo->file($imagePath);
            header("Content-Type: $imageType");
            header("Content-Length: ".filesize($imagePath));
            readfile($imagePath);
            exit;
        }
    }

 /**
     * Apresenta o produto selecionado na tela.
     *
     * @param int $id
     *
     * @return object $product
     */
    public function extras($id = null)
    {
        $product = $this->findProductOr404($id);

        // dd($extra);

        $data = [
         'title' => "Gerenciar os extras do Produto: $product->name",
         'product' => $product,
         'extras' => $this->extraModel->where('active',true)->findAll(),
         'productsExtras' => $this->productExtraModel->findExtrasProduct($product->id),
        ];

        dd($data['productsExtras']);
        
        return view('adm/Products/extras', $data);
    }


    public function update($id = null)
    {
        if ($this->request->getMethod() === 'post') {
            $product = $this->findProductOr404($id);

            $product->fill($this->request->getPost());

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
