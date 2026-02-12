@extends('layouts.app')

@section('content')
<div class="py-8 transition-colors duration-300">
  <div class="max-w-full sm:max-w-4xl mx-auto px-4">
    <div class="bg-white dark:bg-[#1e2839] rounded-xl border border-gray-200 dark:border-gray-700 shadow-md p-6 md:p-8 transition-colors duration-300">
      <div class="flex justify-end mb-4">
          <button type="button" 
              onclick="openRiwayatModal()" 
              class="bg-[#2c3e8f] hover:bg-[#233170] dark:bg-teal-600 dark:hover:bg-teal-700 text-white px-6 py-2 rounded-md transition">
              Riwayat Pendaftaran
          </button>
      </div>
      <h1 class="text-2xl font-bold text-center text-[#2c3e8f] dark:text-white mb-8 transition-colors duration-300">Pendaftaran Pasien Baru</h1>
      <!-- Bar Proses -->
      <div class="mb-8">
        <div class="flex items-center justify-between">
          <div class="flex items-center">
            <div class="w-8 h-8 rounded-full bg-[#2c3e8f] text-white flex items-center justify-center text-sm font-bold cursor-pointer" id="step-1-indicator" role="button" tabindex="0" onclick="tryShowStep(1)" onkeydown="if(event.key==='Enter'||event.key===' ') tryShowStep(1)">1</div>
            <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Akun</span>
          </div>
          <div class="flex-1 h-1 bg-gray-300 mx-4">
            <div class="h-1 bg-[#2c3e8f] transition-all duration-300" id="progress-1" style="width: 0%"></div>
          </div>
          <div class="flex items-center">
            <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-500 flex items-center justify-center text-sm font-bold cursor-pointer" id="step-2-indicator" role="button" tabindex="0" onclick="tryShowStep(2)" onkeydown="if(event.key==='Enter'||event.key===' ') tryShowStep(2)">2</div>
            <span class="ml-2 text-sm font-medium text-gray-500 dark:text-gray-400">Reservasi</span>
          </div>
          <div class="flex-1 h-1 bg-gray-300 mx-4">
            <div class="h-1 bg-[#2c3e8f] transition-all duration-300" id="progress-2" style="width: 0%"></div>
          </div>
          <div class="flex items-center">
            <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-500 flex items-center justify-center text-sm font-bold cursor-pointer" id="step-3-indicator" role="button" tabindex="0" onclick="tryShowStep(3)" onkeydown="if(event.key==='Enter'||event.key===' ') tryShowStep(3)">3</div>
            <span class="ml-2 text-sm font-medium text-gray-500 dark:text-gray-400">Data Diri</span>
          </div>
          <div class="flex-1 h-1 bg-gray-300 mx-4">
            <div class="h-1 bg-[#2c3e8f] transition-all duration-300" id="progress-3" style="width: 0%"></div>
          </div>
          <div class="flex items-center">
            <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-500 flex items-center justify-center text-sm font-bold cursor-pointer" id="step-4-indicator" role="button" tabindex="0" onclick="tryShowStep(4)" onkeydown="if(event.key==='Enter'||event.key===' ') tryShowStep(4)">4</div>
            <span class="ml-2 text-sm font-medium text-gray-500 dark:text-gray-400">Alamat</span>
          </div>
        </div>
      </div>

      <form id="registrationForm" method="POST" action="{{ route('pendaftaran.pasien-baru.store') }}">
        @csrf
        @include('pendaftaran.partials.step1')
        @include('pendaftaran.partials.step2')
        @include('pendaftaran.partials.step3')
        @include('pendaftaran.partials.step4')
      </form>
    </div>
  </div>
</div>

        @include('pendaftaran.konfirmasi')

@include('pendaftaran.partials.riwayat')

<!-- Select2 Styles & Configuration -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
  /* Select2 Light Mode */
  .select2-container--default .select2-selection--single {
    background-color: #ffffff;
    border-color: #d1d5db;
    color: #111827;
    height: auto;
    min-height: 2.75rem;
    padding: 0.5rem 0.75rem;
    border-radius: 0.375rem;
    box-sizing: border-box;
    display: flex;
    align-items: center;
  }
  .select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 1.5;
    color: inherit;
  }
  .select2-container--default .select2-selection--single .select2-selection__arrow b {
    border-color: #6b7280;
  }
  .select2-container--default .select2-dropdown {
    background: #ffffff;
    color: #111827;
    border: 1px solid #d1d5db;
  }
  .select2-container--default .select2-results {
    background: #ffffff;
    max-height: 260px;
  }
  .select2-container--default .select2-results__option {
    color: #111827;
  }
  .select2-container--default .select2-results__option--highlighted[aria-selected],
  .select2-container--default .select2-results__option--selected[aria-selected] {
    background-color: #e5e7eb;
    color: #111827;
  }
  .select2-container--default .select2-search--dropdown .select2-search__field {
    background: transparent;
    border: none;
    color: inherit;
    padding: 0.25rem 0.5rem;
  }

  /* Select2 Dark Mode */
  .dark .select2-container--default .select2-selection--single {
    border-color: #4b5563;
    background-color: #374151;
    color: #e5e7eb;
  }
  .dark .select2-container--default .select2-selection--single .select2-selection__arrow b {
    border-color: #d1d5db;
  }
  .dark .select2-container--default .select2-dropdown {
    background: #0f172a !important;
    border: 1px solid #374151 !important;
    color: #d1d5db;
  }
  .dark .select2-container--default .select2-results {
    background: #0f172a !important;
  }
  .dark .select2-container--default .select2-results__option {
    color: #d1d5db !important;
  }
  .dark .select2-container--default .select2-results__option--highlighted[aria-selected],
  .dark .select2-container--default .select2-results__option--selected[aria-selected] {
    background-color: #1f2937 !important;
    color: #fff !important;
  }
  .dark .select2-container--default .select2-search--dropdown .select2-search__field {
    background: transparent !important;
    border: none !important;
    color: #d1d5db !important;
  }

  /* Form Control Styles */
  .form-control {
    width: 100%;
    min-height: 2.75rem;
    padding: 0.5rem 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    background-color: #ffffff;
    color: #111827;
    box-sizing: border-box;
    font-size: 1rem;
  }
  .dark .form-control,
  .form-control.dark {
    border-color: #4b5563;
    background-color: #374151;
    color: #e5e7eb;
  }
  .form-control:focus {
    outline: none;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.15);
  }

  /* Visual validation states */
  .field-valid {
    border-color: #16a34a !important;
    box-shadow: 0 0 0 4px rgba(16,185,129,0.12) !important;
    transition: box-shadow 120ms ease, border-color 120ms ease;
  }
  .field-invalid {
    border-color: #ef4444 !important;
    box-shadow: 0 0 0 4px rgba(239,68,68,0.12) !important;
    transition: box-shadow 120ms ease, border-color 120ms ease;
  }
  /* Apply same visuals to Select2 selection boxes */
  .select2-container--default .select2-selection.field-valid {
    border-color: #16a34a !important;
    box-shadow: 0 0 0 4px rgba(16,185,129,0.12) !important;
  }
  .select2-container--default .select2-selection.field-invalid {
    border-color: #ef4444 !important;
    box-shadow: 0 0 0 4px rgba(239,68,68,0.12) !important;
  }

  /* Captcha Modal */
  .captcha-wrapper img {
    max-width: 220px;
    width: 100%;
    height: auto;
    display: block;
  }
  .captcha-refresh-btn {
    flex: 0 0 auto;
    height: 36px;
    align-self: flex-start;
  }
  .dark #captchaModal .captcha-wrapper img {
    border-color: #4b5563;
  }
