<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Request as HttpRequest;
use App\Models\Pasien;
use App\Models\Reservasi;
use App\Models\Alamat;
use App\Models\Dokter;
use App\Models\Jadwal;
use App\Models\Klinik;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\ReservationRequest;
use Mews\Captcha\Captcha;
use App\Models\Pendidikan;
use App\Models\Status;
use App\Models\Pekerjaan;
use App\Models\Agama;
use App\Models\GolonganDarah;
use App\Models\Kewarganegaraan;
use App\Models\BahasaKeseharian;
use App\Models\Suku;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PasienBaruController extends Controller
{
    public function index()
    {
        $polis = Klinik::all();
        $pendidikans = Pendidikan::all();
        $statuses = Status::all();
        $pekerjaans = Pekerjaan::all();
        $agamas = Agama::all();
        $golonganDarahs = GolonganDarah::all();
        $kewarganegaraans = Kewarganegaraan::all();
        $bahasaKeseharians = BahasaKeseharian::all();
        $sukus = Suku::all();

        return view('pendaftaran.pasien-baru', compact(
            'polis',
            'pendidikans',
            'statuses',
            'pekerjaans',
            'agamas',
            'golonganDarahs',
            'kewarganegaraans',
            'bahasaKeseharians',
            'sukus'
        ));
    }


    public function store(ReservationRequest $request)
    {
        try {
            // Data sudah tervalidasi termasuk captcha
            // Lanjut simpan data pendaftaran di sini

            DB::beginTransaction();

            // Generate medical record number (no_rm) based on tanggal_lahir
            $noRm = $this->generateNoRekamMedis($request->input('tanggal_lahir'));

            // Create patient (include generated no_rm)
            $pasien = Pasien::create(array_merge($request->only([
                'no_ktp', 'nama_lengkap', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin',
                'telepon', 'pendidikan', 'status', 'pekerjaan', 'agama', 'email',
                'golongan_darah', 'kewarganegaraan', 'bahasa', 'suku'
            ]), ['no_rm' => $noRm]));

            // Create address
            $alamat = Alamat::create(array_merge($request->only([
                'provinsi', 'kabupaten', 'kecamatan', 'kelurahan', 'alamat', 'rt', 'rw'
            ]), ['patient_id' => $pasien->id]));

            // Check kuota dokter per jenis bayar
            $dokter = Dokter::find($request->input('dokter_id'));
            if (!$dokter) {
                return response()->json(['success' => false, 'message' => 'Dokter tidak ditemukan'], 404);
            }

            $caraBayar = $request->input('cara_bayar');
            $quotaField = $caraBayar === 'BPJS' ? 'kuota_bpjs' : 'kuota_umum';
            try {
                $tanggalReservasi = Carbon::parse($request->input('tanggal_reservasi'))->toDateString();
            } catch (\Exception $e) {
                $tanggalReservasi = $request->input('tanggal_reservasi');
            }

            $existingCount = Reservasi::where('dokter_id', $dokter->id)
                ->whereDate('tanggal_reservasi', $tanggalReservasi)
                ->where('cara_bayar', $caraBayar)
                ->whereIn('status', ['PENDING', 'VERIFIED'])
                ->count();

            $limit = (int) ($dokter->{$quotaField} ?? 0);
            if ($limit <= 0) {
                return response()->json(['success' => false, 'message' => 'Dokter tidak menerima ' . strtolower($caraBayar)], 422);
            }
            if ($existingCount >= $limit) {
                return response()->json(['success' => false, 'message' => 'Kuota untuk ' . strtolower($caraBayar) . ' pada dokter ini sudah penuh'], 422);
            }

            // Ensure the dokter has a jadwal on the requested tanggal_reservasi
            $jadwal = Jadwal::where('dokter_id', $dokter->id)
                ->where('tanggal', $tanggalReservasi)
                ->first();

            if (!$jadwal) {
                return response()->json(['success' => false, 'message' => 'Dokter tidak praktik pada tanggal yang dipilih'], 422);
            }

            // ensure jadwal.poli_id matches requested poli
            if (!empty($request->input('poli_id')) && $jadwal->poli_id != $request->input('poli_id')) {
                return response()->json(['success' => false, 'message' => 'Poliklinik tidak sesuai dengan jadwal dokter'], 422);
            }

            // Generate booking code
            $kodeBooking = $this->generateKodeBooking();

            // Create reservation
            $reservasi = Reservasi::create(array_merge($request->only([
                'cara_bayar', 'poli_id', 'dokter_id', 'tanggal_reservasi', 'no_bpjs', 'no_rujukan'
            ]), [
                'patient_id' => $pasien->id,
                'status' => 'PENDING',
                'kode_booking' => $kodeBooking,
                'jadwal_id' => $jadwal->id,
            ]));

            // Generate and persist nomor_antrian per poli for this reservation
            $reservasi->nomor_antrian = \App\Models\Reservasi::generateNextNomor($reservasi->poli_id, $reservasi->tanggal_reservasi);
            $reservasi->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan',
                'kode_booking' => $kodeBooking,
                'redirect_url' => route('pendaftaran.booking.print', $reservasi->id)
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            // log full exception
            Log::error($e);

            // If app is in debug mode, return the exception message and trace to help local debugging
            if (config('app.debug')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.',
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ], 500);
            }

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server'
            ], 500);
        }
    }

    public function getDokterByPoli(Request $request, $poliId)
    {
        $tanggal = $request->query('tanggal');
        if ($tanggal) {
            try {
                $tanggal = Carbon::parse($tanggal)->toDateString();
            } catch (\Exception $e) {
                // keep original
            }
        }
        $caraBayar = strtoupper($request->query('cara_bayar', 'UMUM'));

        $doctors = Dokter::where('poli_id', $poliId)->orderBy('nama')->get();

        if ($tanggal) {
            $result = [];
            foreach ($doctors as $d) {
                // only include doctors who have a jadwal for the selected date
                $jadwal = Jadwal::where('dokter_id', $d->id)->where('tanggal', $tanggal)->first();
                if (!$jadwal) {
                    continue;
                }
                $quotaField = $caraBayar === 'BPJS' ? 'kuota_bpjs' : 'kuota_umum';
                $limit = (int) ($d->{$quotaField} ?? 0);

                if ($limit > 0) {
                    $existing = Reservasi::where('dokter_id', $d->id)
                        ->whereDate('tanggal_reservasi', $tanggal)
                        ->where('cara_bayar', $caraBayar)
                        ->whereIn('status', ['PENDING', 'VERIFIED'])
                        ->count();

                    $remaining = max(0, $limit - $existing);
                } else {
                    // limit <= 0 means no quota configured for this payment type -> do not show
                    $remaining = 0;
                }

                if ($remaining > 0) {
                    $result[] = [
                        'id' => $d->id,
                        'nama' => $d->nama,
                        'poli_id' => $d->poli_id,
                        'kuota_umum' => $d->kuota_umum,
                        'kuota_bpjs' => $d->kuota_bpjs,
                        'remaining' => $remaining,
                        'jadwal' => [
                            'id' => $jadwal->id,
                            'tanggal' => $jadwal->tanggal,
                            'jam_mulai' => $jadwal->jam_mulai,
                            'jam_selesai' => $jadwal->jam_selesai,
                        ],
                    ];
                }
            }

            return response()->json($result);
        }

        return response()->json($doctors->map(function($d){
            return [
                'id' => $d->id,
                'nama' => $d->nama,
                'poli_id' => $d->poli_id,
                'kuota_umum' => $d->kuota_umum,
                'kuota_bpjs' => $d->kuota_bpjs,
                'remaining' => null,
            ];
        })->values());
    }

    public function printBooking($reservasiId)
    {
        // load pasien with alamat to access address via pasien->alamat in the view
        $reservasi = Reservasi::with(['pasien.alamat', 'poli', 'dokter'])->findOrFail($reservasiId);

        // Do not expose the medical record number on the print view
        if ($reservasi->pasien) {
            $reservasi->pasien->no_rm = null;
        }

        // Resolve wilayah names for alamat so the view can display names instead of ids
        if ($reservasi->pasien && $reservasi->pasien->alamat) {
            try {
                $names = $this->resolveWilayahNames($reservasi->pasien->alamat);
                $reservasi->pasien->alamat->provinsi_name = $names['provinsi'] ?? $reservasi->pasien->alamat->provinsi;
                $reservasi->pasien->alamat->kabupaten_name = $names['kabupaten'] ?? $reservasi->pasien->alamat->kabupaten;
                $reservasi->pasien->alamat->kecamatan_name = $names['kecamatan'] ?? $reservasi->pasien->alamat->kecamatan;
                $reservasi->pasien->alamat->kelurahan_name = $names['kelurahan'] ?? $reservasi->pasien->alamat->kelurahan;
            } catch (\Throwable $e) {
                // fail silently, keep ids as fallback
                Log::warning('Failed to resolve wilayah names: ' . $e->getMessage());
            }
        }

        // Generate QR image (PNG base64) for the web view as well. Use Google Charts QR endpoint as fallback.
        $qr = null;
        try {
            $kode = $reservasi->kode_booking ?? '';
            if (!empty($kode)) {
                $qrUrl = 'https://chart.googleapis.com/chart?cht=qr&chs=300x300&chl=' . urlencode($kode) . '&chld=L|1';
                $response = Http::timeout(5)->get($qrUrl);
                if ($response->ok()) {
                    $raw = $response->body();
                    $qr = base64_encode($raw);
                }
            }
        } catch (\Throwable $e) {
            // ignore QR generation failures for the web view
        }

        return view('pendaftaran.booking-print', compact('reservasi', 'qr'));
    }

    /**
     * Generate server-side PDF for a booking (same content as booking-print view).
     */
    public function printBookingPdf($reservasiId)
    {
        $reservasi = Reservasi::with(['pasien.alamat', 'poli', 'dokter'])->findOrFail($reservasiId);

        if ($reservasi->pasien) {
            $reservasi->pasien->no_rm = null;
        }

        if ($reservasi->pasien && $reservasi->pasien->alamat) {
            try {
                $names = $this->resolveWilayahNames($reservasi->pasien->alamat);
                $reservasi->pasien->alamat->provinsi_name = $names['provinsi'] ?? $reservasi->pasien->alamat->provinsi;
                $reservasi->pasien->alamat->kabupaten_name = $names['kabupaten'] ?? $reservasi->pasien->alamat->kabupaten;
                $reservasi->pasien->alamat->kecamatan_name = $names['kecamatan'] ?? $reservasi->pasien->alamat->kecamatan;
                $reservasi->pasien->alamat->kelurahan_name = $names['kelurahan'] ?? $reservasi->pasien->alamat->kelurahan;
            } catch (\Throwable $e) {
                // ignore
            }
        }

        // Generate QR image (PNG base64) for server-side PDF. Use Google Charts QR endpoint as fallback.
        $qr = null;
        try {
            $kode = $reservasi->kode_booking ?? '';
            if (!empty($kode)) {
                $qrUrl = 'https://chart.googleapis.com/chart?cht=qr&chs=300x300&chl=' . urlencode($kode) . '&chld=L|1';
                $response = Http::timeout(5)->get($qrUrl);
                if ($response->ok()) {
                    $raw = $response->body();
                    $qr = base64_encode($raw);
                }
            }
        } catch (\Throwable $e) {
            // ignore QR generation failures
        }

        $pdf = Pdf::loadView('pendaftaran.booking-print', compact('reservasi', 'qr'));
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOptions(['isRemoteEnabled' => true]);
        return $pdf->stream('bukti_pendaftaran_' . ($reservasi->kode_booking ?? $reservasi->id) . '.pdf');
    }

    /**
     * Resolve wilayah (provinsi/kabupaten/kecamatan/kelurahan) names from stored ids.
     * Uses public JSON endpoints (emsifa) to translate ids to human-readable names.
     * Returns array with keys: provinsi, kabupaten, kecamatan, kelurahan
     */
    private function resolveWilayahNames($alamat)
    {
        $result = [];

        // provinsi and kabupaten may be stored as numeric ids. Try to resolve progressively.
        $provId = $alamat->provinsi;
        $kabId = $alamat->kabupaten;
        $kecId = $alamat->kecamatan;
        $kelId = $alamat->kelurahan;

        // helper to fetch json with basic timeout
        $fetch = function($url) {
            $context = stream_context_create(['http' => ['timeout' => 5]]);
            $raw = @file_get_contents($url, false, $context);
            if (!$raw) return null;
            return @json_decode($raw, true);
        };

        // provinces
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

        // kabupaten (regencies) require province id
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

        // kecamatan (districts) require kabupaten id
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

        // kelurahan (villages) require kecamatan id
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



    private function generateKodeBooking()
    {
        // Use the same 8-char format as legacy registration: BK + 6 random chars
        return strtoupper('BK' . Str::random(6));
    }

    /**
     * Generate medical record number using patient's birth date.
     * Format: RM-YYYYMMDD ; if duplicate exists, append -001, -002, ...
     *
     * @param string $tanggal_lahir
     * @return string
     */
    private function generateNoRekamMedis($tanggal_lahir)
    {
        // Fallback to today if tanggal_lahir not provided or invalid
        try {
            $datePart = $tanggal_lahir ? date('Ymd', strtotime($tanggal_lahir)) : date('Ymd');
        } catch (\Throwable $e) {
            $datePart = date('Ymd');
        }

        $base = 'RM-' . $datePart;
        $candidate = $base;
        $counter = 1;

        // Ensure uniqueness by appending a counter if needed
        while (Pasien::where('no_rm', $candidate)->exists()) {
            $candidate = $base . '-' . str_pad($counter, 3, '0', STR_PAD_LEFT);
            $counter++;
        }

        return $candidate;
    }

    public function searchRiwayat(Request $request)
    {
        try {
            $searchType = $request->input('search_type');
            $searchValue = trim($request->input('search_value'));

            // Validate input
            if (!$searchType || !$searchValue) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data Tidak Ditemukan'
                ]);
            }

            $query = Reservasi::with(['pasien', 'poli', 'dokter']);

            if ($searchType === 'no_ktp') {
                // Search by no_ktp (partial match, case-insensitive via LIKE)
                $query->whereHas('pasien', function($q) use ($searchValue) {
                    $q->where('no_ktp', 'LIKE', "%{$searchValue}%");
                });
            } elseif ($searchType === 'kode_booking') {
                // Search by booking code (exact or partial match)
                $query->where('kode_booking', 'LIKE', "%{$searchValue}%");
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Data Tidak Ditemukan'
                ]);
            }

            $reservasis = $query->orderBy('created_at', 'desc')->get();

            // Debug log for search attempts - include SQL for diagnosis
            try {
                Log::info('searchRiwayat', [
                    'type' => $searchType, 
                    'value' => $searchValue, 
                    'found' => $reservasis->count(),
                    'sql' => $query->toSql(),
                    'bindings' => $query->getBindings()
                ]);
            } catch (\Throwable $e) { /* ignore logging errors */ }

            if ($reservasis->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data Tidak Ditemukan'
                ]);
            }

            // Format data for response
            $results = $reservasis->map(function($reservasi) {
                // Determine a simple status for display:
                // - If reservation date is in the future or today => Menunggu
                // - If reservation date is in the past => Sudah Selesai
                // There is no 'cancelled' flag in the reservations table; that would need DB support.
                $status = '-';
                try {
                    if ($reservasi->tanggal_reservasi) {
                        $resDate = strtotime($reservasi->tanggal_reservasi);
                        $today = strtotime(date('Y-m-d'));
                        if ($resDate < $today) $status = 'Sudah Selesai';
                        else $status = 'Menunggu';
                    }
                } catch (\Throwable $e) {
                    $status = '-';
                }

                return [
                    'kode_booking' => $reservasi->kode_booking,
                    'nama_lengkap' => $reservasi->pasien->nama_lengkap ?? '',
                    'tanggal_lahir' => $reservasi->pasien->tanggal_lahir ? date('d/m/Y', strtotime($reservasi->pasien->tanggal_lahir)) : '',
                    'jenis_kelamin' => $reservasi->pasien->jenis_kelamin ?? '',
                    'email' => $reservasi->pasien->email ?? '',
                    'no_ktp' => $reservasi->pasien->no_ktp ?? '',
                    'poli' => $reservasi->poli->nama_poli ?? '',
                    'dokter' => $reservasi->dokter->nama ?? '',
                    'tanggal_reservasi' => $reservasi->tanggal_reservasi ? date('d/m/Y', strtotime($reservasi->tanggal_reservasi)) : '',
                    'cara_bayar' => $reservasi->cara_bayar ?? '',
                    'created_at' => $reservasi->created_at ? date('d/m/Y H:i', strtotime($reservasi->created_at)) : '',
                    'status' => $status,
                    'keterangan' => $reservasi->keterangan ?? ''
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $results
            ]);

        } catch (\Exception $e) {
            Log::error('Error searching riwayat: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mencari data'
            ], 500);
        }
    }
}
