<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDiskonTable extends Migration
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
            'tanggal' => [ // Kolom tanggal diskon
                'type'       => 'DATE',
                'null'       => false,
                'unique'     => true, // Pastikan tanggal unik
            ],
            'nominal' => [ // Kolom nominal diskon
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => false,
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
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('diskon');
    }

    public function down()
    {
        $this->forge->dropTable('diskon');
    }
}
