<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableExtras extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
            ],
            'description' => [
                'type' => 'text',
            ],
            'slug' => [
                'type' => 'VARCHAR',
                'constraint' => '128',
                'null' => true,
            ],
            'price' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'image' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
            ],
            'active' => [
                'type' => 'BOOLEAN',
                'null' => false,
                'default' => true,
            ],
            'created_at' => [
                 'type' => 'DATETIME',
                 'null' => true,
                 'default' => null,
            ],
            'modified_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
            ],
        ]);
        $this->forge->addKey('id', true)->addUniqueKey('name');
        $this->forge->createTable('extras');
    }

    public function down()
    {
        $this->forge->dropTable('extras');
    }
}