</style>

@include('pendaftaran.validation-partial')

<script>
let currentStep = 1;

// ===== VALIDASI STEP 1 (AKUN) =====
function validateStep1() {
  let isValid = true;
  const step1 = document.getElementById('step-1');
  
 
  // No KTP validation will be performed after computing age (KTP wajib jika umur >= 18)
  
  // Validasi Nama Lengkap
  const namaLengkap = step1.querySelector('#nama_lengkap');
  if (namaLengkap) {
    const namaValue = namaLengkap.value.trim();
    if (!namaValue) {
      showFieldError(namaLengkap, 'Nama Lengkap wajib diisi');
      isValid = false;
    } else if (namaValue.length < 3) {
      showFieldError(namaLengkap, 'Nama Lengkap minimal 3 karakter');
      isValid = false;
    } else if (!/^[a-zA-Z\s]+$/.test(namaValue)) {
      showFieldError(namaLengkap, 'Nama hanya boleh berisi huruf dan spasi');
      isValid = false;
    } else {
      markFieldValid(namaLengkap);
    }
  }
  
  // Validasi Tempat Lahir
  const tempatLahir = step1.querySelector('#tempat_lahir');
  if (tempatLahir) {
    const tempatValue = tempatLahir.value.trim();
    if (!tempatValue) {
      showFieldError(tempatLahir, 'Tempat Lahir wajib diisi');
      isValid = false;
    } else if (tempatValue.length < 3) {
      showFieldError(tempatLahir, 'Tempat Lahir minimal 3 karakter');
      isValid = false;
    } else {
      markFieldValid(tempatLahir);
    }
  }
  
  // Validasi Tanggal Lahir
  const tanggalLahir = step1.querySelector('#tanggal_lahir');
  if (tanggalLahir) {
    const tanggalValue = tanggalLahir.value;
    if (!tanggalValue) {
      showFieldError(tanggalLahir, 'Tanggal Lahir wajib diisi');
      isValid = false;
    } else {
      const birthDate = new Date(tanggalValue);
      const today = new Date();
      let age = today.getFullYear() - birthDate.getFullYear();
      const m = today.getMonth() - birthDate.getMonth();
      if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
        age--;
      }

      if (birthDate > today) {
        showFieldError(tanggalLahir, 'Tanggal lahir tidak boleh di masa depan');
        isValid = false;
      } else if (age > 150 || age < 0) {
        showFieldError(tanggalLahir, 'Tanggal lahir tidak valid');
        isValid = false;
      } else {
        markFieldValid(tanggalLahir);

        // After validating birth date, enforce KTP requirement for adults (>=18)
        const noKtpAfter = step1.querySelector('#no_ktp');
        if (noKtpAfter) {
            const ktpVal = noKtpAfter.value.trim();
            if (age >= 18) {
                if (!ktpVal) {
                    showFieldError(noKtpAfter, 'No KTP wajib diisi untuk umur 18 tahun ke atas');
                    isValid = false;
                } else if (!/^\d+$/.test(ktpVal)) {
                    showFieldError(noKtpAfter, 'No KTP harus berupa angka');
                    isValid = false;
                } else if (ktpVal.length !== 16) {
                    showFieldError(noKtpAfter, `No KTP harus tepat 16 digit (saat ini ${ktpVal.length} digit)`);
                    isValid = false;
                } else {
                    markFieldValid(noKtpAfter);
                }
            } else {
                // Under 18: KTP optional — if provided, validate format
                if (ktpVal) {
                    if (!/^\d+$/.test(ktpVal)) {
                        showFieldError(noKtpAfter, 'No KTP harus berupa angka');
                        isValid = false;
                    } else if (ktpVal.length !== 16) {
                        showFieldError(noKtpAfter, 'No KTP harus tepat 16 digit jika diisi');
                        isValid = false;
                    } else {
                        markFieldValid(noKtpAfter);
                    }
                }
            }
        }
      }
    }
  }
  
  // Validasi Jenis Kelamin
  const jenisKelamin = step1.querySelector('#jenis_kelamin');
  if (jenisKelamin) {
    const jenisValue = jenisKelamin.value;
    if (!jenisValue) {
      showFieldError(jenisKelamin, 'Jenis Kelamin wajib dipilih');
      isValid = false;
    } else {
      markFieldValid(jenisKelamin);
    }
  }
  
  return isValid;
}

// ===== VALIDASI STEP 2 (RESERVASI) =====
function validateStep2() {
  let isValid = true;
  const step2 = document.getElementById('step-2');
  
  // Validasi Cara Bayar
  const caraBayar = step2.querySelector('#cara_bayar');
  if (caraBayar) {
    const caraBayarValue = caraBayar.value;
    if (!caraBayarValue) {
      showFieldError(caraBayar, 'Cara Bayar wajib dipilih');
      isValid = false;
    } else {
      markFieldValid(caraBayar);
    }
  }
  
  // Validasi BPJS jika cara bayar = BPJS
  if (caraBayar && caraBayar.value === 'BPJS') {
    const noBpjs = step2.querySelector('#no_bpjs');
    if (noBpjs) {
      const bpjsValue = noBpjs.value.trim();
      if (!bpjsValue) {
        showFieldError(noBpjs, 'No BPJS wajib diisi');
        isValid = false;
      } else if (!/^\d+$/.test(bpjsValue)) {
        showFieldError(noBpjs, 'No BPJS harus berupa angka');
        isValid = false;
      } else if (bpjsValue.length < 13) {
        showFieldError(noBpjs, 'No BPJS minimal 13 digit');
        isValid = false;
      } else {
        markFieldValid(noBpjs);
      }
    }
    
    const noRujukan = step2.querySelector('#no_rujukan');
    if (noRujukan) {
      const rujukanValue = noRujukan.value.trim();
      if (!rujukanValue) {
        showFieldError(noRujukan, 'No Rujukan wajib diisi');
        isValid = false;
      } else {
        markFieldValid(noRujukan);
      }
    }
  }
  
  // Validasi Poli
  const poli = step2.querySelector('#poli_id');
  if (poli) {
    const poliValue = poli.value;
    if (!poliValue) {
      showFieldError(poli, 'Poli wajib dipilih');
      isValid = false;
    } else {
      markFieldValid(poli);
    }
  }
  
  // Validasi Dokter
  const dokter = step2.querySelector('#dokter_id');
  if (dokter) {
    const dokterValue = dokter.value;
    if (!dokterValue) {
      showFieldError(dokter, 'Dokter wajib dipilih');
      isValid = false;
    } else {
      markFieldValid(dokter);
    }
  }
  
  // Validasi Tanggal Reservasi
  const tanggalReservasi = step2.querySelector('#tanggal_reservasi');
  if (tanggalReservasi) {
    const tanggalValue = tanggalReservasi.value;
    if (!tanggalValue) {
      showFieldError(tanggalReservasi, 'Tanggal Reservasi wajib diisi');
      isValid = false;
    } else {
      const reservDate = new Date(tanggalValue);
      const today = new Date();
      today.setHours(0, 0, 0, 0);
      
      if (reservDate < today) {
        showFieldError(tanggalReservasi, 'Tanggal reservasi tidak boleh di masa lalu');
        isValid = false;
      } else {
        markFieldValid(tanggalReservasi);
      }
    }
  }
  
  return isValid;
}

