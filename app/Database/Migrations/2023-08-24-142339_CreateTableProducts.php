<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableProducts extends Migration
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
            'category_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '120',
            ],
            'slug' => [
                'type' => 'VARCHAR',
                'constraint' => '120',
            ],
            'ingredients' => [
                'type' => 'text',
            ],
            'image' => [
                'type' => 'VARCHAR',
                'constraint' => '200',
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
        $this->forge->addForeignKey('category_id', 'categories', 'id');
        $this->forge->createTable('products');
    }

    public function down()
    {
        $this->forge->dropTable('products');
    }
}
