<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use App\Libraries\Token;


class User extends Entity
{
   protected $dates = ['created_at', 'modified_at', 'deleted_at'];

   public function passwordVerify(string $password){
    
     return password_verify($password, $this->password_hash);

   }

   public function startPasswordReset(){

     /** Instancio novo objeto da classe Token  */
     $token = new Token();

     /**
      * @descricao: Atribuimos ao objeto Entities User ($this) o atributo 
      *             'reset_token' que conterá o token gerado para que 
      *             possamos acessá-lo na view 'Password/reset_email'
      */
     $this->reset_token = $token->getValue();
     /** 
      * @descricao: Atribuimos ao objeto Entities User ($this) o atributo
      *             'reset_hash' que conterá o hash do token
     */
     $this->reset_hash = $token->getHash();
     /**
      * @descricao: Atribuímos ao objeto Entities User ($this) o atributo
      *            'reset_expired' que conterá a data de expiração do token gerado
      */
     $this->reset_expired = date('Y-m-d H:i:s', time() + 7200); // Expira em 2 horas a partir da data e hora atual;


   }

   /**
    *   Setando as colunas 'reset_hash' e 'reset_expired_at' como null 
    *   Invalidamos o link antigo que foi para o email do usuário
    * @param null
    * @return void
    *
   */
   public function completePasswordReset(){

      $this->reset_hash = null;
      $this->reset_expired = null;

   }
}
