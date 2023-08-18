<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        return view('welcome_message');
    }

    public function email(){

        $email = \Config\Services::email();

        $email->setFrom('no-replay@fooddelivery.com.br', 'Food Delivery');
        $email->setTo('marcelino.feliciano@outlook.com');

        $email->setSubject( 'Redefinição de Senha' );
        $email->setMessage( 'Teste Email' );
       
        if ( $email->send() ){
            echo 'Email enviado ...';
        }else {
           echo $email->printDebugger();
        }  



    }
}
