<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    protected $table = 'patients';

    protected $fillable = [
        'no_rm',
        'no_ktp',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'telepon',
        'pendidikan',
        'status',
        'pekerjaan',
        'agama',
        'email',
        'golongan_darah',
        'kewarganegaraan',
        'bahasa',
        'suku',
    ];

    public function reservasi()
    {
        return $this->hasOne(Reservasi::class, 'patient_id');
    }

    public function alamat()
    {
        return $this->hasOne(Alamat::class, 'patient_id');
    }
}
