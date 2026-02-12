@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="bg-white dark:bg-[#1e2839] py-8 md:py-12 transition-colors duration-300">
  <div class="max-w-7xl mx-auto px-4 md:px-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 items-center">
      <!-- Left Content -->
      <div class="text-center md:text-left">
        <div class="inline-block bg-[#2c3e8f] hover:bg-[#233170] dark:bg-teal-600 dark:hover:bg-teal-700 text-white text-xs font-semibold px-3 py-1 rounded-full mb-3">
          LAYANAN DIGITAL
        </div>
        <h1 class="text-3xl md:text-4xl font-bold text-[#2c3e8f] dark:text-white mb-4 leading-tight">
          SISTEM PENDAFTARAN<br>ONLINE
        </h1>
        <p class="text-gray-600 dark:text-gray-300 text-sm md:text-base leading-relaxed mb-6 px-2 md:px-0">
          Pendaftaran Online, memfasilitasi pendaftaran pelayanan rawat jalan melalui pendaftaran online.
          Pendaftaran Online dapat dilakukan minimal
          <span class="font-bold text-red-600 dark:text-red-400">SATU HARI</span>
          sebelum rencana reservasi dilakukan.
        </p>
        <!-- Search Bar / Daftar Button -->
        <div class="flex items-center justify-center md:justify-start">
          <div class="hidden md:flex items-center space-x-2 max-w-md">
            <input
                type="text"
                placeholder="Silakan Cari informasi di sini..."
                class="flex-1 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
              <button class="bg-[#2c3e8f] hover:bg-[#233170] dark:bg-teal-600 dark:hover:bg-teal-700 text-white p-3 rounded-lg transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
              </button>
          </div>
              <!-- Tombol Daftar hanya tampil di mobile -->
            <a href="{{ route('pendaftaran.pasien-baru') }}"class="md:hidden bg-[#2c3e8f] hover:bg-[#233170] dark:bg-teal-600 dark:hover:bg-teal-700 text-white font-semibold px-5 py-3 rounded-lg shadow-md transition">
              Daftar Pasien Baru
            </a>
        </div>
      </div>
      <!-- Foto slide -->
      <div class="rounded-xl overflow-hidden shadow-lg relative mt-6 md:mt-0">
        <div class="slider relative h-64 md:h-80">
          <img src="{{ asset('images/empat.jpg') }}" alt="Pendaftaran" class="w-full h-full object-cover slide opacity-100 transition-opacity duration-700">
          <img src="{{ asset('images/satu.jpg') }}" alt="Pendaftaran" class="w-full h-full object-cover slide absolute top-0 left-0 opacity-0 transition-opacity duration-700">
          <img src="{{ asset('images/dua.jpg') }}" alt="Pendaftaran" class="w-full h-full object-cover slide absolute top-0 left-0 opacity-0 transition-opacity duration-700">
          <img src="{{ asset('images/tiga.jpg') }}" alt="Pendaftaran" class="w-full h-full object-cover slide absolute top-0 left-0 opacity-0 transition-opacity duration-700">
        </div>
        <!-- Navigation Buttons -->
        <button id="prevBtn" class="absolute top-1/2 left-2 md:left-4 transform -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white w-10 h-10 md:w-12 md:h-12 rounded-full transition flex items-center justify-center">
          <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <button id="nextBtn" class="absolute top-1/2 right-2 md:right-4 transform -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white w-10 h-10 md:w-12 md:h-12 rounded-full transition flex items-center justify-center">
          <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </button>
      </div>
    </div>
  </div>
</section>

