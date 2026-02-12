<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PendidikansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
        'TIDAK/BLM SEKOLAH',
        'BELUM TAMAT SD/SEDERAJAT',
        'TAMAT SD/SEDERAJAT',
        'SLTP/SEDERAJAT',
        'SLTA/SEDERAJAT',
        'DIPLOMA I/II',
        'AKADEMI/DIPLOMA III/SARJANA MUDA',
        'STRATA-I',
        'STRATA-II',
        'STRATA-III'
    ];

    foreach ($data as $item) {
        DB::table('pendidikans')->insert(['nama' => $item]);
    }
    }
}
