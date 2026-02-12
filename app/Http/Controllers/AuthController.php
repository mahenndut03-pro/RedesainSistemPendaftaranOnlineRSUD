<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    // 🏠 Tampilkan halaman utama (index.blade.php)
    public function index()
    {
        return view('index');
    }

    // 🔐 Proses login pasien
    public function login(Request $request)
    {
        $request->validate([
            'no_rm' => 'required',
            'tgl_lahir' => 'required|date',
            'captcha' => 'required|captcha',
        ]);

                // Cari pasien berdasarkan nomor rekam medis (no_rm) atau nomor KTP (no_ktp) dan tanggal lahir
                $pasien = \App\Models\Pasien::where(function($q) use ($request) {
                                $q->where('no_rm', $request->no_rm)
                                    ->orWhere('no_ktp', $request->no_rm);
                        })
                        ->where('tanggal_lahir', $request->tgl_lahir)
                        ->first();

        if ($pasien) {
            $alamat = null;
            $provinsi = $kabupaten = $kecamatan = $kelurahan = $rt = $rw = null;
            if (method_exists($pasien, 'alamat') && $pasien->alamat) {
                $alamat = $pasien->alamat->alamat_lengkap ?? $pasien->alamat->alamat ?? null;
                    try {
                        $names = $this->resolveWilayahNames($pasien->alamat);
                    } catch (\Throwable $e) {
                        $names = [];
                    }
                    $provinsi = $names['provinsi'] ?? null;
                    $kabupaten = $names['kabupaten'] ?? null;
                    $kecamatan = $names['kecamatan'] ?? null;
                    $kelurahan = $names['kelurahan'] ?? null;
                    $rt = $pasien->alamat->rt ?? null;
                    $rw = $pasien->alamat->rw ?? null;
            }

            Session::put('user', [
                'id' => $pasien->id,
                'no_rm' => $pasien->no_rm ?? null,
                'no_ktp' => $pasien->no_ktp ?? null,
                'nama' => $pasien->nama_lengkap ?? null,
                'telepon' => $pasien->telepon ?? null,
                'email' => $pasien->email ?? null,
                'tanggal_lahir' => $pasien->tanggal_lahir ?? null,
                'tempat_lahir' => $pasien->tempat_lahir ?? null,
                'jenis_kelamin' => $pasien->jenis_kelamin ?? null,
                'pekerjaan' => $pasien->pekerjaan ?? null,
                'pendidikan' => $pasien->pendidikan ?? null,
                'agama' => $pasien->agama ?? null,
                'golongan_darah' => $pasien->golongan_darah ?? null,
                'kewarganegaraan' => $pasien->kewarganegaraan ?? null,
                'bahasa' => $pasien->bahasa ?? null,
                'suku' => $pasien->suku ?? null,
                'alamat' => $alamat,
                'provinsi' => $provinsi,
                'kabupaten' => $kabupaten,
                'kecamatan' => $kecamatan,
                'kelurahan' => $kelurahan,
                'rt' => $rt,
                'rw' => $rw,
                    'provinsi_name' => $names['provinsi'] ?? null,
                    'kabupaten_name' => $names['kabupaten'] ?? null,
                    'kecamatan_name' => $names['kecamatan'] ?? null,
                    'kelurahan_name' => $names['kelurahan'] ?? null,
            ]);
            // return redirect()->route('home')->with('success', 'Login berhasil! Selamat datang, ' . $pasien->nama_lengkap);
            return redirect()->route('pendaftaran-pasien-lama.index')->with('success', 'Login berhasil! Selamat datang, ' . $pasien->nama_lengkap);
        }

        return back()->withErrors(['login' => 'Nomor RM atau tanggal lahir salah.'])->withInput();
    }

    // 🚪 Logout
    public function logout()
    {
        Session::forget('user');
        return redirect()->route('home');
    }

    /**
     * Resolve wilayah names (provinsi/kabupaten/kecamatan/kelurahan) from stored ids.
     * Uses public JSON endpoints (emsifa) to translate ids to human-readable names.
     */
    private function resolveWilayahNames($alamat)
    {
        $result = [];
        if (!$alamat) return $result;

        $provId = $alamat->provinsi ?? null;
        $kabId = $alamat->kabupaten ?? null;
        $kecId = $alamat->kecamatan ?? null;
        $kelId = $alamat->kelurahan ?? null;

        $fetch = function($url) {
            $context = stream_context_create(['http' => ['timeout' => 5]]);
            $raw = @file_get_contents($url, false, $context);
            if (!$raw) return null;
            return @json_decode($raw, true);
        };

        if ($provId) {
            $provinces = $fetch('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json');
            if (is_array($provinces)) {
                foreach ($provinces as $p) {
                    if ((string)($p['id'] ?? '') === (string)$provId) {
                        $result['provinsi'] = $p['name'] ?? null;
                        break;
                    }
                }
            }
        }

        if ($provId && $kabId) {
            $regUrl = "https://www.emsifa.com/api-wilayah-indonesia/api/regencies/{$provId}.json";
            $regencies = $fetch($regUrl);
            if (is_array($regencies)) {
                foreach ($regencies as $r) {
                    if ((string)($r['id'] ?? '') === (string)$kabId) {
                        $result['kabupaten'] = $r['name'] ?? null;
                        break;
                    }
                }
            }
        }

        if ($kabId && $kecId) {
            $distUrl = "https://www.emsifa.com/api-wilayah-indonesia/api/districts/{$kabId}.json";
            $districts = $fetch($distUrl);
            if (is_array($districts)) {
                foreach ($districts as $d) {
                    if ((string)($d['id'] ?? '') === (string)$kecId) {
                        $result['kecamatan'] = $d['name'] ?? null;
                        break;
                    }
                }
            }
        }

        if ($kecId && $kelId) {
            $villUrl = "https://www.emsifa.com/api-wilayah-indonesia/api/villages/{$kecId}.json";
            $villages = $fetch($villUrl);
            if (is_array($villages)) {
                foreach ($villages as $v) {
                    if ((string)($v['id'] ?? '') === (string)$kelId) {
                        $result['kelurahan'] = $v['name'] ?? null;
                        break;
                    }
                }
            }
        }

        return $result;
    }
}
