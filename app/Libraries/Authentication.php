<?php

/**
 * @descrição essa biblioteca / classe cuidará da parte de autenticação na nossa aplicação
 */
namespace App\Libraries;

class Authentication {

    private $user ;

    /**
     *  @param string $email
     *  @param string $password
     *  @return boolean
     */

    public function login(string $email, string $password){

        $userModel = new \App\Models\UserModel();

        $user = $userModel->findUserByEmail( $email) ;

        /* Se não encontrar o usuário por email, retorna false */
        if ($user == null){

            return false ;

        }
        /* Se a senha não combinar com o hash, retorna false */
        if (!$user->passwordVerify( $password )){

            return false ;
        }

        /* Só é permitido o login de usuário atiovo */
        if (!$user->active){

            return false ;
        }

        /* Nesse ponto está tudo certo e podemos logar o usuário da aplicação,
           invocando o metodo abaixo */

        $this->loginUser($user);

        return true ;

    }

    /** Finaliza o login e destroy a sessão do user */
    public function logout(){

        session()->destroy();
    }

    /** 
     * Retorna o usuário logado na session
     * 
    */
    public function getLoggedInUser(){
        
        /**  Não esquecer de compartilhar a instancia com services */
        if ($this->user === null) {

            $this->user = $this->getSessionUser();

        }
        /** Retornamos o usuário que foi definido no inicio da classe */
        return $this->user;

    }

    /** Pega o usuário da sessão e se ele existir retorno seu object */

    public function getSessionUser(){

        if(!session()->has('userId')){

            return null;

        } 

        /** Instaciamos o userModel  */
        $userModel = new \App\Models\UserModel();

        /** Recupero o usuário de acordo com a chave da sessão userId */
        $user = $userModel->find(session()->get('userId'));

        /** Só retorno o object user se for encontrado e estiver ativo */
        if ($user && $user->active ){

           return $user ;

        }

    }

    /** 
     * Cria uma nova session para o usuário
     * Credenciais validadas. Regeneramos a session_id e inserimos o userId na sessão
     * 
     * @param object $user (Usuário)
     * 
     * @Importante: Antes de inserimos os dados do usuário na sessão, devemos regenerar o ID da sessão.
     * Pois quando carregamos a view login pela primeira vez, o valor da variável 'ci_session' do debug tool bar é um,
     * Quando realizamos o login, o valor muda.
     * Ao fazermos isso, estamos previnindo session fixation attack. 
     * 
    */
    public function loginUser(object $user){

        $session = session();
        $session->regenerate();
        $session->set('userId', $user->id);

    } 

    /**
     * 
     * @description O método só permite ficar logado na aplicação aquele que ainda 
     *             existir na base e que esteja ativo.
     *             Do contrário, será feito o logout do mesmo, caso haja uma mudança
     *             na sua conta durante a sua sessão
     *
     * @uso : No filtro LoginFilter
     *
     * @return boolean  Retorna true se o método getLoggedUser() não for null. Ou
     *                  seja se o usuário estiver logado 
     * 
    */
    public function isLogged(){

        return $this->getLoggedInUser() !== null ;

    }
}


