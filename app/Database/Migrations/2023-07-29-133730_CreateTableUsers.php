<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableUsers extends Migration
{
    public function up()
    {
         $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
               
            ],
            'cpf' => [
                'type'       => 'VARCHAR',
                'constraint' => '14',
                'null'       => true,
                'unique'     => true,
            ],
            'nickname' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'user' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'is_admin' => [
                'type'       => 'BOOLEAN',
                'null'       => false,
                'default'    => false,
            ],
            'active' => [
                'type'       => 'BOOLEAN',
                'null'       => false,
                'default'    => false,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'password_hash' => [
                'type'       => 'VARCHAR',
                'constraint' => '220',
            ],
            'activated_hash' => [
                'type'       => 'VARCHAR',
                'constraint' => '64',
                'null'       => true,
                'unique'     => true,
            ],
            'reset_hash' => [
                'type'       => 'VARCHAR',
                'constraint' => '64',
                'null'       => true,
                'unique'     => true,
            ],
            'reset_expired' => [
                'type'       => 'DATETIME',
                'null'       => true,
                'default'    => null,
            ],
            'recover_password' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'confirm_email' => [
                'type'       => 'VARCHAR',
                'constraint' => '220',
            ],
            'validated_email' => [
                'type'       => 'BOOLEAN',
                'null'       => false,
                'default'    => false,
            ],
            'celular_number' => [
                'type'       => 'CHAR', 
                'constraint' => '15',
            ],
            'validated_celular' => [
                'type'       => 'BOOLEAN',
                'null'       => false,
                'default'    => false,
            ],
            'image' => [
                'type'       => 'VARCHAR', 
                'constraint' => '50',
            ],
            'created_at' => [
                 'type'       => 'DATETIME',
                 'default'    => null,
            ],
            'modified_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
                'default'    => null,
            ],
            'deleted_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
                'default'    => null,
            ],
        
        ]);
        $this->forge->addKey('id', true)->addUniqueKey('email');
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
