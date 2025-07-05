<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class DiskonSeeder extends Seeder
{
    public function run()
    {
        $data = [];
        $today = Time::today('Asia/Jakarta'); // Menggunakan Time helper untuk tanggal saat ini

        // Data untuk tanggal saat migrasi dibuat (hari ini)
        $data[] = [
            'tanggal'    => $today->toDateString(),
            'nominal'    => 100000,
            'created_at' => Time::now('Asia/Jakarta'),
            'updated_at' => Time::now('Asia/Jakarta'),
        ];

        // Data untuk 9 hari selanjutnya
        for ($i = 1; $i <= 9; $i++) {
            $nextDay = $today->addDays($i);
            $data[] = [
                'tanggal'    => $nextDay->toDateString(),
                'nominal'    => rand(100000, 300000), // Contoh nominal acak
                'created_at' => Time::now('Asia/Jakarta'),
                'updated_at' => Time::now('Asia/Jakarta'),
            ];
        }

        // Insert data ke tabel diskon
        $this->db->table('diskon')->insertBatch($data);
    }
}