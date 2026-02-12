<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasienBaruController;
use App\Http\Controllers\BpjsController;
use App\Models\Reservation;
use App\Models\Reservasi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DokterController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\KlinikController;
use App\Http\Controllers\Admin\PoliController;
use App\Http\Controllers\Admin\ReservasiController;


// Halaman utama (Login)
Route::get('/', [AuthController::class, 'index'])->name('home');

// Proses Login
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Logout (accept POST for form-based logout and GET for compatibility)
Route::match(['get','post'], '/logout', [AuthController::class, 'logout'])->name('logout');

// Jadwal Dokter
Route::get('/jadwal', function () {
    return view('jadwal.index');
})->name('jadwal');

// Pendaftaran Pasien Baru
Route::get('/pendaftaran/pasien-baru', [PasienBaruController::class, 'index'])->name('pendaftaran.pasien-baru');
Route::post('/pendaftaran/pasien-baru', [PasienBaruController::class, 'store'])->name('pendaftaran.pasien-baru.store');
Route::get('/pendaftaran/pasien-baru/dokter/{poliId}', [PasienBaruController::class, 'getDokterByPoli'])->name('pendaftaran.pasien-baru.dokter');
Route::post('/pendaftaran/riwayat/search', [PasienBaruController::class, 'searchRiwayat'])->name('pendaftaran.riwayat.search');
Route::get('/pendaftaran/booking/print/{reservasiId}', [PasienBaruController::class, 'printBooking'])->name('pendaftaran.booking.print');
// Server-side PDF for booking (same layout as booking-print)
Route::get('/pendaftaran/booking/pdf/{reservasiId}', [PasienBaruController::class, 'printBookingPdf'])->name('pendaftaran.booking.pdf');

// Debug route (local use): inspect reservations matching a value
Route::get('/debug/riwayat/{value}', [PasienBaruController::class, 'debugSearchBooking'])->name('debug.riwayat.search');


// Refresh captcha (AJAX) - returns a new captcha image src
Route::get('/refresh-captcha', function () {
    return response()->json(['captcha' => captcha_src()]);
})->name('refresh.captcha');

// Menu Pendaftaran (setelah login)
Route::get('/pendaftaran-pasien-lama/index', function () {
    return view('pendaftaran-pasien-lama.index');
})->name('pendaftaran-pasien-lama.index');

Route::get('/pendaftaran-pasien-lama/menu', function () {
    return view('pendaftaran-pasien-lama.menu');
})->name('pendaftaran-pasien-lama.menu');

Route::get('/pendaftaran-pasien-lama/history', [\App\Http\Controllers\PendaftaranLamaController::class, 'history'])
    ->name('pendaftaran-pasien-lama.history');
Route::get('/pendaftaran-pasien-lama/history/json', [\App\Http\Controllers\PendaftaranLamaController::class, 'historyJson'])
    ->name('pendaftaran-pasien-lama.history.json');
Route::post('/pendaftaran-pasien-lama/history/cancel/{id}', [\App\Http\Controllers\PendaftaranLamaController::class, 'cancel'])
    ->name('pendaftaran-pasien-lama.cancel');

Route::prefix('bpjs')->group(function () {
        Route::view('/', 'pendaftaran-pasien-lama.bpjs.index')->name('bpjs.index');
        Route::get('/create', [\App\Http\Controllers\PendaftaranLamaController::class, 'showBpjsForm'])->name('bpjs.create');
        Route::get('/daftar', [\App\Http\Controllers\PendaftaranLamaController::class, 'showBpjsForm'])->name('bpjs.daftar');
        Route::get('/rujukan', [\App\Http\Controllers\PendaftaranLamaController::class, 'showRujukanForm'])->name('bpjs.rujukan');
        Route::get('/rujukan/create', [\App\Http\Controllers\PendaftaranLamaController::class, 'showRujukanForm'])->name('bpjs.rujukan.create');
        Route::post('/rujukan', [\App\Http\Controllers\PendaftaranLamaController::class, 'storeRujukan'])->name('bpjs.rujukan.store');
        Route::post('/daftar', [\App\Http\Controllers\PendaftaranLamaController::class, 'storeBpjs'])->name('bpjs.daftar.post');
        Route::get('/konfirmasi', [\App\Http\Controllers\PendaftaranLamaController::class, 'showBpjsConfirmation'])->name('bpjs.konfirmasi');
        Route::post('/konfirmasi/complete', [\App\Http\Controllers\PendaftaranLamaController::class, 'confirmBpjs'])->name('bpjs.konfirmasi.post');
        Route::get('/menu-verifikasi',[\App\Http\Controllers\PendaftaranLamaController::class, 'showBpjsMenuVerifikasi'])->name('bpjs.menu-verifikasi');
        Route::get('/verifikasi',[\App\Http\Controllers\PendaftaranLamaController::class, 'showBpjsVerifikasi'])->name('bpjs.verifikasi');
        Route::get('/reservasi/print', function () {$pdf = Pdf::loadView('pendaftaran-pasien-lama.bpjs.reservasi-print');return $pdf->stream('reservasi.pdf');})->name('bpjs.reservasi-print');
    });
