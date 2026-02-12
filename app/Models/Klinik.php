<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Klinik extends Model
{
    protected $table = 'clinics';

    protected $fillable = ['nama_poli', 'kode_poli', 'pelayanan_aktif', 'estimasi_menit'];

    protected $casts = [
        'estimasi_menit' => 'integer',
        'pelayanan_aktif' => 'boolean',
    ];

    public function dokter()
    {
        return $this->hasMany(Dokter::class, 'poli_id');
    }

    public function reservasi()
    {
        return $this->hasMany(Reservasi::class, 'poli_id');
    }
}

