@extends('layouts.app')

@section('content')

<div class="py-8">
    <div class="max-w-4xl mx-auto px-4">
        <div class="bg-white rounded-xl shadow p-6 text-center relative">
            <a href="#" onclick="window.history.back(); return false;"
               class="absolute right-6 top-6 text-white px-4 py-2 rounded-md font-semibold bg-[#2c3e8f] hover:bg-[#233170] dark:bg-teal-600 dark:hover:bg-teal-700 transition shadow-sm">
                KEMBALI
            </a>
            <h2 class="text-lg font-bold mb-4">Halaman Verifikasi Dihapus</h2>
            <p class="mb-4">Halaman verifikasi telah dipindahkan ke Riwayat. Anda akan diarahkan ke halaman Riwayat pendaftaran.</p>
            <a href="{{ route('pendaftaran-pasien-lama.history') }}" class="px-4 py-2 bg-blue-600 text-white rounded">Ke Riwayat</a>
        </div>
    </div>
</div>

<script>
    setTimeout(function(){
        window.location.href = "{{ route('pendaftaran-pasien-lama.history') }}";
    }, 1200);
</script>

@endsection