// ===== VALIDASI STEP 3 (DATA DIRI) =====
function validateStep3() {
  let isValid = true;
  const step3 = document.getElementById('step-3');
  
  // Validasi Telepon
  const telepon = step3.querySelector('#telepon');
  if (telepon) {
    const teleponValue = telepon.value.trim();
    if (!teleponValue) {
      showFieldError(telepon, 'Nomor Telepon wajib diisi');
      isValid = false;
    } else if (!/^[0-9+\-\s()]+$/.test(teleponValue)) {
      showFieldError(telepon, 'Format nomor telepon tidak valid');
      isValid = false;
    } else if (teleponValue.replace(/\D/g, '').length < 10) {
      showFieldError(telepon, 'Nomor telepon minimal 10 digit');
      isValid = false;
    } else {
      markFieldValid(telepon);
    }
  }
  
  // Validasi Email: wajib hanya jika ada KTP atau umur >= 18
  const email = step3.querySelector('#email');
  if (email) {
    const emailValue = email.value.trim();
    // determine if email required: check no_ktp or tanggal_lahir from step1
    const step1 = document.getElementById('step-1');
    const noKtpField = step1 ? step1.querySelector('#no_ktp') : null;
    const dobField = step1 ? step1.querySelector('#tanggal_lahir') : null;
    let ageForEmail = null;
    try {
      if (dobField && dobField.value) {
        const bd = new Date(dobField.value);
        const t = new Date();
        let a = t.getFullYear() - bd.getFullYear();
        const m = t.getMonth() - bd.getMonth();
        if (m < 0 || (m === 0 && t.getDate() < bd.getDate())) a--;
        ageForEmail = a;
      }
    } catch (e) { ageForEmail = null; }

    const requiresEmail = (noKtpField && noKtpField.value && noKtpField.value.trim() !== '') || (ageForEmail !== null && ageForEmail >= 18);

    if (requiresEmail) {
      if (!emailValue) {
        showFieldError(email, 'Email wajib diisi');
        isValid = false;
      } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailValue)) {
        showFieldError(email, 'Format email tidak valid');
        isValid = false;
      } else {
        markFieldValid(email);
      }
    } else {
      // optional: if provided, validate format; if empty, clear any errors
      if (!emailValue) {
        resetFieldState(email);
      } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailValue)) {
        showFieldError(email, 'Format email tidak valid');
        isValid = false;
      } else {
        markFieldValid(email);
      }
    }
  }
  
  // Validasi Pendidikan
  const pendidikan = step3.querySelector('#pendidikan');
  if (pendidikan) {
    const pendidikanValue = pendidikan.value;
    if (!pendidikanValue) {
      showFieldError(pendidikan, 'Pendidikan wajib dipilih');
      isValid = false;
    } else {
      markFieldValid(pendidikan);
    }
  }
  
  // Validasi Status
  const status = step3.querySelector('#status');
  if (status) {
    const statusValue = status.value;
    if (!statusValue) {
      showFieldError(status, 'Status wajib dipilih');
      isValid = false;
    } else {
      markFieldValid(status);
    }
  }
  
  // Validasi Pekerjaan
  const pekerjaan = step3.querySelector('#pekerjaan');
  if (pekerjaan) {
    const pekerjaanValue = pekerjaan.value;
    if (!pekerjaanValue) {
      showFieldError(pekerjaan, 'Pekerjaan wajib dipilih');
      isValid = false;
    } else {
      markFieldValid(pekerjaan);
    }
  }
  
  // Validasi Agama
  const agama = step3.querySelector('#agama');
  if (agama) {
    const agamaValue = agama.value;
    if (!agamaValue) {
      showFieldError(agama, 'Agama wajib dipilih');
      isValid = false;
    } else {
      markFieldValid(agama);
    }
  }
  
  // Validasi Golongan Darah
  const golDarah = step3.querySelector('#golongan_darah');
  if (golDarah) {
    const golValue = golDarah.value;
    if (!golValue) {
      showFieldError(golDarah, 'Golongan Darah wajib dipilih');
      isValid = false;
    } else {
      markFieldValid(golDarah);
    }
  }
  
  // Validasi Kewarganegaraan
  const kewarganegaraan = step3.querySelector('#kewarganegaraan');
  if (kewarganegaraan) {
    const kewarganegaraanValue = kewarganegaraan.value;
    if (!kewarganegaraanValue) {
      showFieldError(kewarganegaraan, 'Kewarganegaraan wajib dipilih');
      isValid = false;
    } else {
      markFieldValid(kewarganegaraan);
    }
  }
  
  // Validasi Bahasa Keseharian
  const bahasa = step3.querySelector('#bahasa_keseharian');
  if (bahasa) {
    const bahasaValue = bahasa.value.trim();
    if (!bahasaValue) {
      showFieldError(bahasa, 'Bahasa Keseharian wajib diisi');
      isValid = false;
    } else {
      markFieldValid(bahasa);
    }
  }
  
  // Validasi Suku
  const suku = step3.querySelector('#suku');
  if (suku) {
    const sukuValue = suku.value.trim();
    if (!sukuValue) {
      showFieldError(suku, 'Suku wajib diisi');
      isValid = false;
    } else {
      markFieldValid(suku);
    }
  }
  
  return isValid;
}

