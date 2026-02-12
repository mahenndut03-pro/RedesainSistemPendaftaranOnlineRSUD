<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alamat extends Model
{
    protected $table = 'addresses';

    protected $fillable = [
        'patient_id',
        'provinsi',
        'kabupaten',
        'kecamatan',
        'kelurahan',
        'alamat',
        'rt',
        'rw',
    ];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'patient_id');
    }
}
