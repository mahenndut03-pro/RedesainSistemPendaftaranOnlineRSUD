@extends('layouts.app')

@section('content')
<div class="py-8 transition-colors duration-300">
  <div class="max-w-4xl mx-auto px-4">
    <div class="bg-white dark:bg-[#1e2839] rounded-xl border border-gray-200 dark:border-gray-700 shadow-md p-6 md:p-8 transition-colors duration-300 relative">
      <div class="text-center mb-3">
        <h2 class="text-lg md:text-xl font-bold text-[#2c3e8f] dark:text-white">
            Menu Pendaftaran Online
        </h2>
        <p class="text-gray-600 dark:text-gray-300">
            Berikut dibawah ini menu pendaftaran online
        </p>
    </div>
      <!-- MENU BUTTONS -->
      <div class="grid grid-cols-2 gap-4 mb-6">
        <!-- REGISTRASI -->
        <a href="{{ route('pendaftaran-pasien-lama.menu') }}"
          class="group flex flex-col items-center justify-center p-6 bg-[#2c3e8f] hover:bg-[#233170] dark:bg-teal-600 dark:hover:bg-teal-700 text-white rounded-xl shadow-lg transition transform hover:scale-[1.02]">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
            <path d="M1.5 8.67v8.58a3 3 0 0 0 3 3h15a3 3 0 0 0 3-3V8.67l-8.928 5.493a3 3 0 0 1-3.144 0L1.5 8.67Z" />
            <path d="M22.5 6.908V6.75a3 3 0 0 0-3-3h-15a3 3 0 0 0-3 3v.158l9.714 5.978a1.5 1.5 0 0 0 1.572 0L22.5 6.908Z" />
        </svg>
          <span class="text-base font-semibold">REGISTRASI</span>
        </a>
        <!-- HISTORY -->
        <a href="{{ route('pendaftaran-pasien-lama.history') }}"
          class="group flex flex-col items-center justify-center p-6 bg-[#2c3e8f] hover:bg-[#233170] dark:bg-teal-600 dark:hover:bg-teal-700 text-white rounded-xl shadow-lg transition transform hover:scale-[1.02]">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
            <path d="M18.375 2.25c-1.035 0-1.875.84-1.875 1.875v15.75c0 1.035.84 1.875 1.875 1.875h.75c1.035 0 1.875-.84 1.875-1.875V4.125c0-1.036-.84-1.875-1.875-1.875h-.75ZM9.75 8.625c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-.75a1.875 1.875 0 0 1-1.875-1.875V8.625ZM3 13.125c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v6.75c0 1.035-.84 1.875-1.875 1.875h-.75A1.875 1.875 0 0 1 3 19.875v-6.75Z" />
          </svg>
          <span class="text-base font-semibold">HISTORY</span>
        </a>
      </div>
    </div>

  </div>
</div>
@endsection