// ===== VALIDASI STEP 4 (ALAMAT) =====
function validateStep4() {
  let isValid = true;
  const step4 = document.getElementById('step-4');
  
  // Validasi Provinsi
  const provinsi = step4.querySelector('#provinsi');
  if (provinsi) {
    const provinsiValue = provinsi.value;
    if (!provinsiValue) {
      showFieldError(provinsi, 'Provinsi wajib dipilih');
      isValid = false;
    } else {
      markFieldValid(provinsi);
    }
  }
  
  // Validasi Kabupaten
  const kabupaten = step4.querySelector('#kabupaten');
  if (kabupaten) {
    const kabupatenValue = kabupaten.value;
    if (!kabupatenValue) {
      showFieldError(kabupaten, 'Kabupaten/Kota wajib dipilih');
      isValid = false;
    } else {
      markFieldValid(kabupaten);
    }
  }
  
  // Validasi Kecamatan
  const kecamatan = step4.querySelector('#kecamatan');
  if (kecamatan) {
    const kecamatanValue = kecamatan.value;
    if (!kecamatanValue) {
      showFieldError(kecamatan, 'Kecamatan wajib dipilih');
      isValid = false;
    } else {
      markFieldValid(kecamatan);
    }
  }
  
  // Validasi Kelurahan
  const kelurahan = step4.querySelector('#kelurahan');
  if (kelurahan) {
    const kelurahanValue = kelurahan.value;
    if (!kelurahanValue) {
      showFieldError(kelurahan, 'Kelurahan wajib dipilih');
      isValid = false;
    } else {
      markFieldValid(kelurahan);
    }
  }
  
  // Validasi Alamat Lengkap
  const alamat = step4.querySelector('#alamat');
  if (alamat) {
    const alamatValue = alamat.value.trim();
    if (!alamatValue) {
      showFieldError(alamat, 'Alamat Lengkap wajib diisi');
      isValid = false;
    } else if (alamatValue.length < 10) {
      showFieldError(alamat, 'Alamat minimal 10 karakter');
      isValid = false;
    } else {
      markFieldValid(alamat);
    }
  }
  
  // Validasi RT
  const rt = step4.querySelector('#rt');
  if (rt) {
    const rtValue = rt.value.trim();
    if (!rtValue) {
      showFieldError(rt, 'RT wajib diisi');
      isValid = false;
    } else if (!/^\d{1,3}$/.test(rtValue)) {
      showFieldError(rt, 'RT harus berupa angka (1-3 digit)');
      isValid = false;
    } else {
      markFieldValid(rt);
    }
  }
  
  // Validasi RW
  const rw = step4.querySelector('#rw');
  if (rw) {
    const rwValue = rw.value.trim();
    if (!rwValue) {
      showFieldError(rw, 'RW wajib diisi');
      isValid = false;
    } else if (!/^\d{1,3}$/.test(rwValue)) {
      showFieldError(rw, 'RW harus berupa angka (1-3 digit)');
      isValid = false;
    } else {
      markFieldValid(rw);
    }
  }
  
  return isValid;
}

// ===== UPDATE FUNGSI validateStep =====
function validateStep(step) {
  switch(step) {
    case 1:
      return validateStep1();
    case 2:
      return validateStep2();
    case 3:
      return validateStep3();
    case 4:
      return validateStep4();
    default:
      return true;
  }
}

function showStep(step) {
  document.querySelectorAll('.step').forEach(s => s.classList.add('hidden'));
  document.getElementById(`step-${step}`).classList.remove('hidden');

  // Update progress indicators
  for (let i = 1; i <= 4; i++) {
    const indicator = document.getElementById(`step-${i}-indicator`);
    const progress = document.getElementById(`progress-${i-1}`);
    if (i <= step) {
      indicator.classList.remove('bg-gray-300', 'text-gray-500');
      indicator.classList.add('bg-[#2c3e8f]', 'text-white');
      if (progress) progress.style.width = '100%';
    } else {
      indicator.classList.remove('bg-[#2c3e8f]', 'text-white');
      indicator.classList.add('bg-gray-300', 'text-gray-500');
      if (progress) progress.style.width = '0%';
    }
  }
}

function nextStep(step) {
  if (validateStep(step)) {
    currentStep = step + 1;
    showStep(currentStep);
  } else {
    // Scroll ke field error pertama
    const errorField = document.querySelector(`#step-${step} .border-red-500`);
    if (errorField) {
      errorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
      errorField.focus();
    }
  }
}

function prevStep(step) {
  currentStep = step - 1;
  showStep(currentStep);
}

// Toggle search input based on selected method (No KTP / Kode Booking)
function toggleSearchInput() {
  const type = document.getElementById('searchType').value;
  const input = document.getElementById('searchInput');
  const label = document.getElementById('searchLabel');

  if (!type) {
    input.value = '';
    input.setAttribute('disabled', 'disabled');
    input.setAttribute('placeholder', 'Masukkan data pencarian');
    label.innerText = 'Masukkan Data';
    return;
  }

  input.removeAttribute('disabled');
  if (type === 'no_ktp') {
    label.innerHTML = 'No KTP <span class="text-red-500">*</span>';
    input.setAttribute('placeholder', 'Masukkan Nomor KTP');
    input.setAttribute('inputmode', 'numeric');
  } else if (type === 'kode_booking') {
   label.innerHTML = 'Kode Booking <span class="text-red-500">*</span>';
    input.setAttribute('placeholder', 'Masukkan Kode Booking');
    input.removeAttribute('inputmode');
  }
}

// Close the riwayat modal
function closeRiwayatModal() {
  const m = document.getElementById('riwayatModal');
  if (m) m.classList.add('hidden');
  // hide results and clear input
  try { document.getElementById('riwayatResults').classList.add('hidden'); } catch(e){}
  try { document.getElementById('resultsContent').innerHTML = ''; } catch(e){}
  try { document.getElementById('searchInput').value = ''; } catch(e){}
  try { document.getElementById('searchType').value = ''; toggleSearchInput(); } catch(e){}
}

// Open the riwayat modal and reset its contents
function openRiwayatModal() {
  try {
    const m = document.getElementById('riwayatModal');
    if (!m) return;
    // reset search controls
    const type = document.getElementById('searchType');
    const input = document.getElementById('searchInput');
    const results = document.getElementById('riwayatResults');
    const resultsContent = document.getElementById('resultsContent');
    const notFound = document.getElementById('notFoundModal');

    if (type) { type.value = ''; }
    if (input) { input.value = ''; input.setAttribute('disabled', 'disabled'); }
    if (results) { results.classList.add('hidden'); }
    if (resultsContent) { resultsContent.innerHTML = ''; }
    if (notFound) { notFound.classList.add('hidden'); }

    // ensure placeholder/label reset
    try { toggleSearchInput(); } catch(e){}

    // show modal
    m.classList.remove('hidden');

    // focus select so user can pick a method quickly
    try { document.getElementById('searchType').focus(); } catch(e){}
  } catch (e) {
    console.warn('openRiwayatModal error', e);
  }
}

