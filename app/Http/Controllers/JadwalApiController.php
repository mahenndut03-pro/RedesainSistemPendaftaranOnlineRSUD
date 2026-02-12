<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Reservasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class JadwalApiController extends Controller
{
    public function index()
    { 
        $colors = [
            'Umum' => '#2563EB',
            'THT' => '#059669',
            'Mata' => '#7C3AED',
            'Rehabilitasi Medik' => '#DC2626',
        ];

        $jadwals = Jadwal::with(['dokter.poli'])->get()->map(function ($j) use ($colors) {

            $poli = $j->dokter->poli->nama_poli ?? 'Lainnya';

            return [
                'title' => $j->dokter->nama,
                'start' => $j->tanggal,
                'backgroundColor' => $colors[$poli] ?? '#0EA5E9',
                'borderColor' => 'transparent',

                // custom data
                'extendedProps' => [
                    'poli' => $poli,
                    'jam' => \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') . ' - ' . \Carbon\Carbon::parse($j->jam_selesai)->format('H:i'),
                ]
            ];
        });

        return response()->json($jadwals);
    }

    /**
     * Return aggregated summary per date (and per poli counts) between start and end dates.
     * Query params: start=YYYY-MM-DD, end=YYYY-MM-DD
     */
    public function summary(Request $request)
    {
        $start = $request->query('start') ?? now()->startOfMonth()->toDateString();
        $end = $request->query('end') ?? now()->endOfMonth()->toDateString();

        $cacheKey = "jadwal_summary:{$start}:{$end}";

        $result = Cache::remember($cacheKey, 60, function () use ($start, $end) {
            $jadwals = Jadwal::with(['dokter.poli'])
                ->whereBetween('tanggal', [$start, $end])
                ->get()
                ->groupBy('tanggal');

            // precompute reservation counts for the date range
            $resMap = [];
                $resRows = Reservasi::whereDate('tanggal_reservasi', '>=', $start)
                    ->whereDate('tanggal_reservasi', '<=', $end)
                ->whereIn('status', ['PENDING', 'VERIFIED'])
                ->get();

            foreach ($resRows as $res) {
                $key = "{$res->tanggal_reservasi}|{$res->dokter_id}|{$res->cara_bayar}";
                if (!isset($resMap[$key])) $resMap[$key] = 0;
                $resMap[$key]++;
            }

            $out = [];

            foreach ($jadwals as $date => $group) {
                // determine available unique dokter ids for date (only count if any quota remains)
                $availableDokterIds = [];

                foreach ($group->pluck('dokter')->unique('id') as $dokter) {
                    if (!$dokter) continue;
                    $dokterId = $dokter->id;
                    $kuotaUmum = (int) ($dokter->kuota_umum ?? 0);
                    $kuotaBpjs = (int) ($dokter->kuota_bpjs ?? 0);

                    $reservedUmum = $resMap["{$date}|{$dokterId}|UMUM"] ?? 0;
                    $reservedBpjs = $resMap["{$date}|{$dokterId}|BPJS"] ?? 0;

                    if ($kuotaUmum > $reservedUmum || $kuotaBpjs > $reservedBpjs) {
                        $availableDokterIds[] = $dokterId;
                    }
                }

                // group by poli but only counting available doctors
                $polis = [];
                $byPoli = $group->groupBy(function ($j) {
                    return $j->dokter->poli->id ?? 0;
                });

                foreach ($byPoli as $poliId => $rows) {
                    if (!$poliId) continue;
                    $poliName = $rows->first()->dokter->poli->nama_poli ?? 'Lainnya';
                    $dokterIdsInGroup = $rows->pluck('dokter_id')->unique()->values()->all();

                    $availableCount = 0;
                    foreach ($dokterIdsInGroup as $did) {
                        if (in_array($did, $availableDokterIds)) $availableCount++;
                    }

                    if ($availableCount > 0) {
                        $polis[] = [
                            'id' => $poliId,
                            'name' => $poliName,
                            'doctor_count' => $availableCount,
                        ];
                    }
                }

                $out[$date] = [
                    'total_doctors' => count(array_unique($availableDokterIds)),
                    'total_polis' => count($polis),
                    'polis' => array_values($polis),
                ];
            }

            return $out;
        });

        return response()->json($result);
    }

    /**
     * Return list of doctors and their times for a specific date + poli
     */
    public function detailsByDatePoli($date, $poliId)
    {
        $key = "jadwal_details:{$date}:{$poliId}";
        $data = Cache::remember($key, 60, function () use ($date, $poliId) {
            $rows = Jadwal::with('dokter')
                ->where('tanggal', $date)
                ->whereHas('dokter', function ($q) use ($poliId) {
                    $q->where('poli_id', $poliId);
                })->get();

            return $rows->map(function ($r) use ($date) {
                $dokter = $r->dokter;
                $kuotaUmum = (int) ($dokter->kuota_umum ?? 0);
                $kuotaBpjs = (int) ($dokter->kuota_bpjs ?? 0);

                // Count only VERIFIED reservations so quota decreases on confirmation
                $reservedUmum = Reservasi::where('dokter_id', $dokter->id)
                        ->whereDate('tanggal_reservasi', $date)
                    ->where('cara_bayar', 'UMUM')
                    ->whereIn('status', ['PENDING', 'VERIFIED'])
                    ->count();

                $reservedBpjs = Reservasi::where('dokter_id', $dokter->id)
                        ->whereDate('tanggal_reservasi', $date)
                    ->where('cara_bayar', 'BPJS')
                    ->whereIn('status', ['PENDING', 'VERIFIED'])
                    ->count();

                // if both quotas exhausted, don't include this doctor
                if ($kuotaUmum <= $reservedUmum && $kuotaBpjs <= $reservedBpjs) {
                    return null;
                }

                return [
                    'dokter_id' => $r->dokter_id,
                    'nama' => $dokter->nama ?? '',
                    'jam_mulai' => \Carbon\Carbon::parse($r->jam_mulai)->format('H:i'),
                    'jam_selesai' => \Carbon\Carbon::parse($r->jam_selesai)->format('H:i'),
                    'kuota_umum' => $kuotaUmum,
                    'kuota_bpjs' => $kuotaBpjs,
                    'reserved_umum' => $reservedUmum,
                    'reserved_bpjs' => $reservedBpjs,
                ];
            })->filter(function ($x) {
                return $x !== null;
            })->values();
        });

        return response()->json($data);
    }

    /**
     * Return list of poliklinik (clinics) that have at least one jadwal on the given date.
     */
    public function polisByDate($date)
    {
        $date = $date;

        // build reservation map for this date
        $resMap = [];
            $resRows = Reservasi::whereDate('tanggal_reservasi', $date)
            ->whereIn('status', ['PENDING', 'VERIFIED'])
            ->get();

        foreach ($resRows as $res) {
            $key = "{$res->tanggal_reservasi}|{$res->dokter_id}|{$res->cara_bayar}";
            if (!isset($resMap[$key])) $resMap[$key] = 0;
            $resMap[$key]++;
        }

        $klinikList = \App\Models\Klinik::with(['dokter.jadwals' => function($q) use ($date) {
            $q->where('tanggal', $date);
        }, 'dokter'])
        ->whereHas('dokter.jadwals', function ($q) use ($date) {
            $q->where('tanggal', $date);
        })->orderBy('nama_poli')->get();

        $polis = $klinikList->filter(function($klinik) use ($date, $resMap) {
            foreach ($klinik->dokter as $dok) {
                // ensure dokter has jadwal for the date
                $hasJadwal = $dok->jadwals->where('tanggal', $date)->count() > 0;
                if (!$hasJadwal) continue;

                $kuotaUmum = (int) ($dok->kuota_umum ?? 0);
                $kuotaBpjs = (int) ($dok->kuota_bpjs ?? 0);

                $reservedUmum = $resMap["{$date}|{$dok->id}|UMUM"] ?? 0;
                $reservedBpjs = $resMap["{$date}|{$dok->id}|BPJS"] ?? 0;

                if ($kuotaUmum > $reservedUmum || $kuotaBpjs > $reservedBpjs) {
                    return true;
                }
            }
            return false;
        })->map(function($k) {
            return ['id' => $k->id, 'nama_poli' => $k->nama_poli];
        })->values();

        return response()->json($polis);
    }
}
