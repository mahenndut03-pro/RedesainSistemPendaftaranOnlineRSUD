<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WargasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
        'Warga Negara Indonesia (WNI)',
        'Warga Negara Asing (WNA)',
    ];
    foreach ($data as $item) {
        DB::table('wargas')->insert(['name' => $item]);
    }
    }
}
