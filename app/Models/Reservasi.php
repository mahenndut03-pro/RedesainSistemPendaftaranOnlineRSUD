<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Klinik;
use App\Models\Jadwal;
use Carbon\Carbon;
use App\Helpers\TimePeriod;

class Reservasi extends Model
{
    protected $table = 'reservations';

    protected $fillable = [
        'patient_id',
        'cara_bayar',
        'poli_id',
        'dokter_id',
        'jadwal_id',
        'tanggal_reservasi',
        'no_bpjs',
        'no_rujukan',
        'tanggal_rujukan',
        'kode_booking',
        'status',
        'cancellation_reason',
        'nomor_antrian',
        'waktu',
        'estimasi_pelayanan',
        'alasan_kontrol',
    ];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'patient_id');
    }

    public function poli()
    {
        return $this->belongsTo(Klinik::class, 'poli_id');
    }

    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'dokter_id');
    }
    
    public function jadwal()
    {
    return $this->belongsTo(Jadwal::class, 'jadwal_id');
    }

    public function getEstimasiPelayananAttribute($value)
    {
        if (!empty($value)) return (int)$value;

        $estimasiMenit = $this->poli->estimasi_menit ?? 10;

        if (!$this->nomor_antrian) return null;

        $num = 1;
        if (preg_match('/-(\d+)$/', $this->nomor_antrian, $m)) {
            $num = intval($m[1]);
        }

        // Service estimation: first queue (num=1) is jadwal start + one slot (e.g. 08:00 -> 08:10)
        return ($num) * (int)$estimasiMenit;
    }

    public function getWaktuAttribute($value)
    {
        if (!empty($value)) return $value;

        // Try to find a matching jadwal for this reservation
        $jadwal = null;
        if (!empty($this->jadwal_id)) {
            $jadwal = Jadwal::find($this->jadwal_id);
        }

        if (!$jadwal && !empty($this->dokter_id)) {
            $jadwal = Jadwal::where('dokter_id', $this->dokter_id)
                ->whereDate('tanggal', $this->tanggal_reservasi)
                ->first();
        }

        if (!$jadwal) {
            $jadwal = Jadwal::where('poli_id', $this->poli_id)
                ->whereDate('tanggal', $this->tanggal_reservasi)
                ->orderBy('jam_mulai')
                ->first();
        }

        if (!$jadwal || empty($jadwal->jam_mulai)) {
            return null;
        }

        $estimasiMenit = $this->poli->estimasi_menit ?? 10;

        $num = 1;
        if ($this->nomor_antrian && preg_match('/-(\d+)$/', $this->nomor_antrian, $m)) {
            $num = intval($m[1]);
        }

        // First queue occurs after one slot from jam_mulai (e.g. 08:00 -> 08:10)
        $minutesToAdd = ($num) * (int)$estimasiMenit;

        try {
            $dt = Carbon::parse($this->tanggal_reservasi . ' ' . $jadwal->jam_mulai)->addMinutes($minutesToAdd);
            return $dt->format('H:i');
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Human-readable waktu label: Pagi / Siang / Sore / Malam
     */
    public function getWaktuLabelAttribute()
    {
        // Prefer the computed estimasi_waktu (HH:MM) so the label matches the displayed estimated time
        $time = null;
        if (!empty($this->estimasi_waktu)) {
            $time = $this->estimasi_waktu;
        }

        // Next prefer explicit waktu stored on reservation
        if (!$time && !empty($this->waktu)) {
            $time = $this->waktu;
        }

        // Finally fall back to jadwal's jam_mulai
        if (!$time && !empty($this->jadwal) && !empty($this->jadwal->jam_mulai)) {
            $time = $this->jadwal->jam_mulai;
        }

        if (!$time) return null;

        try {
            $period = TimePeriod::current($time);
        } catch (\Exception $e) {
            return null;
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

    /**
     * Estimated served time in H:i format, computed from jadwal.jam_mulai + (position-1)*estimasi_menit
     */
    public function getEstimasiWaktuAttribute()
    {
        // find jadwal similar to getWaktuAttribute
        $jadwal = null;
        if (!empty($this->jadwal_id)) {
            $jadwal = Jadwal::find($this->jadwal_id);
        }

        if (!$jadwal && !empty($this->dokter_id)) {
            $jadwal = Jadwal::where('dokter_id', $this->dokter_id)
                ->whereDate('tanggal', $this->tanggal_reservasi)
                ->first();
        }

        if (!$jadwal) {
            $jadwal = Jadwal::where('poli_id', $this->poli_id)
                ->whereDate('tanggal', $this->tanggal_reservasi)
                ->orderBy('jam_mulai')
                ->first();
        }

        if (!$jadwal || empty($jadwal->jam_mulai)) return null;

        $estimasiMenit = $this->poli->estimasi_menit ?? 10;
        $num = 1;
        if ($this->nomor_antrian && preg_match('/-(\d+)$/', $this->nomor_antrian, $m)) {
            $num = intval($m[1]);
        }

        // First queue occurs after one slot from jam_mulai (e.g. 08:00 -> 08:10)
        $minutesToAdd = ($num) * (int)$estimasiMenit;

        try {
            $dt = Carbon::parse($this->tanggal_reservasi . ' ' . $jadwal->jam_mulai)->addMinutes($minutesToAdd);
            return $dt->format('H:i');
        } catch (\Exception $e) {
            return null;
        }
    }


    /**
     * Generate the next `nomor_antrian` for a given poli and date.
     * This method locks the poli row to avoid race conditions and
     * counts existing reservations that already have a `nomor_antrian`.
     *
     * @param int $poliId
     * @param string $tanggalReservasi (Y-m-d)
     * @return string next nomor_antrian (e.g. "A-01")
     */
    public static function generateNextNomor($poliId, $tanggalReservasi)
{
    return DB::transaction(function () use ($poliId, $tanggalReservasi) {

        // Lock poli
        $poli = Klinik::where('id', $poliId)->lockForUpdate()->firstOrFail();

        // Prefix UTAMA: kode_poli
        $prefix = strtoupper($poli->kode_poli);

        // Ambil nomor antrian TERAKHIR hari itu
        $last = self::where('poli_id', $poliId)
            ->whereDate('tanggal_reservasi', $tanggalReservasi)
            ->whereNotNull('nomor_antrian')
            ->orderByDesc('id')
            ->lockForUpdate()
            ->first();

        $next = 1;

        if ($last && preg_match('/-(\d+)$/', $last->nomor_antrian, $m)) {
            $next = intval($m[1]) + 1;
        }

        return $prefix . '-' . str_pad($next, 2, '0', STR_PAD_LEFT);
    });
}

    /**
     * Generate the next `nomor_antrian` for BPJS reservations for a given poli and date.
     * This method locks the poli row to avoid race conditions and
     * counts existing VERIFIED BPJS reservations that already have a `nomor_antrian`.
     * Ordered by updated_at (time of verification).
     *
     * @param int $poliId
     * @param string $tanggalReservasi (Y-m-d)
     * @return string next nomor_antrian (e.g. "A-01")
     */
    public static function generateNextNomorBpjs($poliId, $tanggalReservasi)
    {
        return DB::transaction(function () use ($poliId, $tanggalReservasi) {

            // Lock poli
            $poli = Klinik::where('id', $poliId)->lockForUpdate()->firstOrFail();

            // Prefix UTAMA: kode_poli
            $prefix = strtoupper($poli->kode_poli);

            // Ambil nomor antrian TERAKHIR hari itu untuk BPJS VERIFIED
            $last = self::where('poli_id', $poliId)
                ->whereDate('tanggal_reservasi', $tanggalReservasi)
                ->where('cara_bayar', 'BPJS')
                ->where('status', 'VERIFIED')
                ->whereNotNull('nomor_antrian')
                ->orderBy('updated_at', 'desc')
                ->lockForUpdate()
                ->first();

            $next = 1;

            if ($last && preg_match('/-(\d+)$/', $last->nomor_antrian, $m)) {
                $next = intval($m[1]) + 1;
            }

            return $prefix . '-' . str_pad($next, 2, '0', STR_PAD_LEFT);
        });
    }
}


