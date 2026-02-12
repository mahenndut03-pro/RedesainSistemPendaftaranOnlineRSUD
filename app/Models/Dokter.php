<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    protected $table = 'doctors';

    protected $fillable = [
        'nama',
        'poli_id',
        'kuota_umum',
        'kuota_bpjs',
    ];

    protected $casts = [
        'kuota_umum' => 'integer',
        'kuota_bpjs' => 'integer',
    ];

    public function poli()
    {
        return $this->belongsTo(Klinik::class, 'poli_id');
    }

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class, 'dokter_id');
    }
}
