@php
    date_default_timezone_set('Asia/Jakarta');
    use Carbon\Carbon;
    use App\Helpers\TimePeriod;

    // Greeting based on current time with minute accuracy
    try {
        $currentPeriod = TimePeriod::current(date('H:i'));
        switch ($currentPeriod) {
            case TimePeriod::PERIOD_PAGI:
                $salam = 'Selamat Pagi';
                break;
            case TimePeriod::PERIOD_SIANG:
                $salam = 'Selamat Siang';
                break;
            case TimePeriod::PERIOD_SORE:
                $salam = 'Selamat Sore';
                break;
            default:
                $salam = 'Selamat Malam';
                break;
        }
    } catch (\Throwable $e) {
        $jam = date('H');
        if ($jam >= 5 && $jam < 11) {
            $salam = 'Selamat Pagi';
        } elseif ($jam >= 11 && $jam < 15) {
            $salam = 'Selamat Siang';
        } elseif ($jam >= 15 && $jam < 18) {
            $salam = 'Selamat Sore';
        } else {
            $salam = 'Selamat Malam';
        }
    }

    $shift = '-';
    $jamMulai = null;
    $jamSelesai = null;
    if (!empty($reservasi->jadwal)) {
        $jamMulai = Carbon::parse($reservasi->jadwal->jam_mulai);
        $jamSelesai = Carbon::parse($reservasi->jadwal->jam_selesai);

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
    <title>Bukti Pendaftaran Online RSUD Kiwari</title>
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

        .salam {
            margin-top: 6px;
            font-size: 13px;
            font-weight: 700;
            color: #1b7f82;
        }

        .table-like { width: 100%; margin-top: 14px; border-collapse: collapse; }
        .table-like .row { display: flex; padding: 6px 0; border-bottom: 1px solid #eee; align-items: center; }
        .table-like .label { width: 160px; color: #333; font-size: 12px; }
        .table-like .sep { width: 8px; color: #333; }
        .table-like .value { flex: 1; color: #333; font-size: 12px; }

        .kode-box { display:inline-block; background:#e6f2ff; border:1px solid #cfe8ff; padding:4px 10px; font-weight:700; color:#0b5394; border-radius:3px; }
        .kode-label { font-size:11px; color:#2b2b2b; }

        .qr-wrap { text-align: center; margin-top: 18px; }

        .small-note { font-size:11px; color:#666; margin-top:8px; }
        .footer-note { font-size:10px; color:#999; margin-top:18px; text-align:center; }

        .no-print { margin-top:18px; text-align:center; }
        .btn { display:inline-block; margin:0 6px; padding:8px 14px; font-weight:700; border-radius:4px; color:#fff; cursor:pointer; border:none; }
        .btn-print { background:#28a745; }
        .btn-pdf { background:#007bff; }

        @media print {
            body { padding:0 }
            .no-print { display:none }
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
            <div class="salam">
                {{ $salam }}, {{ $reservasi->pasien->nama_lengkap }}
            </div>
        </div>

        <div style="margin-top:12px;">
            <div style="margin-bottom:8px;">
                <span class="kode-label">Kode Booking :</span>
                <span class="kode-box">{{ $reservasi->kode_booking }}</span>
            </div>

                <style>
                .content-row { display:flex; gap:24px; align-items:flex-start; }
                .content-row .left { flex:1; min-width:0; }
                .content-row .right { width:260px; flex:0 0 260px; text-align:center; }
                .qr-wrap { margin-top:0; }
                .qr-caption { margin-top:8px; font-weight:700; color:#333; }
                /* Keep columns side-by-side when printing; prevent right column from wrapping below */
                @media print {
                    body { padding:0 }
                    .page { margin:0 }
                    .no-print { display:none }
                    .content-row { display:flex; flex-wrap:nowrap; gap:12px; }
                    .content-row .left { min-width:0 }
                    .content-row .right { width:260px; flex:0 0 260px; text-align:center; margin-top:0 }
                    .content-row img { max-width:100%; height:auto }
                    /* slightly reduce QR for printing to help fit on portrait */
                    .content-row .right img { width:160px; height:160px }
                }
            </style>

            <div class="content-row">
                <div class="left">
                    <div class="table-like">
                <div class="row">
                    <div class="label">Nama</div>
                    <div class="sep">:</div>
                    <div class="value">{{ $reservasi->pasien->nama_lengkap }}</div>
                </div>
                <div class="row">
                    <div class="label">Alamat</div>
                    <div class="sep">:</div>
                    <div class="value">
                        @if(!empty($reservasi->pasien) && !empty($reservasi->pasien->alamat))
                            {{ $reservasi->pasien->alamat->alamat ?? '' }}
                            @if(!empty($reservasi->pasien->alamat->kelurahan_name)), {{ $reservasi->pasien->alamat->kelurahan_name }}@endif
                            @if(!empty($reservasi->pasien->alamat->kecamatan_name)), {{ $reservasi->pasien->alamat->kecamatan_name }}@endif
                            @if(!empty($reservasi->pasien->alamat->kabupaten_name)), {{ $reservasi->pasien->alamat->kabupaten_name }}@endif
                            @if(!empty($reservasi->pasien->alamat->provinsi_name)), {{ $reservasi->pasien->alamat->provinsi_name }}@endif
                        @else
                            -
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="label">Poliklinik Tujuan</div>
                    <div class="sep">:</div>
                    <div class="value">{{ $reservasi->poli->nama_poli }}</div>
                </div>
                <div class="row">
                    <div class="label">Dokter</div>
                    <div class="sep">:</div>
                    <div class="value">{{ $reservasi->dokter->nama }}</div>
                </div>
                <div class="row">
                    <div class="label">Tanggal Reservasi</div>
                    <div class="sep">:</div>
                    <div class="value">
                        {{ \Carbon\Carbon::parse($reservasi->tanggal_reservasi)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                    </div>
                </div>
                <div class="row">
                    <div class="label">Jam Pelayanan @if($shift !== '-') ({{ $shift }}) @endif</div>
                    <div class="sep">:</div>
                    <div class="value">
                        @if($jamMulai && $jamSelesai)
                            {{ $jamMulai->format('H:i') }} - {{ $jamSelesai->format('H:i') }} WIB
                        @elseif(!empty($reservasi->estimasi_waktu))
                            Perkiraan dilayani sekitar {{ $reservasi->estimasi_waktu }}@if(!empty($reservasi->estimasi_pelayanan)) &nbsp;(± {{ $reservasi->estimasi_pelayanan }} menit)@endif
                        @elseif(!empty($reservasi->waktu_label))
                            Shift: {{ $reservasi->waktu_label }}
                        @else
                            Jadwal belum ditentukan
                        @endif
                    </div>
                </div>
                <!-- Pelayanan Poliklinik Pagi removed to avoid duplication; keep Jam Pelayanan row above -->
                    </div>
                </div>

                <div class="right">
                    <div class="qr-wrap">
                        @if(!empty($qr))
                            <img src="data:image/png;base64,{{ $qr }}" alt="QR Kode" style="width:200px;height:200px;" />
                        @elseif(!empty($reservasi->kode_booking))
                            <div id="qrcode" style="display:inline-block;"></div>
                        @endif
                    </div>
                    <div class="qr-caption">{{ $reservasi->kode_booking }}</div>
                </div>
            </div>

            <div class="small-note" style="margin-top:14px;">
                Pasien diharapkan datang minimal 30 menit sebelum pelayanan poliklinik dimulai.
            </div>

            <div class="footer-note">
                Dicetak pada {{ now()->format('d-m-Y H:i:s') }} WIB
            </div>
        </div>
    </div>

    <div class="no-print">
        <button class="btn btn-print" onclick="window.print()">Cetak Bukti Pendaftaran</button>
    </div>
    
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    // Render client-side QR only when the placeholder exists (fallback when server-side $qr is empty)
    (function(){
        var el = document.getElementById("qrcode");
        if (el) {
            new QRCode(el, {
                text: "{{ $reservasi->kode_booking }}",
                width: 130,
                height: 130
            });
        }
    })();
</script>
</body>
</html>
