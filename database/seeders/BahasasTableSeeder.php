<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BahasasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
        'Bahasa Aceh',
        'Bahasa Ambon',
        'Bahasa Bali',
        'Bahasa Banjar',
        'Bahasa Batak',
        'Bahasa Bengkulu',
        'Bahasa Betawi',
        'Bahasa Bugis',
        'Bahasa Dayak',
        'Bahasa Indonesia',
        'Bahasa Inggris',
        'Bahasa Jawa',
        'Bahasa Lampung',
        'Bahasa Madura',
        'Bahasa Makassar',
        'Bahasa Melayu',
        'Bahasa Minangkabau',
        'Bahasa Palembang',
        'Bahasa Papua',
        'Bahasa Sasak',
        'Bahasa Sunda',
    ];
    foreach ($data as $item) {
        DB::table('bahasas')->insert(['name' => $item]);
    }
    }
}
