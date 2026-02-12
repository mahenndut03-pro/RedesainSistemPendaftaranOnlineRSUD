<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasien;
use App\Models\Reservasi;
use App\Services\ReservationService;
use Illuminate\Support\Str;
use App\Models\Klinik;
use App\Models\Dokter;
use Illuminate\Support\Facades\Session;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

use Carbon\Carbon;
use App\Models\Jadwal;

class PendaftaranLamaController extends Controller
{
    public function storeBpjs(Request $request)
    {
        $data = $request->validate([
            'no_ktp' => 'nullable|string|max:255',
            'nama_lengkap' => 'required|string|max:255',
            'telepon' => 'required|string|max:50',
            'poli_id' => 'required|integer',
            'dokter_id' => 'required|integer',
            'tanggal_reservasi' => 'required|date',
            'no_bpjs' => 'nullable|string|max:100',
            'no_rujukan' => 'nullable|string|max:100',
            'tanggal_rujukan' => 'nullable|date',
            'waktu' => 'nullable|string|max:20',
            'alasan_kontrol' => 'nullable|string|max:2000',
            'kode_booking' => 'nullable|string',
        ]);

        $user = Session::get('user');
        if (!$user || !isset($user['id'])) {
            return redirect()->route('login')->withErrors(['login' => 'Silakan login terlebih dahulu.']);
        }

        // If kode_booking provided, update existing reservation
        if (!empty($data['kode_booking'])) {
            $reservasi = Reservasi::where('kode_booking', $data['kode_booking'])
                ->where('patient_id', $user['id'])
                ->where('status', 'VERIFIED')
                ->first();

            if (!$reservasi) {
                return redirect()->back()->withErrors(['not_found' => 'Reservasi tidak ditemukan.']);
            }

            // Update the reservation with selected poli, dokter, etc.
            $reservasi->update([
                'poli_id' => $data['poli_id'],
                'dokter_id' => $data['dokter_id'],
                'tanggal_reservasi' => $data['tanggal_reservasi'],
                'no_bpjs' => $data['no_bpjs'] ?? $reservasi->no_bpjs,
                'alasan_kontrol' => $data['alasan_kontrol'] ?? $reservasi->alasan_kontrol,
            ]);

            // Generate nomor_antrian if not set
            if (empty($reservasi->nomor_antrian)) {
                $reservasi->nomor_antrian = Reservasi::generateNextNomorBpjs($reservasi->poli_id, $reservasi->tanggal_reservasi);
                $reservasi->save();
            }

            return redirect()->route('bpjs.konfirmasi', ['kode' => $reservasi->kode_booking]);
        }

        // Original logic for new reservation

        // defensive init: ensure $tanggal is defined even if input is missing or invalid
        $tanggal = $data['tanggal_reservasi'] ?? null;
        try {
            $tanggal = \Carbon\Carbon::parse($tanggal)->toDateString();
        } catch (\Exception $e) {
            // keep raw input if parsing fails
        }

        // If user is logged in, prefer their patient record to avoid mismatches
        $pasien = null;
        $user = Session::get('user');
        if ($user && isset($user['id'])) {
            $pasien = Pasien::find($user['id']);
        }

        // Otherwise try to locate by KTP or telepon as before
        if (!$pasien) {
            if (!empty($data['no_ktp'])) {
                $pasien = Pasien::where('no_ktp', $data['no_ktp'])->first();
            }
        }

        if (!$pasien) {
            $pasien = Pasien::where('telepon', $data['telepon'])->first();
        }

        if ($pasien) {
            // update fields as needed — ensure telepon reflects what user provided
            $updated = false;
            if (empty($pasien->no_ktp) && !empty($data['no_ktp'])) { $pasien->no_ktp = $data['no_ktp']; $updated = true; }
            if (empty($pasien->nama_lengkap) && !empty($data['nama_lengkap'])) { $pasien->nama_lengkap = $data['nama_lengkap']; $updated = true; }
            if (!empty($data['telepon']) && ($pasien->telepon !== $data['telepon'])) { $pasien->telepon = $data['telepon']; $updated = true; }
            if ($updated) { $pasien->save(); }
        } else {
            $pasien = Pasien::create([
                'no_ktp' => $data['no_ktp'] ?? null,
                'nama_lengkap' => $data['nama_lengkap'],
                'telepon' => $data['telepon'],
            ]);
        }

        $kode = strtoupper('BK'.Str::random(6));

        // Periksa kuota dokter untuk BPJS
        $dokter = Dokter::find($data['dokter_id']);
        if (!$dokter) {
            return redirect()->back()->withErrors(['dokter' => 'Dokter tidak ditemukan.']);
        }

        $service = new ReservationService();

        $existing = Reservasi::where('dokter_id', $dokter->id)
            ->whereDate('tanggal_reservasi', $tanggal)
            ->where('cara_bayar', 'BPJS')
            ->whereIn('status', ['PENDING', 'VERIFIED'])
            ->count();

        // Use centralized duplicate detection for consistency
        $duplicateForPatient = $service->hasDuplicate($pasien->id, $tanggal, $data['poli_id']);

        Log::info('storeBpjs: duplicate check (central)', [
            'patient_id' => $pasien->id ?? null,
            'tanggal_reservasi' => $data['tanggal_reservasi'] ?? null,
            'poli_id' => $data['poli_id'] ?? null,
            'dokter_id' => $data['dokter_id'] ?? null,
            'duplicate' => $duplicateForPatient,
        ]);

        // If patient already has reservation for requested date and that date is today,
        // do NOT auto-move the reservation. Ask the patient to pick another date
        // manually so they can choose an available jadwal.
        if ($duplicateForPatient && \Carbon\Carbon::parse($data['tanggal_reservasi'])->isToday()) {
            Log::info('storeBpjs: duplicate today - require user to pick another date', [
                'patient_id' => $pasien->id ?? null,
                'tanggal_reservasi' => $data['tanggal_reservasi'] ?? null,
                'poli_id' => $data['poli_id'] ?? null,
            ]);

            return redirect()->back()->withErrors(['duplicate' => 'Reservasi ditolak — Anda sudah memiliki reservasi aktif hari ini. Silakan pilih tanggal lain atau periksa jadwal poliklinik.']);
        }

        if ($duplicateForPatient) {
            return redirect()->back()->withErrors(['duplicate' => 'Reservasi ditolak, pasien sudah memiliki reservasi aktif pada waktu tersebut.']);
        }

        $limit = (int) ($dokter->kuota_bpjs ?? 0);
        if ($limit <= 0) {
            return redirect()->back()->withErrors(['kuota' => 'Dokter tidak menerima BPJS pada tanggal tersebut.']);
        }
        if ($existing >= $limit) {
            return redirect()->back()->withErrors(['kuota' => 'Kuota BPJS untuk dokter ini sudah penuh pada tanggal tersebut.']);
        }

        try {
            $reservasi = $service->create([
                'patient_id' => $pasien->id,
                'cara_bayar' => 'BPJS',
                'poli_id' => $data['poli_id'],
                'dokter_id' => $data['dokter_id'],
                'tanggal_reservasi' => $data['tanggal_reservasi'],
                'no_bpjs' => $data['no_bpjs'] ?? null,
                'no_rujukan' => $data['no_rujukan'] ?? null,
                'tanggal_rujukan' => $data['tanggal_rujukan'] ?? null,
                'waktu' => $data['waktu'] ?? null,
                'alasan_kontrol' => $data['alasan_kontrol'] ?? null,
            ]);
        } catch (\RuntimeException $e) {
            return redirect()->back()->withErrors(['duplicate' => 'Reservasi ditolak, pasien sudah memiliki reservasi aktif pada waktu tersebut.']);
        }

        // Ensure rujukan/waktu/alasan fields persisted (defensive save + logging)
        try {
            Log::info('storeBpjs payload', $data);
            $noRujukan = isset($data['no_rujukan']) ? trim($data['no_rujukan']) : null;
            $reservasi->no_rujukan = $noRujukan !== '' ? $noRujukan : null;

            $tanggalRujukan = isset($data['tanggal_rujukan']) ? trim($data['tanggal_rujukan']) : null;
            $reservasi->tanggal_rujukan = $tanggalRujukan !== '' ? $tanggalRujukan : null;

            // Only persist `waktu` if it looks like a time (e.g. "09:17").
            $rawWaktu = isset($data['waktu']) ? trim($data['waktu']) : null;
            if (!empty($rawWaktu) && preg_match('/\d{1,2}:\d{2}/', $rawWaktu)) {
                $reservasi->waktu = $rawWaktu;
            } else {
                $reservasi->waktu = null;
            }

            if (isset($data['alasan_kontrol'])) $reservasi->alasan_kontrol = trim($data['alasan_kontrol']) ?: null;
            $reservasi->save();
        } catch (\Throwable $e) {
            Log::error('storeBpjs: failed to persist extra fields: '.$e->getMessage());
        }

        // Generate and persist nomor_antrian per poli for the reservation (safe, locks poli)
        // compute estimasi_pelayanan based on poli->estimasi_menit and nomor_antrian
        try {
            $estimasiMenit = optional($reservasi->poli)->estimasi_menit;
            $estimasiVal = null;
            if ($estimasiMenit !== null && $reservasi->nomor_antrian) {
                $num = 1;
                if (preg_match('/-(\d+)$/', $reservasi->nomor_antrian, $m)) {
                    $num = intval($m[1]);
                }
                $estimasiVal = ($num - 1) * (int)$estimasiMenit;
            }
            if ($estimasiVal !== null) {
                $reservasi->estimasi_pelayanan = $estimasiVal;
            }
        } catch (\Throwable $e) {
            Log::warning('Failed computing estimasi_pelayanan: '.$e->getMessage());
        }

        $reservasi->save();

        // Redirect with the actual kode_booking generated by the service
        $kode = $reservasi->kode_booking ?? $kode;
        return redirect()->route('bpjs.konfirmasi', ['kode' => $kode]);
    }

