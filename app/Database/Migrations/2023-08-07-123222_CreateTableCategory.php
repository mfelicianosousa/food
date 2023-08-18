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
            'slug' => [
                'type'       => 'VARCHAR',
                'constraint' => '128',
                'null'       => true,
            ],
            'active' => [
                'type'       => 'BOOLEAN',
                'null'       => false,
                'default'    => true,
            ],
            'image' => [
                'type'       => 'VARCHAR', 
                'constraint' => '50',
            ],
            'created_at' => [
                 'type'       => 'DATETIME',
                 'null'       => true,
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
        $this->forge->addKey('id', true)->addUniqueKey('name');
        $this->forge->createTable('categories');
    }

    public function down()
    {
        $this->forge->dropTable('categories');
    }
}
