<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dokter;
use Carbon\Carbon;

class DokterApiController extends Controller
{
    /**
     * Return doctors optionally filtered by poli_id
     */
    public function index(Request $request)
    {
        $poli = $request->query('poli_id');
        $tanggal = $request->query('tanggal');
        if ($tanggal) {
            try {
                $tanggal = Carbon::parse($tanggal)->toDateString();
            } catch (\Exception $e) {
                // keep original
            }
        }
        $caraBayar = strtoupper($request->query('cara_bayar', 'UMUM'));

        $q = Dokter::query();
        if ($poli) {
            $q->where('poli_id', $poli);
        }

        $doctors = $q->orderBy('nama')->get();

        // If tanggal provided, filter out doctors whose quota for that payment type is already full
        if ($tanggal) {
            $result = [];
            foreach ($doctors as $d) {
                // ensure doctor has a jadwal on the requested date
                $jadwal = \App\Models\Jadwal::where('dokter_id', $d->id)->whereDate('tanggal', $tanggal)->first();
                if (!$jadwal) continue;

                $quotaField = $caraBayar === 'BPJS' ? 'kuota_bpjs' : 'kuota_umum';
                $limit = (int) ($d->{$quotaField} ?? 0);

                if ($limit > 0) {
                    $existing = \App\Models\Reservasi::where('dokter_id', $d->id)
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

        // No tanggal -> return full list (no quota filtering)
        $out = $doctors->map(function($d){
            return [
                'id' => $d->id,
                'nama' => $d->nama,
                'poli_id' => $d->poli_id,
                'kuota_umum' => $d->kuota_umum,
                'kuota_bpjs' => $d->kuota_bpjs,
                'remaining' => null,
            ];
        })->values();

        return response()->json($out);
    }
}