// Close the not-found informational modal
function closeNotFoundModal() {
  const m = document.getElementById('notFoundModal');
  if (m) m.classList.add('hidden');
}

// Execute search: validate input, call backend, and render results or not-found
function searchRiwayat() {
  const type = document.getElementById('searchType').value;
  const value = document.getElementById('searchInput').value;

  const tokenMeta = document.querySelector('meta[name="csrf-token"]');
  const csrf = tokenMeta ? tokenMeta.getAttribute('content') : '';

  // Prepare payload
  const payload = { search_type: type, search_value: value };

  fetch('/pendaftaran/riwayat/search', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-Requested-With': 'XMLHttpRequest',
      'X-CSRF-TOKEN': csrf
    },
    body: JSON.stringify(payload)
  })
  .then(res => res.json())
  .then(json => {
    if (!json || !json.success || !json.data || json.data.length === 0) {
      document.getElementById('notFoundModal').classList.remove('hidden');
      return;
    }

    const data = json.data || [];
    const tableBody = document.getElementById('riwayatTableBody');
    tableBody.innerHTML = '';

    // Build table rows for each result
    data.forEach((item, index) => {
      const statusClass = getStatusBadgeClass(item.status);
      const statusLabel = item.status || 'Pending';
      
      const row = document.createElement('tr');
      row.innerHTML = `
        <td>${escapeHtml(item.nama_lengkap || '-')}</td>
        <td>${escapeHtml(item.tanggal_lahir || '-')}</td>
        <td>${escapeHtml(item.jenis_kelamin === 'L' ? 'Laki-laki' : (item.jenis_kelamin === 'P' ? 'Perempuan' : item.jenis_kelamin || '-'))}</td>
        <td>${escapeHtml(item.email || '-')}</td>
        <td>${escapeHtml(item.poli || '-')}</td>
        <td>${escapeHtml(item.tanggal_reservasi || '-')}</td>
        <td>${escapeHtml(item.dokter || '-')}</td>
      <td>
          <span class="status-badge ${statusClass}">
            ${escapeHtml(statusLabel)}
          </span>
        </td>
      `;
      tableBody.appendChild(row);
    });

    // Hide empty state and show results
    document.getElementById('emptyState').classList.add('hidden');
    document.getElementById('riwayatResults').classList.remove('hidden');
  })
  .catch(err => {
    console.error('Search riwayat error', err);
    document.getElementById('notFoundModal').classList.remove('hidden');
  });
}

