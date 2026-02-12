<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservasi {{ $reservasi->kode_booking ?? $reservasi->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #222; }
        .header { text-align: center; margin-bottom: 20px; }
        .box { border: 1px solid #ddd; padding: 12px; border-radius: 6px; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 6px 4px; vertical-align: top; }
        .label { font-weight: bold; width: 200px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Surat Bukti Reservasi</h2>
        <div>Kode Booking: <strong>{{ $reservasi->kode_booking }}</strong></div>
    </div>

    <div class="box">
        <table>
            <tr>
                <td class="label">Nama Pasien</td>
                <td>: {{ $reservasi->pasien->nama_lengkap ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">No. KTP</td>
                <td>: {{ $reservasi->pasien->no_ktp ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Telepon</td>
                <td>: {{ $reservasi->pasien->telepon ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Poliklinik</td>
                <td>: {{ $reservasi->poli->nama_poli ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Dokter</td>
                <td>: {{ $reservasi->dokter->nama ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Reservasi</td>
                <td>: {{ \Carbon\Carbon::parse($reservasi->tanggal_reservasi)->format('d-m-Y') }}</td>
            </tr>
            <tr>
                <td class="label">Cara Bayar</td>
                <td>: {{ $reservasi->cara_bayar }}</td>
            </tr>
            <tr>
                <td class="label">Status</td>
                <td>: {{ $reservasi->status }}</td>
            </tr>
        </table>
    </div>

    <p style="margin-top:20px; font-size:12px; color:#666">Silakan bawa bukti ini saat daftar ulang.</p>
</body>
</html>
