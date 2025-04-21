<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisPlgSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['id' => 1, 'id_jenis' => 'R1', 'nama_jenis' => 'Rumah Tangga Daya 900–2.200 VA', 'biayabeban' => 20000.00, 'tarifkwh' => 1350.00],
            ['id' => 2, 'id_jenis' => 'R2', 'nama_jenis' => 'Rumah Tangga Menengah Daya 3.500–5.500 VA', 'biayabeban' => 50000.00, 'tarifkwh' => 1450.00],
            ['id' => 3, 'id_jenis' => 'R3', 'nama_jenis' => 'Rumah Tangga Besar Daya di atas 6.600 VA', 'biayabeban' => 100000.00, 'tarifkwh' => 1550.00],
            ['id' => 4, 'id_jenis' => 'B1', 'nama_jenis' => 'Usaha Kecil Daya 450–5.500 VA', 'biayabeban' => 30000.00, 'tarifkwh' => 1400.00],
            ['id' => 5, 'id_jenis' => 'B2', 'nama_jenis' => 'Usaha Menengah Daya 6.600 VA–200 kVA', 'biayabeban' => 70000.00, 'tarifkwh' => 1500.00],
            ['id' => 6, 'id_jenis' => 'B3', 'nama_jenis' => 'Usaha Besar Daya di atas 200 kVA', 'biayabeban' => 150000.00, 'tarifkwh' => 1600.00],
            ['id' => 7, 'id_jenis' => 'I-1', 'nama_jenis' => 'Industri Kecil', 'biayabeban' => 25000.00, 'tarifkwh' => 1300.00],
            ['id' => 8, 'id_jenis' => 'I-2', 'nama_jenis' => 'Industri Menengah', 'biayabeban' => 60000.00, 'tarifkwh' => 1400.00],
            ['id' => 9, 'id_jenis' => 'I-3', 'nama_jenis' => 'Industri Besar', 'biayabeban' => 120000.00, 'tarifkwh' => 1500.00],
            ['id' => 10, 'id_jenis' => 'I-4', 'nama_jenis' => 'Industri Besar (Semen, Smelter, Mineral)', 'biayabeban' => 200000.00, 'tarifkwh' => 1600.00],
            ['id' => 11, 'id_jenis' => 'P1', 'nama_jenis' => 'Pemerintah Golongan 1', 'biayabeban' => 50000.00, 'tarifkwh' => 1400.00],
            ['id' => 12, 'id_jenis' => 'P2', 'nama_jenis' => 'Pemerintah Golongan 2', 'biayabeban' => 100000.00, 'tarifkwh' => 1500.00],
            ['id' => 13, 'id_jenis' => 'P3', 'nama_jenis' => 'Pemerintah Golongan 3', 'biayabeban' => 150000.00, 'tarifkwh' => 1600.00],
            ['id' => 14, 'id_jenis' => 'S1', 'nama_jenis' => 'Sosial Daya 220 VA', 'biayabeban' => 10000.00, 'tarifkwh' => 1200.00],
            ['id' => 15, 'id_jenis' => 'S2', 'nama_jenis' => 'Sosial Daya 450 VA–200 kVA', 'biayabeban' => 20000.00, 'tarifkwh' => 1300.00],
            ['id' => 16, 'id_jenis' => 'S3', 'nama_jenis' => 'Sosial Daya di atas 200 kVA', 'biayabeban' => 50000.00, 'tarifkwh' => 1400.00],
        ];

        DB::table('jenis_plg')->insert($data);
    }
}