// Helper function to escape HTML and prevent XSS
function escapeHtml(text) {
  if (!text) return '';
  const map = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;'
  };
  return text.replace(/[&<>"']/g, m => map[m]);
}

// Helper function to return badge styling based on status
function getStatusBadgeClass(status) {
  status = (status || '').toLowerCase();
  
  if (status === 'pending' || status === 'menunggu') {
    return 'pending';
  } else if (status === 'confirmed' || status === 'terkonfirmasi' || status === 'approved') {
    return 'confirmed';
  } else if (status === 'cancelled' || status === 'batal') {
    return 'cancelled';
  } else if (status === 'completed' || status === 'selesai') {
    return 'completed';
  }
  return 'pending';
}

// Helper function untuk action buttons
function viewDetail(id) {
  if (!id) {
    alert('ID tidak ditemukan');
    return;
  }
  alert('Menampilkan detail untuk ID: ' + escapeHtml(id));
  // Implementasi: buka modal detail atau navigate ke halaman detail
}

function printReservation(kodeBooking) {
  if (!kodeBooking) {
    alert('Kode booking tidak ditemukan');
    return;
  }
  alert('Mencetak bukti untuk kode booking: ' + escapeHtml(kodeBooking));
  // Implementasi: buka halaman print atau trigger print
}

// Try to show target step but enforce required checks for previous steps
function tryShowStep(target) {
  // allow going back without checks
  if (target <= currentStep) {
    currentStep = target;
    showStep(target);
    return;
  }

  // validate each prior step
  for (let i = 1; i < target; i++) {
    if (!validateStep(i)) {
      currentStep = i;
      showStep(i);
      // Scroll ke field error pertama
      setTimeout(() => {
        const errorField = document.querySelector(`#step-${i} .border-red-500`);
        if (errorField) {
          errorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
          errorField.focus();
        }
      }, 100);
      return;
    }
  }

  // all previous steps valid
  currentStep = target;
  showStep(target);
}

function toggleBpjsFields() {
  const caraBayar = document.getElementById('cara_bayar').value;
  const bpjsFields = document.getElementById('bpjs_fields');
  const bpjsInputs = bpjsFields.querySelectorAll('input');

  if (caraBayar === 'BPJS') {
    bpjsFields.classList.remove('hidden');
    bpjsInputs.forEach(input => input.setAttribute('required', 'required'));
  } else {
    bpjsFields.classList.add('hidden');
    bpjsInputs.forEach(input => input.removeAttribute('required'));
  }
}

function loadDokter() {
  const poliId = document.getElementById('poli_id').value;
  const dokterSelect = document.getElementById('dokter_id');

  if (poliId) {
    dokterSelect.innerHTML = '<option value="">Pilih Dokter</option>';
    fetch(`{{ route('pendaftaran.pasien-baru.dokter', ':poliId') }}`.replace(':poliId', poliId))
      .then(response => response.json())
      .then(data => {
        data.forEach(dokter => {
          dokterSelect.innerHTML += `<option value="${dokter.id}">${dokter.nama}</option>`;
        });
      })
      .catch(error => {
        console.error('Error loading doctors:', error);
        // Default doctor is already added
      });
  } else {
    dokterSelect.innerHTML = '<option value="">Pilih Dokter</option>';
  }
}

function showConfirmationModal() {
  // Validate all steps (1..4) before showing the confirmation modal.
  // If any step is invalid, switch to that step and focus the first invalid field.
  for (let i = 1; i <= 4; i++) {
    if (!validateStep(i)) {
      currentStep = i;
      showStep(i);
      setTimeout(() => {
        const errorField = document.querySelector(`#step-${i} .border-red-500`);
        if (errorField) {
          errorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
          try { errorField.focus(); } catch(e){}
        }
      }, 100);
      return;
    }
  }

  const form = document.getElementById('registrationForm');
  const formData = new FormData(form);

  // helper to escape HTML
  function _escape(s) {
    if (!s) return '';
    return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
  }

  // get display text for select options safely
  function optText(id) {
    const el = document.getElementById(id);
    if (!el) return '';
    const idx = el.selectedIndex;
    return idx > -1 && el.options[idx] ? el.options[idx].text : '';
  }

  // display value or dash when empty
  function displayValue(v) {
    if (v === null || v === undefined) return '-';
    let s = '';
    try { s = (typeof v === 'string') ? v.trim() : String(v).trim(); } catch (e) { s = String(v); }
    return s ? _escape(s) : '-';
  }

  const step1 = `
    <div class="px-2 py-1 text-sm space-y-1">
      <div><strong>No KTP:</strong> ${displayValue(formData.get('no_ktp'))}</div>
      <div><strong>Nama:</strong> ${displayValue(formData.get('nama_lengkap'))}</div>
      <div><strong>Tempat/Tgl Lahir:</strong> ${displayValue(formData.get('tempat_lahir'))}, ${displayValue(formData.get('tanggal_lahir'))}</div>
      <div><strong>Jenis Kelamin:</strong> ${displayValue(formData.get('jenis_kelamin') === 'L' ? 'Laki-laki' : (formData.get('jenis_kelamin') === 'P' ? 'Perempuan' : ''))}</div>
    </div>`;

  const step2 = `
    <div class="px-2 py-1 text-sm space-y-1">
      <div><strong>Cara Bayar:</strong> ${displayValue(formData.get('cara_bayar'))}</div>
      <div><strong>Poli:</strong> ${displayValue(optText('poli_id'))}</div>
      <div><strong>Dokter:</strong> ${displayValue(optText('dokter_id'))}</div>
      <div><strong>Tanggal:</strong> ${displayValue(formData.get('tanggal_reservasi'))}</div>
      ${formData.get('cara_bayar') === 'BPJS' ? `<div><strong>No BPJS:</strong> ${displayValue(formData.get('no_bpjs'))}</div><div><strong>No Rujukan:</strong> ${displayValue(formData.get('no_rujukan'))}</div>` : ''}
    </div>`;

  const step3 = `
    <div class="px-2 py-1 text-sm space-y-1">
      <div><strong>Telepon:</strong> ${displayValue(formData.get('telepon'))}</div>
      <div><strong>Pendidikan:</strong> ${displayValue(formData.get('pendidikan'))}</div>
      <div><strong>Status:</strong> ${displayValue(formData.get('status'))}</div>
      <div><strong>Pekerjaan:</strong> ${displayValue(formData.get('pekerjaan'))}</div>
      <div><strong>Agama:</strong> ${displayValue(formData.get('agama'))}</div>
      <div><strong>Email:</strong> ${displayValue(formData.get('email'))}</div>
      <div><strong>Golongan Darah:</strong> ${displayValue(formData.get('golongan_darah'))}</div>
      <div><strong>Kewarganegaraan:</strong> ${displayValue(formData.get('kewarganegaraan'))}</div>
      <div><strong>Bahasa:</strong> ${displayValue(formData.get('bahasa_keseharian'))}</div>
      <div><strong>Suku:</strong> ${displayValue(formData.get('suku'))}</div>
    </div>`;

  const step4 = `
    <div class="px-2 py-1 text-sm space-y-1">
      <div><strong>Provinsi:</strong> ${displayValue(optText('provinsi'))}</div>
      <div><strong>Kabupaten:</strong> ${displayValue(optText('kabupaten'))}</div>
      <div><strong>Kecamatan:</strong> ${displayValue(optText('kecamatan'))}</div>
      <div><strong>Kelurahan:</strong> ${displayValue(optText('kelurahan'))}</div>
      <div><strong>Alamat:</strong> ${displayValue(formData.get('alamat'))}</div>
      <div><strong>RT/RW:</strong> ${displayValue(formData.get('rt'))}/${displayValue(formData.get('rw'))}</div>
    </div>`;

  function sectionHtml(id, title, content, open) {
    return `
      <div class="border-b">
        <button type="button" onclick="toggleSection('${id}')" class="w-full flex items-center justify-between px-4 py-3 bg-gray-100 dark:bg-[#111827] text-left">
          <span class="font-medium">${title}</span>
          <svg id="${id}-chev" class="confirmation-section-chevron w-5 h-5 transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </button>
        <div id="${id}-content" class="confirmation-section-content ${open ? '' : 'hidden'} bg-white dark:bg-[#0f172a] px-4 py-3">${content}</div>
      </div>`;
  }

  const html = `
    ${sectionHtml('section-1','Step 1 — Akun', step1, true)}
    ${sectionHtml('section-2','Step 2 — Reservasi', step2, true)}
    ${sectionHtml('section-3','Step 3 — Data Diri', step3, true)}
    ${sectionHtml('section-4','Step 4 — Alamat', step4, true)}
  `;

  document.getElementById('confirmationData').innerHTML = html;
  // show modal overlay
  document.getElementById('confirmationModal').classList.remove('hidden');
  // set all chevrons rotated (open)
  document.querySelectorAll('.confirmation-section-chevron').forEach(c => c.style.transform = 'rotate(180deg)');
}

function closeModal() {
  // hide modal overlay
  const m = document.getElementById('confirmationModal');
  if (m) m.classList.add('hidden');
}

function showApprovalModal() {
  // hide confirmation overlay and show approval modal
  const m = document.getElementById('confirmationModal');
  if (m) m.classList.add('hidden');
  document.getElementById('approvalModal').classList.remove('hidden');
}

// Toggle individual accordion section inside confirmation modal
function toggleSection(id) {
  try {
    const content = document.getElementById(id + '-content');
    const chev = document.getElementById(id + '-chev');
    if (!content) return;
    const isHidden = content.classList.contains('hidden');
    if (isHidden) {
      content.classList.remove('hidden');
      if (chev) chev.style.transform = 'rotate(180deg)';
    } else {
      content.classList.add('hidden');
      if (chev) chev.style.transform = '';
    }
  } catch (e) { console.warn(e); }
}

function closeApprovalModal() {
  document.getElementById('approvalModal').classList.add('hidden');
}

function showCaptchaModal() {
  document.getElementById('approvalModal').classList.add('hidden');
  refreshCaptcha();
  document.getElementById('captchaModal').classList.remove('hidden');
}

function closeCaptchaModal() {
  document.getElementById('captchaModal').classList.add('hidden');
}

function refreshCaptcha() {
  try {
    const img = document.getElementById('captchaImage');
    if (!img) return;
    // Append a timestamp to avoid caching
    img.src = `{{ captcha_src() }}?t=` + Date.now();
    // clear previous input
    try { document.getElementById('captchaInputModal').value = ''; } catch(e){}
  } catch (e) {
    console.warn('refreshCaptcha failed', e);
  }
}

function submitFormWithCaptcha() {
  console.log('submitFormWithCaptcha called');
  const captchaValueEl = document.getElementById('captchaInputModal');
  const captchaValue = captchaValueEl ? (captchaValueEl.value || '') : '';
  if (!captchaValue.trim()) {
    alert('Silakan masukkan kode captcha.');
    return;
  }

  const form = document.getElementById('registrationForm');
  const formData = new FormData(form);

  // Defensive: ensure BPJS-related fields and cara_bayar are sent as plain strings
  // Some Select2 or browser behaviors can produce multiple values for the same name.
  try {
    ['no_bpjs', 'no_rujukan', 'cara_bayar'].forEach(name => {
      const values = formData.getAll(name);
      if (!values || values.length === 0) return;
      // If multiple values present, join with comma; otherwise ensure scalar string
      if (values.length > 1) {
        formData.set(name, values.join(','));
      } else {
        const v = values[0];
        // if value is an object (rare), convert to string
        if (typeof v === 'object') formData.set(name, JSON.stringify(v));
        else formData.set(name, String(v));
      }
    });
  } catch (e) {
    console.warn('Form coercion warning', e);
  }

  formData.append('captcha', captchaValue);

  // DEV: log final FormData entries to console for debugging payload issues
  try {
    const obj = {};
    for (const pair of formData.entries()) {
      // collapse repeated keys into comma-separated string for readability
      if (obj[pair[0]]) obj[pair[0]] += ',' + pair[1]; else obj[pair[0]] = pair[1];
    }
    console.log('Submitting registration payload:', obj);
  } catch (e) {
    console.warn('Could not log formData', e);
  }

  // disable the button to prevent double submits
  const saveBtn = document.querySelector('#captchaModal button[onclick="submitFormWithCaptcha()"]');
  if (saveBtn) saveBtn.setAttribute('disabled', 'disabled');

  fetch(form.action, {
    method: 'POST',
    body: formData,
    headers: {
      // get CSRF token safely
      'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') || {getAttribute:() => ''}).getAttribute('content'),
      'Accept': 'application/json'
    }
  })
  .then(response => {
    // Try to parse JSON body even if status !== 200
    return response.text().then(text => {
      let data = null;
      try { data = text ? JSON.parse(text) : null; } catch (e) { data = null; }
      if (!response.ok) {
        // If server returned JSON with error details, throw it for the catch handler
        if (data && (data.error || data.message)) throw data;
        // otherwise throw a generic error with status
        throw { message: 'Network response was not ok', status: response.status, body: text };
      }
      return data;
    });
  })
  .then(data => {
    if (data && data.success) {
      document.getElementById('captchaModal').classList.add('hidden');
      // show simplified modal with booking code and keep the print url for the button
      document.getElementById('bookingCode').textContent = `Kode Booking: ${data.kode_booking}`;
      const successModal = document.getElementById('successModal');
      successModal.dataset.printUrl = data.redirect_url || '';
      document.getElementById('successModal').classList.remove('hidden');
    } else {
      alert((data && data.message) ? data.message : 'Terjadi kesalahan');
    }
  })
  .catch(err => {
    console.error('Submission error:', err);
    if (saveBtn) saveBtn.removeAttribute('disabled');
    // If server returned structured error object, show it
    // If Laravel validation returned errors, it usually comes as { errors: { field: [msg] }, message }
    if (err && err.errors) {
      // Mark server-side validation errors on respective fields (no popup)
      let firstField = null;
      Object.keys(err.errors).forEach(fieldName => {
        const messages = err.errors[fieldName];
        const field = document.querySelector(`[name="${fieldName}"]`);
        if (field) {
          showFieldError(field, Array.isArray(messages) ? messages[0] : String(messages));
          if (!firstField) firstField = field;
        }
      });
      // focus/scroll to first invalid field if present
      if (firstField) {
        firstField.scrollIntoView({ behavior: 'smooth', block: 'center' });
        try { firstField.focus(); } catch(e){}
      }
    } else if (err && (err.error || err.message)) {
      // Non-validation errors: show as a small inline error near the captcha modal input if present,
      // otherwise fall back to alert for unexpected failures.
      const msg = err.message || err.error || 'Terjadi kesalahan saat menyimpan data.';
      const form = document.getElementById('registrationForm');
      const topAlertId = 'submissionErrorBanner';
      // remove existing banner
      const existing = document.getElementById(topAlertId);
      if (existing) existing.remove();
      if (form) {
        const banner = document.createElement('div');
        banner.id = topAlertId;
        banner.className = 'mb-4 p-3 rounded text-sm bg-red-100 text-red-800';
        banner.textContent = msg;
        form.parentNode.insertBefore(banner, form);
        // auto-remove after 8s
        setTimeout(() => { try { banner.remove(); } catch(e){} }, 8000);
      } else {
        alert(msg);
      }
    } else {
      alert('Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
    }
  });
}