    public function storeUmum(Request $request)
    {
        $data = $request->validate([
            'no_ktp' => 'nullable|string|max:255',
            'nama_lengkap' => 'required|string|max:255',
            'telepon' => 'required|string|max:50',
            'poli_id' => 'required|integer',
            'dokter_id' => 'required|integer',
            'tanggal_reservasi' => 'required|date',
        ]);

        // defensive init: ensure $tanggal is defined even if input is missing or invalid
        $tanggal = $data['tanggal_reservasi'] ?? null;
        try {
            $tanggal = \Carbon\Carbon::parse($tanggal)->toDateString();
        } catch (\Exception $e) {
            // keep raw input if parsing fails
        }

        // If user is logged in, prefer their patient record to avoid mismatches
        $pasien = null;
        $user = Session::get('user');
        if ($user && isset($user['id'])) {
            $pasien = Pasien::find($user['id']);
        }

        // Otherwise try to locate by KTP or telepon as before
        if (!$pasien) {
            if (!empty($data['no_ktp'])) {
                $pasien = Pasien::where('no_ktp', $data['no_ktp'])->first();
            }
        }

        if (!$pasien) {
            $pasien = Pasien::where('telepon', $data['telepon'])->first();
        }

        if ($pasien) {
            $updated = false;
            if (empty($pasien->no_ktp) && !empty($data['no_ktp'])) { $pasien->no_ktp = $data['no_ktp']; $updated = true; }
            if (empty($pasien->nama_lengkap) && !empty($data['nama_lengkap'])) { $pasien->nama_lengkap = $data['nama_lengkap']; $updated = true; }
            if (!empty($data['telepon']) && ($pasien->telepon !== $data['telepon'])) { $pasien->telepon = $data['telepon']; $updated = true; }
            if ($updated) { $pasien->save(); }
        } else {
            $pasien = Pasien::create([
                'no_ktp' => $data['no_ktp'] ?? null,
                'nama_lengkap' => $data['nama_lengkap'],
                'telepon' => $data['telepon'],
            ]);
        }

        $kode = strtoupper('BK'.Str::random(6));

        // Periksa kuota dokter untuk UMUM
        $dokter = Dokter::find($data['dokter_id']);
        if (!$dokter) {
            return redirect()->back()->withErrors(['dokter' => 'Dokter tidak ditemukan.']);
        }

        $existing = Reservasi::where('dokter_id', $dokter->id)
            ->whereDate('tanggal_reservasi', $tanggal)
            ->where('cara_bayar', 'UMUM')
            ->whereIn('status', ['PENDING', 'VERIFIED'])
            ->count();

        Log::info('storeUmum: pre-quota check', [
            'patient_id' => $pasien->id,
            'tanggal_reservasi' => $data['tanggal_reservasi'],
            'dokter_id' => $dokter->id,
            'existing_count' => $existing,
            'kuota_umum' => $dokter->kuota_umum ?? null,
        ]);

        $service = new ReservationService();

        // Use centralized duplicate detection for consistency
        $duplicateForPatient = $service->hasDuplicate($pasien->id, $tanggal, $data['poli_id']);

        Log::info('storeUmum: duplicate check (central)', [
            'patient_id' => $pasien->id ?? null,
            'tanggal_reservasi' => $data['tanggal_reservasi'] ?? null,
            'poli_id' => $data['poli_id'] ?? null,
            'dokter_id' => $data['dokter_id'] ?? null,
            'duplicate' => $duplicateForPatient,
        ]);

        // If patient already has reservation for requested date and that date is today,
        // do NOT auto-move the reservation. Ask the patient to pick another date
        // manually so they can choose an available jadwal.
        if ($duplicateForPatient && \Carbon\Carbon::parse($data['tanggal_reservasi'])->isToday()) {
            Log::info('storeUmum: duplicate today - require user to pick another date', [
                'patient_id' => $pasien->id ?? null,
                'tanggal_reservasi' => $data['tanggal_reservasi'] ?? null,
                'poli_id' => $data['poli_id'] ?? null,
            ]);

            return redirect()->back()->withErrors(['duplicate' => 'Reservasi ditolak — Anda sudah memiliki reservasi aktif hari ini. Silakan pilih tanggal lain atau periksa jadwal poliklinik.']);
        }

        if ($duplicateForPatient) {
            return redirect()->back()->withErrors(['duplicate' => 'Reservasi ditolak, pasien sudah memiliki reservasi aktif pada waktu tersebut.']);
        }

        $limit = (int) ($dokter->kuota_umum ?? 0);
        if ($limit <= 0) {
            return redirect()->back()->withErrors(['kuota' => 'Dokter tidak menerima pasien UMUM pada tanggal tersebut.']);
        }
        if ($existing >= $limit) {
            return redirect()->back()->withErrors(['kuota' => 'Kuota UMUM untuk dokter ini sudah penuh pada tanggal tersebut.']);
        }

        $service = new ReservationService();
        try {
            $reservasi = $service->create([
                'patient_id' => $pasien->id,
                'cara_bayar' => 'UMUM',
                'poli_id' => $data['poli_id'],
                'dokter_id' => $data['dokter_id'],
                'tanggal_reservasi' => $data['tanggal_reservasi'],
            ]);
        } catch (\RuntimeException $e) {
            return redirect()->back()->withErrors(['duplicate' => 'Reservasi ditolak, pasien sudah memiliki reservasi aktif pada waktu tersebut.']);
        }

        // Redirect with the actual kode_booking generated by the service
        $kode = $reservasi->kode_booking ?? $kode;
        return redirect()->route('umum.konfirmasi', ['kode' => $kode]);
    }

