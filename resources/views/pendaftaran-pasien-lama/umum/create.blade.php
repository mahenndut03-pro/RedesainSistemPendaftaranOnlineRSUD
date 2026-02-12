@extends('layouts.app')

@section('content')

<div class="py-8 transition-colors duration-300">
    <div class="max-w-5xl mx-auto">
        <!-- CARD FORM -->
        <div class="bg-white dark:bg-[#1e2839] rounded-xl border border-gray-200 dark:border-gray-700 shadow-md p-6 md:p-8 transition-colors duration-300 relative">

            <a href="#" onclick="window.history.back(); return false;"
               class="absolute right-6 top-6 text-white px-4 py-2 rounded-md font-semibold bg-[#2c3e8f] hover:bg-[#233170] dark:bg-teal-600 dark:hover:bg-teal-700 transition shadow-sm">
                KEMBALI
            </a>

            <h2 class="text-lg font-bold text-gray-800 dark:text-white flex items-center gap-2 mb-1">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8 6H21M8 12H21M8 18H21M3 6h.01M3 12h.01M3 18h.01" />
                </svg>
                Form Pendaftaran Online
            </h2>

            <p class="text-sm text-gray-600 dark:text-gray-300 mb-6">
                Jika nama dokter tidak tampil setelah pilih Poliklinik, kemungkinan <span class="font-bold">KUOTA SUDAH HABIS</span>
                atau dokter tidak praktek pada tanggal tersebut.
            </p>
            @if($errors->any())
                <div class="mb-4">
                    <div class="text-sm text-red-700 bg-red-50 border border-red-100 p-3 rounded">
                        <strong>Terjadi kesalahan:</strong>
                        <ul class="mt-2 list-disc list-inside">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
            @if(session('success'))
                <div class="mb-4">
                    <div class="text-sm text-green-700 bg-green-50 border border-green-100 p-3 rounded">{{ session('success') }}</div>
                </div>
            @endif
            <!-- FORM -->
            <form action="{{ route('umum.create.post') }}" method="POST">
                @csrf

                <!-- NAMA & KTP (hidden when user already logged in) -->
                @if(!isset($currentPatient))
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block font-semibold text-gray-700 dark:text-gray-200 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" id="nama_lengkap" name="nama_lengkap" required class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#243447] text-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Nama lengkap" value="{{ old('nama_lengkap', $currentPatient->nama_lengkap ?? '') }}">
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 dark:text-gray-200 mb-2">No. KTP</label>
                        <input type="text" id="no_ktp" name="no_ktp" class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#243447] text-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Nomor KTP (opsional)" value="{{ old('no_ktp', $currentPatient->no_ktp ?? '') }}">
                    </div>
                </div>
                @else
                {{-- send values as hidden inputs so server validation still receives them --}}
                <input type="hidden" name="nama_lengkap" value="{{ old('nama_lengkap', $currentPatient->nama_lengkap ?? '') }}">
                <input type="hidden" name="no_ktp" value="{{ old('no_ktp', $currentPatient->no_ktp ?? '') }}">
                @endif

                <!-- TANGGAL RESERVASI -->
                <div class="mb-6">
                    <label class="block font-semibold text-gray-700 dark:text-gray-200 mb-2">
                        Tanggal Reservasi <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="tanggal_reservasi" name="tanggal_reservasi" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#243447] text-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 outline-none"
                        value="{{ old('tanggal_reservasi','') }}">
                </div>

                <!-- ROW OPTIONS -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- PILIH POLIKLINIK -->
                    <div>
                        <label class="block font-semibold text-gray-700 dark:text-gray-200 mb-2">
                            Pilih Poliklinik <span class="text-red-500">*</span>
                        </label>
                        <select name="poli_id" id="poli_id" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#243447] text-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value=""> Pilih Poliklinik</option>
                            {{-- akan diisi via AJAX berdasarkan tanggal yang dipilih --}}
                        </select>
                    </div>

                    <!-- PILIH DOKTER -->
                    <div>
                        <label class="block font-semibold text-gray-700 dark:text-gray-200 mb-2">
                            Pilih Dokter <span class="text-red-500">*</span>
                        </label>
                        <select name="dokter_id" id="dokter_id" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#243447] text-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value=""> Pilih Dokter</option>
                            @foreach($doctors ?? [] as $d)
                                <option value="{{ $d->id }}" data-poli="{{ $d->poli_id }}">{{ $d->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <!-- PEMBAYARAN -->
                <div class="mt-6 mb-6">
                    <label class="block font-semibold text-gray-700 dark:text-gray-200 mb-2">
                        Pembayaran <span class="text-red-500">*</span>
                    </label>
                    <input type="text" value="UMUM" readonly
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-[#2c3a4d] text-gray-700 dark:text-gray-100">
                </div>

                <!-- TELEPON (hidden when user already logged in) -->
                @if(!isset($currentPatient))
                <div class="mb-8">
                    <label class="block font-semibold text-gray-700 dark:text-gray-200 mb-2">
                        Nomor Telepon <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="telepon" name="telepon" required placeholder="Masukan Nomor Telepon aktif yang dapat dihubungi."
                        value="{{ old('telepon','') }}"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#243447] text-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                @else
                <input type="hidden" id="telepon" name="telepon" value="{{ old('telepon', $currentPatient->telepon ?? '') }}">
                @endif

                <!-- DAFTARKAN BTN FULL WIDTH -->
                <button type="submit" id="daftarkanBtn" class="w-full bg-sky-500 hover:bg-sky-600 text-white font-semibold px-6 py-4 rounded-lg shadow transition flex justify-center items-center gap-2">
                    DAFTARKAN
                </button>

            </form>

            @include('pendaftaran.validation-partial')

    <script>
         // Validate minimal required fields for the UMUM registration page
        document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('daftarkanBtn');
        if (!btn) return;
        btn.addEventListener('click', function(e) {
            // simple client-side checks mirroring server-side requirements
            const form = this.closest('form');
            if (!form) return;
            let firstInvalid = null;

            const tanggal = form.querySelector('#tanggal_reservasi');
            const poli = form.querySelector('#poli_id');
            const dokter = form.querySelector('#dokter_id');
            const telepon = form.querySelector('#telepon');

            // reset previous errors
            [tanggal, poli, dokter, telepon].forEach(f => { try { if (f) resetFieldState(f); } catch(e){} });

            if (tanggal && (!tanggal.value || tanggal.value.trim() === '')) { showFieldError(tanggal, 'Tanggal Reservasi wajib diisi'); if (!firstInvalid) firstInvalid = tanggal; }
            if (poli && (!poli.value || poli.value.trim() === '')) { showFieldError(poli, 'Poliklinik wajib dipilih'); if (!firstInvalid) firstInvalid = poli; }
            if (dokter && (!dokter.value || dokter.value.trim() === '')) { showFieldError(dokter, 'Dokter wajib dipilih'); if (!firstInvalid) firstInvalid = dokter; }
            // Only validate telepon when input is required (i.e. visible for anonymous users)
            if (telepon && telepon.hasAttribute('required')) {
                const t = telepon.value ? telepon.value.trim() : '';
                if (!t) { showFieldError(telepon, 'Nomor Telepon wajib diisi'); if (!firstInvalid) firstInvalid = telepon; }
                else if (!/^[0-9+\-\s()]+$/.test(t) || t.replace(/\D/g,'').length < 10) { showFieldError(telepon, 'Format nomor telepon tidak valid'); if (!firstInvalid) firstInvalid = telepon; }
            }

            if (firstInvalid) {
                e.preventDefault();
                try { firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' }); firstInvalid.focus(); } catch(e){}
                return false;
            }

            // ensure the form is submitted explicitly (guards against other handlers)
            try {
                form.submit();
            } catch (ex) {
                // fallback: allow default behavior
            }
            // prevent double handling
            e.preventDefault();
            return false;
        });
    });
    </script>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    const poliSelect = document.getElementById('poli_id');
    const dokterSelect = document.getElementById('dokter_id');
    const tanggalInput = document.getElementById('tanggal_reservasi');
    if (!poliSelect || !dokterSelect || !tanggalInput) return;

    async function loadPolisByDate(date){
        poliSelect.innerHTML = '<option value=""> Pilih Poliklinik</option>';
        dokterSelect.innerHTML = '<option value=""> Pilih Dokter</option>';
        if (!date) return;
        const url = '{{ url('/api/jadwal/date') }}' + '/' + encodeURIComponent(date) + '/polis';
        try {
            const res = await fetch(url);
            if (!res.ok) throw new Error('HTTP '+res.status);
            const data = await res.json();
            data.forEach(c => {
                const el = document.createElement('option');
                el.value = c.id;
                el.textContent = c.nama_poli;
                poliSelect.appendChild(el);
            });
        } catch (e) {
            console.error('Gagal memuat poli berdasarkan tanggal', e);
        }
    }

    async function loadDoctors(){
        const poli = poliSelect.value;
        dokterSelect.innerHTML = '<option value=""> Pilih Dokter</option>';
        if (!poli) return; // only load when poli selected
        const tanggal = tanggalInput.value;
        if (!tanggal) {
            dokterSelect.innerHTML = '<option value="">Pilih tanggal terlebih dahulu</option>';
            return;
        }
        const url = '{{ route('api.dokter') }}' + '?poli_id='+encodeURIComponent(poli) + '&tanggal=' + encodeURIComponent(tanggal) + '&cara_bayar=UMUM';
        try {
            const res = await fetch(url);
            if (!res.ok) throw new Error('HTTP '+res.status);
            const data = await res.json();
            data.forEach(d => {
                const el = document.createElement('option');
                el.value = d.id;
                el.textContent = d.nama;
                dokterSelect.appendChild(el);
            });
        } catch (e) {
            console.error('Gagal memuat dokter', e);
        }
    }

    poliSelect.addEventListener('change', loadDoctors);

    tanggalInput.addEventListener('change', function () {
        loadPolisByDate(this.value).then(function(){
            // after polis loaded, refresh doctors for selected poli (same as step2 behavior)
            loadDoctors();
        });
    });

    // load on page ready if date prefilled
    if (tanggalInput.value) loadPolisByDate(tanggalInput.value);
});
</script>
@endpush