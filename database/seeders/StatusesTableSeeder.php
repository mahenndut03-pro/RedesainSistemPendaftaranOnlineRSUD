<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      $data = [
        'BELUM KAWIN',
        'KAWIN',
        'CERAI HIDUP',
        'CERAI MATI',
    ];

    foreach ($data as $item) {
        DB::table('statuses')->insert(['name' => $item]);
    }
    }
}
