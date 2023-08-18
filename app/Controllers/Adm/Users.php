<?php

namespace App\Controllers\Adm;

use App\Controllers\BaseController;

use App\Entities\User;

class Users extends BaseController
{
    private $userModel ;
    private $showDeleted = true ;
    private $showPage = 5;

    public function __construct(){

        $this-> userModel = new \App\Models\UserModel();

    }

    public function index()
    {
        // withDeleted(true) apresenta os registros deletados 
        $data = [
            'title'=>'Lista de usuários',
            //'users' => $this->userModel->withDeleted( $this->showDeleted )->findAll(),
            'users' => $this->userModel->withDeleted( $this->showDeleted )->paginate($this->showPage),
            'pager' => $this->userModel->pager,
        ];
        
       // session()->set('success','Olá Marcelino, que bom que está conosco');
       // session()->remove('success');
        return view('Adm/Users/index', $data);
    }

    public function search(){

        if(!$this->request->isAJAX()){
        
            exit('Página não encontrada!');
        
        }
        $users = $this->userModel->search($this->request->getGet('term'));

        $return = []; // variavel de retorno

        foreach( $users as $user){
            $data['id'] = $user->id;
            $data['value'] = $user->name;
            $return[] = $data;

        }
        return $this->response->setJSON($return);
       
    }


     /**
     * 
     * Criar novo usuário
     *
     * @param int $id
     * @return object $user
     */
    public function create(){

        $user = new User();

        $data = [
         'title'=>"Criando novo usuário",
         'user' => $user,
        ];
 
        return view('adm/Users/create', $data);
 
     }
    /**
     * 
     * Apresenta o usuário na tela
     *
     * @param int $id
     * @return object $user
     */
    public function show($id = null){

       $user = $this->findUserOr404( $id );

      // dd($user);

       $data = [
        'title'=>"Detalhes do Usuário: $user->name",
        'user' => $user
       ];

       return view('adm/Users/show', $data);

    }

    /**
     * 
     * Excluir o usuário no database (softdelete)
     *
     * @param int $id
     * @return object $user
     */
    public function excluir($id = null){

        $user = $this->findUserOr404( $id );

        if ($user->deleted_at != null){

            return redirect()->back()->with('info', "O usuário, <strong>'$user->name'</strong> já encontra-se excluído.");

        }

        if($user->is_admin){

            return redirect()->back()->with('info',"Não é possivel excluir um usuário <b>Administrador</b>");
        }

 
        if ($this->request->getMethod()==='post') {
            
            $this->userModel->delete($id);

            return redirect()->to(site_url('adm/Users'))->with('success', "Usuário $user->name, excluído com sucesso!");

        }
 
        $data = [
         'title'=>"Excluir o usuário: $user->name",
         'user' => $user
        ];
 
        return view('adm/Users/excluir', $data);
 
     }
     
     /**
     * 
     * Desfazer a exclusão do usuário no database (softdelete)
     *
     * @param int $id
     * @return object $user
     */
    public function undoDelete($id = null){

        $user = $this->findUserOr404( $id );

        if ( $user->deleted_at == null){

           
            return redirect()->back()->with('info', "Apenas usuário excluídos podem ser recuperados!");
 
        }

        if ($this->userModel->undoDelete( $id )){

            return redirect()->back()->with('success','Exclusão desfeita com sucesso.');
     
        } else {

            return redirect()->back()
                             ->with('errors_model',$this->userModel->errors())
                             ->with('info', 'Por favor verifique os erros abaixo')
                             ->withInput();

        }
     }

     /**
     * 
     * Editar o usuário na tela
     *
     * @param int $id
     * @return object $user
     */
    public function edit($id = null){

        $user = $this->findUserOr404( $id );

        if ($user->deleted_at != null){

            return redirect()->back()->with('info', "O usuário, <strong>'$user->name'</strong> encontra-se excluído. Portanto, não é possivel editá-lo.");

        }
 
       // dd($user);
 
        $data = [
         'title'=>"Editando o usuário: $user->name",
         'user' => $user
        ];
 
        return view('adm/Users/edit', $data);
 
     }

     /**
      * Criar dados do formulário 
      */
      public function cadastrar() {

        // $this->request->getMethod() == 'post') deprecated
        if($this->request->getPost()) {
        
            $user = new User($this->request->getPost());

            if( $this->userModel->protect(false)->save($user)) {

               return redirect()->to(site_url("adm/users/show/".$this->userModel->getInsertID()))
                                ->with('success',"Usuário <strong>'$user->name'</strong>, cadastrado com sucesso!"); 
            } else {
                return redirect()->back()
                                 ->with('errors_model',$this->userModel->errors())
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
      * Atualizar os dados do formulário 
      */
      public function update( $id = null ) {

        // $this->request->getMethod() == 'post') deprecated
        if($this->request->getPost()) {
        
            $user = $this->findUserOr404( $id );

            if ($user->deleted_at != null){

                return redirect()->back()->with('info', "O usuário, <strong>'$user->name'</strong> encontra-se excluído. Portanto não é possivel exclui-lo");
    
            }

            // captura tudo que está vindo do post
            //dd($user);

            $post = $this->request->getPost();

            if ( empty( $post['password'] ) ){

                $this->userModel->disabledPasswordValidation();
                unset($post['password']);
                unset($post['password_confirmation']);

            }
            // Preparar os dados e enviar para o database
            $user->fill($post);

            // Não houve alteração nos dados, não atualiza o database
            if (!$user->hasChanged()){

                return redirect()->back()->with('warning','Não há dados para atualizar!');

            }
         

            if( $this->userModel->protect(false)->save($user)) {

               return redirect()->to(site_url("adm/users/show/$user->id"))
                                ->with('success',"Usuário <strong>'$user->name'</strong>, atualizado com sucesso!"); 
            } else {
                return redirect()->back()->with('errors_model',$this->userModel->errors())
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
     * Pesquisa o Usuário no banco de dados 
     *
     * @param int $id
     * @return object $user
     */
    private function findUserOr404(int $id = null){

        // ->withDeleted(true) Pesquisar os usuários deletados
        if (!$id || ! $user = $this->userModel->withDeleted(true)->where('id', $id)->first()){

            throw \CodeIgniter\Exceptions\PageNotFoundException::ForPageNotFound("Não encontramos o usuário $id");

        }
        return $user;

    }
}
