<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PekerjaansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          $data = [
        'BELUM/TIDAK BEKERJA',
        'MENGURUS RUMAH TANGGA',
        'PELAJAR/MAHASISWA',
        'PENSIUNAN',
        'PEGAWAI NEGRI SIPIL (PNS)',
        'TENTARA NASIONAL INDONESIA (TNI)',
        'KEPOLISIAN RI (POLRI)',
        'PERDAGANGAN',
        'PETANI/PEKEBUN',
        'PETERNAK',
        'NELAYAN/PERIKANAN',
        'INDUSTRI',
        'KONSTRUKSI',
        'TRANSPORTASI',
        'KARYAWAN SWASTA',
        'KARYAWAN BUMN',
        'KARYAWAN BUMD',
        'KARYAWAN HONORER',
        'BURUH HARIAN LEPAS',
        'BURUH TANI/PEKEBUN',
        'BURUH NELAYAN/PERIKANAN',
        'BURUH PETERNAK',
        'PEMBANTU RUMAH TANGGA',
        'TUKANG CUKUR',
        'TUKANG LISTRIK',
        'TUKANG BATU',
        'TUKANG KAYU',
        'TUKANG SOL SEPATU',
        'TUKANG LAS/PANDAI BESI',
        'TUKANG JAHIT',
        'TUKANG GIGI',
        'PENATA RIAS',
        'PENATA BUSANA',
        'PENATA RAMBUT',
        'MEKANIK',
        'SENIMAN',
        'TABIB',
        'PARAJI',
        'PERANCANG BUSANA',
        'PENTERJEMAH',
        'IMAM MASJID',
        'PENDETA',
        'PASTOR',
        'WARTAWAN',
        'USTADZ/MUBALIGH',
        'JURU MASAK',
        'PROMOTOR KESEHATAN',
        'ANGGOTA DPR-RI',
        'ANGGOTA DPD',
        'ANGGOTA BPK',
        'PRESIDEN',
        'WAKIL PRESIDEN',
        'ANGGOTA MAHKAMAH KONSTITUSI',
        'ANGGOTA KABINET KEMENTRIAN',
        'DUTA BESAR',
        'GUBERNUR',
        'WAKIL GUBERNUR',
        'BUPATI',
        'WAKIL BUPATI',
        'WALIKOTA',
        'WAKIL WALIKOTA',
        'ANGGOTA DPRD PROP.',
        'ANGGOTA DPRD KAB.',
        'DOSEN',
        'GURU',
        'PILOT',
        'PENGACARA',
        'NOTARIS',
        'ARSITEK',
        'AKUNTAN',
        'KONSULTAN',
        'DOKTER',
        'BIDAN',
        'PERAWAT',
        'APOTEKER',
        'PSIKIATER/PSIKOLOG',
        'PENYIAR TELEVISI',
        'PENYIAR RADIO',
        'PELAUT',
        'PENELITI',
        'SOPIR',
        'PIALANG',
        'PARANORMAL',
        'PEDAGANG',
        'PERANGKAT DESA',
        'KEPALA DESA',
        'BIARAWATI',
        'WIRASWASTA',
        'PEKERJAAN LAINNYA'

    ];

    foreach ($data as $item) {
        DB::table('pekerjaans')->insert(['nama' => $item]);
    }
    }
}
