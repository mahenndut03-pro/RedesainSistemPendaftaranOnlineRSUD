@extends('admin.layouts.app')

@section('title', 'Verifikasi Reservasi')

@section('content')
<div class="max-w-3xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Verifikasi Reservasi - {{ $reservasi->kode_booking ?? $reservasi->id }}</h1>

    <div class="bg-white rounded shadow p-4">
        <p class="mb-2">Pasien: <strong>{{ $reservasi->pasien->nama_lengkap ?? '-' }}</strong></p>
        <p class="mb-4">Poli: <strong>{{ $reservasi->poli->nama_poli ?? '-' }}</strong> — Dokter: <strong>{{ $reservasi->dokter->nama ?? '-' }}</strong></p>
        
        @if(strtoupper($reservasi->cara_bayar) === 'BPJS')
            <p class="mb-2">Cara Bayar: <strong>{{ $reservasi->cara_bayar }}</strong></p>
            <p class="mb-2">No. BPJS: <strong>{{ $reservasi->no_bpjs ?? '-' }}</strong></p>
            <p class="mb-2">No. Rujukan: <strong>{{ $reservasi->no_rujukan ?? '-' }}</strong></p>
            <p class="mb-4">Tanggal Rujukan: <strong>{{ $reservasi->tanggal_rujukan ? \Carbon\Carbon::parse($reservasi->tanggal_rujukan)->format('d/m/Y') : '-' }}</strong></p>
        @endif
        
        <form method="POST" action="{{ route('admin.reservasi.verify', $reservasi->id) }}">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Alasan Verifikasi <span class="text-red-600">*</span></label>
                <textarea name="alasan_kontrol" rows="5" class="w-full border rounded p-2" required placeholder="Tuliskan catatan / alasan verifikasi di sini...">{{ old('alasan_kontrol', $reservasi->alasan_kontrol ?? '') }}</textarea>
                @error('alasan_kontrol')
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="flex gap-2 justify-end">
                <a href="{{ route('admin.reservasi.index') }}" class="px-4 py-2 bg-gray-200 rounded">Batal</a>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Kirim & Verifikasi</button>
            </div>
        </form>
    </div>
</div>
@endsection
