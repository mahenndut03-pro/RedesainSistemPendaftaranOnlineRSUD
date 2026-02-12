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
                Konfirmasi Data Reservasi
            </h1>

            @if(session('success'))
                <div class="mt-4 p-3 rounded bg-green-100 text-green-800">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="mt-4 p-3 rounded bg-red-50 text-red-800">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                Berikut dibawah ini data reservasi yang akan anda lakukan, Klik tombol proses dan SETUJUI untuk melanjutkan proses reservasi pelayanan di RSUD Bandung Kiwari
            </p>

            <!-- CONTENT -->
            <div class="p-6 md:p-8">

                <table class="w-full text-sm text-gray-700 dark:text-gray-200">
                    <tbody>
                        <tr class="border-b dark:border-gray-700">
                            <td class="py-3 font-semibold">No. Rekam Medis / Nama Pasien</td>
                            <td class="py-3">: {{ $reservasi ? ($reservasi->pasien->nama_lengkap ?? '-') : '-' }}</td>
                        </tr>
                        <tr class="border-b dark:border-gray-700">
                            <td class="py-3 font-semibold">Hari, Tanggal Reservasi</td>
                            <td class="py-3">: {{ $reservasi ? (\Carbon\Carbon::parse($reservasi->tanggal_reservasi)->translatedFormat('l, d-m-Y')) : '-' }}</td>
                        </tr>
                        <tr class="border-b dark:border-gray-700">
                            <td class="py-3 font-semibold">Poliklinik</td>
                            <td class="py-3">: {{ $reservasi ? ($reservasi->poli->nama_poli ?? '-') : '-' }}</td>
                        </tr>
                        <tr class="border-b dark:border-gray-700">
                            <td class="py-3 font-semibold">Dokter</td>
                            <td class="py-3">: {{ $reservasi ? ($reservasi->dokter->nama ?? '-') : '-' }}</td>
                        </tr>
                        <tr class="border-b dark:border-gray-700">
                            <td class="py-3 font-semibold">Cara Bayar</td>
                            <td class="py-3">: {{ $reservasi ? ($reservasi->cara_bayar ?? 'BPJS') : '-' }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 font-semibold">Telepon</td>
                            <td class="py-3">: {{ $reservasi ? ($reservasi->pasien->telepon ?? '-') : '-' }}</td>
                        </tr>
                    </tbody>
                </table>

                <!-- BUTTON -->
                <form id="confirmFormBpjs" method="POST" action="{{ route('bpjs.konfirmasi.post') }}">
                    @csrf
                    <input type="hidden" name="kode_booking" value="{{ $reservasi->kode_booking ?? '' }}" />
                    <input type="hidden" name="nama_lengkap" value="{{ $reservasi->pasien->nama_lengkap ?? '' }}" />
                    <input type="hidden" name="telepon" value="{{ $reservasi->pasien->telepon ?? '' }}" />

                    <button type="button" id="openPopup"
                        class="w-full bg-sky-500 hover:bg-sky-600 text-white font-semibold py-4 rounded-lg shadow transition flex justify-center items-center gap-2 text-lg mt-6">
                        PROSES
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </form>

            </div>
        </div>
    </div>

</div>


<!-- ================= POPUP SYARAT & KETENTUAN ================= -->
<div id="popup"
    class="fixed inset-0 bg-black/50 backdrop-blur-md flex items-center justify-center z-50 hidden transition">

    <div class="w-[90%] md:w-[60%] lg:w-[45%]
        bg-blue-600 dark:bg-[#1e2839]
        text-white dark:text-gray-200
        rounded-xl shadow-xl p-6 max-h-[80vh] overflow-y-auto transition">

        <h2 class="text-xl font-bold mb-4">Syarat dan Ketentuan</h2>

        <ol class="list-decimal pl-5 space-y-2 text-sm">
            <li>Seluruh Data yang diinput pasien di reservasi online diluar tanggungjawab RS</li>
            <li>Hanya Pasien Lama (RSUD BANDUNG KIWARI) yang sudah terdaftar di sistem kami, yang dapat melakukan reservasi online.</li>
            <li>Pendaftaran Online hanya dapat dilakukan minimal H-1 sampai H-7</li>
            <li>Pembatalan Reservasi dapat dilakukan paling lambat H-1</li>
            <li>Pendaftaran online digunakan hanya untuk mendapatkan nomor antrian Skrining (Berlaku Selama Pandemi COVID-19)</li>
            <li>Data yang telah diinput pasien direservasi online tidak dapat diubah ketika daftar ulang.</li>
            <li>Daftar Ulang Pendaftaran Online harus dilakukan sebelum skrining dimulai.</li>
            <li>Jam Pelayanan Poliklinik PAGI : 07.30 – 08.30 WIB</li>
            <li>Jam Pelayanan Poliklinik SIANG : 12.30 – 14.00 WIB</li>
            <li>Jam Pelayanan Poliklinik SORE : 15.00 – 17.00 WIB</li>
            <li>Pasien/Keluarga Pasien diwajibkan untuk daftar ulang untuk mendapatkan nomor antrian pendaftaran Rawat Jalan.</li>
            <li>Jika Pasien/Keluarga Pasien tidak melakukan daftar ulang pada waktu yang telah ditentukan, maka pendaftaran online secara otomatis dibatalkan/gugur.</li>
            <li>Pasien Wajib membawa Kartu Tanda Pengenal (KTP), kartu berobat dan bukti pendaftaran online ketika melakukan daftar ulang.</li>
            <li>Khusus untuk pasien BPJS selain membawa bukti pendaftaran online, diwajibkan membawa dokumen rujukan BPJS dari faskes 1 atau Surat Kontrol untuk Peserta Kontrol, saat daftar ulang.</li>
        </ol>

        <div class="flex justify-between mt-6">
            <button onclick="closePopup()"
                class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold">
                SAYA TIDAK SETUJU
            </button>

            <button onclick="showConfirmPopup()"
                class="px-6 py-3 bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-bold rounded-lg">
                SAYA SETUJU
            </button>
        </div>

    </div>
</div>



<!-- ================= POPUP KONFIRMASI ================= -->
<div id="confirmPopup"
    class="fixed inset-0 bg-black/40 backdrop-blur-md flex items-center justify-center z-50 hidden">

    <div class="bg-white dark:bg-[#1e2839] text-center p-8 rounded-xl shadow-xl w-[85%] md:w-[40%]">

        <div class="text-yellow-500 text-6xl mb-4">⚠</div>

        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-2">
            Selesaikan Proses Pendaftaran Online?
        </h2>

        <p class="text-sm text-gray-600 dark:text-gray-300 mb-6">
            Simpan untuk menyelesaikan pendaftaran online
        </p>

        <div class="flex justify-center gap-3">
            <button onclick="closeConfirmPopup()"
                class="px-6 py-3 bg-gray-300 hover:bg-gray-400 text-gray-900 rounded-lg font-semibold">
                Cancel
            </button>

            <button onclick="showSuccessPopup()"
                class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold">
                OK
            </button>
        </div>

    </div>
</div>



<!-- ================= POPUP SUKSES ================= -->
<div id="successPopup"
    class="fixed inset-0 bg-black/40 backdrop-blur-md flex items-center justify-center z-50 hidden">

    <div class="bg-white dark:bg-[#1e2839] text-center p-8 rounded-xl shadow-xl w-[85%] md:w-[40%]">

        <div class="text-green-500 text-6xl mb-4">✔</div>

        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-2">
            Berhasil!
        </h2>

        <p class="text-sm text-gray-600 dark:text-gray-300 mb-6">
            Pendaftaran Online Berhasil dilakukan!
        </p>

        <button onclick="window.location.href='{{ route('bpjs.menu-verifikasi') }}'"
            class="px-6 py-3 bg-sky-500 hover:bg-sky-600 text-white rounded-lg font-semibold">
            OK!
        </button>

    </div>
</div>



<!-- ================= JS CONTROL ================= -->
<script>
    const popup = document.getElementById("popup");
    const confirmPopup = document.getElementById("confirmPopup");
    const successPopup = document.getElementById("successPopup");

    const openBtn = document.getElementById("openPopup");
    const form = document.getElementById('confirmFormBpjs');

    if (form) {
        form._confirmed = false;
        form.addEventListener('submit', function(e) {
            if (!form._confirmed) {
                e.preventDefault();
                popup.classList.remove('hidden');
            }
        });
    }

    if (openBtn) {
        openBtn.addEventListener("click", (e) => {
            e.preventDefault();
            popup.classList.remove("hidden");
        });
    }

    function closePopup() {
        popup.classList.add("hidden");
    }

    function showConfirmPopup() {
        popup.classList.add("hidden");
        confirmPopup.classList.remove("hidden");
    }

    function closeConfirmPopup() {
        confirmPopup.classList.add("hidden");
    }

    function showSuccessPopup() {
        confirmPopup.classList.add("hidden");
        // submit BPJS confirmation to server for strict matching
        const formEl = document.getElementById('confirmFormBpjs');
        if (formEl) {
            formEl._confirmed = true;
            formEl.submit();
            return;
        }
        successPopup.classList.remove("hidden");
    }
</script>

@endsection