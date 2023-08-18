<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Password extends BaseController
{
 
    private $userModel;

    public function __construct(){

        $this->userModel = new \App\Models\UserModel();

    }
    public function forgot()
    {
        $data =[
            'title' => 'Esqueci a minha senha',
        ];

        return view('Password/forgot', $data);
    }

    public function processForgot(){

        if($this->request->getPost()){

            $email = $this->request->getPost('email') ;

            $user = $this->userModel->findUserByEmail( (string) $email ) ;
            if($user === null || ! $user->active){

                return redirect()->to(site_url('password/forgot'))
                                  ->with('attention',"Não encontramos uma conta válida com esse email")
                                  ->withInput();
            }
            // O Usuário foi encontrado para a recuperação da senha
            $user->startPasswordReset();

            // Atualiza a tabela de usuário
            $this->userModel->save($user);

            //dd($user);
            $this->sendEmailResetPassword( $user ) ;

            return redirect()->to(site_url('login'))
                             ->with('success','E-mail de redefinição de senha enviado para a sua caixa de entrada');

         
        } else {

            /** Não é POST */
            return redirect()->back();
        }

    }

    public function reset( $token = null ){

        if( $token === null ){

            return redirect()->to(site_url('password/forgot'))
                             ->with('attention','Link inválido ou expirado.');
        }

        $user = $this->userModel->findUserByResetPassword( $token );

        if ( $user != null ){

            $data =[
                'title' => 'Redefina a sua senha',
                'token' => $token,
            ];

            return view('Password/reset_password',$data);
        } else {

            return redirect()->to(site_url('password/forgot'))
                             ->with('attention','Link inválido ou expirado');

        };
    }

    /**
     * 
     *  Atualiza a nova senha no database
     *
     * @param string $token
     * @return void
     */
    public function process_reset( $token ){

        if( $token === null ){

            return redirect()->to(site_url('password/forgot'))
                             ->with('attention','Link inválido ou expirado.');
        }

        $user = $this->userModel->findUserByResetPassword( $token );

        if ( $user != null ){

            // Processar o que está vindo do formulário 
            $user->fill($this->request->getPost());

            if( $this->userModel->save($user)){


                /**
                 *  Setando as colunas 'reset_hash e 'reset_expired' como null ao invocar
                 *  o metodo abaixo que foi definido na entidade user
                 */
                $user->completePasswordReset();

                /** 
                 * Atualizando novamente o usuário com os novos valores definidos acima 
                */
                $this->userModel->save($user);

                return redirect()->to(site_url("login"))
                                 ->with('success','Nova senha cadastrada com sucesso!');
            } else {
                return redirect()->to(site_url("password/reset/$token"))
                                 ->with('errors_model',$this->userModel->errors())
                                 ->with('info', 'Por favor verifique os erros abaixo')
                                 ->withInput();
            }
            
        } else {

            return redirect()->to(site_url('password/forgot'))
                             ->with('attention','Link inválido ou expirado');

        };

    }

    private function sendEmailResetPassword( object $user ) {
    
        //$email = \Config\Services::email();
        $email = service('email');

        $email->setFrom('no-replay@fooddelivery.com.br', 'Food Delivery');
        $email->setTo($user->email);

        $email->setSubject( 'Redefinição de Senha' );
       
        $mensagem = view( 'Password/reset_email', [ 'token'=>$user->reset_token ] );
       
        $email->setMessage($mensagem);

        $email->send();
       
    }
}
