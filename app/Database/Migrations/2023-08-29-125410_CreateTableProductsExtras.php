<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableProductsExtras extends Migration
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
            'extra_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('extra_id', 'extras', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('products_extras');
    }

    public function down()
    {
        $this->forge->dropTable('products_extras');
    }
}
