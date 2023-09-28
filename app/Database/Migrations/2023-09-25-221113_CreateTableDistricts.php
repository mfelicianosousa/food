<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableDistricts extends Migration
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
            'slug' => [
                'type' => 'VARCHAR',
                'constraint' => '128',
                'null' => true,
            ],
            'city' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'default' => 'Cuiabá',
            ],
            'delivery_value' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
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
        $this->forge->createTable('districts');
    }

    public function down()
    {
        $this->forge->dropTable('districts');
    }
}
