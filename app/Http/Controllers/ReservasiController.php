<?php

namespace App\Http\Controllers;

use App\Models\Reservasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ReservationService;
use Carbon\Carbon;

class ReservasiController extends Controller
{
    // Menambahkan pembatasan reservasi pasien
    public function __construct()
    {
        $this->middleware('auth')->only(['store', 'index']);
    }

    public function index()
    {
        // Logic to display reservation list
    }

    // Logika untuk memeriksa reservasi aktif
    public function store(Request $request)
    {
        // accept either 'tanggal_reservasi' (new) or 'tanggal' (old)
        $date = $request->input('tanggal_reservasi', $request->input('tanggal'));
        try {
            $date = Carbon::parse($date)->toDateString();
        } catch (\Exception $e) {
            // keep original if parse fails
        }
        $poliId = $request->input('poli_id', $request->input('poli'));
        $dokterId = $request->input('dokter_id', $request->input('dokter_id'));

        $request->validate([
            'pasien_id' => 'sometimes|integer',
            'patient_id' => 'sometimes|integer',
            'tanggal_reservasi' => 'required_without:tanggal|date',
            'tanggal' => 'required_without:tanggal_reservasi|date',
            'poli_id' => 'required_without:poli',
            'poli' => 'required_without:poli_id',
        ]);

        // normalize patient id
        $patientId = $request->input('patient_id', $request->input('pasien_id'));
        if (!$patientId && Auth::check()) {
            $patientId = Auth::id();
        }

        // check duplicates across both naming conventions and status variants
        $statusVariants = ['PENDING', 'VERIFIED', 'menunggu', 'terverifikasi'];

        $duplicate = Reservasi::where('patient_id', $patientId)
            ->where(function($q) use ($date) {
                $q->whereDate('tanggal_reservasi', $date)
                  ->orWhereDate('tanggal', $date);
            })
            ->where(function($q) use ($poliId) {
                $q->where('poli_id', $poliId)
                  ->orWhere('poli', $poliId);
            })
            ->whereIn('status', $statusVariants)
            ->exists();

        if ($duplicate) {
            return response()->json(['message' => 'Reservasi ditolak, pasien sudah memiliki reservasi aktif pada waktu tersebut.'], 400);
        }

        $service = new ReservationService();
        try {
            $reservasi = $service->create([
                'patient_id' => $patientId,
                'poli_id' => $poliId,
                'dokter_id' => $dokterId,
                'tanggal_reservasi' => $date,
            ]);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => 'Reservasi ditolak, pasien sudah memiliki reservasi aktif pada waktu tersebut.'], 400);
        }

        return response()->json(['message' => 'Reservasi berhasil dibuat.'], 201);
    }
}