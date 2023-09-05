<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableProductSpecification extends Migration
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
            'product_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
            ],
            'measure_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
            ],
            'price' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'customizable' => [
                'type' => 'boolean',
                'null' => false,
                'true' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('measure_id', 'measurements', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('products_specifications');
    }

    public function down()
    {
        $this->forge->dropTable('products_specifications');
    }
}
