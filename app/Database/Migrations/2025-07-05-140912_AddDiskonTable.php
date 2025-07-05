<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDiskonTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'tanggal' => [
                'type'       => 'DATE',
            ],
            'nominal' => [
                'type'       => 'DOUBLE',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('tanggal'); // Menambahkan unique key agar tanggal tidak boleh sama 
        $this->forge->createTable('diskon');
    }

    public function down()
    {
        $this->forge->dropTable('diskon');
    }
}