<!-- Info Cards Section -->
<section class="max-w-7xl mx-auto px-4 md:px-6 py-8">
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
    <!-- Card 1 - Important Info -->
    <div id="informasi" class="bg-white dark:bg-[#1e2839] rounded-xl border border-gray-200 dark:border-gray-700 shadow-md p-4 md:p-6 text-center transition-colors duration-300">
      <div class="flex justify-center items-center space-x-2 mb-4">
        <div class="bg-[#2c3e8f] hover:bg-[#233170] dark:bg-teal-600 dark:hover:bg-teal-700 p-2 rounded-lg">
          <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <h3 class="font-bold text-gray-900 dark:text-white text-sm md:text-base">Informasi Penting !</h3>
      </div>
      <p class="text-red-600 dark:text-red-400 font-semibold text-xs md:text-sm mb-3">
        PENDAFTARAN ONLINE RSUD BANDUNG KIWARI TIDAK DIPUNGUT BIAYA APAPUN.
      </p>
      <p class="text-gray-600 dark:text-gray-300 text-xs md:text-sm">
        Untuk pasien yang berumur di bawah 50 tahun dan menggunakan BPJS, harap menggunakan aplikasi
        <span class="text-teal-600 dark:text-teal-400 font-semibold">MOBILE JKN</span>.
      </p>
      <hr class="my-4 border-gray-300 dark:border-gray-700">
      <p class="text-xs text-gray-500 italic">*Terakhir diperbarui yyyy</p>
    </div>
    <!--card 2-->
    <div class="bg-white dark:bg-[#1e2839] rounded-xl border border-gray-200 dark:border-gray-700 shadow-md p-4 md:p-6 text-center transition-colors duration-300">
      <div class="flex justify-center items-center space-x-2 mb-4">
        <div class="bg-[#2c3e8f] hover:bg-[#233170] dark:bg-teal-600 dark:hover:bg-teal-700 p-2 rounded-lg">
          <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <h3 class="font-bold text-gray-900 dark:text-white text-sm md:text-base">Informasi Penting !</h3>
      </div>
      <p class="text-gray-600 dark:text-gray-300 text-xs md:text-sm">
        Seluruh Pasien <span class="text-pink-600 dark:text-pink-400 font-bold">POST SC</span>
        silakan langsung datang ke RS sesuai dengan tanggal surat kontrol.
      </p>
      <hr class="my-4 border-gray-300 dark:border-gray-700">
      <p class="text-xs text-gray-500 italic">*Terakhir diperbarui yyyy</p>
    </div>
    <!-- Card 3 - Doctor Schedule -->
    <div class="bg-white dark:bg-[#1e2839] rounded-xl border border-gray-200 dark:border-gray-700 shadow-md overflow-hidden transition-colors duration-300">
      <img src="{{ asset('images/dokter1.jpeg') }}" alt="Jadwal Dokter" class="w-full h-24 md:h-32 object-cover">
      <div class="p-4 md:p-6">
        <div class="flex items-center space-x-2 mb-3">
          <div class="bg-[#2c3e8f] hover:bg-[#233170] dark:bg-teal-600 dark:hover:bg-teal-700 p-2 rounded-lg">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
          </div>
          <h3 class="font-bold text-gray-900 dark:text-white text-sm md:text-base">Jadwal Dokter</h3>
        </div>
        <p class="text-gray-600 dark:text-gray-300 text-xs md:text-sm mb-4">
          Temukan jadwal praktik dokter spesialis yang Anda butuhkan.
        </p>
        <a href="{{ route('jadwal') }}"
           class="block w-full bg-[#2c3e8f] hover:bg-[#233170] dark:bg-teal-600 dark:hover:bg-teal-700 text-white py-3 rounded-lg font-bold transition text-sm md:text-base text-center">
          Lihat Jadwal Dokter
        </a>
      </div>
    </div>
  </div>
</section>