Route::prefix('umum')->group(function () {
        Route::view('/','pendaftaran-pasien-lama.umum.index')->name('umum.index');
        Route::get('/create',[\App\Http\Controllers\PendaftaranLamaController::class, 'showUmumForm'])->name('umum.create');
        Route::get('/konfirmasi',[\App\Http\Controllers\PendaftaranLamaController::class, 'showUmumConfirmation'])->name('umum.konfirmasi');
        Route::post('/konfirmasi/complete',[\App\Http\Controllers\PendaftaranLamaController::class, 'confirmUmum'])->name('umum.konfirmasi.post');
        Route::post('/create', [\App\Http\Controllers\PendaftaranLamaController::class, 'storeUmum'])->name('umum.create.post');
        Route::get('/verifikasi',[\App\Http\Controllers\PendaftaranLamaController::class, 'showUmumVerifikasi'])->name('umum.verifikasi');
    });

Route::get('/reservasi/print', function () {
    $pdf = Pdf::loadView('pendaftaran-pasien-lama.umum.reservasi-print');
    return $pdf->stream('reservasi.pdf');
})->name('reservasi.pdf');

// Patient-facing print for their reservation (only if VERIFIED)
Route::get('/pendaftaran-pasien-lama/reservasi/{id}/print', [\App\Http\Controllers\PendaftaranLamaController::class, 'print'])
    ->name('pendaftaran-pasien-lama.print');

// Server-side PDF for patient reservation (same content as the print view)
Route::get('/pendaftaran-pasien-lama/reservasi/{id}/pdf', [\App\Http\Controllers\PendaftaranLamaController::class, 'printPdf'])
    ->name('pendaftaran-pasien-lama.pdf');

// Debug route: return reservations matching a given value (booking code or KTP)
if (config('app.debug')) {
    Route::get('/debug/riwayat/{value}', function($value) {
        $normalized = mb_strtolower(preg_replace('/\s+/', '', $value));
        $q = Reservasi::with(['pasien', 'poli', 'dokter'])
            ->whereRaw("LOWER(kode_booking) LIKE ?", ['%'.strtolower($value).'%'])
            ->orWhereHas('pasien', function($q2) use ($normalized) {
                $q2->whereRaw("REPLACE(LOWER(no_ktp), ' ', '') LIKE ?", ["%{$normalized}%"]);
            })
            ->orderBy('created_at', 'desc')
            ->limit(50);

        $results = $q->get();
        return response()->json([
            'count' => $results->count(),
            'query_value' => $value,
            'results' => $results
        ]);
    })->name('debug.riwayat');
    Route::get('/debug/reservasi/{kode}', function($kode) {
        $reservasi = \App\Models\Reservasi::with(['pasien','poli','dokter'])
            ->where('kode_booking', $kode)
            ->first();

        return response()->json([
            'request_kode' => $kode,
            'session_user' => session('user'),
            'reservasi' => $reservasi
        ]);
    })->name('debug.reservasi');
};
Route::prefix('admin')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])
        ->name('admin.dashboard');

   Route::resource('dokter', DokterController::class);
    Route::resource('jadwal', JadwalController::class);
    Route::resource('poli', PoliController::class);
    Route::post('poli/{poli}/toggle-pelayanan', [\App\Http\Controllers\Admin\PoliController::class, 'togglePelayanan'])->name('poli.toggle-pelayanan');

     Route::get('reservasi', [\App\Http\Controllers\Admin\ReservasiController::class, 'index'])
        ->name('admin.reservasi.index');
    Route::get('reservasi/{id}/verify', [\App\Http\Controllers\Admin\ReservasiController::class, 'showVerifyForm'])->name('admin.reservasi.verify.form');
    Route::post('reservasi/{id}/verify', [\App\Http\Controllers\Admin\ReservasiController::class, 'verify'])->name('admin.reservasi.verify');
    Route::get('reservasi/{id}/reject', [\App\Http\Controllers\Admin\ReservasiController::class, 'showRejectForm'])->name('admin.reservasi.reject.form');
    Route::post('reservasi/{id}/reject', [\App\Http\Controllers\Admin\ReservasiController::class, 'reject'])->name('admin.reservasi.reject');
    Route::get('reservasi/{id}/print', [\App\Http\Controllers\Admin\ReservasiController::class, 'print'])->name('admin.reservasi.print');
});
    Route::get('/api/jadwal', [\App\Http\Controllers\JadwalApiController::class, 'index'])
    ->name('api.jadwal');
    
    // Simple test route for custom BpjsController
    Route::post('/bpjs/validate', [\App\Http\Controllers\BpjsController::class, 'store'])->name('bpjs.validate');

// Current authenticated patient data (used by frontend account dropdown)
Route::get('/api/patient/current', [\App\Http\Controllers\PendaftaranLamaController::class, 'currentPatient'])
    ->name('api.patient.current');

    Route::get('/api/jadwal/summary', [\App\Http\Controllers\JadwalApiController::class, 'summary'])
        ->name('api.jadwal.summary');

    Route::get('/api/jadwal/date/{date}/poli/{poliId}', [\App\Http\Controllers\JadwalApiController::class, 'detailsByDatePoli'])
        ->name('api.jadwal.details');

    Route::get('/api/jadwal/date/{date}/polis', [\App\Http\Controllers\JadwalApiController::class, 'polisByDate'])
        ->name('api.jadwal.polis');

// API: dokter by poli
Route::get('/api/dokter', [\App\Http\Controllers\DokterApiController::class, 'index'])
    ->name('api.dokter');

Route::prefix('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
});
