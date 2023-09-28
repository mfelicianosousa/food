<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/* Entregadores (Delivery Men) */
class CreateTableDeliveryMen extends Migration
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
               'constraint' => '40',
           ],
           'cpf' => [
               'type' => 'VARCHAR',
               'constraint' => '11',
               'unique' => true,
           ],
           'cnh' => [
               'type' => 'VARCHAR',
               'constraint' => '20',
               'unique' => true,
           ],
           'email' => [
               'type' => 'VARCHAR',
               'constraint' => '60',
               'unique' => true,
           ],
           'phone_celular' => [
               'type' => 'VARCHAR',
               'constraint' => '20',
               'unique' => true,
           ],
           'address' => [
               'type' => 'VARCHAR',
               'constraint' => '200',
           ],
           'image' => [
               'type' => 'VARCHAR',
               'constraint' => '200',
               'null' => true,
           ],
           'vehicle' => [
               'type' => 'VARCHAR',
               'constraint' => '40',
           ],
           'vehicle_plate' => [
            'type' => 'VARCHAR',
            'constraint' => '9',
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
        $this->forge->createTable('delivery_mens');
    }

    public function down()
    {
        $this->forge->dropTable('delivery_mens');
    }
}
