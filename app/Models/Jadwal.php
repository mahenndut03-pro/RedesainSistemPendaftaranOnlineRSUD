<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $table = 'jadwals';

    protected $fillable = [
        'poli_id',
        'dokter_id',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
    ];

    public function poli()
    {
        return $this->belongsTo(Klinik::class, 'poli_id');
    }

    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'dokter_id');
    }

    public function reservasiHariIni()
    {
    return $this->hasMany(Reservasi::class, 'dokter_id', 'dokter_id')
                ->whereDate('tanggal_reservasi', $this->tanggal);
    }

}