function closeSuccessModal() {
  document.getElementById('successModal').classList.add('hidden');
  // Reset the page so the form is empty for a new registration
  window.location.reload();
}

function printBooking() {
  try {
    const successModal = document.getElementById('successModal');
    const url = successModal ? successModal.dataset.printUrl : null;
    if (!url) {
      alert('Halaman bukti tidak tersedia.');
      return;
    }
    // Open the print page in a new tab/window
    const w = window.open(url, '_blank');
    if (!w) {
      alert('Gagal membuka tab baru. Izinkan pop-up untuk situs ini atau coba link cetak langsung.');
      return;
    }
    // Attempt to call print after the new window loads (same-origin expected)
    try {
      w.onload = function() { try { w.print(); } catch(e){} };
      // fallback: call print after a short delay
      setTimeout(function(){ try { w.print(); } catch(e){} }, 800);
    } catch (e) {
      console.warn('Tidak bisa memanggil print pada window baru', e);
    }
  } catch (e) {
    console.error('printBooking error', e);
    alert('Terjadi kesalahan saat membuka halaman cetak.');
  }
}

// Load Provinsi di awal
document.addEventListener("DOMContentLoaded", function() {
  fetch("https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json")
    .then(res => {
      if (!res.ok) throw new Error(`Failed to load provinces: ${res.status}`);
      return res.json();
    })
    .then(data => {
    data.sort((a, b) => a.name.localeCompare(b.name));
      let provinsi = document.getElementById("provinsi");
      provinsi.innerHTML = `<option value="">Pilih Provinsi</option>`;
      data.forEach(item => {
        provinsi.innerHTML += `<option value="${item.id}">${item.name}</option>`;
      });
      // If Select2 is present, (re)initialize so options show correctly
      if (window.jQuery && window.jQuery.fn.select2) {
        try { jQuery('#provinsi').select2('destroy'); } catch(e){}
        try { jQuery('#provinsi').select2({ width: '100%' }); } catch(e){}
        try { jQuery('#provinsi').trigger('change'); } catch(e){}
      }
    })
    .catch(err => {
      console.warn('loadProvinsi error', err);
    });
    
  // Minimal behavior: clear previous errors while user types or changes selects.
  // We intentionally DO NOT run per-field validation on blur/change here so
  // validation only occurs when the user clicks the per-step "Lanjut" button
  // (which calls `nextStep(step)` -> `validateStep(step)`).
  const allInputs = document.querySelectorAll('input[required], select[required], textarea[required]');
  allInputs.forEach(input => {
    // Reset visual error state when user types
    input.addEventListener('input', function() {
      try { resetFieldState(this); } catch(e){}
    });

    // For selects, clear previous error when value changes
    if (input.tagName === 'SELECT') {
      input.addEventListener('change', function() {
        try { resetFieldState(this); } catch(e){}
      });
    }
  });
  
  // Validasi khusus untuk Select2
  if (window.jQuery && window.jQuery.fn.select2) {
    // Clear previous error state when Select2 value changes; do not run full
    // step validation here so validation only happens on `Lanjut`.
    jQuery('select').on('select2:select select2:unselect', function() {
      try { resetFieldState(this); } catch(e){}
    });
  }
});

