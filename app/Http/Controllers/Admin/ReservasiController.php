<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservasi;
use App\Models\Klinik;
use Carbon\Carbon;
use App\Helpers\TimePeriod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReservasiController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->query('q', ''));

        $query = Reservasi::with([
            'pasien',
            'poli',
            'dokter',
            'jadwal',
        ])->orderBy('created_at', 'desc');

        if (!empty($q)) {
            $query->where(function($qb) use ($q) {
                $qb->where('kode_booking', 'like', "%{$q}%")
                    ->orWhereHas('pasien', function($q2) use ($q) {
                        $q2->where('nama_lengkap', 'like', "%{$q}%");
                    })
                    ->orWhereHas('poli', function($q3) use ($q) {
                        $q3->where('nama_poli', 'like', "%{$q}%");
                    });
            });
        }

        // paginate results so view can render links
        $reservasis = $query->paginate(20)->withQueryString();

        // compute displayable waktu and estimasi for each reservation from jadwal
        $reservasis->getCollection()->each(function($r){
            $r->computed_waktu = '-';
            $r->computed_estimasi = '-';
            if ($r->jadwal) {
                $jamMulai = $r->jadwal->jam_mulai ?? null;
                if ($jamMulai) {
                    $r->computed_estimasi = Carbon::parse($jamMulai)->format('H:i');
                    $r->computed_waktu = $this->determineWaktu($jamMulai);
                }
            }
        });

        return view('admin.reservasi.index', compact('reservasis', 'q'));
    }

    // Verify a reservation (mark as VERIFIED)
    public function verify(Request $request, $id)
    {
        $reservasi = Reservasi::with('jadwal')->find($id);
        if (!$reservasi) {
            return redirect()->back()->withErrors(['not_found' => 'Reservasi tidak ditemukan']);
        }


        // require alasan_kontrol for verification
        $data = $request->validate([
            'alasan_kontrol' => 'required|string'
        ]);

        // Save provided fields
        $reservasi->nomor_antrian = $request->input('nomor_antrian') ?? $reservasi->nomor_antrian;
        $reservasi->alasan_kontrol = $data['alasan_kontrol'] ?? $reservasi->alasan_kontrol;

        // Use scheduled jadwal->jam_mulai as the displayed waktu when available,
        // otherwise fallback to current time. Use poli->estimasi_menit for estimasi.
        if ($reservasi->jadwal && ($reservasi->jadwal->jam_mulai ?? null)) {
            try {
                $reservasi->waktu = Carbon::parse($reservasi->jadwal->jam_mulai)->format('H:i');
            } catch (\Exception $e) {
                $reservasi->waktu = Carbon::now()->format('H:i');
            }
        } else {
            $reservasi->waktu = Carbon::now()->format('H:i');
        }

        if (optional($reservasi->poli)->estimasi_menit !== null) {
            $reservasi->estimasi_pelayanan = optional($reservasi->poli)->estimasi_menit;
        }


        // If nomor_antrian not provided, generate next number per poli + date
        if (empty($reservasi->nomor_antrian)) {
            if (strtoupper($reservasi->cara_bayar) === 'BPJS' && $reservasi->poli_id) {
                $reservasi->nomor_antrian = Reservasi::generateNextNomorBpjs($reservasi->poli_id, $reservasi->tanggal_reservasi);
            } elseif (strtoupper($reservasi->cara_bayar) !== 'BPJS') {
                $reservasi->nomor_antrian = Reservasi::generateNextNomor($reservasi->poli_id, $reservasi->tanggal_reservasi);
            }
            $reservasi->status = 'VERIFIED';
            $reservasi->save();
        } else {
            $reservasi->status = 'VERIFIED';
            $reservasi->save();
        }

        return redirect()->route('admin.reservasi.index')->with('success', 'Reservasi telah diverifikasi.');
    }

    private function determineWaktu($time)
    {
        if (empty($time)) return '-';
        try {
            $period = TimePeriod::current($time);
        } catch (\Exception $e) {
            return '-';
        }

        switch ($period) {
            case TimePeriod::PERIOD_PAGI:
                return 'Pagi';
            case TimePeriod::PERIOD_SIANG:
                return 'Siang';
            case TimePeriod::PERIOD_SORE:
                return 'Sore';
            default:
                return 'Malam';
        }
    }

    // Show reject form (GET)
    public function showRejectForm($id)
    {
        $reservasi = Reservasi::with(['pasien', 'poli', 'dokter'])->find($id);
        if (!$reservasi) {
            return redirect()->back()->withErrors(['not_found' => 'Reservasi tidak ditemukan']);
        }

        return view('admin.reservasi.reject', compact('reservasi'));
    }

    // Show verify form (GET) — require admin to fill alasan_kontrol similar to reject
    public function showVerifyForm($id)
    {
        $reservasi = Reservasi::with(['pasien', 'poli', 'dokter', 'jadwal'])->find($id);
        if (!$reservasi) {
            return redirect()->back()->withErrors(['not_found' => 'Reservasi tidak ditemukan']);
        }

        // compute waktu/estimasi from jadwal if available
        $computed_waktu = '-';
        $computed_estimasi = '-';
        if ($reservasi->jadwal && ($reservasi->jadwal->jam_mulai ?? null)) {
            $computed_estimasi = \Carbon\Carbon::parse($reservasi->jadwal->jam_mulai)->format('H:i');
            $computed_waktu = $this->determineWaktu($reservasi->jadwal->jam_mulai);
        }

        return view('admin.reservasi.verify', compact('reservasi', 'computed_waktu', 'computed_estimasi'));
        
    }

    // Reject a reservation (mark as REJECTED)
    public function reject(Request $request, $id)
    {
        $data = $request->validate([
            'alasan_batal' => 'required|string',
        ]);

        $reservasi = Reservasi::find($id);
        if (!$reservasi) {
            return redirect()->back()->withErrors(['not_found' => 'Reservasi tidak ditemukan']);
        }

        $reservasi->status = 'REJECTED';
        $reservasi->cancellation_reason = $data['alasan_batal'];
        $reservasi->save();

        return redirect()->route('admin.reservasi.index')->with('success', 'Reservasi ditolak.');
    }

    // Cancel a reservation (mark as CANCELLED) by admin, requires reason
    public function cancel(Request $request, $id)
    {
        $request->validate([
            'alasan_batal' => 'required|string',
        ]);

        $reservasi = Reservasi::find($id);
        if (!$reservasi) {
            return redirect()->back()->withErrors(['not_found' => 'Reservasi tidak ditemukan']);
        }

        $reservasi->status = 'CANCELLED';
        $reservasi->cancellation_reason = $request->input('alasan_batal');
        $reservasi->save();

        return redirect()->route('admin.reservasi.index')->with('success', 'Reservasi dibatalkan.');
    }

    // Print reservation PDF (only if VERIFIED)
    public function print(Request $request, $id)
    {
        $reservasi = Reservasi::with(['pasien', 'poli', 'dokter'])->find($id);
        if (!$reservasi) {
            return redirect()->back()->withErrors(['not_found' => 'Reservasi tidak ditemukan']);
        }

        if (($reservasi->status ?? 'PENDING') !== 'VERIFIED') {
            return redirect()->back()->withErrors(['not_verified' => 'Reservasi belum terverifikasi.']);
        }

        $pdf = Pdf::loadView('admin.reservasi.print', compact('reservasi'));

        return $pdf->stream('reservasi_' . ($reservasi->kode_booking ?? $reservasi->id) . '.pdf');
    }
}
