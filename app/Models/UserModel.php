<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Libraries\Token;

class UserModel extends Model
{
    protected $table            = 'users';
    //protected $returnType       = 'object';
    //protected $returnType       = 'array';
    protected $returnType       = 'App\Entities\User';
   
    protected $allowedFields    = ['name','email','cpf','celular_number','password','reset_hash','reset_expired'];

    // Datas
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at'; // Nome da coluna no banco de dados
    protected $updatedField     = 'modified_at'; // Nome da coluna no banco de dados
    protected $dateFormat       = 'datetime'; // Para uso com o $useSoftDeletes

    protected $useSoftDeletes   = true; // Não excluir o registro definitivo
    protected $deletedField     = 'deleted_at'; // Nome da coluna no banco de dados
    
    // Regras de validações
    protected $validationRules = [
        'name'         => 'required|alpha_numeric_space|min_length[4]|max_length[50]',
        'email'        => 'required|valid_email|is_unique[users.email]',
        'cpf'          => 'required|is_unique[users.cpf]|exact_length[14]|validateCpf',
        'celular_number' => 'required',
        'password'     => 'required|min_length[6]',
        'password_confirmation' => 'required_with[password]|matches[password]',
    ];
    protected $validationMessages = [
        'name' => [
            'required' => 'O campo nome é obrigatório.',
        ],
        'email' => [
            'required' => 'O campo e-mail é obrigatório.',
            'is_unique' => 'Desculpe. Esse email já existe.',
        ],
        'cpf' => [
            'required' => 'O campo cpf é obrigatório.',
            'is_unique' => 'Desculpe. Esse número de cpf já existe.',
        ],
        'celular' => [
            'required' => 'O campo celular é obrigatório.',
        ],
    ];
    // Eventos de callback
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];



    /**
     * 
     * Gerar o hash password da senha 
     *
     * @param array $data
     * @return $data
     */
    protected function hashPassword(array $data){


        if (isset($data['data']['password'])){
        
            $data['data']['password_hash'] = password_hash($data['data']['password'],PASSWORD_DEFAULT);
            unset($data['data']['password']);
            unset($data['data']['password_confirmation']);

        }

        return $data;
    
    }

    /**
     * 
     * @uso Controller usuários no método search com o autocomplete
     *
     * author: Marcelino
     * 
     * @param string $term
     * @return array users
     */
    public function search( $term ){

        if($term === null){
            return [];

        }

        return $this->select('id, name')
                    ->like('name', $term)
                    ->withDeleted( true )
                    ->get()
                    ->getResult();

    }

    /**
     * Desabilitar Validação de Senha
     *
     * @return void
     */
    public function disabledPasswordValidation() {

        unset($this->validationRules['password']);
        unset($this->validationRules['password_confirmation']);

    }

    /**
     * 
     * @uso Desfazer a exclusão do usuário
     *
     * author: Marcelino
     * 
     * @param int $id
     * @return void
     */

    public function undoDelete( int $id ){

        return $this->protect(false)
                        ->where('id',$id)
                        ->set('deleted_at',null)
                        ->update();

    }


    /**
     * @uso Classe Autenticação
     * Busca usuário por email
     *
     * @param string $email
     * @return object $user 
     */
    public function findUserByEmail(string $email){

        return $this->where('email',$email)->first();

    }

    /**
     * @uso Classe Autenticação
     * Busca usuário por email
     *
     * @param string $hash
     * @return object $user 
     */
    public function findUserByResetPassword( string $token ){
       
        $token = new Token( $token );

        $tokenHash = $token->getHash();

    

        $user = $this->where('reset_hash',$tokenHash)->first();


        if ($user != null){

            /**
             *  Verificamos se o token não está expirado de acordo
             *  com a data e hora atuais
             */

            if ($user->reset_expired < date('Y-m-d H:i:s')) {
               
                /**
                 *  Token está expirado, então setamos o $usuario null
                 */
                $user = null ;

            }
            return $user;

        }
    }
    
}
