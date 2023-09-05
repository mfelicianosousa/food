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
    private $measureModel;
    private $productSpecificationModel;

    public function __construct()
    {
        $this->productModel = new \App\Models\ProductModel();
        $this->categoryModel = new \App\Models\CategoryModel();
        $this->extraModel = new \App\Models\ExtraModel();
        $this->measureModel = new \App\Models\MeasureModel();
        $this->productExtraModel = new \App\Models\productExtraModel();
        $this->productSpecificationModel = new \App\Models\ProductSpecificationModel();
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

        if ( $product->deleted_at != null){

            return redirect()->back()->with('info', "Não é possivel editar a imagem de um producto excluído");
 
        }

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
        $pathImage = WRITEPATH.'uploads/products/'.$oldImage;

        if (is_file($pathImage)) {
            unlink($pathImage);
        }

        return redirect()->to(site_url("adm/products/show/$product->id"))
                         ->with('success', 'Imagem alterada com sucesso');
    }

    // Apresenta a imagem na tela de produto

    public function image(string $image = null)
    {
        dd($image);

        if ($image) {

            $imagePath = WRITEPATH."uploads\products" . DIRECTORY_SEPARATOR . $image;

            $imageInfo = new \finfo(FILEINFO_MIME);

            echo '<pre>';
            print_r($imagePath);
            echo '</pre>';
            exit;
            // $imageInfo = new Finfo(FILEINFO_MIME_TYPE);
            // $imageInfo = new finfo();
            // $imageInfo->set_flags(FILEINFO_MIME_TYPE);
            $imageType = $imageInfo->file($imagePath);
            header("Content-Type: $imageType");
            header('Content-Length: '.filesize($imagePath));
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
         'moeda' => 'R$',
         'product' => $product,
         'extras' => $this->extraModel->where('active', true)->findAll(),
         'productExtras' => $this->productExtraModel->findExtrasProduct($product->id, 10),
         'pager' => $this->productExtraModel->pager,
        ];

        return view('adm/Products/extra', $data);
    }

    public function registerExtras($id = null)
    {
        if ($this->request->getMethod() === 'post') {
            $product = $this->findProductOr404($id);

            $extraProduct['extra_id'] = $this->request->getPost('extra_id');
            $extraProduct['product_id'] = $product->id;

            $extraExist = $this->productExtraModel->where('product_id', $product->id)
                                                  ->where('extra_id', $extraProduct['extra_id'])
                                                  ->first();
            if ($extraExist) {
                return redirect()->back()->with('attention', 'Esse extra já existe para esse produto.');
            }

            // dd($extraProduct);

            if ($this->productExtraModel->save($extraProduct)) {
                return redirect()->back()->with('success', 'Extra atribuido com sucesso');
            } else {
                // erros de validação

                return redirect()->back()
                                ->with('errors_model', $this->productExtraModel->errors())
                                ->with('attention', 'Por favor verifique os erros abaixo')
                                ->withInput();
            }
        } else {
            /* Não é o método post */
            return redirect()->back();
        }
    }

    public function registerSpecification($id = null)
    {
        if ($this->request->getMethod() === 'post') {
            $product = $this->findProductOr404($id);

           // dd($this->request->getPost());

            $specificationProduct['product_id'] = $product->id;
            $specificationProduct['measure_id'] = $this->request->getPost('measure_id');
            $specificationProduct['price'] = str_replace(",","",$this->request->getPost('price'));
            $specificationProduct['customizable'] = $this->request->getPost('customizable');

            $specificationExist = $this->productSpecificationModel
                                       ->where('product_id', $product->id)
                                       ->where('measure_id', $specificationProduct['measure_id'])
                                       ->first();
            if ($specificationExist) {
                return redirect()->back()->with('attention', 'Essa especificação já existe para esse produto.');
            }

            // dd($extraProduct);

            if ($this->productSpecificationModel->save($specificationProduct)) {
                return redirect()->back()->with('success', 'Especificação atribuida com sucesso');
            } else {
                // erros de validação

                return redirect()->back()
                                ->with('errors_model', $this->productSpecificationModel->errors())
                                ->with('attention', 'Por favor verifique os erros abaixo')
                                ->withInput();
            }
        } else {
            /* Não é o método post */
            return redirect()->back();
        }
    }

    /**
     * delete_extra function
     * Deletar o registro extra do produto.
     *
     * @param [type] $main_id
     * @param [type] $product_id
     *
     * @return void
     */
    public function delete_extra($main_id = null, $product_id)
    {
        if ($this->request->getMethod() === 'post') {
            $product = $this->findProductOr404($product_id);

            $productExtra = $this->productExtraModel
                                 ->where('id', $main_id)
                                 ->where('product_id', $product->id)
                                 ->first();
            if (!$productExtra) {
                return redirect()->back()->with('attention', 'Não encontramos o registro principal');
            }

            $this->productExtraModel->delete($main_id);

            return redirect()->back()->with('success', 'Extra excluido com sucesso.');
        } else {
            /* Não é o método post */
            return redirect()->back();
        }
    }
    /**
     * delete_specification function
     * Deletar o registro de especificacao do produto.
     *
     * @param [type] $main_id
     * @param [type] $product_id
     *
     * @return void
     */
         
    public function delete_specification($main_id = null, $product_id = null)
    {
        $product = $this->findProductOr404($product_id);
        $specification = $this->productSpecificationModel
                                     ->where('id',$main_id)
                                     ->where('product_id',$product->id)
                                     ->first();
        if (!$specification){

            return redirect()->back()->with('attention','Não encontramos a especificação do produto');
        }
        if($this->request->getMethod()==='post'){

            $this->productSpecificationModel->delete($specification->id);
            return redirect()->to(site_url("adm/products/specification/$product->id"))->with('success', 'Especificação excluido com sucesso.');
        }

        $data = [

            'title' => 'Exclusão de especificação do produto',
            'specificationProduct' =>$specification,
        ];    

        return view('Adm/Products/delete_specification', $data);
    }

     /**
     * delete_product function
     * Deletar o registro do produto.
     *
     * @param [type] $id
     *
     * @return void
     */
    public function delete_product($id = null){

        $product = $this->findProductOr404( $id );

        if($this->request->getMethod()==='post'){

            $this->productModel->delete($id);

            // product tem imagem
            if($product->image){

                $pathImage = WRITEPATH . 'uploads/products/' . $product->image; 
               //dd($pathImage);
                if (is_file($pathImage)){

                    unlink($pathImage);
                }
                //
            }
            // Atualiza o produto tornando a image null
            $product->image = null;
            $this->productModel->save($product);

            return redirect()->to(site_url("adm/products"))->with('success','Produto excluido com sucesso');

        }
        

        $data = [
            'title' => "Excluindo o produto $product->name",
            'product'=> $product
        ];

        return view('Adm/Products/delete_product', $data);

    }

     /**
     * undoDelete function
     * 
     * Desfazer a exclusão do product no database (softdelete)
     *
     * @param int $id
     * @return object $user
     */
    public function undoDelete($id = null){

        $product = $this->findProductOr404( $id );

        if ( $product->deleted_at == null){

            return redirect()->back()->with('info', "Apenas produtos excluídos podem ser recuperados!");
 
        }

        if ($this->productModel->undoDelete( $id )){

            return redirect()->back()->with('success','Exclusão desfeita com sucesso.');
     
        } else {

            return redirect()->back()
                             ->with('errors_model',$this->productModel->errors())
                             ->with('info', 'Por favor verifique os erros abaixo')
                             ->withInput();

        }
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
     * View especification product.
     *
     * @param int $id
     *
     * @return object $product
     */
    public function specification($id = null)
    {
        $product = $this->findProductOr404($id);

        // dd($extra);

        $data = [
         'title' => "Gerenciar as especificações do Produto: $product->name",
         'moeda' => 'R$',
         'product' => $product,
         'measurements' => $this->measureModel->where('active', true)->findAll(),
         'productSpecifications' => $this->productSpecificationModel->findSpecificationProduct($product->id, 10),
         'pager' => $this->productSpecificationModel->pager,
        ];

        return view('adm/Products/specification', $data);
    }

      /**
       * findProductOr404() function
       * Search products in table 
       *        
       * @param int $id
       *
       * @return object $product
       */
      private function findProductOr404(int $id = null)
      {

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