// Load Kabupaten/Kota berdasarkan Provinsi
function loadKabupaten() {
    let provId = document.getElementById("provinsi").value;
  if (!provId) {
    // no province selected - reset downstream selects and return
    document.getElementById("kabupaten").innerHTML = `<option value="">Pilih Kabupaten/Kota</option>`;
    document.getElementById("kecamatan").innerHTML = `<option value="">Pilih Kecamatan</option>`;
    document.getElementById("kelurahan").innerHTML = `<option value="">Pilih Kelurahan</option>`;
    if (window.jQuery && window.jQuery.fn.select2) {
      try { jQuery('#kabupaten').trigger('change'); } catch(e){}
      try { jQuery('#kecamatan').trigger('change'); } catch(e){}
      try { jQuery('#kelurahan').trigger('change'); } catch(e){}
    }
    return;
  }

  fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provId}.json`)
    .then(res => {
      if (!res.ok) throw new Error(`Failed to load regencies: ${res.status}`);
      return res.json();
    })
    .then(data => {
      let kab = document.getElementById("kabupaten");
      kab.innerHTML = `<option value="">Pilih Kabupaten/Kota</option>`;
      data.forEach(item => {
        kab.innerHTML += `<option value="${item.id}">${item.name}</option>`;
      });

      // reset dropdown bawah
      document.getElementById("kecamatan").innerHTML = `<option value="">Pilih Kecamatan</option>`;
      document.getElementById("kelurahan").innerHTML = `<option value="">Pilih Kelurahan</option>`;
      // If Select2 is present, reinitialize/update the kabupaten select so it shows the new options
      if (window.jQuery && window.jQuery.fn.select2) {
        try { jQuery('#kabupaten').trigger('change'); } catch(e){}
        try { jQuery('#kabupaten').select2 && jQuery('#kabupaten').select2(); } catch(e){}
      }
    })
    .catch(err => {
      console.warn('loadKabupaten error', err);
    });
}

// Load Kecamatan
function loadKecamatan() {
    let kabId = document.getElementById("kabupaten").value;
  if (!kabId) {
    document.getElementById("kecamatan").innerHTML = `<option value="">Pilih Kecamatan</option>`;
    document.getElementById("kelurahan").innerHTML = `<option value="">Pilih Kelurahan</option>`;
    if (window.jQuery && window.jQuery.fn.select2) {
      try { jQuery('#kecamatan').trigger('change'); } catch(e){}
      try { jQuery('#kelurahan').trigger('change'); } catch(e){}
    }
    return;
  }

  fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${kabId}.json`)
    .then(res => {
      if (!res.ok) throw new Error(`Failed to load districts: ${res.status}`);
      return res.json();
    })
    .then(data => {
      let kec = document.getElementById("kecamatan");
      kec.innerHTML = `<option value="">Pilih Kecamatan</option>`;
      data.forEach(item => {
        kec.innerHTML += `<option value="${item.id}">${item.name}</option>`;
      });

      document.getElementById("kelurahan").innerHTML = `<option value="">Pilih Kelurahan</option>`;
      // If Select2 is present, reinitialize/update the kecamatan select so it shows the new options
      if (window.jQuery && window.jQuery.fn.select2) {
        try { jQuery('#kecamatan').trigger('change'); } catch(e){}
        try { jQuery('#kecamatan').select2 && jQuery('#kecamatan').select2(); } catch(e){}
      }
    })
    .catch(err => {
      console.warn('loadKecamatan error', err);
    });
}

// Load Kelurahan
function loadKelurahan() {
    let kecId = document.getElementById("kecamatan").value;
  if (!kecId) {
    document.getElementById("kelurahan").innerHTML = `<option value="">Pilih Kelurahan</option>`;
    if (window.jQuery && window.jQuery.fn.select2) {
      try { jQuery('#kelurahan').trigger('change'); } catch(e){}
    }
    return;
  }

  fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/villages/${kecId}.json`)
    .then(res => {
      if (!res.ok) throw new Error(`Failed to load villages: ${res.status}`);
      return res.json();
    })
    .then(data => {
      let kel = document.getElementById("kelurahan");
      kel.innerHTML = `<option value="">Pilih Kelurahan</option>`;
      data.forEach(item => {
        kel.innerHTML += `<option value="${item.id}">${item.name}</option>`;
      });
      // If Select2 is present, reinitialize/update the kelurahan select so it shows the new options
      if (window.jQuery && window.jQuery.fn.select2) {
        try { jQuery('#kelurahan').trigger('change'); } catch(e){}
        try { jQuery('#kelurahan').select2 && jQuery('#kelurahan').select2(); } catch(e){}
      }
    })
    .catch(err => {
      console.warn('loadKelurahan error', err);
    });
}

// Initialize
showStep(1);
</script>

<!-- Select2 Libraries & Initialize -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
  try {
    if (window.jQuery && window.jQuery.fn.select2) {
      // Initialize Select2 on all main form controls
      jQuery('#poli_id, #dokter_id, #cara_bayar, #pendidikan, #pekerjaan, #provinsi, #kabupaten, #kecamatan, #kelurahan').select2({ width: '100%' });

      // Prefer the newer `loadDoctors` (exposed by the step2 partial) when present
      jQuery('#poli_id').on('change', function() {
        if (window.loadDoctors && typeof window.loadDoctors === 'function') {
          try { window.loadDoctors(); return; } catch(e) { /* fallthrough */ }
        }
        try { loadDokter(); } catch(e) {}
      });

      // Ensure wilayah API calls fire when using Select2
      jQuery('#provinsi').on('change select2:select', function() {
        loadKabupaten();
      });
      jQuery('#kabupaten').on('change select2:select', function() {
        loadKecamatan();
      });
      jQuery('#kecamatan').on('change select2:select', function() {
        loadKelurahan();
      });
    }
  } catch (e) {
    console.warn('Select2 initialization failed', e);
  }
  // If Select2 is not present, attach a plain change listener so loadDokter still works
  if (!(window.jQuery && window.jQuery.fn && window.jQuery.fn.select2)) {
    const poli = document.getElementById('poli_id');
    if (poli) poli.addEventListener('change', loadDokter);
  }
});
</script>
@endsection
