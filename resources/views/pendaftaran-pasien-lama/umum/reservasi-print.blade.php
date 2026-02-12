@php
    date_default_timezone_set('Asia/Jakarta');

    use Carbon\Carbon;
    use App\Helpers\TimePeriod;

    // Default nilai aman
    $shift = '-';
    $jamMulai = null;
    $jamSelesai = null;

    if ($reservasi->jadwal) {
        $jamMulai   = Carbon::parse($reservasi->jadwal->jam_mulai);
        $jamSelesai = Carbon::parse($reservasi->jadwal->jam_selesai);
    } else {
        // Jika relasi jadwal kosong, coba cari jadwal berdasarkan poli/dokter/tanggal (fallback)
        try {
            $jadwalFallback = null;
            if (!empty($reservasi->jadwal_id)) {
                $jadwalFallback = \App\Models\Jadwal::find($reservasi->jadwal_id);
            }
            if (!$jadwalFallback && !empty($reservasi->dokter_id)) {
                $jadwalFallback = \App\Models\Jadwal::where('dokter_id', $reservasi->dokter_id)
                    ->whereDate('tanggal', $reservasi->tanggal_reservasi)
                    ->orderBy('jam_mulai')
                    ->first();
            }
            if (!$jadwalFallback) {
                $jadwalFallback = \App\Models\Jadwal::where('poli_id', $reservasi->poli_id)
                    ->whereDate('tanggal', $reservasi->tanggal_reservasi)
                    ->orderBy('jam_mulai')
                    ->first();
            }

            if ($jadwalFallback) {
                if (!empty($jadwalFallback->jam_mulai)) {
                    $jamMulai = Carbon::parse($jadwalFallback->jam_mulai);
                }
                if (!empty($jadwalFallback->jam_selesai)) {
                    $jamSelesai = Carbon::parse($jadwalFallback->jam_selesai);
                }
            }
        } catch (\Throwable $e) {
            // ignore lookup errors
        }
    }

    // Tentukan shift jika jamMulai tersedia (gunakan TimePeriod untuk akurasi menit)
    if ($jamMulai) {
        try {
            $period = TimePeriod::current($jamMulai);
            switch ($period) {
                case TimePeriod::PERIOD_PAGI:
                    $shift = 'Pagi';
                    break;
                case TimePeriod::PERIOD_SIANG:
                    $shift = 'Siang';
                    break;
                case TimePeriod::PERIOD_SORE:
                    $shift = 'Sore';
                    break;
                default:
                    $shift = 'Malam';
                    break;
            }
        } catch (\Throwable $e) {
            // fallback ke metode jam saja jika TimePeriod gagal
            $hour = $jamMulai->hour;
            if ($hour >= 5 && $hour < 12) {
                $shift = 'Pagi';
            } elseif ($hour >= 12 && $hour < 16) {
                $shift = 'Siang';
            } elseif ($hour >= 16 && $hour < 19) {
                $shift = 'Sore';
            } else {
                $shift = 'Malam';
            }
        }
    }
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Reservasi RSUD Bandung Kiwari</title>
    <style>
        body { font-family: 'Arial', sans-serif; margin: 0; padding: 40px; background: #fff; }
        .page { max-width: 900px; margin: 0 auto; }
        .print-box {
            border: 2px solid #1b7f82;
            padding: 20px 30px;
            background: #fff;
        }
        .title { text-align: center; }
        .title h2 { margin: 6px 0; font-size: 18px; color: #d32f2f; font-weight: 800; }
        .title p { margin: 0; font-size: 12px; color: #000; }

        .table-like { width: 100%; margin-top: 14px; }
        .table-like .row { display: flex; padding: 6px 0; border-bottom: 1px solid #eee; align-items: center; }
        .table-like .label { width: 160px; color: #333; font-size: 12px; }
        .table-like .sep { width: 8px; color: #333; }
        .table-like .value { flex: 1; color: #333; font-size: 12px; }

        .kode-box { display:inline-block; background:#e6f2ff; border:1px solid #cfe8ff; padding:4px 10px; font-weight:700; color:#0b5394; border-radius:3px; }
        .kode-label { font-size:11px; color:#2b2b2b; }

        .qr-wrap { text-align: center; margin-top: 18px; }
        .qr-box { display:inline-block; position:relative; background:#fff; padding:10px; border-radius:4px; }

        .small-note { font-size:11px; color:#666; margin-top:8px; }
        .footer-note { font-size:10px; color:#999; margin-top:18px; text-align:center; }

        .btn { display:inline-block; margin:0 6px; padding:8px 14px; font-weight:700; border-radius:4px; color:#fff; cursor:pointer; border:none; }
        .btn-print { background:#28a745; }
        .btn-pdf { background:#007bff; }

        @media print {
            body { padding:0 }
            .page { margin:0 }
        }
    </style>
</head>
<body>
<div class="page">
    <div class="print-box">
        <div class="title">
            <h2>PENDAFTARAN ONLINE RSUD BANDUNG KIWARI</h2>
            <p style="font-weight:700; color:#d32f2f;">TIDAK DIPUNGUT BIAYA APAPUN</p>
        </div>

        <div style="margin-top:12px; display:flex; justify-content:space-between;">
            <div>
                <div style="margin-bottom:8px;">
                    <span class="kode-label">Kode Reservasi :</span>
                    <span class="kode-box">{{ $reservasi->kode_booking }}</span>
                </div>

                <div class="table-like">
                    <div class="row">
                        <div class="label">Nama</div>
                        <div class="sep">:</div>
                        <div class="value">{{ optional($reservasi->pasien)->nama_lengkap ?? '-' }}</div>
                    </div>

                      <div class="row">
                        <div class="label">Nomor Rekam Medis</div>
                        <div class="sep">:</div>
                        <div class="value">
                            {{ optional($reservasi->pasien)->no_rm ?? optional($reservasi->pasien)->nomor_rm ?? optional($reservasi->pasien)->nomor_rekam ?? optional($reservasi->pasien)->no_rekam_medis ?? '-' }}
                        </div>
                    </div>

                    <div class="row">
                        <div class="label">Poliklinik Tujuan</div>
                        <div class="sep">:</div>
                        <div class="value">{{ optional($reservasi->poli)->nama_poli ?? '-' }}</div>
                    </div>

                    <div class="row">
                        <div class="label">Dokter</div>
                        <div class="sep">:</div>
                        <div class="value">{{ optional($reservasi->dokter)->nama ?? '-' }}</div>
                    </div>

                    <div class="row">
                        <div class="label">Tanggal Reservasi</div>
                        <div class="sep">:</div>
                        <div class="value">
                            {{ \Carbon\Carbon::parse($reservasi->tanggal_reservasi)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                        </div>
                    </div>

                    <!-- INI SUDAH DINAMIS -->
                    <div class="row">
                        <div class="label">Pelayanan Poliklinik @if($shift !== '-') {{ $shift }} @endif</div>
                        <div class="sep">:</div>
                        <div class="value">
                            @php
                                $jadwal = $reservasi->jadwal ?? null;
                                $jm = null; $js = null;
                                if ($jadwal) {
                                    $rawMulai = trim($jadwal->jam_mulai ?? '');
                                    $rawSelesai = trim($jadwal->jam_selesai ?? '');

                                    // If jam_mulai stores a range like "08:00 - 15:00", split it
                                    if (!empty($rawMulai) && strpos($rawMulai, '-') !== false && empty($rawSelesai)) {
                                        $parts = array_map('trim', explode('-', $rawMulai, 2));
                                        $rawMulai = $parts[0] ?? $rawMulai;
                                        if (isset($parts[1])) $rawSelesai = $parts[1];
                                    }

                                    try { $jm = !empty($rawMulai) ? Carbon::parse($rawMulai) : null; } catch (\Exception $e) { $jm = null; }
                                    try { $js = !empty($rawSelesai) ? Carbon::parse($rawSelesai) : null; } catch (\Exception $e) { $js = null; }
                                }
                            @endphp

                            @if($jadwal && ($jm || $js))
                                @if($jm && $js)
                                    dimulai jam {{ $jm->format('H:i:s') }} - {{ $js->format('H:i:s') }} WIB
                                @elseif($jm)
                                    dimulai jam {{ $jm->format('H:i:s') }} WIB
                                @else
                                    Jadwal tidak lengkap
                                @endif
                            @elseif($jamMulai && $jamSelesai)
                                dimulai jam {{ $jamMulai->format('H:i:s') }} - {{ $jamSelesai->format('H:i:s') }} WIB
                            @elseif(!empty($reservasi->waktu_label) || !empty($reservasi->estimasi_waktu))
                                {{-- show label and estimation if available --}}
                                @if(!empty($reservasi->waktu_label))
                                    Shift: {{ $reservasi->waktu_label }}
                                @endif
                                @if(!empty($reservasi->estimasi_waktu))
                                    <br />Perkiraan dilayani sekitar jam {{ $reservasi->estimasi_waktu }}@if(!empty($reservasi->estimasi_pelayanan)) &nbsp;(± {{ $reservasi->estimasi_pelayanan }} menit)@endif
                                @endif
                            @else
                                Jadwal belum ditentukan
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="label">Estimasi Dilayani</div>
                        <div class="sep">:</div>
                        <div class="value">
                            @if(!empty($reservasi->estimasi_waktu))
                                Perkiraan dilayani sekitar jam {{ $reservasi->estimasi_waktu }}@if(!empty($reservasi->estimasi_pelayanan)) &nbsp;(± {{ $reservasi->estimasi_pelayanan }} menit)@endif
                            @elseif(!empty($reservasi->estimasi_pelayanan))
                                {{ $reservasi->estimasi_pelayanan }} menit
                            @elseif($jamMulai)
                                Jam {{ $jamMulai->format('H:i') }}
                            @else
                                -
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="label">Cara Pembayaran</div>
                        <div class="sep">:</div>
                        <div class="value">{{ $reservasi->cara_pembayaran ?? $reservasi->cara_bayar ?? optional($reservasi->pasien)->cara_bayar ?? 'UMUM' }}</div>
                    </div>

                    <div class="row">
                        <div class="label">Antrian Poliklinik</div>
                        <div class="sep">:</div>
                        <div class="value">{{ $reservasi->nomor_antrian ?? $reservasi->no_antrian ?? $reservasi->antrian ?? '-' }}</div>
                    </div>
                </div>
            </div>

            <div style="width:260px; text-align:center;">
                <div class="qr-wrap">
                    <div class="qr-box" id="qrcode"></div>
                </div>
                <div class="small-note">{{ $reservasi->kode_booking }}</div>
            </div>
        </div>

        <div class="small-note" style="margin-top:14px;">
            Pasien diharapkan datang minimal 30 menit sebelum pelayanan poliklinik dimulai.
        </div>

        <div class="footer-note">
            Dokumen ini dikeluarkan oleh Sistem Pendaftaran Online RSUD BANDUNG KIWARI,
            tanggal cetak: {{ now()->format('Y-m-d H:i:s') }}
        </div>
    </div>
</div>

</div>

    <div style="text-align:center; margin-top:18px;">
        <button class="btn btn-print" onclick="window.print()">Cetak Bukti Pendaftaran</button>
    </div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    new QRCode(document.getElementById("qrcode"), {
        text: "{{ $reservasi->kode_booking }}",
        width: 180,
        height: 180
    });
</script>
</body>
</html>