    public function showUmumForm()
    {
        $clinics = Klinik::orderBy('nama_poli')->get();
        $doctors = Dokter::orderBy('nama')->get();
        $user = Session::get('user');
        $currentPatient = null;
        if ($user && isset($user['id'])) {
            $currentPatient = Pasien::find($user['id']);
        }
        return view('pendaftaran-pasien-lama.umum.create', compact('clinics', 'doctors', 'currentPatient'));
    }

    public function showBpjsForm(Request $request)
    {
        $clinics = Klinik::orderBy('nama_poli')->get();
        $doctors = Dokter::orderBy('nama')->get();
        $user = Session::get('user');
        $currentPatient = null;
        if ($user && isset($user['id'])) {
            $currentPatient = Pasien::find($user['id']);
        }

        $reservasi = null;
        $kode = $request->query('kode');
        if ($kode) {
            $reservasi = Reservasi::where('kode_booking', $kode)
                ->where('patient_id', $user['id'] ?? null)
                ->where('status', 'VERIFIED')
                ->first();
        }

        return view('pendaftaran-pasien-lama.bpjs.daftar', compact('clinics', 'doctors', 'currentPatient', 'reservasi'));
    }

    // Show standalone rujukan input page
    public function showRujukanForm()
    {
        $user = Session::get('user');
        $currentPatient = null;
        if ($user && isset($user['id'])) {
            $currentPatient = Pasien::find($user['id']);
        }
        return view('pendaftaran-pasien-lama.bpjs.rujukan', compact('currentPatient'));
    }

