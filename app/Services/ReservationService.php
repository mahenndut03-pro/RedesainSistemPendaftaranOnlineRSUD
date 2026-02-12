<?php

namespace App\Services;

use App\Models\Reservasi;
use App\Models\Jadwal;
use App\Models\Klinik;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReservationService
{
    /**
     * Create a reservation using canonical columns. Throws on duplicate.
     *
     * @param array $data
     * @return Reservasi
     * @throws \RuntimeException|\InvalidArgumentException
     */
    public function create(array $data)
    {
        $patientId = $data['patient_id'] ?? null;
        if (!$patientId) {
            throw new \InvalidArgumentException('patient_id required');
        }

        $date = $data['tanggal_reservasi'] ?? $data['tanggal'] ?? null;
        try {
            $date = Carbon::parse($date)->toDateString();
        } catch (\Exception $e) {
            // leave original value if parsing fails
        }
        $poliId = $data['poli_id'] ?? $data['poli'] ?? null;
        $dokterId = $data['dokter_id'] ?? null;

        if (!$date || !$poliId) {
            throw new \InvalidArgumentException('tanggal_reservasi and poli_id are required');
        }

        $statusVariants = ['PENDING', 'VERIFIED', 'menunggu', 'terverifikasi'];

        return DB::transaction(function() use ($data, $patientId, $date, $poliId, $dokterId, $statusVariants) {
            // Find relevant jadwal (same logic as Reservasi model accessors)
            $jadwal = null;
            if (!empty($data['jadwal_id'])) {
                $jadwal = Jadwal::find($data['jadwal_id']);
            }

            if (!$jadwal && !empty($dokterId)) {
                $jadwal = Jadwal::where('dokter_id', $dokterId)
                    ->whereDate('tanggal', $date)
                    ->first();
            }

            if (!$jadwal) {
                $jadwal = Jadwal::where('poli_id', $poliId)
                    ->whereDate('tanggal', $date)
                    ->orderBy('jam_mulai')
                    ->first();
            }

            // Estimasi menit from poli
            $poli = Klinik::find($poliId);
            $estimasiMenit = $poli->estimasi_menit ?? 10;

            // Determine next nomor_antrian by locking poli and reading last nomor
            $poliLock = Klinik::where('id', $poliId)->lockForUpdate()->firstOrFail();

            $last = Reservasi::where('poli_id', $poliId)
                ->whereDate('tanggal_reservasi', $date)
                ->whereNotNull('nomor_antrian')
                ->orderByDesc('id')
                ->lockForUpdate()
                ->first();

            $nextNum = 1;
            if ($last && preg_match('/-(\d+)$/', $last->nomor_antrian, $m)) {
                $nextNum = intval($m[1]) + 1;
            }
            $prefix = strtoupper($poliLock->kode_poli);
            $nextNomor = $prefix . '-' . str_pad($nextNum, 2, '0', STR_PAD_LEFT);

            // Compute prospective start time for the new reservation
            $prospectiveStart = null;
            if (!empty($data['waktu'])) {
                try {
                    $prospectiveStart = Carbon::parse($date . ' ' . $data['waktu']);
                } catch (\Exception $e) {
                    $prospectiveStart = null;
                }
            } elseif ($jadwal && !empty($jadwal->jam_mulai)) {
                try {
                    $minutesToAdd = ($nextNum) * (int)$estimasiMenit;
                    $prospectiveStart = Carbon::parse($date . ' ' . $jadwal->jam_mulai)->addMinutes($minutesToAdd);
                } catch (\Exception $e) {
                    $prospectiveStart = null;
                }
            }

            // If we can compute a prospective start time, check conflicts for same patient on same date
            if ($prospectiveStart) {
                $existing = Reservasi::where('patient_id', $patientId)
                    ->whereIn('status', $statusVariants)
                    ->where(function($q) use ($date) {
                        $q->whereDate('tanggal_reservasi', $date);
                        if (Schema::hasColumn((new Reservasi)->getTable(), 'tanggal')) {
                            $q->orWhereDate('tanggal', $date);
                        }
                    })
                    ->where(function($q) use ($poliId) {
                        $q->where('poli_id', $poliId);
                        if (Schema::hasColumn((new Reservasi)->getTable(), 'poli')) {
                            $q->orWhere('poli', $poliId);
                        }
                    })
                    ->get();

                foreach ($existing as $ex) {
                    $exStart = null;

                    if (!empty($ex->waktu)) {
                        try {
                            $exStart = Carbon::parse($ex->tanggal_reservasi . ' ' . $ex->waktu);
                        } catch (\Exception $e) {
                            $exStart = null;
                        }
                    }

                    if (!$exStart) {
                        // try estimasi_waktu accessor
                        try {
                            if (!empty($ex->estimasi_waktu)) {
                                $exStart = Carbon::parse($ex->tanggal_reservasi . ' ' . $ex->estimasi_waktu);
                            } else {
                                // fallback: attempt to find jadwal for that reservation and compute
                                $j = null;
                                if (!empty($ex->jadwal_id)) {
                                    $j = Jadwal::find($ex->jadwal_id);
                                }
                                if (!$j && !empty($ex->dokter_id)) {
                                    $j = Jadwal::where('dokter_id', $ex->dokter_id)
                                        ->whereDate('tanggal', $ex->tanggal_reservasi)
                                        ->first();
                                }
                                if (!$j) {
                                    $j = Jadwal::where('poli_id', $ex->poli_id)
                                        ->whereDate('tanggal', $ex->tanggal_reservasi)
                                        ->orderBy('jam_mulai')
                                        ->first();
                                }
                                if ($j && !empty($j->jam_mulai) && preg_match('/-(\d+)$/', $ex->nomor_antrian, $m2)) {
                                    $numEx = intval($m2[1]);
                                    $minutesToAddEx = ($numEx) * (int)($ex->poli->estimasi_menit ?? $estimasiMenit);
                                    $exStart = Carbon::parse($ex->tanggal_reservasi . ' ' . $j->jam_mulai)->addMinutes($minutesToAddEx);
                                }
                            }
                        } catch (\Exception $e) {
                            $exStart = null;
                        }
                    }

                    if ($exStart) {
                        $diff = (int) $exStart->diffInMinutes($prospectiveStart);
                        if ($diff < (int)$estimasiMenit) {
                            Log::info('ReservationService: time conflict for patient', [
                                'patient_id' => $patientId,
                                'tanggal_reservasi' => $date,
                                'existing_start' => $exStart->format('Y-m-d H:i'),
                                'prospective_start' => $prospectiveStart->format('Y-m-d H:i'),
                            ]);
                            throw new \RuntimeException('duplicate_time');
                        }
                    }
                }
            }

            // No conflict detected; create reservation and set nomor_antrian we computed
            $kode = strtoupper('BK'.Str::random(6));

            $caraBayar = $data['cara_bayar'] ?? 'UMUM';
            $isBpjs = strtoupper($caraBayar) === 'BPJS';

            $reservasi = Reservasi::create([
                'patient_id' => $patientId,
                'cara_bayar' => $caraBayar,
                'poli_id' => $poliId,
                'dokter_id' => $dokterId,
                'tanggal_reservasi' => $date,
                'no_bpjs' => $data['no_bpjs'] ?? null,
                'no_rujukan' => $data['no_rujukan'] ?? null,
                'tanggal_rujukan' => $data['tanggal_rujukan'] ?? null,
                'waktu' => $data['waktu'] ?? null,
                'alasan_kontrol' => $data['alasan_kontrol'] ?? null,
                'status' => 'PENDING',
                'kode_booking' => $kode,
                'nomor_antrian' => $isBpjs ? null : $nextNomor,
            ]);

            Log::info('ReservationService: created reservation', ['id' => $reservasi->id, 'kode' => $kode, 'patient_id' => $patientId]);

            return $reservasi;
        });
    }

    /**
     * Check for duplicate reservation (used by callers)
     */
    public function hasDuplicate($patientId, $date, $poliId)
    {
        $statusVariants = ['PENDING', 'VERIFIED', 'menunggu', 'terverifikasi'];
        try {
            $date = Carbon::parse($date)->toDateString();
        } catch (\Exception $e) {
            // keep original
        }

        // If there's no jadwal for this poli on the requested date, don't treat it as a duplicate
        try {
            $dateCheck = Carbon::parse($date)->toDateString();
        } catch (\Exception $e) {
            $dateCheck = $date;
        }

        $jadwalExists = \App\Models\Jadwal::where('poli_id', $poliId)
            ->whereDate('tanggal', $dateCheck)
            ->exists();

        if (!$jadwalExists) {
            return false;
        }

        $query = Reservasi::where('patient_id', $patientId)
            ->whereIn('status', $statusVariants)
            ->where(function($q) use ($date) {
                $q->whereDate('tanggal_reservasi', $date);
                if (Schema::hasColumn((new Reservasi)->getTable(), 'tanggal')) {
                    $q->orWhereDate('tanggal', $date);
                }
            })
            ->where(function($q) use ($poliId) {
                $q->where('poli_id', $poliId);
                if (Schema::hasColumn((new Reservasi)->getTable(), 'poli')) {
                    $q->orWhere('poli', $poliId);
                }
            });

        return $query->exists();
    }
}
