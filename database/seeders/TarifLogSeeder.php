<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TarifLogSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tarif_log')->insert([
            [
                'id' => 1,
                'id_jenis' => 'R1',
                'tarifkwh' => 1352.00,
                'biayabeban' => 20000.00,
                'berlaku_mulai' => '2024-01-01',
                'berlaku_sampai' => '2024-06-30',
            ],
            [
                'id' => 2,
                'id_jenis' => 'R1',
                'tarifkwh' => 1400.00,
                'biayabeban' => 21000.00,
                'berlaku_mulai' => '2024-07-01',
                'berlaku_sampai' => null, // berlaku sampai tidak ditentukan
            ],
            [
                'id' => 3,
                'id_jenis' => 'R2',
                'tarifkwh' => 1444.70,
                'biayabeban' => 30000.00,
                'berlaku_mulai' => '2024-01-01',
                'berlaku_sampai' => null,
            ],
            [
                'id' => 4,
                'id_jenis' => 'B1',
                'tarifkwh' => 1115.00,
                'biayabeban' => 50000.00,
                'berlaku_mulai' => '2024-03-01',
                'berlaku_sampai' => '2024-12-31',
            ],
        ]);
    }
}
