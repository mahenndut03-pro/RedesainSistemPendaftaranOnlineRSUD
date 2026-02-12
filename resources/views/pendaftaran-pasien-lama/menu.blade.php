@extends('layouts.app')

@section('content')
<div class="py-8 transition-colors duration-300">
  <div class="max-w-4xl mx-auto px-4">
    <!-- INFORMASI -->
    <div class="bg-[#00a59c] text-white font-semibold text-lg px-4 py-3 rounded-t-xl">
        Informasi !
    </div>
    <!-- BANNER MERAH -->
    <div class="bg-white dark:bg-[#1e2839] border-x border-b border-gray-200 dark:border-gray-700 text-center py-4">
        <p class="text-red-600 font-bold text-sm md:text-base">
            PENDAFTARAN ONLINE RSUD BANDUNG KIWARI TIDAK DIPUNGUT BIAYA APAPUN.
        </p>
    </div>
    
    <div class="bg-white dark:bg-[#1e2839] rounded-xl border border-gray-200 dark:border-gray-700 shadow-md p-6 md:p-8 transition-colors duration-300 mt-4 relative">
      <div class="text-center mb-3">  
        <h2 class="text-lg md:text-xl font-bold text-[#2c3e8f] dark:text-white">
            Menu Pendaftaran Online
        </h2>
        <p class="text-gray-600 dark:text-gray-300">
            Berikut dibawah ini menu pendaftaran online
        </p>
      </div>
      <!-- BACK BUTTON inside card -->
      <a href="#" onclick="window.history.back(); return false;"
        class="absolute right-6 top-6 text-white px-4 py-2 rounded-md font-semibold bg-[#2c3e8f] hover:bg-[#233170] dark:bg-teal-600 dark:hover:bg-teal-700 transition shadow-sm">
        KEMBALI
      </a>

      <!-- MENU BUTTONS -->
      <div class="grid grid-cols-2 gap-4 mb-6">
        <!-- UMUM -->
        <a href="{{ route('umum.index') }}"
          class="group flex flex-col items-center justify-center p-6 bg-[#00b4d8] hover:bg-[#0090ad] text-white rounded-xl shadow-lg transition transform hover:scale-[1.02]">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
            <path d="M1.5 8.67v8.58a3 3 0 0 0 3 3h15a3 3 0 0 0 3-3V8.67l-8.928 5.493a3 3 0 0 1-3.144 0L1.5 8.67Z" />
            <path d="M22.5 6.908V6.75a3 3 0 0 0-3-3h-15a3 3 0 0 0-3 3v.158l9.714 5.978a1.5 1.5 0 0 0 1.572 0L22.5 6.908Z" />
          </svg>
          <span class="text-base font-semibold">UMUM</span>
        </a>
        <!-- BPJS -->
        <a href="{{ route('bpjs.index') }}"
          class="group flex flex-col items-center justify-center p-6 bg-[#f59e0b] hover:bg-[#d98806] text-white rounded-xl shadow-lg transition transform hover:scale-[1.02]">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
            <path d="M18.375 2.25c-1.035 0-1.875.84-1.875 1.875v15.75c0 1.035.84 1.875 1.875 1.875h.75c1.035 0 1.875-.84 1.875-1.875V4.125c0-1.036-.84-1.875-1.875-1.875h-.75ZM9.75 8.625c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-.75a1.875 1.875 0 0 1-1.875-1.875V8.625ZM3 13.125c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v6.75c0 1.035-.84 1.875-1.875 1.875h-.75A1.875 1.875 0 0 1 3 19.875v-6.75Z" />
          </svg>
          <span class="text-base font-semibold">BPJS</span>
        </a>
      </div>
    </div>
  </div>
</div>
@endsection
