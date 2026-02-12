@extends('admin.layouts.app')

@section('title', 'Tolak Reservasi')

@section('content')
<div class="max-w-3xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Tolak Reservasi - {{ $reservasi->kode_booking ?? $reservasi->id }}</h1>

    <div class="bg-white rounded shadow p-4">
        <p class="mb-4">Pasien: <strong>{{ $reservasi->pasien->nama_lengkap ?? '-' }}</strong></p>
        <form method="POST" action="{{ route('admin.reservasi.reject', $reservasi->id) }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Alasan Penolakan <span class="text-red-600">*</span></label>
                <textarea name="alasan_batal" rows="5" class="w-full border rounded p-2" required placeholder="Tuliskan alasan penolakan...">{{ old('alasan_batal') }}</textarea>
                @error('alasan_batal')
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="flex gap-2 justify-end">
                <a href="{{ route('admin.reservasi.index') }}" class="px-4 py-2 bg-gray-200 rounded">Batal</a>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded">Kirim & Tolak</button>
            </div>
        </form>
    </div>
</div>
@endsection
