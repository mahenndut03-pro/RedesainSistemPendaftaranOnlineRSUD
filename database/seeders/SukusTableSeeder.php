<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SukusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
        'Aceh',
        'Ambon',
        'Bali',
        'Banjar',
        'Batak',
        'Bengkulu',
        'Betawi',
        'Bugis',
        'Dayak',
        'Jawa',
        'Lampung',
        'Madura',
        'Makassar',
        'Melayu',
        'Minangkabau',
        'Palembang',
        'Papua',
        'Rejang',
        'Riau',
        'Sasak',
        'Sunda',
        'Tionghoa'
    ];
    foreach ($data as $item) {
        DB::table('sukus')->insert(['name' => $item]);
    }
    }
}
