<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AgamasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
        'ISLAM',
        'KRISTEN KATOLIK',
        'KRISTEN PROTESTAN',
        'HINDU',
        'BUDDHA',
        'KONGHUCU',
    ];

    foreach ($data as $item) {
        DB::table('agamas')->insert(['name' => $item]);
    }
    }
}
