<?php

namespace App\Controllers\Adm;

use App\Controllers\BaseController;

use App\Entities\Category;

class Categories extends BaseController
{
    private $categoryModel;
    private $showDeleted = true ;
    private $showPage = 5;

    public function __construct(){


        $this->categoryModel = new \App\Models\CategoryModel();
       
    }

    public function index()
    {
        $data = [
            'title' => 'Listando as categorias',
            'categories' => $this->categoryModel->withDeleted( $this->showDeleted )->paginate($this->showPage),
            'pager' =>$this->categoryModel->pager
        ];

        return view('Adm/Categories/index',$data);
    }



    public function search(){

        if(!$this->request->isAJAX()){
        
            exit('Página não encontrada!');
        
        }
        $categories = $this->categoryModel->search($this->request->getGet('term'));

        $return = []; // variavel de retorno

        foreach( $categories as $category){
            $data['id'] = $category->id;
            $data['value'] = $category->name;
            $return[] = $data;

        }
        return $this->response->setJSON($return);
       
    }

    /**
     * 
     * Criar uma nova categoria
     *
     * @param int $id
     * @return object $category
     */
    public function create(){

        $category = new Category();

        $data = [
         'title'=>"Cadastrando nova categoria",
         'category' => $category,
        ];
 
        return view('adm/Categories/create', $data);
 
     }

     /**
      * 
      * Cadastrar dados do formulário no database 
      *
      */
      public function cadastrar() {


        if($this->request->getPost()) {
        
            $category = new Category($this->request->getPost());

            if( $this->categoryModel->save($category)) {

               return redirect()->to(site_url("adm/categories/show/".$this->categoryModel->getInsertID()))
                                ->with('success',"Categoria <strong>'$category->name'</strong>, cadastrada com sucesso"); 
            } else {
                return redirect()->back()
                                 ->with('errors_model',$this->categoryModel->errors())
                                 ->with('info', 'Por favor verifique os erros abaixo')
                                 ->withInput();
            }
        } else {
            /* Não é post */
           // return redirect()->back()->with('info','Por favor envie um POST');
            return redirect()->back();
        }
      }

    /**
     * 
     * Excluir o usuário no database (softdelete)
     *
     * @param int $id
     * @return object $user
     */
    public function excluir($id = null){

        $category = $this->findCategoryOr404( $id );

        if ($category->deleted_at != null){

            return redirect()->back()->with('info', "A categoria, <strong>'$category->name'</strong> já encontra-se excluída.");

        }

       
        if ($this->request->getMethod() === 'post') {
            
            $this->categoryModel->delete( $id );

            return redirect()->to(site_url('adm/categories'))
                             ->with('success', "Categoria $category->name, excluído com sucesso");

        }
 
        $data = [
         'title'=>"Excluindo a categoria: $category->name",
         'category' => $category
        ];
 
        return view('adm/Categories/excluir', $data);
 
     }

     /**
     * 
     * Desfazer a exclusão do usuário no database (softdelete)
     *
     * @param int $id
     * @return object $user
     */
    public function undoDelete($id = null){

        $category = $this->findCategoryOr404( $id );

        if ( $category->deleted_at == null){

            return redirect()->back()->with('info', "Apenas categorias excluídos podem ser recuperados!");
 
        }

        if ($this->categoryModel->undoDelete( $id )){

            return redirect()->back()->with('success','Exclusão desfeita com sucesso.');
     
        } else {

            return redirect()->back()
                             ->with('errors_model',$this->categoryModel->errors())
                             ->with('info', 'Por favor verifique os erros abaixo')
                             ->withInput();

        }
     }


    /**
     * 
     * Apresenta a categoria na tela
     *
     * @param int $id
     * @return object $category
     */
    public function show($id = null){

        $category = $this->findCategoryOr404( $id );
 
       // dd($category);
 
        $data = [
         'title'=>"Detalhes da Categoria: $category->name",
         'category' => $category
        ];
 
        return view('adm/Categories/show', $data);
 
     }

     /**
     * 
     * Pesquisa Categoria no banco de dados 
     *
     * @param int $id
     * @return object $category
     */

     private function findCategoryOr404(int $id = null){

        // ->withDeleted(true) Pesquisar as categorias deletados
        if (!$id || ! $category = $this->categoryModel->withDeleted(true)->where('id', $id)->first()){

            throw \CodeIgniter\Exceptions\PageNotFoundException::ForPageNotFound("Não encontramos a categoria $id");
        }
        return $category;
    }


    /**
     * 
     * Editar o usuário na tela
     *
     * @param int $id
     * @return object $category
     */
    public function edit($id = null){

        $category = $this->findCategoryOr404( $id );

        if ($category->deleted_at != null){

            return redirect()->back()->with('info', "A categoria, <strong>'$category->name'</strong> encontra-se excluído. Portanto, não é possivel editá-la.");

        }
 
       // dd($category);
 
        $data = [
         'title'=>"Editando o usuário: $category->name",
         'category' => $category
        ];
 
        return view('adm/Categories/edit', $data);
 
     }

     /**
      * Atualizar os dados do formulário 
      */
      public function update( $id = null ) {

        // $this->request->getMethod() == 'post') deprecated
        if($this->request->getPost()) {
        
            $category = $this->findCategoryOr404( $id );

            if ($category->deleted_at != null){

                return redirect()->back()->with('info', "A categória, <strong>'$category->name'</strong> encontra-se excluída. Portanto não é possivel exclui-lo");
    
            }

            // Preparar os dados e enviar para o database
            $category->fill($this->request->getPost());

            // Não houve alteração nos dados, não atualiza o database
            if (!$category->hasChanged()){

                return redirect()->back()->with('warning','Não há dados para atualizar!');

            }
         

            if( $this->categoryModel->save($category)) {

               return redirect()->to(site_url("adm/categories/show/$category->id"))
                                ->with('success',"Categoria <strong>'$category->name'</strong>, atualizada com sucesso"); 
            } else {
                return redirect()->back()->with('errors_model',$this->categoryModel->errors())
                                         ->with('info', 'Por favor verifique os erros abaixo')
                                         ->withInput();
            }

        
        } else {
            /* Não é post */
           // return redirect()->back()->with('info','Por favor envie um POST');
            return redirect()->back();
        }


      }

}