    // Store rujukan then redirect back to daftar form with query params
    public function storeRujukan(Request $request)
    {
        $data = $request->validate([
            'no_rujukan' => 'required|string|max:100',
            'tanggal_rujukan' => 'required|date',
            'waktu' => 'nullable|string|max:20',
        ]);

        $user = Session::get('user');
        if (!$user || !isset($user['id'])) {
            return redirect()->route('login')->withErrors(['login' => 'Silakan login terlebih dahulu.']);
        }

        $patientId = $user['id'];

        // Check if already have pending BPJS reservation with same no_rujukan
        $existing = Reservasi::where('patient_id', $patientId)
            ->where('cara_bayar', 'BPJS')
            ->where('no_rujukan', $data['no_rujukan'])
            ->whereIn('status', ['PENDING', 'VERIFIED'])
            ->first();

        if ($existing) {
            return redirect()->route('bpjs.index')->withErrors(['duplicate' => 'Rujukan dengan nomor tersebut sudah ada.']);
        }

        // Create pending reservation for BPJS referral
        $reservasi = Reservasi::create([
            'patient_id' => $patientId,
            'cara_bayar' => 'BPJS',
            'no_rujukan' => $data['no_rujukan'],
            'tanggal_rujukan' => $data['tanggal_rujukan'],
            'waktu' => $data['waktu'],
            'status' => 'PENDING',
            'kode_booking' => strtoupper('BK' . Str::random(6)),
            'tanggal_reservasi' => now()->toDateString(), // placeholder
        ]);

        // Redirect back to index
        return redirect()->route('bpjs.index')->with('success', 'Rujukan telah disimpan. Menunggu verifikasi admin.');
    }