<!-- Main Content Section -->
<section class="max-w-7xl mx-auto px-4 md:px-6 py-8">
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">

    <!-- Syarat & Ketentuan -->
    <div id="syarat" class="md:col-span-2 bg-white dark:bg-[#1e2839] rounded-xl border border-gray-200 dark:border-gray-700 shadow-md p-6 md:p-8 transition-colors duration-300">
      <h2 class="text-lg md:text-xl font-bold text-[#2c3e8f] dark:text-white mb-6">
        Syarat & Ketentuan Pendaftaran Online:
      </h2>
      <ol class="list-decimal ml-4 md:ml-5 text-gray-700 dark:text-gray-300 space-y-2 text-xs md:text-sm leading-relaxed">
        <li>Pendaftaran Pasien Baru dapat dilakukan melalui Tombol "Daftar Pasien Baru" dibawah tombol Login.</li>
        <li>Seluruh Data yang diinput pasien di Pendaftaran online diluar tanggungjawab RS.</li>
        <li>Pendaftaran Online hanya dapat dilakukan minimal H-1 sampai H-7.</li>
        <li>Pembatalan Reservasi dapat dilakukan paling lambat H-1 oleh pasien melalui Menu HISTORY.</li>
        <li>Data yang telah diinput pasien direservasi online tidak dapat diubah ketika daftar ulang.</li>
        <li>Jam Pelayanan Poliklinik PAGI : 07.30 - 08.30 WIB.</li>
        <li>Jam Pelayanan Poliklinik SIANG : 12.30 - 14.00 WIB.</li>
        <li>Jam Pelayanan Poliklinik SORE : 15.00 - 17.00 WIB.</li>
        <li>Selain Poliklinik Rehab Medik dan Hemodialisis, Pasien diwajibkan untuk daftar ulang pada Anjungan Mandiri Lantai 2 Area Poliklinik.</li>
        <li>Untuk Peserta JKN, diwajibkan melakukan Verifikasi Biometrik menggunakan FRISTA atau Fingerprint.</li>
        <li>Jika Pasien/Keluarga Pasien tidak melakukan daftar ulang pada waktu yang telah ditentukan, maka pendaftaran online secara otomatis dibatalkan/gugur.</li>
        <li>Pasien Wajib membawa Kartu Tanda Pengenal (KTP), kartu berobat dan bukti pendaftaran online ketika melakukan daftar ulang.</li>
        <li>Khusus Pasien Anak Wajib membawa kartu berobat, Kartu Keluarga dan bukti pendaftaran online ketika melakukan daftar ulang.</li>
        <li>Khusus untuk pasien BPJS selain membawa bukti pendaftaran online, diwajibkan membawa dokumen rujukan BPJS dari faskes 1 saat daftar ulang.</li>
      </ol>
    </div>
    <!-- Login Form -->
  <div id="loginSection" class="mt-6 md:mt-0">
    <div class="bg-white dark:bg-[#1e2839] rounded-xl border border-gray-200 dark:border-gray-700 shadow-md p-4 md:p-6 h-fit transition-colors duration-300">
      <h3 class="text-base md:text-lg font-bold text-[#2c3e8f] dark:text-white mb-6 text-center">
        Silakan Login<br>Terlebih dahulu
      </h3>
      @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-200 rounded">
          {{ session('success') }}
        </div>
      @endif
      @if($errors->any())
        <div class="mb-4 p-3 bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-200 rounded">
          <ul>
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif
      <form id="loginForm" method="POST" action="{{ route('login') }}" class="space-y-4" novalidate data-validate-on-submit="true">
        @csrf
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Nomor Rekam Medis
          </label>
          <input
            id="noRm"
            name="no_rm"
            type="text"
            placeholder="Masukkan nomor RM Anda"
            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-teal-500"
            required
          >
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Tanggal Lahir
          </label>
          <input
            id="tglLahir"
            name="tgl_lahir"
            type="date"
            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-teal-500"
            required
          >
        </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Kode Captcha
            </label>

            <!-- CAPTCHA + REFRESH -->
            <div class="flex justify-center items-center gap-3 mb-3">
                <img src="{{ captcha_src() }}"
                alt="captcha"
                class="captcha-img w-48 h-14 object-contain border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 rounded-lg shadow-sm"
                title="Klik untuk refresh">

                <button id="refreshCaptcha" type="button"
                title="Refresh Captcha"
                class="w-10 h-10 flex items-center justify-center bg-gray-100 dark:bg-gray-600 border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-200 dark:hover:bg-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
                </button>
            </div>

            <input
                id="captchaInput"
                name="captcha"
                type="text"
                placeholder="Masukkan Kode"
                class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-teal-500"
                required
            >

            <div class="flex items-center mt-3">
                <input type="checkbox" id="remember" name="remember" class="mr-2 w-4 h-4 text-teal-600 rounded focus:ring-teal-500">
                <label for="remember" class="text-xs text-gray-600 dark:text-gray-400">Ingat Saya</label>
            </div>
        </div>

        <button
          type="submit"
          class="w-full bg-[#2c3e8f] hover:bg-[#233170] dark:bg-teal-600 dark:hover:bg-teal-700 text-white py-3 rounded-lg font-bold transition text-sm md:text-base"
        >
          LOGIN
        </button>

        <p class="text-center text-xs md:text-sm text-gray-600 dark:text-gray-400">
          Jika Belum Pernah Mendaftar,<br>Silahkan Daftar Terlebih Dahulu.
        </p>

        <a
          href="{{ route('pendaftaran.pasien-baru') }}"
          class="block w-full bg-[#2c3e8f] hover:bg-[#233170] dark:bg-teal-600 dark:hover:bg-teal-700 text-white py-3 rounded-lg font-bold transition text-center md:text-base"
        >
          DAFTAR PASIEN BARU
        </a>
      </form>

      @include('pendaftaran.validation-partial')
    </div>
  </div>
