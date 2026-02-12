@extends('layouts.app')

@section('content')

<div class="py-8 transition-colors duration-300">
    <div class="max-w-4xl mx-auto px-4">
        <!-- CARD -->
        <div class="bg-white dark:bg-[#1e2839] rounded-xl border border-gray-200 dark:border-gray-700 shadow-md p-6 md:p-8 transition-colors duration-300 relative">
            <a href="#" onclick="window.history.back(); return false;"
               class="absolute right-6 top-6 text-white px-4 py-2 rounded-md font-semibold bg-[#2c3e8f] hover:bg-[#233170] dark:bg-teal-600 dark:hover:bg-teal-700 transition shadow-sm">
                KEMBALI
            </a>
            <!-- HEADER -->

                <h1 class="text-lg font-semibold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 6H21M8 12H21M8 18H21M3 6h.01M3 12h.01M3 18h.01" />
                    </svg>
                    Cek Ketersediaan Pelayanan
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Pastikan Anda sudah mengetahui jadwal dokter yang praktek pada tanggal reservasi</p>

            <!-- FORM BODY (dummy removed) -->
            <div class="p-6 md:p-8">
                <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">Silakan gunakan tombol di bawah untuk melanjutkan ke form pendaftaran UMUM.</p>

                <!-- BUTTON FULL WIDTH -->
                <a href="{{ route('umum.create') }}"
                   class="w-full bg-sky-500 hover:bg-sky-600 text-white font-semibold py-4 rounded-lg shadow transition flex justify-center items-center gap-2 text-lg">
                    Buka Form UMUM
                    <i class="fa-solid fa-check"></i>
                </a>
            </div>
        </div>
    </div>

</div>

@endsection