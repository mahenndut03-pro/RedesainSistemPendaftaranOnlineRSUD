<?php

namespace Database\Seeders;

use App\Models\Klinik;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KlinikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kliniks = [
            ['nama_poli' => 'Poliklinik Anak'],
            ['nama_poli' => 'Poliklinik Bedah'],
            ['nama_poli' => 'Poliklinik Bedah Anak'],
            ['nama_poli' => 'Poliklinik Bedah Mulut'],
            ['nama_poli' => 'Poliklinik Bedah Plastik'],
            ['nama_poli' => 'Poliklinik Bedah Saraf'],
            ['nama_poli' => 'Poliklinik Atmosfer'],
            ['nama_poli' => 'Poliklinik Gigi'],
            ['nama_poli' => 'Konsultasi Gigi'],
            ['nama_poli' => 'Konsultasi Gizi'],
            ['nama_poli' => 'Unit Hemodialisis'],
            ['nama_poli' => 'Poliklinik Melati'],
            ['nama_poli' => 'Poliklinik Penyakit Dalam'],
            ['nama_poli' => 'Poliklinik Jantung'],
            ['nama_poli' => 'Poliklinik Kedokteran Jiwa'],
            ['nama_poli' => 'Poliklinik Kandungan'],
            ['nama_poli' => 'Poliklinik Mata'],
            ['nama_poli' => 'Poliklinik Saraf'],
            ['nama_poli' => 'Poliklinik Ortodonti'],
            ['nama_poli' => 'Poliklinik Ortopedi'],
            ['nama_poli' => 'Poliklinik Paru'],
            ['nama_poli' => 'Poliklinik Psikologi'],
            ['nama_poli' => 'Poliklinik Rehab Medik'],
            ['nama_poli' => 'Poliklinik THT'],
            ['nama_poli' => 'Poliklinik Urologi'],
        ];

        foreach ($kliniks as $klinik) {
            Klinik::create($klinik);
        }
    }
}
