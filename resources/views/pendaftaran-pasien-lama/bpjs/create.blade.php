@extends('layouts.app')

@section('content')

<div class="py-8 transition-colors duration-300">
    <div class="max-w-4xl mx-auto px-4">
        <div class="bg-white dark:bg-[#1e2839] rounded-xl border border-gray-200 dark:border-gray-700 shadow-md p-6 md:p-8 transition-colors duration-300 relative">
            <a href="#" onclick="window.history.back(); return false;"
               class="absolute right-6 top-6 text-white px-4 py-2 rounded-md font-semibold bg-[#2c3e8f] hover:bg-[#233170] dark:bg-teal-600 dark:hover:bg-teal-700 transition shadow-sm">
                KEMBALI
            </a>
            <div class="p-4">
                <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">Fitur penambahan rujukan langsung belum diimplementasikan di versi ini. Silakan gunakan menu pendaftaran utama untuk mendaftarkan pasien (BPJS/UMUM).</p>

                <div class="flex justify-between items-center mt-4">
                    <a href="{{ route('bpjs.daftar') }}" class="bg-sky-500 hover:bg-sky-600 text-white px-6 py-3 rounded-lg shadow font-semibold transition">Ke Form BPJS</a>
                    <a href="{{ url()->previous() }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-3 rounded-lg shadow font-semibold transition">Tutup</a>
                </div>
            </div>

        </div>

    </div>
</div>

@endsection