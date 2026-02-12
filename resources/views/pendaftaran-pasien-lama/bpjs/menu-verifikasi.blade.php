@extends('layouts.app')

@section('content')
<div class="py-8 transition-colors duration-300">
  <div class="max-w-4xl mx-auto px-4">
    <div class="bg-white dark:bg-[#1e2839] rounded-xl border border-gray-200 dark:border-gray-700 shadow-md p-6 md:p-8 transition-colors duration-300 mt-4 relative">

      <a href="#" onclick="window.history.back(); return false;"
         class="absolute right-6 top-6 text-white px-4 py-2 rounded-md font-semibold bg-[#2c3e8f] hover:bg-[#233170] dark:bg-teal-600 dark:hover:bg-teal-700 transition shadow-sm">
        KEMBALI
      </a>

        <div class="text-center mb-3">
            <h2 class="text-lg md:text-xl font-bold text-[#2c3e8f] dark:text-white">Reservasi Online Selesai</h2>
            <p class="text-gray-600 dark:text-gray-300">Silakan cek Riwayat pendaftaran untuk melihat hasil verifikasi. Anda akan diarahkan ke Riwayat.</p>
        </div>

        <div class="mt-4 text-center">
            <a href="{{ route('pendaftaran-pasien-lama.history') }}" class="px-4 py-2 bg-blue-600 text-white rounded">Ke Riwayat</a>
        </div>

    </div>

  </div>
</div>
@endsection