    public function history(Request $request)
    {
        $user = Session::get('user');
        if (!$user || !isset($user['id'])) {
            return redirect()->route('home')->withErrors(['login' => 'Silakan login terlebih dahulu untuk melihat riwayat.']);
        }

        $patientId = $user['id'];

        // Order by newest reservation first (by creation time)
        $reservations = Reservasi::with(['pasien', 'poli', 'dokter'])
            ->where('patient_id', $patientId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pendaftaran-pasien-lama.history', compact('reservations'));
    }

    // Return reservations for current patient as JSON (for realtime polling)
    public function historyJson(Request $request)
    {
        $user = Session::get('user');
        if (!$user || !isset($user['id'])) {
            return response()->json(['error' => 'not_logged_in'], 401);
        }

        $patientId = $user['id'];

        $reservations = Reservasi::with(['pasien', 'poli', 'dokter', 'jadwal'])
            ->where('patient_id', $patientId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($r){
                $status = strtoupper($r->status ?? 'PENDING');
                $jam = null;
                if ($r->jadwal && ($r->jadwal->jam_mulai ?? null)) {
                    try { $jam = \Carbon\Carbon::parse($r->jadwal->jam_mulai)->format('H:i'); } catch (\Exception $e) { $jam = null; }
                }
                if (!$jam && $r->waktu) {
                    try { $jam = \Carbon\Carbon::parse($r->waktu)->format('H:i'); } catch (\Exception $e) { $jam = $r->waktu; }
                }

                $estimasi = null;
                if (optional($r->poli)->estimasi_menit !== null) {
                    $estimasi = optional($r->poli)->estimasi_menit;
                } elseif ($r->estimasi_pelayanan !== null && $r->estimasi_pelayanan !== '') {
                    $estimasi = $r->estimasi_pelayanan;
                }

                return [
                    'id' => $r->id,
                    'status' => $status,
                    'jam' => $jam,
                    'waktu_label' => $r->waktu_label ?? null,
                    'estimasi_waktu' => $r->estimasi_waktu ?? null,
                    'estimasi' => $estimasi,
                    'kode_booking' => $r->kode_booking,
                    'nomor_antrian' => $r->nomor_antrian,
                ];
            });

        return response()->json($reservations);
    }

    public function cancel(Request $request, $id)
    {
        $user = Session::get('user');
        if (!$user || !isset($user['id'])) {
            return redirect()->route('home')->withErrors(['login' => 'Silakan login terlebih dahulu.']);
        }

        $patientId = $user['id'];

        $reservasi = Reservasi::where('id', $id)->where('patient_id', $patientId)->first();
        if (!$reservasi) {
            return redirect()->back()->withErrors(['not_found' => 'Reservasi tidak ditemukan atau bukan milik Anda.']);
        }

        $data = $request->validate([
            'alasan_batal' => 'required|string',
        ]);

        // mark as cancelled and store reason
        $reservasi->status = 'CANCELLED';
        $reservasi->cancellation_reason = $data['alasan_batal'];
        $reservasi->save();

        return redirect()->route('pendaftaran-pasien-lama.history')->with('success', 'Reservasi berhasil dibatalkan.');
    }

    // Show BPJS confirmation page with reservation data
    public function showBpjsConfirmation(Request $request)
    {
        // Prefer explicit kode query parameter over flashed session data
        $kode = $request->query('kode') ?? session('kode_booking');
        $reservasi = null;
        if ($kode) {
            $reservasi = Reservasi::with(['pasien', 'poli', 'dokter'])->where('kode_booking', $kode)->first();
        }

        // fallback: if user logged in, show latest BPJS reservation
        $user = Session::get('user');
        if (!$reservasi && $user && isset($user['id'])) {
            $reservasi = Reservasi::with(['pasien', 'poli', 'dokter'])
                ->where('patient_id', $user['id'])
                ->where('cara_bayar', 'BPJS')
                ->orderBy('created_at', 'desc')
                ->first();
        }

        return view('pendaftaran-pasien-lama.bpjs.konfirmasi', compact('reservasi'));
    }

    // Show UMUM confirmation page with reservation data
    public function showUmumConfirmation(Request $request)
    {
        // Prefer explicit kode query parameter over flashed session data
        $kode = $request->query('kode') ?? session('kode_booking');
        $reservasi = null;
        if ($kode) {
            $reservasi = Reservasi::with(['pasien', 'poli', 'dokter'])->where('kode_booking', $kode)->first();
        }

        $user = Session::get('user');
        if (!$reservasi && $user && isset($user['id'])) {
            $reservasi = Reservasi::with(['pasien', 'poli', 'dokter'])
                ->where('patient_id', $user['id'])
                ->where('cara_bayar', 'UMUM')
                ->orderBy('created_at', 'desc')
                ->first();
        }

        return view('pendaftaran-pasien-lama.umum.konfirmasi', compact('reservasi'));
    }

    // Menu verifikasi (simple view)
    public function showBpjsMenuVerifikasi()
    {
        return view('pendaftaran-pasien-lama.bpjs.menu-verifikasi');
    }

    // Show verifikasi list for BPJS
    public function showBpjsVerifikasi()
    {
        $user = Session::get('user');
        if (!$user || !isset($user['id'])) {
            return redirect()->route('home')->withErrors(['login' => 'Silakan login terlebih dahulu untuk melihat verifikasi.']);
        }

        $reservations = Reservasi::with(['pasien', 'poli', 'dokter'])
            ->where('patient_id', $user['id'])
            ->where('cara_bayar', 'BPJS')
            ->orderBy('tanggal_reservasi', 'desc')
            ->get();

        // verifikasi page removed; redirect user to history
        return redirect()->route('pendaftaran-pasien-lama.history');
    }

    // Show verifikasi list for UMUM
    public function showUmumVerifikasi()
    {
        $user = Session::get('user');
        if (!$user || !isset($user['id'])) {
            return redirect()->route('home')->withErrors(['login' => 'Silakan login terlebih dahulu untuk melihat verifikasi.']);
        }

        $reservations = Reservasi::with(['pasien', 'poli', 'dokter'])
            ->where('patient_id', $user['id'])
            ->where('cara_bayar', 'UMUM')
            ->orderBy('tanggal_reservasi', 'desc')
            ->get();

        // verifikasi page removed; redirect user to history
        return redirect()->route('pendaftaran-pasien-lama.history');
    }

    // Confirm (patient) — UMUM: validate that provided confirmation data matches reservation
    public function confirmUmum(Request $request)
    {
        $data = $request->validate([
            'kode_booking' => 'required|string',
            'nama_lengkap' => 'required|string',
            'telepon' => 'required|string',
        ]);

        $reservasi = Reservasi::with('pasien')->where('kode_booking', $data['kode_booking'])->first();
        if (!$reservasi) {
            return redirect()->back()->withErrors(['not_found' => 'Reservasi tidak ditemukan.']);
        }

        $givenName = mb_strtolower(preg_replace('/\s+/', '', $data['nama_lengkap']));
        $storedName = mb_strtolower(preg_replace('/\s+/', '', $reservasi->pasien->nama_lengkap ?? ''));

        $givenPhone = preg_replace('/\D+/', '', $data['telepon']);
        $storedPhone = preg_replace('/\D+/', '', $reservasi->pasien->telepon ?? '');

        if ($givenName !== $storedName || $givenPhone !== $storedPhone) {
            return redirect()->back()->withErrors(['mismatch' => 'Data konfirmasi tidak sesuai dengan data reservasi. Silakan periksa kembali.']);
        }

        // Patient confirmation received — now redirect user to history page.
        return redirect()->route('pendaftaran-pasien-lama.history')->with('success', 'Konfirmasi diterima. Silakan lihat riwayat pendaftaran Anda.');
    }

    // Confirm (patient) — BPJS: validate that provided confirmation data matches reservation
    public function confirmBpjs(Request $request)
    {
        $data = $request->validate([
            'kode_booking' => 'required|string',
            'nama_lengkap' => 'required|string',
            'telepon' => 'required|string',
        ]);

        $reservasi = Reservasi::with('pasien')->where('kode_booking', $data['kode_booking'])->first();
        if (!$reservasi) {
            return redirect()->back()->withErrors(['not_found' => 'Reservasi tidak ditemukan.']);
        }

        $givenName = mb_strtolower(preg_replace('/\s+/', '', $data['nama_lengkap']));
        $storedName = mb_strtolower(preg_replace('/\s+/', '', $reservasi->pasien->nama_lengkap ?? ''));

        $givenPhone = preg_replace('/\D+/', '', $data['telepon']);
        $storedPhone = preg_replace('/\D+/', '', $reservasi->pasien->telepon ?? '');

        if ($givenName !== $storedName || $givenPhone !== $storedPhone) {
            return redirect()->back()->withErrors(['mismatch' => 'Data konfirmasi tidak sesuai dengan data reservasi. Silakan periksa kembali.']);
        }

        // Patient confirmation received — now redirect user to history page.
        return redirect()->route('pendaftaran-pasien-lama.history')->with('success', 'Konfirmasi diterima. Silakan lihat riwayat pendaftaran Anda.');
    }

    // Patient-facing PDF print for their reservation (only if VERIFIED)
    public function print(Request $request, $id)
    {
        $user = Session::get('user');
        if (!$user || !isset($user['id'])) {
            return redirect()->route('home')->withErrors(['login' => 'Silakan login terlebih dahulu.']);
        }

        $reservasi = Reservasi::with(['pasien', 'poli', 'dokter', 'jadwal'])->find($id);
        if (!$reservasi) {
            return redirect()->back()->withErrors(['not_found' => 'Reservasi tidak ditemukan.']);
        }

        if ($reservasi->patient_id != $user['id']) {
            return redirect()->back()->withErrors(['forbidden' => 'Anda tidak berhak mengunduh reservasi ini.']);
        }

        if (($reservasi->status ?? 'PENDING') !== 'VERIFIED') {
            return redirect()->back()->withErrors(['not_verified' => 'Reservasi belum terverifikasi oleh petugas.']);
        }

        $view = $reservasi->cara_bayar === 'BPJS' ? 'pendaftaran-pasien-lama.bpjs.reservasi-print' : 'pendaftaran-pasien-lama.umum.reservasi-print';

        // Log jadwal for debugging (temporary)
        try {
            Log::info('print.reservasi.jadwal', ['id' => $reservasi->id ?? null, 'jadwal' => $reservasi->jadwal]);
        } catch (\Throwable $e) {
            // ignore
        }

        // Return the HTML view so the browser can render and generate PDF client-side
        return view($view, compact('reservasi'));
    }

    /**
     * Server-side PDF for patient reservation (stream or download)
     */
    public function printPdf(Request $request, $id)
    {
        $user = Session::get('user');
        if (!$user || !isset($user['id'])) {
            return redirect()->route('home')->withErrors(['login' => 'Silakan login terlebih dahulu.']);
        }

        $reservasi = Reservasi::with(['pasien', 'poli', 'dokter', 'jadwal'])->find($id);
        if (!$reservasi) {
            return redirect()->back()->withErrors(['not_found' => 'Reservasi tidak ditemukan.']);
        }

        if ($reservasi->patient_id != $user['id']) {
            return redirect()->back()->withErrors(['forbidden' => 'Anda tidak berhak mengunduh reservasi ini.']);
        }

        if (($reservasi->status ?? 'PENDING') !== 'VERIFIED') {
            return redirect()->back()->withErrors(['not_verified' => 'Reservasi belum terverifikasi oleh petugas.']);
        }

        $view = $reservasi->cara_bayar === 'BPJS' ? 'pendaftaran-pasien-lama.bpjs.reservasi-print' : 'pendaftaran-pasien-lama.umum.reservasi-print';

        // generate QR for server-side PDF similar to PasienBaruController
        $qr = null;
        try {
            $kode = $reservasi->kode_booking ?? '';
            if (!empty($kode)) {
                $qrUrl = 'https://chart.googleapis.com/chart?cht=qr&chs=300x300&chl=' . urlencode($kode) . '&chld=L|1';
                $response = \Illuminate\Support\Facades\Http::timeout(5)->get($qrUrl);
                if ($response->ok()) {
                    $raw = $response->body();
                    $qr = base64_encode($raw);
                }
            }
        } catch (\Throwable $e) {
            // ignore
        }

        try {
            Log::info('printPdf.reservasi.jadwal', ['id' => $reservasi->id ?? null, 'jadwal' => $reservasi->jadwal]);
        } catch (\Throwable $e) {
            // ignore
        }

        $pdf = Pdf::loadView($view, compact('reservasi', 'qr'));
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOptions(['isRemoteEnabled' => true]);
        return $pdf->stream('bukti_reservasi_' . ($reservasi->kode_booking ?? $reservasi->id) . '.pdf');
    }

    // Return current patient data as JSON (used by frontend)
    public function currentPatient(Request $request)
    {
        $user = Session::get('user');
        if (!$user || !isset($user['id'])) {
            return response()->json(['error' => 'not_logged_in'], 401);
        }

        $patient = Pasien::with('alamat')->find($user['id']);
        if (!$patient) {
            return response()->json(['error' => 'not_found'], 404);
        }

        // attempt to resolve wilayah names for alamat (so frontend shows names instead of ids)
        try {
            if ($patient->alamat) {
                $names = $this->resolveWilayahNames($patient->alamat);
                $patient->alamat->provinsi_name = $names['provinsi'] ?? ($patient->alamat->provinsi ?? null);
                $patient->alamat->kabupaten_name = $names['kabupaten'] ?? ($patient->alamat->kabupaten ?? null);
                $patient->alamat->kecamatan_name = $names['kecamatan'] ?? ($patient->alamat->kecamatan ?? null);
                $patient->alamat->kelurahan_name = $names['kelurahan'] ?? ($patient->alamat->kelurahan ?? null);
            }
        } catch (\Throwable $e) {
            // ignore resolution errors
        }

        return response()->json($patient);
    }

    /**
     * Resolve wilayah names (provinsi/kabupaten/kecamatan/kelurahan) from stored ids.
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
