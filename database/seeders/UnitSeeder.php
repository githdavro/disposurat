<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            ['nama_unit' => 'Unit 1', 'kode_unit' => 'U001'],
            ['nama_unit' => 'Unit 2', 'kode_unit' => 'U002'],
            ['nama_unit' => 'Bagian Pengadaan', 'kode_unit' => 'PENGADAAN'],
            ['nama_unit' => 'Direktur', 'kode_unit' => 'DIREKTUR'],
        ];

        foreach ($units as $unit) {
            Unit::create($unit);
        }
    }
}