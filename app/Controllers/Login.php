<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Login extends BaseController
{
    public function login()
    {
        $data = [
            'title' => 'Realize o login',
        ];

        return view('Login/login', $data);
    }

    public function create(){

       // if($this->request->getMethod() === 'post'){
        if($this->request->getPost()){

           // dd($this->request->getPost());
           $email = $this->request->getPost('email');
           $password = $this->request->getPost('password');

           // duas formas de recuperar a instancia de um serviço (autenticação)
           
           //$authentication = \Config\Services::authentication();
           $authentication = service('authentication');

           if ($authentication->login($email, $password)){

                $user =$authentication->getLoggedInUser();

                if (!$user->is_admin){

                    return redirect()->to(site_url('/'));
                    
                }

                return redirect()->to(site_url('adm/home'))->with('success',"Olá <strong>$user->name</strong>, que bom que está de volta.") ;

           } else {

                return redirect()->back()->with('warning','Não encontramos suas credenciais de acesso.');
           }
           



        } else {

            return redirect()->back();
        }

    }

    /** 
     * Para que possamos exibir a mensagem sua sessão expirou, ou que achar melhor,
     * Após o logout, devemos fazer uma requisição para uma url, nesse caso 'showLogoutMessage'
     * Pois quando fazemos o Logout, todos os dados da sessão atual, incluindo os flashdata são destruidos
     * Ou seja, as mensagens nunca serão exibidas
     * 
     * Portanto para conseguirmos exibi-la, basta criarmos o método 'showLogoutMessage' que fará o 
     * redirect para a home com a mensagem desejada.
     * 
     */
    public function logout(){

        service('authentication')->logout();

        return redirect()->to(site_url('login/showLogoutMessage'));
       
    }

    public function showLogoutMessage(){

        return redirect()->to(site_url('login'))->with('info',"Até logo. Esperamos ver você novamente em breve.");
    }
}