</div>
</section>

<script>
  // Clear form on successful login or error
  @if(session('success') || $errors->any())
    document.addEventListener("DOMContentLoaded", () => {
      document.getElementById("captchaInput").value = "";
    });
  @endif

  // Captcha refresh (one-click)
  document.addEventListener("DOMContentLoaded", () => {
    const refreshBtn = document.getElementById('refreshCaptcha');
    const captchaImg = document.querySelector('img.captcha-img');
    if (refreshBtn && captchaImg) {
      refreshBtn.addEventListener('click', () => {
        fetch('{{ url("/refresh-captcha") }}')
          .then(res => res.json())
          .then(data => {
            captchaImg.src = data.captcha + '?' + Date.now();
          })
          .catch(() => {});
      });

      // allow clicking the image to refresh as well
      captchaImg.addEventListener('click', () => refreshBtn.click());
    }
  });

  // Slider Script
  document.addEventListener("DOMContentLoaded", () => {
    const slides = document.querySelectorAll('.slide');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const slider = document.querySelector('.slider');
    let current = 0;
    let autoSlide;
    let isDragging = false;
    let startPos = 0;
    let currentTranslate = 0;
    let prevTranslate = 0;
    let animationID;

    function showSlide(index) {
      slides.forEach((slide, i) => {
        slide.classList.remove('opacity-100');
        slide.classList.add('opacity-0');
        if (i === index) {
          slide.classList.remove('opacity-0');
          slide.classList.add('opacity-100');
        }
      });
    }

    function nextSlide() {
      current = (current + 1) % slides.length;
      showSlide(current);
    }

    function prevSlide() {
      current = (current - 1 + slides.length) % slides.length;
      showSlide(current);
    }

    function startAutoSlide() {
      autoSlide = setInterval(nextSlide, 4000);
    }

    function resetAutoSlide() {
      clearInterval(autoSlide);
      startAutoSlide();
    }

    // Touch events for mobile
    function touchStart(event) {
      isDragging = true;
      startPos = getPositionX(event);
      clearInterval(autoSlide);
    }

    function touchMove(event) {
      if (!isDragging) return;
      const currentPosition = getPositionX(event);
      currentTranslate = prevTranslate + currentPosition - startPos;
    }

    function touchEnd() {
      isDragging = false;
      const movedBy = currentTranslate - prevTranslate;

      if (movedBy < -100 && current < slides.length - 1) {
        nextSlide();
      } else if (movedBy > 100 && current > 0) {
        prevSlide();
      }

      setPositionByIndex();
      startAutoSlide();
    }

    function getPositionX(event) {
      return event.type.includes('mouse') ? event.pageX : event.touches[0].clientX;
    }

    function setPositionByIndex() {
      currentTranslate = current * -window.innerWidth;
      prevTranslate = currentTranslate;
    }

    // Add touch event listeners
    slider.addEventListener('touchstart', touchStart, false);
    slider.addEventListener('touchmove', touchMove, false);
    slider.addEventListener('touchend', touchEnd, false);

    // Button events
    nextBtn.addEventListener('click', () => {
      nextSlide();
      resetAutoSlide();
    });

    prevBtn.addEventListener('click', () => {
      prevSlide();
      resetAutoSlide();
    });

    startAutoSlide();
  });
</script>

@endsection
