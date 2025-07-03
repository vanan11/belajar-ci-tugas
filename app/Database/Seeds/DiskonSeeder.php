<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time; // Penting: Pastikan ini di-import untuk manipulasi tanggal

class DiskonSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'tanggal'      => Time::now()->toDateString(), // Diskon untuk hari ini
                'nominal'      => 200000.00, // Nominal diskon
                'created_at'   => Time::now(),
                'updated_at'   => Time::now(),
            ],
            [
                'tanggal'      => Time::now()->addDays(1)->toDateString(), // Diskon untuk besok
                'nominal'      => 150000.00,
                'created_at'   => Time::now(),
                'updated_at'   => Time::now(),
            ],
            [
                'tanggal'      => Time::now()->subDays(5)->toDateString(), // Diskon untuk 5 hari yang lalu (tidak aktif hari ini)
                'nominal'      => 75000.00,
                'created_at'   => Time::now(),
                'updated_at'   => Time::now(),
            ],
        ];

        // Menggunakan Query Builder untuk memasukkan data
        // Pastikan tabel 'diskon' sudah ada dan memiliki kolom 'tanggal' dan 'nominal'
        $this->db->table('diskon')->insertBatch($data);
    }
}