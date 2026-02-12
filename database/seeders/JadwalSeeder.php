<?php

namespace Database\Seeders;

use App\Models\Jadwal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JadwalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jadwals = [
            // Poliklinik Rehabilitasi Medik
            [
                'poli_id' => 1,
                'dokter_id' => 1,
                'tanggal' => '2025-12-01',
                'jam_mulai' => '07:30:00',
                'jam_selesai' => '14:30:00',
            ],
            [
                'poli_id' => 1,
                'dokter_id' => 1,
                'tanggal' => '2025-12-02',
                'jam_mulai' => '07:30:00',
                'jam_selesai' => '14:30:00',
            ],
            [
                'poli_id' => 1,
                'dokter_id' => 1,
                'tanggal' => '2025-12-03',
                'jam_mulai' => '07:30:00',
                'jam_selesai' => '14:30:00',
            ],
            // Poliklinik THT
            [
                'poli_id' => 24,
                'dokter_id' => 2,
                'tanggal' => '2025-12-01',
                'jam_mulai' => '08:00:00',
                'jam_selesai' => '15:00:00',
            ],
            [
                'poli_id' => 24,
                'dokter_id' => 2,
                'tanggal' => '2025-12-02',
                'jam_mulai' => '08:00:00',
                'jam_selesai' => '15:00:00',
            ],
            [
                'poli_id' => 24,
                'dokter_id' => 2,
                'tanggal' => '2025-12-04',
                'jam_mulai' => '08:00:00',
                'jam_selesai' => '15:00:00',
            ],
            // Poliklinik Mata
            [
                'poli_id' => 18,
                'dokter_id' => 3,
                'tanggal' => '2025-12-01',
                'jam_mulai' => '09:00:00',
                'jam_selesai' => '16:00:00',
            ],
            [
                'poli_id' => 18,
                'dokter_id' => 3,
                'tanggal' => '2025-12-03',
                'jam_mulai' => '09:00:00',
                'jam_selesai' => '16:00:00',
            ],
            [
                'poli_id' => 18,
                'dokter_id' => 3,
                'tanggal' => '2025-12-05',
                'jam_mulai' => '09:00:00',
                'jam_selesai' => '16:00:00',
            ],
        ];

        foreach ($jadwals as $jadwal) {
            Jadwal::create($jadwal);
        }
    }
}
