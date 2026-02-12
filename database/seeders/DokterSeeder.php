<?php

namespace Database\Seeders;

use App\Models\Dokter;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DokterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dokters = [
            [
                'nama' => 'dr. PUTRI KHARISMA, Sp.K.F.R.',
                'poli_id' => 1,
            ],
            [
                'nama' => 'dr. RIZKY RAMADHAN, Sp.THT',
                'poli_id' => 24,
            ],
            [
                'nama' => 'dr. SARAH NURHALIZA, Sp.M',
                'poli_id' => 18,
            ],
        ];

        foreach ($dokters as $dokter) {
            // Use firstOrCreate so seeder can be run multiple times without creating duplicates
            Dokter::firstOrCreate([
                'nama' => $dokter['nama'],
                'poli_id' => $dokter['poli_id']
            ], $dokter);
        }
    }
}
